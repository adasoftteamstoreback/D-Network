<!-- Panel ลูกค้า-->
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/quotation/quotation', 'tTQPanelCar'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvPanelCar" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvPanelCar" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">
            <!-- ทะเบียนรถ -->
            <div class="form-group">
              <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQCarlic'); ?></label>
              <div class="input-group">
                <input  type="text" class="form-control xControlForm xCNHide" id="oetPreCarRegCode" name="oetPreCarRegCode" maxlength="5"
                value="<?= @$tJR1CarRegCode;?>">
                <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarRegName" name="oetPreCarRegName" maxlength="100" placeholder="<?= language('document/quotation/quotation', 'tTQCarlic'); ?>"
                value="<?= @$tPreCarRegName; ?>" readonly>
                  <span class="input-group-btn">
                    <button id="oimPreBrowseCarRegNo" type="button" class="btn xCNBtnBrowseAddOn">
                      <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                    </button>
                  </span>
              </div>
            </div>
            <!-- ประเภท / ลักษณะ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tPreLabelCarType'); ?></label>
                <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarTypeName" name="oetPreCarTypeName" maxlength="100"
                placeholder="<?php echo language('document/quotation/quotation', 'tPreLabelCarType') ?>" value="<?= @$tPreCarTypeName; ?>" readonly>
            </div>
            <!-- ยี่ห้อ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tPreLabelCarBrand'); ?></label>
                <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarBrandName" name="oetPreCarBrandName" maxlength="100"
                placeholder="<?php echo language('document/quotation/quotation', 'tPreLabelCarBrand') ?>" value="<?= @$tPreCarTypeBrand; ?>" readonly>
            </div>
            <!-- รุ่น -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tPreLabelCarModel'); ?></label>
                <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarModelName" name="oetPreCarModelName" maxlength="100"
                placeholder="<?php echo language('document/quotation/quotation', 'tPreLabelCarModel') ?>" value="<?= @$tPreCarTypeModel; ?>" readonly>
            </div>
            <!-- สี -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('document/quotation/quotation', 'tPreLabelCarColor') ?></label>
                <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarColorName" name="oetPreCarColorName" maxlength="100"
                placeholder="<?php echo language('document/quotation/quotation', 'tPreLabelCarColor') ?>" value="<?= @$tPreCarTypeColor; ?>" readonly>
            </div>
            <!-- ระบบเกียร์ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('document/quotation/quotation', 'tPreLabelCarGear') ?></label>
                <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetPreCarGearName" name="oetPreCarGearName" maxlength="100"
                placeholder="<?php echo language('document/quotation/quotation', 'tPreLabelCarGear') ?>" value="<?= @$tPreCarGearName; ?>" readonly>
            </div>
            <!-- เลขมิเตอร์ -->
            <div class="form-group" hidden>
                <label class="xCNLabelFrm"><?php echo language('document/quotation/quotation', 'tPreLabelCarMiter') ?></label>
                <input type="text" class="form-control" id="oetPreCarMiter" name="oetPreCarMiter"
                placeholder="<?php echo language('document/quotation/quotation', 'tPreLabelCarMiter') ?>" value="<?= @$tPreCarMileage; ?>" readonly>
            </div>
            <!-- เลขตัวถัง -->
            <div class="form-group" hidden>
                <label class="xCNLabelFrm"><?php echo language('document/quotation/quotation', 'tPreLabelCarVIDRef') ?></label>
                <input type="text" class="form-control" id="oetPreCarVIDRef" name="oetPreCarVIDRef"
                placeholder="<?php echo language('document/quotation/quotation', 'tPreLabelCarVIDRef') ?>" value="<?= @$tPreCarVIDRef; ?>" readonly>
            </div>
        </div>
    </div>
</div>
