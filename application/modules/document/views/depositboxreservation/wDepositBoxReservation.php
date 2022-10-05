<input id="oetDBRStaBrowse" type="hidden" value="<?php echo $nDBRBrowseType ?>">
<input id="oetDBRCallBackOption" type="hidden" value="<?php echo $tDBRBrowseOption ?>">
<input id="oetDBRJumpDocNo" type="hidden" value="<?php echo $aParams['tDocNo'] ?>">
<input id="oetDBRJumpBchCode" type="hidden" value="<?php echo $aParams['tBchCode'] ?>">
<input id="oetDBRJumpAgnCode" type="hidden" value="<?php echo $aParams['tAgnCode'] ?>">
<input id="oetCheckJumpStatus"      type="hidden" value="<?= @$aParams['tCheckJump'] ?>">
<input id="oetCheckJumpType"      type="hidden" value="<?= @$aParams['tCheckType'] ?>">
<input id="oetCheckBackTo"      type="hidden" value="<?= @$aParams['tCheckBackTo'] ?>">

<?php if (isset($nDBRBrowseType) && ( $nDBRBrowseType == 0 || $nDBRBrowseType ==2) ) : ?>
    <div id="odvDBRMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <ol id="oliDBRMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('docDBR/0/0');?>
                        <li id="oliDBRTitle" style="cursor:pointer;"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTitleMenu'); ?></li>
                        <li id="oliDBRTitleAdd" class="active"><a><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTitleAdd'); ?></a></li>
                        <li id="oliDBRTitleEdit" class="active"><a><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTitleEdit'); ?></a></li>
                        <li id="oliDBRTitleDetail" class="active"><a><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTitleDetail'); ?></a></li>
                        <li id="oliDBRTitleAprove" class="active"><a><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTitleAprove'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvDBRBtnGrpInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button id="obtDBRCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                            <?php endif; ?>
                        </div>
                        <div id="odvDBRBtnGrpAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button id="obtDBRCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                    <button id="obtDBRPrintDoc1" onclick="JSxDBRPrintDocABB()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'พิมพ์ใบเสร็จ'); ?></button>
                                    <button id="obtDBRPrintDoc2" onclick="JSxDBRPrintDocTAX()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'พิมพ์ใบกำกับภาษี'); ?></button>
                                    <button id="obtDBRPrintDoc" onclick="JSxDBRPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'พิมพ์ใบจองช่องฝาก'); ?></button>
                                    <button id="obtDBRCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel'); ?></button>
                                    <button id="obtDBRApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button>  
                                    <button id="obtDBRGenSO" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button">สร้างใบสั่งขาย</button>  
                                    <div  id="odvDBRBtnGrpSave" class="btn-group">
                                        <button id="obtDBRSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?php echo language('common/main/main', 'tSave'); ?></button>
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
    <div class="xCNMenuCump xCNDBRBrowseLine" id="odvMenuCump">&nbsp;</div>
    <div class="main-content">
        <div id="odvDBRContentPageDocument">
        </div>
    </div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a id="oahDBRBrowseCallBack" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliDBRNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li id="oliDBRBrowsePrevious" class="xWBtnPrevious"><a><?php echo language('common/main/main', 'tShowData'); ?> : <?php echo language('document/purchaseinvoice/purchaseinvoice', 'tDBRTitleMenu'); ?></a></li>
                    <li class="active"><a><?php echo language('document/purchaseorder/purchaseorder', 'tDBRTitleAdd'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvDBRBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button id="obtDBRBrowseSubmit" type="button" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/depositboxreservation/jDepositboxreservation.js?v=2"></script>







