<input id="oetDOStaBrowse" type="hidden" value="<?php echo $nDOBrowseType ?>">
<input id="oetDOCallBackOption" type="hidden" value="<?php echo $tDOBrowseOption ?>">
<input id="oetDOJumpDocNo" type="hidden" value="<?php echo $aParams['tDocNo'] ?>">
<input id="oetDOJumpBchCode" type="hidden" value="<?php echo $aParams['tBchCode'] ?>">
<input id="oetDOJumpAgnCode" type="hidden" value="<?php echo $aParams['tAgnCode'] ?>">

<?php if (isset($nDOBrowseType) && ( $nDOBrowseType == 0 || $nDOBrowseType ==2) ) : ?>
    <div id="odvDOMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ol id="oliDOMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('docDO/0/0');?>
                        <li id="oliDOTitle" style="cursor:pointer;"><?php echo language('document/deliveryorder/deliveryorder', 'tDOTitleMenu'); ?></li>
                        <li id="oliDOTitleAdd" class="active"><a><?php echo language('document/deliveryorder/deliveryorder', 'tDOTitleAdd'); ?></a></li>
                        <li id="oliDOTitleEdit" class="active"><a><?php echo language('document/deliveryorder/deliveryorder', 'tDOTitleEdit'); ?></a></li>
                        <li id="oliDOTitleDetail" class="active"><a><?php echo language('document/deliveryorder/deliveryorder', 'tDOTitleDetail'); ?></a></li>
                        <li id="oliDOTitleAprove" class="active"><a><?php echo language('document/deliveryorder/deliveryorder', 'tDOTitleAprove'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvDOBtnGrpInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button id="obtDOCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                            <?php endif; ?>
                        </div>
                        <div id="odvDOBtnGrpAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button id="obtDOCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                    <button id="obtDOPrintDoc" onclick="JSxDOPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtDOCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel'); ?></button>
                                    <button id="obtDOApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button>  
                                    <button id="obtDOGenSO" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button">สร้างใบสั่งขาย</button>  
                                    <div  id="odvDOBtnGrpSave" class="btn-group">
                                        <button id="obtDOSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?php echo language('common/main/main', 'tSave'); ?></button>
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
    <div class="xCNMenuCump xCNDOBrowseLine" id="odvMenuCump">&nbsp;</div>
    <div class="main-content">
        <div id="odvDOContentPageDocument">
        </div>
    </div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a id="oahDOBrowseCallBack" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliDONavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li id="oliDOBrowsePrevious" class="xWBtnPrevious"><a><?php echo language('common/main/main', 'tShowData'); ?> : <?php echo language('document/purchaseinvoice/purchaseinvoice', 'tDOTitleMenu'); ?></a></li>
                    <li class="active"><a><?php echo language('document/purchaseorder/purchaseorder', 'tDOTitleAdd'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvDOBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button id="obtDOBrowseSubmit" type="button" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/deliveryorder/jDeliveryorder.js"></script>








