<div class="row p-t-10">
    <div class="col-lg-12" style="margin-bottom: 15px;">
        <ul class="bb-wizard-steps markers bb-justified" id="CheckStep">
            <li class="xWPointStep active" data-step="1">
                <a data-toggle="tab" href="#odvRPPStep1Point1" class="xCNRPPStep1Point1">
                    <label><?= language('document/receiptpurchasepmt/receiptpurchasepmt','tRPPStep1Point1'); ?></label>
                </a>
            </li>
            <li class="xWPointStep" data-step="2">
                <a data-toggle="tab2" href="#odvRPPStep1Point2" class="xCNRPPStep1Point2">
                    <label><?= language('document/receiptpurchasepmt/receiptpurchasepmt','tRPPStep1Point2'); ?></label>
                </a>
            </li>
            <li class="xWPointStep" data-step="3">
                <a data-toggle="tab3" href="#odvRPPStep1Point3" class="xCNRPPStep1Point3">
                    <label><?= language('document/receiptpurchasepmt/receiptpurchasepmt','tRPPStep1Point3'); ?></label>
                </a>
            </li>
        </ul>
    </div>
    <!-- Step Control -->
    <div>
        <div class="col-md-12" style="margin-bottom: 30px;" id="odvStp1Div">
            <button disabled class="btn xCNBTNPrimery xCNBTNPrimery2Btn xCNRPPBackStep" type="button" style="width:150px;"> <?= language('document/Promotion/Promotion', 'tBack'); ?></button>
            <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xCNRPPNextStep" type="button" style="display: inline-block; width:150px;"> <?= language('document/Promotion/Promotion', 'tNext'); ?></button>
        </div>
    </div>

    <div class="col-xs-12 col-md-12 col-lg-12" id="odvStep1Search" style="margin-bottom: 30px;">
        <div class="col-xs-12 col-md-12 col-lg-12" style="border: 1px solid #ccc!important;">
            <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocDateList'); ?></label>
            <div class="row">
                <!-- ประเภทเอกสาร -->
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
                    <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocType'); ?></label>
                    <div class="form-group">
                        <select class="selectpicker form-control" id="ocmRPPBillType" name="ocmRPPBillType" maxlength="1">
                            <option value="0" selected><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocAllBill'); ?></option>
                            <option value="1" ><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocPIBill'); ?></option>
                            <option value="2" ><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocPCBill'); ?></option>
                            <option value="3" ><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocPDBill'); ?></option>
                        </select>
                    </div>
                </div>
                <!-- วันที่ครบชำระ -->
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
                    <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocFrmDateExp'); ?></label>
                    <div class="form-group">
                        <div class="input-group">
                            <input 
                                type="text"
                                class=" input100 xCNDatePicker"
                                id="oetRPPSearchBillDateFrom"
                                name="oetRPPSearchBillDateFrom"
                                autocomplete="off"
                                placeholder="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocFrmDateExp'); ?>"
                            >
                            <span class="input-group-btn">
                                <button id="obtRPPSearchBillDateFrom" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?= base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- ถึงวันที่ครบชำระ -->
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
                    <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocToDateExp'); ?></label>
                    <div class="form-group">
                        <div class="input-group">
                            <input 
                                type="text"
                                class=" input100 xCNDatePicker"
                                id="oetRPPSearchBillDateTo"
                                name="oetRPPSearchBillDateTo"
                                autocomplete="off"
                                placeholder="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocToDateExp'); ?>"
                            >
                            <span class="input-group-btn">
                                <button id="obtRPPSearchBillDateTo" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?= base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- เลขที่เอกสาร -->
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
                    <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocNo'); ?></label>
                    <div class="form-group">
                        <input 
                            type="text" 
                            class="form-control text-left"
                            id="oetRPPSearchDocno"
                            name="oetRPPSearchDocno"
                            autocomplete="off"
                            placeholder="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPDocNo'); ?>"
                        >
                    </div>
                </div>
                <!-- เลขที่บิลผู้จำหน่าย -->
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
                    <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSuppliBillNo'); ?></label>
                    <div class="form-group">
                        <input 
                            type="text" 
                            class="form-control text-left"
                            id="oetRPPSearchDocRef"
                            name="oetRPPSearchDocRef"
                            autocomplete="off"
                            placeholder="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPSuppliBillNo'); ?>"
                        >
                    </div>
                </div>
                <!-- ปุ่มค้นหา -->
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
                    <div class='row'>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm">&nbsp;</label>
                                <button 
                                    id="oahRPPAdvanceSearchSubmit"
                                    class="btn xCNBTNDefult xCNBTNDefult1Btn"
                                    style="width:100%" 
                                    onclick="JSxRPPResetFilter()"
                                >
                                    <?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFilterReset'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm">&nbsp;</label>
                                <button 
                                    id="oahRPPAdvanceSearchSubmit"
                                    class="btn xCNBTNPrimery"
                                    style="width:100%"
                                    onclick="JSxRPPShowBillOnEditEventSearch()"
                                >
                                    <?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFilter'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="odvRPPTableStep" style="margin-top: 40px;">
        <div class="containter tab-content">
            <div class="bb-wizard-page tab-pane active" id="odvRPPStep1Point1">
                <?php include('wRPPStep1Point1.php'); ?>
            </div>
            <div class="bb-wizard-page tab-pane" id="odvRPPStep1Point2">
                <?php include('wRPPStep1Point2.php'); ?>
            </div>
            <div class="bb-wizard-page tab-pane" id="odvRPPStep1Point3">
                <?php include('wRPPStep1Point3.php'); ?>
            </div>
        </div>
    </div>

    
<script type="text/javascript">
    // กด ปุ่ม เลือกเอกสาร ใบจ่ายชำระเอกสาร
    $('.xCNRPPStep1Point1').unbind().click(function() {
    });

    // กด ปุ่ม ตรวจสอบเอกสาร ใบจ่ายชำระเอกสาร
    $('.xCNRPPStep1Point2').unbind().click(function() {
        var aPdtLent    = $("#ohdConfirmRPPInsertPDT").val();
        if (aPdtLent != '') {
            JSxRPPStep1Point2LoadDatatable();
        }else {
            FSvCMNSetMsgErrorDialog("กรุณาเลือกเอกสารที่จะนำมาชำระ");
            return;
        }
    });

    // กด ปุ่ม ชำระเงิน ใบจ่ายชำระเอกสาร
    $('.xCNRPPStep1Point3').unbind().click(function() {
        var aPdtLent    = $("#ohdConfirmRPPInsertPDT").val();
        if (aPdtLent != '') {
            JSxRPPStep1Point3LoadDatatable();
        }else{
            FSvCMNSetMsgErrorDialog("กรุณาเลือกเอกสารที่จะนำมาชำระ");
            return;
        }
    });

</script>