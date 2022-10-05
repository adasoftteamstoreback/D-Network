<input id="oetQahStaBrowse" type="hidden" value="<?php echo $nQahBrowseType?>">
<input id="oetQahCallBackOption" type="hidden" value="<?php echo $tQahBrowseOption?>">
<?php if(isset($nQahBrowseType) && $nQahBrowseType == 0) : ?>
	<div id="odvQahMainMenu" class="main-menu">
		<div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('masQAHView/0/0');?> 
						<li id="oliQahTitle" onclick="JSvCallPageQuestionList()" style="cursor:pointer"><?php echo language('service/question/question','tQAHTitle')?></li>
						<li id="oliQahTitleAdd" class="active"><a><?php echo language('service/question/question','tQAHTitleAdd')?></a></li>
						<li id="oliQahTitleEdit" class="active"><a><?php echo language('service/question/question','tQAHTitleEdit')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
					<div id="odvBtnQahInfo">
					<?php if($aAlwEventQuestion['tAutStaFull'] == 1 || ($aAlwEventQuestion['tAutStaAdd'] == 1 || $aAlwEventQuestion['tAutStaEdit'] == 1)) : ?>
						<button class="xCNBTNPrimeryPlus" type="submit" onclick="JSvCallPageQuestionAdd()">+</button>
					<?php endif;?>
					</div>
					<div id="odvBtnQahAddEdit">
						<button onclick="JSvCallPageQuestionList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
						<?php if($aAlwEventQuestion['tAutStaFull'] == 1 || ($aAlwEventQuestion['tAutStaAdd'] == 1 || $aAlwEventQuestion['tAutStaEdit'] == 1)) : ?>
						<div class="btn-group">
							<button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitQuestion').click()"> <?php echo language('common/main/main', 'tSave')?></button>
							<?php echo $vBtnSave?>
						</div>
					<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="xCNMenuCump xCNQahBrowseLine" id="odvMenuCump">
		&nbsp;
	</div>
	<div class="main-content">
		<div id="odvContentPageQuestion" class="panel panel-headline">
		</div>
	</div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a onclick="JCNxBrowseData('<?php echo $tQahBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliPvnNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?php echo $tQahBrowseOption?>')"><a><?php echo language('common/main/main','tShowData');?> : <?php echo  language('service/question/question','tQAHTitle')?></a></li>
                    <li class="active"><a><?php echo language('service/question/question','tQAHTitleAdd')?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPvnBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitQuestion').click()"><?php echo language('common/main/main', 'tSave')?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif;?>

<script src="<?php echo base_url('application/modules/service/assets/question/jQuestion.js'); ?>"></script>
