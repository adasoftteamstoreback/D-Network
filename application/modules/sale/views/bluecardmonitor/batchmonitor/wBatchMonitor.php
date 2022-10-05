<style>
    .xWBCMSALTextNumber{
        font-size: 25px !important;
        font-weight: bold;
    }
    
    .xWBCMSALPanelMainRight{
        padding-bottom:0px;
        /* min-height:300px; */
        overflow-x: auto;
    }

    .xWBCMSALFilter{
        cursor: pointer;
    }

    .xWBCMSALRequest{
        cursor: pointer;
    }
    .xWOverlayLodingChart{
        position: absolute;
	    min-width: 100%;
	    min-height: 100%;
	    width: 100%;
	    background: #FFFFFF;
	    z-index: 2500;
	    display: none;
	    top: 0%;
        margin-left: 0px;
        left: 0%;
    }
</style>
<?php
    $dDateToday         = date("Y-m-d");
    $dFirstDateOfMonth  = $dDateToday;
    $dLastDateOfMonth   = $dDateToday;
?>
<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="odvBCMSALPanelRight1" class="">
            <div >
                <div class="panel-body xWBCMSALPanelMainRight">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row">
                                <form action="javascript:void(0);" class="validate-form" method="post" id="ofmBCMSALFormFilter">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    
                                        <?php 
                                        if($this->session->userdata('tSesUsrLevel') != 'HQ'){
                                            $tBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
                                            $tBchName       = $this->session->userdata("tSesUsrBchNameDefault");
                                            $tDisabledPos   = '';
                                        }else{
                                            $tBchCode       = '';
                                            $tBchName       = '';
                                            $tDisabledPos   = 'disabled';
                                        }
                                        ?>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?= language('sale/salemonitor/salemonitor','tBCMBranch')?></label>
                                                <div class="input-group">
                                                    <input class="form-control xCNHide" id="oetBCMBchCode" name="oetBCMBchCode" value="<?=$tBchCode?>" >
                                                    <input
                                                        class="form-control xWPointerEventNone"
                                                        type="text"
                                                        id="oetBCMBchName"
                                                        name="oetBCMBchName"
                                                        value="<?=$tBchName?>"
                                                        placeholder="<?= language('sale/salemonitor/salemonitor','tBCMBranch')?>"
                                                        readonly
                                                    >
                                                    <span class="input-group-btn">
                                                        <button id="obtBCMBrowsBch" type="button" class="btn xCNBtnBrowseAddOn" ><img class="xCNIconFind"></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?= language('sale/salemonitor/salemonitor','tBCMPos')?></label>
                                                <div class="input-group">
                                                    <input class="form-control xCNHide" id="oetBCMPosCode" name="oetBCMPosCode" value="" >
                                                    <input
                                                        class="form-control xWPointerEventNone"
                                                        type="text"
                                                        id="oetBCMPosName"
                                                        name="oetBCMPosName"
                                                        value=""
                                                        placeholder="<?= language('sale/salemonitor/salemonitor','tBCMPos')?>"
                                                        readonly
                                                    >
                                                    <span class="input-group-btn">
                                                        <button id="obtBCMBrowsPos" type="button" class="btn xCNBtnBrowseAddOn" <?=$tDisabledPos?>><img class="xCNIconFind"></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('sale/salemonitor/salemonitor','tBCMDate')?></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control text-center xCNDatePicker" id="oetBCMSALDate" name="oetBCMSALDate" value="<?php echo @$dFirstDateOfMonth; ?>">
                                                    <span class="input-group-btn">
                                                        <button id="obtBCMSALDate" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconCalendar"></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?= language('sale/salemonitor/salemonitor','tBCMBatStaClosed')?></label>
                                                <select class="selectpicker form-control" name="ocmBCMBatStaClosed" id="ocmBCMBatStaClosed">
                                                    <option value=""><?= language('sale/salemonitor/salemonitor','tBCMItemAll')?></option>
                                                    <option value="1"><?= language('sale/salemonitor/salemonitor','tBCMBatStaClosed1')?></option>
                                                    <option value="2"><?= language('sale/salemonitor/salemonitor','tBCMBatStaClosed2')?></option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?= language('sale/salemonitor/salemonitor','tBCMBatStaVerify')?></label>
                                                <select class="selectpicker form-control" name="ocmBCMBatStaVerify" id="ocmBCMBatStaVerify">
                                                    <option value=""><?= language('sale/salemonitor/salemonitor','tBCMItemAll')?></option>
                                                    <option value="1"><?= language('sale/salemonitor/salemonitor','tBCMBatStaVerify1')?></option>
                                                    <option value="2"><?= language('sale/salemonitor/salemonitor','tBCMBatStaVerify2')?></option>
                                                    <option value="3"><?= language('sale/salemonitor/salemonitor','tBCMBatStaVerify3')?></option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?= language('sale/salemonitor/salemonitor','tBCMBatStaInsBat')?></label>
                                                <select class="selectpicker form-control" name="ocmtBCMBatStaInsBat" id="ocmtBCMBatStaInsBat">
                                                    <option value=""><?= language('sale/salemonitor/salemonitor','tBCMItemAll')?></option>
                                                    <option value="1"><?= language('sale/salemonitor/salemonitor','tBCMBatStaInsBat1')?></option>
                                                    <option value="2"><?= language('sale/salemonitor/salemonitor','tBCMBatStaInsBat2')?></option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"></label>
                                            <div class="form-group">
                                                <button id="obtBCMBtnRefresh" class="btn btn-primary xCNBTNImportRole xCNBTNDefult xCNBTNDefult2Btn" type="button" style='min-width: 130px;width:20%;margin-right: 10px;'> <?=language('common\main\main','tClearSearch')?></button>
                                                <button id="obtBCMBtnFilter" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" style='min-width: 100px;width:20%;margin-right: 10px;'> <?=language('common\main\main','tSearch')?></button>
                                                <button id="obtBCMBtnRepair" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" style='min-width: 100px;width:20%;'> <?=language('sale/salemonitor/salemonitor', 'tBCMBtnRepair')?></button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="panel-body">
                                <div class="xWBCMSALDataPanel"  id="odvPanelSaleData"></div>
                                <div class="xWOverlayLodingChart" data-keyfilter="FSD">
                                    <img src="<?php echo base_url(); ?>application/modules/common/assets/images/ada.loading.gif" class="xWImgLoading">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal Confirm  ======================================================================== -->
<div id="odvBCMModalConfim" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('sale/salemonitor/salemonitor','tBCMMsgConfirm'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="oepMassageConfirm"></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxBCMConfirmRepiar()" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="odvBCMSALModalFilterHTML"></div>

<?php include "script/jBatchMonitor.php";?>