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
                            onkeyup="Javascript:if(event.keyCode==13) JSxMNPLoadTableImportDoc()"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button type="button" class="btn xCNBtnDateTime" onclick="JSxMNPLoadTableImportDoc()">
                                <img class="xCNIconSearch">
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <!--ค้นหาขั้นสูง-->
                <a id="obtMNPPOAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;"><?=language('common/main/main', 'tAdvanceSearch'); ?></a>
        
                <!--ล้างข้อมูลค้นหา-->
                <a class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;" onclick="JSxMNPClearSearchData()"><?=language('common/main/main', 'tClearSearch'); ?></a>
            </div>
        </div>

        <!--ค้นหาขั้นสูง-->
        <div class="hidden" id="odvMNPPOAdvanceSearchContainer" style="margin-bottom:20px;">
            <div class="row">
                <!-- From Search Advanced  Branch -->
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/deliveryorder/deliveryorder','tDOAdvSearchBranch'); ?></label>
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
                            <input class="form-control xCNHide" type="text" id="oetMNPPOAdvSearchBchCodeFrom" name="oetMNPPOAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                            <input
                                class="form-control xWPointerEventNone"
                                type="text"
                                id="oetMNPPOAdvSearchBchNameFrom"
                                name="oetMNPPOAdvSearchBchNameFrom"
                                placeholder="<?=language('document/deliveryorder/deliveryorder','tDOAdvSearchFrom'); ?>"
                                readonly
                                value="<?= $tBCHName; ?>"
                            >
                            <span class="input-group-btn">
                                <button id="obtMNPPOAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="form-group">
                    <label class="xCNLabelFrm"><?=language('document/deliveryorder/deliveryorder','tDOAdvSearchTo'); ?></label>
                        <div class="input-group">
                            <input class="form-control xCNHide" id="oetMNPPOAdvSearchBchCodeTo"name="oetMNPPOAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                            <input
                                class="form-control xWPointerEventNone"
                                type="text"
                                id="oetMNPPOAdvSearchBchNameTo"
                                name="oetMNPPOAdvSearchBchNameTo"
                                placeholder="<?=language('document/deliveryorder/deliveryorder','tDOAdvSearchTo'); ?>"
                                readonly
                                value="<?= $tBCHName; ?>"
                            >
                            <span class="input-group-btn">
                                <button id="obtMNPPOAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                            </span>
                        </div>
                    </div>
                </div>
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
                                <button id="obtMNPBrowseSplFrom" type="button" class="btn xCNBtnBrowseAddOn">
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
                                <button id="obtMNPBrowseSplTo" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img src="<?=base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- From Search Advanced Status Doc -->
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/purchaseorder/purchaseorder','tPOAdvSearchStaDoc'); ?></label>
                        <select class="selectpicker form-control" id="ocmMNPPOAdvSearchStaDoc" name="ocmMNPPOAdvSearchStaDoc">
                            <option value='0'> <?=language('common/main/main', 'tStaDocAll'); ?> </option>
                            <option value='1'> <?=language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPStatusWait'); ?> </option>
                            <option value='2'> <?=language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPStatusConfrimAndWaitAprove'); ?> </option>
                            <option value='3'> <?=language('document/managedocpurchaseorder/managedocpurchaseorder', 'tMNPStatusAprove'); ?> </option>
                        </select>
                    </div>
                </div>
                <!-- Button Form Search Advanced -->
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="form-group" style="width:60%;">
                        <label class="xCNLabelFrm">&nbsp;</label>
                        <button id="obtMNPPOAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?=language('common/main/main', 'tSearch'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-heading"></div>
    <div class="panel-body">
		<section id="ostContentMNPImport"></section>
	</div>
</div>

<?php include('script/jAdvancedSearch.php')?>
