<?php
    $tSesUsrLevel   = $this->session->userdata('tSesUsrLevel');
    if(isset($aDataDocHD) && $aDataDocHD['rtCode'] == '1'){
        $aDataDocHD             = @$aDataDocHD['raItems'];
        $aDataDocHDSpl          = @$aDataDocHDSpl['raItems'];

        $tPXRoute               = "docPXEventEdit";
        $nPXAutStaEdit          = 1;
        $tPXDocNo               = $aDataDocHD['FTXphDocNo'];
        $dPXDocDate             = date("Y-m-d",strtotime($aDataDocHD['FDXphDocDate']));
        $dPXDocTime             = date("H:i:s",strtotime($aDataDocHD['FDXphDocDate']));
        $tPXCreateBy            = $aDataDocHD['FTCreateBy'];
        $tPXUsrNameCreateBy     = $aDataDocHD['FTUsrName'];

        $tPXStaRefund           = $aDataDocHD['FTXphStaRefund'];
        $tPXStaDoc              = $aDataDocHD['FTXphStaDoc'];
        $tPXStaApv              = $aDataDocHD['FTXphStaApv'];
        // $tPXStaPrcStk           = $aDataDocHD['FTXphStaPrcStk'];
        $tPXStaDelMQ            = $aDataDocHD['FTXphStaDelMQ'];
        $tPXStaPaid             = $aDataDocHD['FTXphStaPaid'];

        $tPXSesUsrBchCode       = $this->session->userdata("tSesUsrBchCode");
        $tPXDptCode             = $aDataDocHD['FTDptCode'];
        $tPXUsrCode             = $this->session->userdata('tSesUsername');
        $tPXLangEdit            = $this->session->userdata("tLangEdit");

        $tPXApvCode             = $aDataDocHD['FTXphApvCode'];
        $tPXUsrNameApv          = $aDataDocHD['FTXphApvName'];
        $tPXRefPoDoc            = "";

        // $tPXRefIntDoc           = "";
        // $dPXRefIntDocDate       = "";
        // $tPXRefExtDoc           = "";
        // $dPXRefExtDocDate       = "";

        // print_r($aDataDocHDRef);
        // if( $aDataDocHDRef['tCode'] == '1' ){
        //     foreach($aDataDocHDRef['aItems'] as $aValue){
        //         // print_r($aValue);
        //         switch($aValue['FTXphRefType']){
        //             case '1':
        //                 $tPXRefIntDoc           = $aValue['FTXphRefDocNo'];
        //                 $dPXRefIntDocDate       = $aValue['FDXphRefDocDate'];
        //                 break;
        //             case '2':
        //                 $tPXRefExtDoc           = $aValue['FTXphRefDocNo'];
        //                 $dPXRefExtDocDate       = $aValue['FDXphRefDocDate'];
        //                 break;
        //         }
        //     }
        // }

        // $tPXRefIntDoc           = $aDataDocHD['FTXphRefInt'];
        // $dPXRefIntDocDate       = $aDataDocHD['FDXphRefIntDate'];
        // $tPXRefExtDoc           = $aDataDocHD['FTXphRefExt'];
        // $dPXRefExtDocDate       = $aDataDocHD['FDXphRefExtDate'];

        $tPXBchCode             = $aDataDocHD['FTBchCode'];
        $tPXBchName             = $aDataDocHD['FTBchName'];
        // $tPXUserBchCode         = $tUserBchCode;
        // $tPXUserBchName         = $tUserBchName;
        // $tPXBchCompCode         = $tBchCompCode;
        // $tPXBchCompName         = $tBchCompName;

        $tPXMerCode             = $aDataDocHD['FTMerCode'];
        $tPXMerName             = $aDataDocHD['FTMerName'];
        $tPXShopType            = $aDataDocHD['FTShpType'];
        $tPXShopCode            = $aDataDocHD['FTShpCode'];
        $tPXShopName            = $aDataDocHD['FTShpName'];
        $tPXPosCode             = $aDataDocHD['FTWahRefCode'];
        $tPXPosName             = $aDataDocHD['FTPosComName'];
        $tPXWahCode             = $aDataDocHD['FTWahCode'];
        $tPXWahName             = $aDataDocHD['FTWahName'];
        $nPXStaDocAct           = $aDataDocHD['FNXphStaDocAct'];
        $tPXFrmDocPrint         = $aDataDocHD['FNXphDocPrint'];
        $tPXFrmRmk              = $aDataDocHD['FTXphRmk'];
        $tPXSplCode             = $aDataDocHD['FTSplCode'];
        $tPXSplName             = $aDataDocHD['FTSplName'];

        $tPXCmpRteCode          = $aDataDocHD['FTRteCode'];
        $cPXRteFac              = $aDataDocHD['FCXphRteFac'];

        $tPXVatInOrEx           = $aDataDocHD['FTXphVATInOrEx'];
        $tPXSplPayMentType      = $aDataDocHD['FTXphCshOrCrd'];

        // ข้อมูลผู้จำหน่าย Supplier
        $tPXSplDstPaid          = $aDataDocHDSpl['FTXphDstPaid'];
        $tPXSplCrTerm           = $aDataDocHDSpl['FNXphCrTerm'];
        $dPXSplDueDate          = $aDataDocHDSpl['FDXphDueDate'];
        $dPXSplBillDue          = $aDataDocHDSpl['FDXphBillDue'];
        $tPXSplCtrName          = $aDataDocHDSpl['FTXphCtrName'];
        $dPXSplTnfDate          = $aDataDocHDSpl['FDXphTnfDate'];
        $tPXSplRefTnfID         = $aDataDocHDSpl['FTXphRefTnfID'];
        $tPXSplRefVehID         = $aDataDocHDSpl['FTXphRefVehID'];
        $tPXSplRefInvNo         = $aDataDocHDSpl['FTXphRefInvNo'];
        $tPXSplQtyAndTypeUnit   = $aDataDocHDSpl['FTXphQtyAndTypeUnit'];

        // ที่อยู่สำหรับการจัดส่ง
        $tPXSplShipAdd          = $aDataDocHDSpl['FNXphShipAdd'];
        $tPXSplShipAddVersion   = (!empty($aDataDocHDSpl['FTXphShipAddVersion']) ? $aDataDocHDSpl['FTXphShipAddVersion'] : '1');
        $tPXShipAddAddV1No      = (isset($aDataDocHDSpl['FTXphShipAddNo']) && !empty($aDataDocHDSpl['FTXphShipAddNo']))? $aDataDocHDSpl['FTXphShipAddNo'] : "-";
        $tPXShipAddV1Soi        = (isset($aDataDocHDSpl['FTXphShipAddSoi']) && !empty($aDataDocHDSpl['FTXphShipAddSoi']))? $aDataDocHDSpl['FTXphShipAddSoi'] : "-";
        $tPXShipAddV1Village    = (isset($aDataDocHDSpl['FTXphShipAddVillage']) && !empty($aDataDocHDSpl['FTXphShipAddVillage']))? $aDataDocHDSpl['FTXphShipAddVillage'] : "-";
        $tPXShipAddV1Road       = (isset($aDataDocHDSpl['FTXphShipAddRoad']) && !empty($aDataDocHDSpl['FTXphShipAddRoad']))? $aDataDocHDSpl['FTXphShipAddRoad'] : "-";
        $tPXShipAddV1SubDist    = (isset($aDataDocHDSpl['FTXphShipSubDistrict']) && !empty($aDataDocHDSpl['FTXphShipSubDistrict']))? $aDataDocHDSpl['FTXphShipSubDistrict'] : "-";
        $tPXShipAddV1DstCode    = (isset($aDataDocHDSpl['FTXphShipDistrict']) && !empty($aDataDocHDSpl['FTXphShipDistrict']))? $aDataDocHDSpl['FTXphShipDistrict'] : "-";
        $tPXShipAddV1PvnCode    = (isset($aDataDocHDSpl['FTXphShipProvince']) && !empty($aDataDocHDSpl['FTXphShipProvince']))? $aDataDocHDSpl['FTXphShipProvince'] : "-";
        $tPXShipAddV1PostCode   = (isset($aDataDocHDSpl['FTXphShipPosCode']) && !empty($aDataDocHDSpl['FTXphShipPosCode']))? $aDataDocHDSpl['FTXphShipPosCode'] : "-";

        // ที่อยู่สำหรับการออกใบกำกับภาษี
        $tPXSplTaxAdd           = $aDataDocHDSpl['FNXphTaxAdd'];
        $tPXSplTaxAddVersion    = (!empty($aDataDocHDSpl['FTXphTaxAddVersion']) ? $aDataDocHDSpl['FTXphTaxAddVersion'] : '1');
        $tPXTexAddAddV1No       = (isset($aDataDocHDSpl['FTXphTaxAddNo']) && !empty($aDataDocHDSpl['FTXphTaxAddNo']))? $aDataDocHDSpl['FTXphTaxAddNo'] : "-";
        $tPXTexAddV1Soi         = (isset($aDataDocHDSpl['FTXphTaxAddSoi']) && !empty($aDataDocHDSpl['FTXphTaxAddSoi']))? $aDataDocHDSpl['FTXphTaxAddSoi'] : "-";
        $tPXTexAddV1Village     = (isset($aDataDocHDSpl['FTXphTaxAddVillage']) && !empty($aDataDocHDSpl['FTXphTaxAddVillage']))? $aDataDocHDSpl['FTXphTaxAddVillage'] : "-";
        $tPXTexAddV1Road        = (isset($aDataDocHDSpl['FTXphTaxAddRoad']) && !empty($aDataDocHDSpl['FTXphTaxAddRoad']))? $aDataDocHDSpl['FTXphTaxAddRoad'] : "-";
        $tPXTexAddV1SubDist     = (isset($aDataDocHDSpl['FTXphTaxSubDistrict']) && !empty($aDataDocHDSpl['FTXphTaxSubDistrict']))? $aDataDocHDSpl['FTXphTaxSubDistrict'] : "-";
        $tPXTexAddV1DstCode     = (isset($aDataDocHDSpl['FTXphTaxDistrict']) && !empty($aDataDocHDSpl['FTXphTaxDistrict']))? $aDataDocHDSpl['FTXphTaxDistrict'] : "-";
        $tPXTexAddV1PvnCode     = (isset($aDataDocHDSpl['FTXphTaxProvince']) && !empty($aDataDocHDSpl['FTXphTaxProvince']))? $aDataDocHDSpl['FTXphTaxProvince'] : "-";
        $tPXTexAddV1PostCode    = (isset($aDataDocHDSpl['FTXphTaxPosCode']) && !empty($aDataDocHDSpl['FTXphTaxPosCode']))? $aDataDocHDSpl['FTXphTaxPosCode'] : "-";

        $tPXVatCodeBySPL        = $aDetailSPL['FTVatCode'];
        $tPXVatRateBySPL        = $aDetailSPL['FCXpdVatRate'];

        $tPXAgnCode             = $aDataDocHD['FTAgnCode'];
        $tPXAgnName             = $aDataDocHD['FTAgnName'];

        $tPXStaRef              = $aDataDocHD['FNXphStaRef'];
        $nStaUploadFile        = 2;

    }else{
        $tPXRoute               = "docPXEventAdd";
        $nPXAutStaEdit          = 0;
        $tPXDocNo               = "";
        $dPXDocDate             = "";
        $dPXDocTime             = date("H:i:s");
        $tPXCreateBy            = $this->session->userdata('tSesUsrUsername');
        $tPXUsrNameCreateBy     = $this->session->userdata('tSesUsrUsername');

        $tPXStaRefund           = 1;
        $tPXStaDoc              = 1;
        $tPXStaApv              = NULL;
        // $tPXStaPrcStk           = NULL;
        $tPXStaDelMQ            = NULL;
        $tPXStaPaid             = 1;

        $tPXSesUsrBchCode       = $this->session->userdata("tSesUsrBchCode");
        $tPXDptCode             = $tDptCode;
        $tPXUsrCode             = $this->session->userdata('tSesUsername');
        $tPXLangEdit            = $this->session->userdata("tLangEdit");

        $tPXApvCode             = "";
        $tPXUsrNameApv          = "";
        $tPXRefPoDoc            = "";
        // $tPXRefIntDoc           = "";
        // $dPXRefIntDocDate       = "";
        // $tPXRefExtDoc           = "";
        // $dPXRefExtDocDate       = "";

        // $tPXBchCode             = $tBchCode;
        // $tPXBchName             = $tBchName;
        $tPXBchCode             = $this->session->userdata('tSesUsrBchCodeDefault');
        $tPXBchName             = $this->session->userdata('tSesUsrBchNameDefault');
        // $tPXUserBchCode         = $tBchCode;
        // $tPXUserBchName         = $tBchName;
        // $tPXBchCompCode         = $tBchCompCode;
        // $tPXBchCompName         = $tBchCompName;
        $tPXMerCode             = "";
        $tPXMerName             = "";
        // $tPXShopType            = $tShopType;
        $tPXShopCode            = "";
        $tPXShopName            = "";
        $tPXPosCode             = "";
        $tPXPosName             = "";
        $tPXWahCode             = $this->session->userdata("tSesUsrWahCode");
        $tPXWahName             = $this->session->userdata("tSesUsrWahName");
        $nPXStaDocAct           = 1;
        $tPXFrmDocPrint         = 0;
        $tPXFrmRmk              = "";
        $tPXSplCode             = "";
        $tPXSplName             = "";

        $tPXCmpRteCode          = $tCmpRteCode;
        $cPXRteFac              = $cXthRteFac;

        $tPXVatInOrEx           = $tCmpRetInOrEx;
        $tPXSplPayMentType      = "";

        // ข้อมูลผู้จำหน่าย Supplier
        $tPXSplDstPaid          = "";
        $tPXSplCrTerm           = "";
        $dPXSplDueDate          = "";
        $dPXSplBillDue          = "";
        $tPXSplCtrName          = "";
        $dPXSplTnfDate          = "";
        $tPXSplRefTnfID         = "";
        $tPXSplRefVehID         = "";
        $tPXSplRefInvNo         = "";
        $tPXSplQtyAndTypeUnit   = "";

        // ที่อยู่สำหรับการจัดส่ง
        $tPXSplShipAdd          = "";
        $tPXShipAddAddV1No      = "-";
        $tPXShipAddV1Soi        = "-";
        $tPXShipAddV1Village    = "-";
        $tPXShipAddV1Road       = "-";
        $tPXShipAddV1SubDist    = "-";
        $tPXShipAddV1DstCode    = "-";
        $tPXShipAddV1PvnCode    = "-";
        $tPXShipAddV1PostCode   = "-";

        // ที่อยู่สำหรับการออกใบกำกับภาษี
        $tPXSplTaxAdd           = "";
        $tPXTexAddAddV1No       = "-";
        $tPXTexAddV1Soi         = "-";
        $tPXTexAddV1Village     = "-";
        $tPXTexAddV1Road        = "-";
        $tPXTexAddV1SubDist     = "-";
        $tPXTexAddV1DstCode     = "-";
        $tPXTexAddV1PvnCode     = "-";
        $tPXTexAddV1PostCode    = "-";
        $tPXVatCodeBySPL        = "";
        $tPXVatRateBySPL        = "";

        $tPXSplShipAddVersion   = "1";
        $tPXSplTaxAddVersion    = "1";

        $tPXAgnCode             = $this->session->userdata("tSesUsrAgnCode");
        $tPXAgnName             = $this->session->userdata("tSesUsrAgnName");

        $tPXStaRef              = "";
        $nStaUploadFile        = 1;
    }
?>
<form id="ofmPXFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <input type="hidden" id="ohdPXStaImport" name="ohdPXStaImport" value="0">
    <input type="hidden" id="ohdPXRoute" name="ohdPXRoute" value="<?php echo $tPXRoute;?>">
    <input type="hidden" id="ohdPXCheckClearValidate" name="ohdPXCheckClearValidate" value="0">
    <input type="hidden" id="ohdPXCheckSubmitByButton" name="ohdPXCheckSubmitByButton" value="0">
    <input type="hidden" id="ohdPXAutStaEdit" name="ohdPXAutStaEdit" value="<?php echo $nPXAutStaEdit;?>">
    <input type="hidden" id="ohdPXDecimalShow" name="ohdPXDecimalShow" value="<?=$nOptDecimalShow?>">

    <input type="hidden" id="ohdPXStaRefund" name="ohdPXStaRefund" value="<?php echo $tPXStaRefund;?>">
    <input type="hidden" id="ohdPXStaDoc" name="ohdPXStaDoc" value="<?php echo $tPXStaDoc;?>">
    <input type="hidden" id="ohdPXStaApv" name="ohdPXStaApv" value="<?php echo $tPXStaApv;?>">
    <input type="hidden" id="ohdPXStaDelMQ" name="ohdPXStaDelMQ" value="<?php echo $tPXStaDelMQ; ?>">
    <!-- <input type="hidden" id="ohdPXStaPrcStk" name="ohdPXStaPrcStk" value="<?php echo $tPXStaPrcStk;?>"> -->
    <input type="hidden" id="ohdPXStaPaid" name="ohdPXStaPaid" value="<?php echo $tPXStaPaid;?>">

    <input type="hidden" id="ohdPXSesUsrBchCode" name="ohdPXSesUsrBchCode" value="<?php echo $tPXSesUsrBchCode; ?>">
    <input type="hidden" id="ohdPXBchCode" name="ohdPXBchCode" value="<?php echo $tPXBchCode; ?>">
    <input type="hidden" id="ohdPXDptCode" name="ohdPXDptCode" value="<?php echo $tPXDptCode;?>">
    <input type="hidden" id="ohdPXUsrCode" name="ohdPXUsrCode" value="<?php echo $tPXUsrCode?>">

    <input type="hidden" id="ohdPXCmpRteCode" name="ohdPXCmpRteCode" value="<?php echo $tPXCmpRteCode;?>">
    <input type="hidden" id="ohdPXRteFac" name="ohdPXRteFac" value="<?php echo $cPXRteFac;?>">

    <input type="hidden" id="ohdPXApvCodeUsrLogin" name="ohdPXApvCodeUsrLogin" value="<?php echo $tPXUsrCode; ?>">
    <input type="hidden" id="ohdPXLangEdit" name="ohdPXLangEdit" value="<?php echo $tPXLangEdit; ?>">
    <input type="hidden" id="ohdPXOptAlwSaveQty" name="ohdPXOptAlwSaveQty" value="<?php echo $nOptDocSave?>">
    <input type="hidden" id="ohdPXOptScanSku" name="ohdPXOptScanSku" value="<?php echo $nOptScanSku?>">


    <input type="hidden" id="ohdSesSessionID" name="ohdSesSessionID" value="<?=$this->session->userdata('tSesSessionID')?>">
    <input type="hidden" id="ohdSesUsrLevel" name="ohdSesUsrLevel" value="<?=$this->session->userdata('tSesUsrLevel')?>">
    <input type="hidden" id="ohdSesUsrBchCom" name="ohdSesUsrBchCom" value="<?=$this->session->userdata('tSesUsrBchCom')?>">
    <input type="hidden" id="ohdPXCmpRetInOrEx" name="ohdPXCmpRetInOrEx" value="<?=$tCmpRetInOrEx?>">
    <input type="hidden" id="ohdPXVatRate" name="ohdPXVatRate" value="<?=$cVatRate?>">
    <input type="hidden" id="ohdPXValidatePdtImp" name="ohdPXValidatePdtImp" value="<?=language('document/purchaseorder/purchaseorder', 'tPONotFoundPdtCodeAndBarcodeImpList')?>">
    <input type="hidden" id="ohdPXValidatePdt" name="ohdPXValidatePdt" value="<?=language('document/purchaseorder/purchaseorder', 'tPOPleaseSeletedPDTIntoTable')?>">

    <input type="hidden" id="ohdPXRemark" name="ohdPXRemark" value="<?=$tPXFrmRmk?>">

    <button style="display:none" type="submit" id="obtPXSubmitDocument" onclick="JSxPXAddEditDocument()"></button>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPXHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmStatus'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvPXDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPXDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmAppove');?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/expenserecord/expenserecord','tPXLabelAutoGenCode'); ?></label>
                                <?php if(isset($tPXDocNo) && empty($tPXDocNo)):?>
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" id="ocbPXStaAutoGenCode" name="ocbPXStaAutoGenCode" maxlength="1" checked="checked">
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmAutoGenCode');?></span>
                                    </label>
                                </div>
                                <?php endif;?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input
                                        type="text"
                                        class="form-control xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai"
                                        id="oetPXDocNo"
                                        name="oetPXDocNo"
                                        maxlength="20"
                                        value="<?php echo $tPXDocNo;?>"
                                        data-validate-required="<?php echo language('document/expenserecord/expenserecord','tPXPlsEnterOrRunDocNo'); ?>"
                                        data-validate-duplicate="<?php echo language('document/expenserecord/expenserecord','tPXPlsDocNoDuplicate'); ?>"
                                        placeholder="<?php echo language('document/expenserecord/expenserecord','tPXLabelFrmDocNo');?>"
                                        style="pointer-events:none"
                                        readonly
                                    >
                                    <input type="hidden" id="ohdPXCheckDuplicateCode" name="ohdPXCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmDocDate');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNDatePicker xCNInputMaskDate xWPXDisabledOnApv"
                                            id="oetPXDocDate"
                                            name="oetPXDocDate"
                                            value="<?php echo $dPXDocDate; ?>"
                                            data-validate-required="<?php echo language('document/expenserecord/expenserecord','tPXPlsEnterDocDate'); ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPXDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmDocTime');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNTimePicker xCNInputMaskTime xWPXDisabledOnApv"
                                            id="oetPXDocTime"
                                            name="oetPXDocTime"
                                            value="<?php echo $dPXDocTime; ?>"
                                            data-validate-required="<?php echo language('document/expenserecord/expenserecord', 'tPXPlsEnterDocTime');?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPXDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmCreateBy');?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdPXCreateBy" name="ohdPXCreateBy" value="<?php echo $tPXCreateBy?>">
                                            <label><?php echo $tPXUsrNameCreateBy?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                                if($tPXRoute == "docPXEventAdd"){
                                                    $tPXLabelStaDoc  = language('document/expenserecord/expenserecord', 'tPXLabelFrmValStaDoc');
                                                }else{
                                                    $tPXLabelStaDoc  = language('document/expenserecord/expenserecord', 'tPXLabelFrmValStaDoc'.$tPXStaDoc);
                                                }
                                            ?>
                                            <label><?php echo $tPXLabelStaDoc;?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmValStaApv'.$tPXStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <?php if(isset($tPXDocNo) && !empty($tPXDocNo)):?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdPXApvCode" name="ohdPXApvCode" maxlength="20" value="<?php echo $tPXApvCode?>">
                                                <label>
                                                    <?php echo (isset($tPXUsrNameApv) && !empty($tPXUsrNameApv))? $tPXUsrNameApv : "-" ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel เงื่อนไขเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPXConditionDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmConditionDoc'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvPXDataConditionDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPXDataConditionDoc" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                <!-- Condition ตัวแทนขาย -->
                                <div class="form-group m-b-0">

                                    <?php
                                        if( $tPXRoute == "docPXEventAdd" ){
                                            $tDisabledAgn           = '';
                                        }else{
                                            $tDisabledAgn           = 'disabled';
                                        }
                                    ?>
                                    <script>
                                        var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                                        if( tUsrLevel != "HQ" ){
                                            $('#obtBrowseTWOBCH').attr('disabled',true);
                                        }
                                    </script>

                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tAgency')?></label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                                id="oetPXAgnCode"
                                                name="oetPXAgnCode"
                                                maxlength="5"
                                                value="<?=$tPXAgnCode?>"
                                            >
                                            <input
                                                type="text"
                                                class="form-control xWPointerEventNone"
                                                id="oetPXAgnName"
                                                name="oetPXAgnName"
                                                maxlength="100"
                                                placeholder="<?php echo language('common/main/main', 'tAgency')?>"
                                                value="<?=$tPXAgnName?>"
                                                readonly
                                            >
                                            <span class="input-group-btn">
                                                <button id="obtPXBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn xWPXDisabledOnApv" <?=$tDisabledAgn?> >
                                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <!-- Condition สาขา -->
                                <div class="form-group m-b-0">

                                    <?php
                                        if($tPXRoute == "docPXEventAdd"){
                                            // $tPXDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                                            // $tPXDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                                            $tDisabledBch           = '';
                                        }else{
                                            // $tPXDataInputBchCode    = $tPXBchCode;
                                            // $tPXDataInputBchName    = $tPXBchName;
                                            $tDisabledBch           = 'disabled';
                                        }
                                    ?>
                                    <script>
                                        var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                                        if( tUsrLevel != "HQ" ){
                                            //BCH - SHP
                                            var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount")?>';
                                            if(tBchCount < 2){
                                                $('#obtBrowseTWOBCH').attr('disabled',true);
                                            }
                                        }
                                    </script>

                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmBranch')?></label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                                id="oetPXFrmBchCode"
                                                name="oetPXFrmBchCode"
                                                maxlength="5"
                                                value="<?=$tPXBchCode?>"
                                            >
                                            <input
                                                type="text"
                                                class="form-control xWPointerEventNone"
                                                id="oetPXFrmBchName"
                                                name="oetPXFrmBchName"
                                                maxlength="100"
                                                placeholder="<?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmBranch')?>"
                                                value="<?=$tPXBchName?>"
                                                readonly
                                            >
                                            <span class="input-group-btn">
                                                <button id="obtBrowseTWOBCH" type="button" class="btn xCNBtnBrowseAddOn xWPXDisabledOnApv" <?=$tDisabledBch?>>
                                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                <!-- Condition กลุ่มธุรกิจ -->
                                <!-- <div class="form-group <?php if( !FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmMerchant');?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNHide" id="oetPXFrmMerCode" name="oetPXFrmMerCode" maxlength="5" value="<?php echo $tPXMerCode;?>">
                                        <input type="text" class="form-control xWPointerEventNone" id="oetPXFrmMerName" name="oetPXFrmMerName" value="<?php echo $tPXMerName;?>" readonly placeholder="<?php echo language('document/expenserecord/expenserecord','tPXLabelFrmMerchant');?>">
                                        <?php
                                            // $tDisabledBtnMerchant = "";
                                            // if($tPXRoute == "dcmPXEventAdd"){
                                            //     if($tSesUsrLevel == "SHP"){
                                            //         $tDisabledBtnMerchant = "disabled";
                                            //     }
                                            // }else{
                                            //     if($tSesUsrLevel == "SHP"){
                                            //         $tDisabledBtnMerchant = "disabled";
                                            //     }
                                            // }
                                        ?>
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtPXBrowseMerchant" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div> -->
                                <!-- Condition ร้านค้า -->
                                <!-- <div class="form-group <?php if( !FCNbGetIsShpEnabled()) : echo 'xCNHide';  endif;?>">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmShop');?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNHide" id="oetPXFrmShpCode" name="oetPXFrmShpCode" placeholder="<?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmShop')?>" maxlength="5" value="<?php echo $tPXShopCode;?>">
                                        <input type="text" class="form-control xWPointerEventNone" id="oetPXFrmShpName" name="oetPXFrmShpName" placeholder="<?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmShop')?>"  value="<?php echo $tPXShopName;?>" readonly>
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtPXBrowseShop" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div> -->
                                <!-- Condition เครื่องจุดขาย -->
                                <!-- <div class="form-group xCNHide">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmPos');?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNHide" id="oetPXFrmPosCode" name="oetPXFrmPosCode" maxlength="5" value="<?php echo $tPXPosCode;?>">
                                        <input type="text" class="form-control xWPointerEventNone" id="oetPXFrmPosName" name="oetPXFrmPosName" value="<?php echo $tPXPosName;?>" readonly>
                                        <?php
                                            $tDisabledBtnPos    = "";
                                            if($tPXRoute == "docPXEventAdd"){
                                                $tDisabledBtnPos    = "disabled";
                                            }else{
                                                if($tSesUsrLevel == "SHP"){
                                                    $tDisabledBtnPos    = "disabled";
                                                }else{
                                                    if(empty($tPXPosCode)){
                                                        $tDisabledBtnPos    = "disabled";
                                                    }
                                                }
                                            }
                                        ?>
                                        <span class="xWConditionSearchPdt input-group-btn <?php echo $tDisabledBtnPos;?>">
                                            <button id="obtPXBrowsePos" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn <?php echo $tDisabledBtnPos;?>">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div> -->
                                <!-- Condition คลังสินค้า -->
                                <!-- <div class="form-group">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span> <?php echo language('document/expenserecord/expenserecord','tPXLabelFrmWah');?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNHide" id="oetPXFrmWahCode" name="oetPXFrmWahCode" maxlength="5" value="<?php echo $tPXWahCode;?>">
                                        <input
                                            type="text"
                                            class="form-control xWPointerEventNone"
                                            id="oetPXFrmWahName"
                                            name="oetPXFrmWahName"
                                            value="<?php echo $tPXWahName;?>"
                                            placeholder="<?=language('document/expenserecord/expenserecord','tPXLabelFrmWah');?>"
                                            data-validate-required="<?php echo language('document/expenserecord/expenserecord','tPXPlsEnterWah'); ?>"
                                            readonly
                                        >
                                        <?php
                                            $tDisabledBtnWah    = "";
                                        ?>
                                        <span class="xWConditionSearchPdt input-group-btn <?php echo $tDisabledBtnWah;?>">
                                            <button id="obtPXBrowseWahouse" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn <?php echo $tDisabledBtnWah;?>">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Supplier Info -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPXSupplierInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoDoc');?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvPXDataSupplierInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPXDataSupplierInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div id="odvRowPanelSplInfo" class="row"  style="max-height:350px;overflow-x:auto">
                        <!-- <div class="row"> -->
                            <div class="col-xs-12 col-sm-12 col-col-md-12 col-lg-12">
                                <!-- ประเภทภาษี -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoVatInOrEx');?></label>
                                    <?php
                                        switch($tPXVatInOrEx){
                                            case '1':
                                                $tOptionVatIn   = "selected";
                                                $tOptionVatEx   = "";
                                            break;
                                            case '2':
                                                $tOptionVatIn   = "";
                                                $tOptionVatEx   = "selected";
                                            break;
                                            default:
                                                $tOptionVatIn   = "selected";
                                                $tOptionVatEx   = "";
                                        }
                                    ?>
                                    <select class="selectpicker form-control xWPXDisabledOnApv" id="ocmPXFrmSplInfoVatInOrEx" name="ocmPXFrmSplInfoVatInOrEx" maxlength="1">
                                        <option value="1" <?php echo @$tOptionVatIn;?>><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoVatInclusive');?></option>
                                        <option value="2" <?php echo @$tOptionVatEx;?>><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoVatExclusive');?></option>
                                    </select>
                                </div>
                                <input type="hidden" id="ohdPXFrmSplVatRate" name="ohdPXFrmSplVatRate" value="<?=$tPXVatRateBySPL?>">
                                <input type="hidden" id="ohdPXFrmSplVatCode" name="ohdPXFrmSplVatCode" value="<?=$tPXVatCodeBySPL?>">

                                <!-- ประเภทการชำระ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoPaymentType');?></label>
                                    <select class="selectpicker form-control xWPXDisabledOnApv" id="ocmPXFrmSplInfoPaymentType" name="ocmPXFrmSplInfoPaymentType" maxlength="1" >
                                        <option value="1" <?php if(@$tPXSplPayMentType=='1'){ echo 'selected'; } ?>><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoPaymentType1');?></option>
                                        <option value="2" <?php if(@$tPXSplPayMentType=='2'){ echo 'selected'; } ?>><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoPaymentType2');?></option>
                                    </select>
                                </div>
                                <!-- วิธีการชำระเงิน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoDstPaid');?></label>
                                    <select class="selectpicker form-control xWPXDisabledOnApv" id="ocmPXFrmSplInfoDstPaid" name="ocmPXFrmSplInfoDstPaid" maxlength="1" >
                                        <option value="1" <?php if(@$tPXSplDstPaid=='1'){ echo 'selected'; } ?>><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoDstPaid1');?></option>
                                        <option value="2" <?php if(@$tPXSplDstPaid=='2'){ echo 'selected'; } ?>><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoDstPaid2');?></option>
                                    </select>
                                </div>
                                <!-- ระยะเครดิต -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoCrTerm');?></label>
                                    <input
                                        type="text"
                                        class="form-control text-right xCNInputNumericWithoutDecimal xWPXDisabledOnApv"
                                        id="oetPXFrmSplInfoCrTerm"
                                        name="oetPXFrmSplInfoCrTerm"
                                        value="<?php echo $tPXSplCrTerm;?>"
                                    >
                                </div>
                                <!-- วันครบกำหนดชำระเงิน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoDueDate');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNDatePicker xCNInputMaskDate xWPXDisabledOnApv"
                                            id="oetPXFrmSplInfoDueDate"
                                            name="oetPXFrmSplInfoDueDate"
                                            placeholder="YYYY-MM-DD"
                                            value="<?php echo $dPXSplDueDate;?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPXFrmSplInfoDueDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- วันวางบิล -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoBillDue');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNDatePicker xCNInputMaskDate xWPXDisabledOnApv"
                                            id="oetPXFrmSplInfoBillDue"
                                            name="oetPXFrmSplInfoBillDue"
                                            placeholder="YYYY-MM-DD"
                                            value="<?php echo $dPXSplBillDue;?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPXFrmSplInfoBillDue" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- วันที่ขนส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoTnfDate');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xCNDatePicker xCNInputMaskDate xWPXDisabledOnApv"
                                            id="oetPXFrmSplInfoTnfDate"
                                            name="oetPXFrmSplInfoTnfDate"
                                            placeholder="YYYY-MM-DD"
                                            value="<?php echo $dPXSplTnfDate;?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPXFrmSplInfoTnfDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ชื่อผู้ติดต่อ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoCtrName');?></label>
                                    <input
                                        type="text"
                                        class="form-control xWPXDisabledOnApv"
                                        id="oetPXFrmSplInfoCtrName"
                                        name="oetPXFrmSplInfoCtrName"
                                        value="<?php echo $tPXSplCtrName;?>"
                                    >
                                </div>
                                <!-- เลขที่ขนส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoRefTnfID');?></label>
                                    <input
                                        type="text"
                                        class="form-control xWPXDisabledOnApv"
                                        id="oetPXFrmSplInfoRefTnfID"
                                        name="oetPXFrmSplInfoRefTnfID"
                                        value="<?php echo $tPXSplRefTnfID;?>"
                                    >
                                </div>
                                <!-- อ้างอิงเลขที่ขนส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoRefVehID');?></label>
                                    <input
                                        type="text"
                                        class="form-control xWPXDisabledOnApv"
                                        id="oetPXFrmSplInfoRefVehID"
                                        name="oetPXFrmSplInfoRefVehID"
                                        value="<?php echo $tPXSplRefVehID;?>"
                                    >
                                </div>
                                <!-- เลขที่บัญชีราคาสินค้า -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoRefInvNo');?></label>
                                    <input
                                        type="text"
                                        class="form-control xWPXDisabledOnApv"
                                        id="oetPXFrmSplInfoRefInvNo"
                                        name="oetPXFrmSplInfoRefInvNo"
                                        value="<?php echo $tPXSplRefInvNo;?>"
                                    >
                                </div>
                                <!-- จำนวนและลักษณะหีบห่อ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoQtyAndTypeUnit');?></label>
                                    <input
                                        type="text"
                                        class="form-control xWPXDisabledOnApv"
                                        id="oetPXFrmSplInfoQtyAndTypeUnit"
                                        name="oetPXFrmSplInfoQtyAndTypeUnit"
                                        value="<?php echo $tPXSplQtyAndTypeUnit;?>"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- </div> -->
                        <div id="odvRowPanelBtnGrpSplInfo" class="row" style="padding-top:20px;">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <input type="hidden" id="ohdPXFrmShipAdd" name="ohdPXFrmShipAdd" value="<?php echo $tPXSplShipAdd;?>">
                                <!-- <button type="button" id="obtPXFrmBrowseShipAdd" class="btn btn-primary xWPXDisabledOnApv" style="width:100%;">
                                    +&nbsp;<?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoShipAddress');?>
                                </button> -->
                                <button id="obtPXFrmBrowseShipAdd" class="btn xCNBTNSubSave" style="width:100%">+&nbsp;<?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoShipAddress');?></button>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
                                <input type="hidden" id="ohdPXFrmTaxAdd" name="ohdPXFrmTaxAdd" value="<?php echo $tPXSplTaxAdd;?>">
                                <!-- <button type="button" id="obtPXFrmBrowseTaxAdd" class="btn btn-primary xWPXDisabledOnApv" style="width:100%;">
                                    +&nbsp;<?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoTaxAddress');?>
                                </button> -->
                                <button id="obtPXFrmBrowseTaxAdd" class="btn xCNBTNSubSave" style="width:100%">+&nbsp;<?php echo language('document/expenserecord/expenserecord','tPXLabelFrmSplInfoTaxAddress');?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel อืนๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPXInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmInfoOth');?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvPXDataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPXDataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <!-- <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" value="1" id="ocbPXFrmInfoOthStaDocAct" name="ocbPXFrmInfoOthStaDocAct" maxlength="1" <?php echo ($nPXStaDocAct == '1' || empty($nPXStaDocAct)) ? 'checked' : ''; ?>>
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div> -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tStaDocAct'); ?></label>
                                    <select class="selectpicker form-control" id="ocbPXFrmInfoOthStaDocAct" name="ocbPXFrmInfoOthStaDocAct">
                                        <option value='1' <?php echo ($nPXStaDocAct == '1') ? 'selected' : ''; ?> ><?php echo language('common/main/main', 'tStaDocActMove'); ?></option>
                                        <option value='0' <?php echo ($nPXStaDocAct == '0') ? 'selected' : ''; ?> ><?php echo language('common/main/main', 'tStaDocActNotMoving'); ?></option>
                                    </select>
                                </div>

                                <!-- สถานะอ้างอิง -->
                                <!-- <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmInfoOthRef');?></label>
                                    <select class="selectpicker form-control xWPXDisabledOnApv" id="ocmPXFrmInfoOthRef" name="ocmPXFrmInfoOthRef" maxlength="1" >
                                        <option value="0" <?php if( empty($tPXStaRef) ){ echo "selected"; } ?>><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmInfoOthRef0');?></option>
                                        <option value="1" <?php if( $tPXStaRef == '1' ){ echo "selected"; } ?>><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmInfoOthRef1');?></option>
                                        <option value="2" <?php if( $tPXStaRef == '2' ){ echo "selected"; } ?>><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmInfoOthRef2');?></option>
                                    </select>
                                </div> -->
                                <!-- จำนวนครั้งที่พิมพ์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmInfoOthDocPrint');?></label>
                                    <input
                                        type="text"
                                        class="form-control text-right"
                                        id="ocmPXFrmInfoOthDocPrint"
                                        name="ocmPXFrmInfoOthDocPrint"
                                        value="<?php echo $tPXFrmDocPrint;?>"
                                        readonly
                                    >
                                </div>
                                <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmInfoOthReAddPdt');?></label>
                                    <select class="form-control selectpicker xWPXDisabledOnApv" id="ocmPXFrmInfoOthReAddPdt" name="ocmPXFrmInfoOthReAddPdt">
                                        <option value="1" selected><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmInfoOthReAddPdt1');?></option>
                                        <option value="2"><?php echo language('document/expenserecord/expenserecord', 'tPXLabelFrmInfoOthReAddPdt2');?></option>
                                    </select>
                                </div>
                                <!-- หมายเหตุ -->
                                <!-- <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXLabelFrmInfoOthRemark');?></label>
                                    <textarea
                                        class="form-control xWPXDisabledOnApv"
                                        id="otaPXFrmInfoOthRmk"
                                        name="otaPXFrmInfoOthRmk"
                                        rows="10"
                                        maxlength="200"
                                        style="resize: none;height:86px;"
                                    ><?php echo $tPXFrmRmk?></textarea>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel ไฟลแนบ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPXReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'ไฟล์แนบ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvPXDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPXDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvPXShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>


                    var oPXCallDataTableFile = {
                        ptElementID     : 'odvPXShowDataTable',
                        ptBchCode       : $('#oetPXFrmBchCode').val(),
                        ptDocNo         : $('#oetPXDocNo').val(),
                        ptDocKey        : 'TAPTPxHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdPXStaApv').val(),
                        ptStaDoc        : $('#ohdPXStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oPXCallDataTableFile);
                </script>
            </div>

        </div>

        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row">

                <!-- ตารางรายการสินค้า -->
                <div id="odvPXDataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">

                                <div id="odvPdtRowNavMenu" class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                            <ul class="nav" role="tablist">

                                                <!-- สินค้า -->
                                                <li id="oliPXContentProduct" class="xWMenu active xCNStaHideShow" data-menutype="MN">
                                                    <a role="tab" data-toggle="tab" data-target="#odvPXContentProduct" aria-expanded="true"><?php echo language('document/expenserecord/expenserecord', 'ข้อมูลสินค้า') ?></a>
                                                </li>

                                                <!-- อ้างอิง -->
                                                <li id="oliPXContentHDRef" class="xWMenu xWSubTab xCNStaHideShow" data-menutype="FHN">
                                                    <a role="tab" data-toggle="tab" data-target="#odvPXContentHDRef" aria-expanded="false"><?php echo language('document/expenserecord/expenserecord', 'เอกสารอ้างอิง') ?></a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content">

                                    <div id="odvPXContentProduct" class="tab-pane fade active in" style="padding: 0px !important;">
                                        <div class="row p-t-15">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXTBSpl');?></label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xCNHide" id="oetPXFrmSplCode" name="oetPXFrmSplCode" value="<?php echo $tPXSplCode;?>">
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="oetPXFrmSplName"
                                                            name="oetPXFrmSplName"
                                                            value="<?php echo $tPXSplName;?>"
                                                            placeholder="<?php echo language('document/expenserecord/expenserecord','tPXMsgValidSplCode') ?>"
                                                            readonly
                                                        >
                                                        <span class="input-group-btn">
                                                            <button id="obtPXBrowseSupplier" type="button" class="btn xCNBtnBrowseAddOn">
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

                                                     <!--ค้นหา-->
                                                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetPXFrmFilterPdtHTML" name="oetPXFrmFilterPdtHTML" onkeyup="JSvDOCSearchPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                                                        <span class="input-group-btn">
                                                            <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvDOCSearchPdtHTML()">
                                                                <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                                            </button>
                                                        </span>
                                                    
                                                        <!-- <input
                                                            type="text"
                                                            class="form-control"
                                                            maxlength="100"
                                                            id="oetPXFrmFilterPdtHTML"
                                                            name="oetPXFrmFilterPdtHTML"
                                                            placeholder="<?php echo language('document/expenserecord/expenserecord','tPXFrmFilterTablePdt');?>"
                                                            onkeyup="javascript:if(event.keyCode==13) JSvPXDOCFilterPdtInTableTemp()"
                                                        >
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            maxlength="100"
                                                            id="oetPXFrmSearchAndAddPdtHTML"
                                                            name="oetPXFrmSearchAndAddPdtHTML"
                                                            onkeyup="Javascript:if(event.keyCode==13) JSxPXChkConditionSearchAndAddPdt()"
                                                            placeholder="<?php echo language('document/expenserecord/expenserecord','tPXFrmSearchAndAddPdt');?>"
                                                            style="display:none;"
                                                            data-validate="<?php echo language('document/expenserecord/expenserecord','tPXMsgValidScanNotFoundBarCode');?>"
                                                        > -->
                                                        <!-- <span class="input-group-btn">
                                                            <div id="odvPXSearchAndScanBtnGrp" class="xCNDropDrownGroup input-group-append">
                                                                <button id="obtPXMngPdtIconSearch" type="button" class="btn xCNBTNMngTable xCNBtnDocSchAndScan" onclick="JSvPXDOCFilterPdtInTableTemp()">
                                                                    <i class="fa fa-filter" style="width:20px;"></i>
                                                                </button> -->
                                                                <!-- <button id="obtPXMngPdtIconScan" type="button" class="btn xCNBTNMngTable xCNBtnDocSchAndScan" style="display:none;" onclick="JSxPXChkConditionSearchAndAddPdt()">
                                                                    <i class="fa fa-search" style="width:20px;"></i>
                                                                </button> -->
                                                                <!-- <button type="button" class="btn xCNDocDrpDwn xCNBtnDocSchAndScan" data-toggle="dropdown" style="display:none;">
                                                                    <i class="fa fa-chevron-down f-s-14 t-plus-1" style="font-size: 12px;"></i>
                                                                </button> -->
                                                                <!-- <ul class="dropdown-menu" role="menu">
                                                                    <li>
                                                                        <a id="oliPXMngPdtSearch"><label><?php echo language('document/expenserecord/expenserecord','tPXFrmFilterTablePdt'); ?></label></a>
                                                                        <a id="oliPXMngPdtScan"><?php echo language('document/expenserecord/expenserecord','tPXFrmSearchAndAddPdt'); ?></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </span> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right">
                                                <div id="odvPXMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup" style="margin-bottom:10px;">
                                                    <button type="button" class="btn xCNBTNMngTable xWDropdown" data-toggle="dropdown">
                                                        <?php echo language('common/main/main','tCMNOption')?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li id="oliPXBtnDeleteMulti" class="disabled">
                                                            <a data-toggle="modal" data-target="#odvPXModalDelPdtInDTTempMultiple"><?php echo language('common/main/main','tDelAll')?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right">
                                                <!--ค้นหาจากบาร์โค๊ด-->
                                                <div class="form-group">
                                                    <input type="text" class="form-control xCNPdtEditInLine" id="oetPXInsertBarcode"  autocomplete="off" name="oetPXInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);"  placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า" >
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
                                                <div style="margin-top:-2px;">
                                                    <button type="button" id="obtPXDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt">+</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-t-10" id="odvPXDataPdtTableDTTemp"></div>

                                        <?php include('wExpenseRecordEndOfBill.php');?>
                                    </div>

                                    <div id="odvPXContentHDRef" class="tab-pane fade" style="padding: 0px !important;">
                                        <div class="row p-t-15">

                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                                <div style="margin-top:-2px;">
                                                    <button type="button" id="obtPXAddDocRef" class="xCNBTNPrimeryPlus xCNDocBrowsePdt">+</button>
                                                </div>
                                            </div>

                                            <div id="odvPXTableHDRef"></div>

                                        <div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row">

                <div id="odvPXPanelHDDocRef" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;">
                        <div id="odvPXConditionDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <label class="xCNTextDetail1"><?php echo language('document/expenserecord/expenserecord', 'อ้างอิงเอกสาร'); ?></label>
                        </div>
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <table class="table xWPdtTableFont">
                                    <thead>
                                        <tr class="xCNCenter">
                                            <th nowrap style="width:20%"><?php echo language('document/expenserecord/expenserecord','ประเภทอ้างอิง')?></th>
                                            <th nowrap><?php echo language('document/expenserecord/expenserecord','เลขที่เอกสารอ้างอิง')?></th>
                                            <th nowrap style="width:20%"><?php echo language('document/expenserecord/expenserecord','วันที่เอกสารอ้างอิง')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if( $aDataDocHDRef['tCode'] == '1' ){
                                                foreach($aDataDocHDRef['aItems'] as $aValue){
                                        ?>
                                                    <tr>
                                                        <td nowrap><?=language('document/document/document','tDocRefType'.$aValue['FTXphRefType'])?></td>
                                                        <td nowrap><?=$aValue['FTXphRefDocNo']?></td>
                                                        <td nowrap class="text-center"><?=date_format(date_create($aValue['FDXphRefDocDate']),'Y-m-d')?></td>
                                                    </tr>
                                        <?php
                                                }
                                            }else{
                                        ?>
                                                <tr><td class="text-center xCNTextDetail2" colspan="100%"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->


    </div>
</form>

<!-- =================================================================== View Modal Shipping Purchase Invoice  =================================================================== -->
    <div id="odvPXBrowseShipAdd" class="modal fade">
        <div class="modal-dialog" style="width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/expenserecord/expenserecord','tPXShipAddress'); ?></label>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                            <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSnPXShipAddData()"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                            <button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
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
                                            <label class="xCNTextDetail1"><?php echo language('document/expenserecord/expenserecord', 'tPXShipAddInfo');?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <a style="font-size:14px!important;color:#FFFFFF;">
                                                <i class="fa fa-pencil" id="oliPXEditShipAddress">&nbsp;<?php echo language('document/expenserecord/expenserecord','tPXShipChange');?></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body xCNPDModlue">
                                    <input type="hidden" id="ohdPXShipAddSeqNo" class="form-control">
                                    <div class="xWPXShipContentAddVersion1" style="<?php echo ($tPXSplShipAddVersion != '1' ? 'display:none;' : '' )?>" >
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXShipADDV1No');?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXShipAddAddV1No"><?php echo @$tPXShipAddAddV1No;?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXShipADDV1Village');?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXShipAddV1Soi"><?php echo @$tPXShipAddV1Soi;?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXShipADDV1Soi'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXShipAddV1Village"><?php echo @$tPXShipAddV1Village;?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXShipADDV1Road'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXShipAddV1Road"><?php echo @$tPXShipAddV1Road;?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXShipADDV1SubDist'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXShipAddV1SubDist"><?php echo @$tPXShipAddV1SubDist;?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXShipADDV1DstCode'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXShipAddV1DstCode"><?php echo @$tPXShipAddV1DstCode?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXShipADDV1PvnCode'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXShipAddV1PvnCode"><?php echo @$tPXShipAddV1PvnCode?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXShipADDV1PostCode'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXShipAddV1PostCode"><?php echo @$tPXShipAddV1PostCode;?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="xWPXShipContentAddVersion2" style="<?php echo ($tPXSplShipAddVersion != '2' ? 'display:none;' : '' )?>">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXShipADDV2Desc1')?></label><br>
                                                    <label id="ospPXShipAddV2Desc1"><?php echo @$tPXShipAddV2Desc1;?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXShipADDV2Desc2')?></label><br>
                                                    <label id="ospPXShipAddV2Desc2"><?php echo @$tPXShipAddV2Desc2;?></label>
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
    </div>
<!-- ============================================================================================================================================================================= -->

<!-- ================================================================== View Modal TexAddress Purchase Invoice  ================================================================== -->
    <div id="odvPXBrowseTexAdd" class="modal fade">
        <div class="modal-dialog" style="width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/expenserecord/expenserecord','tPXTexAddress'); ?></label>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                            <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSnPXTexAddData()"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                            <button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
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
                                            <label class="xCNTextDetail1"><?php echo language('document/expenserecord/expenserecord', 'tPXTexAddInfo');?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <a style="font-size:14px!important;color:#FFFFFF;">
                                                <i class="fa fa-pencil" id="oliPXEditTexAddress">&nbsp;<?php echo language('document/expenserecord/expenserecord','tPXTexChange');?></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body xCNPDModlue">
                                    <input type="hidden" id="ohdPXTexAddSeqNo" class="form-control">
                                    <div class="xWPXTaxContentAddVersion1" style="<?php echo ($tPXSplTaxAddVersion != '1' ? 'display:none;' : '' )?>">
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXTexADDV1No');?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXTexAddAddV1No"><?php echo @$tPXTexAddAddV1No;?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXTexADDV1Village');?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXTexAddV1Soi"><?php echo @$tPXTexAddV1Soi;?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXTexADDV1Soi'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXTexAddV1Village"><?php echo @$tPXTexAddV1Village;?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXTexADDV1Road'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXTexAddV1Road"><?php echo @$tPXTexAddV1Road;?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXTexADDV1SubDist'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXTexAddV1SubDist"><?php echo @$tPXTexAddV1SubDist;?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXTexADDV1DstCode'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXTexAddV1DstCode"><?php echo @$tPXTexAddV1DstCode?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXTexADDV1PvnCode'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXTexAddV1PvnCode"><?php echo @$tPXTexAddV1PvnCode?></label>
                                            </div>
                                        </div>
                                        <div class="row p-b-5">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'tPXTexADDV1PostCode'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label id="ospPXTexAddV1PostCode"><?php echo @$tPXTexAddV1PostCode;?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="xWPXTaxContentAddVersion2" style="<?php echo ($tPXSplTaxAddVersion != '2' ? 'display:none;' : '' )?>">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXTexADDV2Desc1')?></label><br>
                                                    <label id="ospPXTexAddV2Desc1"><?php echo @$tPXTexAddV2Desc1;?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','tPXTexADDV2Desc2')?></label><br>
                                                    <label id="ospPXTexAddV2Desc2"><?php echo @$tPXTexAddV2Desc2;?></label>
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
    </div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
    <div id="odvPXModalAppoveDoc" class="modal fade xCNModalApprove">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main','tApproveTheDocument'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo language('common/main/main','tMainApproveStatus'); ?></p>
                        <ul>
                            <li><?php echo language('common/main/main','tMainApproveStatus1'); ?></li>
                            <li><?php echo language('common/main/main','tMainApproveStatus2'); ?></li>
                            <li><?php echo language('common/main/main','tMainApproveStatus3'); ?></li>
                            <li><?php echo language('common/main/main','tMainApproveStatus4'); ?></li>
                        </ul>
                    <p><?php echo language('common/main/main','tMainApproveStatus5'); ?></p>
                    <p><strong><?php echo language('common/main/main','tMainApproveStatus6'); ?></strong></p>
                </div>
                <div class="modal-footer">
                    <button onclick="JSxPXApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
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
                    <label class="xCNTextModalHeard"><?php echo language('document/expenserecord/expenserecord','tPXMsgCancel')?></label>
                </div>
                <div class="modal-body">
                    <p id="obpMsgApv"><?php echo language('document/expenserecord/expenserecord','tPXMsgDocProcess')?></p>
                    <p><strong><?php echo language('document/expenserecord/expenserecord','tPXMsgCanCancel')?></strong></p>
                </div>
                <div class="modal-footer">
                    <button onclick="JSnPXCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
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
    <div class="modal fade" id="odvPXOrderAdvTblColumns" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <div class="modal-body" id="odvPXModalBodyAdvTable">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                    <button id="obtPXSaveAdvTableColums" type="button" class="btn btn-primary"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
<!-- ============================================================================================================================================================================= -->

<!-- ============================================================== View Modal Delete Product In DT DocTemp Multiple  ============================================================ -->
    <div id="odvPXModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main','tModalDelete')?></label>
                </div>
                <div class="modal-body">
                    <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                    <input type="hidden" id="ohdConfirmPXDocNoDelete"   name="ohdConfirmPXDocNoDelete">
                    <input type="hidden" id="ohdConfirmPXSeqNoDelete"   name="ohdConfirmPXSeqNoDelete">
                    <input type="hidden" id="ohdConfirmPXPdtCodeDelete" name="ohdConfirmPXPdtCodeDelete">
                    <input type="hidden" id="ohdConfirmPXPunCodeDelete" name="ohdConfirmPXPunCodeDelete">

                </div>
                <div class="modal-footer">
                    <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
                </div>
            </div>
        </div>
    </div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal ไม่พบลูกค้า   ======================================================================== -->
<div id="odvPXModalPleseselectCustomer" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>กรุณาเลือกผู้จำหน่าย ก่อนเพิ่มสินค้า</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxFocusInputCustomer();">
                        <?php echo language('common/main/main', 'tModalConfirm'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvPXModalPDTNotFound" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>ไม่พบข้อมูลสินค้า กรุณาลองใหม่อีกครั้ง</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxNotFoundClose();" >
                        <?php echo language('common/main/main', 'tModalConfirm'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- ============================================================================================================================================================================= -->

<div id="odvPXModalPDTMoreOne" class="modal fade">
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
                                <th class="xCNTextBold" style="text-align:center; width:120px;"><?=language('common/main/main', 'tModalcodePDT')?></th>
                                <th class="xCNTextBold" style="text-align:center; width:160px;"><?=language('common/main/main', 'tModalnamePDT')?></th>
                                <th class="xCNTextBold" style="text-align:center; width:120px;"><?=language('common/main/main', 'tModalPriceUnit')?></th>
                                <th class="xCNTextBold" style="text-align:center; width:160px;"><?=language('common/main/main', 'tModalbarcodePDT')?></th>
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

<div id="odvPXModalAddDocRef" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="ofmPXFormAddDocRef" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main','อ้างอิงเอกสาร')?></label>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control xCNHide" id="oetPXRefDocNoOld" name="oetPXRefDocNoOld">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ประเภทการอ้างอิงเอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbPXRefType" name="ocbPXRefType">
                                    <option value="1" disabled><?php echo language('common/main/main', 'อ้างอิงภายใน'); ?></option>
                                    <option value="3" selected><?php echo language('common/main/main', 'อ้างอิงภายนอก'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbPXRefDoc" name="ocbPXRefDoc">
                                    <option value="1" selected><?php echo language('common/main/main', 'ใบรับของ'); ?></option>
                                    <option value="2"><?php echo language('common/main/main', 'ใบซื้อสินค้า'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง')?></label>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                        id="oetPXDocRefInt"
                                        name="oetPXDocRefInt"
                                        maxlength="5"
                                        value="<?=$tPXAgnCode?>"
                                    >
                                    <input
                                        type="text"
                                        class="form-control xWPointerEventNone"
                                        id="oetPXDocRefIntName"
                                        name="oetPXDocRefIntName"
                                        maxlength="100"
                                        placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง')?>"
                                        value="<?=$tPXAgnName?>"
                                        readonly
                                    >
                                    <span class="input-group-btn">
                                        <button id="obtPXBrowseRefDoc" type="button" class="btn xCNBtnBrowseAddOn" <?=$tDisabledAgn?> >
                                            <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="oetPXRefDocNo"
                                    name="oetPXRefDocNo"
                                    placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?>"
                                    maxlength="20"
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord','วันที่เอกสารอ้างอิง');?></label>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control xCNDatePicker xCNInputMaskDate"
                                        id="oetPXRefDocDate"
                                        name="oetPXRefDocDate"
                                        placeholder="YYYY-MM-DD"
                                        autocomplete="off"
                                    >
                                    <span class="input-group-btn">
                                        <button id="obtPXRefDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ค่าอ้างอิง'); ?></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="oetPXRefKey"
                                    name="oetPXRefKey"
                                    placeholder="<?php echo language('common/main/main', 'ค่าอ้างอิง'); ?>"
                                    maxlength="10"
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="obtPXConfirmAddDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="submit"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================================================================================================================================= -->




<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jExpenseRecordAdd.php');?>
<?php include('dis_chg/wExpenseRecordDisChgModal.php'); ?>

