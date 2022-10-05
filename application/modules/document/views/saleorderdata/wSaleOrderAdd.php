<?php
$tSesUsrLevel       = $this->session->userdata('tSesUsrLevel');
$nAddressVersion    = FCNaHAddressFormat('TCNMCst');
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == '1') {
    $aDataDocHD             = @$aDataDocHD['raItems'];
    $aDataDocHDSpl          = @$aDataDocHDSpl['raItems'];
    $aDataDocHDCstAddr      = @$aDataDocHDCstAddr['raItems'];
    $tSORoute               = "dcmSOEventEdit";
    $nSOAutStaEdit          = 1;
    $tSODocNo               = $aDataDocHD['FTXshDocNo'];
    $dSODocDate             = date("Y-m-d", strtotime($aDataDocHD['FDXshDocDate']));
    $dSODocTime             = date("H:i", strtotime($aDataDocHD['FDXshDocDate']));
    $tSOCreateBy            = $aDataDocHD['FTCreateBy'];
    $tSOUsrNameCreateBy     = $aDataDocHD['FTUsrName'];
    $tSOStaRefund           = $aDataDocHD['FTXshStaRefund'];
    $tSOStaDoc              = $aDataDocHD['FTXshStaDoc'];
    $tSOStaApv              = $aDataDocHD['FTXshStaApv'];
    $tSOStaPrcStk           = '';
    $tSOStaDelMQ            = '';
    $tSOStaPaid             = $aDataDocHD['FTXshStaPaid'];
    $tSOSesUsrBchCode       = $this->session->userdata("tSesUsrBchCode");
    $tSODptCode             = $aDataDocHD['FTDptCode'];
    $tSOUsrCode             = $this->session->userdata('tSesUsername');
    $tSOLangEdit            = $this->session->userdata("tLangEdit");
    $tSOApvCode             = $aDataDocHD['FTXshApvCode'];
    $tSOUsrNameApv          = $aDataDocHD['FTXshApvName'];
    $tSORefPoDoc            = "";
    $nSOStaRef              = $aDataDocHD['FNXshStaRef'];
    $tSOBchCode             = $aDataDocHD['FTBchCode'];
    $tSOBchName             = $aDataDocHD['FTBchName'];
    $tSOUserBchCode         = $tUserBchCode;
    $tSOUserBchName         = $tUserBchName;
    $tSOBchCompCode         = $tBchCompCode;
    $tSOBchCompName         = $tBchCompName;
    $tSOMerCode             = $aDataDocHD['FTMerCode'];
    $tSOMerName             = $aDataDocHD['FTMerName'];
    $tSOShopType            = $aDataDocHD['FTShpType'];
    $tSOShopCode            = $aDataDocHD['FTShpCode'];
    $tSOShopName            = $aDataDocHD['FTShpName'];
    $tSOPosCode             = $aDataDocHD['FTPosCode'];
    $tSOPosName             = $aDataDocHD['FTPosComName'];
    $tSOWahCode             = $aDataDocHD['FTWahCode'];
    $tSOWahName             = $aDataDocHD['FTWahName'];
    $nSOStaDocAct           = $aDataDocHD['FNXshStaDocAct'];
    $tSOFrmDocPrint         = $aDataDocHD['FNXshDocPrint'];
    $tSOFrmRmk              = $aDataDocHD['FTXshRmk'];
    $tSOSplCode             = '';
    $tSOSplName             = '';
    $tSOCmpRteCode          = $aDataDocHD['FTRteCode'];
    $cSORteFac              = $aDataDocHD['FCXshRteFac'];
    $tSOVatInOrEx           = $aDataDocHD['FTXshVATInOrEx'];
    $tSOSplPayMentType      = $aDataDocHD['FTXshCshOrCrd'];

    // ข้อมูลผู้จำหน่าย Supplier
    $tSOSplDstPaid          = $aDataDocHDSpl['FTXshDstPaid'];
    $tSOSplCrTerm           = $aDataDocHDSpl['FNXshCrTerm'];
    $dSOSplDueDate          = $aDataDocHDSpl['FDXshDueDate'];
    $dSOSplBillDue          = $aDataDocHDSpl['FDXshBillDue'];
    $tSOSplCtrName          = $aDataDocHDSpl['FTXshCtrName'];
    $dSOSplTnfDate          = $aDataDocHDSpl['FDXshTnfDate'];
    $tSOSplRefTnfID         = $aDataDocHDSpl['FTXshRefTnfID'];
    $tSOSplRefVehID         = $aDataDocHDSpl['FTXshRefVehID'];
    $tSOSplRefInvNo         = $aDataDocHDSpl['FTXshRefInvNo'];
    $tSOSplQtyAndTypeUnit   = $aDataDocHDSpl['FTXshQtyAndTypeUnit'];
    $tSOCstCode             = $aDataDocHD['FTCstCode'];
    $tSOCstCardID           = $aDataDocHD['FTXshCardID'];
    $tSOCstName             = $aDataDocHD['FTXshCstName'];
    $tSOCstTel              = $aDataDocHD['FTXshCstTel'];
    $tSOCstPplCode          = $aDataDocHD['FTPplCodeRet'];
    $tSOCrTerm              = $aDataDocHD['FNXshCrTerm'];
    $tSODueDate             = date("Y-m-d", strtotime($aDataDocHD['FDXshDueDate']));
    if($nAddressVersion == '2'){
        $tSOCstAddr            = $aDataDocHD['FTAddV2Desc1'];
    }elseif($nAddressVersion == '1'){
        $tSOCstAddr            = $aDataDocHD['FTAddV1Desc'];
    }

    //car
    $tSOCarCode              = $aDataDocHD['FTCarCode'];
    $tSOCarRegNo             = $aDataDocHD['FTCarRegNo'];
    $tSOCarBrand             = $aDataDocHD['FTCarBrand'] . ' ';
    $tSOCarModel             = $aDataDocHD['FTCarModel'];
    $tSOSpnName              = $aDataDocHD['rtSpnName'];

    //ที่อยู่จัดส่ง
    $tSHIP_FNAddSeqNo        = @$aDataDocHDCstAddr['FNAddSeqNo'];
    $tSHIP_FTAddV1No         = @$aDataDocHDCstAddr['FTAddV1No'];
    $tSHIP_FTAddV1Soi        = @$aDataDocHDCstAddr['FTAddV1Soi'];
    $tSHIP_FTAddV1Village    = @$aDataDocHDCstAddr['FTAddV1Village'];
    $tSHIP_FTAddV1Road       = @$aDataDocHDCstAddr['FTAddV1Road'];
    $tSHIP_FTSudName         = @$aDataDocHDCstAddr['FTSudName'];
    $tSHIP_FTDstName         = @$aDataDocHDCstAddr['FTDstName'];
    $tSHIP_FTPvnName         = @$aDataDocHDCstAddr['FTPvnName'];
    $tSHIP_FTAddV1PostCode   = @$aDataDocHDCstAddr['FTAddV1PostCode'];
    $tSHIP_FTAddTel          = @$aDataDocHDCstAddr['FTAddTel'];
    $tSHIP_FTAddFax          = @$aDataDocHDCstAddr['FTAddFax'];
    $tFTXshCstRef            = @$aDataDocHDCstAddr['FTXshCstRef'];
    $tSOStaAlwPosCalSo       = $aDataDocHD['FTXshStaAlwPosCalSo'];
    $nStaUploadFile          = 2;

    //สถานะประมวลผล
    $nStaPrcDoc             = $aDataDocHD['FTXshStaPrcDoc'];
} else {
    $tSORoute               = "dcmSOEventAdd";
    $nSOAutStaEdit          = 0;
    $tSODocNo               = "";
    $dSODocDate             = "";
    $dSODocTime             = date('H:i:s');
    $tSOCreateBy            = $this->session->userdata('tSesUsrUsername');
    $tSOUsrNameCreateBy     = $this->session->userdata('tSesUsrUsername');
    $nSOStaRef              = 0;
    $tSOStaRefund           = 1;
    $tSOStaDoc              = 1;
    $tSOStaApv              = NULL;
    $tSOStaPrcStk           = NULL;
    $tSOStaDelMQ            = NULL;
    $tSOStaPaid             = 1;
    $tSOSesUsrBchCode       = $this->session->userdata("tSesUsrBchCode");
    $tSODptCode             = $tDptCode;
    $tSOUsrCode             = $this->session->userdata('tSesUsername');
    $tSOLangEdit            = $this->session->userdata("tLangEdit");
    $tSOApvCode             = "";
    $tSOUsrNameApv          = "";
    $tSORefPoDoc            = "";
    $tSOSpnName             = "";
    $tSOBchCode             = $tBchCode;
    $tSOBchName             = $tBchName;
    $tSOUserBchCode         = $tBchCode;
    $tSOUserBchName         = $tBchName;
    $tSOBchCompCode         = $tBchCompCode;
    $tSOBchCompName         = $tBchCompName;
    $tSOMerCode             = $tMerCode;
    $tSOMerName             = $tMerName;
    $tSOShopType            = $tShopType;
    $tSOShopCode            = $tShopCode;
    $tSOShopName            = $tShopName;
    $tSOPosCode             = "";
    $tSOPosName             = "";
    $tSOWahCode             = "";
    $tSOWahName             = "";
    $nSOStaDocAct           = "";
    $tSOFrmDocPrint         = 0;
    $tSOFrmRmk              = "";
    $tSOSplCode             = "";
    $tSOSplName             = "";
    $tSOCmpRteCode          = $tCmpRteCode;
    $cSORteFac              = $cXthRteFac;
    $tSOVatInOrEx           = $tCmpRetInOrEx;
    $tSOSplPayMentType      = "";

    // ข้อมูลผู้จำหน่าย Supplier
    $tSOSplDstPaid          = "";
    $tSOSplCrTerm           = "";
    $dSOSplDueDate          = "";
    $dSOSplBillDue          = "";
    $tSOSplCtrName          = "";
    $dSOSplTnfDate          = "";
    $tSOSplRefTnfID         = "";
    $tSOSplRefVehID         = "";
    $tSOSplRefInvNo         = "";
    $tSOSplQtyAndTypeUnit   = "";
    $tSOCstCode             = '';
    $tSOCstCardID           = '';
    $tSOCstName             = '';
    $tSOCstTel              = '';
    $tSOCstPplCode          = '';
    $tSOCrTerm              = '';
    $tSODueDate             = '';
    $tSOCstAddr             = '';
    $tFTXshCstRef           = '';

    //car
    $tSOCarCode             = '';
    $tSOCarRegNo            = '';
    $tSOStaAlwPosCalSo      = "1";
    $nStaUploadFile         = 1;

    //สถานะประมวลผล
    $nStaPrcDoc             = null;
}
if (empty($tSOBchCode) && empty($tSOShopCode)) {
    $tASTUserType   = "HQ";
} else {
    if (!empty($tSOBchCode) && empty($tSOShopCode)) {
        $tASTUserType   = "BCH";
    } else if (!empty($tSOBchCode) && !empty($tSOShopCode)) {
        $tASTUserType   = "SHP";
    } else {
        $tASTUserType   = "";
    }
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

<form id="ofmSOFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <input type="hidden" id="ohdSOPage" name="ohdSOPage" value="1">
    <input type="hidden" id="ohdSORoute" name="ohdSORoute" value="<?php echo $tSORoute; ?>">
    <input type="hidden" id="ohdSOCheckClearValidate" name="ohdSOCheckClearValidate" value="0">
    <input type="hidden" id="ohdSOCheckSubmitByButton" name="ohdSOCheckSubmitByButton" value="0">
    <input type="hidden" id="ohdSOAutStaEdit" name="ohdSOAutStaEdit" value="<?php echo $nSOAutStaEdit; ?>">
    <input type="hidden" id="ohdSOPplCodeBch" name="ohdSOPplCodeBch" value="<?php echo $tSOPplCode ?>">
    <input type="hidden" id="ohdSOPplCodeCst" name="ohdSOPplCodeCst" value="<?= $tSOCstPplCode ?>">
    <input type="hidden" id="ohdSOStaRefund" name="ohdSOStaRefund" value="<?php echo $tSOStaRefund; ?>">
    <input type="hidden" id="ohdSOStaDoc" name="ohdSOStaDoc" value="<?php echo $tSOStaDoc; ?>">
    <input type="hidden" id="ohdSOStaApv" name="ohdSOStaApv" value="<?php echo $tSOStaApv; ?>">
    <input type="hidden" id="ohdSOStaDelMQ" name="ohdSOStaDelMQ" value="<?php echo $tSOStaDelMQ; ?>">
    <input type="hidden" id="ohdSOStaPrcStk" name="ohdSOStaPrcStk" value="<?php echo $tSOStaPrcStk; ?>">
    <input type="hidden" id="ohdSOStaPaid" name="ohdSOStaPaid" value="<?php echo $tSOStaPaid; ?>">
    <input type="hidden" id="ohdSOSesUsrBchCode" name="ohdSOSesUsrBchCode" value="<?php echo $tSOSesUsrBchCode; ?>">
    <input type="hidden" id="ohdSOBchCode" name="ohdSOBchCode" value="<?php echo $tSOBchCode; ?>">
    <input type="hidden" id="ohdSOBchName" name="ohdSOBchName" value="<?php echo $tSOBchName; ?>">
    <input type="hidden" id="ohdSODptCode" name="ohdSODptCode" value="<?php echo $tSODptCode; ?>">
    <input type="hidden" id="ohdSOUsrCode" name="ohdSOUsrCode" value="<?php echo $tSOUsrCode ?>">
    <input type="hidden" id="ohdSOPosCode" name="ohdSOPosCode" value="">
    <input type="hidden" id="ohdSOShfCode" name="ohdSOShfCode" value="">
    <input type="hidden" id="ohdSOCmpRteCode" name="ohdSOCmpRteCode" value="<?php echo $tSOCmpRteCode; ?>">
    <input type="hidden" id="ohdSORteFac" name="ohdSORteFac" value="<?php echo $cSORteFac; ?>">
    <input type="hidden" id="ohdSOApvCodeUsrLogin" name="ohdSOApvCodeUsrLogin" value="<?php echo $tSOUsrCode; ?>">
    <input type="hidden" id="ohdSOLangEdit" name="ohdSOLangEdit" value="<?php echo $tSOLangEdit; ?>">
    <input type="hidden" id="ohdSOOptAlwSaveQty" name="ohdSOOptAlwSaveQty" value="<?php echo $nOptDocSave ?>">
    <input type="hidden" id="ohdSOOptScanSku" name="ohdSOOptScanSku" value="<?php echo $nOptScanSku ?>">
    <input type="hidden" id="ohdSOVatRate" name="ohdSOVatRate" value="<?= $cVatRate ?>">
    <input type="hidden" id="ohdSOCmpRetInOrEx" name="ohdSOCmpRetInOrEx" value="<?= $tCmpRetInOrEx ?>">
    <input type="hidden" id="ohdSesSessionID" name="ohdSesSessionID" value="<?= $this->session->userdata('tSesSessionID') ?>">
    <input type="hidden" id="ohdSesUsrLevel" name="ohdSesUsrLevel" value="<?= $this->session->userdata('tSesUsrLevel') ?>">
    <input type="hidden" id="ohdSesUsrBchCom" name="ohdSesUsrBchCom" value="<?= $this->session->userdata('tSesUsrBchCom') ?>">
    <input type="hidden" id="ohdSOValidatePdt" name="ohdSOValidatePdt" value="<?= language('document/saleorder/saleorder', 'tSOPleaseSeletedPDTIntoTable') ?>">
    <input type="hidden" id="ohdSOValidateCarCode" name="ohdSOValidateCarCode" value="<?= language('document/saleorder/saleorder', 'tSOPleaseSeletedCarIntoTable') ?>">
    <input type="hidden" id="ohdSOStaPrcDoc" name="ohdSOStaPrcDoc" value="<?= $nStaPrcDoc ?>">
    <input type="hidden" id="ohdSODocRefJump" name="ohdSODocRefJump" value="">
    <input type="hidden" id="ohdSODocRefBchJump" name="ohdSODocRefBchJump" value="">

    <!-- รหัสสาขาที่อ้างอิงมาจากใบสั่งขาย -->
    <input type="hidden" id="oetSOHDXshCstRef" name="oetSOHDXshCstRef" value='<?=$tFTXshCstRef?>'>

    <button style="display:none" type="submit" id="obtSOSubmitDocument" onclick="JSxSOAddEditDocument()"></button>
    <div class="row">
        <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar">
            <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSOHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmStatus'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvSODataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvSODataStatusInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmAppove'); ?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/saleorder/saleorder', 'tSOLabelAutoGenCode'); ?></label>
                                <?php if (isset($tSODocNo) && empty($tSODocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbSOStaAutoGenCode" name="ocbSOStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xCNGenarateCodeTextInputValidate" id="oetSODocNo" name="oetSODocNo" maxlength="20" value="<?php echo $tSODocNo; ?>" data-validate-required="<?php echo language('document/saleorder/saleorder', 'tSOPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/saleorder/saleorder', 'tSOPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/saleorder/saleorder', 'tSOLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdSOCheckDuplicateCode" name="ohdSOCheckDuplicateCode" value="2">
                                </div>

                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate xCNDateTimePicker" id="oetSODocDate" name="oetSODocDate" value="<?php echo $dSODocDate; ?>" data-validate-required="<?php echo language('document/saleorder/saleorder', 'tSOPlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSODocDate" type="button" class="btn xCNBtnDateTime xWControlBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNTimePicker xCNInputMaskTime xCNDateTimePicker" id="oetSODocTime" name="oetSODocTime" value="<?php echo $dSODocTime; ?>" data-validate-required="<?php echo language('document/saleorder/saleorder', 'tSOPlsEnterDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSODocTime" type="button" class="btn xCNBtnDateTime xWControlBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdSOCreateBy" name="ohdSOCreateBy" value="<?php echo $tSOCreateBy ?>">
                                            <label><?php echo $tSOUsrNameCreateBy ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                            if ($tSORoute == "dcmSOEventAdd") {
                                                $tSOLabelStaDoc  = language('document/saleorder/saleorder', 'tSOLabelFrmValStaDoc');
                                            } else {
                                                $tSOLabelStaDoc  = language('document/saleorder/saleorder', 'tSOLabelFrmValStaDoc' . $tSOStaDoc);
                                            }
                                            ?>
                                            <label><?php echo $tSOLabelStaDoc; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmStaApv'); ?></label>
                                        </div>
                                        <?php
                                        if ($tSOStaDoc == 3) {
                                            $tClassStaDoc = 'text-danger';
                                            $tStaDoc = language('common/main/main', 'tStaDoc3');
                                        } else {
                                            if ($tSOStaDoc == 1 && $tSOStaApv == '') {
                                                $tClassStaDoc = 'text-warning';
                                                $tStaDoc = language('common/main/main', 'tStaDoc');
                                            } elseif ($tSOStaDoc == 1 && $tSOStaApv == 1) {
                                                $tClassStaDoc = 'text-success';
                                                $tStaDoc = language('common/main/main', 'tStaDoc1');
                                            } else {
                                                $tClassStaDoc = 'text-warning';
                                                $tStaDoc = language('common/main/main', 'tStaDoc');
                                            }
                                        }
                                        ?>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label class="<?= $tClassStaDoc ?>"><?php echo $tStaDoc; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะอ้างอิงเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmStaRef'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">

                                            <label><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmStaRef' . $nSOStaRef); ?></label>

                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tSODocNo) && !empty($tSODocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdSOApvCode" name="ohdSOApvCode" maxlength="20" value="<?php echo $tSOApvCode ?>">
                                                <label>
                                                    <?php echo (isset($tSOUsrNameApv) && !empty($tSOUsrNameApv)) ? $tSOUsrNameApv : "-" ?>
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



            <!-- Panel เงื่อนไขเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSOConditionDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmConditionDoc'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSODataConditionDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSODataConditionDoc" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <!-- Condition สาขา -->
                                <div class="form-group m-b-0">
                                    <?php
                                    $tSODataInputBchCode   = "";
                                    $tSODataInputBchName   = "";
                                    if ($tSORoute  == "dcmSOEventAdd") {
                                        if ($this->session->userdata('tSesUsrLevel') == "HQ") {
                                            $tSODataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                                            $tSODataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                                        } else {
                                            $tSODataInputBchCode    = $tSOBchCode;
                                            $tSODataInputBchName    = $tSOBchName;
                                        }
                                    } else {
                                        $tSODataInputBchCode    = $tSOBchCode;
                                        $tSODataInputBchName    = $tSOBchName;
                                    }
                                    ?>

                                    <script>
                                        var tUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
                                        if (tUsrLevel != "HQ") {
                                            var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                                            if(tBchCount < 2){
                                                $('#obtBrowseSOBCH').attr('disabled', true);
                                            }
                                        }
                                    </script>

                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmBranch') ?></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetSOFrmBchCode" name="oetSOFrmBchCode" maxlength="5" value="<?php echo @$tSODataInputBchCode ?>">
                                            <input type="text" class="form-control xWPointerEventNone" id="oetSOFrmBchName" name="oetSOFrmBchName" maxlength="100" placeholder="<?php echo language('document/purchaseinvoice/purchaseinvoice', 'tPILabelFrmBranch') ?>" value="<?php echo @$tSODataInputBchName ?>" readonly>
                                            <span class="input-group-btn xWConditionSearchPdt">
                                                <button id="obtBrowseSOBCH" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <!-- Condition คลังสินค้า -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span> <?php echo language('document/saleorder/saleorder', 'tSOLabelFrmWah'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNHide" id="oetSOFrmWahCode" name="oetSOFrmWahCode" maxlength="5" value="<?php echo $tSOWahCode; ?>">
                                        <input type="text" class="form-control xWPointerEventNone" id="oetSOFrmWahName" name="oetSOFrmWahName" value="<?php echo $tSOWahName; ?>" data-validate-required="<?php echo language('document/saleorder/saleorder', 'tSOPlsEnterWah'); ?>" readonly>
                                        <?php
                                        $tDisabledBtnWah    = "";
                                        if ($tSORoute == "dcmSOEventAdd") {
                                            if ($tSesUsrLevel == "SHP") {
                                                $tDisabledBtnWah    = "disabled";
                                            }
                                        } else {
                                            if ($tSesUsrLevel == "SHP") {
                                                $tDisabledBtnWah    = "disabled";
                                            } else {
                                                if (!empty($tSOMerCode) && !empty($tSOShopCode) && !empty($tSOWahCode)) {
                                                    $tDisabledBtnWah    = "disabled";
                                                }
                                            }
                                        }
                                        ?>
                                        <span class="xWConditionSearchPdt input-group-btn <?php echo $tDisabledBtnWah; ?>">
                                            <button id="obtSOBrowseWahouse" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn <?php echo $tDisabledBtnWah; ?>">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Panel Customer Info -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSOCustomerInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('customer/customer/customer', 'tCSTTitle'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSODataCustomerInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSODataCustomerInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <input type="hidden" id="ocmSOFrmSplInfoVatInOrEx" name="ocmSOFrmSplInfoVatInOrEx" value="<?= $tSOVatInOrEx ?>">
                            <div class="col-xs-12 col-sm-12 col-col-md-12 col-lg-12">

                                <!-- HN Number -->
                                <div class="form-group xCNHide">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmCstHNNumber'); ?></label>
                                    <input type="text" class="form-control" id="oetSOFrmCstHNNumber" name="oetSOFrmCstHNNumber" value="<?php echo $tSOCstCode; ?>" lavudate-label="<?= language('document/saleorder/saleorder', 'tSOCstHNNumber') ?>" readonly>
                                </div>

                                <!-- ID card code -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmCstCtzID'); ?></label>
                                    <input type="text" class="form-control" id="oetSOFrmCstCtzID" name="oetSOFrmCstCtzID" value="<?php echo $tSOCstCardID; ?>" placeholder="<?php echo language('document/saleorder/saleorder', 'tSOLabelFrmCstCtzID'); ?>" readonly>
                                </div>

                                <!-- Cst Name -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmCstName'); ?></label>
                                    <input type="text" class="form-control" id="oetSOFrmCustomerName" name="oetSOFrmCustomerName" value="<?php echo $tSOCstName; ?>" placeholder="<?php echo language('document/saleorder/saleorder', 'tSOLabelFrmCstName'); ?>" readonly>
                                </div>

                                <!-- ที่อยู่ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQAddress'); ?></label>
                                    <textarea name="otaSOCustomerAddress" id="otaSOCustomerAddress" cols="30" rows="4" readonly><?= $tSOCstAddr ?></textarea>
                                </div>

                                <!-- Cst tel -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmCsttel'); ?></label>
                                    <input type="text" class="form-control" id="oetSOFrmCstTel" name="oetSOFrmCstTel" value="<?php echo $tSOCstTel; ?>" placeholder="<?php echo language('document/saleorder/saleorder', 'tSOLabelFrmCsttel'); ?>" readonly>
                                </div>

                                <!-- ประเภทการชำระ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseinvoice/purchaseinvoice', 'tPILabelFrmSplInfoPaymentType'); ?></label>
                                    <select class="selectpicker form-control" id="ocmSOFrmSplInfoPaymentType" name="ocmSOFrmSplInfoPaymentType" maxlength="1">
                                        <option value="1" <?php if ($tSOSplPayMentType == 1) {
                                                                echo 'selected';
                                                            } ?>><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmSplInfoPaymentType1'); ?></option>
                                        <option value="2" <?php if ($tSOSplPayMentType == 2) {
                                                                echo 'selected';
                                                            } ?>><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmSplInfoPaymentType2'); ?></option>
                                    </select>
                                </div>

                                <!-- ระยะเครดิต -->
                                <div class="form-group xCNPanel_CreditTerm" style="display:none;">
                                    <label class="xCNLabelFrm"><?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?></label>
                                    <input style="text-align: right;" maxlength="5" autocomplete="off" value="<?= @$tSOCrTerm ?>" type="text" class="form-control xCNInputNumericWithDecimal xCNControllForm" id="oetSOCreditTerm" name="oetSOCreditTerm" placeholder="<?= language('document/creditnote/creditnote', 'tCreditNoteCreditTerm'); ?>">
                                </div>

                                <!-- วันที่มีผล -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?= language('document/quotation/quotation', 'tTQEffectiveDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate xCNDateTimePicker" id="oetSOEffectiveDate" name="oetSOEffectiveDate" placeholder="YYYY-MM-DD" value="<?= @$tSODueDate; ?>" data-validate-required="<?= language('document/quotation/quotation', 'tTQPlsEffectiveDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtTQEffectiveDate" type="button" class="btn xCNBtnDateTime xWControlBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <hr>
                                <!-- Car -->
                                <div class="form-group" style="display:none;">
                                    <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARTitle'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNHide" id="oetSOFrmCarCode" name="oetSOFrmCarCode" value="<?= @$tSOCarCode ?>">
                                        <input type="text" class="form-control" id="oetSOFrmCarName" name="oetSOFrmCarName" value="<?= @$tSOCarBrand ?><?= @$tSOCarModel ?>" placeholder="<?php echo language('service/car/car', 'tCARTitle'); ?>" data-validate-required="<?php echo language('document/saleorder/saleorder', 'tSOPleaseSeletedCarIntoTable'); ?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtSOBrowseCarCode" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group" style="display:none;">
                                    <label class="xCNLabelFrm"><?php echo language('service/car/car', 'tCARRegNo'); ?></label>
                                    <input type="text" class="form-control" id="oetSOFrmCarRegNo" name="oetSOFrmCarRegNo" value="<?= @$tSOCarRegNo ?>" placeholder="<?php echo language('service/car/car', 'tCARRegNo'); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <div class="">

                                        <?php
                                            //ถ้าอนุมัติแล้ว หรือยกเลิก จะกดปุ่ม checkbox ไม่ได้
                                            if ($tSOStaDoc == 3 || $tSOStaApv == 1) {
                                                $tClassStyleBlock = 'pointer-events: none;';
                                            }else{
                                                $tClassStyleBlock = '';
                                            }
                                        ?>
                                        <label class="fancy-checkbox" style="<?=$tClassStyleBlock?>">
                                            <?php
                                                $tSOStaAlwPosCalSoCheck;
                                                !empty($tSOStaAlwPosCalSo == "1") ? $tSOStaAlwPosCalSoCheck = "checked" : $tSOStaAlwPosCalSoCheck = "";
                                            ?>
                                            <input type="checkbox" name="ocbSOStaAlwPosCalSo" id="ocbSOStaAlwPosCalSo" <?php echo $tSOStaAlwPosCalSoCheck; ?> value="1">
                                            <span> <?php echo language('customer/customer/customer', 'tCstStaAlwPosCalSo'); ?></span>
                                        </label>
                                    </div>
                                </div>
                                <!-- ที่อยู่สำหรับจัดส่ง -->
                                <div class="row xCNSOFrmBrowseAddrAdd">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"></div>
                                            <!-- เก็บรายละเอียดที่อยู่ไว้แสดง -->
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOAddrCode" value="<?= @$tSHIP_FNAddSeqNo; ?>">
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOAddrName" value="<?= @$tSHIP_FTAddV1No; ?>">
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOAddrNoHouse" value="<?= @$tSHIP_FTAddV1Soi; ?>">
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOAddrVillage" value="<?= @$tSHIP_FTAddV1Village; ?>">
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOAddrRoad" value="<?= @$tSHIP_FTAddV1Road; ?>">
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOAddrSubDistrict" value="<?= @$tSHIP_FTSudName; ?>">
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOAddrDistict" value="<?= @$tSHIP_FTDstName; ?>">
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOAddrProvince" value="<?= @$tSHIP_FTPvnName; ?>">
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOZipCode" value="<?= @$tSHIP_FTAddV1PostCode; ?>">
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOAddrTel" value="<?= @$tSHIP_FTAddTel; ?>">
                                            <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOAddrFax" value="<?= @$tSHIP_FTAddFax; ?>">

                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <input type="hidden" class="xWClearDataWhenChangeCst" id="ohdSOFrmShipAdd" name="ohdSOFrmShipAdd" value="<?= @$tSHIP_FNAddSeqNo ?>">
                                                <button type="button" id="obtSOFrmBrowseAddrAdd" class="btn xCNBTNSubSave" style="width:100%;" data-codebrowse="1">
                                                    <?= language('document/purchaseinvoice/purchaseinvoice', 'tPILabelFrmSplInfoShipAddress'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Panel ข้อมูลอ้างอิง -->
            
            <!-- Panel อืนๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSOInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmInfoOth'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSODataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSODataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" value="1" id="ocbSOFrmInfoOthStaDocAct" name="ocbSOFrmInfoOthStaDocAct" maxlength="1" <?php echo ($nSOStaDocAct == '1' || empty($nSOStaDocAct)) ? 'checked' : ''; ?>>
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>
                                <!-- สถานะอ้างอิง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthRef'); ?></label>
                                    <select class="selectpicker form-control xCNControllForm" id="ocmSOFrmInfoOthRef" name="ocmSOFrmInfoOthRef" maxlength="1">
                                        <option value="0" selected><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthRef0'); ?></option>
                                        <option value="1"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthRef1'); ?></option>
                                        <option value="2"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthRef2'); ?></option>
                                    </select>
                                </div>
                                <!-- จำนวนครั้งที่พิมพ์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthDocPrint'); ?></label>
                                    <input type="text" class="form-control xCNControllForm text-right" id="ocmSOFrmInfoOthDocPrint" name="ocmSOFrmInfoOthDocPrint" value="<?php echo $tSOFrmDocPrint; ?>" readonly>
                                </div>
                                <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthReAddPdt'); ?></label>
                                    <select class="form-control xCNControllForm selectpicker" id="ocmSOFrmInfoOthReAddPdt" name="ocmSOFrmInfoOthReAddPdt">
                                        <option value="1" selected><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthReAddPdt1'); ?></option>
                                        <option value="2"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthReAddPdt2'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Panel ไฟลแนบ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSOReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'ไฟล์แนบ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSODataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSODataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvSOShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>


                    var oSOCallDataTableFile = {
                        ptElementID     : 'odvSOShowDataTable',
                        ptBchCode       : $('#oetSOFrmBchCode').val(),
                        ptDocNo         : $('#oetSODocNo').val(),
                        ptDocKey        : 'TARTSoHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdSOStaApv').val(),
                        ptStaDoc        : $('#ohdSOStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oSOCallDataTableFile);
                </script>
            </div>
        </div>
        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9"> <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <div id="odvSODataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <!-- Tab -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                            <ul class="nav" role="tablist">

                                                <!-- สินค้า -->
                                                <li class="xWMenu active xCNStaHideShow" style="cursor:pointer;" id="oliSOContentProduct">
                                                    <a role="tab" data-toggle="tab" data-target="#odvSOContentProduct" aria-expanded="true"><?= language('document/expenserecord/expenserecord', 'ข้อมูลสินค้า') ?></a>
                                                </li>

                                                <!-- อ้างอิง -->
                                                <li class="xWMenu xCNStaHideShow" style="cursor:pointer;" id="oliSOContentHDRef">
                                                    <a role="tab" data-toggle="tab" data-target="#odvSOContentHDRef" aria-expanded="false"><?= language('document/expenserecord/expenserecord', 'เอกสารอ้างอิง') ?></a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <!-- ตารางรายการสินค้า -->
                                    <div id="odvSOContentProduct" class="tab-pane fade active in" style="padding: 0px !important;">
                                        <div class="row p-t-10">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><span style="color:red;">*</span><?php echo language('document/saleorder/saleorder', 'tSOCstCode') ?></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xCNControllForm xCNHide" id="oetSOFrmCstCode" name="oetSOFrmCstCode" value="<?php echo $tSOCstCode; ?>">
                                                        <input type="text" class="form-control" id="oetSOFrmCstName" name="oetSOFrmCstName" value="<?php echo $tSOCstName; ?>" placeholder="<?php echo language('document/saleorder/saleorder', 'tSOCstCode') ?>" readonly>
                                                        <span class="input-group-btn">
                                                            <button id="obtSOBrowseCustomer" type="button" class="btn xCNBtnBrowseAddOn">
                                                                <img class="xCNIconFind">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-t-10">

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSOFrmFilterPdtHTML" name="oetSOFrmFilterPdtHTML" onkeyup="JSvSOCSearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="obtSOMngPdtIconSearch" class="btn xCNBtnDateTime" type="button" onclick="JSvSOCSearchPdtHTML()">
                                                                <img class="xCNIconSearch" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                            </button>
                                                        </span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right">

                                                <div class="row">
                                                    <!--ตัวเลือก-->
                                                    <div id="odvSOMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup">
                                                        <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                                                            <?php echo language('common/main/main', 'tCMNOption') ?>
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li id="oliSOBtnDeleteMulti" class="disabled">
                                                                <a data-toggle="modal" data-target="#odvSOModalDelPdtInDTTempMultiple"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <!--ค้นหาจากบาร์โค๊ด-->
                                                <div class="form-group" style="width: 85%;">
                                                    <input type="text" class="form-control xCNControllForm" id="oetSOInsertBarcode" autocomplete="off" name="oetSOInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);" placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า">
                                                </div>

                                                <!--เพิ่มสินค้าแบบปกติ-->
                                                <div class="form-group">
                                                    <div style="position: absolute;right: 15px;top:-5px;">
                                                        <button type="button" id="obtSODocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt">+</button>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row p-t-10" id="odvSODataPdtTableDTTemp"></div>

                                        <div class="row" id="odvRowDataEndOfBill">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <div class="pull-left mark-font"><?= language('document/depositdoc/depositdoc', 'tDPSVatAndRmk'); ?></div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div style='padding: 10px 10px 0px 10px;'>
                                                        <!-- หมายเหตุ -->
                                                        <div class="form-group">
                                                            <textarea class="form-control" id="otaSOFrmInfoOthRmk" name="otaSOFrmInfoOthRmk" maxlength="200"><?php echo $tSOFrmRmk ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="panel-heading">
                                                        <div class="pull-left mark-font"><?= language('document/saleorder/saleorder', 'tSOTBVatRate'); ?></div>
                                                        <div class="pull-right mark-font"><?= language('document/saleorder/saleorder', 'tSOTBAmountVat'); ?></div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <ul class="list-group" id="oulDataListVat">
                                                        </ul>
                                                    </div>
                                                    <div class="panel-heading">
                                                        <label class="pull-left mark-font"><?= language('document/saleorder/saleorder', 'tSOTBTotalValVat'); ?></label>
                                                        <label class="pull-right mark-font" id="olbVatSum">0.00</label>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- End Of Bill -->
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading mark-font" id="odvDataTextBath"></div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-body">
                                                        <ul class="list-group">
                                                            <li class="list-group-item">
                                                                <label class="pull-left mark-font"><?= language('document/saleorder/saleorder', 'tSOTBSumFCXtdNet'); ?></label>
                                                                <input type="text" id="olbSumFCXtdNetAlwDis" style="display:none;"></label>
                                                                <label class="pull-right mark-font" id="olbSumFCXtdNet">0.00</label>
                                                                <div class="clearfix"></div>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <label class="pull-left"><?= language('document/saleorder/saleorder', 'tSOTBDisChg'); ?>
                                                                    <button type="button" class="xCNBTNPrimeryDisChgPlus" onclick="JCNvSOMngDocDisChagHD(this)" style="float: right; margin-top: 3px; margin-left: 5px; background-color: #1866ae !important;">+</button>
                                                                </label>
                                                                <label class="pull-left" style="margin-left: 5px;" id="olbDisChgHD"></label>
                                                                <label class="pull-right" id="olbSumFCXtdAmt">0.00</label>
                                                                <input type="hidden" id="ohdSODisChgHD" />
                                                                <div class="clearfix"></div>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <label class="pull-left"><?= language('document/saleorder/saleorder', 'tSOTBSumFCXtdNetAfHD'); ?></label>
                                                                <label class="pull-right" id="olbSumFCXtdNetAfHD">0.00</label>
                                                                <div class="clearfix"></div>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <label class="pull-left"><?= language('document/saleorder/saleorder', 'tSOTBSumFCXtdVat'); ?></label>
                                                                <label class="pull-right" id="olbSumFCXtdVat">0.00</label>
                                                                <input type="hidden" name="ohdSumFCXtdVat" id="ohdSumFCXtdVat" value="0.00">
                                                                <div class="clearfix"></div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="panel-heading">
                                                        <label class="pull-left mark-font"><?= language('document/saleorder/saleorder', 'tSOTBFCXphGrand'); ?></label>
                                                        <label class="pull-right mark-font" id="olbCalFCXphGrand">0.00</label>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- อ้างอิง -->
                                    <div id="odvSOContentHDRef" class="tab-pane fade" style="padding: 0px !important;">
                                        <div class="row p-t-15">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div style="margin-top:-2px;">
                                                    <button type="button" id="obtSOAddDocRef" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                                </div>
                                            </div>
                                            <div id="odvSOTableHDRef"></div>
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

<!-- ===========================================  อ้างอิงเอกสารภายใน (ภายใน หรือ ภายนอก) =========================================== -->
<div id="odvSOModalAddDocRef" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="ofmSOFormAddDocRef" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'อ้างอิงเอกสาร') ?></label>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control xCNHide" id="oetSORefDocNoOld" name="oetSORefDocNoOld">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ประเภทการอ้างอิงเอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbSORefType" name="ocbSORefType">
                                    <option value="1" selected><?php echo language('common/main/main', 'อ้างอิงภายใน'); ?></option>
                                    <option value="3"><?php echo language('common/main/main', 'อ้างอิงภายนอก'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbSORefDoc" name="ocbSORefDoc">
                                    <option value="1" selected><?php echo language('common/main/main', 'ใบเสนอราคา'); ?></option>
                                    <option value="2">ใบสั่งซื้อ</option>
                                </select>
                            </div>
                        </div>
                        <!-- อ้างอิงภายใน -->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetSORefIntDoc" name="oetSORefIntDoc" maxlength="20" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?>" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtSOBrowseRefDocInt" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetSORefDocNo" name="oetSORefDocNo" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?>" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'วันที่เอกสารอ้างอิง'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetSORefDocDate" name="oetSORefDocDate" placeholder="YYYY-MM-DD" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="obtSORefDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ค่าอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetSORefKey" name="oetSORefKey" placeholder="<?php echo language('common/main/main', 'ค่าอ้างอิง'); ?>" maxlength="10" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="obtSOConfirmAddDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="submit"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal Shipping Purchase Invoice  =================================================================== -->
<div id="odvSOModalAddress" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard">กำหนดที่อยู่</label>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-lg-12">
                        <!--ที่อยู่-->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPAddrName'); ?></label>
                            <div class="input-group" style="width:100%;">
                                <input type="text" class="input100 xCNHide" id="ohdSOAddrCode" name="oetSOAddrCode" value="">
                                <input class="form-control xWPointerEventNone xWClearDataWhenChangeCst" type="text" id="oetSOAddrName" name="oetSOAddrName" value="" readonly placeholder="<?= language('company/company/company', 'tCMPAddrName'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtSOBrowseAddr" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!--บ้านเลขที่-->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPAddressNo'); ?></label>
                            <input class="form-control xWPointerEventNone xWClearDataWhenChangeCst" type="text" id="oetSOAddrNoHouse" name="oetSOAddrNoHouse" value="" readonly placeholder="<?= language('company/company/company', 'tCMPAddressNo'); ?>">
                        </div>

                        <!--หมู่บ้าน / อาคาร-->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPVillage'); ?></label>
                            <input class="form-control xWPointerEventNone xWClearDataWhenChangeCst" type="text" id="oetSOAddrVillage" name="oetSOAddrVillage" value="" readonly placeholder="<?= language('company/company/company', 'tCMPVillage'); ?>">
                        </div>

                        <!--ถนน-->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPRoad'); ?></label>
                            <input class="form-control xWPointerEventNone xWClearDataWhenChangeCst" type="text" id="oetSOAddrRoad" name="oetSOAddrRoad" value="" readonly placeholder="<?= language('company/company/company', 'tCMPRoad'); ?>">
                        </div>
                    </div>

                    <!--แขวง / ตำบล-->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPSubDistrict'); ?></label>
                            <input class="form-control xWPointerEventNone xWClearDataWhenChangeCst" type="text" id="oetSOAddrSubDistrict" name="oetSOAddrSubDistrict" value="" readonly placeholder="<?= language('company/company/company', 'tCMPSubDistrict'); ?>">
                        </div>
                    </div>

                    <!--เขต / อำเภอ-->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPDistict'); ?></label>
                            <input class="form-control xWPointerEventNone xWClearDataWhenChangeCst" type="text" id="oetSOAddrDistict" name="oetSOAddrDistict" value="" readonly placeholder="<?= language('company/company/company', 'tCMPDistict'); ?>">
                        </div>
                    </div>

                    <!--จังหวัด-->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPProvince'); ?></label>
                            <input class="form-control xWPointerEventNone xWClearDataWhenChangeCst" type="text" id="oetSOAddrProvince" name="oetSOAddrProvince" value="" readonly placeholder="<?= language('company/company/company', 'tCMPProvince'); ?>">
                        </div>
                    </div>

                    <!--รหัสไปรณีย์-->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPZipCode'); ?></label>
                            <input class="form-control xWPointerEventNone xWClearDataWhenChangeCst" type="text" id="oetSOZipCode" name="oetSOZipCode" value="" readonly placeholder="<?= language('company/company/company', 'tCMPZipCode'); ?>">
                        </div>
                    </div>

                    <!--เบอร์โทร-->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPTel'); ?></label>
                            <input class="form-control xWPointerEventNone xWClearDataWhenChangeCst" type="text" id="oetSOAddrTel" name="oetSOAddrTel" value="" readonly placeholder="<?= language('company/company/company', 'tCMPTel'); ?>">
                        </div>
                    </div>

                    <!--เบอร์สาร-->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPFax'); ?></label>
                            <input class="form-control xWPointerEventNone xWClearDataWhenChangeCst" type="text" id="oetSOAddrFax" name="oetSOAddrFax" value="" readonly placeholder="<?= language('company/company/company', 'tCMPFax'); ?>">
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmAddress" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" onclick="JSxConfirmAddress();" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
<div id="odvSOModalAppoveDoc" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'tApproveTheDocument'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('common/main/main', 'tMainApproveStatus'); ?></p>
                <ul>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus1'); ?></li>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus2'); ?></li>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus3'); ?></li>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus4'); ?></li>
                </ul>
                <p><?php echo language('common/main/main', 'tMainApproveStatus5'); ?></p>
                <p><strong><?php echo language('common/main/main', 'tMainApproveStatus6'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxSOApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== สร้างเอกสารใบจัดเรียบร้อย ======================================================================== -->
<div id="odvSOModalCreatePCK" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tStaDocComplete'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>เอกสารใบสั่งขาย ได้ถูกสร้างเอกสารใบจัดสินค้าเรียบร้อย</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal Cancel Document  ======================================================================== -->
<div class="modal fade" id="odvPurchaseInviocePopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard">ยกเลิกเอกสาร</label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv">เอกสารใบนี้ทำการประมวลผล หรือยกเลิกแล้ว ไม่สามารถแก้ไขได้</p>
                <p><strong>คุณต้องการที่จะยกเลิกเอกสารนี้หรือไม่?</strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnSOCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal Advance Table Product DT Temp ==================================================================-->
<div class="modal fade" id="odvSOOrderAdvTblColumns" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('common/main/main', 'tModalAdvTable'); ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body" id="odvSOModalBodyAdvTable">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                <button id="obtSOSaveAdvTableColums" type="button" class="btn btn-primary"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal Delete Product In DT DocTemp Multiple  ============================================================ -->
<div id="odvSOModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type="hidden" id="ohdConfirmSODocNoDelete" name="ohdConfirmSODocNoDelete">
                <input type="hidden" id="ohdConfirmSOSeqNoDelete" name="ohdConfirmSOSeqNoDelete">
                <input type="hidden" id="ohdConfirmSOPdtCodeDelete" name="ohdConfirmSOPdtCodeDelete">
                <input type="hidden" id="ohdConfirmSOPunCodeDelete" name="ohdConfirmSOPunCodeDelete">

            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal ไม่พบลูกค้า   ======================================================================== -->
<div id="odvSOModalPleseselectCustomer" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block">แจ้งเตือน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="odvSOPlsChooseCst">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxFocusInputCustomer();">
                    ตกลง
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvSOModalPDTNotFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block">แจ้งเตือน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>ไม่พบข้อมูลสินค้า กรุณาลองใหม่อีกครั้ง</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxNotFoundClose();">
                    ตกลง
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== พบสินค้ามากกว่าหนึ่งตัว ======================================================================== -->
<div id="odvSOModalPDTMoreOne" class="modal fade">
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
                            <th class="xCNTextBold" style="text-align:center; width:120px;">รหัสสินค้า</th>
                            <th class="xCNTextBold" style="text-align:center; width:160px;">ชื่อสินค้า</th>
                            <th class="xCNTextBold" style="text-align:center; width:120px;">หน่วยสินค้า</th>
                            <th class="xCNTextBold" style="text-align:center; width:160px;">บาร์โค๊ต</th>
                            <th class="xCNTextBold" style="text-align:center; width:120px;">ขายปลีก</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvSOModalChangeBCH" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block">แจ้งเตือน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="odvWarningTxt"></div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" id="obtChangeBCH" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                <button type="button" data-dismiss="modal" class="btn xCNBTNDefult"><?php echo language('common/main/main', 'ยกเลิก'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal เอกสารอ้างอิง ======================================================================== -->
<div id="odvSOModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard" id="ospTextRefIntDoc"><?= language('document/saleorder/saleorder', 'tSOTitlePanelRefQT') ?></label>
            </div>
            <div class="modal-body">
                <div class="row" id="odvSOFromRefIntDoc"></div>
            </div>
            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('application/modules/common/assets/src/jThaiBath.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jSaleOrderAdd.php'); ?>
<?php include('dis_chg/wSaleOrderDisChgModal.php'); ?>

<script>

    //บังคับให้เลือกลูกค้า
    function JSxFocusInputCustomer() {
        $('#oetSOFrmCstName').focus();
    }

    function JSxNotFoundClose() {
        $('#oetSOInsertBarcode').focus();
    }

    //กดเลือกบาร์โค๊ด
    function JSxSearchFromBarcode(e, elem) {
        var tValue = $(elem).val();
        if ($('#oetSOFrmCstHNNumber').val() != "") {
            JSxCheckPinMenuClose();
            if (tValue.length === 0) {

            } else {
                // JCNxOpenLoading();
                $('#oetSOInsertBarcode').attr('readonly', true);
                JCNSearchBarcodePdt(tValue);
                $('#oetSOInsertBarcode').val('');
            }
        } else {
            $('#odvSOModalPleseselectCustomer #odvSOPlsChooseCst').html('<p><?php echo language('document/saleorder/saleorder', 'tSoWarningBeforeSelectPdt'); ?></p>');
            $('#odvSOModalPleseselectCustomer').modal('show');
            $('#oetSOInsertBarcode').val('');
        }
        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
    function JCNSearchBarcodePdt(ptTextScan) {
        var tSOSplCode = $('#oetSOFrmSplCode').val();
        if ($('#ohdSOPplCodeCst').val() != '') {
            var tSOPplCode = $('#ohdSOPplCodeCst').val();
        } else {
            var tSOPplCode = $('#ohdSOPplCodeBch').val();
        }

        var aMulti = [];
        $.ajax({
            type: "POST",
            url: "BrowseDataPDTTableCallView",
            data: {
                aPriceType: ['Price4Cst', tSOPplCode],
                NextFunc: "",
                SPL: $("#oetSOFrmSplCode").val(),
                BCH: $("#oetSOFrmBchCode").val(),
                PDTMoveon: 1,
                tInpSesSessionID: $('#ohdSesSessionID').val(),
                tInpUsrCode: $('#ohdSOUsrCode').val(),
                tInpLangEdit: $('#ohdSOLangEdit').val(),
                tInpSesUsrLevel: $('#ohdSesUsrLevel').val(),
                tInpSesUsrBchCom: $('#ohdSesUsrBchCom').val(),
                tTextScan: ptTextScan
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                // $('#oetSOInsertBarcode').attr('readonly',false);
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                if (oText == '800') {
                    $('#oetSOInsertBarcode').attr('readonly', false);
                    $('#odvSOModalPDTNotFound').modal('show');
                    $('#oetSOInsertBarcode').val('');
                } else {
                    if (oText.length > 1) {

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvSOModalPDTMoreOne').modal('show');
                        $('#odvSOModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
                        for (i = 0; i < oText.length; i++) {
                            var aNewReturn = JSON.stringify(oText[i]);
                            var tTest = "[" + aNewReturn + "]";
                            var oEncodePackData = window.btoa(unescape(encodeURIComponent(tTest)));
                            var tHTML = "<tr class='xCNColumnPDTMoreOne" + i + " xCNColumnPDTMoreOne' data-information='" + oEncodePackData + "' style='cursor: pointer;'>";
                            tHTML += "<td>" + oText[i].pnPdtCode + "</td>";
                            tHTML += "<td>" + oText[i].packData.PDTName + "</td>";
                            tHTML += "<td>" + oText[i].packData.PUNName + "</td>";
                            tHTML += "<td>" + oText[i].ptBarCode + "</td>";
                            tHTML += "<td class='xCNTextRight' style='text-align: right;'>" + oText[i].packData.PriceRet + "</td>";
                            tHTML += "</tr>";
                            $('#odvSOModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick', function(e) {
                            $('#odvSOModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            FSvSOAddPdtIntoDocDTTemp(tJSON); //Client
                            FSvSOAddBarcodeIntoDocDTTemp(tJSON);
                        });

                        //คลิกได้เลย
                        $('.xCNColumnPDTMoreOne').on('click', function(e) {
                            //เลือกสินค้าแบบหลายตัว
                            // var tCheck = $(this).hasClass('xCNActivePDT');
                            // if($(this).hasClass('xCNActivePDT')){
                            //     //เอาออก
                            //     $(this).removeClass('xCNActivePDT');
                            //     $(this).children().attr('style', 'background-color:transparent !important; color:#232C3D !important');
                            // }else{
                            //     //เลือก
                            //     $(this).addClass('xCNActivePDT');
                            //     $(this).children().attr('style', 'background-color:#179bfd !important; color:#FFF !important');
                            // }

                            //เลือกสินค้าแบบตัวเดียว
                            $('.xCNColumnPDTMoreOne').removeClass('xCNActivePDT');
                            $('.xCNColumnPDTMoreOne').children().attr('style', 'background-color:transparent !important; color:#232C3D !important;');
                            $('.xCNColumnPDTMoreOne').children(':last-child').css('text-align', 'right');

                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#179bfd !important; color:#FFF !important;');
                            $(this).children().last().css('text-align', 'right');
                        });
                    } else {
                        //มีตัวเดียว
                        var aNewReturn = JSON.stringify(oText);
                        FSvSOAddPdtIntoDocDTTemp(aNewReturn); //Client
                        FSvSOAddBarcodeIntoDocDTTemp(aNewReturn); //Server
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // JCNxResponseError(jqXHR,textStatus,errorThrown);
                JCNSearchBarcodePdt(ptTextScan);
            }
        });
    }

    //เลือกสินค้า กรณีพบมากกว่าหนึ่งตัว
    function JCNxConfirmPDTMoreOne($ptType) {
        if ($ptType == 1) {
            $("#odvSOModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function(index) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                FSvSOAddPdtIntoDocDTTemp(tJSON);
                FSvSOAddBarcodeIntoDocDTTemp(tJSON);
            });
        } else {
            $('#oetSOInsertBarcode').attr('readonly', false);
            $('#oetSOInsertBarcode').val('');
        }
    }

    //หลังจากค้นหาเสร็จแล้ว
    function FSvSOAddBarcodeIntoDocDTTemp(ptPdtData) {
        var ptXthDocNoSend = "";
        if ($("#ohdSORoute").val() == "dcmSOEventEdit") {
            ptXthDocNoSend = $("#oetSODocNo").val();
        }
        var tSOVATInOrEx    = $('#ocmSOFrmSplInfoVatInOrEx').val();
        var tSOOptionAddPdt = $('#ocmSOFrmInfoOthReAddPdt').val();
        let tSOPplCodeBch   = $('#ohdSOPplCodeBch').val();
        let tSOPplCodeCst   = $('#ohdSOPplCodeCst').val();
        var nKey            = parseInt($('#otbSODocPdtAdvTableList tr:last').attr('data-seqno'));

        $('#oetSOInsertBarcode').attr('readonly', false);
        $('#oetSOInsertBarcode').val('');

        $.ajax({
            type    : "POST",
            url     : "dcmSOAddPdtIntoDTDocTemp",
            data    : {
                'tSelectBCH'        : $('#oetSOFrmBchCode').val(),
                'tSODocNo'          : ptXthDocNoSend,
                'tSOVATInOrEx'      : tSOVATInOrEx,
                'tSOOptionAddPdt'   : tSOOptionAddPdt,
                'tSOPdtData'        : ptPdtData,
                'tSOPplCodeBch'     : tSOPplCodeBch,
                'tSOPplCodeCst'     : tSOPplCodeCst,
                'ohdSesSessionID'   : $('#ohdSesSessionID').val(),
                'ohdSOUsrCode'      : $('#ohdSOUsrCode').val(),
                'ohdSOLangEdit'     : $('#ohdSOLangEdit').val(),
                'ohdSesUsrLevel'    : $('#ohdSesUsrLevel').val(),
                'ohdSOSesUsrBchCode': $('#ohdSOSesUsrBchCode').val(),
                'tSeqNo'            : nKey
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                console.log(oResult);
                
                var aResult = JSON.parse(oResult);
                if (aResult['nStaEvent'] == 1) {
                    JCNxCloseLoading();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    $('#obtSOBrowseRefDocInt').on('click', function() {
        JSxCallPageSOBrowseRefDocc();
    });

    //Ref เอกสารใบเสนอราคา , ใบสั่งซื้อ
    function JSxCallPageSOBrowseRefDocc() {
        var tBCHCode = $('#oetSOFrmBchCode').val()
        var tBCHName = $('#oetSOFrmBchName').val()
        var tRefType = $('#ocbSORefDoc').val()

        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docSORefIntDoc",
            data: {
                'tBCHCode': tBCHCode,
                'tBCHName': tBCHName,
                'tRefType': tRefType,
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                JCNxCloseLoading();
                
                if($('#ocbSORefDoc').val() == 1){
                    $('#ospTextRefIntDoc').text("อ้างอิงเอกสารใบเสนอราคา");
                }else{
                    $('#ospTextRefIntDoc').text("อ้างอิงเอกสารใบสั่งซื้อ");
                }

                $('#odvSOModalRefIntDoc #odvSOFromRefIntDoc').html(oResult);
                $('#odvSOModalRefIntDoc').modal({
                    backdrop: 'static',
                    show: true
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กดยืนยัน Ref เอกสารใบเสนอราคา / เอกสารใบสั่งซื้อ
    $('#obtConfirmRefDocInt').click(function() {

        $('#oetSOFrmCstCode').val('');
        $('#oetSOFrmCstName').val('');
        $('#oetSOFrmCstHNNumber').val('');
        $('#oetSOFrmCstCtzID').val('');
        $('#oetSOFrmCustomerName').val('');
        $('#otaSOCustomerAddress').val('');
        $('#oetSOFrmCstTel').val('');
        $('#oetSOFrmCarCode').val('');
        $('#oetSOFrmCarName').val('');
        $('#oetSOFrmCarRegNo').val('');
        $('.xWClearDataWhenChangeCst').val('');

        var tRefIntDocNo        = $('.xDocuemntRefInt.active').data('docno');
        var tRefIntDocDate      = $('.xDocuemntRefInt.active').data('docdate');
        var tRefIntBchCode      = $('.xDocuemntRefInt.active').data('bchcode');
        var tRefIntCstCode      = $('.xDocuemntRefInt.active').data('cstcode');
        var tRefIntCstName      = $('.xDocuemntRefInt.active').data('cstname');
        var tRefIntCstCardID    = $('.xDocuemntRefInt.active').data('cstcardid');
        var tRefIntCstTel       = $('.xDocuemntRefInt.active').data('csttel');
        var tRefIntCstAddr      = $('.xDocuemntRefInt.active').data('cstaddr');
        var tRefIntCshOrCrd     = $('.xDocuemntRefInt.active').data('cshorcrd');
        var tRefIntCrtTerm      = $('.xDocuemntRefInt.active').data('crterm');
        var tRefIntDueDate      = $('.xDocuemntRefInt.active').data('duedate');
        var tRefIntBchCodeTo    = $('.xDocuemntRefInt.active').data('bchcodeto');

        var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm) {
            return $(this).val();
        }).get();

        //ถ้าไม่เลือกเอกสารอ้างอิงมา
        if (tRefIntDocNo != undefined) {

            var tSplStaVATInOrEx    = $('.xDocuemntRefInt.active').data('vatinroex');
            var cSplCrLimit         = $('.xDocuemntRefInt.active').data('crtrem');
            var nSplCrTerm          = $('.xDocuemntRefInt.active').data('crlimit');
            var tSplCode            = $('.xDocuemntRefInt.active').data('splcode');
            var tSplName            = $('.xDocuemntRefInt.active').data('splname');
            var tSPlPaidType        = $('.xDocuemntRefInt.active').data('dstpain');
            var tVatcode            = $('.xDocuemntRefInt.active').data('vatcode');
            var nVatrate            = $('.xDocuemntRefInt.active').data('vatrate');
            var tRefType            = $('#ocbSORefDoc').val()

            //รหัสสาขาที่อ้างอิง
            $('#oetSOHDXshCstRef').val(tRefIntBchCodeTo);

            //อ้างอิงเอกสารภายใน
            $('#oetSORefIntDoc').val(tRefIntDocNo);

            //วันที่อ้างอิงเอกสารใน
            $('#oetSORefDocDate').val(tRefIntDocDate).datepicker("refresh");

            //ลูกค้า
            $('#oetSOFrmCstCode').val(tRefIntCstCode);
            $('#oetSOFrmCstName').val(tRefIntCstName);
            $('#oetSOFrmCstHNNumber').val(tRefIntCstCode);
            $('#oetSOFrmCustomerName').val(tRefIntCstName);
            $('#otaSOCustomerAddress').val(tRefIntCstAddr);
            $('#oetSOFrmCstCtzID').val(tRefIntCstCardID);
            $('#oetSOFrmCstTel').val(tRefIntCstTel);

            if (tRefIntCshOrCrd == 1) {
                $("#ocmSOFrmSplInfoPaymentType").val("1").selectpicker("refresh");
                $('.xCNPanel_CreditTerm').hide();
            } else {
                $("#ocmSOFrmSplInfoPaymentType").val("2").selectpicker("refresh");
                $('.xCNPanel_CreditTerm').show();
            }

            $('#oetSOCreditTerm').val(tRefIntCrtTerm);
            $('#oetSOEffectiveDate').val(tRefIntDueDate);
            
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docSORefIntDocInsertDTToTemp",
                data    : {
                    'tSODocNo'          : $('#oetSODocNo').val(),
                    'tSOFrmBchCode'     : $('#oetSOFrmBchCode').val(),
                    'tRefIntDocNo'      : tRefIntDocNo,
                    'tRefIntBchCode'    : tRefIntBchCode,
                    'aSeqNo'            : aSeqNo,
                    'tRefType'          : tRefType,
                },
                cache   : false,
                Timeout : 0,
                success : function(oResult) {
                    //โหลดสินค้าใน Temp
                    JSvSOLoadPdtDataTableHtml();
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            //อ้างอิงเอกสารภายใน
            $('#oetSORefInt').val('');
            $('#oetSORefIntName').val('');

            //วันที่อ้างอิงเอกสารใน
            $('#oetSORefIntDate').val('').datepicker("refresh");
        }
    });

    //เปลี่ยนประเภทการชำระ
    $('#ocmSOFrmSplInfoPaymentType').on('change', function() {
        if (this.value == 1) {
            $('.xCNPanel_CreditTerm').hide();
        } else {
            $('.xCNPanel_CreditTerm').show();
        }
    });

    var nKeepBrowseAddrOption = '';
    $('#obtSOFrmBrowseAddrAdd').click(function() {

        if ($('#oetSOFrmCstHNNumber').val() != "") {
            $('#odvSOModalAddress').modal({
                backdrop: 'static',
                show: true
            });
        } else {
            $('#odvSOModalPleseselectCustomer #odvSOPlsChooseCst').html('<p><?php echo language('document/saleorder/saleorder', 'tSoWarningBeforeSelectCst'); ?></p>');
            if ($('#ohdSOFrmShipAdd').val() != '' || $('#ohdSOFrmShipAdd').val() != null) {

            } else {

            }
            $('#odvSOModalPleseselectCustomer').modal('show');
        }

        //ถ้าเอกสารบึนทึกข้อมูลแล้ว
        if ($('#oetSODocNo').val() != '' || $('#oetSODocNo').val() != null) {
            nKeepBrowseAddrOption = 1;
            //เอาที่อยู่ของมีข้อมูลมาเเล้วมาโชว์
            JSxSetAddrInInput(nKeepBrowseAddrOption);
        }

        if ($('#ohdSOFrmShipAdd').val() != '' || $('#ohdSOFrmShipAdd').val() != null) {
            //เก็บที่อยู่ที่เลือกไว้ใน Modal
            nKeepBrowseAddrOption = 2;
            JSxSetAddrInInput(nKeepBrowseAddrOption);
        }
    });

    //เอาที่อยู่ของมีข้อมูลมาเเล้วมาโชว์
    function JSxSetAddrInInput(pnKeepBrowseAddrOption) {
        if (pnKeepBrowseAddrOption == 1) {
            //ที่อยู่สำหรับจัดส่ง
            var tFNAddSeqNo = '<?= @$tSHIP_FNAddSeqNo; ?>';
            var tFTAddV1No = '<?= @$tSHIP_FTAddV1No; ?>';
            var tFTAddV1Soi = '<?= @$tSHIP_FTAddV1Soi; ?>';
            var tFTAddV1Village = '<?= @$tSHIP_FTAddV1Village; ?>';
            var tFTAddV1Road = '<?= @$tSHIP_FTAddV1Road; ?>';
            var tFTSudName = '<?= @$tSHIP_FTSudName; ?>';
            var tFTDstName = '<?= @$tSHIP_FTDstName; ?>';
            var tFTPvnName = '<?= @$tSHIP_FTPvnName; ?>';
            var tFTAddV1PostCode = '<?= @$tSHIP_FTAddV1PostCode; ?>';
            var tFTAddTel = '<?= @$tSHIP_FTAddTel; ?>';
            var tFTAddFax = '<?= @$tSHIP_FTAddFax; ?>';

            //โชว์ค่า
            $('#oetSOAddrCode').val(tFNAddSeqNo)
            $('#oetSOAddrName').val(tFTAddV1No)
            $('#oetSOAddrNoHouse').val(tFTAddV1Soi)
            $('#oetSOAddrVillage').val(tFTAddV1Village)
            $('#oetSOAddrRoad').val(tFTAddV1Road)
            $('#oetSOAddrSubDistrict').val(tFTSudName)
            $('#oetSOAddrDistict').val(tFTDstName)
            $('#oetSOAddrProvince').val(tFTPvnName)
            $('#oetSOZipCode').val(tFTAddV1PostCode)
            $('#oetSOAddrTel').val(tFTAddTel)
        } else {
            //ที่อยู่สำหรับจัดส่ง
            var tFNAddSeqNo = $('#ohdSOAddrCode').val();
            var tFTAddV1No = $('#ohdSOAddrName').val();
            var tFTAddV1Soi = $('#ohdSOAddrNoHouse').val();
            var tFTAddV1Village = $('#ohdSOAddrVillage').val();
            var tFTAddV1Road = $('#ohdSOAddrRoad').val();
            var tFTSudName = $('#ohdSOAddrSubDistrict').val();
            var tFTDstName = $('#ohdSOAddrDistict').val();
            var tFTPvnName = $('#ohdSOAddrProvince').val();
            var tFTAddV1PostCode = $('#ohdSOZipCode').val();
            var tFTAddTel = $('#ohdSOAddrTel').val();
            var tFTAddFax = $('#ohdSOAddrFax').val();

            //โชว์ค่า
            $('#oetSOAddrCode').val(tFNAddSeqNo)
            $('#oetSOAddrName').val(tFTAddV1No)
            $('#oetSOAddrNoHouse').val(tFTAddV1Soi)
            $('#oetSOAddrVillage').val(tFTAddV1Village)
            $('#oetSOAddrRoad').val(tFTAddV1Road)
            $('#oetSOAddrSubDistrict').val(tFTSudName)
            $('#oetSOAddrDistict').val(tFTDstName)
            $('#oetSOAddrProvince').val(tFTPvnName)
            $('#oetSOZipCode').val(tFTAddV1PostCode)
            $('#oetSOAddrTel').val(tFTAddTel)
        }

    }

    //ค้นหาสินค้าใน temp
    function JSvSOCSearchPdtHTML() {
        var value = $("#oetSOFrmFilterPdtHTML").val().toLowerCase();
        $("#otbSODocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    // เวลา Click Tab1 ให้ Button Hide
    $('#oliSOContentProduct').click(function() {
        $('#odvSOContentHDRef').hide();
        $('#odvSOContentProduct').show();
    });

    // เวลา Click Tab2 ให้ Button Show
    $('#oliSOContentHDRef').click(function() {
        $('#odvSOContentProduct').hide();
        $('#odvSOContentHDRef').show();
        $('#obtSOAddDocRef').show();

        var nSOStaApv =  $('#ohdSOStaApv').val();
        var nSOStaDoc =  $('#ohdSOStaDoc').val();
        if(nSOStaApv == 2 || nSOStaApv == 1 || nSOStaDoc == 3){
            //เพิ่มข้อมูลสินค้า
            $('.xCNHideWhenCancelOrApprove').hide();
        }


    });
</script>
