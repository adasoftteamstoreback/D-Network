<input id="oetMsgStaBrowse" type="hidden" value="<?php echo $nMsgBrowseType?>">
<input id="oetMsgCallBackOption" type="hidden" value="<?php echo $tMsgBrowseOption?>">
<?php if(isset($nMsgBrowseType) && $nMsgBrowseType == 0) : ?>
	<div id="odvMsgMainMenu" class="main-menu">
		<div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
					<ol id="oliMenuNav" class="breadcrumb">
						<?php FCNxHADDfavorite('masMSGView/0/0');?> 
						<li id="oliMsgTitle" onclick="JSvCallPageMessageList()" style="cursor:pointer"><?php echo language('service/message/message','tMSGTitle')?></li>
						<li id="oliMsgTitleAdd" class="active"><a><?php echo language('service/message/message','tMSGTitleAdd')?></a></li>
						<li id="oliMsgTitleEdit" class="active"><a><?php echo language('service/message/message','tMSGTitleEdit')?></a></li>
					</ol>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
					<div id="odvBtnMsgInfo">
					<?php if($aAlwEventMessage['tAutStaFull'] == 1 || ($aAlwEventMessage['tAutStaAdd'] == 1 || $aAlwEventMessage['tAutStaEdit'] == 1)) : ?>
						<button class="xCNBTNPrimeryPlus" type="submit" onclick="JSvCallPageMessageAdd()">+</button>
					<?php endif;?>
					</div>
					<div id="odvBtnMsgAddEdit">
						<button onclick="JSvCallPageMessageList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
						<?php if($aAlwEventMessage['tAutStaFull'] == 1 || ($aAlwEventMessage['tAutStaAdd'] == 1 || $aAlwEventMessage['tAutStaEdit'] == 1)) : ?>
						<div class="btn-group">
							<button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitMessage').click()"> <?php echo language('common/main/main', 'tSave')?></button>
							<?php echo $vBtnSave?>
						</div>
					<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="xCNMenuCump xCNMsgBrowseLine" id="odvMenuCump">
		&nbsp;
	</div>
	<div class="main-content">
		<div id="odvContentPageMessage" class="panel panel-headline">
		</div>
	</div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a onclick="JCNxBrowseData('<?php echo $tMsgBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliPvnNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?php echo $tMsgBrowseOption?>')"><a><?php echo language('common/main/main','tShowData');?> : <?php echo  language('service/message/message','tMSGTitle')?></a></li>
                    <li class="active"><a><?php echo language('service/message/message','tMSGTitleAdd')?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPvnBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitMessage').click()"><?php echo language('common/main/main', 'tSave')?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif;?>

<script src="<?php echo base_url('application/modules/service/assets/message/jMessage.js'); ?>"></script>
