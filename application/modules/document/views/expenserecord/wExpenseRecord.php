<input id="oetPXStaBrowse" type="hidden" value="<?php echo $nPXBrowseType ?>">
<input id="oetPXCallBackOption" type="hidden" value="<?php echo $tPXBrowseOption ?>">

<?php if (isset($nPXBrowseType) && $nPXBrowseType == 0) : ?>
    <div id="odvPXMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ol id="oliPXMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('docPX/0/0');?>
                        <li id="oliPXTitle" style="cursor:pointer;"><?php echo language('document/expenserecord/expenserecord', 'tPXTitleMenu'); ?></li>
                        <li id="oliPXTitleAdd" class="active"><a><?php echo language('document/expenserecord/expenserecord', 'tPXTitleAdd'); ?></a></li>
                        <li id="oliPXTitleEdit" class="active"><a><?php echo language('document/expenserecord/expenserecord', 'tPXTitleEdit'); ?></a></li>
                        <li id="oliPXTitleDetail" class="active"><a><?php echo language('document/expenserecord/expenserecord', 'tPXTitleDetail'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvPXBtnGrpInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button id="obtPXCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                            <?php endif; ?>
                        </div>
                        <div id="odvPXBtnGrpAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button id="obtPXCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                    <button id="obtPXPrintDoc" onclick="JSxPXPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtPXCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel'); ?></button>
                                    <button id="obtPXApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button>                                  
                                    <div  id="odvPXBtnGrpSave" class="btn-group">
                                        <button id="obtPXSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?php echo language('common/main/main', 'tSave'); ?></button>
                                        <?php echo $vBtnSave ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="xCNMenuCump xCNPXBrowseLine" id="odvMenuCump">&nbsp;</div>
    <div class="main-content">
        <div id="odvPXContentPageDocument">
        </div>
    </div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a id="oahPXBrowseCallBack" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliPXNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li id="oliPXBrowsePrevious" class="xWBtnPrevious"><a><?php echo language('common/main/main', 'tShowData'); ?> : <?php echo language('document/expenserecord/expenserecord', 'tPXTitleMenu'); ?></a></li>
                    <li class="active"><a><?php echo language('document/expenserecord/expenserecord', 'tPXTitleAdd'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPXBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button id="obtPXBrowseSubmit" type="button" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/expenserecord/jExpenseRecord.js"></script>








