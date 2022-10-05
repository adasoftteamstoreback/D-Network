<?php
    $tRoute = $tRoute;
    // Check Data HD
    if($aDataDocHD['rtCode'] == '1'){
        $nStaPage   = 2; //ขาแก้ไข
        $aDataDocHD = $aDataDocHD['raItems'];
        // ====================== Panel รหัสเอกสารและสถานะเอกสาร ======================
        $tDBNDocNo      = $aDataDocHD['FTXshDocNo'];
        $dDBNDocDate    = date("Y-m-d", strtotime($aDataDocHD['FDXshDocDate']));
        $dDBNDocTime    = $aDataDocHD['FTXshDocTime'];
        $tDBNCreateBy   = $aDataDocHD['FTCreateBy'];
        $tDBNUsrNameCreateBy    = $aDataDocHD['FTCreateByName'];
        $tDBNApvCode    = $aDataDocHD['FTXshApvCode'];
        $tDBNApvName    = $aDataDocHD['FTXshApvName'];
        // Status Document
        $tDBNStaRefund  = $aDataDocHD['FTXshStaRefund'];
        $tDBNStaDoc     = $aDataDocHD['FTXshStaDoc'];
        $tDBNStaApv     = $aDataDocHD['FTXshStaApv'];
        $tDBNStaPrcStk  = $aDataDocHD['FTXshStaPrcStk'];
        $tDBNStaPaid    = $aDataDocHD['FTXshStaPaid'];
        $tDBNStaDocAct  = $aDataDocHD['FNXshStaDocAct'];
        $tDBNStaRef     = $aDataDocHD['FNXshStaRef'];
        $tDBNDocPrint   = $aDataDocHD['FNXshDocPrint'];
        $tDBNXshRmk     = $aDataDocHD['FTXshRmk'];
        // ====================== Panel รหัสเอกสารและสถานะเอกสาร ======================
        // ========================== Panel เงื่อนไขการรับเข้า ===========================
        // สาขา
        $tDBNBchCode    = $aDataDocHD['FTBchCode'];
        $tDBNBchName    = $aDataDocHD['FTBchName'];
        // ร้านค้า
        $tDBNShpCode    = $aDataDocHD['FTShpCode'];
        $tDBNShpName    = $aDataDocHD['FTShpName'];
        // คลังสินค้า
        $tDBNWahCode    = $aDataDocHD['FTWahCode'];
        $tDBNWahName    = $aDataDocHD['FTWahName'];
        // เครื่องจุดขาย
        $tDBNPosCode    = $aDataDocHD['FTPosCode'];
        $tDBNPosName    = $aDataDocHD['FTPosName'];
        // ========================== Panel เงื่อนไขการรับเข้า ===========================
        // ============================== Panel ลูกค้า ================================
        $tDBNVATInOrEx  = $aDataDocHD['FTXshVATInOrEx'];
        $tDBNCshOrCrd   = $aDataDocHD['FTXshCshOrCrd'];
        if($aDataDocHdCst['rtCode'] == '1'){
            $tDBNCstCrTerm  = $aDataDocHdCst['raItems']['FNXshCrTerm'];
            $tDBNCstDueDate = $aDataDocHdCst['raItems']['FDXshDueDate'];
            $tDBNCstBillDue = $aDataDocHdCst['raItems']['FDXshBillDue'];
            $tDBNCstCardID  = $aDataDocHdCst['raItems']['FTXshCardID'];
            $tDBNRefTnfID   = $aDataDocHdCst['raItems']['FTXshRefTnfID'];
            $tDBNCtrName    = $aDataDocHdCst['raItems']['FTXshCtrName'];
            $tDBNCstTel     = $aDataDocHdCst['raItems']['FTXshCstTel'];
            $tDBNCstEmail   = $aDataDocHdCst['raItems']['FTXshCstEmail'];
        }
        // ============================== Panel ลูกค้า ================================
        // ============================ Panel ข้อมูลสินค้า ==============================
        //ลูกค้า
        $tDBNCstCode    = $aDataDocHD['FTCstCode'];
        $tDBNCstName    = $aDataDocHD['FTCstName'];
        // ============================ Panel ข้อมูลสินค้า ==============================
        // ============================ Panel ภาษีมูลค่าเพิ่ม ==============================
        $aDataSumVat    = @$aDataDocSumVat['raItems'][0];
        $nDBNXshVat     = @$aDataSumVat['FCXsdVat'];
        // ============================ Panel ภาษีมูลค่าเพิ่ม ==============================
        // ============================ Panel ส่วนลดท้ายบิล ==============================
        $nDBNXshGrand           = $aDataDocHD['FCXshGrand'];
        $nDBNXshTotal           = $aDataDocHD['FCXshTotal'];
        $tDBNXshDisChgTxt       = $aDataDocHD['FTXshDisChgTxt'];
        $nDBNXshDis             = $aDataDocHD['FCXshDis'];
        $nDBNXshTotalAfDisChgV  = $aDataDocHD['FCXshTotalAfDisChgV'];
        // ============================ Panel ส่วนลดท้ายบิล ==============================
    }else {
      $tDBNStaDoc ="";
      $tDBNStaApv ="";
    }

    // Check Text Label Color Status Document
    if ($tDBNStaDoc == 3) {
        $tClassStaDoc = 'text-danger';
        $tStaDoc = language('common/main/main', 'tStaDoc3');
    }else{
        if ($tDBNStaDoc == 1 && $tDBNStaApv == '') {
            $tClassStaDoc = 'text-warning';
            $tStaDoc = language('common/main/main', 'tStaDoc');
        }elseif ($tDBNStaDoc == 1 && $tDBNStaApv == 1) {
            $tClassStaDoc = 'text-success';
            $tStaDoc = language('common/main/main', 'tStaDoc1');
        }else{
            $tClassStaDoc = 'text-warning';
            $tStaDoc = language('common/main/main', 'tStaDoc');
        }
    }
    $bIsApvOrCancel = !empty($tDBNStaApv) || $tDBNStaDoc == 3 ? true : false;
    $nStaUploadFile = 2;
?>
<style type="text/css">
    #odvDBNRowDataEndOfBill .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvDBNRowDataEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvDBNRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{color: #232C3D !important;font-weight: 900;}
    .xCNHideTD {border: none;}
    .xCNViewDetailBtn{cursor: pointer;color: #0081c2;}
</style>
<input type="hidden" id="ohdDBNRoute" name="ohdDBNRoute" value="<?=$tRoute?>">
<input type="hidden" id="ohdDBNCheckClearValidate" name="ohdDBNCheckClearValidate" value="0">
<input type="hidden" id="ohdDBNCheckSubmitByButton" name="ohdDBNCheckSubmitByButton" value="0">
<form id="ofmDBNAddForm" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <button style="display:none" type="submit" id="obtSubmitDBN" onclick="JSoAddEditDBN('<?= $tRoute ?>')"></button>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDBNHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/debitnote/debitnote', 'tDBNDoucment'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDBNDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDBNDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/debitnote/debitnote', 'tDBNLabelFrmAppove');?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style = "color:red">*</span> <?php echo language('document/debitnote/debitnote','tDBNLabelFrmDocNo'); ?></label>
                                <?php if(isset($tDBNDocNo) && empty($tDBNDocNo)):?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbDBNStaAutoGenCode" name="ocbDBNStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote','tDBNLabelFrmAutoGenCode');?></span>
                                        </label>
                                    </div>
                                <?php endif;?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input
                                        type="text"
                                        class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai"
                                        id="oetDBNDocNo"
                                        name="oetDBNDocNo"
                                        maxlength="20"
                                        value="<?php echo $tDBNDocNo;?>"
                                        data-validate-required="<?php echo language('document/debitnote/debitnote','tDBNPlsEnterOrRunDocNo'); ?>"
                                        data-validate-duplicate="<?php echo language('document/debitnote/debitnote','tDBNPlsDocNoDuplicate'); ?>"
                                        placeholder="<?php echo language('document/debitnote/debitnote','tDBNLabelFrmDocNo');?>"
                                        style="pointer-events:none"
                                        readonly
                                    >
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote','tDBNLabelFrmDocDate');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNDatePicker xCNInputMaskDate"
                                            id="oetDBNDocDate"
                                            name="oetDBNDocDate"
                                            value="<?php echo $dDBNDocDate; ?>"
                                            placeholder="<?php echo language('document/debitnote/debitnote', 'tDBNLabelFrmDocDate');?>"
                                            readonly
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtDBNDocDate" type="button" class="btn xCNBtnDateTime" disabled><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelFrmDocTime');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNTimePicker xCNInputMaskTime"
                                            id="oetDBNDocTime"
                                            name="oetDBNDocTime"
                                            value="<?php echo $dDBNDocTime; ?>"
                                            placeholder="<?php echo language('document/debitnote/debitnote', 'tDBNLabelFrmDocTime');?>"
                                            readonly
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtDBNDocTime" type="button" class="btn xCNBtnDateTime" disabled><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote','tDBNLabelFrmCreateBy');?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdDBNCreateBy" name="ohdDBNCreateBy" value="<?php echo $tDBNCreateBy?>">
                                            <label><?php echo $tDBNUsrNameCreateBy?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                                if($tRoute == "docDBNEventAdd"){
                                                    $tDBNLabelStaDoc    = language('document/debitnote/debitnote', 'tDBNLabelFrmValStaDoc');
                                                }else{
                                                    $tDBNLabelStaDoc    = language('document/debitnote/debitnote', 'tDBNLabelFrmValStaDoc'.$tDBNStaDoc);
                                                }
                                            ?>
                                            <label><?php echo $tDBNLabelStaDoc;?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelFrmStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                        <label class="<?php echo $tClassStaDoc;?>">
                                            <?php echo $tStaDoc;?>
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- ผู้อนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0" id="odvDBNApvBy">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelFrmApvBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdDBNApvCode" name="ohdDBNApvCode" maxlength="20" value="<?php echo $tDBNApvCode?>">
                                            <label>
                                                <?php echo (isset($tDBNUsrNameApv) && !empty($tDBNUsrNameApv))? $tDBNUsrNameApv : "-" ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel ลูกค้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDBNCustomer" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote','tDBNLabelCustomer');?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDBNDataCustomer" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDBNDataCustomer" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <!-- ประเภทภาษี -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelVATInOrEx'); ?></label>
                                    <select class="selectpicker form-control" id="ocmDBNVATInOrEx" name="ocmDBNVATInOrEx" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <option value="1" <?php echo @$tDBNVATInOrEx == '1' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocVatIn'); ?></option>
                                        <option value="2" <?php echo @$tDBNVATInOrEx == '2' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocVatEx'); ?></option>
                                    </select>
                                </div>
                                <!-- ประเภทชำระเงิน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelPaymentType'); ?></label>
                                    <select class="selectpicker form-control" id="ocmDBNCshOrCrd" name="ocmDBNCshOrCrd" maxlength="1" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                        <option value="1" <?php echo @$tDBNCshOrCrd == '1' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocCash'); ?></option>
                                        <option value="2" <?php echo @$tDBNCshOrCrd == '2' ? 'selected' : ''; ?>><?php echo language('document/document/document', 'tDocCredit'); ?></option>
                                    </select>
                                </div>
                                <!-- ระยะเครดิต -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelCreditTerm'); ?></label>
                                    <input
                                        class="form-control text-right"
                                        type="text"
                                        id="oetDBNCstCrTerm"
                                        name="oetDBNCstCrTerm"
                                        value="<?php echo @$tDBNCstCrTerm; ?>"
                                        <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>
                                    >
                                </div>
                                <!-- วันครบกำหนดการชำระเงิน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelPayDueDate'); ?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNDatePicker xCNInputMaskDate"
                                            id="oetDBNCstDueDate"
                                            name="oetDBNCstDueDate"
                                            value="<?php echo @$tDBNCstDueDate; ?>"
                                            data-validate-required="<?php echo language('document/debitnote/debitnote', 'tDBNLabelPayDueDate'); ?>"
                                            <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>
                                        >
                                        <span class="input-group-btn">
                                            <button type="button" class="btn xCNBtnDateTime" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                                <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- วันวางบิล -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelBillDue'); ?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNDatePicker xCNInputMaskDate"
                                            id="oetDBNCstBillDue"
                                            name="oetDBNCstBillDue"
                                            value="<?php echo @$tDBNCstBillDue; ?>"
                                            data-validate-required="<?php echo language('document/debitnote/debitnote', 'tDBNLabelBillDue'); ?>"
                                            <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>
                                        >
                                        <span class="input-group-btn">
                                            <button type="button" class="btn xCNBtnDateTime" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                                <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เลขที่ขนส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelRefTnfID'); ?></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="oetDBNRefTnfID"
                                        name="oetDBNRefTnfID"
                                        value="<?php echo @$tDBNRefTnfID;?>"
                                        placeholder="<?php echo language('document/debitnote/debitnote', 'tDBNLabelRefTnfID'); ?>"
                                        <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>
                                    >
                                </div>
                                <!-- ชื่อผู้ติดต่อ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelCtrName'); ?></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="oetDBNCtrName"
                                        name="oetDBNCtrName"
                                        value="<?php echo @$tDBNCtrName;?>"
                                        placeholder="<?php echo language('document/debitnote/debitnote', 'tDBNLabelCtrName'); ?>"
                                        <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>
                                    >
                                </div>
                                <!-- เบอร์โทรติดต่อ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelCstTel'); ?></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="oetDBNCstTel"
                                        name="oetDBNCstTel"
                                        value="<?php echo @$tDBNCstTel;?>"
                                        placeholder="<?php echo language('document/debitnote/debitnote', 'tDBNLabelCstTel'); ?>"
                                        <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>
                                    >
                                </div>
                                <!-- อีเมล์ติดต่อ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelCstEmail'); ?></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="oetDBNCstEmail"
                                        name="oetDBNCstEmail"
                                        value="<?php echo @$tDBNCstEmail;?>"
                                        placeholder="<?php echo language('document/debitnote/debitnote', 'tDBNLabelCstEmail'); ?>"
                                        <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel เงื่อนไขการรับเข้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDBNCondition" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/debitnote/debitnote','tDBNLabelCondition');?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDBNDataCondition" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDBNDataCondition" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <?php
                                    if($tRoute == "docDBNEventAdd"){
                                        $tUserBchCode       = $this->session->userdata('tSesUsrBchCodeDefault');
                                        $tUserBchName       = $this->session->userdata('tSesUsrBchNameDefault');
                                        $tDisabledBch       = '';
                                    }else{
                                        $tUserBchCode       = $tDBNBchCode;
                                        $tUserBchName       = $tDBNBchName;
                                        $tDisabledBch       = 'disabled';
                                    }
                                ?>
                                <script type="text/javascript">
                                    var tUsrLevel   = '<?=$this->session->userdata('tSesUsrLevel')?>';
                                    if( tUsrLevel != "HQ" ){
                                        var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount")?>';
                                        if(tBchCount < 2){
                                            $('#obtDBNBrowseBch').attr('disabled',true);
                                        }
                                    }
                                </script>
                                <!-- สาขา -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelBranch'); ?></label>
                                    <div class="input-group">
                                        <input type="hidden" id="ohdDBNBchCode" name="ohdDBNBchCode" value="<?=$tUserBchCode; ?>">
                                        <input class="form-control xCNHide" id="oetDBNBchCode" name="oetDBNBchCode" maxlength="5" value="<?=$tUserBchCode; ?>">
                                        <input
                                            class="form-control xWPointerEventNone"
                                            type="text"
                                            id="oetDBNBchName"
                                            name="oetDBNBchName"
                                            value="<?php echo $tUserBchName; ?>"
                                            readonly>
                                        <span class="input-group-btn">
                                            <button id="obtDBNBrowseBch" type="button" class="btn xCNBtnBrowseAddOn" <?=$tDisabledBch; ?> >
                                                <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- คลัง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/creditnote/creditnote', 'tDBNLabelWarehouse'); ?></label>
                                    <div class="input-group">
                                        <input type="hidden" id="ohdDBNWahCode" name="ohdDBNWahCode" value="<?php echo @$tDBNWahCode; ?>">
                                        <input type="hidden" id="ohdDBNWahName" name="ohdDBNWahName" value="<?php echo @$tDBNWahName; ?>">
                                        <input type="text" class="input100 xCNHide" id="oetDBNWahCode" name="oetDBNWahCode" maxlength="5" value="<?php echo @$tDBNWahCode; ?>">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetDBNWahName" name="oetDBNWahName" value="<?php echo @$tDBNWahName; ?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtDBNBrowseWah" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                                <img src="<?php echo base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel อื่นๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDBNHeadAnother" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/debitnote/debitnote', 'tDBNLabelOther'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDBNOtherPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDBNOtherPanel" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="hidden" class="form-control" id="ohdDBNStaDocAct" name="ohdDBNStaDocAct" value="<?=$tDBNStaDocAct;?>">
                                        <input
                                            type="checkbox"
                                            id="ocbDBNStaDocAct"
                                            name="ocbDBNStaDocAct"
                                            maxlength="1" <?php echo ($tDBNStaDocAct == '1') ? 'checked' : ''; ?>
                                        >
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelStaDocAct'); ?></span>
                                    </label>
                                    <script type="text/javascript">
                                        $('#ocbDBNStaDocAct').unbind().click(function(){
                                            let tStaDocAct  = "";
                                            if ($(this).prop('checked')) {
                                                tStaDocAct  = 1;
                                            }else{
                                                tStaDocAct  = 0;
                                            }
                                            $('#ohdDBNStaDocAct').val(tStaDocAct);
                                        });
                                    </script>
                                </div>
                                <!-- สถานะความเคลื่อนไหว -->

                                <!-- สถานะอ้างอิง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote', 'tDBNLabelStaRef'); ?></label>
                                    <select class="selectpicker form-control" id="ocmDBNXphStaRef" name="ocmDBNXphStaRef" maxlength="1" disabled>
                                        <option value="0" <?php echo $tDBNStaRef == '0' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocNeverReference'); ?></option>
                                        <option value="1" <?php echo $tDBNStaRef == '1' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocSomeReference'); ?></option>
                                        <option value="2" <?php echo $tDBNStaRef == '2' ? 'selected' : '' ?>><?php echo language('document/document/document', 'tDocAllReference'); ?></option>
                                    </select>
                                </div>
                                <!-- สถานะอ้างอิง -->

                                <!-- จำนวนครั้งที่พิมพ์ -->
                                <div class="form-group">
                                    <label class="xCNTextDetail1"><?php echo language('document/debitnote/debitnote', 'tDBNCountLabelDocPrint'); ?></label>
                                    <input
                                        class="form-control text-right"
                                        type="text"
                                        id="ocmDBNXphDocPrint"
                                        name="ocmDBNXphDocPrint"
                                        value="<?php echo $tDBNDocPrint; ?>"
                                        readonly="true"
                                        <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                </div>
                                <!-- จำนวนครั้งที่พิมพ์ -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel แนบไฟล์เอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDBNReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/debitnote/debitnote', 'tDBNLabelReferenceDoc'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDBNDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDBNDataFile" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvDBNShowDataTable">
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">


                    var oDBNCallDataTableFile = {
                        ptElementID     : 'odvDBNShowDataTable',
                        ptBchCode       : $('#ohdDBNBchCode').val(),
                        ptDocNo         : $('#oetDBNDocNo').val(),
                        ptDocKey        : 'TPSTTaxHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : '<?= $nStaUploadFile ?>',
                        ptCallBackFunct : '',
                        ptStaApv        : '<?= $tDBNStaApv ?>',
                        ptStaDoc        : '<?= $tDBNStaDoc ?>'
                    };
                    JCNxUPFCallDataTable(oDBNCallDataTableFile);
                    $('.xFileInputPositionTPSTTaxHDodvDBNShowDataTable').hide();
                </script>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <!-- ข้อมูลรายการสินค้า -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom: 25px;position: relative;min-height: 200px;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body xCNPDModlue">
                                <div class="row" style="margin-top: 10px;">
                                    <!-- เลือกลูกค้า -->
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <label class="xCNLabelFrm"><?php echo language('document/debitnote/debitnote','tDBNLabelCst');?></label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="input100 xCNHide" id="oetDBNCstCode" name="oetDBNCstCode" maxlength="5" value="<?php echo $tDBNCstCode; ?>">
                                                <input
                                                    class="form-control xWPointerEventNone"
                                                    type="text"
                                                    id="oetDBNCstName"
                                                    name="oetDBNCstName"
                                                    value="<?php echo $tDBNCstName; ?>"
                                                    placeholder="<?php echo language('document/debitnote/debitnote', 'tDBNLabelCst'); ?>"
                                                    readonly
                                                >
                                                <span class="input-group-btn xWConditionSearchPdt">
                                                    <button id="obtDBNBrowseCst" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt" <?php echo $bIsApvOrCancel ? 'disabled' : '' ?>>
                                                        <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ค้นหาสินค้า -->
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                                                    <div class="input-group">
                                                        <input
                                                            class="form-control xCNInpuDBNthoutSingleQuote"
                                                            type="text"
                                                            id="oetDBNSearchPDT"
                                                            name="oetDBNSearchPDT"
                                                            placeholder="<?=language('document/debitnote/debitnote','tDBNLabelSearchPdt')?>"
                                                            autocomplete="off"
                                                            onkeyup="JSvDBNDOCSearchPdtHTML()"
                                                        >
                                                        <span class="input-group-btn">
                                                            <button id="obtDBNSerchAllDocument" type="button" class="btn xCNBtnDateTime" onclick="JSvDBNDOCSearchPdtHTML()">
                                                                <img class="xCNIconSearch">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ตารางรายการสินค้า -->
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <?php
                                                    $nOptDecimalShow    = FCNxHGetOptionDecimalShow();
                                                ?>
                                                <div class="table-responsive">
                                                    <table class="table table-striped" id="otbDBNPdtTable">
                                                        <thead>
                                                            <tr class="xCNCenter">
                                                                <th class="xCNTextBold" style="width:5%;"><?=language('document/debitnote/debitnote','tDBNNum')?></th>
                                                                <!-- <th class="xCNTextBold"><?=language('document/debitnote/debitnote','tDBNPDTCode')?></th> -->
                                                                <th class="xCNTextBold"><?=language('document/debitnote/debitnote','tDBNPDTName')?></th>
                                                                <!-- <th class="xCNTextBold"><?=language('document/debitnote/debitnote','tDBNPDTUnit')?></th> -->
                                                                <th class="xCNTextBold"><?=language('document/debitnote/debitnote','tDBNPDTQty')?></th>
                                                                <th class="xCNTextBold"><?=language('document/debitnote/debitnote','tDBNPDTDiscount')?></th>
                                                                <th class="xCNTextBold"><?=language('document/debitnote/debitnote','tDBNPDTTotal')?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if($aDataDocDT['rtCode'] == '1'): ?>
                                                                <?php foreach($aDataDocDT['raItems'] AS $aValueDT): ?>
                                                                    <tr class="text-center xCNTextDetail2">
                                                                        <td class="text-left"><label><?=$aValueDT['FNXsdSeqNo']?></label></td>
                                                                        <!-- <td class="text-left"><label><?=$aValueDT['FTPdtCode']?></label></td> -->
                                                                        <td class="text-left"><label><?=$aValueDT['FTXsdPdtName']?></label></td>
                                                                        <!-- <td class="text-center"><label><?=(!empty($aValueDT['FTPunName'])? $aValueDT['FTPunName'] : '-');?></label></td> -->
                                                                        <td class="text-right"><label><?=number_format($aValueDT['FCXsdQty'],$nOptDecimalShow)?></label></td>
                                                                        <td class="text-right"><label><?=number_format($aValueDT['FCXsdDis'] + $aValueDT['FCXsdChg'],$nOptDecimalShow)?></label></td>
                                                                        <td class="text-right"><label><?=number_format($aValueDT['FCXsdNet'],$nOptDecimalShow)?></label></td>
                                                                    </tr>
                                                                <?php endforeach;?>
                                                            <?php else :?>
                                                                <tr class="xCNTextDetail2">
                                                                    <td class="text-center" colspan="100%"><label><?=language('common/main/main','tCMNNotFoundData')?></label></td>
                                                                </tr>
                                                            <?php endif;?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ข้อมูลท้ายบิล -->
                                <div class="row p-t-10" id="odvDBNRowDataEndOfBill" >
                                    <!-- ข้อมูลภาษี -->
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="pull-left mark-font"><?= language('document/debitnote/debitnote', 'tDBNVatAndRmk'); ?></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div style='padding: 10px 10px 0px 10px;'>
                                                <!-- หมายเหตุ -->
                                                <div class="form-group">
                                                    <textarea class="form-control" id="otaDBNXshRmk" name="otaDBNXshRmk" maxlength="200"><?php echo $tDBNXshRmk?></textarea>
                                                </div>
                                            </div>
                                            <div class="panel-heading">
                                                <div class="pull-left mark-font"><?= language('document/debitnote/debitnote','tDBNVatRate');?></div>
                                                <div class="pull-right mark-font"><?= language('document/debitnote/debitnote','tDBNAmountVat');?></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body">
                                                <ul class="list-group" id="oulDBNDataListVat">
                                                    <?php foreach ($aDataDocVat['raItems'] as $key => $tVal): ?>
                                                        <label class="pull-left"><?=number_format($tVal['FCXsdVatRate'],0)?>%</label>
                                                        <label class="pull-right" id="olbDBNSumFCXtdNet"><?=number_format($tVal['FCXsdVat'],2)?></label><br>
                                                        <div class="clearfix"></div>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <div class="panel-heading">
                                                <label class="pull-left mark-font"><?= language('document/debitnote/debitnote','tDBNTotalValVat');?></label>
                                                <label class="pull-right mark-font" id="olbDBNVatSum"><?=number_format($nDBNXshVat,2);?></label>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ข้อมูลส่วนลดท้ายบิล -->
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading mark-font" id="odvDBNDataTextBath"></div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <label class="pull-left mark-font"><?= language('document/debitnote/debitnote','tDBNSumFCXtdNet');?></label>
                                                        <label class="pull-right mark-font" id="olbDBNSumFCXtdNet"><?=number_format(@$nDBNXshTotal,2)?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label class="pull-left"><?= language('document/debitnote/debitnote','tDBNDisChg');?></label>
                                                        <label class="pull-left" style="margin-left: 5px;" id="olbDBNDisChgHD"><?=@$tDBNXshDisChgTxt?></label>
                                                        <label class="pull-right" id="olbDBNSumFCXtdAmt"><?=number_format(@$nDBNXshDis, 2)?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label class="pull-left"><?= language('document/debitnote/debitnote','tDBNSumFCXtdNetAfHD');?></label>
                                                        <label class="pull-right" id="olbDBNSumFCXtdNetAfHD"><?=number_format(@$nDBNXshTotalAfDisChgV, 2)?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label class="pull-left"><?= language('document/debitnote/debitnote','tDBNSumFCXtdVat');?></label>
                                                        <label class="pull-right" id="olbDBNSumFCXtdVat"><?=number_format(@$nDBNXshVat, 2)?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="panel-heading">
                                                <label class="pull-left mark-font"><?= language('document/debitnote/debitnote','tDBNFCXphGrand');?></label>
                                                <input type="hidden" id="ohdDBNCalFCXphGrand" value="<?php echo @$nDBNXshGrand;?>">
                                                <label class="pull-right mark-font" id="olbDBNCalFCXphGrand"><?php echo number_format(@$nDBNXshGrand, 2);?></label>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ข้อมูลรายการอ้างอิงเอกสาร -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div style="margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="table-responsive" style="max-height: 260px;">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr class="xCNCenter">
                                                            <th class="xCNTextBold" style="width:15%;"><?=language('document/debitnote/debitnote','tDBNTitleRefType')?></th>
                                                            <th class="xCNTextBold"><?=language('document/debitnote/debitnote','tDBNTitleRefDocNo')?></th>
                                                            <th class="xCNTextBold" style="width:15%;"><?=language('document/debitnote/debitnote','tDBNTitleRefDocDate')?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if($aDataDocHDRef['rtCode'] == '1'): ?>
                                                            <?php foreach($aDataDocHDRef['raItems'] as $aDocRef): ?>
                                                                <tr class="xCNTextDetail2">
                                                                    <td class="text-left"><label><?=language('document/debitnote/debitnote','tDBNRefType'.$aDocRef['FTXshRefType'])?></label></td>
                                                                    <td class="text-left"><label><?=$aDocRef['FTXshRefDocNo']?></label></td>
                                                                    <td class="text-center"><label><?=date_format(date_create($aDocRef['FDXshRefDocDate']),'Y-m-d')?></label></td>
                                                                </tr>
                                                            <?php endforeach;?>
                                                        <?php else: ?>
                                                            <tr class="xCNTextDetail2">
                                                                <td class="text-center" colspan="100%"><label><?=language('common/main/main','tCMNNotFoundData')?></label></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
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
<script src="<?php echo base_url('application/modules/common/assets/src/jThaiBath.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jDebitNotePageForm.php');?>
<!-- ควบคุม Checkbox -->
<script type="text/javascript">
    $("document").ready(function(){
        var tTextTotal  = $('#olbDBNCalFCXphGrand').text().replace(/,/g, '');
        var tThaibath 	= ArabicNumberToText(tTextTotal);
        $('#odvDBNDataTextBath').text(tThaibath);
    });
</script>

<!-- madal insert success -->
<div id="odvDBNModalAddSuccess" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'แจ้งเตือน')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"><?php echo language('common/main/main', 'บันทึกการประเมินสำเร็จ')?></span>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>
