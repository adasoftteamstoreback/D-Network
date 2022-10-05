<style>
    #oetRecommendLay {
        border-color: #2c82b6;
        color: #000000;
        margin-right: 10px;
        padding-bottom: 5px;
        font-size: 18px;
    }

    #oetRecommendLay:hover {
        background: #2c82b6;
        color: white;
    }
</style>

<?php
$tSesUsrLevel   = $this->session->userdata('tSesUsrLevel');
$tUserBchName   = $this->session->userdata('tSesUsrBchNameDefault');
$tUserBchCode   = $this->session->userdata('tSesUsrBchCodeDefault');
$tUserWahName   = $this->session->userdata('tSesUsrWahName');
$tUserWahCode   = $this->session->userdata('tSesUsrWahCode');
if (isset($aDataDocHD) && $aDataDocHD['rtCode'] == '1') {
    $aDataDocHD             = @$aDataDocHD['raItems'];
    $tDBRRoute               = "docDBREventEdit";
    $nDBRAutStaEdit          = 1;
    $tDBRDocNo               = $aDataDocHD['FTXshDocNo'];
    $dDBRDocDate             = date("Y-m-d", strtotime($aDataDocHD['FDXshDocDate']));
    $dDBRDocTime             = date("H:i", strtotime($aDataDocHD['FDXshDocDate']));
    $dDBRDepositDate         = date("Y-m-d", strtotime($aDataDocHD['FDXshBookDate']));
    $dDBRDepositTime         = date("H:i", strtotime($aDataDocHD['FDXshBookDate']));
    $tDBRCreateBy            = $aDataDocHD['CreateBy'];
    $tDBRUsrNameCreateBy     = $aDataDocHD['CreateByName'];
    $tDBRDocrefSO            = $aDataDocHD['DOCREF'];
    $tDBRDocrefS            = $aDataDocHD['SALDOCNO'];
    $tDBRDocrefSGrand       = $aDataDocHD['SALGRANDTXT'];
    $tDBRDocrefSBch         = $aDataDocHD['SALBchNO'];
    $tDBRDocrefTAX          = $aDataDocHD['TAXDOCNO'];
    $tDBRDocrefTAXGrand     = $aDataDocHD['TAXGRANDTXT'];
    $tDBRDocrefTAXBch       = $aDataDocHD['TAXBchNO'];

    $tDBRUsrBepositCodeBy     = $aDataDocHD['FTUsrDepositCode'];
    $tDBRUsrBepositNameBy     = $aDataDocHD['FTUsrDepositName'];

    $tDBRStaDoc              = $aDataDocHD['FTXshStaDoc'];
    $tDBRStaApv              = $aDataDocHD['FTXshStaApv'];

    $tDBRStaPrcStk           = '';
    $tDBRStaDelMQ            = '';

    $tDBRSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
    $tDBRUsrCode             = $this->session->userdata('tSesUsername');
    $tDBRLangEdit            = $this->session->userdata("tLangEdit");

    $tDBRApvCode             = $aDataDocHD['FTXshApvCode'];
    $tDBRUsrNameApv          = $aDataDocHD['FTXshApvName'];
    $tDBRRefPoDoc            = "";
    $tDBRRefIntDoc           = $aDataDocHD['FTXshRefInt'];
    $dDBRRefIntDocDate       = $aDataDocHD['FDXshRefIntDate'];
    $tDBRRefExtDoc           = $aDataDocHD['FTXshRefExt'];
    $dDBRRefExtDocDate       = $aDataDocHD['FDXshRefExtDate'];

    $nDBRStaRef              = $aDataDocHD['FNXshStaRef'];

    $tDBRBchCode             = $aDataDocHD['FTBchCode'];
    $tDBRBchName             = $aDataDocHD['FTBchName'];
    $tDBRUserBchCode         = $tUserBchCode;
    $tDBRUserBchName         = $tUserBchName;

    $tDBRWahCode             = $aDataDocHD['FTWahCode'];
    $tDBRWahName             = $aDataDocHD['rtWahName'];
    $nDBRStaDocAct           = $aDataDocHD['FNXshStaDocAct'];
    $tDBRFrmDocPrint         = $aDataDocHD['FNXshDocPrint'];
    $tDBRFrmRmk              = $aDataDocHD['FTXshRmk'];

    $nStaUploadFile         = 2;
    $nPOStaDocAct           = $aDataDocHD['FNXshStaDocAct'];
    $tDBRAgnCode             = $aDataDocHD['rtAgnCode'];
    $tDBRAgnName             = $aDataDocHD['rtAgnName'];

    $tCstCode               = $aDataDocHD['FTCstCode'];
    $tCstName               = $aDataDocHD['FTCstName'];
    $tCstTel                = $aDataDocHD['FTCstTel'];
    $tCstMail               = $aDataDocHD['FTCstEmail'];

    $tXshRefKey              = $aDataDocHD['FTXshRefKey'];
    
} else {
    $tDBRRoute               = "docDBREventAdd";
    $nDBRAutStaEdit          = 0;
    $tDBRDocNo               = "";
    $dDBRDocDate             = "";
    $dDBRDocTime             = date('H:i:s');
    $dDBRDepositDate         = "";
    $dDBRDepositTime         = date('H:i:s');
    $tDBRCreateBy            = $this->session->userdata('tSesUsrUsername');
    $tDBRUsrNameCreateBy     = $this->session->userdata('tSesUsrUsername');

    $tDBRUsrBepositCodeBy     = "";
    $tDBRUsrBepositNameBy     = "";

    $nDBRStaRef              = 0;
    $tDBRStaDoc              = 1;
    $tDBRStaApv              = NULL;
    $tDBRStaPrcStk           = NULL;
    $tDBRStaDelMQ            = NULL;

    $tDBRSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
    $tDBRDptCode             = '';
    $tDBRUsrCode             = $this->session->userdata('tSesUsername');
    $tDBRLangEdit            = $this->session->userdata("tLangEdit");

    $tDBRDocrefSO            = "";
    $tDBRApvCode             = "";
    $tDBRUsrNameApv          = "";
    $tDBRRefPoDoc            = "";
    $tDBRRefIntDoc           = "";
    $dDBRRefIntDocDate       = "";
    $tDBRRefExtDoc           = "";
    $dDBRRefExtDocDate       = "";
    $tDBRRefPO               = "";
    $tDBRRefIV               = "";
    $tDBRRefSO               = "";
    $tDBRDocrefS            = "";
    $tDBRDocrefSGrand       = "";
    $tDBRDocrefSBch         = "";
    $tDBRDocrefTAX          = "";
    $tDBRDocrefTAXGrand     = "";
    $tDBRDocrefTAXBch      = "";

    $tDBRBchCode             = "";
    $tDBRBchName             = "";
    $tDBRUserBchCode         = "";
    $tDBRUserBchName         = "";

    $tDBRWahCode             = "";
    $tDBRWahName             = "";
    $nDBRStaDocAct           = "";
    $tDBRFrmDocPrint         = "";
    $tDBRFrmRmk              = "";

    $tCstCode               = "";
    $tCstName               = "";
    $tCstTel                = "";
    $tCstMail               = "";

    $aSPLConfig             = $aSPLConfig;
    if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){ //แฟรนไซส์
        $tDBRSplCode         = $aSPLConfig['rtSPLCode'];
        $tDBRSplName         = $aSPLConfig['rtSPLName'];
    }else{ //สำนักงานใหญ่
        $tDBRSplCode         = "";
        $tDBRSplName         = "";
    }

    $tDBRCmpRteCode          = "";
    $cDBRRteFac              = "";

    $tDBRVatInOrEx           = "";
    $tDBRSplPayMentType      = "";

    $nStaUploadFile         = 1;
    $nPOStaDocAct           = "";
    $tDBRAgnCode             = $this->session->userdata('tSesUsrAgnCode');
    $tDBRAgnName             = $this->session->userdata('tSesUsrAgnName');
    $tXshRefKey              = "";
}
if (empty($tDBRBchCode) && empty($tDBRShopCode)) {
    $tASTUserType   = "HQ";
} else {
    if (!empty($tDBRBchCode) && empty($tDBRShopCode)) {
        $tASTUserType   = "BCH";
    } else if (!empty($tDBRBchCode) && !empty($tDBRShopCode)) {
        $tASTUserType   = "SHP";
    } else {
        $tASTUserType   = "";
    }
}
?>
<style>
    #odvRowDataEndOfBill .panel-heading {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }

    #odvRowDataEndOfBill .panel-body {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }

    #odvRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
</style>
<form id="ofmDBRFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <input type="hidden" id="ohdDBRPage" name="ohdDBRPage" value="1">
    <input type="hidden" id="ohdDBRStaaImport" name="ohdDBRStaaImport" value="0">
    <input type="hidden" id="ohdDBRRoute" name="ohdDBRRoute" value="<?php echo $tDBRRoute; ?>">
    <input type="hidden" id="ohdDBRCheckClearValidate" name="ohdDBRCheckClearValidate" value="0">
    <input type="hidden" id="ohdDBRCheckSubmitByButton" name="ohdDBRCheckSubmitByButton" value="0">
    <input type="hidden" id="ohdDBRAutStaEdit" name="ohdDBRAutStaEdit" value="<?php echo $nDBRAutStaEdit; ?>">
    <input type="hidden" id="ohdDBRODecimalShow" name="ohdDBRODecimalShow" value="<?= $nOptDecimalShow ?>">
    <input type="hidden" id="ohdDBRStaDoc" name="ohdDBRStaDoc" value="<?php echo $tDBRStaDoc; ?>">
    <input type="hidden" id="ohdDBRStaApv" name="ohdDBRStaApv" value="<?php echo $tDBRStaApv; ?>">
    <input type="hidden" id="ohdDBRDocRefSO" name="ohdDBRDocRefSO" value="<?php echo $tDBRDocrefSO; ?>">
    <input type="hidden" id="ohdDBRDocRefTAX" name="ohdDBRDocRefTAX" value="<?php echo $tDBRDocrefTAX; ?>">
    <input type="hidden" id="ohdXshRefKey" name="ohdXshRefKey" value="<?php echo $tXshRefKey; ?>">
    

    <input type="hidden" id="ohdDBRStaDelMQ" name="ohdDBRStaDelMQ" value="<?php echo $tDBRStaDelMQ; ?>">
    <input type="hidden" id="ohdDBRStaPrcStk" name="ohdDBRStaPrcStk" value="<?php echo $tDBRStaPrcStk; ?>">

    <input type="hidden" id="ohdDBRSesUsrBchCode" name="ohdDBRSesUsrBchCode" value="<?php echo $tDBRSesUsrBchCode; ?>">
    <input type="hidden" id="ohdDBRBchCode" name="ohdDBRBchCode" value="<?php echo $tDBRBchCode; ?>">
    <input type="hidden" id="ohdDBRUsrCode" name="ohdDBRUsrCode" value="<?php echo $tDBRUsrCode ?>">
    <input type="hidden" id="ohdDBRStaRef" name="ohdDBRStaRef" value="<?php echo $nDBRStaRef; ?>">

    <input type="hidden" id="ohdDBRApvCodeUsrLogin" name="ohdDBRApvCodeUsrLogin" value="<?php echo $tDBRUsrCode; ?>">
    <input type="hidden" id="ohdDBRLangEdit" name="ohdDBRLangEdit" value="<?php echo $tDBRLangEdit; ?>">
    <input type="hidden" id="ohdDBROptAlwSaveQty" name="ohdDBROptAlwSaveQty" value="<?php echo $nOptDocSave ?>">
    <input type="hidden" id="ohdSesSessionID" name="ohdSesSessionID" value="<?= $this->session->userdata('tSesSessionID') ?>">
    <input type="hidden" id="ohdSesSessionName" name="ohdSesSessionName" value="<?= $this->session->userdata('tSesUsrUsername') ?>">
    <input type="hidden" id="ohdSesUsrLevel" name="ohdSesUsrLevel" value="<?= $this->session->userdata('tSesUsrLevel') ?>">
    <input type="hidden" id="ohdSesUsrBchCom" name="ohdSesUsrBchCom" value="<?= $this->session->userdata('tSesUsrBchCom') ?>">
    <input type="hidden" id="ohdDBRValidatePdt" name="ohdDBRValidatePdt" value="<?= language('document/depositboxreservation/depositboxreservation', 'tDBRPleaseSeletedPDTIntoTable') ?>">
    <input type="hidden" id="ohdDBRSubmitWithImp" name="ohdDBRSubmitWithImp" value="0">
    <input type="hidden" id="ohdDBRVATInOrEx" name="ohdDBRVATInOrEx" value="">
    <input type="hidden" id="ohdDBRDocType" name="ohdDBRDocType" value="">
    <input type="hidden" id="ohdDBRApvOrSave" name="ohdDBRApvOrSave" value="">

    <input type="hidden" id="ohdDBRValidatePdtImp" name="ohdDBRValidatePdtImp" value="<?= language('document/depositboxreservation/depositboxreservation', 'tDBRNotFoundPdtCodeAndBarcodeImpList') ?>">

    <button style="display:none" type="submit" id="obtDBRSubmitDocument" onclick="JSxDBRAddEditDocument()"></button>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDBRHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRDoucment'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvDBRDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDBRDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmAppove'); ?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmDocNo'); ?></label>
                                <?php if (isset($tDBRDocNo) && empty($tDBRDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbDBRStaAutoGenCode" name="ocbDBRStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai" id="oetDBRDocNo" name="oetDBRDocNo" maxlength="20" value="<?php echo $tDBRDocNo; ?>" data-validate-required="<?php echo language('document/purchaseorder/purchaseorder', 'tDBRPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder', 'tDBRPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="text" class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai xCNHide" id="oetDBRDocNoTmp" name="oetDBRDocNoTmp" maxlength="20" value="<?php echo $tDBRDocNo; ?>" data-validate-required="<?php echo language('document/purchaseorder/purchaseorder', 'tDBRPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder', 'tDBRPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdDBRCheckDuplicateCode" name="ohdDBRCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <?php if ($dDBRDocDate == '') {
                                            $dDBRDocDate = '';
                                        } ?>
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetDBRDocDate" name="oetDBRDocDate" value="<?php echo $dDBRDocDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtDBRDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetDBRDocTime" name="oetDBRDocTime" value="<?php echo $dDBRDocTime; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtDBRDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdDBRCreateBy" name="ohdDBRCreateBy" value="<?php echo $tDBRCreateBy ?>">
                                            <label><?php echo $tDBRUsrNameCreateBy ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                            if ($tDBRRoute == "docDBREventAdd") {
                                                $tDBRLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                            } else {
                                                $tDBRLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc' . $tDBRStaDoc);
                                            }
                                            ?>
                                            <label><?php echo $tDBRLabelStaDoc; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php 
                                                if ($tDBRStaDoc == 3) {
                                                    $tDBRLabelStaApv = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc' . 3);
                                                }else{
                                                    $tDBRLabelStaApv = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaApv' . $tDBRStaApv);
                                                } 
                                            ?>
                                            <label><?php echo $tDBRLabelStaApv; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะอ้างอิงเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmStaRef'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">

                                            <label><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmStaRef' . $nDBRStaRef); ?></label>

                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tDBRDocNo) && !empty($tDBRDocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdDBRApvCode" name="ohdDBRApvCode" maxlength="20" value="<?php echo $tDBRApvCode ?>">
                                                <label>
                                                    <?php echo (isset($tDBRUsrNameApv) && !empty($tDBRUsrNameApv)) ? $tDBRUsrNameApv : "-" ?>
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
                <div id="odvDBRReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRDocRole'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvDBRDataReferenceDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDBRDataReferenceDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
                                <div class="form-group m-b-0">
                                    <?php
                                    $tDBRDataInputBchCode   = "";
                                    $tDBRDataInputBchName   = "";
                                    if ($tDBRRoute  == "docDBREventAdd") {
                                        $tDBRDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                                        $tDBRDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                                        $tDisabledBch = '';
                                    } else {
                                        $tDBRDataInputBchCode    = $tDBRBchCode;
                                        $tDBRDataInputBchName    = $tDBRBchName;
                                        $tDisabledBch = 'disabled';
                                    }
                                    ?>
                                    <!--สาขา-->
                                    <script>
                                        var tUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
                                        if (tUsrLevel != "HQ") {
                                            var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                                            if(tBchCount < 2){
                                                $('#obtDBRBrowseBCH').attr('disabled', true);
                                            }
                                        }
                                    </script>
                                    <input type="text" class="form-control xControlForm xCNHide" id="oetDBRAgnCode" name="oetDBRAgnCode" maxlength="5" value="<?=$tDBRAgnCode;?>">

                                    <!--สาขาที่ฝากของ-->
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmBranch') ?></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetDBRFrmBchCode" name="oetDBRFrmBchCode" maxlength="5" value="<?php echo @$tDBRDataInputBchCode ?>" data-bchcodeold="<?php echo @$tDBRDataInputBchCode ?>">
                                            <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDBRFrmBchName" name="oetDBRFrmBchName" maxlength="100" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelFrmBranch') ?>" data-validate-required="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRPlsEnterBch'); ?>" value="<?php echo @$tDBRDataInputBchName ?>" readonly>
                                            <span class="input-group-btn xWConditionSearchPdt">
                                                <button id="obtDBRBrowseBCH" type="button" class="btn xCNBtnBrowseAddOn ">
                                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                <!-- พนักงานฝากของเข้าตู้ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRUserDepositToBox'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetDBRUsrCode" name="oetDBRUsrCode" maxlength="5" value="<?=$tDBRUsrBepositCodeBy?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDBRUsrName" name="oetDBRUsrName" value="<?=$tDBRUsrBepositNameBy?>" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRUserDepositToBox'); ?>" readonly>
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtDBRBrowseUser" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- วันที่ฝาก -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRDepositDate'); ?></label>
                                    <div class="input-group">
                                        <?php if ($dDBRDepositDate == '') {
                                            $dDBRDepositDate = '';
                                        } ?>
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetDBRDepositDate" name="oetDBRDepositDate" value="<?php echo $dDBRDepositDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtDBRDepositDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เวลาฝาก -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRDepositTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetDBRDepositTime" name="oetDBRDepositTime" value="<?php echo $dDBRDepositTime; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtDBRDepositTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel ข้อมูลลูกค้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDBRConditionDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRCstInfo'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvDBRDataConditionDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDBRDataConditionDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRCstName') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetDBRCstCode" name="oetDBRCstCode" maxlength="5" value="<?=$tCstCode?>" data-bchcodeold="" >
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDBRCstName" name="oetDBRCstName" maxlength="100" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRCstName') ?>" data-validate-required="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRPlsEnterCst'); ?>" value="<?=$tCstName?>" readonly>
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtDBRBrowseCst" type="button" class="btn xCNBtnBrowseAddOn ">
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRCstTel'); ?></label>
                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDBRCstTel" name="oetDBRCstTel" maxlength="100" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRCstTel') ?>" value="<?=$tCstTel?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRCstMail'); ?></label>
                                    <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDBRCstMail" name="oetDBRCstMail" maxlength="100" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRCstMail') ?>" value="<?=$tCstMail?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel อืนๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDBRInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/depositboxreservation/depositboxreservation', 'อื่นๆ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvDBRDataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDBRDataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" value="1" id="ocbDBRFrmInfoOthStaDocAct" name="ocbDBRFrmInfoOthStaDocAct" maxlength="1" <?php echo ($nPOStaDocAct == '1' || empty($nPOStaDocAct)) ? 'checked' : ''; ?> checked="checked">
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>
                                <!-- สถานะอ้างอิง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef'); ?></label>
                                    <select class="selectpicker xWDBRDisabledOnApv form-control xControlForm" id="ocmDBRFrmInfoOthRef" name="ocmDBRFrmInfoOthRef" maxlength="1">
                                        <option value="0" selected><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef0'); ?></option>
                                        <option value="1"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef1'); ?></option>
                                        <option value="2"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef2'); ?></option>
                                    </select>
                                </div>
                                <!-- จำนวนครั้งที่พิมพ์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthDocPrint'); ?></label>
                                    <input type="text" class="form-control xControlForm text-right" id="ocmDBRFrmInfoOthDocPrint" name="ocmDBRFrmInfoOthDocPrint" value="<?php echo $tDBRFrmDocPrint; ?>" readonly>
                                </div>
                                <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt'); ?></label>
                                    <select class="form-control xControlForm selectpicker xWDBRDisabledOnApv" id="ocmDBRFrmInfoOthReAddPdt" name="ocmDBRFrmInfoOthReAddPdt">
                                        <option value="1" selected><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt1'); ?></option>
                                        <option value="2"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt2'); ?></option>
                                    </select>
                                </div>
                                <!-- หมายเหตุ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRemark'); ?></label>
                                    <textarea class="form-control xControlRmk xWConditionSearchPdt" id="otaDBRFrmInfoOthRmk" name="otaDBRFrmInfoOthRmk" rows="10" maxlength="200" style="resize: none;height:86px;"><?php echo $tDBRFrmRmk ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel ไฟลแนบ -->
            <!-- <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvSOReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/saleorder/saleorder', 'ไฟล์แนบ'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvSODataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvSODataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvDBRShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>


                    var oSOCallDataTableFile = {
                        ptElementID     : 'odvDBRShowDataTable',
                        ptBchCode       : $('#oetDBRFrmBchCode').val(),
                        ptDocNo         : $('#oetDBRDocNo').val(),
                        ptDocKey        : 'TAPTDoHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : '<?= $nStaUploadFile ?>',
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdDBRStaApv').val(),
                        ptStaDoc        : $('#ohdDBRStaDoc').val()
                        //JSxSoCallBackUploadFile -- ดูข้อมูลไฟล์แนบ
                    }
                    JCNxUPFCallDataTable(oSOCallDataTableFile);
                </script>
            </div> -->
        </div>
        <div class="col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <!-- ตารางรายการสินค้า -->
                <div id="odvDBRDataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <!-- Tab -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                            <ul class="nav" role="tablist">

                                                <!-- สินค้า -->
                                                <li class="xWMenu active xCNStaHideShow" style="cursor:pointer;" id="oliDBRContentProduct">
                                                    <a role="tab" data-toggle="tab" data-target="#odvDBRContentProduct" aria-expanded="true"><?= language('document/expenserecord/expenserecord', 'ข้อมูลสินค้า') ?></a>
                                                </li>

                                                <!-- อ้างอิง -->
                                                <li class="xWMenu xCNStaHideShow" style="cursor:pointer;" id="oliDBRContentHDRef">
                                                    <a role="tab" data-toggle="tab" data-target="#odvDBRContentHDRef" aria-expanded="false"><?= language('document/expenserecord/expenserecord', 'เอกสารอ้างอิง') ?></a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div id="odvDBRContentProduct" class="tab-pane fade active in" style="padding: 0px !important;">
                                        <!-- <div class="row p-t-10">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelSplName'); ?></label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xControlForm xCNHide" id="oetDBRFrmSplCode" name="oetDBRFrmSplCode" value="<?php echo $tDBRSplCode; ?>">
                                                        <input type="text" class="form-control xControlForm" id="oetDBRFrmSplName" name="oetDBRFrmSplName" value="<?php echo $tDBRSplName; ?>" placeholder="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRLabelSplName') ?>" data-validate-required="<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRPlsEnterSplCode'); ?>" readonly>
                                                        <span class="input-group-btn">
                                                            <button id="obtDBRBrowseSupplier" type="button" class="btn xCNBtnBrowseAddOn">
                                                                <img class="xCNIconFind">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="row p-t-10">

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvDBRCSearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvDBRCSearchPdtHTML()">
                                                                <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right">
                                                <button class="btn info" id="oetRecommendLay" onclick="JSxDBRSuggestLay()"><?php echo language('common/main/main', 'แนะนำช่องฝาก') ?></button>
                                                <!--ตัวเลือก-->
                                                <!-- <div id="odvDBRMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup">
                                                    <button type="button" class="btn xCNBTNMngTable xWConditionSearchPdt" data-toggle="dropdown">
                                                        <?//php echo language('common/main/main', 'tCMNOption') ?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li id="oliDBRBtnDeleteMulti">
                                                            <a data-toggle="modal" data-target="#odvDBRModalDelPdtInDTTempMultiple"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                        </li>
                                                    </ul>
                                                </div> -->
                                            </div>

                                        </div>
                                        <div class="row p-t-10" id="odvDBRDataPdtTableDTTemp">
                                        </div>
                                        <!--ส่วนสรุปท้ายบิล-->
                                        <div class="odvRowDataEndOfBill" id="odvRowDataEndOfBill">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder', 'จำนวนรายการรวมทั้งสิ้น'); ?></label>
                                                    <label class="pull-right mark-font"><span class="mark-font xShowQtyFooter">0</span> <?= language('document/purchaseorder/purchaseorder', 'tPOItems'); ?></label>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>

                                    <!-- อ้างอิง -->
                                    <div id="odvDBRContentHDRef" class="tab-pane fade" style="padding: 0px !important;">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="xCNHideWhenCancelOrApprove" style="margin-top:10px;">
                                                    <button type="button" id="obtDBRAddDocRef" class="xCNBTNPrimeryPlus xCNDocBrowsePdt">+</button>
                                                </div>
                                            </div>
                                            <div id="odvDBRTableHDRef"></div>
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
<div id="odvDBRBrowseShipAdd" class="modal fade">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipAddress'); ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSnDBRShipAddData()"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="panel panel-default" style="margin-bottom:5px;">
                            <div class="panel-heading" style="padding-top:5px!important;padding-bottom:5px!important;">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <label class="xCNTextDetail1"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipAddInfo'); ?></label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                        <a style="font-size:14px!important;color:#1866ae;">
                                            <i class="fa fa-pencil" id="oliDBREditShipAddress">&nbsp;<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipChange'); ?></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body xCNPDModlue">
                                <input type="hidden" id="ohdDBRShipAddSeqNo" class="form-control xControlForm">
                                <?php $tDBRFormatAddressType = FCNaHAddressFormat('TCNMBranch'); //1 ที่อยู่ แบบแยก  ,2  แบบรวม
                                ?>
                                <?php if (!empty($tDBRFormatAddressType) && $tDBRFormatAddressType == '1') : ?>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipADDV1No'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRShipAddAddV1No"><?php echo @$tDBRShipAddAddV1No; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipADDV1Village'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRShipAddV1Soi"><?php echo @$tDBRShipAddV1Soi; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipADDV1Soi'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRShipAddV1Village"><?php echo @$tDBRShipAddV1Village; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipADDV1Road'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRShipAddV1Road"><?php echo @$tDBRShipAddV1Road; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipADDV1SubDist'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRShipAddV1SubDist"><?php echo @$tDBRShipAddV1SubDist; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipADDV1DstCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRShipAddV1DstCode"><?php echo @$tDBRShipAddV1DstCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipADDV1PvnCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRShipAddV1PvnCode"><?php echo @$tDBRShipAddV1PvnCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipADDV1PostCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRShipAddV1PostCode"><?php echo @$tDBRShipAddV1PostCode; ?></label>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipADDV2Desc1') ?></label><br>
                                                <label id="ospDBRShipAddV2Desc1"><?php echo @$tDBRShipAddV2Desc1; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRShipADDV2Desc2') ?></label><br>
                                                <label id="ospDBRShipAddV2Desc2"><?php echo @$tDBRShipAddV2Desc2; ?></label>
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
<div id="odvDBRBrowseTexAdd" class="modal fade">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexAddress'); ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSnDBRTexAddData()"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="panel panel-default" style="margin-bottom:5px;">
                            <div class="panel-heading" style="padding-top:5px!important;padding-bottom:5px!important;">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <label class="xCNTextDetail1"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexAddInfo'); ?></label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                        <a style="font-size:14px!important;color:#1866ae;">
                                            <i class="fa fa-pencil" id="oliDBREditTexAddress">&nbsp;<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexChange'); ?></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body xCNPDModlue">
                                <input type="hidden" id="ohdDBRTexAddSeqNo" class="form-control xControlForm">
                                <?php $tDBRFormatAddressType = FCNaHAddressFormat('TCNMBranch'); //1 ที่อยู่ แบบแยก  ,2  แบบรวม
                                ?>
                                <?php if (!empty($tDBRFormatAddressType) && $tDBRFormatAddressType == '1') : ?>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexADDV1No'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRTexAddAddV1No"><?php echo @$tDBRTexAddAddV1No; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexADDV1Village'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRTexAddV1Soi"><?php echo @$tDBRTexAddV1Soi; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexADDV1Soi'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRTexAddV1Village"><?php echo @$tDBRTexAddV1Village; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexADDV1Road'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRTexAddV1Road"><?php echo @$tDBRTexAddV1Road; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexADDV1SubDist'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRTexAddV1SubDist"><?php echo @$tDBRTexAddV1SubDist; ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexADDV1DstCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRTexAddV1DstCode"><?php echo @$tDBRTexAddV1DstCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexADDV1PvnCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRTexAddV1PvnCode"><?php echo @$tDBRTexAddV1PvnCode ?></label>
                                        </div>
                                    </div>
                                    <div class="row p-b-5">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexADDV1PostCode'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label id="ospDBRTexAddV1PostCode"><?php echo @$tDBRTexAddV1PostCode; ?></label>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexADDV2Desc1') ?></label><br>
                                                <label id="ospDBRTexAddV2Desc1"><?php echo @$tDBRTexAddV2Desc1; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRTexADDV2Desc2') ?></label><br>
                                                <label id="ospDBRTexAddV2Desc2"><?php echo @$tDBRTexAddV2Desc2; ?></label>
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
<div id="odvDBRModalAppoveDoc" class="modal fade">
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
                <button onclick="JSxDBRApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
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
<div id="odvDBRModalAppoveDoc2" class="modal fade">
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
                <button onclick="JSxDBRApproveDocument2(true)" type="button" class="btn xCNBTNPrimery">
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
<!-- ======================================================================== View Modal FailItem  ======================================================================== -->
<div id="odvDBRModalFailItems" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'ข้อมูลช่องฝากที่ไม่สามารถเปลี่ยนได้'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <table id="otbDBRDocPdtFailAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','tDBRTable_pdtname')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ตู้ฝากเดิม')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ช่องฝากเดิม')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ตู้ฝากใหม่')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ช่องฝากใหม่')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','สถานะ')?></th>
                </tr>
            </thead>
            <tbody id="odvTBodyDBRFailPdtAdvTableList">
            
            </tbody>
            </table>
            </div>
            <div class="modal-footer">
                <button onclick="JSxDBRSubmit(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->
<!-- ======================================================================== View Modal FailItem  ======================================================================== -->
<div id="odvDBRModalFailItemsSubmit" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'ข้อมูลช่องฝากที่ไม่สามารถเปลี่ยนได้'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <table id="otbDBRDocPdtFailAdvTableList" class="table xWPdtTableFont">
            <thead>
                <tr class="xCNCenter">
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','tDBRTable_pdtname')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ตู้ฝากเดิม')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ช่องฝากเดิม')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ตู้ฝากใหม่')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','ช่องฝากใหม่')?></th>
                    <th class="xCNTextBold"><?=language('document/depositboxreservation/depositboxreservation','สถานะ')?></th>
                </tr>
            </thead>
            <tbody id="odvTBodyDBRFailPdtAdvTableListSubmit">
            
            </tbody>
            </table>
            </div>
            <div class="modal-footer">
                <button onclick="JSxDBRSubmit2(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== View Modal Cancel Document  ======================================================================== -->
<div class="modal fade" id="odvDBRPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRCancelDoc') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRCancelDocWarnning') ?></p>
                <p><strong><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRCancelDocConfrim') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnDBRCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
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
<div class="modal fade" id="odvDBROrderAdvTblColumns" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <div class="modal-body" id="odvDBRModalBodyAdvTable">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                <button id="obtDBRSaveAdvTableColums" type="button" class="btn btn-primary"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ============================================================== View Modal Delete Product In DT DocTemp Multiple  ============================================================ -->
<div id="odvDBRModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type="hidden" id="ohdConfirmDBRDocNoDelete" name="ohdConfirmDBRDocNoDelete">
                <input type="hidden" id="ohdConfirmDBRSeqNoDelete" name="ohdConfirmDBRSeqNoDelete">
                <input type="hidden" id="ohdConfirmDBRPdtCodeDelete" name="ohdConfirmDBRPdtCodeDelete">
                <input type="hidden" id="ohdConfirmDBRPunCodeDelete" name="ohdConfirmDBRPunCodeDelete">

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
<div id="odvDBRModalPleseselectSPL" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRSplNotFound') ?></p>
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
<div id="odvDBRModalPDTNotFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRPdtNotFound') ?></p>
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
<div id="odvDBRModalPDTMoreOne" class="modal fade">
    <div class="modal-dialog" role="document" style="width: 85%; margin: 1.75rem auto;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRSelectPdt') ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTMoreOne(1)" data-dismiss="modal"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRChoose') ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTMoreOne(2)" data-dismiss="modal"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRClose') ?></button>
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
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal เปลี่ยนสาขา ======================================================================== -->
<div id="odvDBRModalChangeData" class="modal fade" style="z-index: 1400;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ohdDBRTypeChange" name="ohdDBRTypeChange">
                <p><span id="ospDBRTxtWarningAlert"></span></p>
                <?php //echo language('document/depositboxreservation/depositboxreservation', 'tDBRBchNotFound') ?>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" id="obtDBRChangeData" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                <button type="button" data-dismiss="modal" class="btn xCNBTNDefult"><?php echo language('common/main/main', 'tModalCancel'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- =========================================== อ้างอิงเอกสารภายใน ============================================= -->
<div id="odvDBRModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1400;">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                
                <label class="xCNTextModalHeard" id="olbTextModalHead"><?php echo language('document/purchaseorder/purchaseorder', 'อ้างอิงเอกสารใบสั่งซื้อ') ?></label>

            </div>

            <div class="modal-body">
                <div class="row" id="odvDBRFromRefIntDoc">

                </div>
            </div>

            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>

        </div>
    </div>
</div>

<!-- =========================================== Browse ตู้ฝาก ============================================= -->
<div id="odvDBRModalBrowseBox" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1400;">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                
                <label class="xCNTextModalHeard" id="olbTextModalHead"><?php echo language('document/purchaseorder/purchaseorder', 'ตู้ฝาก') ?></label>

            </div>

            <div class="modal-body">
                <div class="row" id="odvDBRFromBrowseBox">

                </div>
            </div>

            <div class="modal-footer">
                <button id="obtConfirmBrowseBox" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>

        </div>
    </div>
</div>

<!-- =========================================== ไม่พบคลังสินค้า ============================================= -->
<div id="odvDBRModalWahNoFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRWahNotFound') ?></label>
            </div>

            <div class="modal-body">
                <p><?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRPlsSelectWah') ?></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>

        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<div id="odvDBRModalPONoFound" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/depositboxreservation/depositboxreservation','tDBRPONotFound')?></label>
            </div>

            <div class="modal-body">
                <p><?php echo language('document/depositboxreservation/depositboxreservation','tDBRPleaseSelectPO')?></p>
            </div>

            <div class="modal-footer">
                <button type="button" id="obtConfirmPo" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ===========================================  อ้างอิงเอกสารภายใน (ภายใน หรือ ภายนอก) =========================================== -->
<div id="odvDBRModalAddDocRef" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="ofmDBRFormAddDocRef" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'อ้างอิงเอกสาร') ?></label>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control xCNHide" id="oetDBRRefDocNoOld" name="oetDBRRefDocNoOld">
                    <input type="text" class="form-control xCNHide" id="oetDBRRefDoc" name="oetDBRRefDoc">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ประเภทการอ้างอิงเอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbDBRRefType" name="ocbDBRRefType">
                                    <option value="1" selected><?php echo language('common/main/main', 'อ้างอิงภายใน'); ?></option>
                                    <option value="3"><?php echo language('common/main/main', 'อ้างอิงภายนอก'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbDBRRefDoc" name="ocbDBRRefDoc">
                                    <option value="1" selected><?php echo language('common/main/main', 'ใบสั่งขาย'); ?></option>
                                </select>
                            </div>
                        </div>
                        <!-- อ้างอิงภายใน -->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetDBRRefIntDoc" name="oetDBRRefIntDoc" maxlength="20" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?>" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtDBRBrowseRefDocInt" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetDBRRefDocNo" name="oetDBRRefDocNo" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?>" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/expenserecord/expenserecord', 'วันที่เอกสารอ้างอิง'); ?></label>
                                <div class="input-group">
                                    <input type="hidden" id="ohdDBRRefDocTime" name="ohdDBRRefDocTime" value=''>
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetDBRRefDocDate" name="oetDBRRefDocDate" placeholder="YYYY-MM-DD" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="obtDBRRefDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ค่าอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetDBRRefKey" name="oetDBRRefKey" placeholder="<?php echo language('common/main/main', 'ค่าอ้างอิง'); ?>" maxlength="10" autocomplete="off" value="SO">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="obtDBRConfirmAddDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="submit"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jDepositBoxReservationAdd.php'); ?>
<?php include("script/jDepositBoxReservationPdtAdvTableData.php"); ?>
<script type="text/javascript">
    //บังคับให้เลือกลูกค้า
    function JSxFocusInputCustomer() {
        $('#oetDBRFrmCstName').focus();
    }

    //ค้นหาสินค้าใน temp
    function JSvDBRCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbDBRDocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    function JSxNotFoundClose() {
        $('#oetDBRInsertBarcode').focus();
    }

    //กดเลือกบาร์โค๊ด
    function JSxSearchFromBarcode(e, elem) {
        var tValue = $(elem).val();
        if ($('#oetDBRFrmSplName').val() != "") {
            JSxCheckPinMenuClose();
            if (tValue.length === 0) {

            } else {
                // JCNxOpenLoading();
                $('#oetDBRInsertBarcode').attr('readonly', true);
                JCNSearchBarcodePdt(tValue);
                $('#oetDBRInsertBarcode').val('');
            }
        } else {
            $('#odvDBRModalPleseselectSPL').modal('show');
            $('#oetDBRInsertBarcode').val('');
        }
        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
    function JCNSearchBarcodePdt(ptTextScan) {
        var tWhereCondition = "";
        var aMulti = [];
        $.ajax({
            type: "POST",
            url: "BrowseDataPDTTableCallView",
            data: {
                aPriceType: ["Cost", "tCN_Cost", "Company", "1"],
                NextFunc: "",
                SPL: $("#oetDBRFrmSplCode").val(),
                BCH: $("#oetDBRFrmBchCode").val(),
                tInpSesSessionID: $('#ohdSesSessionID').val(),
                tInpUsrCode: $('#ohdDBRUsrCode').val(),
                tInpLangEdit: $('#ohdDBRLangEdit').val(),
                tInpSesUsrLevel: $('#ohdSesUsrLevel').val(),
                tInpSesUsrBchCom: $('#ohdSesUsrBchCom').val(),
                tWhere: [" AND FTPdtStkControl = 1 "],
                tTextScan: ptTextScan
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                // $('#oetDBRInsertBarcode').attr('readonly',false);
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                if (oText == '800') {
                    $('#oetDBRInsertBarcode').attr('readonly', false);
                    $('#odvDBRModalPDTNotFound').modal('show');
                    $('#oetDBRInsertBarcode').val('');
                } else {
                    if (oText.length > 1) {

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvDBRModalPDTMoreOne').modal('show');
                        $('#odvDBRModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
                        for (i = 0; i < oText.length; i++) {
                            var aNewReturn = JSON.stringify(oText[i]);
                            var tTest = "[" + aNewReturn + "]";
                            var oEncodePackData = window.btoa(unescape(encodeURIComponent(tTest)));
                            var tHTML = "<tr class='xCNColumnPDTMoreOne" + i + " xCNColumnPDTMoreOne' data-information='" + oEncodePackData + "' style='cursor: pointer;'>";
                            tHTML += "<td>" + oText[i].pnPdtCode + "</td>";
                            tHTML += "<td>" + oText[i].packData.PDTName + "</td>";
                            tHTML += "<td>" + oText[i].packData.PUNName + "</td>";
                            tHTML += "<td>" + oText[i].ptBarCode + "</td>";
                            tHTML += "</tr>";
                            $('#odvDBRModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick', function(e) {
                            $('#odvDBRModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            FSvDBRAddPdtIntoDocDTTemp(tJSON); //Client
                            FSvDBRAddBarcodeIntoDocDTTemp(tJSON);
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
                        FSvDBRAddPdtIntoDocDTTemp(aNewReturn); //Client
                        // JCNxCloseLoading();
                        // $('#oetDBRInsertBarcode').attr('readonly',false);
                        // $('#oetDBRInsertBarcode').val('');
                        FSvDBRAddBarcodeIntoDocDTTemp(aNewReturn); //Server
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
            $("#odvDBRModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function(index) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                FSvDBRAddPdtIntoDocDTTemp(tJSON);
                FSvDBRAddBarcodeIntoDocDTTemp(tJSON);
            });
        } else {
            $('#oetDBRInsertBarcode').attr('readonly', false);
            $('#oetDBRInsertBarcode').val('');
        }
    }

    //หลังจากค้นหาเสร็จแล้ว
    function FSvDBRAddBarcodeIntoDocDTTemp(ptPdtData) {
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            // JCNxOpenLoading();
            var ptXthDocNoSend = "";
            if ($("#ohdDBRRoute").val() == "docDBREventEdit") {
                ptXthDocNoSend = $("#oetDBRDocNo").val();
            }
            var tDBROptionAddPdt = $('#ocmDBRFrmInfoOthReAddPdt').val();
            var nKey = parseInt($('#otbDBRDocPdtAdvTableList tr:last').attr('data-seqno'));

            $('#oetDBRInsertBarcode').attr('readonly', false);
            $('#oetDBRInsertBarcode').val('');

            $.ajax({
                type: "POST",
                url: "docDBRAddPdtIntoDTDocTemp",
                data: {
                    'tSelectBCH': $('#oetDBRFrmBchCode').val(),
                    'tDBRDocNo': ptXthDocNoSend,
                    'tDBROptionAddPdt': tDBROptionAddPdt,
                    'tDBRPdtData': ptPdtData,
                    'ohdSesSessionID': $('#ohdSesSessionID').val(),
                    'ohdDBRUsrCode': $('#ohdDBRUsrCode').val(),
                    'ohdDBRLangEdit': $('#ohdDBRLangEdit').val(),
                    'ohdSesUsrLevel': $('#ohdSesUsrLevel').val(),
                    'ohdDBRSesUsrBchCode': $('#ohdDBRSesUsrBchCode').val(),
                    'tSeqNo': nKey,
                    'nVatRate': $('#ohdDBRFrmSplVatRate').val(),
                    'nVatCode': $('#ohdDBRFrmSplVatCode').val()
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    // JSvDBRLoadPdtDataTableHtml();
                    var aResult = JSON.parse(oResult);

                    if (aResult['nStaEvent'] == 1) {
                        JCNxCloseLoading();
                        // $('#oetDBRInsertBarcode').attr('readonly',false);
                        // $('#oetDBRInsertBarcode').val('');
                        // if(tDBROptionAddPdt=='1'){
                        //     JSvDBRCallEndOfBill();
                        // }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // JCNxResponseError(jqXHR, textStatus, errorThrown);
                    FSvDBRAddBarcodeIntoDocDTTemp(ptPdtData);
                }
            });
        } else {
            JCNxphowMsgSessionExpired();
        }
    }

    // เวลา Click Tab1 ให้ Button Hide
    $('#oliDBRContentProduct').click(function() {
        $('#odvDBRContentHDRef').hide();
        $('#odvDBRContentProduct').show();
    });

    // เวลา Click Tab2 ให้ Button Show
    $('#oliDBRContentHDRef').click(function() {
        $('#odvDBRContentProduct').hide();
        $('#odvDBRContentHDRef').show();
        $('#obtDBRAddDocRef').show();
    });

    //Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    function JSxDBREventCheckShowHDDocRef(){
        var tDBRRefType = $('#ocbDBRRefType').val();
        if( tDBRRefType == '1' ){
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();
        }else{
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }
</script>
