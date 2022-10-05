<!-- Panel ลูกค้า-->
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/bookingorder/bookingorder', 'tTWXCstOrBooking'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvPanelCustomer" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvPanelCustomer" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <!-- ลูกค้า -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/bookingorder/bookingorder', 'tTWXCst'); ?></label>
                <input type="hidden" class="form-control xCNInputWithoutSpc" id="oetTWXPanel_CustomerCode" name="oetTWXPanel_CustomerCode" value="<?= @$tTWXCstCode; ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXCst') ?>" readonly>
                <input type="hidden" class="form-control xCNInputWithoutSpc" id="oetTWXPanel_ADDSeq" name="oetTWXPanel_ADDSeq" value="<?= @$tTWXAddSeq; ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXCst') ?>" readonly>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetTWXPanel_CustomerName" name="oetTWXPanel_CustomerName" value="<?= @$tTWXCstName; ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXCst') ?>" readonly>
            </div>

            <!-- ที่อยู่-->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/bookingorder/bookingorder', 'tTWXCstAddress'); ?></label>
                <input type="hidden" class="form-control xCNInputWithoutSpc" id="oetTWXPanel_CustomerAddressCode" name="oetTWXPanel_CustomerAddressCode" value="<?= @$tTWXCstCode; ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXCst') ?>" readonly>
                <!-- <input type="text" class="form-control xCNInputWithoutSpc" id="oetTWXPanel_CustomerAddress" name="oetTWXPanel_CustomerAddress" maxlength="200" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXCstAddress') ?>" value='<?= @$tTWXAddress ?>' readonly></input> -->
                <textarea readonly class="form-control" placeholder="<?= language('document/quotation/quotation', 'tTQAddress'); ?>" id="oetTWXPanel_CustomerAddress" name="oetTWXPanel_CustomerAddress" maxlength="200"><?= @$tTWXAddress ?></textarea>

            </div>

            <!-- เบอร์โทรศัพท์ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/bookingorder/bookingorder', 'tTWXCstTell'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetTWXPanel_CustomerTelephone" name="oetTWXPanel_CustomerTelephone" maxlength="20" value="<?= @$tTWXTel; ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXCstTell') ?>" readonly>
            </div>

            <!-- อีเมล -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/bookingorder/bookingorder', 'tTWXCstEmail'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetTWXPanel_CustomerEmail" name="oetTWXPanel_CustomerEmail" maxlength="100" value="<?= @$tTWXEmail; ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXCstEmail') ?>" readonly>
            </div>

            <div><hr></div>

            <!-- ข้อมูลรถ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('document/bookingorder/bookingorder', 'tTWXCstCar'); ?></label>
                <div class="input-group">
                    <input type="hidden" class="form-control xControlForm xWPointerEventNone" id="oetTWXCrscarCode" name="oetTWXCrscarCode" value="<?php echo $tTWXCarCode ?>" readonly>
                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetTWXcarCrsName" name="oetTWXCrscarName" value="<?php echo $tTWXCarBrand ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXCstCar') ?>" readonly>
                    <span class="xWConditionSearchPdt input-group-btn">
                        <button id="obtTWXBrowseCrsCar" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                            <img class="xCNIconFind">
                        </button>
                    </span>
                </div>
            </div>

            <!-- ทะเบียน -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/bookingorder/bookingorder', 'tTWXCstCarRegNo'); ?></label>
                <input type="text" class="form-control xCNInputWithoutSpc" id="oetTWXPanel_CustomerCarRegNo" name="oetTWXPanel_CustomerCarRegNo" maxlength="100" value="<?= $tTWXCstCarRegno ?>" placeholder="<?php echo language('document/bookingorder/bookingorder', 'tTWXCstCarRegNo') ?>" readonly>
            </div>

            <!-- Browse ที่อยู่ ซ่อนไว้ก่อน -->
            <!-- <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <button type="button" id="obtTWXBrowseAddress" class="btn xCNBTNSubSave" style="width:100%;" data-codebrowse="2">
                        <?php echo language('document/bookingorder/bookingorder', 'tTWXCstAddress'); ?>
                    </button>
                </div>
            </div> -->

        </div>
    </div>
</div>