<?php
    $nAddressVersion    = FCNaHAddressFormat('TCNMSpl');
    if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == "1") {

    } else {
        $tRPPRoute          = "docRPPEventAdd";
        $nStaUploadFile     = 1; 
        $tRPPDocNo          = '';
        $tRPPStaApv         = '';
        $nRPPStaDocAct      = '';
        $nRPPStaRef         = '';
        $tRPPFrmDocPrint    = '';
        $tRPPRmk            = '';
        $tRPPApvCode        = '';
        $tRPPFTPrdCode      = '';
        $tRPPUsrNameApv     = '';
        $tRPPFTDueDate      = '';

        $tRPPCtrCode        = '';
        $tRPPCtrName        = '';
        $tRPPCtrPhone       = '';


        $tRPPAddress        = '';

        $dRPPDocDate        = date('Y-m-d');
        $dRPPDocTime        = date('H:i:s');
        $tRPPCreateByName   = $this->session->userdata('tSesUsrUsername');
        $tRPPAgnCode        = $this->session->userdata('tSesUsrAgnCode');
        $tRPPAgnName        = $this->session->userdata('tSesUsrAgnName');


    }
?>
<form id="ofmRPPFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtRPPSubmitDocument" onclick="JSxRPPAddEditDocument()"></button>
    <input type="hidden" id="ohdRPPDecimalShow" name="ohdRPPDecimalShow" value="<?=$nOptDecimalShow?>">
    <input type="hidden" id="ohdRPPRoute" name="ohdRPPRoute" value="<?=@$tRPPRoute?>">
    <input type="hidden" id="ohdRPPDocNo" name="ohdRPPDocNo" value="<?=@$tRPPDocNo?>">
    <input type="hidden" id="ohdRPPStaApv" name="ohdRPPStaApv" value="<?=@$tRPPStaApv?>">
    <input type="hidden" id="ohdRPPStaPrc" name="ohdRPPStaPrc" value="<?=@$tRPPStaPrc?>">
    <input type="hidden" id="ohdRPPStaDoc" name="ohdRPPStaDoc" value="<?=@$tRPPStaDoc?>">
    <input type="hidden" id="ohdConfirmRPPInsertPDT"    name="ohdConfirmRPPInsertPDT">
    <input type="hidden" id="ohdRPPCheckClearValidate"  name="ohdRPPCheckClearValidate"     value="0">
    <input type="hidden" id="ohdRPPCheckSubmitByButton" name="ohdRPPCheckSubmitByButton"    value="0">

    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmDocument'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvRPPDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvRPPDataStatusInfo" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="xCNTitleFrom "><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmApproved'); ?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmDocNo'); ?></label>
                                <?php if (empty($tRPPDocNo)): ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbRPPStaAutoGenCode" name="ocbRPPStaAutoGenCode" maxlength="1" checked="true" value="1">
                                            <span class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input 
                                        type="text"
                                        class="form-control xCNInputReadOnly xWTooltipsBT xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                        id="oetRPPDocNo"
                                        name="oetRPPDocNo"
                                        maxlength="20"
                                        value="<?= $tRPPDocNo; ?>" 
                                        data-validate-required="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPPlsEnterOrRunDocNo'); ?>" 
                                        data-validate-duplicate="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPlsDocNoDuplicate'); ?>" 
                                        placeholder="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmDocNo'); ?>"
                                        style="pointer-events:none" 
                                        readonly
                                    >
                                    <input type="hidden" id="ohdRPPCheckDuplicateCode" name="ohdRPPCheckDuplicateCode" value="2">
                                </div>

                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate"
                                            id="oetRPPDocDate"
                                            name="oetRPPDocDate"
                                            value="<?= $dRPPDocDate; ?>"
                                            data-validate-required="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPlsEnterDocDates'); ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtRPPDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNInputReadOnly xCNTimePicker"
                                            id="oetRPPDocTime"
                                            name="oetRPPDocTime"
                                            value="<?= $dRPPDocTime; ?>"
                                            data-validate-required="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPPlsEnterDocTime'); ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtRPPDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?= $tRPPCreateByName ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmTBStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php   
                                                if($tRPPRoute == "docInvoiceBillEventEdit"){
                                                    $tRPPLabelStaDoc    = language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmStaDoc'.$tRPPStaDoc);
                                                }else{
                                                    $tRPPLabelStaDoc    = '-';
                                                }
                                            ?>
                                            <label><?=$tRPPLabelStaDoc;?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmTBStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmStaApv' . $tRPPStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tRPPDocNo) && !empty($tRPPDocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPFrmApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdRPPApvCode" name="ohdRPPApvCode" maxlength="20" value="<?= $tRPPApvCode ?>">
                                                <label>
                                                    <?= (isset($tRPPUsrNameApv) && !empty($tRPPUsrNameApv)) ? $tRPPUsrNameApv : "-"; ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel เงื่อนไข อ้างอิงเอกสารภายใน และ ภายนอก--> 
            <?php include('Panel/wRPPPanelCondition.php');?>

            <!-- Panel Supplier -->    
            <?php include('Panel/wRPPPanelSupplier.php');?>

            <!-- Panel อื่นๆ -->
            <?php include('Panel/wRPPPanelOther.php');?>

            <!-- Panel ไฟลแนบ -->
            <?php include('Panel/wRPPPanelFileImport.php');?>

        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <!-- ตารางสินค้า -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px; position:relative; min-height:200px;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- ผู้จำหน่าย -->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt','tRPPTBSpl'); ?></label>
                                            <div class="input-group" style="width:100%;">
                                                <input type="text" class="input100 xCNHide" id="ohdRPPSPLCode" name="ohdRPPSPLCode" value="<?= @$tRPPSPLCode; ?>">
                                                <input 
                                                    type="text"
                                                    class="form-control xWPointerEventNone" 
                                                    id="oetRPPSPLName"
                                                    name="oetRPPSPLName"
                                                    value="<?= @$tRPPSPLName; ?>"
                                                    readonly
                                                    placeholder="<?= language('document/receiptpurchasepmt/receiptpurchasepmt','tRPPMsgValidSplCode'); ?>"
                                                >
                                                <span class="input-group-btn">
                                                    <button id="obtRPPBrowseSPL" type="button" class="btn xCNBtnBrowseAddOn" >
                                                        <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- ผู้จำหน่าย -->
                                        <div class="row tab-content xCNPaymentTabContent">
                                            <div id="odvRPPStep1" class="tab-pane fade in active" style="padding-top: 0px;">
                                                <?php include('step_form/wRPPStep1.php'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- ============================================================ กรุณากรอกข้อมูลให้ครบถ้วน ============================================================ -->
<div id="odvRPPModalPleseDataInFill" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="ospRPPModalPleseDataInFill">กรุณาเลือกผู้จำหน่าย ก่อนทำรายการ</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- =============================================================================================================================================== -->

<!-- ================================================================== อนุมัติเอกสาร  ================================================================ -->
<div class="modal fade xCNModalApprove" id="odvRPPPopupApv">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tApproveTheDocument'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?=language('common/main/main', 'tMainApproveStatus'); ?></p>
                <ul>
                    <li><?=language('common/main/main', 'tMainApproveStatus1'); ?></li>
                    <li><?=language('common/main/main', 'tMainApproveStatus2'); ?></li>
                    <li><?=language('common/main/main', 'tMainApproveStatus3'); ?></li>
                    <li><?=language('common/main/main', 'tMainApproveStatus4'); ?></li>
                </ul>
                <p><?=language('common/main/main', 'tMainApproveStatus5'); ?></p>
                <p><strong><?=language('common/main/main', 'tMainApproveStatus6'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxRPPDocumentSaveAndAppoveDoc(true)" type="button" class="btn xCNBTNPrimery"><?=language('common/main/main', 'tModalConfirm'); ?></button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- =============================================================================================================================================== -->

<!-- ================================================================= ยกเลิกเอกสาร  ================================================================ -->
<div class="modal fade" id="odvRPPPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('document/document/document', 'tDocDocumentCancel') ?></label>
            </div>
            <div class="modal-body">
                <p>เอกสารที่ทำการยกเลิกแล้ว จะไม่สามารถแก้ไขได้</p>
                <p><strong><?=language('document/document/document', 'tDocCancelText2') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxRPPDocumentCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- =============================================================================================================================================== -->

<?php include('script/jReceiptPurchasepmtPageAdd.php');?>