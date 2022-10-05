<input id="oetCldStaBrowse" type="hidden" value="<?php echo $nCldBrowseType?>">
<input id="oetCldCallBackOption" type="hidden" value="<?php echo $tCldBrowseOption?>">
<?php if(isset($nCldBrowseType) && $nCldBrowseType == 0) : ?>
	<div id="odvCldMainMenu" class="main-menu">
		<div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('masCLDView/0/0');?> 
						<li id="oliCldTitle" onclick="JSvCallPageCalendarList()" style="cursor:pointer"><?php echo language('service/calendar/calendar','tCLDTitle')?></li>
						<li id="oliCldTitleAdd" class="active"><a><?php echo language('service/calendar/calendar','tCLDTitleAdd')?></a></li>
						<li id="oliCldTitleEdit" class="active"><a><?php echo language('service/calendar/calendar','tCLDTitleEdit')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
					<div id="odvBtnCldInfo">
					<?php if($aAlwEventCalendar['tAutStaFull'] == 1 || ($aAlwEventCalendar['tAutStaAdd'] == 1 || $aAlwEventCalendar['tAutStaEdit'] == 1)) : ?>
						<button class="xCNBTNPrimeryPlus" type="submit" onclick="JSvCallPageCalendarAdd()">+</button>
					<?php endif;?>
					</div>
					<div id="odvBtnCldAddEdit">
						<button onclick="JSvCallPageCalendarList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
						<?php if($aAlwEventCalendar['tAutStaFull'] == 1 || ($aAlwEventCalendar['tAutStaAdd'] == 1 || $aAlwEventCalendar['tAutStaEdit'] == 1)) : ?>
						<div class="btn-group">
							<button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitCalendar').unbind().click()"> <?php echo language('common/main/main', 'tSave')?></button>
							<?php echo $vBtnSave?>
						</div>
					<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="xCNMenuCump xCNCldBrowseLine" id="odvMenuCump">
		&nbsp;
	</div>
	<div class="main-content">
		<div id="odvContentPageCalendar" class="panel panel-headline">
		</div>
	</div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a onclick="JCNxBrowseData('<?php echo $tCldBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliPvnNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?php echo $tCldBrowseOption?>')"><a><?php echo language('common/main/main','tShowData');?> : <?php echo  language('service/calendar/calendar','tCLDTitle')?></a></li>
                    <li class="active"><a><?php echo language('service/calendar/calendar','tCLDTitleAdd')?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPvnBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitCalendar').click()"><?php echo language('common/main/main', 'tSave')?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif;?>
<script src="<?php echo base_url('application/modules/service/assets/calendar/jCalendar.js'); ?>"></script>
