<div class="panel panel-headline">
    <div class="panel-body">
        <div class="row">
            <!--ส่วนบน-->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="xCNLabelMNGTitle"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPTitleDetail')?></label>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="xCNPanelHD" >
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="xCNPanelHDTitle xCNPanelHeadColor">
                                <span style="color: #FFF; padding: 15px;"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPDeteilPRB')?></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="xCNPanelHDDetail row" style="padding: 15px;">
                                <div class="col-lg-3">
                                    <label class="xCNLabelFrm"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPBranchForm')?></label><br>
                                    <label class="xCNLabelFrm"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPBranchDocNo')?></label><br>
                                    <label class="xCNLabelFrm"><?=language('document/managedocpurchaseorder/managedocpurchaseorder','tMNPBranchDate')?></label><br>
                                </div>
                                <div class="col-lg-9" style="border-left: 1px solid #d7d7d7;">
                                    <input class="form-control xCNHide" id="oetMNGBchCode" name="oetMNGBchCode" value="<?=$aGetDetailHD[0]['FTBchCode']?>" >
                                    <label class="xCNLabelFrm"><?=$aGetDetailHD[0]['FTBchName']?></label><br>
                                    <label class="xCNLabelFrm"><?=$aGetDetailHD[0]['FTXphDocNo']?></label><br>
                                    <label class="xCNLabelFrm"><?=date('d/m/Y H:i:s',strtotime($aGetDetailHD[0]['FDXphDocDate']))?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--ส่วนค้นหา-->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row" style="margin-top:20px;">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('common/main/main','tSearch')?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvFindSearchPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                                <span class="input-group-btn">
                                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvFindSearchPdtHTML()">
                                        <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-lg-8 text-right">
                        <label class="xCNLabelFrm"></label>
                        <div class="form-group">
                            <button class="btn" type="button" style="background-color: #D4D4D4; color: #000000;" onclick="JSvMNGCalculateMoveToPRS();"><?= language('common/main/main', 'ขอซื้อทั้งหมด') ?></button>
                            <button class="btn" type="button" style="background-color: #D4D4D4; color: #000000;" onclick="JSvMNGCallPageList();"><?= language('common/main/main', 'tBack') ?></button>
                            <button class="btn xCNBTNSubSave" type="button" style="color: white;"  onclick="JSvMNGSaveCustomByPDT();"><?= language('common/main/main', 'tSave') ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!--ส่วนตาราง-->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div id="odvPanelMNGPDT"></div>
            </div>

        </div>
    </div>
</div>

<script>
    //โหลด PDTTemp
    JSvMNGCallPagePDTTemp();
    function JSvMNGCallPagePDTTemp(){
        $.ajax({
            type    : "POST",
            url     : "docMngDocPreOrdBPDTTemp",
            data    : { 
                'ptDocumentNumber'  : '<?=$aGetDetailHD[0]['FTXphDocNo']?>' , 
                'ptBchDocRef'       : '<?=$aGetDetailHD[0]['FTBchCode']?>' ,
                'tMNGTypeDocument'  : $('#ohdMNGTypeDocument').val()
            },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $("#odvPanelMNGPDT").html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
</script>