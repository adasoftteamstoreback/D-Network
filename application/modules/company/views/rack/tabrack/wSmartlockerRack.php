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
						<?php FCNxHADDfavorite('SHPSmartLockerrack');?> 
							<li id="oliSMLTitle" class="xCNLinkClick" onclick="JSvRackMain()" style="cursor:pointer"><?=language('company/rack/rack','tRckTitle')?></li>
							<li id="oliSMLTitleAdd"  class="active"><a><?php echo language('company/rack/rack','tRacAdd')?></a></li>
							<li id="oliSMLTitleEdit" class="active"><a><?php echo language('company/rack/rack','tRacEdit')?></a></li>
						</ol>
					</div>
					<div id="odvBtnGrpShop" class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
						<div id="odvBtnSMLInfo">
					
								<button  id="obtSMLLayout" name="obtSMLLayout"  class="xCNBTNPrimeryPlus" type="button" onclick="SHPSmartLockerrackPageAdd()">+</button>
						
						
						</div>
						<div id="odvBtnAddEdit">
							<button onclick="JSvRackMain()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tBack')?></button>
						
								<div class="btn-group">
									<button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitRack').click()"> <?=language('common/main/main', 'tSave')?></button>
									<?=$vBtnSave?>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="xCNMenuCump xCNSMLBrowseLine" id="odvMenuCump">
        &nbsp;
    </div>
    <div class="main-content">
        <div id="odvContentRackSizeMain" class="panel panel-headline" style="margin-bottom:0px;">
        </div>
    </div>


<?php include "script/jSmartlockerRackMain.php"; ?>
