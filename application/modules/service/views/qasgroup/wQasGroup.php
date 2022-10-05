<input id="oetQGPStaBrowse" type="hidden" value="<?php echo $nQGPBrowseType?>">
<input id="oetQGPCallBackOption" type="hidden" value="<?php echo $tQGPBrowseOption?>">
<?php if(isset($nQGPBrowseType) && $nQGPBrowseType == 0) : ?>
	<div id="odvQGPMainMenu" class="main-menu">
		<div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('masQGPView/0/0');?> 
						<li id="oliQGPTitle" onclick="JSvCallPageQasGroupList()" style="cursor:pointer"><?php echo language('service/qasgroup/qasgroup','tQGPTitle')?></li>
						<li id="oliQGPTitleAdd" class="active"><a><?php echo language('service/qasgroup/qasgroup','tQGPTitleAdd')?></a></li>
						<li id="oliQGPTitleEdit" class="active"><a><?php echo language('service/qasgroup/qasgroup','tQGPTitleEdit')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
					<div id="odvBtnQGPInfo">
					<?php if($aAlwEventQasGroup['tAutStaFull'] == 1 || ($aAlwEventQasGroup['tAutStaAdd'] == 1 || $aAlwEventQasGroup['tAutStaEdit'] == 1)) : ?>
						<button class="xCNBTNPrimeryPlus" type="submit" onclick="JSvCallPageQasGroupAdd()">+</button>
					<?php endif;?>
					</div>
					<div id="odvBtnQGPAddEdit">
						<button onclick="JSvCallPageQasGroupList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
						<?php if($aAlwEventQasGroup['tAutStaFull'] == 1 || ($aAlwEventQasGroup['tAutStaAdd'] == 1 || $aAlwEventQasGroup['tAutStaEdit'] == 1)) : ?>
						<div class="btn-group">
							<button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitQasGroup').click()"> <?php echo language('common/main/main', 'tSave')?></button>
							<?php echo $vBtnSave?>
						</div>
					<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="xCNMenuCump xCNQGPBrowseLine" id="odvMenuCump">
		&nbsp;
	</div>
	<div class="main-content">
		<div id="odvContentPageQasGroup" class="panel panel-headline">
		</div>
	</div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a onclick="JCNxBrowseData('<?php echo $tQGPBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliPvnNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?php echo $tQGPBrowseOption?>')"><a><?php echo language('common/main/main','tShowData');?> : <?php echo  language('service/qasgroup/qasgroup','tQGPTitle')?></a></li>
                    <li class="active"><a><?php echo language('service/qasgroup/qasgroup','tQGPTitleAdd')?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPvnBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitQasGroup').click()"><?php echo language('common/main/main', 'tSave')?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif;?>

<script src="<?php echo base_url('application/modules/service/assets/qasgroup/jQasGroup.js'); ?>"></script>
