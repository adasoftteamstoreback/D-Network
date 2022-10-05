<div class="panel panel-headline">
    <div class="panel-heading" id="odvSearch">
        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInpuTXOthoutSingleQuote"
                            type="text"
                            id="oetBACSearchAllDocument"
                            name="oetBACSearchAllDocument"
                            placeholder="<?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACFillTextSearch')?>"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button id="obtBACSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                        </span>
                    </div>
                </div>
            </div>
            <button id="obtBACAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
            <button id="obtBACSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
        </div>
        <div id="odvBACAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
            <form id="ofmBACFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
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
                            <label class="xCNLabelFrm"><?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACAgnFC'); ?></label>
                            <div class="input-group">
                                <input class="form-control xCNHide" type="text" id="oetBACAdvSearchAgnCodeFrom" name="oetBACAdvSearchAgnCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                <input
                                    class="form-control xWPointerEventNone"
                                    type="text"
                                    id="oetBACAdvSearchAgnNameFrom"
                                    name="oetBACAdvSearchAgnNameFrom"
                                    placeholder="<?php echo language('settingconfig/backupandcleardata/backupandcleardata','tBACAgnFC'); ?>"
                                    readonly
                                    value="<?= $tBCHName; ?>"
                                >
                                <span class="input-group-btn">
                                    <button id="obtBACAdvSearchBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                            <label class="xCNLabelFrm"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgType'); ?></label>
                        </div>
                        <div class="form-group">
                            <select class="selectpicker form-control" id="ocmStaPrgType" name="ocmStaPrgType">
                            <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgType1'); ?></option>
                                <option value='2'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgType2'); ?></option>
                                <option value='3'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgType3'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                            <label class="xCNLabelFrm"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACFTPrgGroup'); ?></label>
                        </div>
                        <div class="form-group">
                            <select class="selectpicker form-control" id="ocmStaPrgGroup" name="ocmStaPrgGroup">
                            <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACFTPrgGroup1'); ?></option>
                                <option value='2'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACFTPrgGroup2'); ?></option>
                                <option value='3'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACFTPrgGroup3'); ?></option>
                                <option value='4'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACFTPrgGroup4'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                            <label class="xCNLabelFrm"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgStaPrg'); ?></label>
                        </div>
                        <div class="form-group">
                            <select class="selectpicker form-control" id="ocmStaPrgAllowPurge" name="ocmStaPrgAllowPurge">
                            <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACStaPrg1'); ?></option>
                                <option value='2'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACStaPrg2'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                            <label class="xCNLabelFrm"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgStaUse'); ?></label>
                        </div>
                        <div class="form-group">
                            <select class="selectpicker form-control" id="ocmStaPrgStaUse" name="ocmStaPrgStaUse">
                            <option value='0'><?php echo language('common/main/main', 'tStaDocAll'); ?></option>
                                <option value='1'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgUse1'); ?></option>
                                <option value='2'><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPrgUse2'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <label class="xCNLabelFrm"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPurgDateFrom'); ?></label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input 
                                        class="form-control input100 xCNDatePicker" 
                                        type="text" id="oetSearchDocDateFrom" 
                                        name="oetSearchDocDateFrom" 
                                        autocomplete="off"
                                        placeholder="<?php echo language('document/topupVending/topupVending', 'tFrom'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSearchDocDateFrom" type="button" class="btn xCNBtnDateTime">
                                                <img src="<?php echo base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <label class="xCNLabelFrm"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPurgDateTo'); ?></label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input 
                                        class="form-control input100 xCNDatePicker" 
                                        type="text" id="oetSearchDocDateTo" 
                                        name="oetSearchDocDateTo" 
                                        autocomplete="off"
                                        placeholder="<?php echo language('document/topupVending/topupVending', 'tTo'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSearchDocDateTo" type="button" class="btn xCNBtnDateTime">
                                                <img src="<?php echo base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="obtBACAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <section id="ostBACDataTableDocument"></section>
    </div>
</div>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jBackupAndClearDataFormSearchList.php')?>
