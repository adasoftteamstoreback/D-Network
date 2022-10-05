<style>
    .xWTdDisable {
        cursor: not-allowed !important;
        opacity: 0.4 !important;
    }

    .xWImgDisable {
        cursor: not-allowed !important;
        pointer-events: none;
    }

    .xWRateing {
        display: flex;
        transform: rotateY(180deg);
    }

    .xWRateing input {
        display: none;

    }

    .xWRateing label {
        display: block;
        cursor: pointer;
        width: 25px;
    }

    .xWRateing label:before {
        content: '\f005';
        font-family: fontAwesome;
        position: absolute;
        display: block;
        font-size: 20px;
        color: #eaeaea;
    }

    .xWRateing label:after {
        content: '\f005';
        font-family: fontAwesome;
        position: absolute;
        display: block;
        font-size: 20px;
        color: #179BFD;
        top: 0;
        opacity: 0;
        transition: .3s;
        text-shadow: 0 1px 2px rgba(0, 0, 0, .5);
    }


    .xWRateing input:checked~label:after {
        opacity: 1;
    }

    input[type="radio"].xWDisabled:disabled {
        -webkit-appearance: none;
        display: inline-block;
        width: 12px;
        height: 12px;
        padding: 0px;
        background-clip: content-box;
        border: 2px solid #bbbbbb;
        background-color: white;
        border-radius: 50%;
    }

    input[type="radio"].xWDisabled:checked {
        border: 2px solid #1580ff;
        background-color: #0075ff;
    }

    input[type="checkbox"][readonly] {
        pointer-events: none;
    }

    .xWFontSpan {
        font-size: 16px !important;
    }
</style>

<input id="oetSATJumpDocNo" type="hidden" value="<?= @$aParams['tDocNo'] ?>">
<input id="oetSATJumpBchCode" type="hidden" value="<?= @$aParams['tBchCode'] ?>">
<input id="oetSATJumpAgnCode" type="hidden" value="<?= @$aParams['tAgnCode'] ?>">

<div id="odvSpaMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-md-6">
                <ol id="oliSatMenuNav" class="breadcrumb">
                    <?php FCNxHADDfavorite('docBackupCleanup'); ?>
                    <li id="oliBACUTitle" class="active" style="cursor:pointer"><?= language('document/backupandcleanup/backupandcleanup', 'tBACUTitle') ?></li>
                    <li id="oliBACUTitleViewData" class="active"><a><?= language('document/backupandcleanup/backupandcleanup', 'tBACUTitleCancel') ?></a></li>
                </ol>
            </div>
            <div class="col-xs-12 col-md-6 text-right p-r-0">
                <div id="odvSatSvBtnGrpInfo">
                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                        <button id="obtSatSvCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                    <?php endif; ?>
                </div>
                <div id="odvBtnAddEdit">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <!-- <button id="obtBtnBack" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"><?= language('common/main/main', 'tCancel') ?></button> -->
                        <button id="obtSatSvCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel'); ?></button>
                        <button id="obtSatSvApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button>
                        <button id="obtBACUPurgeApprove" type="button" class="btn xCNBTNPrimery xCNBTNPrimery2Btn"> <?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xCNMenuCump xCNRDHBrowseLine" id="odvMenuCump">&nbsp;</div>
<div class="main-content">
    <div id="odvBACUPageDocument" class="panel panel-headline">
    </div>
</div>

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
<div id="odvBACUModalAppoveDoc" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'สำรอง/ล้างข้อมูล'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong><?php echo language('common/main/main', 'คุณต้องการยืนยัน การ สำรอง/ล้างข้อมูล หรือไม่?'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxBACUApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
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

<!--Modal Success-->
<div class="modal fade" id="odvBACUPurgeSuccess">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('interface/interfaceexport/interfaceexport','tStatusProcess'); ?></h5>
            </div>
            <div class="modal-body">
                <p><?=language('interface/interfaceexport/interfaceexport','tContentProcess'); ?></p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" id="obtBACUModalMsgConfirm" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
				<button type="button" id="obtBACUModalMsgCancel" class="btn xCNBTNDefult" data-dismiss="modal">
					<?php echo language('common/main/main', 'tModalCancel'); ?>
				</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/backupandcleanup/jBackupandCleanup.js"></script>