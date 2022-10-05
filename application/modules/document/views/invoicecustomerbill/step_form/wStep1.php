<div class="row p-t-10">
    <div class="col-lg-12" style="margin-bottom: 15px;">
        <ul class="bb-wizard-steps markers bb-justified" id="CheckStep">
            <li class="xWPointStep active" data-step="1">
                <a data-toggle="tab" data-target="#odvInvoiceBillStep1Point1" class="xCNInvoiceBillStep1Point1"><label>เลือกเอกสาร</label></a>
            </li>
            <li class="xWPointStep" data-step="2">
                <a data-toggle="tab2" data-target="#odvInvoiceBillStep1Point2" class="xCNInvoiceBillStep1Point2"><label>ตรวจสอบ และยืนยัน</label></a>
            </li>
        </ul>
    </div>

    <!-- Step Control -->
    <div>
        <div class="col-md-12 xCNHide" style="margin-bottom: 30px;" id="odvStp1Div">
            <button disabled class="btn xCNBTNPrimery xCNBTNPrimery2Btn xCNIVCBackStep" type="button" style="width:150px;"> <?= language('document/Promotion/Promotion', 'tBack'); ?></button>
            <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xCNIVCNextStep" type="button" style="display: inline-block; width:150px;"> <?= language('document/Promotion/Promotion', 'tNext'); ?></button>
        </div>
    </div>

    <div class="col-xs-12 col-md-12 col-lg-12" id="odvStep1Search" style="margin-bottom: 30px;">
        <div class="col-xs-12 col-md-12 col-lg-12" style="border: 1px solid #ccc!important;">
            <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBDocDateList'); ?></label>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-xs-6 xCNHide">
                    <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBDocType'); ?></label>
                    <div class="form-group">
                        <select class="selectpicker form-control" id="ocmIVCBillType" name="ocmIVCBillType" maxlength="1">
                            <!-- <option value="0" selected><?= language('document/invoicebill/invoicebill', 'tIVBDocAllBill'); ?></option> -->
                            <option value="1" selected><?= language('document/invoicebill/invoicebill', 'tIVCDocSalBill'); ?></option>
                            <!-- <option value="2" ><?= language('document/invoicebill/invoicebill', 'tIVBDocPCBill'); ?></option> -->
                            <!-- <option value="3" ><?= language('document/invoicebill/invoicebill', 'tIVBDocPDBill'); ?></option> -->
                        </select>
                    </div>
                </div>
                <!-- <div class="col-lg-4 col-md-6 col-xs-6">
                    <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBDocFrmDateExp'); ?></label>
                    <div class="form-group">
                        <div class="input-group">
                            <input class=" input100 xCNDatePicker" type="text" id="oetSearchBillDateFrom" name="oetSearchBillDateFrom" autocomplete="off" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBDocFrmDateExp'); ?>">
                            <span class="input-group-btn">
                                <button id="obtSearchBillDateFrom" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?= base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-xs-6">
                    <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBDocToDateExp'); ?></label>
                    <div class="form-group">
                        <div class="input-group">
                            <input class=" input100 xCNDatePicker" type="text" id="oetSearchBillDateTo" name="oetSearchBillDateTo" autocomplete="off" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBDocToDateExp'); ?>">
                            <span class="input-group-btn">
                                <button id="obtSearchBillDateTo" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?= base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                </button>
                            </span>
                        </div>
                    </div>
                </div> -->
                <div class="col-lg-3 col-md-3 col-xs-3">
                    <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBDocFrmDateSal'); ?></label>
                    <div class="form-group">
                        <div class="input-group">
                            <input class=" input100 xCNDatePicker" type="text" id="oetSearchBillDateFrom" name="oetSearchBillDateFrom" autocomplete="off" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBDocFrmDateSal'); ?>">
                            <span class="input-group-btn">
                                <button id="obtSearchBillDateFrom" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?= base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-xs-3">
                    <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBDocToDateSal'); ?></label>
                    <div class="form-group">
                        <div class="input-group">
                            <input class=" input100 xCNDatePicker" type="text" id="oetSearchBillDateTo" name="oetSearchBillDateTo" autocomplete="off" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBDocToDateSal'); ?>">
                            <span class="input-group-btn">
                                <button id="obtSearchBillDateTo" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?= base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-xs-3">
                    <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBDocFrmDateSalBch'); ?></label>
                    <div class="input-group">
                    <input type="text" class="form-control xCNInputReadOnly xControlForm xCNHide" id="oetIVCCarBchFrm" name="oetIVCCarBchFrm" maxlength="20">
                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetIVCCarBchFrmName" name="oetIVCCarBchFrmName" maxlength="100" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBDocFrmDateSalBch') ?>" value="<?= @$tIVCCTRName ?>" readonly>
                    <span class="input-group-btn">
                        <button id="oimIVCBrowseCarBchFrm" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                        </button>
                    </span>
                </div>
                </div>
                <div class="col-lg-3 col-md-3 col-xs-3">
                    <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBDocToDateSalBch'); ?></label>
                    <div class="input-group">
                    <input type="text" class="form-control xCNInputReadOnly xControlForm xCNHide" id="oetIVCCarBchTo" name="oetIVCCarBchTo" maxlength="20">
                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetIVCCarBchToName" name="oetIVCCarBchToName" maxlength="100" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVBDocToDateSalBch') ?>" value="<?= @$tIVCCTRName ?>" readonly>
                    <span class="input-group-btn">
                        <button id="oimIVCBrowseCarBchTo" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                        </button>
                    </span>
                </div>
                </div>
            </div>

            <div class="row">
                <!--เลขที่เอกสาร-->
                <div class="col-lg-3 col-md-6 col-xs-6">
                    <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBDocNo'); ?></label>
                    <div class="form-group">
                        <input style="text-align: left;" autocomplete="off" type="text" class="" id="oetIVSearchDocno" name="oetIVSearchDocno">
                    </div>
                </div>
                <!--เลขที่บิลผู้จำหน่าย-->
                <div class="col-lg-3 col-md-6 col-xs-6">
                    <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill', 'tIVBDocNoTo'); ?></label>
                    <div class="form-group">
                        <input style="text-align: left;" autocomplete="off" type="text" class="" id="oetIVSearchDocRef" name="oetIVSearchDocRef">
                    </div>
                </div>
                <!--ปุ่มค้นหา-->
                <div class="col-lg-4 col-md-6 col-xs-6">

                    <div class='row'>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm">&nbsp;</label>
                                <button id="oahIVCAdvanceSearchSubmit" class="btn xCNBTNDefult xCNBTNDefult1Btn" style="width:100%" onclick="JSxIVCResetFilter()"><?= language('document/invoicebill/invoicebill', 'tIVBFilterReset'); ?></button>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm">&nbsp;</label>
                            <button id="oahIVCAdvanceSearchSubmit" class="btn xCNBTNPrimery" style="width:100%" onclick="JSxIVCShowBillOnEditEventSearch()"><?= language('document/invoicebill/invoicebill', 'tIVBFilter'); ?></button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="odvTableStep" style="margin-top: 40px;">
        <div class="containter tab-content">
            <div class="bb-wizard-page tab-pane active" id="odvInvoiceBillStep1Point1">
                <?php include('wStep1Point1.php'); ?>
            </div>
            <div class="bb-wizard-page tab-pane" id="odvInvoiceBillStep1Point2">
                <?php include('wStep1Point2.php'); ?>
            </div>
        </div>
    </div>

    <script>
        //กด "รับสินค้าจากลูกค้า"
        $('.xCNInvoiceBillStep1Point1').unbind().click(function() {});

        //กด "ตรวจสอบเงื่อนไขภายใน"
        $('.xCNInvoiceBillStep1Point2').unbind().click(function() {
            //โหลดสินค้า
            var aPdtLent = $("#ohdConfirmIVCInsertPDT").val();
            if (aPdtLent != '') {
                JSxIVCStep1Point2LoadDatatable();
            } else {
                FSvCMNSetMsgErrorDialog("กรุณาเลือกเอกสารที่จะนำมาวางบิล");
                return;
            }
        });
    </script>