<input id="oetLOGStaBrowse"        type="hidden" value="<?= $nBrowseType ?>">
<input id="oetLOGCallBackOption"   type="hidden" value="<?= $tBrowseOption ?>">

<div id="odvJR1MainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <ol id="oliJR1MenuNav" class="breadcrumb">
                    <?php FCNxHADDfavorite('docJR1/0/0');?>
                    <li id="oliJR1Title" class="active" style="cursor:pointer" onclick="JSvCallPageLOGList()"><?= language('settingconfig/logmonitor/logmonitor','tLOGTitle')?></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="xCNMenuCump xCNRDHBrowseLine" id="odvMenuCump">&nbsp;</div>
<div class="main-content">
    <div id="odvLOGPageDocument"></div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/settingconfig/assets/src/logmonitor/jLogMonitor.js"></script>
