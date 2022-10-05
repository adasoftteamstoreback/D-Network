<?php
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == "1") {
    $tCLMRoute              = "docClaimEventEdit";
    $tCLMDocNo              = $aDataDocHD['raItems']['FTPchDocNo'];
    $dCLMDocDate            = date("Y-m-d", strtotime($aDataDocHD['raItems']['FDPchDocDate']));
    $dCLMDocTime            = date("H:i",strtotime($aDataDocHD['raItems']['FDPchDocDate']));
    $tCLMCreateByName       = $aDataDocHD['raItems']['FTUsrName'];
    $nStaUploadFile         = 2;
    $tCLMStaDoc             = $aDataDocHD['raItems']['FTPchStaDoc'];
    $tCLMStaPrc             = $aDataDocHD['raItems']['FTPchStaPrcDoc'];
    $tCLMStaApv             = $aDataDocHD['raItems']['FTPchStaApv'];
    $tCLMBchCode            = $aDataDocHD['raItems']['FTBchCode'];
    $tCLMBchName            = $aDataDocHD['raItems']['FTBchName'];
    $tCLMMile               = $aDataDocHD['raItems']['FCXshCarMileage'];
    $tCLMRmk                = $aDataDocHD['raItems']['FTPchRmk'];
    $tCLMCarCode            = $aDataDocCST['raItems']['FTCarCode'];
    $tCLMCarRegNo           = $aDataDocCST['raItems']['FTCarRegNo'];
    $tCLMCstCode            = $aDataDocCST['raItems']['FTCstCode'];
    $tCLMCstName            = $aDataDocCST['raItems']['FTCstName'];
    $tCLMCstADDL            = $aDataDocCST['raItems']['FTAddV2Desc1'];
    $tCLMCstTel             = $aDataDocCST['raItems']['FTCstTel'];
    $tCLMCstEmail           = $aDataDocCST['raItems']['FTCstEmail'];

    if($aDataDocHDDocRef['rtCode'] == 1){ //มีการอ้างอิงเอกสาร
        if(@$aDataDocHDDocRef['raItems'][0]->FTXshRefType == 1){ //อ้างอิงภายใน
            $tCLMRefInt             = @$aDataDocHDDocRef['raItems'][0]->FTXshRefDocNo;
            $tCLMRefIntDate         = date("Y-m-d", strtotime(@$aDataDocHDDocRef['raItems'][0]->FDXshRefDocDate));
        }else{
            $tCLMRefInt             = @$aDataDocHDDocRef['raItems'][1]->FTXshRefDocNo;
            $tCLMRefIntDate         = date("Y-m-d", strtotime(@$aDataDocHDDocRef['raItems'][1]->FDXshRefDocDate));
        }

        if(@$aDataDocHDDocRef['raItems'][0]->FTXshRefType == 3){ //อ้างอิงภายนอก
            $tCLMRefEx              = @$aDataDocHDDocRef['raItems'][0]->FTXshRefDocNo;
            $tCLMRefExDate          = date("Y-m-d", strtotime(@$aDataDocHDDocRef['raItems'][0]->FDXshRefDocDate));
        }else{
            $tCLMRefEx              = @$aDataDocHDDocRef['raItems'][1]->FTXshRefDocNo;
            $tCLMRefExDate          = date("Y-m-d", strtotime(@$aDataDocHDDocRef['raItems'][1]->FDXshRefDocDate));
        }
    }
} else {
    $tCLMRoute              = "docClaimEventAdd";
    $nStaUploadFile         = 1;
    $tCLMDocNo              = '';
    $tCLMStaApv             = '';
    $dCLMDocDate            = date('Y-m-d');
    $dCLMDocTime            = date('H:i:s');
    $tCLMCreateByName       = $this->session->userdata('tSesUsrUsername');
}
?>

<form id="ofmCLMFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtCLMSubmitDocument" onclick="JSxCLMAddEditDocument()"></button>
    <input type="hidden" id="ohdCLMDecimalShow" name="ohdCLMDecimalShow" value="<?=$nOptDecimalShow?>">
    <input type="hidden" id="ohdCLMRoute" name="ohdCLMRoute" value="<?=@$tCLMRoute?>">
    <input type="hidden" id="ohdCLMDocNo" name="ohdCLMDocNo" value="<?=@$tCLMDocNo?>">
    <input type="hidden" id="ohdCLMStaApv" name="ohdCLMStaApv" value="<?=@$tCLMStaApv?>">
    <input type="hidden" id="ohdCLMStaPrc" name="ohdCLMStaPrc" value="<?=@$tCLMStaPrc?>">
    <input type="hidden" id="ohdCLMStaDoc" name="ohdCLMStaDoc" value="<?=@$tCLMStaDoc?>">
    <input type="hidden" id="ohdCLMStaDocFile" name="ohdCLMStaDocFile" value="<?=@($tCLMStaDoc == '2' ) ? 3 : @$tCLMStaDoc?>">

    <!--เก็บเอาไว้ว่าเป็นการบันทึก หรือ บันทึกและยืนยันการเคลม-->
    <!--1 : บันทึกเฉยๆ -->
    <!--2 : บันทึกและยืนยันการเคลม -->
    <input type="hidden" id="ohdCLMStaSaveOrSaveClaim" name="ohdCLMStaSaveOrSaveClaim" value="1">
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/quotation/quotation', 'tTQDocument'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvCLMDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvCLMDataStatusInfo" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="xCNTitleFrom "><?= language('document/quotation/quotation', 'tTQApproved'); ?></label>
                                </div>

                                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/quotation/quotation', 'tTQDocNo'); ?></label>
                                <?php if (empty($tCLMDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbCLMStaAutoGenCode" name="ocbCLMStaAutoGenCode" maxlength="1" checked="true" value="1">
                                            <span class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xCNInputReadOnly xWTooltipsBT xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetCLMDocNo" name="oetCLMDocNo" maxlength="20" value="<?= $tCLMDocNo; ?>"
                                           data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterOrRunDocNo'); ?>"
                                           data-validate-duplicate="<?= language('document/quotation/quotation', 'tTQPlsDocNoDuplicate'); ?>"
                                           placeholder="<?= language('document/quotation/quotation', 'tTQDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdCLMCheckDuplicateCode" name="ohdCLMCheckDuplicateCode" value="2">
                                </div>

                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNInputReadOnly xCNDatePicker xCNInputMaskDate" id="oetCLMDocDate" name="oetCLMDocDate" value="<?= $dCLMDocDate; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtCLMDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNInputReadOnly xCNTimePicker" id="oetCLMDocTime" name="oetCLMDocTime" value="<?= $dCLMDocTime; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtCLMDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                                            <label><?= $tCLMCreateByName ?></label>
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
                                                if($tCLMRoute == "docClaimEventEdit"){
                                                    if($tCLMStaDoc == 2){
                                                        $tTextStaPrcDoc = 'เอกสารยกเลิก';
                                                    }else{
                                                        if ($tCLMStaPrc == 1 ) {
                                                            $tTextStaPrcDoc = 'รออนุมัติ';
                                                        }else if($tCLMStaPrc == 2 ) {
                                                            $tTextStaPrcDoc = 'รอส่งสินค้าไปยังผู้จำหน่าย';
                                                        }else if($tCLMStaPrc == 3 ) {
                                                            $tTextStaPrcDoc = 'รอรับสินค้าจากผู้จำหน่าย';
                                                        }else if($tCLMStaPrc == 4 ) {
                                                            $tTextStaPrcDoc = 'รับสินค้าบางส่วนจากผู้จำหน่ายแล้ว';
                                                        }else if($tCLMStaPrc == 5 ) {
                                                            $tTextStaPrcDoc = 'รอส่งสินค้าให้ลูกค้า';
                                                        }else if($tCLMStaPrc == 6 ) {
                                                            $tTextStaPrcDoc = 'ส่งสินค้าบางส่วนให้ลูกค้าแล้ว';
                                                        }else{
                                                            $tTextStaPrcDoc = 'เอกสารสมบูรณ์';
                                                        }
                                                    }
                                                    $tCLMLabelPrcDoc  = $tTextStaPrcDoc;
                                                }else{
                                                    $tCLMLabelPrcDoc  = '-';
                                                }
                                            ?>
                                            <label><?=$tCLMLabelPrcDoc;?></label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Panel เงื่อนไข อ้างอิงเอกสารภายใน และ ภายนอก-->
            <?php include('Panel/wPanelCondition.php');?>

            <!-- Panel ลูกค้า -->
            <?php include('Panel/wPanelCustomer.php');?>

             <!-- Panel ข้อมูลรถ -->
            <?php include('Panel/wPanelCar.php');?>

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

                                        <div id="odvClaimLineCont">
                                            <div id="odvClaimLine">
                                                <div class="xCNClaimCircle active xCNClaimStep1" data-tab="odvClaimStep1" data-step="1" style="left: -7px;">
                                                    <div class="xCNClaimPopupSpan"><?=language('document/invoice/invoice','ตรวจสอบเงื่อนไขการรับประกัน'); ?></div>
                                                </div>
                                                <div class="xCNClaimCircle xCNClaimStep2" data-tab="odvClaimStep2" data-step="2" style="left: 33%;">
                                                    <div class="xCNClaimPopupSpan"><?=language('document/invoice/invoice', 'ส่งสินค้าไปยังผู้จำหน่าย'); ?></div>
                                                </div>
                                                <div class="xCNClaimCircle xCNClaimStep3" data-tab="odvClaimStep3" data-step="3" style="left: 66%;">
                                                    <div class="xCNClaimPopupSpan"><?=language('document/invoice/invoice', 'รับสินค้าจากผู้จำหน่าย'); ?></div>
                                                </div>
                                                <div class="xCNClaimCircle xCNClaimStep4" data-tab="odvClaimStep4" data-step="4" style="left: 99%;">
                                                    <div class="xCNClaimPopupSpan" style="left:-55px;"><?= language('document/invoice/invoice', 'ลูกค้ารับของ'); ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        <ul class="nav nav-tabs hidden">
                                            <li class="active"><a data-toggle="tab" href="#odvClaimStep1"></a></li>
                                            <li><a data-toggle="tab" href="#odvClaimStep2"></a></li>
                                            <li><a data-toggle="tab" href="#odvClaimStep3"></a></li>
                                            <li><a data-toggle="tab" href="#odvClaimStep4"></a></li>
                                        </ul>

                                        <!-- Step Control -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button disabled class="btn xCNBTNPrimery xCNBTNPrimery2Btn xCNClaimBackStep" type="button" style="display: none; width:150px;"> <?= language('document/Promotion/Promotion', 'tBack'); ?></button>
                                                <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xCNClaimNextStep" type="button" style="display: inline-block; width:150px;"> <?= language('document/Promotion/Promotion', 'tNext'); ?></button>
                                                <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xCNClaimConfirm" type="button" style="display: none; width:150px; float: right;"> ยืนยันการเคลม</button>
                                            </div>
                                        </div>

                                        <div class="row tab-content xCNClaimTabContent">
                                            <div id="odvClaimStep1" class="tab-pane fade in active" >
                                                <?php if(@$tCLMStaDoc == 2){ //เอกสารยกเลิก ?>
                                                    <?php include('step_form/wStep1Result.php'); ?>
                                                <?php }else{ ?>
                                                    <?php if(@$tCLMStaPrc < 2){ //ถ้าส่งสินค้าหาผู้จำหน่ายแล้ว จะโชว์สรุป ?>
                                                        <div><hr style="margin-top: 0px;"></div>
                                                        <?php include('step_form/wStep1.php'); ?>
                                                    <?php }else{ //ยังไม่ได้ส่งหาผู้จำหน่าย ?>
                                                        <?php include('step_form/wStep1Result.php'); ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <div id="odvClaimStep2" class="tab-pane fade">
                                                <?php include('step_form/wStep2.php'); ?>
                                            </div>
                                            <div id="odvClaimStep3" class="tab-pane fade">
                                                <?php include('step_form/wStep3.php'); ?>
                                            </div>
                                            <div id="odvClaimStep4" class="tab-pane fade">
                                                <?php include('step_form/wStep4.php'); ?>
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
<div id="odvCLMModalPleseDataInFill" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="ospCLMModalPleseDataInFill">กรุณาเลือกผู้จำหน่าย ก่อนทำรายการ</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== กรุณาทำการส่งสินค้าไปยังผู้จำหน่ายก่อน =========================================== -->
<div id="odvCLMModalStepNotClear" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="ospCLMModalStepNotClear">กรุณาทำการส่งสินค้าไปยังผู้จำหน่ายให้ครบถ้วนก่อน ทำรายการถัดไป</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== ยกเลิกเอกสาร  ============================================= -->
<div class="modal fade" id="odvCLMPopupCancel">
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
                <button onclick="JSxCLMDocumentCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php include('script/jClaimPageAdd.php');?>
