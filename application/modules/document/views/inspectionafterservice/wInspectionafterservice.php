<style>
    .xWTdDisable {
        cursor: not-allowed !important;
        opacity: 0.4 !important;
    }

    .xWImgDisable {
        cursor: not-allowed !important;
        pointer-events: none;
    }

    input[type="radio"].xWDisabled:disabled  {
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

    input[type="radio"].xWDisabled:checked{
        border: 2px solid #1580ff;
        background-color: #0075ff;
    }

    input[type="checkbox"][readonly] {
        pointer-events: none;
    }
    .xWFontSpan{
        font-size: 16px !important;
    }

</style>

    <input id="oetIASJumpDocNo" 	type="hidden" value="<?=@$aParamsWeView['tDocNo'] ?>">
    <input id="oetIASJumpBchCode" 	type="hidden" value="<?=@$aParamsWeView['tBchCode'] ?>">
    <input id="oetIASJumpAgnCode" 	type="hidden" value="<?=@$aParamsWeView['tAgnCode'] ?>">
    <input id="oetIASCheckJump" 	type="hidden" value="<?=@$aParamsWeView['tCheckJump'] ?>">

    <div id="odvSpaMainMenu" class="main-menu"> 
        <div class="xCNMrgNavMenu">
            <div class="row xCNavRow" style="width:inherit;">
                <div class="col-xs-12 col-md-6">
                    <ol id="oliIASMenuNav" class="breadcrumb"> 
                        <?php FCNxHADDfavorite('docIASisfactionSurvey');?> 
                        <li id="oliIASTitle" class="active" style="cursor:pointer" ><?= language('document/inspectionafterservice/inspectionafterservice','tIASTitle')?></li> 
                        <li id="oliIASTitleAdd" class="active"><a><?= language('document/inspectionafterservice/inspectionafterservice','tIASTitleAdd')?></a></li>
                        <li id="oliIASTitleViewData" class="active"><a><?= language('document/inspectionafterservice/inspectionafterservice','tIASViewData')?></a></li>
                        <li id="oliIASTitleEdit" class="active"><a><?php echo language('document/deliveryorder/deliveryorder', 'tIASTitleEdit'); ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-6 text-right p-r-0"> 
                    <div id="odvIASBtnGrpInfo">
                        <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                            <button id="obtIASCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                        <?php endif; ?>
                    </div>
                    <div id="odvBtnAddEdit">
                        <div class="demo-button xCNBtngroup" style="width:100%;">
                            <button id="obtIASBtnBack" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tBack')?></button>
                            <button id="obtIASPrintDoc" onclick="JSxIASPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
                            <button id="obtIASCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel'); ?></button>
                            <button id="obtIASApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button>                                  
                            <div  id="odvIASBtnGrpSave" class="btn-group">
                                <button id="obtIASSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?php echo language('common/main/main', 'tSave'); ?></button>
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
        <div id="odvIASPageDocument">
        </div>
    </div>

<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/Inspectionafterservice/jInspectionafterservice.js"></script>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>