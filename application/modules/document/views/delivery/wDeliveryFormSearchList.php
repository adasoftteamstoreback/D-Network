<div class="panel panel-headline">
    <div class="panel-heading">
        <div class="row">
            <!--ค้นหาธรรมดา-->
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInputWithoutSingleQuote"
                            type="text"
                            id="oetSearchAll"
                            name="oetSearchAll"
                            placeholder="<?=language('document/adjuststock/adjuststock','tASTFillTextSearch')?>"
                            onkeyup="Javascript:if(event.keyCode==13) JSvDLVCallPageDataTable()"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button type="button" class="btn xCNBtnDateTime" onclick="JSvDLVCallPageDataTable()">
                                <img class="xCNIconSearch">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <!--ค้นหาขั้นสูง-->
            <a id="oahDLVAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;"><?=language('common/main/main', 'tAdvanceSearch'); ?></a>

            <!--ล้างข้อมูลค้นหา-->
            <a class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;" onclick="JSxDLVClearAdvSearchData()"><?=language('common/main/main', 'tClearSearch'); ?></a>
        </div>

        <!--ค้นหาขั้นสูง-->
        <div id="odvDLVAdvanceSearchContainer" class="row hidden" style="margin-bottom:20px;">
            <div class="col-xs-12 col-md-6 col-lg-6">
                <div class="row">

                    <div class="col-lg-6 col-md-6 col-xs-6">
                        <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTAdvSearchBranch'); ?></label>
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
                                <input class="form-control xCNHide" id="oetDLVFrmBchCode" name="oetDLVFrmBchCode" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input 
                                class="form-control xWPointerEventNone" 
                                type="text" id="oetDLVFrmBchName" 
                                name="oetDLVFrmBchName" 
                                placeholder="<?php echo language('document/topupVending/topupVending', 'tFrom'); ?>" 
                                readonly
                                value="<?= $tBCHName; ?>">
                                <span class="input-group-btn">
                                    <button id="obtDLVBrowseBchFrm" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-6">
                        <label class="xCNLabelFrm"><?php echo language('document/adjuststock/adjuststock', 'tASTAdvSearchBranchTo'); ?></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetDLVToBchCode" name="oetDLVToBchCode" maxlength="5" value="<?= $tBCHCode; ?>"> 
                                <input 
                                class="form-control xWPointerEventNone" 
                                type="text" 
                                id="oetDLVToBchName" 
                                name="oetDLVToBchName" 
                                placeholder="<?php echo language('document/topupVending/topupVending', 'tTo'); ?>" 
                                readonly
                                value="<?= $tBCHName; ?>">
                                <span class="input-group-btn">
                                    <button id="obtDLVBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 col-lg-6">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-6">
                        <label class="xCNLabelFrm"><?php echo language('document/topupVending/topupVending', 'tTBDocDate'); ?></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input 
                                class="form-control input100 xCNDatePicker" 
                                type="text" id="oetDLVDocDateFrm" 
                                name="oetDLVDocDateFrm" 
                                placeholder="<?php echo language('document/topupVending/topupVending', 'tFrom'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtDLVDocDateFrm" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?php echo base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-6">
                        <label class="xCNLabelFrm"><?php echo language('document/topupVending/topupVending', 'tTo'); ?><?php echo language('document/topupVending/topupVending', 'tTBDocDate'); ?></label>
                        <div class="form-group">
                            <div class="input-group">
                                <input 
                                class="form-control input100 xCNDatePicker" 
                                type="text" id="oetDLVDocDateTo" 
                                name="oetDLVDocDateTo" 
                                placeholder="<?php echo language('document/topupVending/topupVending', 'tTo'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtDLVDocDateTo" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?php echo base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                    <label class="xCNLabelFrm"><?php echo language('document/topupVending/topupVending', 'tTBStaDoc'); ?></label>
                </div>
                <div class="form-group">
                    <select class="selectpicker form-control xControlForm" id="ocmDLVStaDoc" name="ocmDLVStaDoc" maxlength="1">
                        <option value="" selected><?=language('common/main/main', 'tAll'); ?></option>
                        <option value="2"><?=language('document/document/document', 'tDocStaProApv'); ?></option>
                        <option value="1"><?=language('document/document/document', 'tDocStaProApv1'); ?></option>
                        <option value="3"><?=language('document/document/document', 'tDocStaProDoc3'); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group" style="width: 60%;">
                    <label class="xCNLabelFrm">&nbsp;</label>
                    <button  type="button" id="obtDLVConfirmSearch" class="btn xCNBTNPrimery" style="width:100%" ><?=language('common/main/main', 'tSearch'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4"></div>
            <!--ตัวเลือกลบหลายตัว-->
            <div class="col-xs-4 col-sm-8 col-md-8 col-lg-8 text-right" style="margin-top:-35px;">
                <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                        <?=language('common/main/main','tCMNOption')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li id="oliDLVBtnDeleteAll" class="disabled">
                            <a data-toggle="modal" data-target="#odvDLVModalDelDocMultiple"><?= language('common/main/main','tDelAll')?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <section id="ostDLVDataTableDocument"></section>
    </div>
</div>

<script src="<?=base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?=base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jDeliveryFormSearchList.php')?>
