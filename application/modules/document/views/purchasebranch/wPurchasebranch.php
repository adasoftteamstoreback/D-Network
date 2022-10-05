<input id="oetPRBStaBrowse" type="hidden" value="<?php echo $nPRBBrowseType ?>">
<input id="oetPRBCallBackOption" type="hidden" value="<?php echo $tPRBBrowseOption ?>">
<input id="oetPRBJumpDocNo" type="hidden" value="<?php echo $aParams['tDocNo'] ?>">
<input id="oetPRBJumpBchCode" type="hidden" value="<?php echo $aParams['tBchCode'] ?>">
<input id="oetPRBJumpAgnCode" type="hidden" value="<?php echo $aParams['tAgnCode'] ?>">

<?php if (isset($nPRBBrowseType) && ( $nPRBBrowseType == 0 || $nPRBBrowseType ==2) ) : ?>
    <div id="odvPRBMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ol id="oliPRBMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('docPRB/0/0');?>
                        <li id="oliPRBTitle" style="cursor:pointer;"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBTitleMenu'); ?></li>
                        <li id="oliPRBTitleAdd" class="active"><a><?php echo language('document/purchasebranch/purchasebranch', 'tPRBTitleAdd'); ?></a></li>
                        <li id="oliPRBTitleEdit" class="active"><a><?php echo language('document/purchasebranch/purchasebranch', 'tPRBTitleEdit'); ?></a></li>
                        <li id="oliPRBTitleDetail" class="active"><a><?php echo language('document/purchasebranch/purchasebranch', 'tPRBTitleDetail'); ?></a></li>
                        <li id="oliPRBTitleAprove" class="active"><a><?php echo language('document/purchasebranch/purchasebranch', 'tPRBTitleAprove'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvPRBBtnGrpInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button id="obtPRBCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                            <?php endif; ?>
                        </div>
                        <div id="odvPRBBtnGrpAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button id="obtPRBCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                    <button id="obtPRBPrintDoc" onclick="JSxPRBPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtPRBCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel'); ?></button>
                                    <button id="obtPRBApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button>
                                    <div  id="odvPRBBtnGrpSave" class="btn-group">
                                        <button id="obtPRBSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?php echo language('common/main/main', 'tSave'); ?></button>
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
    <div class="xCNMenuCump xCNPRBBrowseLine" id="odvMenuCump">&nbsp;</div>
    <div class="main-content">
        <div id="odvPRBContentPageDocument">
        </div>
    </div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a id="oahPRBBrowseCallBack" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>
                </a>
                <ol id="oliPRBNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li id="oliPRBBrowsePrevious" class="xWBtnPrevious"><a><?php echo language('common/main/main', 'tShowData'); ?> : <?php echo language('document/purchaseinvoice/purchaseinvoice', 'tDOTitleMenu'); ?></a></li>
                    <li class="active"><a><?php echo language('document/purchaseorder/purchaseorder', 'tDOTitleAdd'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPRBBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button id="obtPRBBrowseSubmit" type="button" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif; ?>
<?php include('script/jPurchasebranch.php')?>
