<input id="oetSOStaBrowse" type="hidden" value="<?php echo $nSOBrowseType ?>">
<input id="oetSOCallBackOption" type="hidden" value="<?php echo $tSOBrowseOption ?>">
<input id="ohdUsrCodeChk" type="hidden" value="<?php echo $_SESSION["tSesUserCode"] ?>">

<?php if (isset($nSOBrowseType) && $nSOBrowseType == 0) : ?>
    <div id="odvSOMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <ol id="oliSOMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('dcmSO/0/0'); ?>
                        <li id="oliSOTitle" style="cursor:pointer;"><?php echo language('document/saleorderdata/saleorderdata', 'tSODTitleMenu'); ?></li>
                        <li id="oliSOTitleAdd" class="active"><a><?php echo language('document/saleorderdata/saleorderdata', 'tSOTitleAdd'); ?></a></li>
                        <li id="oliSOTitleFranChise" class="active"><a><?php echo language('document/saleorderdata/saleorderdata', 'tPoFrancise'); ?></a></li>
                        <li id="oliSOTitleEdit" class="active"><a><?php echo language('document/saleorderdata/saleorderdata', 'tSOTitleEdit'); ?></a></li>
                        <li id="oliSOTitleDetail" class="active"><a><?php echo language('document/saleorderdata/saleorderdata', 'tSOTitleDetail'); ?></a></li>
                        <li id="oliSOTitleAprove" class="active"><a><?php echo language('document/saleorderdata/saleorderdata', 'tSOTitleAprove'); ?></a></li>
                        <li id="oliSOTitleConimg" class="active"><a><?php echo language('document/saleorderdata/saleorderdata', 'tSOTitleConimg'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div class="input-group xCNHide">
                                    <input class="form-control xCNInpuTXOthoutSingleQuote " type="text" id="oetSODSearchSoSCAN" name="oetSODSearchSoSCAN" placeholder="<?php echo language('document/saleorderdata/saleorderdata', 'tSOFillTextScan') ?>" autocomplete="off" value =''>
                                    <span class="input-group-btn">
                                        <button id="obtSODSerchForCRV" type="button" class="btn xCNBtnDateTime"><img class="xCNIconTable" src="<?= base_url('application/modules/common/assets/images/icons/scanner.png'); ?>"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div id="odvSOBtnGrpInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <!-- <button id="obtSOCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button> -->
                            <?php endif; ?>
                        </div>
                        <div id="odvSOBtnGrpAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button id="obtSOCallBackPage" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?= language('common/main/main', 'tBack'); ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
                                    <button id="obtSOPrintDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" onclick="JSxSOPrintDoc()"> <?= language('common/main/main', 'tCMNPrint'); ?></button>
                                    <button id="obtSOCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?= language('common/main/main', 'tCancel'); ?></button>
                                    <button id="obtSOCreatePCK" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> สร้างใบจัด</button>
                                    <button id="obtSOApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?= language('common/main/main', 'tCMNApprove'); ?></button>
                                    <div id="odvSOBtnGrpSave" class="btn-group">
                                        <button id="obtSOSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?= language('common/main/main', 'tSave'); ?></button>
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
                    <label class="xCNTextModalHeard"><?php echo language('document/creditnote/creditnote', 'เลือกประเภทใบสั่งขาย'); ?></label>
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
<?php else : ?>
    <div class="modal-header xCNModalHead">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <a id="oahSOBrowseCallBack" class="xWBtnPrevious xCNIconBack" style="float:left;">
                    <i class="fa fa-arrow-left xCNIcon"></i>
                </a>
                <ol id="oliSONavBrowse" class="breadcrumb xCNMenuModalBrowse">
                    <li id="oliSOBrowsePrevious" class="xWBtnPrevious"><a><?php echo language('common/main/main', 'tShowData'); ?> : <?php echo language('document/purchaseinvoice/purchaseinvoice', 'tSOTitleMenu'); ?></a></li>
                    <li class="active"><a><?php echo language('document/saleorderdata/saleorderdata', 'tSOTitleAdd'); ?></a></li>
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
<!-- ======================================================================== View Modal PCK Document  ======================================================================== -->
<div id="odvChkLoginModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong><h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('document/saleorder/saleorder', 'ตรวจสอบสิทธิ์การรับของ'); ?></strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:25px;">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'ชื่อผู้ใช้'); ?></label>
                        <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" id="oetSODFillUser" name="oetSODFillUser" autocomplete="off">
                        <input class="form-control xCNHide" type="text" id="oetSODocFill" name="oetSODocFill" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/saleorderdata/saleorderdata', 'กรอกรหัสผ่าน'); ?></label>
                        <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" style='-webkit-text-security: disc;' id="oetSODFillPass" name="oetSODFillPass" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="JSxSOFillUserDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal FailItem  ======================================================================== -->
<div id="odvSODToCRVDataModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'ดูประวัติการรับของ'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>เลือกเลขที่ใบรับของ</p>
                <div id ="odvGetOption">
                <select class=" form-control" id="ocmCRVShow" name="ocmCRVShow">
                </select>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="JSxCRVSubmit(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/saleorderdata/jSaleorderdata.js"></script>