<?php   
$nAddressVersion = FCNaHAddressFormat('TCNMSpl');
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == "1") {
    // print_r($aDataDocHD);
    // print_r($aDataDocSPL);
    // print_r($aDataStep2);    
    
    $tIVBRoute              = "docInvoiceBillEventEdit";
    $tIVBDocNo              = $aDataDocHD['raItems']['FTXphDocNo'];
    $dIVBDocDate            = date("Y-m-d",strtotime($aDataDocHD['raItems']['FDXphDocDate']));
    $dIVBDocTime            = date("H:i",strtotime($aDataDocHD['raItems']['FDXphDocDate']));
    $nStaUploadFile         = 2; 
    $tIVBStaDoc             = $aDataDocHD['raItems']['FTXphStaDoc'];
    // $tIVBStaPrc             = $aDataDocHD['raItems']['FTPchStaPrcDoc'];
    $tIVBStaApv             = $aDataDocHD['raItems']['FTXphStaApv'];
    $tIVBBchCode            = $aDataDocHD['raItems']['FTBchCode'];
    $tIVBBchName            = $aDataDocHD['raItems']['FTBchName'];
    $tIVBRmk                = $aDataDocHD['raItems']['FTXphRmk'];
    $tIVBSPLCode            = $aDataDocSPL['raItems']['FTSplCode'];
    $tIVBSPLName            = $aDataDocSPL['raItems']['FTSplName'];
    $nIVBStaDocAct          = $aDataDocHD['raItems']['FNXphStaDocAct'];
    $nIVBStaRef             = $aDataDocHD['raItems']['FNXphStaRef'];
    $tIVBFrmDocPrint        = $aDataDocHD['raItems']['FNXphDocPrint'];
    $tIVBCreateByName       = $aDataDocHD['raItems']['FTUsrName'];
    $tIVBDstPaid            = $aDataDocSPL['raItems']['FTXphDstPaid'];
    $tIVBCrTerm             = $aDataDocSPL['raItems']['FNXphCrTerm'];
    $tIVBCshorCrd           = $aDataDocSPL['raItems']['FTXphCshOrCrd'];
    $tIVBCondition          = $aDataDocHD['raItems']['FTXphCond'];
    $tIVBApvCode            = $aDataDocHD['raItems']['FTXphApvCode'];
    $tIVBUsrNameApv         = $aDataDocHD['raItems']['FTXphApvName'];
    $tIVBFTPrdCode          = $aDataDocHD['raItems']['FTPrdCode'];
    $tIVBFTDueDate          = date("Y-m-d",strtotime($aDataDocHD['raItems']['FDXphDueDate']));
    $tIVBCTRCode            = '';
    $tIVBCTRName            = $aDataDocSPL['raItems']['CtrName'];

    
    if($aDataDocHDDocRef['rtCode'] == 1){ //มีการอ้างอิงเอกสาร
        if(@$aDataDocHDDocRef['raItems'][0]->FTXshRefType == 1){ //อ้างอิงภายใน
            $tIVBRefInt             = @$aDataDocHDDocRef['raItems'][0]->FTXshRefDocNo;
            $tIVBRefIntDate         = @$aDataDocHDDocRef['raItems'][0]->FDXshRefDocDate;
        }else{
            $tIVBRefInt             = @$aDataDocHDDocRef['raItems'][1]->FTXshRefDocNo;
            $tIVBRefIntDate         = @$aDataDocHDDocRef['raItems'][1]->FDXshRefDocDate;
        }
        
        if(@$aDataDocHDDocRef['raItems'][0]->FTXshRefType == 3){ //อ้างอิงภายนอก
            $tIVBRefEx              = @$aDataDocHDDocRef['raItems'][0]->FTXshRefDocNo;
            $tIVBRefExDate          = @$aDataDocHDDocRef['raItems'][0]->FDXshRefDocDate;
        }else{
            $tIVBRefEx              = @$aDataDocHDDocRef['raItems'][1]->FTXshRefDocNo;
            $tIVBRefExDate          = @$aDataDocHDDocRef['raItems'][1]->FDXshRefDocDate;
        }
    }

    if($nAddressVersion == '2'){
        $tIVBAddress            = $aDataDocSPL['raItems']['FTAddV2Desc1'] . ' ' . $aDataDocSPL['raItems']['FTAddV2Desc2'];
    }elseif($nAddressVersion == '1'){
        $tIVBAddress            = $aDataDocSPL['raItems']['FTAddV1No'] . ' ' . $aDataDocSPL['raItems']['FTAddV1Soi'] . ' ' . $aDataDocSPL['raItems']['FTAddV1Road'] . ' ' . $aDataDocSPL['raItems']['FTAddV1Village'];
    }
    // print_r($tIVBAddress);

} else {
    $tIVBRoute              = "docInvoiceBillEventAdd";
    $nStaUploadFile         = 1; 
    $tIVBDocNo              = '';
    $tIVBStaApv             = '';
    $nIVBStaDocAct          = '';
    $nIVBStaRef             = '';
    $tIVBFrmDocPrint        = '';
    $tIVBRmk                = '';
    $tIVBApvCode            = '';
    $tIVBFTPrdCode          = '';
    $tIVBUsrNameApv         = '';
    $tIVBFTDueDate          = '';
    $tIVBCTRCode            = '';
    $tIVBCTRName            = '';
    $tIVBAddress            = '';
    $dIVBDocDate            = date('Y-m-d');
    $dIVBDocTime            = date('H:i:s');
    $tIVBCreateByName       = $this->session->userdata('tSesUsrUsername');
}
?>

<form id="ofmIVBFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtIVBSubmitDocument" onclick="JSxIVBAddEditDocument()"></button>
    <input type="hidden" id="ohdIVBDecimalShow" name="ohdIVBDecimalShow" value="<?=$nOptDecimalShow?>">
    <input type="hidden" id="ohdIVBRoute" name="ohdIVBRoute" value="<?=@$tIVBRoute?>">
    <input type="hidden" id="ohdIVBDocNo" name="ohdIVBDocNo" value="<?=@$tIVBDocNo?>">
    <input type="hidden" id="ohdIVBStaApv" name="ohdIVBStaApv" value="<?=@$tIVBStaApv?>">
    <input type="hidden" id="ohdIVBStaPrc" name="ohdIVBStaPrc" value="<?=@$tIVBStaPrc?>">
    <input type="hidden" id="ohdIVBStaDoc" name="ohdIVBStaDoc" value="<?=@$tIVBStaDoc?>">
    <input type="hidden" id="ohdConfirmIVBInsertPDT" name="ohdConfirmIVBInsertPDT">


    <!--เก็บเอาไว้ว่าเป็นการบันทึก หรือ บันทึกและยืนยันการเคลม-->
    <!--1 : บันทึกเฉยๆ -->
    <!--2 : บันทึกและยืนยันการเคลม -->
    <input type="hidden" id="ohdIVBStaSaveOrSaveClaim" name="ohdIVBStaSaveOrSaveClaim" value="1">
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/quotation/quotation', 'tTQDocument'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvIVBDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvIVBDataStatusInfo" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="xCNTitleFrom "><?= language('document/quotation/quotation', 'tTQApproved'); ?></label>
                                </div>

                                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/quotation/quotation', 'tTQDocNo'); ?></label>
                                <?php if (empty($tIVBDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbIVBStaAutoGenCode" name="ocbIVBStaAutoGenCode" maxlength="1" checked="true" value="1">
                                            <span class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xCNInputReadOnly xWTooltipsBT xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetIVBDocNo" name="oetIVBDocNo" maxlength="20" value="<?= $tIVBDocNo; ?>" 
                                           data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterOrRunDocNo'); ?>" 
                                           data-validate-duplicate="<?= language('document/quotation/quotation', 'tTQPlsDocNoDuplicate'); ?>" 
                                           placeholder="<?= language('document/quotation/quotation', 'tTQDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdIVBCheckDuplicateCode" name="ohdIVBCheckDuplicateCode" value="2">
                                </div>

                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" id="oetIVBDocDate" name="oetIVBDocDate" value="<?= $dIVBDocDate; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIVBDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNInputReadOnly xCNTimePicker" id="oetIVBDocTime" name="oetIVBDocTime" value="<?= $dIVBDocTime; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtIVBDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                                            <label><?= $tIVBCreateByName ?></label>
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
                                                if($tIVBRoute == "docInvoiceBillEventEdit"){
                                                    $tIVLabelStaDoc  = language('document/quotation/quotation', 'tTQStaDoc'.$tIVBStaDoc);
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
                                            <label><?= language('document/quotation/quotation', 'tTQStaApv' . $tIVBStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tIVBDocNo) && !empty($tIVBDocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdIVApvCode" name="ohdIVApvCode" maxlength="20" value="<?= $tIVBApvCode ?>">
                                                <label>
                                                    <?= (isset($tIVBUsrNameApv) && !empty($tIVBUsrNameApv)) ? $tIVBUsrNameApv : "-"; ?>
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
                                    <div class="col-md-12">

                                        <!-- ผู้จำหน่าย -->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?= language('document/purchaseinvoice/purchaseinvoice','tPITBSpl'); ?></label>
                                            <div class="input-group" style="width:100%;">
                                                <input type="text" class="input100 xCNHide" id="ohdIVBSPLCode" name="ohdIVBSPLCode" value="<?= @$tIVBSPLCode; ?>">
                                                <input class="form-control xWPointerEventNone" type="text" id="oetIVBSPLName" name="oetIVBSPLName" value="<?= @$tIVBSPLName; ?>" readonly placeholder="<?= language('document/purchaseinvoice/purchaseinvoice','tPIMsgValidSplCode'); ?>">
                                                <span class="input-group-btn">
                                                    <button id="obtIVBBrowseSPL" type="button" class="btn xCNBtnBrowseAddOn" >
                                                        <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

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
<div id="odvIVBModalPleseDataInFill" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="ospIVBModalPleseDataInFill">กรุณาเลือกผู้จำหน่าย ก่อนทำรายการ</p>
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
<div class="modal fade xCNModalApprove" id="odvIVBPopupApv">
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
                <button onclick="JSxIVBDocumentApv(true)" type="button" class="btn xCNBTNPrimery">
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
<div class="modal fade" id="odvIVBPopupCancel">
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
                <button onclick="JSxIVBDocumentCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php include('script/jInvoicebillPageAdd.php');?>
