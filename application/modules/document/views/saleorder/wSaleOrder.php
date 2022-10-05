<input id="oetSOStaBrowse" type="hidden" value="<?php echo $nSOBrowseType ?>">
<input id="oetSOCallBackOption" type="hidden" value="<?php echo $tSOBrowseOption ?>">
<input id="oetDBRJumpDocNo" type="hidden" value="<?php echo $aParams['tDocNo'] ?>">
<input id="oetCheckJumpType"      type="hidden" value="<?= @$aParams['tCheckType'] ?>">
<input id="oetCheckBackTo"      type="hidden" value="<?= @$aParams['tCheckBackTo'] ?>">

<?php if (isset($nSOBrowseType) && $nSOBrowseType == 0) : ?>

    <input id="oetCheckJumpStatus" type="hidden" value="<?= @$aParams['tCheckJump'] ?>">
    <div id="odvSOMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ol id="oliSOMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('dcmSO/0/0');?>
                        <?php if($aParams['tCheckJump'] != 1){ ?>
                        <li id="oliSOTitle" style="cursor:pointer;"><?php echo language('document/saleorder/saleorder', 'tSOTitleMenu'); ?></li>
                        <?php }else{ ?>
                        <li id="oliSOTitle" style="cursor:pointer;"><?php echo language('document/saleorderdata/saleorderdata', 'tSODTitleMenu'); ?></li>
                        <?php }?>
                        <li id="oliSOTitleAdd" class="active"><a><?php echo language('document/saleorder/saleorder', 'tSOTitleAdd'); ?></a></li>
                        <li id="oliSOTitleFranChise" class="active"><a><?php echo language('document/saleorder/saleorder', 'tPoFrancise'); ?></a></li>
                        <li id="oliSOTitleEdit" class="active"><a><?php echo language('document/saleorder/saleorder', 'tSOTitleEdit'); ?></a></li>
                        <li id="oliSOTitleDetail" class="active"><a><?php echo language('document/saleorder/saleorder', 'tSOTitleDetail'); ?></a></li>
                        <li id="oliSOTitleAprove" class="active"><a><?php echo language('document/saleorder/saleorder', 'tSOTitleAprove'); ?></a></li>
                        <li id="oliSOTitleConimg" class="active"><a><?php echo language('document/saleorder/saleorder', 'tSOTitleConimg'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvSOBtnGrpInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button id="obtSOCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                            <?php endif; ?>
                        </div>
                        <div id="odvSOBtnGrpAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button id="obtSOCallBackPage"  class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)): ?>
                                    <button id="obtSOPrintDoc"      class="btn xCNBTNDefult xCNBTNDefult2Btn"       type="button" onclick="JSxSOPrintDoc()"> <?=language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtSOCancelDoc"     class="btn xCNBTNDefult xCNBTNDefult2Btn"       type="button"> <?=language('common/main/main', 'tCancel'); ?></button>
                                    <button id="obtSOCreatePCK"     class="btn xCNBTNPrimery xCNBTNPrimery2Btn"     type="button"> สร้างใบจัด</button>                                  
                                    <button id="obtSOApproveDoc"    class="btn xCNBTNPrimery xCNBTNPrimery2Btn"     type="button"> <?=language('common/main/main', 'tCMNApprove'); ?></button>                                  
                                    <div  id="odvSOBtnGrpSave"      class="btn-group">
                                        <button id="obtSOSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?=language('common/main/main', 'tSave'); ?></button>
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
    <div class="xCNMenuCump xCNSOBrowseLine" id="odvMenuCump">&nbsp;</div>
    <div class="main-content">
        <div id="odvSOContentPageDocument">
        </div>
    </div>
    <div class="modal fade" id="odvSOSelectDocTypePopup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('document/creditnote/creditnote','เลือกประเภทใบสั่งขาย');?></label>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="fancy-radio">
                            <label class="fancy-checkbox custom-bgcolor-blue">
                                <input type="radio" name="orbSOSelectDocType" checked="true" value="1">
                                <span><i></i>ลูกค้าทั่วไป</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="fancy-radio">
                            <label class="fancy-checkbox custom-bgcolor-blue">
                                <input type="radio" name="orbSOSelectDocType" value="2">
                                <span><i></i>ลูกค้า/แฟรนไซส์</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="obtnSOConfirmSelectDocType" type="button" class="btn xCNBTNPrimery">
                        <?php echo language('common/main/main', 'tModalConfirm'); ?>
                    </button>
                    <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                        <?php echo language('common/main/main', 'tModalCancel'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a id="oahSOBrowseCallBack" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>	
                </a>
                <ol id="oliSONavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li id="oliSOBrowsePrevious" class="xWBtnPrevious"><a><?php echo language('common/main/main', 'tShowData'); ?> : <?php echo language('document/purchaseinvoice/purchaseinvoice', 'tSOTitleMenu'); ?></a></li>
                    <li class="active"><a><?php echo language('document/saleorder/saleorder', 'tSOTitleAdd'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <div id="odvSOBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                    <button id="obtSOBrowseSubmit" type="button" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="odvModalBodyBrowse" class="modal-body xCNModalBodyAdd">
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/saleorder/jSaleorder.js"></script>








