<style>
    .NumberDuplicate{
        font-size   : 15px !important;
        color       : red;
        font-style  : italic;
    }

    .xCNSearchpadding{
        padding     : 0px 3px;
    }
</style>


<div id="odvSMLMainMenu" class="main-menu clearfix">
		<div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
					<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
						<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('SHPSmartLockerSize');?> 
							<li id="oliSMLTitle" class="xCNLinkClick" onclick="JSvSMSMain()" style="cursor:pointer"><?=language('company/smartlockerSize/smartlockerSize','tSMSSizeTitle')?></li>
							<li id="oliSMLTitleAdd"  class="active"><a><?php echo language('company/smartlockerSize/smartlockerSize','tSMSSizeAdd')?></a></li>
							<li id="oliSMLTitleEdit" class="active"><a><?php echo language('company/smartlockerSize/smartlockerSize','tSMSSizeEdit')?></a></li>
						</ol>
					</div>
					<div id="odvBtnGrpShop" class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
						<div id="odvBtnSMLInfo">
							<?php if($aAlwEventSMS['tAutStaFull'] == 1 || $aAlwEventSMS['tAutStaAdd'] == 1) : ?>
								<button  id="obtSMLLayout" name="obtSMLLayout"  class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageSmartlockerSizeAdd()">+</button>
							<?php endif; ?>
						
						</div>
						<div id="odvBtnAddEdit">
							<button onclick="JSvSMSMain()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tBack')?></button>
							<?php if($aAlwEventSMS['tAutStaFull'] == 1 || ($aAlwEventSMS['tAutStaAdd'] == 1 || $aAlwEventSMS['tAutStaEdit'] == 1)) : ?>
								<div class="btn-group">
									<button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitSml').click()"> <?=language('common/main/main', 'tSave')?></button>
									<?=$vBtnSave?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="xCNMenuCump xCNSMLBrowseLine" id="odvMenuCump">
        &nbsp;
    </div>
    <div class="main-content">
        <div id="odvContentSMSSizeMain" class="panel panel-headline" style="margin-bottom:0px;">
        </div>
    </div>

<?php include "script/jSmartlockerSizeMain.php"; ?>
