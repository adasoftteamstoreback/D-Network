<input id="oetJOB1StaBrowse"        type="hidden" value="<?= $nBrowseType ?>">
<input id="oetJOB1CallBackOption"   type="hidden" value="<?= $tBrowseOption ?>">
<input id="oetJOB1JumpDocNo"        type="hidden" value="<?= $aParams['tDocNo'] ?>">
<input id="oetJOB1JumpBchCode"      type="hidden" value="<?= $aParams['tBchCode'] ?>">
<input id="oetJOB1JumpAgnCode"      type="hidden" value="<?= $aParams['tAgnCode'] ?>">

<div id="odvJR1MainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <ol id="oliJR1MenuNav" class="breadcrumb">
                    <?php FCNxHADDfavorite('docJR1/0/0');?>
                    <li id="oliJR1Title" class="active" style="cursor:pointer" onclick="JSvJR1CallPageList()"><?= language('document/jobrequest1/jobrequest1','tJR1Title')?></li>
                    <li id="oliJR1TitleAdd" class="active"><a><?= language('document/jobrequest1/jobrequest1','tJR1TitleAdd')?></a></li>
                    <li id="oliJR1TitleViewData" class="active"><a><?= language('document/jobrequest1/jobrequest1','tJR1ViewData')?></a></li>
                    <li id="oliJR1TitleEdit" class="active"><a><?php echo language('document/jobrequest1/jobrequest1', 'tJR1TitleEdit'); ?></a></li>
                </ol>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                <div id="odvJR1BtnGrpInfo">
                    <?php if ($aPermission['tAutStaFull'] == 1 || $aPermission['tAutStaAdd'] == 1) : ?>
                            <button id="obtJR1CallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                    <?php endif; ?>
                </div>
                <div id="odvBtnAddEdit">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <button id="obtBtnBack"         class="btn xCNBTNDefult xCNBTNDefult2Btn"   type="button"    onclick="JSvJR1CallPageList()"> <?=language('common/main/main', 'tBack')?></button>
                        <button id="obtJR1PrintDoc"     class="btn xCNBTNDefult xCNBTNDefult2Btn"   type="button"    onclick="JSxJR1PrintDoc()"> <?php echo language('common/main/main', 'tCMNPrint');?></button>
                        <button id="obtJR1CancelDoc"    class="btn xCNBTNDefult xCNBTNDefult2Btn"   type="button"    onclick="JSxJR1DocumentCancel(false)"> <?php echo language('common/main/main', 'tCancel'); ?></button>
                        <button id="obtJR1ApproveDoc"   class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"    onclick="JSxJR1DocumentApv(false)"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button>
                        <div  id="odvJR1BtnGrpSave" class="btn-group">
                            <button id="obtJR1SubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?php echo language('common/main/main', 'tSave'); ?></button>
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
    <div id="odvJR1PageDocument"></div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/jobrequeststep1/jJobRequestStep1.js"></script>
