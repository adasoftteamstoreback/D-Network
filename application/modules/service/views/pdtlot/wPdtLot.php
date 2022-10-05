<input id="oetLotStaBrowse" type="hidden" value="<?=$nLotBrowseType?>">
<input id="oetLotCallBackOption" type="hidden" value="<?=$tLotBrowseOption?>">

<?php if(isset($nLotBrowseType) && $nLotBrowseType == 0) : ?>
    <div id="odvLotMainMenu" class="main-menu"> 
        <div class="xCNMrgNavMenu">
			<div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                    <ol id="oliMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('maslot/0/0');?> 
                        <li id="oliLotTitle" class="xCNLinkClick" onclick="JSvCallPagePdtLotList()" style="cursor:pointer"><?= language('service/pdtlot/pdtlot','tLOTTitle')?></li> <!-- เปลี่ยน -->
                        <li id="oliLotTitleAdd" class="active"><a><?= language('service/pdtlot/pdtlot','tLOTTitleAdd')?></a></li>
                        <li id="oliLotTitleEdit" class="active"><a><?= language('service/pdtlot/pdtlot','tLOTTitleEdit')?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-4 text-right p-r-0">
                    <div id="odvBtnLotInfo">
                        <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || $aAlwEventPdtLot['tAutStaAdd'] == 1) : ?>
                        <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPagePdtLotAdd()">+</button>
                        <?php endif;?>
                    </div>
                    <div id="odvBtnAddEdit" style="margin-top:3px">
                        <div class="demo-button xCNBtngroup" style="width:100%;">
                            <button onclick="JSvCallPagePdtLotList()" class="btn xCNBTNDefult" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
                            <?php if($aAlwEventPdtLot['tAutStaFull'] == 1 || ($aAlwEventPdtLot['tAutStaAdd'] == 1 || $aAlwEventPdtLot['tAutStaEdit'] == 1)) : ?>
                                <div class="btn-group">
                                    <button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitPdtLot').click()"> <?php echo language('common/main/main', 'tSave')?></button>
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
        <div id="odvContentPagePdtLot"></div>
    </div>
<?php else:?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a onclick="JCNxBrowseData('<?php echo $tLotBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliPunNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?php echo $tLotBrowseOption?>')"><a><?php echo language('common/main/main','tShowData');?> : <?php echo language('service/pdtlot/pdtlot','tLOTTitle')?></a></li>
                    <li class="active"><a><?php echo  language('service/pdtlot/pdtlot','tLOTTitleAdd')?></a></li>    
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPunBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitPdtLot').click()"><?php echo  language('common/main/main', 'tSave')?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd"></div>
<?php endif;?>
<link rel="stylesheet" type="text/css" href="<?= base_url('application/modules/service/assets/pdtlot/css/Ada.LotStyle.css')?>">
<script src="<?= base_url('application/modules/service/assets/pdtlot/jPdtLot.js')?>"></script>
