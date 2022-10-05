<input type="hidden"	id="oetCarStaBrowse"		value="<?php echo $nCarBrowseType?>">
<input type="hidden"	id="oetCarCallBackOption"  	value="<?php echo $tCarBrowseOption?>">
<?php if(isset($nCarBrowseType) && $nCarBrowseType == 0) : ?>
	<div id="odvCarMainMenu" class="main-menu">
		<div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('masCARView/0/0');?>
						<li id="oliCarTitle" onclick="JSvCallPageCarList()" style="cursor:pointer"><?php echo language('service/car/car','tCARTitle')?></li>
						<li id="oliCarTitleAdd" class="active"><a><?php echo language('service/car/car','tCARTitleAdd')?></a></li>
						<li id="oliCarTitleEdit" class="active"><a><?php echo language('service/car/car','tCARTitleEdit')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
					<div id="odvBtnCarInfo">
					<?php if($aAlwEventCar['tAutStaFull'] == 1 || ($aAlwEventCar['tAutStaAdd'] == 1 || $aAlwEventCar['tAutStaEdit'] == 1)) : ?>
						<button class="xCNBTNPrimeryPlus" type="submit" onclick="JSvCallPageCarAdd()">+</button>
					<?php endif;?>
					</div>
					<div id="odvBtnCarAddEdit">
						<button onclick="JSvCallPageCarList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
						<?php if($aAlwEventCar['tAutStaFull'] == 1 || ($aAlwEventCar['tAutStaAdd'] == 1 || $aAlwEventCar['tAutStaEdit'] == 1)) : ?>
						<div class="btn-group">
							<button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitCar').click()"> <?php echo language('common/main/main', 'tSave')?></button>
							<?php echo $vBtnSave?>
						</div>
					<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="xCNMenuCump xCNCarBrowseLine" id="odvMenuCump">
		&nbsp;
	</div>
	<div class="main-content">
		<div id="odvContentPageCar" class="panel panel-headline">
		</div>
	</div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a onclick="JCNxBrowseData('<?php echo $tCarBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>
                </a>
                <ol id="oliPvnNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?php echo $tCarBrowseOption?>')"><a><?php echo language('common/main/main','tShowData');?> : <?php echo  language('service/car/car','tCARTitle')?></a></li>
                    <li class="active"><a><?php echo language('service/car/car','tCARTitleAdd')?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPvnBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitCar').click()"><?php echo language('common/main/main', 'tSave')?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif;?>

<script src="<?php echo base_url('application/modules/service/assets/car/jCar.js'); ?>"></script>
