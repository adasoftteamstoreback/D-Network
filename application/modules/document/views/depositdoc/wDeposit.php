<input id="oetDPSStaBrowse" type="hidden" value="<?php echo $nDPSBrowseType ?>">
<input id="oetDPSCallBackOption" type="hidden" value="<?php echo $tDPSBrowseOption ?>">

<?php if (isset($nDPSBrowseType) && $nDPSBrowseType == 0) : ?>
    <div id="odvDPSMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ol id="oliSOMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('dcmSO/0/0');?>
                        <li id="oliDPSTitle" style="cursor:pointer;"><?php echo language('document/depositdoc/depositdoc', 'tDPSTitleMenu'); ?></li>
                        <li id="oliDPSTitleAdd" class="active"><a><?php echo language('document/depositdoc/depositdoc', 'tDPSTitleAdd'); ?></a></li>
                        <li id="oliDPSTitleEdit" class="active"><a><?php echo language('document/depositdoc/depositdoc', 'tDPSTitleEdit'); ?></a></li>
                        <li id="oliDPSTitleDetail" class="active"><a><?php echo language('document/depositdoc/depositdoc', 'tDPSTitleDetail'); ?></a></li>
                        <li id="oliDPSTitleAprove" class="active"><a><?php echo language('document/depositdoc/depositdoc', 'tDPSTitleAprove'); ?></a></li>
                        <li id="oliDPSTitleConimg" class="active"><a><?php echo language('document/depositdoc/depositdoc', 'tDPSTitleConimg'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvDPSBtnGrpInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button id="obtDPSCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                            <?php endif; ?>
                        </div>
                        <div id="odvDPSBtnGrpAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button id="obtSOCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                    <button id="obtDPSPrintDoc" onclick="JSxDPSPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtDPSCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('document/depositdoc/depositdoc', 'tDPSCancel'); ?></button>
                                    <button id="obtDPSApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button>                                  
                                    <button id="obtDPSApproveDPS" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('document/depositdoc/depositdoc', 'tDPSEventDeposit'); ?></button>                                  
                                    <div  id="odvDPSBtnGrpSave" class="btn-group">
                                        <button id="obtDPSSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?php echo language('common/main/main', 'tSave'); ?></button>
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
    <div class="xCNMenuCump xCNDPSBrowseLine" id="odvMenuCump">&nbsp;</div>
    <div class="main-content">
        <div id="odvDPSContentPageDocument">
        </div>
    </div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a id="oahSOBrowseCallBack" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliDPSNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li id="oliSOBrowsePrevious" class="xWBtnPrevious"><a><?php echo language('common/main/main', 'tShowData'); ?> : <?php echo language('document/purchaseinvoice/purchaseinvoice', 'tSOTitleMenu'); ?></a></li>
                    <li class="active"><a><?php echo language('document/depositdoc/depositdoc', 'tDPSTitleAdd'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvDPSBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button id="obtSOBrowseSubmit" type="button" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/depositdoc/jDeposit.js"></script>








