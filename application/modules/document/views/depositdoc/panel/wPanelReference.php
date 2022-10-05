<!-- Panel อ้างอิง-->
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCReference'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvTQReference" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvTQReference" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">
            <!-- Select Refin -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCRefInt'); ?></label>
                <select class="selectpicker xWDPSDisabledOnApv form-control xControlForm" id="ocmDPSSelectBrowse" name="ocmDPSSelectBrowse" maxlength="1">
                    <option value="0"><?php echo language('document/bookingorder/bookingorder', 'tTWXRefSODoc'); ?></option>
                    <option value="1"><?php echo language('document/bookingorder/bookingorder', 'tTWXRefQTDoc'); ?></option>
                    <option value="2"><?php echo language('document/bookingorder/bookingorder', 'tTWXRefClMDoc'); ?></option>
                </select>
            </div>
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmRefIntDoc'); ?></label>
                <div class="input-group">
                    <input type="text" class="xCNHide" id="ohdDPSRefInAllCode" name="ohdDPSRefInAllCode" maxlength="5" value="">
                    <input class="form-control xControlForm xWPointerEventNone" type="text" id="oetDPSRefInAllName" name="oetDPSRefInAllName" value="<?php echo @$tFTXshRefInt; ?>" readonly placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmRefIntDoc'); ?>">
                    <span class="input-group-btn">
                        <button id="obtDPSBrowseRefDocIntMulti" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                        </button>
                    </span>
                </div>
            </div>
            <input type="hidden" class="form-control xCNInputWithoutSpc" id="oetDPSRefInt" name="oetDPSRefInt" maxlength="20" value="<?= @$tFTXshRefInt; ?>">
            <!-- วันที่เอกสารภายใน -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/adjustmentcost/adjustmentcost', 'tADCRefIntDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD" id="oetDPSRefIntDate" name="oetDPSRefIntDate" value="<?= @$tFDXshRefIntDate; ?>">
                            <span class="input-group-btn">
                                <button id="obtTQRefIntDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/icons8-Calendar-100.png'; ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- เลขที่อ้างอิงเอกสารภายนอก -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?></label>
                        <input type="text" class="form-control xCNInputWithoutSpc" id="oetDPSRefExt" name="oetDPSRefExt" maxlength="20" value="<?= @$tFTXshRefExt; ?>" placeholder="<?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefExt'); ?>">
                    </div>
                </div>
            </div>
            <!-- วันที่เอกสารภายนอก -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/producttransferbranch/producttransferbranch', 'tTBRefExtDate'); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" placeholder="YYYY-MM-DD" id="oetDPSRefExtDate" name="oetDPSRefExtDate" value="<?= @$tFDXshRefExtDate; ?>">
                            <span class="input-group-btn">
                                <button id="obtTQRefExtDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?php echo  base_url() . 'application/modules/common/assets/images/icons/icons8-Calendar-100.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>