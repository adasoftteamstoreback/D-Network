<input id="oetCRVStaBrowse" type="hidden" value="<?php echo $nCRVBrowseType ?>">
<input id="oetCRVCallBackOption" type="hidden" value="<?php echo $tCRVBrowseOption ?>">
<input id="oetCRVJumpDocNo" type="hidden" value="<?php echo $aParams['tDocNo'] ?>">
<input id="oetCRVJumpBchCode" type="hidden" value="<?php echo $aParams['tBchCode'] ?>">
<input id="oetCRVJumpAgnCode" type="hidden" value="<?php echo $aParams['tAgnCode'] ?>">
<input id="oetCheckJumpStatus"      type="hidden" value="<?= @$aParams['tCheckJump'] ?>">
<input id="ohdCRVUsrCodeChk" type="hidden" value="<?php echo $_SESSION["tSesUserCode"] ?>">


<?php if (isset($nCRVBrowseType) && ( $nCRVBrowseType == 0 || $nCRVBrowseType ==2) ) : ?>
    <div id="odvCRVMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ol id="oliCRVMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('docCRV/0/0');?>
                        <li id="oliCRVTitle" style="cursor:pointer;"><?php echo language('document/customerreceived/customerreceived', 'tCRVTitleMenu'); ?></li>
                        <li id="oliCRVTitleAdd" class="active"><a><?php echo language('document/customerreceived/customerreceived', 'tCRVTitleAdd'); ?></a></li>
                        <li id="oliCRVTitleEdit" class="active"><a><?php echo language('document/customerreceived/customerreceived', 'tCRVTitleEdit'); ?></a></li>
                        <li id="oliCRVTitleDetail" class="active"><a><?php echo language('document/customerreceived/customerreceived', 'tCRVTitleDetail'); ?></a></li>
                        <li id="oliCRVTitleAprove" class="active"><a><?php echo language('document/customerreceived/customerreceived', 'tCRVTitleAprove'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <!-- <div id="odvCRVBtnGrpInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button id="obtCRVCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                            <?php endif; ?>
                        </div> -->
                        <div id="odvCRVBtnGrpAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button id="obtCRVCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack'); ?></button>
                                <button id="obtCRVApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'ยืนยันรับของ'); ?></button>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="xCNMenuCump xCNCRVBrowseLine" id="odvMenuCump">&nbsp;</div>
    <div class="main-content">
        <div id="odvCRVContentPageDocument">
        </div>
    </div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a id="oahCRVBrowseCallBack" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliCRVNavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li id="oliCRVBrowsePrevious" class="xWBtnPrevious"><a><?php echo language('common/main/main', 'tShowData'); ?> : <?php echo language('document/purchaseinvoice/purchaseinvoice', 'tCRVTitleMenu'); ?></a></li>
                    <li class="active"><a><?php echo language('document/purchaseorder/purchaseorder', 'tCRVTitleAdd'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvCRVBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button id="obtCRVBrowseSubmit" type="button" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>

<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/customerreceived/jCustomerReceived.js"></script>







