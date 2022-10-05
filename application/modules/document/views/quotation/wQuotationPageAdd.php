<?php
$nAddressVersion = FCNaHAddressFormat('TCNMCst');
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == "1") {
    $tTQRoute              = "docQuotationEventEdit";
    $tTQDocNo              = $aDataDocHD['raItems']['FTXshDocNo'];
    $dTQDocDate            = date("Y-m-d",strtotime($aDataDocHD['raItems']['FDXshDocDate']));
    $dTQDocTime            = date("H:i", strtotime($aDataDocHD['raItems']['FDXshDocDate']));
    $tTQCreateBy           = $aDataDocHD['raItems']['FTCreateBy'];
    $tTQCreateByName       = $aDataDocHD['raItems']['FTUsrName'];
    $tTQStaDoc             = $aDataDocHD['raItems']['FTXshStaDoc'];
    $tTQStaApv             = $aDataDocHD['raItems']['FTXshStaApv'];
    $tTQApvCode            = $aDataDocHD['raItems']['FTXshApvCode'];
    $tTQUsrNameApv         = $aDataDocHD['raItems']['FTXshApvName'];
    $tTQCusCode            = $aDataDocCstHD['raItems']['FTCstCode'];
    $tTQCusName            = $aDataDocCstHD['raItems']['FTCstName'];
    $tTQCardID             = $aDataDocCstHD['raItems']['FTXshCardID'];
    $tTQTelephone          = $aDataDocCstHD['raItems']['FTCstTel'];
    $tTQEmail              = $aDataDocCstHD['raItems']['FTCstEmail'];
    $tPPLCode              = $aDataDocCstHD['raItems']['FTPplCode'];

    if($nAddressVersion == '2'){
        $tTQAddress            = $aDataDocCstHD['raItems']['FTAddV2Desc1'];
    }elseif($nAddressVersion == '1'){
        $tTQAddress            = $aDataDocCstHD['raItems']['FTAddV1Desc'];
    }
    $tTQFTBchCode          = $aDataDocHD['raItems']['FTBchCode'];
    $tTQFTBchName          = $aDataDocHD['raItems']['FTBchName'];
    $tTQVatInOrEx          = $aDataDocHD['raItems']['FTXshVATInOrEx'];
    $tTQCshorCrd           = $aDataDocHD['raItems']['FTXshCshOrCrd'];
    $tTQCrTerm             = $aDataDocCstHD['raItems']['FNXshCrTerm'];
    $tTQAffectDate         = date("Y-m-d",strtotime($aDataDocCstHD['raItems']['FDXshDueDate']));
    $nQTStaDocAct          = $aDataDocHD['raItems']['FNXshStaDocAct'];
    $nQTFNXshStaRef        = $aDataDocHD['raItems']['FNXshStaRef'];
    $nStaUploadFile        = 2;
    $tQTRateCode           = $aDataDocHD['raItems']['FTRteCode'];
    $tQTRateName           = $aDataDocHD['raItems']['FTRteName'];
    $tQTFNXshDocPrint      = $aDataDocHD['raItems']['FNXshDocPrint'];
    $tQTFTXshRmk           = $aDataDocHD['raItems']['FTXshRmk'];
    $tFTXshRefExt          = $aDataDocHD['raItems']['FTXshRefExt'];
    $tFDXshRefExtDate      = $aDataDocHD['raItems']['FDXshRefExtDate'];
    $tFTXshRefInt          = $aDataDocHD['raItems']['FTXshRefInt'];
    $tFDXshRefIntDate      = $aDataDocHD['raItems']['FDXshRefIntDate'];
    $tJR1CarRegCode        = $aDataCarCstItem['FTCarCode'];
    $tPreCarRegName        = $aDataCarCstItem['FTCarRegNo'];
    $tPreCarTypeName       = $aDataCarCstItem['FTCarTypeName'];
    $tPreCarTypeBrand      = $aDataCarCstItem['FTCarBrandName'];
    $tPreCarTypeModel      = $aDataCarCstItem['FTCarModelName'];
    $tPreCarTypeColor      = $aDataCarCstItem['FTCarColorName'];
    $tPreCarOwnerName      = $aDataCarCstItem['FTCarOwnerName'];
    $tPreCarGearName      = $aDataCarCstItem['FTCarGearName'];
} else {
    $tTQRoute              = "docQuotationEventAdd";
    $tTQDocNo              = '';
    $dTQDocDate            = date('Y-m-d');
    $dTQDocTime            = date('H:i:s');
    $tTQCreateByName       = $this->session->userdata('tSesUsrUsername');
    $tTQStaDoc             = '1';
    $tTQStaApv             = '';
    $tTQApvCode            = '';
    $tTQUsrNameApv         = '';
    $tTQVatInOrEx          = '1';
    $tTQAffectDate         = date('Y-m-d');
    $nQTStaDocAct          = 1;
    $nStaUploadFile        = 1;
    $tQTRateCode           = $aRateDefault['raItems']['rtCmpRteCode'];
    $tQTRateName           = $aRateDefault['raItems']['rtCmpRteName'];
    $tJR1CarRegCode        = '';
    $tPreCarRegName        = '';
    $tPreCarTypeName        = '';
    $tPreCarTypeBrand       = '';
    $tPreCarTypeModel       = '';
    $tPreCarTypeColor       = '';
    $tPreCarOwnerName       = '';
    $tPreCarGearName        = '';
}

?>


<!-- ** ========================== Start Tab ปุ่ม เปิด Side Bar =============================================== * -->
<div class="xCNDivSideBarOpen xCNHide">
    <div class="xCNAbsoluteClick" onclick="JCNxOpenDiv()"></div>
    <div class="xCNAbsoluteOpen">
        <div class="input-group-btn xCNDivSideBarOpenGroup">
            <label class="xCNDivSideBarOpenWhite"><?php echo language('document/adjustmentcost/adjustmentcost', 'tDIDocumentInformation'); ?></label>
            <button class="xCNDivSideBarOpenWhite">
                <i class="fa fa-angle-double-down xCNDivSideBarOpenIcon" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</div>
<!-- ** ========================== End Tab ปุ่ม เปิด Side Bar =============================================== * -->



<form id="ofmTQFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtTQSubmitDocument" onclick="JSxTQAddEditDocument()"></button>
    <input type="hidden" id="ohdTQDecimalShow" name="ohdTQDecimalShow" value="<?= $nOptDecimalShow ?>">
    <input type="hidden" id="ohdTQRoute" name="ohdTQRoute" value="<?= @$tTQRoute ?>">
    <input type="hidden" id="ohdTQDocNo" name="ohdTQDocNo" value="<?= @$tTQDocNo ?>">
    <input type="hidden" id="ohdTQStaApv" name="ohdTQStaApv" value="<?= @$tTQStaApv ?>">
    <input type="hidden" id="ohdTQStaDoc" name="ohdTQStaDoc" value="<?= @$tTQStaDoc ?>">
    <input type="hidden" id="ohdTQApvOrSave" name="ohdTQApvOrSave" value="">
    <input type="hidden" id="ohdTQCheckClearValidate" name="ohdTQCheckClearValidate" value="0">
    <div class="row">
        <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar">
            <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->

            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/quotation/quotation', 'tTQDocument'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvTQDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvTQDataStatusInfo" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="xCNTitleFrom "><?= language('document/quotation/quotation', 'tTQApproved'); ?></label>
                                </div>

                                <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('document/quotation/quotation', 'tTQDocNo'); ?></label>
                                <?php if (empty($tTQDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbTQStaAutoGenCode" name="ocbTQStaAutoGenCode" maxlength="1" checked="true" value="1">
                                            <span class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xWTooltipsBT xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetTQDocNo" name="oetTQDocNo" maxlength="20" value="<?= $tTQDocNo; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?= language('document/quotation/quotation', 'tTQPlsDocNoDuplicate'); ?>" placeholder="<?= language('document/quotation/quotation', 'tTQDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdTQCheckDuplicateCode" name="ohdTQCheckDuplicateCode" value="2">
                                </div>

                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetTQDocDate" name="oetTQDocDate" value="<?= $dTQDocDate; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtTQDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNTimePicker" id="oetTQDocTime" name="oetTQDocTime" value="<?= $dTQDocTime; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEnterDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtTQDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                                            <label><?= $tTQCreateByName ?></label>
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
                                            if ($tTQRoute == "docQuotationEventEdit") {
                                                $tQTLabelStaDoc  = language('document/quotation/quotation', 'tTQStaDoc' . $tTQStaDoc);
                                            } else {
                                                $tQTLabelStaDoc  = '-';
                                            }
                                            ?>
                                            <label><?= $tQTLabelStaDoc; ?></label>
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
                                            <label><?= language('document/quotation/quotation', 'tTQStaApv' . $tTQStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tTQDocNo) && !empty($tTQDocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdTQApvCode" name="ohdTQApvCode" maxlength="20" value="<?= $tTQApvCode ?>">
                                                <label>
                                                    <?= (isset($tTQUsrNameApv) && !empty($tTQUsrNameApv)) ? $tTQUsrNameApv : "-"; ?>
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

            <!-- Panel ลูกค้า-->
            <?php include('Panel/wPanelCutomer.php'); ?>
            <!-- Panel รถ-->
            <?php include('Panel/wPanelCar.php'); ?>
            <!-- Panel เงื่อนไขการชำระเงิน-->
            <?php include('Panel/wPanelPayment.php'); ?>

            <!-- Panel อ้างอิงเอกสารภายใน และ ภายนอก-->
            <?php include('Panel/wPanelReference.php'); ?>

            <!-- Panel อื่นๆ -->
            <?php include('Panel/wPanelOther.php'); ?>

            <!-- Panel ไฟลแนบ -->
            <?php include('Panel/wPanelFileImport.php'); ?>
        </div>

        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <!-- ตารางสินค้า -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px; position:relative; min-height:200px;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">


                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                            <ul class="nav" role="tablist">

                                                <!-- สินค้า -->
                                                <li class="xWMenu active xCNStaHideShow" style="cursor:pointer;">
                                                    <a role="tab" data-toggle="tab" data-target="#odvQUOContentProduct" aria-expanded="true"><?= language('document/expenserecord/expenserecord', 'ข้อมูลสินค้า') ?></a>
                                                </li>

                                                <!-- อ้างอิง -->
                                                <li class="xWMenu xCNStaHideShow" style="cursor:pointer;">
                                                    <a role="tab" data-toggle="tab" data-target="#odvQUOContentHDRef" aria-expanded="false"><?= language('document/expenserecord/expenserecord', 'เอกสารอ้างอิง') ?></a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content">
                                    <!-- สินค้า -->
                                    <div id="odvQUOContentProduct" class="tab-pane fade active in" style="padding: 0px !important;">
                                        <div class="row p-t-15">
                                            <!-- ลูกค้า -->
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQCustomerName'); ?></label>
                                                    <div class="input-group" style="width:100%;">
                                                        <input type="text" class="input100 xCNHide" id="ohdTQCustomerCode" name="ohdTQCustomerCode" value="<?= @$tTQCusCode; ?>">
                                                        <input class="form-control xWPointerEventNone" type="text" id="oetTQCustomerName" name="oetTQCustomerName" value="<?= @$tTQCusName; ?>" readonly placeholder="<?= language('document/quotation/quotation', 'tTQCustomerName'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="obtTQBrowseCustomer" type="button" class="btn xCNBtnBrowseAddOn">
                                                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--กลุ่มราคาของลูกค้า-->
                                            <input type="hidden" id="ohdTQCustomerPPLCode" name="ohdTQCustomerPPLCode" value="<?=@$tPPLCode?>">

                                            <!--ค้นหา-->
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvDOCSearchPdtHTML()" placeholder="<?php echo language('common/main/main', 'tPlaceholder'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvDOCSearchPdtHTML()">
                                                                <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--ตัวเลือก-->
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right xCNHideWhenCancelOrApprove">
                                                <div class="btn-group xCNDropDrownGroup" style="margin-bottom:10px;">
                                                    <button type="button" class="btn xCNBTNMngTable xWDropdown" data-toggle="dropdown">
                                                        <?= language('common/main/main', 'tCMNOption') ?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li id="oliQTBtnDeleteMulti" class="disabled">
                                                            <a data-toggle="modal" data-target="#odvTQModalDelPdtInDTTempMultiple"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!--ค้นหาจากบาร์โค๊ด-->
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right xCNHideWhenCancelOrApprove">
                                                <div class="form-group">
                                                    <input type="text" class="form-control xCNPdtEditInLine" id="oetTQInsertBarcode" autocomplete="off" name="oetTQInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);" placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า">
                                                </div>
                                            </div>

                                            <!--เพิ่มสินค้า-->
                                            <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 xCNHideWhenCancelOrApprove">
                                                <div style="margin-top:-2px;">
                                                    <button type="button" id="obtTQDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt">+</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!--สินค้า-->
                                        <div class="row p-t-10" id="odvTQDataPdtTableDTTemp"></div>

                                        <!--สรุปบิล-->
                                        <?php include('wQuotationEndOfBill.php'); ?>
                                    </div>

                                    <!-- อ้างอิง -->
                                    <div id="odvQUOContentHDRef" class="tab-pane fade" style="padding: 0px !important;">
                                        <div class="row p-t-15">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                                <div style="margin-top:-2px;">
                                                    <button type="button" id="obtQUTAddDocRef" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                                </div>
                                            </div>
                                            <div id="odvQTTableHDRef"></div>

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

<!-- ===========================================  อ้างอิงเอกสารภายใน (ภายใน หรือ ภายนอก) =========================================== -->
<div id="odvQTModalAddDocRef" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="ofmQTFormAddDocRef" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'อ้างอิงเอกสาร') ?></label>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control xCNHide" id="oetQTRefDocNoOld" name="oetQTRefDocNoOld">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ประเภทการอ้างอิงเอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbQTRefType" name="ocbQTRefType">
                                    <option value="1" selected><?php echo language('common/main/main', 'อ้างอิงภายใน'); ?></option>
                                    <option value="3"><?php echo language('common/main/main', 'อ้างอิงภายนอก'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbQTRefDoc" name="ocbQTRefDoc">
                                    <option value="1" selected><?php echo language('common/main/main', 'ใบรับรถ'); ?></option>
                                    <option value="2"><?php echo language('common/main/main', 'ใบสั่งงาน'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetQTDocRefInt" name="oetQTDocRefInt" maxlength="20" value="">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetQTDocRefIntName" name="oetQTDocRefIntName" maxlength="20" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?>" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtQTBrowseRefDoc" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetQTRefDocNo" name="oetQTRefDocNo" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?>" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'วันที่เอกสารอ้างอิง'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetQTRefDocDate" name="oetQTRefDocDate" placeholder="YYYY-MM-DD" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="obtQTRefDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ค่าอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetQTRefKey" name="oetQTRefKey" placeholder="<?php echo language('common/main/main', 'ค่าอ้างอิง'); ?>" maxlength="10" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="obtQTConfirmAddDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="submit"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================== ยกเลิกเอกสาร  ============================================= -->
<div class="modal fade" id="odvQTPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('document/document/document', 'tDocDocumentCancel') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?= language('document/document/document', 'tDocCancelText1') ?></p>
                <p><strong><?= language('document/document/document', 'tDocCancelText2') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxQTDocumentCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?= language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อนุมัติเอกสาร  ============================================= -->
<div class="modal fade xCNModalApprove" id="odvQTPopupApv">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tApproveTheDocument'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?= language('common/main/main', 'tMainApproveStatus'); ?></p>
                <ul>
                    <li><?= language('common/main/main', 'tMainApproveStatus1'); ?></li>
                    <li><?= language('common/main/main', 'tMainApproveStatus2'); ?></li>
                    <li><?= language('common/main/main', 'tMainApproveStatus3'); ?></li>
                    <li><?= language('common/main/main', 'tMainApproveStatus4'); ?></li>
                </ul>
                <p><?= language('common/main/main', 'tMainApproveStatus5'); ?></p>
                <p><strong><?= language('common/main/main', 'tMainApproveStatus6'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxQTDocumentApv(true)" type="button" class="btn xCNBTNPrimery">
                    <?= language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== ลบสินค้าใน Temp แบบหลายตัว  ============================================= -->
<div id="odvTQModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type="hidden" id="ohdConfirmTQDocNoDelete" name="ohdConfirmTQDocNoDelete">
                <input type="hidden" id="ohdConfirmTQSeqNoDelete" name="ohdConfirmTQSeqNoDelete">
                <input type="hidden" id="ohdConfirmTQPdtCodeDelete" name="ohdConfirmTQPdtCodeDelete">
                <input type="hidden" id="ohdConfirmTQPunCodeDelete" name="ohdConfirmTQPunCodeDelete">
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== กรุณาเลือกลูกค้า ก่อนเลือกสินค้า =========================================== -->
<div id="odvTQModalPleseselectCustomer" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>กรุณาเลือกลูกค้า ก่อนทำรายการ</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== ไม่พบสินค้าหลังจากสแกนบาร์โค๊ด (สแกน)  ============================================= -->
<div id="odvTQModalPDTNotFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>ไม่พบข้อมูลสินค้า กรุณาลองใหม่อีกครั้ง</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxNotFoundClose();">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== พบสินค้ามากกว่าหนึ่งรายการ (สแกน)  ============================================= -->
<div id="odvTQModalPDTMoreOne" class="modal fade">
    <div class="modal-dialog" role="document" style="width: 85%; margin: 1.75rem auto;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;">กรุณาเลือกสินค้า</label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTMoreOne(1)" data-dismiss="modal">เลือก</button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTMoreOne(2)" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <table class="table table-striped xCNTablePDTMoreOne">
                    <thead>
                        <tr>
                            <th class="xCNTextBold" style="text-align:center; width:120px;"><?= language('common/main/main', 'tModalcodePDT') ?></th>
                            <th class="xCNTextBold" style="text-align:center; width:160px;"><?= language('common/main/main', 'tModalnamePDT') ?></th>
                            <th class="xCNTextBold" style="text-align:center; width:120px;"><?= language('common/main/main', 'tModalPriceUnit') ?></th>
                            <th class="xCNTextBold" style="text-align:center; width:160px;"><?= language('common/main/main', 'tModalbarcodePDT') ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อ้างอิงเอกสารภายใน (ใบรับรถ) ============================================= -->
<div id="odvQTModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1000px;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/purchaseorder/purchaseorder', 'อ้างอิงใบรับรถ') ?></label>
            </div>
            <div class="modal-body">
                <div class="row" id="odvQTFromRefIntDoc"></div>
            </div>
            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>



<?php include('script/jQuotationPageAdd.php'); ?>
<?php include('dis_chg/wQuotationDisChgModal.php'); ?>
