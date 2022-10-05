<?php   
$nAddressVersion = FCNaHAddressFormat('TCNMCst');
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == "1") {
    // print_r($aDataDocHD);
    // print_r($aDataDocSPL);
    // print_r($aDataStep2);    
    
    $tIVCRoute              = "docInvoiceCustomerBillEventEdit";
    $tIVCDocNo              = $aDataDocHD['raItems']['FTXphDocNo'];
    $dIVCDocDate            = date("Y-m-d",strtotime($aDataDocHD['raItems']['FDXphDocDate']));
    $dIVCDocTime            = date("H:i",strtotime($aDataDocHD['raItems']['FDXphDocDate']));
    $nStaUploadFile         = 2; 
    $tIVCStaDoc             = $aDataDocHD['raItems']['FTXphStaDoc'];
    // $tIVCStaPrc             = $aDataDocHD['raItems']['FTPchStaPrcDoc'];
    $tIVCStaApv             = $aDataDocHD['raItems']['FTXphStaApv'];
    $tIVCBchCode            = $aDataDocHD['raItems']['FTBchCode'];
    $tIVCBchName            = $aDataDocHD['raItems']['FTBchName'];
    $tIVCRmk                = $aDataDocHD['raItems']['FTXphRmk'];
    if($tIVCRmk == ''){
        // $tIVCRmk                = 'เป็นส่วนขายและบริหารธุรกิจยานยนต์';
        $tIVCRmk                = 'ส่วนขายและบริหารธุรกิจบริการยานยนต์';

    }
    $tIVCSPLCode            = @$aDataDocSPL['raItems']['FTCstCode'];
    $tIVCSPLName            = @$aDataDocSPL['raItems']['FTCstName'];
    $tIVCCstBchCode         = @$aDataDocSPL['raItems']['FTXphCstRef'];
    $tIVCCstBchName         = @$aDataDocSPL['raItems']['FTCbrBchName'];
    $nIVCStaDocAct          = $aDataDocHD['raItems']['FNXphStaDocAct'];
    $nIVCStaRef             = $aDataDocHD['raItems']['FNXphStaRef'];
    $tIVCFrmDocPrint        = $aDataDocHD['raItems']['FNXphDocPrint'];
    $tIVCCreateByName       = $aDataDocHD['raItems']['FTUsrName'];
    $tIVCDstPaid            = @$aDataDocSPL['raItems']['FTXphDstPaid'];
    $tIVCCrTerm             = @$aDataDocSPL['raItems']['FNXphCrTerm'];
    $tIVCCshorCrd           = @$aDataDocSPL['raItems']['FTXphCshOrCrd'];
    $tIVCCondition          = $aDataDocHD['raItems']['FTXphCond'];
    $tIVCApvCode            = $aDataDocHD['raItems']['FTXphApvCode'];
    $tIVCUsrNameApv         = $aDataDocHD['raItems']['FTXphApvName'];
    $tIVCFTPrdCode          = $aDataDocHD['raItems']['FTPrdCode'];
    $tIVCFTDueDate          = date("Y-m-d",strtotime($aDataDocHD['raItems']['FDXphDueDate']));
    $tIVCCTRCode            = '';
    $tIVCCTRName            = @$aDataDocSPL['raItems']['CtrName'];

    
    if($aDataDocHDDocRef['rtCode'] == 1){ //มีการอ้างอิงเอกสาร
        if(@$aDataDocHDDocRef['raItems'][0]->FTXshRefType == 1){ //อ้างอิงภายใน
            $tIVCRefInt             = @$aDataDocHDDocRef['raItems'][0]->FTXshRefDocNo;
            $tIVCRefIntDate         = @$aDataDocHDDocRef['raItems'][0]->FDXshRefDocDate;
        }else{
            $tIVCRefInt             = @$aDataDocHDDocRef['raItems'][1]->FTXshRefDocNo;
            $tIVCRefIntDate         = @$aDataDocHDDocRef['raItems'][1]->FDXshRefDocDate;
        }
        
        if(@$aDataDocHDDocRef['raItems'][0]->FTXshRefType == 3){ //อ้างอิงภายนอก
            $tIVCRefEx              = @$aDataDocHDDocRef['raItems'][0]->FTXshRefDocNo;
            $tIVCRefExDate          = @$aDataDocHDDocRef['raItems'][0]->FDXshRefDocDate;
        }else{
            $tIVCRefEx              = @$aDataDocHDDocRef['raItems'][1]->FTXshRefDocNo;
            $tIVCRefExDate          = @$aDataDocHDDocRef['raItems'][1]->FDXshRefDocDate;
        }
    }
    
    if(@$aDataDocSPL['raItems']['FTAddVersion'] == '2'){
        $tIVCAddress            = @$aDataDocSPL['raItems']['FTAddV2Desc1'] . ' ' . @$aDataDocSPL['raItems']['FTAddV2Desc2'];
    }elseif(@$aDataDocSPL['raItems']['FTAddVersion'] == '1'){
        $tIVCAddress            = @$aDataDocSPL['raItems']['FTAddV1No'] . ' ' . @$aDataDocSPL['raItems']['FTAddV1Soi'] . ' ' . @$aDataDocSPL['raItems']['FTAddV1Road'] . ' ' . @$aDataDocSPL['raItems']['FTAddV1Village'];
    }
    $tIVCCstTel             = @$aDataDocSPL['raItems']['FTCstTel'];
    $tIVCCstEmail           = @$aDataDocSPL['raItems']['FTCstEmail'];
    // print_r($tIVCAddress);
    // print_r($aDataDocSPL);

} else {
    $tIVCRoute              = "docInvoiceCustomerBillEventAdd";
    $nStaUploadFile         = 1; 
    $tIVCDocNo              = '';
    $tIVCStaApv             = '';
    $nIVCStaDocAct          = '';
    $nIVCStaRef             = '';
    $tIVCFrmDocPrint        = '';
    // $tIVCRmk                = 'เป็นส่วนขายและบริหารธุรกิจยานยนต์';
    $tIVCRmk                = 'ส่วนขายและบริหารธุรกิจบริการยานยนต์';
    $tIVCApvCode            = '';
    $tIVCFTPrdCode          = '';
    $tIVCUsrNameApv         = '';
    $tIVCFTDueDate          = '';
    $tIVCCTRCode            = '';
    $tIVCCTRName            = '';
    $tIVCAddress            = '';
    $tIVCCstTel             = '';
    $tIVCCstEmail           = '';
    $dIVCDocDate            = date('Y-m-d');
    $dIVCDocTime            = date('H:i:s');
    $tIVCCreateByName       = $this->session->userdata('tSesUsrUsername');
    $tIVCCstBchCode         = '';
    $tIVCCstBchName         = '';
    $tIVCDstPaid            = '';
}
?>

<form id="ofmIVCFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtIVCSubmitDocument" onclick="JSxIVCAddEditDocument()"></button>
    <input type="hidden" id="ohdIVCDecimalShow" name="ohdIVCDecimalShow" value="<?=$nOptDecimalShow?>">
    <input type="hidden" id="ohdIVCRoute" name="ohdIVCRoute" value="<?=@$tIVCRoute?>">
    <input type="hidden" id="ohdIVCDocNo" name="ohdIVCDocNo" value="<?=@$tIVCDocNo?>">
    <input type="hidden" id="ohdIVCDocDate" name="ohdIVCDocDate" value="<?=@$dIVCDocDate?>">
    <input type="hidden" id="ohdIVCDocTime" name="ohdIVCDocTime" value="<?=@$dIVCDocTime?>">
    <input type="hidden" id="ohdIVCStaApv" name="ohdIVCStaApv" value="<?=@$tIVCStaApv?>">
    <input type="hidden" id="ohdIVCStaPrc" name="ohdIVCStaPrc" value="<?=@$tIVCStaPrc?>">
    <input type="hidden" id="ohdIVCStaDoc" name="ohdIVCStaDoc" value="<?=@$tIVCStaDoc?>">
    <input type="hidden" id="ohdConfirmIVCInsertPDT" name="ohdConfirmIVCInsertPDT">


    <!--เก็บเอาไว้ว่าเป็นการบันทึก หรือ บันทึกและยืนยันการเคลม-->
    <!--1 : บันทึกเฉยๆ -->
    <!--2 : บันทึกและยืนยันการเคลม -->
    <input type="hidden" id="ohdIVCStaSaveOrSaveClaim" name="ohdIVCStaSaveOrSaveClaim" value="1">
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/quotation/quotation', 'tTQDocument'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvIVCDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvIVCDataStatusInfo" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="xCNTitleFrom "><?= language('document/quotation/quotation', 'tTQApproved'); ?></label>
                                </div>

                                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/quotation/quotation', 'tTQDocNo'); ?></label>
                                <?php if (empty($tIVCDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbIVCStaAutoGenCode" name="ocbIVCStaAutoGenCode" maxlength="1" checked="true" value="1">
                                            <span class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xCNInputReadOnly xWTooltipsBT xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetIVCDocNo" name="oetIVCDocNo" maxlength="20" value="<?= $tIVCDocNo; ?>" 
                                           data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterOrRunDocNo'); ?>" 
                                           data-validate-duplicate="<?= language('document/quotation/quotation', 'tTQPlsDocNoDuplicate'); ?>" 
                                           placeholder="<?= language('document/quotation/quotation', 'tTQDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdIVCCheckDuplicateCode" name="ohdIVCCheckDuplicateCode" value="2">
                                </div>

                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" id="oetIVCDocDate" name="oetIVCDocDate" value="<?= $dIVCDocDate; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIVCDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNInputReadOnly xCNTimePicker" id="oetIVCDocTime" name="oetIVCDocTime" value="<?= $dIVCDocTime; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIVCDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?= $tIVCCreateByName ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQTBStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php   
                                                if($tIVCRoute == "docInvoiceCustomerBillEventEdit"){
                                                    $tIVLabelStaDoc  = language('document/quotation/quotation', 'tTQStaDoc'.$tIVCStaDoc);
                                                }else{
                                                    $tIVLabelStaDoc  = '-';
                                                }
                                            ?>
                                            <label><?=$tIVLabelStaDoc;?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQTBStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?= language('document/quotation/quotation', 'tTQStaApv' . $tIVCStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tIVCDocNo) && !empty($tIVCDocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdIVApvCode" name="ohdIVApvCode" maxlength="20" value="<?= $tIVCApvCode ?>">
                                                <label>
                                                    <?= (isset($tIVCUsrNameApv) && !empty($tIVCUsrNameApv)) ? $tIVCUsrNameApv : "-"; ?>
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
            <?php include('Panel/wPanelCondition.php');?>

            <!-- Panel ลูกค้า -->    
            <?php include('Panel/wPanelCustomer.php');?>

             <!-- Panel อ้างอิง -->
            <?php include('Panel/wPanelRef.php');?>

             <!-- Panel อื่นๆ -->
             <?php include('Panel/wPanelOther.php');?>

            <!-- Panel ไฟลแนบ -->
            <?php include('Panel/wPanelFileImport.php');?>
        </div>

        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <!-- ตารางสินค้า -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px; position:relative; min-height:200px;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                               
                                <div class="row">
                                    <!-- ผู้จำหน่าย -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill','tIVCCustomerChoose'); ?></label>
                                            <div class="input-group" style="width:100%;">
                                                <input type="text" class="input100 xCNHide" id="ohdIVCCSTCode" name="ohdIVCCSTCode" value="<?= @$tIVCSPLCode; ?>">
                                                <input class="form-control xWPointerEventNone" type="text" id="oetIVCCSTName" name="oetIVCCSTName" value="<?= @$tIVCSPLName; ?>" readonly placeholder="<?= language('document/invoicebill/invoicebill','tIVCValidateCustomer'); ?>">
                                                <span class="input-group-btn">
                                                    <button id="obtIVCBrowseCST" type="button" class="btn xCNBtnBrowseAddOn" >
                                                        <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('document/invoicebill/invoicebill','tIVCCustomerBchChoose'); ?></label>
                                            <div class="input-group" style="width:100%;">
                                                <input type="text" class="form-control xCNInputReadOnly xControlForm xCNHide" id="oetIVCCstBchFrm" name="oetIVCCstBchFrm" value="<?= @$tIVCCstBchCode ?>" maxlength="20">
                                                <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetIVCCstBchFrmName" name="oetIVCCstBchFrmName" maxlength="100" placeholder="<?= language('document/invoicebill/invoicebill', 'tIVCCustomerBchChoose') ?>" value="<?= @$tIVCCstBchName ?>" readonly>
                                                <span class="input-group-btn">
                                                    <button id="oimIVCBrowseCstBch" type="button" class="btn xCNBtnBrowseAddOn">
                                                        <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="row tab-content xCNClaimTabContent">
                                            <div id="odvInvoiceBillStep1" class="tab-pane fade in active" style="padding-top: 0px;">
                                                <?php include('step_form/wStep1.php'); ?>
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

<!-- =========================================== กรุณากรอกข้อมูลให้ครบถ้วน =========================================== -->
<div id="odvIVCModalPleseDataInFill" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="ospIVCModalPleseDataInFill">กรุณาเลือกลูกค้า ก่อนทำรายการ</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อนุมัติเอกสาร  ============================================= -->
<div class="modal fade xCNModalApprove" id="odvIVCPopupApv">
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
                <button onclick="JSxIVCDocumentApv(true)" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== ยกเลิกเอกสาร  ============================================= -->
<div class="modal fade" id="odvIVCPopupCancel">
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
                <button onclick="JSxIVCDocumentCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== เคลียค่า  ============================================= -->
<div class="modal fade" id="odvIVCPopupWarning">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?=language('document/invoicebill/invoicebill', 'tIVCWarning') ?></label>
            </div>
            <div class="modal-body">
                <p id="tWarningText"></p>
                <p><strong id="tWarningTextCfm"></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxIVCPopupWarning(true)" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult xCNBtnControllHide" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php include('script/jInvoicecustomerbillPageAdd.php');?>
