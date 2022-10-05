<!-- Panel ขนส่ง-->    
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/producttransferbranch/producttransferbranch', 'tTBDelivery'); ?></label>
        <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvPanelTransport" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvPanelTransport" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <!-- ชื่อผู้ติดต่อ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/producttransferbranch/producttransferbranch', 'tTBCtrName'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetIVCtrName" name="oetIVCtrName" value='<?=@$tFTXphCtrName?>' placeholder="<?= language('document/producttransferbranch/producttransferbranch', 'tTBCtrName'); ?>">
            </div>

            <!-- อ้างอิงเลขที่ใบขนส่ง -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/producttransferbranch/producttransferbranch', 'tTBRefTnfID'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetIVRefTnfID" name="oetIVRefTnfID" value='<?=@$tFTXphRefTnfID?>' placeholder="<?= language('document/producttransferbranch/producttransferbranch', 'tTBRefTnfID'); ?>">
            </div>

            <!-- วันที่ส่งของ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/producttransferbranch/producttransferbranch', 'tTBTnfDate'); ?></label>
                <div class="input-group">
                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetIVTnfDate" name="oetIVTnfDate" value="<?=@$tFDXphTnfDate?>" placeholder="YYYY-MM-DD" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEffectiveDate'); ?>">
                    <span class="input-group-btn">
                        <button id="obtIVTnfDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>

            <!-- อ้างอิงเลขที่ยานพาหนะขนส่ง -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/producttransferbranch/producttransferbranch', 'tTBRefVehID'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetIVVehID" name="oetIVVehID" value='<?=@$tFTXphRefVehID?>' placeholder="<?= language('document/producttransferbranch/producttransferbranch', 'tTBRefVehID'); ?>">
            </div>

        </div>
    </div>
</div>
