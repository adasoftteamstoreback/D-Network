<style>
    .xWTdDisable {
        cursor: not-allowed !important;
        opacity: 0.4 !important;
    }

    .xWImgDisable {
        cursor: not-allowed !important;
        pointer-events: none;
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
    <div id="odvSpaMainMenu" class="main-menu">
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-md-6">
                    <ol id="oliSatMenuNav" class="breadcrumb">
                        <?php FCNxHADDfavorite('docdailyworkorder'); ?>
                        <li id="oliSatTitle" class="active" style="cursor:pointer" onclick="JSvDWOCallPageSearch('')"><?= language('document/dailyworkorder/dailyworkorder', 'tDailyWorkTitle') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="xCNMenuCump xCNRDHBrowseLine" id="odvMenuCump">&nbsp;</div>
    <div class="main-content">
        <div id="odvDWOPageDocument"></div>
    </div>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/dailyworkorder/jDailyworkorder.js"></script>