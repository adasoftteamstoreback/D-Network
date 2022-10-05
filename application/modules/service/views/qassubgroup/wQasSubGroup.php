<input id="oetQSGStaBrowse" type="hidden" value="<?php echo $nQSGBrowseType?>">
<input id="oetQSGCallBackOption" type="hidden" value="<?php echo $tQSGBrowseOption?>">
<?php if(isset($nQSGBrowseType) && $nQSGBrowseType == 0) : ?>
	<div id="odvQSGMainMenu" class="main-menu">
		<div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('masQSGView/0/0');?> 
						<li id="oliQSGTitle" onclick="JSvCallPageQasSubGroupList()" style="cursor:pointer"><?php echo language('service/qassubgroup/qassubgroup','tQSGTitle')?></li>
						<li id="oliQSGTitleAdd" class="active"><a><?php echo language('service/qassubgroup/qassubgroup','tQSGTitleAdd')?></a></li>
						<li id="oliQSGTitleEdit" class="active"><a><?php echo language('service/qassubgroup/qassubgroup','tQSGTitleEdit')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
					<div id="odvBtnQSGInfo">
					<?php if($aAlwEventQasSubGroup['tAutStaFull'] == 1 || ($aAlwEventQasSubGroup['tAutStaAdd'] == 1 || $aAlwEventQasSubGroup['tAutStaEdit'] == 1)) : ?>
						<button class="xCNBTNPrimeryPlus" type="submit" onclick="JSvCallPageQasSubGroupAdd()">+</button>
					<?php endif;?>
					</div>
					<div id="odvBtnQSGAddEdit">
						<button onclick="JSvCallPageQasSubGroupList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
						<?php if($aAlwEventQasSubGroup['tAutStaFull'] == 1 || ($aAlwEventQasSubGroup['tAutStaAdd'] == 1 || $aAlwEventQasSubGroup['tAutStaEdit'] == 1)) : ?>
						<div class="btn-group">
							<button id="obtQASSubSubmitFromDoc" type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitQasSubGroup').click()"> <?php echo language('common/main/main', 'tSave')?></button>
							<?php echo $vBtnSave?>
						</div>
					<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="xCNMenuCump xCNQSGBrowseLine" id="odvMenuCump">
		&nbsp;
	</div>
	<div class="main-content">
		<div id="odvContentPageQasSubGroup" class="panel panel-headline">
		</div>
	</div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a onclick="JCNxBrowseData('<?php echo $tQSGBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliPvnNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?php echo $tQSGBrowseOption?>')"><a><?php echo language('common/main/main','tShowData');?> : <?php echo  language('service/qassubgroup/qassubgroup','tQSGTitle')?></a></li>
                    <li class="active"><a><?php echo language('service/qassubgroup/qassubgroup','tQSGTitleAdd')?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPvnBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitQasSubGroup').click()"><?php echo language('common/main/main', 'tSave')?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif;?>

<script src="<?php echo base_url('application/modules/service/assets/qassubgroup/jQasSubGroup.js'); ?>"></script>