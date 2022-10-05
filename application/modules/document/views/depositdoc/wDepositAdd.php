<?php
$tSesUsrLevel   = $this->session->userdata('tSesUsrLevel');
$nAddressVersion = FCNaHAddressFormat('TCNMCst');
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == '1') {
    $aDataDocHD             = @$aDataDocHD['raItems'];
    // print_r($aDataDocHD);
    if (isset($aDataDocHDSpl)) {
        $aDataDocHDSpl          = @$aDataDocHDSpl['raItems'];
    }else{
        $aDataDocHDSpl          = '';
    }
    
    $tDPSRateCode           = $aDataDocHD['FTRteCode'];
    $tDPSRateName           = $aDataDocHD['FTRteName'];
    $tDPSRoute               = "dcmDPSEventEdit";
    $nSOAutStaEdit          = 1;
    $tDPSAffectDate         = date('Y-m-d');
    $tSODocNo               = $aDataDocHD['FTXshDocNo'];
    $dSODocDate             = date("Y-m-d", strtotime($aDataDocHD['FDXshDocDate']));
    $dSODocTime             = date("H:i", strtotime($aDataDocHD['FDXshDocDate']));
    $tSOCreateBy            = $aDataDocHD['FTCreateBy'];
    $tSOUsrNameCreateBy     = $aDataDocHD['FTUsrName'];

    $tSOStaRefund           = $aDataDocHD['FTXshStaRefund'];
    $tDPSStaDoc              = $aDataDocHD['FTXshStaDoc'];
    $tDPSStaApv              = $aDataDocHD['FTXshStaApv'];
    $tSOStaPrcStk           = '';
    $tSOStaDelMQ            = '';
    $tDPSStaPaid             = $aDataDocHD['FTXshStaPaid'];

    $tSOSesUsrBchCode       = $this->session->userdata("tSesUsrBchCode");
    $tSODptCode             = $aDataDocHD['FTDptCode'];
    $tSOUsrCode             = $this->session->userdata('tSesUsername');
    $tSOLangEdit            = $this->session->userdata("tLangEdit");

    $tSOApvCode             = $aDataDocHD['FTXshApvCode'];
    $tSOUsrNameApv          = $aDataDocHD['FTXshApvName'];
    $tSORefPoDoc            = "";
    $tSORefIntDoc           = $aDataDocHD['FTXshRefInt'];
    $dSORefIntDocDate       = $aDataDocHD['FDXshRefIntDate'];
    $tSORefExtDoc           = $aDataDocHD['FTXshRefExt'];
    $dSORefExtDocDate       = $aDataDocHD['FDXshRefExtDate'];
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
    $nSOStaDocAct           = $aDataDocHD['FNXshStaDocAct'];
    $tSOFrmDocPrint         = $aDataDocHD['FNXshDocPrint'];
    $tSOFrmRmk              = $aDataDocHD['FTXshRmk'];
    $tSOSplCode             = '';
    $tSOSplName             = '';

    $tSOCmpRteCode          = $aDataDocHD['FTRteCode'];
    $cSORteFac              = $aDataDocHD['FCXshRteFac'];

    $tDPSVatInOrEx           = $aDataDocHD['FTXshVATInOrEx'];
    $tSOSplPayMentType      = $aDataDocHD['FTXshCshOrCrd'];

    $tSOCstCode             = $aDataDocHD['FTCstCode'];
    $tSOCstCardID           = $aDataDocHD['FTXshCardID'];
    $tSOCstName             = $aDataDocHD['FTXshCstName'];
    $tSOCstTel              = $aDataDocHD['FTXshCstTel'];
    $tSOSpnName             = $aDataDocHD['rtSpnName'];

    if ($aDataDocHDSpl != '') {
        // ที่อยู่สำหรับการจัดส่ง
        $tSOSplShipAdd          = $aDataDocHDSpl['FNXshShipAdd'];
        $tSOShipAddAddV1No      = (isset($aDataDocHDSpl['FTXshShipAddNo']) && !empty($aDataDocHDSpl['FTXshShipAddNo'])) ? $aDataDocHDSpl['FTXshShipAddNo'] : "-";
        $tSOShipAddV1Soi        = (isset($aDataDocHDSpl['FTXshShipAddSoi']) && !empty($aDataDocHDSpl['FTXshShipAddSoi'])) ? $aDataDocHDSpl['FTXshShipAddSoi'] : "-";
        $tSOShipAddV1Village    = (isset($aDataDocHDSpl['FTXshShipAddVillage']) && !empty($aDataDocHDSpl['FTXshShipAddVillage'])) ? $aDataDocHDSpl['FTXshShipAddVillage'] : "-";
        $tSOShipAddV1Road       = (isset($aDataDocHDSpl['FTXshShipAddRoad']) && !empty($aDataDocHDSpl['FTXshShipAddRoad'])) ? $aDataDocHDSpl['FTXshShipAddRoad'] : "-";
        $tSOShipAddV1SubDist    = (isset($aDataDocHDSpl['FTXshShipSubDistrict']) && !empty($aDataDocHDSpl['FTXshShipSubDistrict'])) ? $aDataDocHDSpl['FTXshShipSubDistrict'] : "-";
        $tSOShipAddV1DstCode    = (isset($aDataDocHDSpl['FTXshShipDistrict']) && !empty($aDataDocHDSpl['FTXshShipDistrict'])) ? $aDataDocHDSpl['FTXshShipDistrict'] : "-";
        $tSOShipAddV1PvnCode    = (isset($aDataDocHDSpl['FTXshShipProvince']) && !empty($aDataDocHDSpl['FTXshShipProvince'])) ? $aDataDocHDSpl['FTXshShipProvince'] : "-";
        $tSOShipAddV1PostCode   = (isset($aDataDocHDSpl['FTXshShipPosCode']) && !empty($aDataDocHDSpl['FTXshShipPosCode'])) ? $aDataDocHDSpl['FTXshShipPosCode'] : "-";

        // ที่อยู่สำหรับการออกใบกำกับภาษี
        $tSOSplTaxAdd           = $aDataDocHDSpl['FNXshTaxAdd'];
        $tSOTexAddAddV1No       = (isset($aDataDocHDSpl['FTXshTaxAddNo']) && !empty($aDataDocHDSpl['FTXshTaxAddNo'])) ? $aDataDocHDSpl['FTXshTaxAddNo'] : "-";
        $tSOTexAddV1Soi         = (isset($aDataDocHDSpl['FTXshTaxAddSoi']) && !empty($aDataDocHDSpl['FTXshTaxAddSoi'])) ? $aDataDocHDSpl['FTXshTaxAddSoi'] : "-";
        $tSOTexAddV1Village     = (isset($aDataDocHDSpl['FTXshTaxAddVillage']) && !empty($aDataDocHDSpl['FTXshTaxAddVillage'])) ? $aDataDocHDSpl['FTXshTaxAddVillage'] : "-";
        $tSOTexAddV1Road        = (isset($aDataDocHDSpl['FTXshTaxAddRoad']) && !empty($aDataDocHDSpl['FTXshTaxAddRoad'])) ? $aDataDocHDSpl['FTXshTaxAddRoad'] : "-";
        $tSOTexAddV1SubDist     = (isset($aDataDocHDSpl['FTXshTaxSubDistrict']) && !empty($aDataDocHDSpl['FTXshTaxSubDistrict'])) ? $aDataDocHDSpl['FTXshTaxSubDistrict'] : "-";
        $tSOTexAddV1DstCode     = (isset($aDataDocHDSpl['FTXshTaxDistrict']) && !empty($aDataDocHDSpl['FTXshTaxDistrict'])) ? $aDataDocHDSpl['FTXshTaxDistrict'] : "-";
        $tSOTexAddV1PvnCode     = (isset($aDataDocHDSpl['FTXshTaxProvince']) && !empty($aDataDocHDSpl['FTXshTaxProvince'])) ? $aDataDocHDSpl['FTXshTaxProvince'] : "-";
        $tSOTexAddV1PostCode    = (isset($aDataDocHDSpl['FTXshTaxPosCode']) && !empty($aDataDocHDSpl['FTXshTaxPosCode'])) ? $aDataDocHDSpl['FTXshTaxPosCode'] : "-";
    }else{
        // ที่อยู่สำหรับการจัดส่ง
        $tSOSplShipAdd          = "";
        $tSOShipAddAddV1No      = "-";
        $tSOShipAddV1Soi        = "-";
        $tSOShipAddV1Village    = "-";
        $tSOShipAddV1Road       = "-";
        $tSOShipAddV1SubDist    = "-";
        $tSOShipAddV1DstCode    = "-";
        $tSOShipAddV1PvnCode    = "-";
        $tSOShipAddV1PostCode   = "-";

        // ที่อยู่สำหรับการออกใบกำกับภาษี
        $tSOSplTaxAdd           = "";
        $tSOTexAddAddV1No       = "-";
        $tSOTexAddV1Soi         = "-";
        $tSOTexAddV1Village     = "-";
        $tSOTexAddV1Road        = "-";
        $tSOTexAddV1SubDist     = "-";
        $tSOTexAddV1DstCode     = "-";
        $tSOTexAddV1PvnCode     = "-";
        $tSOTexAddV1PostCode    = "-";
    }
    
    $nStaUploadFile        = 2;

    //ข้อมูล CST
    $tDPSTelephone          = $aDataDocHD['FTXshCstTel'];
    if($nAddressVersion == '2'){
        $tDPSAddress            = $aDataDocHD['FTAddV2Desc1'];
    }elseif($nAddressVersion == '1'){
        $tDPSAddress            = $aDataDocHD['FTAddV1Desc'];
    }
    $tDPSCstName            = $aDataDocHD['FTXshCstName'];
    $tDPSCstCardID          = $aDataDocHD['FTXshCardID'];
    $tDPSHDRemark           = $aDataDocHD['FTXshRmk'];

    $tDPStVatCode           = $tVatCode;
    $tDPStVatRate           = $cVatRate;
    $nDPSFNXshStaRef        = $aDataDocHD['FNXshStaRef'];
    $tDPSFNXshDocPrint      = $aDataDocHD['FNXshDocPrint'];
    $nDPSStaDocAct          = $aDataDocHD['FNXshStaDocAct'];
    $tFTXshRefExt          = $aDataDocHD['FTXshRefExt'];
    $tFDXshRefExtDate      = $aDataDocHD['FDXshRefExtDate'];
    $tFTXshRefInt          = $aDataDocHD['FTXshRefInt'];
    $tFDXshRefIntDate      = $aDataDocHD['FDXshRefIntDate'];
    $tSOCstPplCode         = $aDataDocHD['FTPplCode'];
} else {
    $tDPSRoute               = "dcmDPSEventAdd";
    $nSOAutStaEdit          = 0;
    $tSODocNo               = "";
    $tDPStVatCode           = $tVatCode;
    $tDPStVatRate           = $cVatRate;
    $tDPSAffectDate         = date('Y-m-d');
    $dSODocDate             = date('Y-m-d');
    $dSODocTime             = date('H:i:s');
    $tSOCreateBy            = $this->session->userdata('tSesUsrUsername');
    $tSOUsrNameCreateBy     = $this->session->userdata('tSesUsrUsername');
    $nSOStaRef              = 0;
    $tSOStaRefund           = 1;
    $tDPSStaDoc              = 1;
    $tDPSStaApv              = NULL;
    $tSOStaPrcStk           = NULL;
    $tSOStaDelMQ            = NULL;
    $tDPSStaPaid             = 1;
    $tDPSRateName           = $aRateDefault['raItems']['rtCmpRteName'];
    $tDPSRateCode           = $aRateDefault['raItems']['rtCmpRteCode'];
    $tSOSesUsrBchCode       = $this->session->userdata("tSesUsrBchCode");
    $tSODptCode             = $tDptCode;
    $tSOUsrCode             = $this->session->userdata('tSesUsername');
    $tSOLangEdit            = $this->session->userdata("tLangEdit");

    $tSOApvCode             = "";
    $tSOUsrNameApv          = "";
    $tSORefPoDoc            = "";
    $tSORefIntDoc           = "";
    $dSORefIntDocDate       = "";
    $tSORefExtDoc           = "";
    $dSORefExtDocDate       = "";
    $tSOSpnName             = "";
    $tDPSTelephone          = "";
    $tDPSAddress            = "";
    $tDPSCstName            = "";
    $tDPSCstCardID          = "";



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

    $tDPSVatInOrEx           = $tCmpRetInOrEx;
    $tSOSplPayMentType      = "";

    $tSOCstCode             = '';
    $tSOCstCardID           = '';
    $tSOCstName             = '';
    $tSOCstTel              = '';
    $tSOCstPplCode          = '';
    // ที่อยู่สำหรับการจัดส่ง
    $tSOSplShipAdd          = "";
    $tSOShipAddAddV1No      = "-";
    $tSOShipAddV1Soi        = "-";
    $tSOShipAddV1Village    = "-";
    $tSOShipAddV1Road       = "-";
    $tSOShipAddV1SubDist    = "-";
    $tSOShipAddV1DstCode    = "-";
    $tSOShipAddV1PvnCode    = "-";
    $tSOShipAddV1PostCode   = "-";

    // ที่อยู่สำหรับการออกใบกำกับภาษี
    $tSOSplTaxAdd           = "";
    $tSOTexAddAddV1No       = "-";
    $tSOTexAddV1Soi         = "-";
    $tSOTexAddV1Village     = "-";
    $tSOTexAddV1Road        = "-";
    $tSOTexAddV1SubDist     = "-";
    $tSOTexAddV1DstCode     = "-";
    $tSOTexAddV1PvnCode     = "-";
    $tSOTexAddV1PostCode    = "-";
    $tSOStaAlwPosCalSo   = "1";
    $nStaUploadFile        = 1;
    $tDPSHDRemark        = "";
    $nDPSFNXshStaRef        = "";
    $tDPSFNXshDocPrint      = "";
    $nDPSStaDocAct          = "1";

    $tFTXshRefInt          = "";
    $tFDXshRefIntDate      = "";
    $tFTXshRefExt          = "";
    $tFDXshRefExtDate      = "";

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

// echo $nDPSStaDocAct;
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
    <input type="hidden" id="ohdDPSRoute" name="ohdDPSRoute" value="<?php echo $tDPSRoute; ?>">
    <input type="hidden" id="ohdSOCheckClearValidate" name="ohdSOCheckClearValidate" value="0">
    <input type="hidden" id="ohdSOCheckSubmitByButton" name="ohdSOCheckSubmitByButton" value="0">
    <input type="hidden" id="ohdSOAutStaEdit" name="ohdSOAutStaEdit" value="<?php echo $nSOAutStaEdit; ?>">
    <input type="hidden" id="ohdSOPplCodeBch" name="ohdSOPplCodeBch" value="">
    <input type="hidden" id="ohdSOPplCodeCst" name="ohdSOPplCodeCst" value="<?= $tSOCstPplCode ?>">
    <input type="hidden" id="ohdSOStaRefund" name="ohdSOStaRefund" value="<?php echo $tSOStaRefund; ?>">
    <input type="hidden" id="ohdDPSStaDoc" name="ohdDPSStaDoc" value="<?php echo $tDPSStaDoc; ?>">
    <input type="hidden" id="ohdDPSStaApv" name="ohdDPSStaApv" value="<?php echo $tDPSStaApv; ?>">
    <input type="hidden" id="ohdDPSVatCode" name="ohdDPSVatCode" value="<?php echo $tDPStVatCode; ?>">
    <input type="hidden" id="ohdSOStaDelMQ" name="ohdSOStaDelMQ" value="<?php echo $tSOStaDelMQ; ?>">
    <input type="hidden" id="ohdSOStaPrcStk" name="ohdSOStaPrcStk" value="<?php echo $tSOStaPrcStk; ?>">
    <input type="hidden" id="ohdDPSStaPaid" name="ohdDPSStaPaid" value="<?php echo $tDPSStaPaid; ?>">
    <input type="hidden" id="ohdSOODecimalShow" name="ohdSOODecimalShow" value="<?= $nOptDecimalShow ?>">
    <input type="hidden" id="ohdSOSesUsrBchCode" name="ohdSOSesUsrBchCode" value="<?php echo $tSOSesUsrBchCode; ?>">
    <input type="hidden" id="ohdSOBchCode" name="ohdSOBchCode" value="<?php echo $tSOBchCode; ?>">
    <input type="hidden" id="ohdSODptCode" name="ohdSODptCode" value="<?php echo $tSODptCode; ?>">
    <input type="hidden" id="ohdSOUsrCode" name="ohdSOUsrCode" value="<?php echo $tSOUsrCode ?>">
    <input type="hidden" id="ohdDPSApvCode" name="ohdDPSApvCode" value="<?php echo $tSOApvCode ?>">

    <input type="hidden" id="ohdSOPosCode" name="ohdSOPosCode" value="">
    <input type="hidden" id="ohdSOShfCode" name="ohdSOShfCode" value="">

    <input type="hidden" id="ohdSOCmpRteCode" name="ohdSOCmpRteCode" value="<?php echo $tSOCmpRteCode; ?>">
    <input type="hidden" id="ohdSORteFac" name="ohdSORteFac" value="<?php echo $cSORteFac; ?>">

    <input type="hidden" id="ohdSOApvCodeUsrLogin" name="ohdSOApvCodeUsrLogin" value="<?php echo $tSOUsrCode; ?>">
    <input type="hidden" id="ohdSOLangEdit" name="ohdSOLangEdit" value="<?php echo $tSOLangEdit; ?>">
    <input type="hidden" id="ohdSOOptAlwSaveQty" name="ohdSOOptAlwSaveQty" value="<?php echo $nOptDocSave ?>">
    <input type="hidden" id="ohdSOOptScanSku" name="ohdSOOptScanSku" value="<?php echo $nOptScanSku ?>">
    <input type="hidden" id="ohdDPSVatRate" name="ohdDPSVatRate" value="<?= $tDPStVatRate ?>">
    <input type="hidden" id="ohdDPSVatInOrEx" name="ohdDPSVatInOrEx" value="<?= $tDPSVatInOrEx ?>">
    <input type="hidden" id="ohdSOCmpRetInOrEx" name="ohdSOCmpRetInOrEx" value="<?= $tCmpRetInOrEx ?>">
    <input type="hidden" id="ohdSesSessionID" name="ohdSesSessionID" value="<?= $this->session->userdata('tSesSessionID') ?>">
    <input type="hidden" id="ohdSesUsrLevel" name="ohdSesUsrLevel" value="<?= $this->session->userdata('tSesUsrLevel') ?>">
    <input type="hidden" id="ohdSesUsrBchCom" name="ohdSesUsrBchCom" value="<?= $this->session->userdata('tSesUsrBchCom') ?>">
    <input type="hidden" id="ohdSOValidatePdt" name="ohdSOValidatePdt" value="<?= language('document/saleorder/saleorder', 'tSOPleaseSeletedPDTIntoTable') ?>">

    <button style="display:none" type="submit" id="obtDPSSubmitDocument" onclick="JSxDPSAddEditDocument()"></button>
    <div class="row">
        <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar">
            <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSOHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/depositdoc/depositdoc', 'tDPSLabelFrmStatus'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvDPSDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvDPSDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
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
                                            <input type="checkbox" id="ocbDPSStaAutoGenCode" name="ocbDPSStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai" id="oetDPSDocNo" name="oetDPSDocNo" maxlength="20" value="<?php echo $tSODocNo; ?>" data-validate-required="<?php echo language('document/saleorder/saleorder', 'tSOPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/saleorder/saleorder', 'tSOPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/saleorder/saleorder', 'tSOLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdSOCheckDuplicateCode" name="ohdSOCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetSODocDate" name="oetSODocDate" value="<?php echo $dSODocDate; ?>" data-validate-required="<?php echo language('document/saleorder/saleorder', 'tSOPlsEnterDocDate'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSODocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNTimePicker" id="oetSODocTime" name="oetSODocTime" value="<?php echo $dSODocTime; ?>" data-validate-required="<?php echo language('document/saleorder/saleorder', 'tSOPlsEnterDocTime'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtSODocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
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
                                            if ($tDPSRoute == "dcmDPSEventAdd") {
                                                $tSOLabelStaDoc  = language('document/saleorder/saleorder', 'tSOLabelFrmValStaDoc');
                                            } else {
                                                $tSOLabelStaDoc  = language('document/saleorder/saleorder', 'tSOLabelFrmValStaDoc' . $tDPSStaDoc);
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
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmValStaApv' . $tDPSStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะประมวลผลเอกสาร -->
                                <!-- <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmStaPrcStk'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/saleorder/saleorder', 'tSOLabelFrmValStaPrcStk' . $tSOStaPrcStk); ?></label>
                                        </div>
                                    </div>
                                </div> -->
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

            <!-- Panel ลูกค้า-->
            <?php include('panel/wPanelCutomer.php'); ?>

            <!-- Panel เงื่อนไขการชำระเงิน-->
            <?php include('panel/wPanelPayment.php'); ?>

            <!-- Panel อ้างอิงเอกสารภายใน และ ภายนอก-->
            <?php include('panel/wPanelReference.php'); ?>

            <!-- Panel อื่นๆ -->
            <?php include('panel/wPanelOther.php'); ?>



            <!-- Panel ไฟลแนบ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSOReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'ไฟล์แนบ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvDPSDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDPSDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>


                var oDPSCallDataTableFile = {
                    ptElementID     : 'odvShowDataTable',
                    ptBchCode       : $('#ohdDPSBchCode').val(),
                    ptDocNo         : $('#oetDPSDocNo').val(),
                    ptDocKey        : 'TARTRcvDepositHD',
                    ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                    pnEvent         : <?= $nStaUploadFile ?>,
                    ptCallBackFunct : '',
                    ptStaApv        : $('#ohdDPSStaApv').val(),
                    ptStaDoc        : $('#ohdDPSStaDoc').val()
                }
                JCNxUPFCallDataTable(oDPSCallDataTableFile);
            </script>
        </div>
        <?php

        if ($tDPSStaApv  == '1' || $tDPSStaDoc == '3') {
            $tBrowseDisabled     = 'disabled';
        } else {
            $tBrowseDisabled     = '';
        }
        ?>
        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9"><!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <!-- ตารางรายการสินค้า -->
                <div id="odvSODataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;min-height:500px;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <div class="row p-t-10">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer', 'tCSTTitle'); ?></label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control xCNHide" id="oetSOFrmCstCode" name="oetSOFrmCstCode" value="<?php echo $tSOCstCode; ?>">
                                                <input type="text" class="form-control" id="oetSOFrmCstName" name="oetSOFrmCstName" value="<?php echo $tSOCstName; ?>" placeholder="<?php echo language('document/saleorder/saleorder', 'tSOCstCode') ?>" readonly>
                                                <span class="input-group-btn">
                                                    <button id="obtDPSBrowseCustomer" type="button" class="btn xCNBtnBrowseAddOn" <?= $tBrowseDisabled; ?>>
                                                        <img class="xCNIconFind">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row p-t-10 p-r-10">

                                    <!--ค้นหา-->
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvDPSSearchPdtHTML()" placeholder="<?php echo language('common/main/main', 'tPlaceholder'); ?>">
                                                <span class="input-group-btn">
                                                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvDPSSearchPdtHTML()">
                                                        <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 text-right">
                                        <div class="row">
                                            <!--แสดงคอลัมน์-->
                                            <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4">
                                                <div id="odvSOMngAdvTableList" class="btn-group xCNDropDrownGroup">
                                                    <!-- <button id="obtSOAdvTablePdtDTTemp" type="button" class="btn xCNBTNMngTable m-r-20"><?php echo language('common/main/main', 'tModalAdvTable') ?></button> -->
                                                </div>
                                            </div>
                                            <!--ตัวเลือก-->
                                            <div class="col-xs-12 col-sm-5 col-md-7 col-lg-7 text-right">
                                                <div id="odvSOMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup">
                                                    <button type="button" class="btn xCNBTNMngTable xWDropdown" data-toggle="dropdown">
                                                        <?php echo language('common/main/main', 'tCMNOption') ?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li id="oliDPSBtnDeleteMulti" class="disabled">
                                                            <a data-toggle="modal" data-target="#odvDPSModalDelPdtInDTTempMultiple"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!--เพิ่มจากอ้างอิงใบขาย-->
                                            <!-- <button type="button" id="obtDPSDocQTPdt" class="xCNBTNDefult xCNDocBrowseSO">อ้างอิงใบเสนอราคา</button> -->
                                            <!--เพิ่มจากอ้างอิงใบขาย-->
                                            <!-- <button type="button" id="obtDPSDocSOPdt" class="xCNBTNDefult xCNDocBrowseSO">อ้างอิงใบสั่งขาย</button> -->
                                            <!--เพิ่มสินค้าแบบปกติ-->

                                            <!-- <button type="button" id="obtDPSDocBrowsePdt" class="xCNBTNDefult xCNDocBrowsePdt">+ เพิ่มรายการเงินมัดจำ</button> -->
                                            <div class="col-xs-12 col-sm-2 col-md-1 col-lg-1">
                                                <div class="form-group">
                                                    <div style="position: absolute;right: 15px;top:-5px;">
                                                        <button type="button" id="obtDPSDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row p-t-10" id="odvSODataPdtTableDTTemp">

                                </div>
                                <!--ส่วนสรุปท้ายบิล-->
                                <div class="row" id="odvRowDataEndOfBill">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading mark-font" id="odvDataTextBath"></div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="pull-left mark-font"><?= language('document/depositdoc/depositdoc', 'tDPSVatAndRmk'); ?></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div style='padding: 10px 10px 0px 10px;'>
                                                <!-- หมายเหตุ -->
                                                <div class="form-group">
                                                    <textarea class="" id="oetDPSHdRemark" name="oetDPSHdRemark" maxlength="200"><?= @$tDPSHDRemark; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="panel-heading">
                                                <div class="pull-left mark-font"><?= language('document/saleorder/saleorder', 'tSOTBVatRate'); ?></div>
                                                <div class="pull-right mark-font"><?= language('document/saleorder/saleorder', 'tSOTBAmountVat'); ?></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body">
                                                <ul class="list-group" id="oulDPSDataListVat">
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
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <label class="pull-left mark-font"><?= language('document/saleorder/saleorder', 'tSOTBSumFCXtdNet'); ?></label>
                                                        <input type="text" id="olbSumFCXtdNetAlwDis" style="display:none;"></label>
                                                        <label class="pull-right mark-font" id="olbSumFCXtdNet">0.00</label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                    <li class="list-group-item" style='display:none'>
                                                        <label class="pull-left"><?= language('document/saleorder/saleorder', 'tSOTBDisChg'); ?>
                                                            <button type="button" class="xCNBTNPrimeryDisChgPlus" onclick="JCNvSOMngDocDisChagHD(this)" style="float: right; margin-top: 3px; margin-left: 5px;">+</button>
                                                        </label>
                                                        <label class="pull-left" style="margin-left: 5px;" id="olbDisChgHD"></label>
                                                        <label class="pull-right" id="olbSumFCXtdAmt">0.00</label>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- =================================================================== View Modal Shipping Purchase Invoice  =================================================================== -->
<div id="odvSOBrowseShipAdd" class="modal fade">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/saleorder/saleorder', 'tSOShipAddress'); ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSnSOShipAddData()"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="panel panel-default" style="margin-bottom:5px;">
                            <div class="panel-heading xCNPanelHeadColor" style="padding-top:5px!important;padding-bottom:5px!important;">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'tSOShipAddInfo'); ?></label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                        <a style="font-size:14px!important;color:#1866ae;">
                                            <i class="fa fa-pencil" id="oliSOEditShipAddress">&nbsp;<?php echo language('document/saleorder/saleorder', 'tSOShipChange'); ?></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body xCNPDModlue">
                                <input type="hidden" id="ohdSOShipAddSeqNo" class="form-control">
                                <?php $tSOFormatAddressType = FCNaHAddressFormat('TCNMBranch'); //1 ที่อยู่ แบบแยก  ,2  แบบรวม
                                ?>
                                <?php if (!empty($tSOFormatAddressType) && $tSOFormatAddressType == '1') : ?>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOShipADDV1No'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOShipAddAddV1No"><?php echo @$tSOShipAddAddV1No; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOShipADDV1Village'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOShipAddV1Soi"><?php echo @$tSOShipAddV1Soi; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOShipADDV1Soi'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOShipAddV1Village"><?php echo @$tSOShipAddV1Village; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOShipADDV1Road'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOShipAddV1Road"><?php echo @$tSOShipAddV1Road; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOShipADDV1SubDist'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOShipAddV1SubDist"><?php echo @$tSOShipAddV1SubDist; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOShipADDV1DstCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOShipAddV1DstCode"><?php echo @$tSOShipAddV1DstCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOShipADDV1PvnCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOShipAddV1PvnCode"><?php echo @$tSOShipAddV1PvnCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOShipADDV1PostCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOShipAddV1PostCode"><?php echo @$tSOShipAddV1PostCode; ?></label>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOShipADDV2Desc1') ?></label><br>
                                                <label id="ospSOShipAddV2Desc1"><?php echo @$tSOShipAddV2Desc1; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOShipADDV2Desc2') ?></label><br>
                                                <label id="ospSOShipAddV2Desc2"><?php echo @$tSOShipAddV2Desc2; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ================================================================== View Modal TexAddress Purchase Invoice  ================================================================== -->
<div id="odvSOBrowseTexAdd" class="modal fade">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/saleorder/saleorder', 'tSOTexAddress'); ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSnSOTexAddData()"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="panel panel-default" style="margin-bottom:5px;">
                            <div class="panel-heading xCNPanelHeadColor" style="padding-top:5px!important;padding-bottom:5px!important;">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'tSOTexAddInfo'); ?></label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                        <a style="font-size:14px!important;color:#1866ae;">
                                            <i class="fa fa-pencil" id="oliSOEditTexAddress">&nbsp;<?php echo language('document/saleorder/saleorder', 'tSOTexChange'); ?></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body xCNPDModlue">
                                <input type="hidden" id="ohdSOTexAddSeqNo" class="form-control">
                                <?php $tSOFormatAddressType = FCNaHAddressFormat('TCNMBranch'); //1 ที่อยู่ แบบแยก  ,2  แบบรวม
                                ?>
                                <?php if (!empty($tSOFormatAddressType) && $tSOFormatAddressType == '1') : ?>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOTexADDV1No'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOTexAddAddV1No"><?php echo @$tSOTexAddAddV1No; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOTexADDV1Village'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOTexAddV1Soi"><?php echo @$tSOTexAddV1Soi; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOTexADDV1Soi'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOTexAddV1Village"><?php echo @$tSOTexAddV1Village; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOTexADDV1Road'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOTexAddV1Road"><?php echo @$tSOTexAddV1Road; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOTexADDV1SubDist'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOTexAddV1SubDist"><?php echo @$tSOTexAddV1SubDist; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOTexADDV1DstCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOTexAddV1DstCode"><?php echo @$tSOTexAddV1DstCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOTexADDV1PvnCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOTexAddV1PvnCode"><?php echo @$tSOTexAddV1PvnCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOTexADDV1PostCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospSOTexAddV1PostCode"><?php echo @$tSOTexAddV1PostCode; ?></label>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOTexADDV2Desc1') ?></label><br>
                                                <label id="ospSOTexAddV2Desc1"><?php echo @$tSOTexAddV2Desc1; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOTexADDV2Desc2') ?></label><br>
                                                <label id="ospSOTexAddV2Desc2"><?php echo @$tSOTexAddV2Desc2; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================================================================================================================================= -->

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
                <button onclick="JSxDPSApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
<div id="odvDPSModalPaidDoc" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('document/depositdoc/depositdoc','tDPSEventDepositStatus'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong><?php echo language('document/depositdoc/depositdoc', 'tDPSConfirmDepositStatus'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxDPSDepositDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

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
                <button onclick="JSnDPSCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- =====================================================================  Modal Advance Table Product DT Temp ==================================================================-->
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
<!-- ============================================================================================================================================================================= -->

<!-- ============================================================== View Modal Delete Product In DT DocTemp Multiple  ============================================================ -->
<div id="odvDPSModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
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
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal ไม่พบลูกค้า   ======================================================================== -->
<div id="odvSOModalPleseselectCustomer" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>กรุณาเลือกลูกค้า ก่อนเพิ่มสินค้า</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxFocusInputCustomer();">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->
<!-- ======================================================================== Modal ไม่พบลูกค้า   ======================================================================== -->
<div id="odvSOModalPleseClearRef" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>กรุณาลบอ้างอิงเอกสารภายในเก่าก่อน</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxFocusInputCustomer();">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvSOModalPDTNotFound" class="modal fade">
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
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

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
                            <th class="xCNTextBold" style="text-align:center; width:120px;"><?= language('common/main/main', 'tModalcodePDT') ?></th>
                            <th class="xCNTextBold" style="text-align:center; width:160px;"><?= language('common/main/main', 'tModalnamePDT') ?></th>
                            <th class="xCNTextBold" style="text-align:center; width:120px;"><?= language('common/main/main', 'tModalPriceUnit') ?></th>
                            <th class="xCNTextBold" style="text-align:center; width:160px;"><?= language('common/main/main', 'tModalbarcodePDT') ?></th>
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
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvSOModalChangeBCH" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>มีการเปลี่ยนแปลงสาขา สินค้าที่ทำรายการไว้ จะถูกล้างหมด กดยืนยันเพื่อเปลี่ยนแปลงสาขา ? </p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" id="obtChangeBCH" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                <button type="button" data-dismiss="modal" class="btn xCNBTNDefult"><?php echo language('common/main/main', 'ยกเลิก'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->
<!-- =========================================== อ้างอิงเอกสารภายใน ============================================= -->
<div id="odvDPSModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/bookingorder/bookingorder', 'tTWXLabelRefDocInt') ?></label>
            </div>

            <div class="modal-body">
                <div class="row" id="odvDPSFromRefIntDoc">

                </div>
            </div>

            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>

        </div>
    </div>
</div>

<!-- =========================================== ไม่พบคลังสินค้า ============================================= -->



<script src="<?php echo base_url('application/modules/common/assets/src/jThaiBath.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jDepositAdd.php'); ?>
<?php
// include('dis_chg/wSaleOrderDisChgModal.php');
?>


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
                BCH: $("#ohdDPSBchCode").val(),
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
                            FSvDPSAddBarcodeIntoDocDTTemp(tJSON);
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
                            //     $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important');
                            // }

                            //เลือกสินค้าแบบตัวเดียว
                            $('.xCNColumnPDTMoreOne').removeClass('xCNActivePDT');
                            $('.xCNColumnPDTMoreOne').children().attr('style', 'background-color:transparent !important; color:#232C3D !important;');
                            $('.xCNColumnPDTMoreOne').children(':last-child').css('text-align', 'right');

                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important;');
                            $(this).children().last().css('text-align', 'right');
                        });
                    } else {
                        //มีตัวเดียว
                        var aNewReturn = JSON.stringify(oText);
                        console.log('aNewReturn: ' + aNewReturn);
                        // var aNewReturn  = '[{"pnPdtCode":"00009","ptBarCode":"ca2020010003","ptPunCode":"00001","packData":{"SHP":null,"BCH":null,"PDTCode":"00009","PDTName":"ขนม_03","PUNCode":"00001","Barcode":"ca2020010003","PUNName":"ขวด","PriceRet":"17.00","PriceWhs":"0.00","PriceNet":"0.00","IMAGE":"D:/xampp/htdocs/Moshi-Moshi/application/modules/product/assets/systemimg/product/00009/Img200128172902CEHHRSS.jpg","LOCSEQ":"","Remark":"ขนม_03","CookTime":0,"CookHeat":0}}]';
                        FSvSOAddPdtIntoDocDTTemp(aNewReturn); //Client
                        // JCNxCloseLoading();
                        // $('#oetSOInsertBarcode').attr('readonly',false);
                        // $('#oetSOInsertBarcode').val('');
                        FSvDPSAddBarcodeIntoDocDTTemp(aNewReturn); //Server
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
                FSvDPSAddBarcodeIntoDocDTTemp(tJSON);
            });
        } else {
            $('#oetSOInsertBarcode').attr('readonly', false);
            $('#oetSOInsertBarcode').val('');
        }
    }

    //หลังจากค้นหาเสร็จแล้ว
    function FSvDPSAddBarcodeIntoDocDTTemp(ptPdtData) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            // JCNxOpenLoading();
            var ptXthDocNoSend = "";
            if ($("#ohdDPSRoute").val() == "dcmDPSEventEdit") {
                ptXthDocNoSend = $("#oetDPSDocNo").val();
            }
            var tSOVATInOrEx = $('#ocmDPSFrmSplInfoVatInOrEx').val();
            var tSOOptionAddPdt = $('#ocmSOFrmInfoOthReAddPdt').val();
            let tSOPplCodeBch = $('#ohdSOPplCodeBch').val();
            let tSOPplCodeCst = $('#ohdSOPplCodeCst').val();
            var tDPSVatInOrEx = $("#ocmVatInOrEx").val();
            var tDPSVatRate = $("#ohdDPSVatRate").val();
            var nKey = parseInt($('#otbDPSDocPdtAdvTableList tr:last').attr('data-seqno'));

            $('#oetSOInsertBarcode').attr('readonly', false);
            $('#oetSOInsertBarcode').val('');

            $.ajax({
                type: "POST",
                url: "dcmDPSAddPdtIntoDTDocTemp",
                data: {
                    'tSelectBCH': $('#ohdDPSBchCode').val(),
                    'tSODocNo': ptXthDocNoSend,
                    'tSOVATInOrEx': tSOVATInOrEx,
                    'tSOOptionAddPdt': tSOOptionAddPdt,
                    'tSOPdtData': ptPdtData,
                    'tSOPplCodeBch': tSOPplCodeBch,
                    'tSOPplCodeCst': tSOPplCodeCst,
                    'nVatInOrEx': tDPSVatInOrEx,
                    'nVatRate': tDPSVatRate,
                    'ohdSesSessionID': $('#ohdSesSessionID').val(),
                    'ohdSOUsrCode': $('#ohdSOUsrCode').val(),
                    'ohdSOLangEdit': $('#ohdSOLangEdit').val(),
                    'ohdSesUsrLevel': $('#ohdSesUsrLevel').val(),
                    'ohdSOSesUsrBchCode': $('#ohdSOSesUsrBchCode').val(),
                    'tSeqNo': nKey
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    // JSvSOLoadPdtDataTableHtml();
                    var aResult = JSON.parse(oResult);

                    if (aResult['nStaEvent'] == 1) {
                        JCNxCloseLoading();
                        // $('#oetSOInsertBarcode').attr('readonly',false);
                        // $('#oetSOInsertBarcode').val('');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // JCNxResponseError(jqXHR, textStatus, errorThrown);
                    FSvDPSAddBarcodeIntoDocDTTemp(ptPdtData);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //หลังจากค้นหาเสร็จแล้ว
    function FSvDPSAddSOIntoDocDTTemp(ptPdtData) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            // JCNxOpenLoading();
            var ptXthDocNoSend = "";
            if ($("#ohdDPSRoute").val() == "dcmDPSEventEdit") {
                ptXthDocNoSend = $("#oetDPSDocNo").val();
            }
            var tSOVATInOrEx = $('#ocmDPSFrmSplInfoVatInOrEx').val();
            var tSOOptionAddPdt = $('#ocmSOFrmInfoOthReAddPdt').val();
            let tSOPplCodeBch = $('#ohdSOPplCodeBch').val();
            let tSOPplCodeCst = $('#ohdSOPplCodeCst').val();
            var tDPSVatInOrEx = $("#ocmVatInOrEx").val();
            var tDPSVatRate = $("#ohdDPSVatRate").val();
            var nKey = parseInt($('#otbDPSDocPdtAdvTableList tr:last').attr('data-seqno'));
            $('#oetSOInsertBarcode').attr('readonly', false);
            $('#oetSOInsertBarcode').val('');

            $.ajax({
                type: "POST",
                url: "dcmDPSAddPdtSOIntoDTDocTemp",
                data: {
                    'tSelectBCH': $('#ohdDPSBchCode').val(),
                    'tSODocNo': ptXthDocNoSend,
                    'tSOVATInOrEx': tSOVATInOrEx,
                    'tSOOptionAddPdt': tSOOptionAddPdt,
                    'tSOPdtData': ptPdtData,
                    'tSOPplCodeBch': tSOPplCodeBch,
                    'tSOPplCodeCst': tSOPplCodeCst,
                    'nVatInOrEx': tDPSVatInOrEx,
                    'nVatRate': tDPSVatRate,
                    'ohdSesSessionID': $('#ohdSesSessionID').val(),
                    'ohdSOUsrCode': $('#ohdSOUsrCode').val(),
                    'ohdSOLangEdit': $('#ohdSOLangEdit').val(),
                    'ohdSesUsrLevel': $('#ohdSesUsrLevel').val(),
                    'ohdSOSesUsrBchCode': $('#ohdSOSesUsrBchCode').val(),
                    'tSeqNo': nKey
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    // JSvSOLoadPdtDataTableHtml();
                    var aResult = JSON.parse(oResult);

                    if (aResult['nStaEvent'] == 1) {
                        JCNxCloseLoading();
                        // $('#oetSOInsertBarcode').attr('readonly',false);
                        // $('#oetSOInsertBarcode').val('');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // JCNxResponseError(jqXHR, textStatus, errorThrown);
                    FSvDPSAddBarcodeIntoDocDTTemp(ptPdtData);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }
</script>
