<input id="oetCAIStaBrowse" type="hidden" value="<?php echo $nCAIBrowseType?>">
<input id="oetCAICallBackOption" type="hidden" value="<?php echo $tCAIBrowseOption?>">
<input id="oetCAICarType" type="hidden" value="<?php echo $nCAICarType?>">
<?php if(isset($nCAIBrowseType) && $nCAIBrowseType == 0) : ?>
	<div id="odvCAIMainMenu" class="main-menu">
		<div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('masCAIView/0/0/'.$nCAICarType);?> 
						<li id="oliCAITitle" onclick="JSvCallPageCarInfoList()" style="cursor:pointer"><?php echo language('product/carinfo/carinfo','tCAITitle'.$nCAICarType)?></li>
						<li id="oliCAITitleAdd" class="active"><a><?php echo language('product/carinfo/carinfo','tCAITitleAdd'.$nCAICarType)?></a></li>
						<li id="oliCAITitleEdit" class="active"><a><?php echo language('product/carinfo/carinfo','tCAITitleEdit'.$nCAICarType)?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
					<div id="odvBtnCAIInfo">
					<?php if($aAlwEventCarInfo['tAutStaFull'] == 1 || ($aAlwEventCarInfo['tAutStaAdd'] == 1 || $aAlwEventCarInfo['tAutStaEdit'] == 1)) : ?>
						<button class="xCNBTNPrimeryPlus" type="submit" onclick="JSvCallPageCarInfoAdd()">+</button>
					<?php endif;?>
					</div>
					<div id="odvBtnCAIAddEdit">
						<button onclick="JSvCallPageCarInfoList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
						<?php if($aAlwEventCarInfo['tAutStaFull'] == 1 || ($aAlwEventCarInfo['tAutStaAdd'] == 1 || $aAlwEventCarInfo['tAutStaEdit'] == 1)) : ?>
						<div class="btn-group">
							<button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitCarInfo').click()"> <?php echo language('common/main/main', 'tSave')?></button>
							<?php echo $vBtnSave?>
						</div>
					<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="xCNMenuCump xCNCAIBrowseLine" id="odvMenuCump">
		&nbsp;
	</div>
	<div class="main-content">
		<div id="odvContentPageCarInfo" class="panel panel-headline">
		</div>
	</div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a onclick="JCNxBrowseData('<?php echo $tCAIBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliPvnNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?php echo $tCAIBrowseOption?>')"><a><?php echo language('common/main/main','tShowData');?> : <?php echo  language('product/carinfo/carinfo','tCAITitle'.$nCAICarType)?></a></li>
                    <li class="active"><a><?php echo language('product/carinfo/carinfo','tCAITitleAdd'.$nCAICarType)?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPvnBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitCarInfo').click()"><?php echo language('common/main/main', 'tSave')?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif;?>

<script src="<?php echo base_url('application/modules/product/assets/src/carinfo/jCarInfo.js'); ?>"></script>