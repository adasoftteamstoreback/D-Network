<input id="oetAPDStaBrowse"         type="hidden" value="<?= $nBrowseType?>">
<input id="oetAPDCallBackOption"    type="hidden" value="<?= $tBrowseOption?>">
<input id="oetAPDJumpDocNo"         type="hidden" value="<?= $tDocNo ?>">
<input id="oetAPDJumpBchCode"       type="hidden" value="<?= $tBchCode ?>">
<input id="oetAPDJumpAgnCode"       type="hidden" value="<?= $tAgnCode ?>">

<div id="odvAPDMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="xCNAPDVMaster">
                <div class="col-xs-12 col-md-5">
                    <ol id="oliMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('docAPDebitnote/0/0');?>
                        <li id="oliAPDTitle"        class="xCNLinkClick" onclick="JSvCallPageAPDList()"><?= language('document/apdebitnote/apdebitnote', 'tAPDTitle') ?></li>
                        <li id="oliAPDTitleAdd"     class="active"><a href="javascript:;"><?= language('document/apdebitnote/apdebitnote', 'tAPDTitleAdd') ?></a></li>
                        <li id="oliAPDTitleEdit"    class="active"><a href="javascript:;"><?= language('document/apdebitnote/apdebitnote', 'tAPDTitleEdit') ?></a></li>
                        <li id="oliAPDTitleDetail"  class="active"><a href="javascript:;"><?= language('document/apdebitnote/apdebitnote', 'tAPDTitleDetail') ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-7 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvBtnAPDInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageAPDAdd()">+</button>
                            <?php endif; ?>
                        </div>
                        <div id="odvBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button onclick="JSvCallPageAPDList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack') ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
                                    <button id="obtAPDPrintDoc" onclick="JSxAPDPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtAPDCancel"   onclick="JSnAPDCancel(false)" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel') ?></button>
                                    <button id="obtAPDApprove"  onclick="JSnAPDApprove(false)" class="btn xCNBTNPrimery xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove') ?></button>
                                    <div class="btn-group">
                                        <button type="button" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitAPD').click()"> <?php echo language('common/main/main', 'tSave') ?></button>
                                        <?php echo $vBtnSave ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="xCNAPDVBrowse">
                <div class="col-xs-12 col-md-6">
                    <a class="xWBtnPrevious xCNIconBack" style="float:left;font-size:19px;">
                        <i class="fa fa-arrow-left xCNIcon"></i>	
                    </a>
                    <ol id="oliAPDNavBrowse" class="breadcrumb xCNBCMenu" style="margin-left:25px">
                        <li class="xWBtnPrevious"><a><?= language('common/main/main', 'tShowData') ?> : <?= language('document/apdebitnote/apdebitnote', 'tAPDTitle') ?></a></li>
                        <li class="active"><a><?= language('common/main/main', 'tAddData') ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-6 text-right">
                    <div id="odvAPDBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                        <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitAPD').click()"><?= language('common/main/main', 'tSave') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="xCNMenuCump xCNAPDBrowseLine" id="odvMenuCump">&nbsp;</div>

<div class="main-content">
    <div id="odvContentPageAPD"></div>
</div>

<div class="modal fade" id="odvAPDSelectDocTypePopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/apdebitnote/apdebitnote','tAPDMemoType');?></label>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="fancy-radio">
                        <label class="fancy-checkbox custom-bgcolor-blue">
                            <input type="radio" name="orbAPDSelectDocType" checked="true" value="6">
                            <span><i></i><?php echo language('document/apdebitnote/apdebitnote','tAPDSendAndReceive');?></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="fancy-radio">
                        <label class="fancy-checkbox custom-bgcolor-blue">
                            <input type="radio" name="orbAPDSelectDocType" value="7">
                            <span><i></i><?php echo language('document/apdebitnote/apdebitnote','tAPDProductAmount');?></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="obtnAPDConfirmSelectDocType" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tBaseURL        = '<?php echo base_url(); ?>';
    var nOptDecimalShow = '<?php echo $nOptDecimalShow; ?>';
    var nOptDecimalSave = '<?php echo $nOptDecimalSave; ?>';
    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit");?>';
    var tUsrApv         = '<?php echo $this->session->userdata("tSesUsername");?>';
</script>

<script type="text/javascript" src="<?php echo base_url('application/modules/document/assets/src/apdebitnote/jAPDebitnote.js'); ?>"></script>