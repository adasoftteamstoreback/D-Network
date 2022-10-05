<!-- 1:ใบขอซื้อแบบสำนักงานใหญ่ , 2:ใบขอซื้อแบบแฟรนไชส์-->
<input id="ohdPRSTypeDocument"      type="hidden" value="<?=$tPRSTypeDocument?>">
<input id="oetPRSStaBrowse"         type="hidden" value="<?=$nPrsBrowseType ?>">
<input id="oetPRSCallBackOption"    type="hidden" value="<?=$tPrsBrowseOption ?>">
<input id="oetPRSJumpDocNo"         type="hidden" value="<?=$aParams['tDocNo'] ?>">
<input id="oetPRSJumpBchCode"       type="hidden" value="<?=$aParams['tBchCode'] ?>">
<input id="oetPRSJumpAgnCode"       type="hidden" value="<?=$aParams['tAgnCode'] ?>">

<?php if (isset($nPrsBrowseType) &&  ( $nPrsBrowseType == 0 || $nPrsBrowseType ==2)) : ?>
    <div id="odvPRSMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ol id="oliPRSMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite(@$tRoute);?>
                        <li id="oliPrsTitle"        style="cursor:pointer;">
                            <?php 
                                if(@$tPRSTypeDocument == 1){ //ใบขอซื้อแบบสำนักงานใหญ่
                                    echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSTitleMenu'); 
                                }else{ //ใบขอซื้อแบบแฟรนไชส์
                                    echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSTitleMenuFN');
                                }   
                            ?>
                        </li>
                        <li id="oliPrsTitleAdd"     class="active"><a><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSTitleAdd'); ?></a></li>
                        <li id="oliPrsTitleEdit"    class="active"><a><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSTitleEdit'); ?></a></li>
                        <li id="oliPrsTitleDetail"  class="active"><a><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSTitleDetail'); ?></a></li>
                        <li id="oliPrsTitleAprove"  class="active"><a><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSTitleAprove'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvPRSBtnGrpInfo">
                            <?php 
                                if(@$tPRSTypeDocument == 1){ //ใบขอซื้อแบบสำนักงานใหญ่ ?>
                                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                        <button id="obtPRSCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                                    <?php endif; ?>
                                <?php }else{ //ใบขอซื้อแบบแฟรนไชส์ ?>
                                   
                                <?php } ?>
                        </div>
                        <div id="odvPRSBtnGrpAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button id="obtPRSCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                    <button id="obtPRSPrintDoc" onclick="JSxPRSPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtPRSCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tCancel'); ?></button>
                                    <button id="obtPRSApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?=language('common/main/main', 'tCMNApprove'); ?></button> 
                                    <button id="obtPRSApproveDocHQ" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?=language('common/main/main', 'tCMNApprove'); ?>ขอซื้อ</button>                                                                   
                                    <div  id="odvPRSBtnGrpSave" class="btn-group">
                                        <button id="obtPRSSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?=language('common/main/main', 'tSave'); ?></button>
                                        <?=$vBtnSave ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="xCNMenuCump xCNPRSBrowseLine" id="odvMenuCump">&nbsp;</div>
    <div class="main-content">
        <div id="odvPRSContentPageDocument"></div>
    </div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a id="oahPRSBrowseCallBack" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliPRSNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li id="oliPRSBrowsePrevious" class="xWBtnPrevious"><a><?=language('common/main/main', 'tShowData'); ?> : <?=language('document/purchaseinvoice/purchaseinvoice', 'tPRSTitleMenu'); ?></a></li>
                    <li class="active"><a><?=language('document/purchaseorder/purchaseorder', 'tPRSTitleAdd'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvPRSBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button id="obtPRSBrowseSubmit" type="button" class="btn xCNBTNPrimery"><?=language('common/main/main', 'tSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif; ?>

<script type="text/javascript" src="<?=base_url(); ?>application/modules/document/assets/src/supplierpurchaserequisition/jSupplierPurchaseRequisition.js"></script>