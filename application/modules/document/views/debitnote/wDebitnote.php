<style type="text/css">
    .xWTdDisable {cursor: not-allowed !important;opacity: 0.4 !important;}
    .xWImgDisable {cursor: not-allowed !important;pointer-events: none;}
    .xWRateing {display: flex;transform: rotateY(180deg);}
    .xWRateing input{display: none;}
    .xWRateing label{display: block;cursor: pointer;width: 25px;}
    .xWRateing label:before {
        content: '\f005';
        font-family: fontAwesome;
        position: absolute;
        display:block;
        font-size: 20px;
        color: #eaeaea;
    }
    .xWRateing label:after {
        content: '\f005';
        font-family: fontAwesome;
        position: absolute;
        display:block;
        font-size: 20px;
        color: #179BFD;
        top: 0;
        opacity: 0;
        transition: .3s;
        text-shadow: 0 1px 2px rgba(0,0,0, .5);
    }
    .xWRateing input:checked ~ label:after{opacity: 1;}
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
    input[type="radio"].xWDisabled:checked{border: 2px solid #1580ff;background-color: #0075ff;}
    input[type="checkbox"][readonly] {pointer-events: none;}
    .xWFontSpan{font-size: 16px !important;}
</style>
<div id="odvDBNMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <ol id="oliDBNMenuNav" class="breadcrumb">
                    <?php FCNxHADDfavorite('docDBN');?>
                    <li id="oliDBNTitle" class="active" style="cursor:pointer" onclick="JSvDBNCallPageList()"><?= language('document/debitnote/debitnote','tDBNTitle')?></li>
                    <li id="oliDBNTitleViewData" class="active"><a><?= language('document/debitnote/debitnote','tDBNViewData')?></a></li>
                </ol>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right p-r-0">
                <!-- <div id="odvDBNBtnGrpInfo">
                <?php //if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                        <button id="obtDBNCallPageAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                    <?php //endif; ?>
                </div> -->
                <div id="odvBtnAddEdit">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <button id="obtBtnBack" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?=language('common/main/main', 'tBack')?></button>
                        <button id="obtDBNPrintDoc" onclick="JSxDBPrintDoc()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNPrint'); ?></button>
                        <!-- <button id="obtDBNCancelDoc" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel'); ?></button> -->
                        <!-- <button id="obtDBNApproveDoc" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove'); ?></button>                                   -->
                        <div  id="odvDBNBtnGrpSave" class="btn-group">
                            <button id="obtDBNSubmitFromDoc" type="button" class="btn xWBtnGrpSaveLeft"> <?php echo language('common/main/main', 'tSave'); ?></button>
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
    <div id="odvDBNPageDocument"></div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>application/modules/document/assets/src/debitnote/jDebitNote.js"></script>