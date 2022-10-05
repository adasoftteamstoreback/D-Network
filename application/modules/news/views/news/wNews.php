<input id="oetNewStaBrowse" type="hidden" value="<?=$nNewBrowseType?>">
<input id="oetNewCallBackOption" type="hidden" value="<?=$tNewBrowseOption?>">

<?php if(isset($nNewBrowseType) && $nNewBrowseType == 0) : ?>
    <div id="odvNewMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="xCNNewVMaster">
                    <div class="col-xs-12 col-md-8">
                        <ol id="oliMenuNav" class="breadcrumb">
                            <?php FCNxHADDfavorite('news/0/0');?>
                            <li id="oliNewTitle" class="xCNLinkClick" onclick="JSvCallPageNewList()" style="cursor:pointer"><?= language('news/news/news','tNewTitle')?></li> <!-- เปลี่ยน -->
                            <li id="oliNewTitleAdd" class="active"><a><?= language('news/news/news','tNewTitleAdd')?></a></li>
                            <li id="oliNewTitleEdit" class="active"><a><?= language('news/news/news','tNewTitleEdit')?></a></li>
                        </ol>
                    </div>
                    <div class="col-xs-12 col-md-4 text-right p-r-0">
                        <div id="odvBtnNewInfo">
                            <?php /*if($aAlwEventNews['tAutStaFull'] == 1 || $aAlwEventNews['tAutStaAdd'] == 1) :*/ ?>
                            <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageNewAdd()">+</button>
                            <?php /*endif;*/ ?>
                        </div>
                        <div id="odvBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button onclick="JSvCallPageNewList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
                                <?php /*if($aAlwEventNews['tAutStaFull'] == 1 || ($aAlwEventNews['tAutStaAdd'] == 1 || $aAlwEventNews['tAutStaEdit'] == 1)) :*/ ?>
                                <div class="btn-group">
                                    <button type="submit" class="btn xWBtnGrpSaveLeft" onclick="JSxSetStatusClickNewSubmit();$('#obtSubmitNew').click()"> <?php echo language('common/main/main', 'tSave')?></button>
                                    <?php echo $vBtnSave?>
                                </div>
                                <?php /*endif;*/ ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="xCNNewVBrowse">
                    <div class="col-xs-12 col-md-6">
                        <a onclick="JCNxBrowseData('<?php echo $tNewBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;font-size:19px;">
                            <i class="fa fa-arrow-left xCNBackBowse"></i>
                        </a>
                        <ol id="oliPunNavBrowse" class="breadcrumb xCNBCMenu" style="margin-left:25px">
                            <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?php echo $tNewBrowseOption?>')"><a><?php echo language('common/main/main','tShowData');?> : <?php echo language('news/news/news','tNewTitle')?></a></li>
                            <li class="active"><a><?php echo  language('news/news/news','tNewTitleAdd')?></a></li>
                        </ol>
                    </div>
                    <div class="col-xs-12 col-md-6 text-right">
                        <div id="odvPunBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                            <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitNew').click()"><?php echo  language('common/main/main', 'tSave')?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="xCNMenuCump xCNNewBrowseLine" id="odvMenuCump">
        &nbsp;
    </div>
    <div class="main-content">
        <div id="odvContentPageNews"  class="panel panel-headline"> </div>
    </div>
<?php else :?>
	<div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a onclick="JCNxBrowseData('<?php echo $tNewBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>
                </a>
                <ol id="oliBchNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?php echo $tNewBrowseOption?>')"><a><?php echo language('common/main/main','tShowData');?> : <?php echo  language('news/news/news','tNewTitle')?></a></li>
                    <li class="active"><a><?php echo language('news/news/news','tNewTitleAdd')?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvBchBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitNew').click()"><?php echo language('common/main/main', 'tSave')?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd"></div>
<?php endif;?>

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
<div id="odvNewModalConfirm" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('checkdocument/checkdocument','tMntBtnSendConfirm'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <ul>
                        <li><?php echo language('news/news/news','tMntBtnSendWaring1'); ?></li>
                        <li><?php echo language('news/news/news','tMntBtnSendWaring2'); ?></li>
                        <li><?php echo language('news/news/news','tMntBtnSendWaring3'); ?></li>
                    </ul>
            </div>
            <div class="modal-footer">
                <button onclick="JSoNewsSendNotiChoose()"  type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->




<script src="<?= base_url('application/modules/news/assets/src/news/jNews.js')?>"></script>
