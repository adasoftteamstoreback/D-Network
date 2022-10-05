<input id="oetPNStaBrowse" type="hidden" value="<?=$nBrowseType?>">
<input id="oetPNCallBackOption" type="hidden" value="<?=$tBrowseOption?>">

<div id="odvPNMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="xCNPNVMaster">
                <div class="col-xs-12 col-md-5">
                    <ol id="oliMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('docPN/0/0');?>
                        <li id="oliPNTitle" class="xCNLinkClick" onclick="JSvCallPagePNList()"><?= language('document/purchasereturn/purchasereturn', 'tPNTitle') ?></li>
                        <li id="oliPNTitleAdd" class="active"><a href="javascript:;"><?= language('document/purchasereturn/purchasereturn', 'tPNTitleAdd') ?></a></li>
                        <li id="oliPNTitleEdit" class="active"><a href="javascript:;"><?= language('document/purchasereturn/purchasereturn', 'tPNTitleEdit') ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-7 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvBtnPNInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPagePNAdd()">+</button>
                            <?php endif; ?>
                        </div>
                        <div id="odvBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button onclick="JSvCallPagePNList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack') ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
                                    <button id="obtPNPrintDoc" onclick="JSxPNPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtPNCancel" onclick="JSnPNCancel(false)" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel') ?></button>
                                    <button id="obtPNApprove" onclick="JSnPNApprove(false)" class="btn xCNBTNPrimery xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove') ?></button>
                                    <div class="btn-group">
                                        <button type="button" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitPN').click()"> <?php echo language('common/main/main', 'tSave') ?></button>
                                        <?php echo $vBtnSave ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="xCNPNVBrowse">
                <div class="col-xs-12 col-md-6">
                    <a class="xWBtnPrevious xCNIconBack" style="float:left;font-size:19px;">
                        <i class="fa fa-arrow-left xCNIcon"></i>	
                    </a>
                    <ol id="oliPNNavBrowse" class="breadcrumb xCNBCMenu" style="margin-left:25px">
                        <li class="xWBtnPrevious"><a><?= language('common/main/main', 'tShowData') ?> : <?= language('promotion/promotion/promotion', 'tPMTTitle') ?></a></li>
                        <li class="active"><a><?= language('common/main/main', 'tAddData') ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-6 text-right">
                    <div id="odvPNBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                        <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitPN').click()"><?= language('common/main/main', 'tSave') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="xCNMenuCump xCNPNBrowseLine" id="odvMenuCump">&nbsp;</div>

<div class="main-content">
    <div id="odvContentPagePN"></div>
</div>

<div class="modal fade" id="odvPNSelectDocTypePopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/purchasereturn/purchasereturn','tPNMemoType');?></label>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="fancy-radio">
                        <label class="fancy-checkbox custom-bgcolor-blue">
                            <input type="radio" name="orbPNSelectDocType" checked="true" value="6">
                            <span><i></i><?php echo language('document/purchasereturn/purchasereturn','tPNSendAndReceive');?></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="fancy-radio">
                        <label class="fancy-checkbox custom-bgcolor-blue">
                            <input type="radio" name="orbPNSelectDocType" value="7">
                            <span><i></i><?php echo language('document/purchasereturn/purchasereturn','tPNProductAmount');?></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="obtnPNConfirmSelectDocType" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var tBaseURL        = '<?php echo base_url(); ?>';
    //tSys Decimal Show
    var nOptDecimalShow = '<?php echo $nOptDecimalShow; ?>';
    var nOptDecimalSave = '<?php echo $nOptDecimalSave; ?>';
    // Set Lang Edit 
    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit");?>';
    var tUsrApv         = '<?php echo $this->session->userdata("tSesUsername");?>';
</script>

<script type="text/javascript" src="<?php echo base_url('application/modules/document/assets/src/purchasereturn/jPurchasereturn.js'); ?>"></script>



