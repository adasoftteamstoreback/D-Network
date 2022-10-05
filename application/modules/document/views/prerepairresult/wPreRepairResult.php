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

    /* สี highlight datepicker */
    /* .datepicker table tr td.today, .datepicker table tr td.today:hover, .datepicker table tr td.today.disabled, .datepicker table tr td.today.disabled:hover {
        background-color: #fde19a;
        background-image: linear-gradient(to bottom, #08c, #0044cc);
        background-repeat: repeat-x;
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fdd49a', endColorstr='#fdf59a', GradientType=0);
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
        color: #fff;
    } */

    .xWFontSpan {
        font-size: 16px !important;
    }
</style>

<input id="oetSATJumpDocNo" type="hidden" value="<?= @$aParams['tDocNo'] ?>">
<input id="oetSATJumpBchCode" type="hidden" value="<?= @$aParams['tBchCode'] ?>">
<input id="oetSATJumpAgnCode" type="hidden" value="<?= @$aParams['tAgnCode'] ?>">
<input id="oetCheckJumpStatus" type="hidden" value="<?= @$aParams['tCheckJump'] ?>">

<div id="odvSpaMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-md-6">
                <ol id="oliPreMenuNav" class="breadcrumb">
                    <?php
                    FCNxHADDfavorite('docPreRepairResult');
                    ?>
                    <li id="oliPreTitle" class="active" style="cursor:pointer" onclick="JSvPreSvCallPageList()"><?= language('document/prerepairresult/prerepairresult', 'tPreSurveyTitle') ?></li>
                    <li id="oliPreTitleAdd" class="active"><a><?= language('document/prerepairresult/prerepairresult', 'tPreSurveyTitleAdd') ?></a></li>
                    <li id="oliPreTitleViewData" class="active"><a><?= language('document/prerepairresult/prerepairresult', 'tPreSurveyViewData') ?></a></li>
                    <li id="oliPreTitleEdit" class="active"><a><?php echo language('document/deliveryorder/deliveryorder', 'tDOTitleEdit'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-12 col-md-6 text-right p-r-0">
                <div id="odvPreSvBtnGrpInfo">
                    <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                        <button id="obtPreSvCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                    <?php endif; ?>
                </div>
                <div id="odvBtnAddEdit">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <button id="obtBtnBack" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?= language('common/main/main', 'tBack') ?></button>
                        <button id="obtPreSvPrintDoc" onclick="JSxPrePrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
                        <button id="obtPreSvCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel'); ?></button>
                        <!-- ซ่อน ปุ่มอนุมัติ จะย้ายไปอยู่ที่ ปิดใบสั่งงาน อนุมัติพร้อมกันที่เดียว 2 ใบ ใบสั่งงาน และตรวจสอบสภาพหลังซ่อม -->
                        <!-- <button id="obtPreSvApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button> -->
                        <div id="odvPreSvBtnGrpSave" class="btn-group">
                            <button id="obtPreSvSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?php echo language('common/main/main', 'tSave'); ?></button>
                            <?php echo $vBtnSave ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xCNMenuCump xCNRDHBrowseLine" id="odvMenuCump">&nbsp;</div>
<div class="main-content">
    <div id="odvPreSvPageDocument">
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/prerepairresult/jPrerepairresult.js"></script>