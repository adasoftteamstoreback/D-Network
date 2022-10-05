<input id="oetBACStaBrowse"        type="hidden" value="<?= $nBrowseType ?>">
<input id="oetBACCallBackOption"   type="hidden" value="<?= $tBrowseOption ?>">

<div id="odvJR1MainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <ol id="oliJR1MenuNav" class="breadcrumb">
                    <?php FCNxHADDfavorite('docJR1/0/0');?>
                    <li id="oliJR1Title" class="active" style="cursor:pointer" onclick="JSvCallPageBACList()"><?= language('settingconfig/backupandcleardata/backupandcleardata','tBACTitle')?></li>
                    <li id="oliAdvTitleEdit" class="active"><a><?= language('pos/admessage/admessage','tADVTitleEdit')?></a></li>
                </ol>
            </div>
            <!-- <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 p-r-0">
                
                <div id="odvBtnSelectBAC" class="text-right">
                    <select class="selectpicker" id="ocmBACOption" name="ocmBACOption" maxlength="1">
                        <option value="1" selected><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACOption1'); ?></option>
                        <option value="2"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACOption2'); ?></option>
                        <option value="3"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACOption3'); ?></option>
                    </select>
                </div>
            </div> -->
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvBtnAddEdit">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <label class="xCNLabelFrm"> </label>
						<button onclick="JSvBACCallPageDataTable()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
                        <button id="obtBacSubmit" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" onclick="$('#obtSubmitBackup').click()"> <?php echo language('common/main/main', 'tSave'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xCNMenuCump xCNRDHBrowseLine" id="odvMenuCump">&nbsp;</div>
<div class="main-content">
    <div id="odvBACPageDocument"></div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/settingconfig/assets/src/backupandcleardata/jฺBackupAndClearData.js"></script>
