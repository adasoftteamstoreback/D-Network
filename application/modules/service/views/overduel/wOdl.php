<input id="oetOdlStaBrowse" type="hidden" value="<?=$nOdlBrowseType?>">
<input id="oetOdlCallBackOption" type="hidden" value="<?=$tOdlBrowseOption?>">
<?php if(isset($nOdlBrowseType) && $nOdlBrowseType == 0) : ?>
    <div id="odvOdlMainMenu" class="main-menu"> <!-- เปลี่ยน -->
        <div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                    <ol id="oliMenuNav" class="breadcrumb"> <!-- เปลี่ยน -->
                        <?php FCNxHADDfavorite('masOdl/0/0');?>
                        <li id="oliOdlTitle" class="xCNLinkClick" onclick="JSvCallPageOdlList()" style="cursor:pointer"><?= language('service/overduel/overduel','tOdlTitle')?></li> <!-- เปลี่ยน -->
                        <li id="oliOdlTitleAdd" class="active"><a><?= language('service/overduel/overduel','tOdlAdd')?></a></li>
                        <li id="oliOdlTitleEdit" class="active"><a><?= language('service/overduel/overduel','tOdlEdit')?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-4 text-right p-r-0">
                    <div id="odvBtnOdlInfo">
                        <?php if($aAlwEventOdl['tAutStaFull'] == 1 || $aAlwEventOdl['tAutStaAdd'] == 1) : ?>
                        <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageOdlAdd()">+</button>
                        <?php endif;?>
                    </div>
                    <div id="odvBtnAddEdit" style="margin-top:3px">
                        <div class="demo-button xCNBtngroup" style="width:100%;">
                            <button onclick="JSvCallPageOdlList()" class="btn xCNBTNDefult" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
                            <?php if($aAlwEventOdl['tAutStaFull'] == 1 || ($aAlwEventOdl['tAutStaAdd'] == 1 || $aAlwEventOdl['tAutStaEdit'] == 1)) : ?>
                                <div class="btn-group">
                                    <button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitOdl').click()"> <?php echo language('common/main/main', 'tSave')?></button>
                                    <?php echo $vBtnSave?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="xCNMenuCump xCNPtyBrowseLine" id="odvMenuCump">
		&nbsp;
	</div>
    <div class="main-content">
        <div id="odvContentPageOdl"></div>
    </div>
<?php endif;?>
<link rel="stylesheet" type="text/css" href="<?= base_url('application/modules/service/assets/overduel/css/Ada.OdlStyle.css')?>">
<script src="<?= base_url('application/modules/service/assets/overduel/jOverDuel.js')?>"></script>
