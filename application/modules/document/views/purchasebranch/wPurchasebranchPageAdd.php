<?php
    $tSesUsrLevel   = $this->session->userdata('tSesUsrLevel');
    $tUserBchName   = $this->session->userdata('tSesUsrBchNameDefault');
    $tUserBchCode   = $this->session->userdata('tSesUsrBchCodeDefault');
    if(isset($aDataDocHD) && $aDataDocHD['rtCode'] == '1'){
        $aDataDocHD             = @$aDataDocHD['raItems'];
        // echo print_r($aDataDocHD);
        $tPRBRoute               = "docPRBEventEdit";
        $nPRBAutStaEdit          = 1;
        $tPRBDocNo               = $aDataDocHD['rtXthDocNo'];
        $dPRBDocDate             = date("Y-m-d",strtotime($aDataDocHD['rdXthDocDate']));
        $dPRBDocTime             = date("H:i",strtotime($aDataDocHD['rdXthDocDate']));
        $tPRBCreateBy            = $aDataDocHD['rtCreateBy'];
        $tPRBUsrNameCreateBy     = $aDataDocHD['rtUsrName'];

        $tPRBStaDoc              = $aDataDocHD['rtXthStaDoc'];
        $tPRBStaApv              = $aDataDocHD['rtXthStaApv'];


        $tPRBSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");

        $tPRBUsrCode             = $this->session->userdata('tSesUsername');
        $tPRBLangEdit            = $this->session->userdata("tLangEdit");

        $tPRBApvCode             = $aDataDocHD['rtXthApvCode'];
        $tPRBUsrNameApv          = $aDataDocHD['rtXthApvName'];
        $tPRBRefPoDoc            = "";
        $tPRBRefIntDoc           = $aDataDocHD['rtXthRefInt'];
        $dPRBRefIntDocDate       = $aDataDocHD['rdXthRefIntDate'];
        $tPRBRefExtDoc           = $aDataDocHD['rtXthRefExt'];
        $dPRBRefExtDocDate       = $aDataDocHD['rdXthRefExtDate'];

        $nPRBStaRef              = $aDataDocHD['rnXthStaRef'];

        $tPRBBchCode             = $aDataDocHD['rtBchCode'];
        $tPRBBchName             = $aDataDocHD['rtBchName'];
        $tPRBUserBchCode         = $tUserBchCode;
        $tPRBUserBchName         = $tUserBchName;


        $nPRBStaDocAct           = $aDataDocHD['rnXthStaDocAct'];
        $tPRBFrmDocPrint         = $aDataDocHD['rnXthDocPrint'];
        $tPRBFrmRmk              = $aDataDocHD['rtXthRmk'];

        $tPRBAgnCode             = $aDataDocHD['rtAgnCode'];
        $tPRBAgnName             = $aDataDocHD['rtAgnName'];

        $tPRBAgnCodeTo             = $aDataDocHD['rtAgnCodeTo'];
        $tPRBAgnNameTo             = $aDataDocHD['rtAgnNameTo'];
        $tPRBBchCodeTo             = $aDataDocHD['rtBchCodeFrm'];
        $tPRBBchNameTo             = $aDataDocHD['rtBchNameTo'];
        $tPRBWahCodeTo             = $aDataDocHD['rtWahCodeTo'];
        $tPRBWahNameTo             = $aDataDocHD['rtWahNameTo'];
        // $tPRBWahCodeTo              = "";
        // $tPRBWahNameTo              = "";

        $tPRBAgnCodeShip             = $aDataDocHD['rtAgnCodeShip'];
        $tPRBAgnNameShip             = $aDataDocHD['rtAgnNameShip'];
        $tPRBBchCodeShip             = $aDataDocHD['rtBchCodeShip'];
        $tPRBBchNameShip             = $aDataDocHD['rtBchNameShip'];
        // $tPRBWahCodeShip             = $aDataDocHD['rtWahCodeShip'];
        // $tPRBWahNameShip             = $aDataDocHD['rtWahNameShip'];

        $tPRBWahCodeShip            = "";
        $tPRBWahNameShip             = "";


        // $tPRBRsnCode             = $aDataDocHD['rtRsnCode'];
        // $tPRBRsnName             = $aDataDocHD['rtRsnName'];
        $tPRBRsnCode             = "";
        $tPRBRsnName             = "";




        $tPRBVatInOrEx           = 1;

        $nStaUploadFile          = 2;
        $nPRBStaDocAct           = $aDataDocHD['rnXthStaDocAct'];

    }else{
        $tPRBRoute               = "docPRBEventAdd";
        $nPRBAutStaEdit          = 0;
        $tPRBDocNo               = "";
        $dPRBDocDate             = "";
        $dPRBDocTime             = date('H:i:s');
        $tPRBCreateBy            = $this->session->userdata('tSesUsrUsername');
        $tPRBUsrNameCreateBy     = $this->session->userdata('tSesUsrUsername');
        $nPRBStaRef              = 0;
        $tPRBStaDoc              = 1;
        $tPRBStaApv              = NULL;


        $tPRBSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");

        $tPRBUsrCode             = $this->session->userdata('tSesUsername');
        $tPRBLangEdit            = $this->session->userdata("tLangEdit");

        $tPRBApvCode             = "";
        $tPRBUsrNameApv          = "";
        $tPRBRefPoDoc            = "";
        $tPRBRefIntDoc           = "";
        $dPRBRefIntDocDate       = "";
        $tPRBRefExtDoc           = "";
        $dPRBRefExtDocDate       = "";


        $tPRBBchCode             = $tBchCode;
        $tPRBBchName             = $tBchName;
        $tPRBUserBchCode         = $tBchCode;
        $tPRBUserBchName         = $tBchName;

        $tPRBAgnCode             = $this->session->userdata("tSesUsrAgnCode");
        $tPRBAgnName             = $this->session->userdata("tSesUsrAgnName");

        if(isset($aBCHHQ['FTBchCode'])){
            $tPRBAgnCodeTo             = $aBCHHQ['FTAgnCode'];
            $tPRBAgnNameTo             = $aBCHHQ['FTAgnName'];
        }else{
            $tPRBAgnCodeTo             = "";
            $tPRBAgnNameTo             = "";
        }

        if(isset($aBCHHQ['FTBchCode'])){
            $tPRBBchCodeTo             = $aBCHHQ['FTBchCode'];
            $tPRBBchNameTo             = $aBCHHQ['FTBchName'];
        }else{
            $tPRBBchCodeTo             = "";
            $tPRBBchNameTo             = "";
        }


        $tPRBWahCodeTo             = $aConfigSysWareHouseTrue['FTWahCode'];
        $tPRBWahNameTo             = $aConfigSysWareHouseTrue['FTWahName'];

        $tPRBAgnCodeShip             = $this->session->userdata("tSesUsrAgnCode");
        $tPRBAgnNameShip             = $this->session->userdata("tSesUsrAgnName");
        $tPRBBchCodeShip             = $this->session->userdata('tSesUsrBchCodeDefault');
        $tPRBBchNameShip             = $this->session->userdata('tSesUsrBchNameDefault');
        $tPRBWahCodeShip             = "";
        $tPRBWahNameShip             = "";

        $nPRBStaDocAct           = "";
        $tPRBFrmDocPrint         = "";
        $tPRBFrmRmk              = "";

        $tPRBRsnCode             = "";
        $tPRBRsnName             = "";

        $tPRBVatInOrEx           = $tCmpRetInOrEx;
        $tPRBSplPayMentType      = "";


        $nStaUploadFile         = 1;
        $nPRBStaDocAct           = "";
    }
    if(empty($tPRBBchCode) && empty($tPRBShopCode)){
        $tASTUserType   = "HQ";
    }else{
        if(!empty($tPRBBchCode) && empty($tPRBShopCode)){
            $tASTUserType   = "BCH";
        }else if( !empty($tPRBBchCode) && !empty($tPRBShopCode)){
            $tASTUserType   = "SHP";
        }else{
            $tASTUserType   = "";
        }
    }
?>

<style>
    #odvRowDataEndOfBill .panel-heading{
        padding-top     : 10px !important;
        padding-bottom  : 10px !important;
    }
    #odvRowDataEndOfBill .panel-body{
        padding-top     : 0px !important;
        padding-bottom  : 0px !important;
    }
    #odvRowDataEndOfBill .list-group-item {
        padding-left    : 0px !important;
        padding-right   : 0px !important;
        border          : 0px solid #ddd;
    }

</style>
<form id="ofmPRBFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <input type="hidden" id="ohdPRBPage" name="ohdPRBPage" value="1">
    <input type="hidden" id="ohdPRBStaaImport" name="ohdPRBStaaImport" value="0">
    <input type="hidden" id="ohdPRBFrmSplInfoVatInOrEx" name="ohdPRBFrmSplInfoVatInOrEx" value="<?=$tPRBVatInOrEx?>">
    <input type="hidden" id="ohdPRBRoute" name="ohdPRBRoute" value="<?php echo $tPRBRoute;?>">
    <input type="hidden" id="ohdPRBCheckClearValidate" name="ohdPRBCheckClearValidate" value="0">
    <input type="hidden" id="ohdPRBCheckSubmitByButton" name="ohdPRBCheckSubmitByButton" value="0">
    <input type="hidden" id="ohdPRBAutStaEdit" name="ohdPRBAutStaEdit" value="<?php echo $nPRBAutStaEdit;?>">
    <input type="hidden" id="ohdPRBODecimalShow" name="ohdPRBODecimalShow" value="<?=$nOptDecimalShow?>">
    <input type="hidden" id="ohdPRBStaDoc" name="ohdPRBStaDoc" value="<?php echo $tPRBStaDoc;?>">
    <input type="hidden" id="ohdPRBStaApv" name="ohdPRBStaApv" value="<?php echo $tPRBStaApv;?>">

    <input type="hidden" id="ohdPRBSesUsrBchCode" name="ohdPRBSesUsrBchCode" value="<?php echo $tPRBSesUsrBchCode; ?>">
    <input type="hidden" id="ohdPRBBchCode" name="ohdPRBBchCode" value="<?php echo $tPRBBchCode; ?>">

    <input type="hidden" id="ohdPRBUsrCode" name="ohdPRBUsrCode" value="<?php echo $tPRBUsrCode?>">


    <input type="hidden" id="ohdPRBApvCodeUsrLogin" name="ohdPRBApvCodeUsrLogin" value="<?php echo $tPRBUsrCode; ?>">
    <input type="hidden" id="ohdPRBLangEdit" name="ohdPRBLangEdit" value="<?php echo $tPRBLangEdit; ?>">
    <input type="hidden" id="ohdPRBOptAlwSaveQty" name="ohdPRBOptAlwSaveQty" value="<?php echo $nOptDocSave?>">
    <input type="hidden" id="ohdSesSessionID" name="ohdSesSessionID" value="<?=$this->session->userdata('tSesSessionID')?>"  >
    <input type="hidden" id="ohdSesSessionName" name="ohdSesSessionName" value="<?=$this->session->userdata('tSesUsrUsername')?>"  >
    <input type="hidden" id="ohdSesUsrLevel" name="ohdSesUsrLevel" value="<?=$this->session->userdata('tSesUsrLevel')?>"  >
    <input type="hidden" id="ohdSesUsrBchCom" name="ohdSesUsrBchCom" value="<?=$this->session->userdata('tSesUsrBchCom')?>"  >
    <input type="hidden" id="ohdPRBValidatePdt" name="ohdPRBValidatePdt" value="<?=language('document/purchasebranch/purchasebranch', 'tPRBPleaseSeletedPDTIntoTable')?>">
    <input type="hidden" id="ohdPRBSubmitWithImp" name="ohdPRBSubmitWithImp" value="0">
    <input type="hidden" id="ohdPRBVATInOrEx" name="ohdPRBVATInOrEx" value="">
    <input type="hidden" id="ohdPRBPayType" name="ohdPRBPayType" value="">

    <input type="hidden" id="ohdPRBValidatePdtImp" name="ohdPRBValidatePdtImp" value="<?=language('document/purchasebranch/purchasebranch', 'tPRBNotFoundPdtCodeAndBarcodeImpList')?>">

    <button style="display:none" type="submit" id="obtPRBSubmitDocument" onclick="JSxPRBAddEditDocument()"></button>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPRBHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBDoucment'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvPRBDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPRBDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmAppove');?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelFrmDocNo'); ?></label>
                                <?php if(isset($tPRBDocNo) && empty($tPRBDocNo)):?>
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" id="ocbPRBStaAutoGenCode" name="ocbPRBStaAutoGenCode" maxlength="1" checked="checked">
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelFrmAutoGenCode');?></span>
                                    </label>
                                </div>
                                <?php endif;?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input
                                        type="text"
                                        class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai"
                                        id="oetPRBDocNo"
                                        name="oetPRBDocNo"
                                        maxlength="20"
                                        value="<?php echo $tPRBDocNo;?>"
                                        data-validate-required="<?php echo language('document/purchaseorder/purchaseorder','tPRBPlsEnterOrRunDocNo'); ?>"
                                        data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder','tPRBPlsDocNoDuplicate'); ?>"
                                        placeholder="<?php echo language('document/purchasebranch/purchasebranch','tPRBLabelFrmDocNo');?>"
                                        style="pointer-events:none"
                                        readonly
                                    >
                                    <input type="hidden" id="ohdPRBCheckDuplicateCode" name="ohdPRBCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelFrmDocDate');?></label>
                                    <div class="input-group">
                                        <?php if ($dPRBDocDate == '') {
                                            $dPRBDocDate = '';
                                        } ?>
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNDatePicker xCNInputMaskDate"
                                            id="oetPRBDocDate"
                                            name="oetPRBDocDate"
                                            value="<?php echo $dPRBDocDate; ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPRBDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmDocTime');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNTimePicker xCNInputMaskTime"
                                            id="oetPRBDocTime"
                                            name="oetPRBDocTime"
                                            value="<?php echo $dPRBDocTime; ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPRBDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelFrmCreateBy');?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdPRBCreateBy" name="ohdPRBCreateBy" value="<?php echo $tPRBCreateBy?>">
                                            <label><?php echo $tPRBUsrNameCreateBy?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                                if($tPRBRoute == "docPRBEventAdd"){
                                                    $tPRBLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                                }else{
                                                    $tPRBLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc'.$tPRBStaDoc);
                                                }
                                            ?>
                                            <label><?php echo $tPRBLabelStaDoc;?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaApv'.$tPRBStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>
                             <!-- สถานะอ้างอิงเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmStaRef'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">

                                            <label><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmStaRef'.$nPRBStaRef); ?></label>

                                        </div>
                                    </div>
                                </div>

                                <?php if(isset($tPRBDocNo) && !empty($tPRBDocNo)):?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdPRBApvCode" name="ohdPRBApvCode" maxlength="20" value="<?php echo $tPRBApvCode?>">
                                                <label>
                                                    <?php echo (isset($tPRBUsrNameApv) && !empty($tPRBUsrNameApv))? $tPRBUsrNameApv : "-" ?>
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

            <!-- Panel สาขาที่รับของ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPRBReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabeAcpBch');?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvPRBDataReferenceDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPRBDataReferenceDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
                            <div class="form-group m-b-0">
                                    <?php
                                        $tPRBDataInputBchCode   = "";
                                        $tPRBDataInputBchName   = "";
                                        if($tPRBRoute  == "docPRBEventAdd"){
                                            $tPRBDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                                            $tPRBDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                                            $tDisabledBch = '';
                                        }else{
                                            $tPRBDataInputBchCode    = $tPRBBchCode;
                                            $tPRBDataInputBchName    = $tPRBBchName;
                                            $tDisabledBch = 'disabled';
                                        }
                                    ?>
                                <!--สาขา-->
                                <script>
                                    var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                                    if( tUsrLevel != "HQ" ){
                                        $('#oimPRBBrowseAgn').attr("disabled", true);
                                        $('#obtPRBBrowseBCH').attr('disabled',true);
                                        $('#oimPRBBrowseAgnShip').attr("disabled", true);
                                        // $('#obtPRBBrowseBCHShip').attr('disabled',true);
                                    }
                                </script>
                                <!--Agn Browse-->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
                                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetPRBAgnCode" name="oetPRBAgnCode" maxlength="5" value="<?= @$tPRBAgnCode; ?>">
                                        <input  type="text"
                                                class="form-control xControlForm xWPointerEventNone"
                                                id="oetPRBAgnName" name="oetPRBAgnName"
                                                maxlength="100"
                                                placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>"
                                                value="<?= @$tPRBAgnName; ?>"
                                                data-validate-required="<?php echo language('document/purchasebranch/purchasebranch', 'tPRBPlsSelectAgn') ?>"
                                                readonly>
                                        <span class="input-group-btn">
                                            <button id="oimPRBBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn" <?= @$tDisabledBch ?>>
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!--Agn Bch-->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmBranch')?></label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                                id="oetPRBFrmBchCode"
                                                name="oetPRBFrmBchCode"
                                                maxlength="5"
                                                value="<?php echo @$tPRBDataInputBchCode?>"
                                                data-bchcodeold = "<?php echo @$tPRBDataInputBchCode?>"
                                            >
                                            <input
                                                type="text"
                                                class="form-control xControlForm xWPointerEventNone"
                                                id="oetPRBFrmBchName"
                                                name="oetPRBFrmBchName"
                                                maxlength="100"
                                                placeholder="<?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmBranch')?>"
                                                data-validate-required="<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsEnterBch'); ?>"
                                                value="<?php echo @$tPRBDataInputBchName?>"
                                                readonly
                                            >
                                            <span class="input-group-btn xWConditionSearchPdt">
                                                <button id="obtPRBBrowseBCH" type="button" class="btn xCNBtnBrowseAddOn" <?= @$tDisabledBch ?>>
                                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                <!-- Ref Doc Int Browse -->
                                <div class="form-group" >
                                    <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelRefDocInt');?></label>
                                        <input
                                            type="text"
                                            class="form-control xControlForm"
                                            id="oetPRBRefDocIntName" name="oetPRBRefDocIntName"
                                            maxlength="20"
                                            value="<?php echo $tPRBRefIntDoc ?>"
                                            placeholder="<?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelRefDocInt')?>"
                                        >
                                </div>

                                <!-- Ref Doc Int Datepicker -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelRefDocIntDate');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNDatePicker xCNInputMaskDate"
                                            id="oetPRBRefIntDocDate"
                                            name="oetPRBRefIntDocDate"
                                            autocomplete="off"
                                            value="<?php echo $dPRBRefIntDocDate ?>"
                                            placeholder="<?php echo language('document/purchasebranch/purchasebranch', 'tPRBPHDRefTSCode')?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPRBBrowseRefIntDocDate" name="obtPRBBrowseRefIntDocDate" type="button" class="btn xCNDatePicker xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- Ref Doc Ext input -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelRefDocExt');?></label>
                                    <input
                                        type="text"
                                        class="form-control xControlForm"
                                        id="oetPRBSplRefDocExt"
                                        name="oetPRBSplRefDocExt"
                                        maxlength="20"
                                        value="<?php echo $tPRBRefExtDoc;?>"
                                        placeholder="<?php echo language('document/purchasebranch/purchasebranch','tPRBLabelRefDocExt');?>"
                                    >
                                </div>

                                <!-- Ref Doc Ext Datepicker -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelRefDocExtDate');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNDatePicker xCNInputMaskDate"
                                            id="oetPRBRefDocExtDate"
                                            name="oetPRBRefDocExtDate"
                                            autocomplete="off"
                                            value="<?php echo $dPRBRefExtDocDate;?>"
                                            placeholder="<?php echo language('document/purchasebranch/purchasebranch','tPRBPHDRefTSCode');?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPRBRefDocExtDate" name="obtPRBRefDocExtDate" type="button" class="btn xCNDatePicker xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

               <!-- Panel ไปยังสาขา -->
               <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPRBConditionDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabeAcpBchTo'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvPRBDataConditionDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPRBDataConditionDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <div class="form-group m-b-0">


                                <!--Agn Browse-->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
                                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetPRBAgnCodeTo" name="oetPRBAgnCodeTo" maxlength="5" value="<?=$tPRBAgnCodeTo?>">
                                        <input  type="text"
                                                class="form-control xControlForm xWPointerEventNone"
                                                id="oetPRBAgnNameTo" name="oetPRBAgnNameTo"
                                                maxlength="100"
                                                placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>"
                                                value="<?=$tPRBAgnNameTo?>"
                                                readonly>
                                        <span class="input-group-btn">
                                            <button id="oimPRBBrowseAgnTo" disabled type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!--Agn Bch-->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmBranch')?></label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                                id="oetPRBFrmBchCodeTo"
                                                name="oetPRBFrmBchCodeTo"
                                                maxlength="5"
                                                value="<?=$tPRBBchCodeTo?>"

                                            >
                                            <input
                                                type="text"
                                                class="form-control xControlForm xWPointerEventNone"
                                                id="oetPRBFrmBchNameTo"
                                                name="oetPRBFrmBchNameTo"
                                                maxlength="100"
                                                placeholder="<?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmBranch')?>"
                                                data-validate-required="<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsEnterBch'); ?>"
                                                value="<?=$tPRBBchNameTo?>"
                                                readonly
                                            >
                                            <span class="input-group-btn xWConditionSearchPdt">
                                                <button id="obtPRBBrowseBCHTo" disabled type="button" class="btn xCNBtnBrowseAddOn ">
                                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <!-- Condition คลังสินค้า -->
                                <!-- <div class="form-group">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelFrmWah');?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetPRBFrmWahCodeTo" name="oetPRBFrmWahCodeTo" maxlength="5" value="<?=$tPRBWahCodeTo?>">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xWPointerEventNone"
                                            id="oetPRBFrmWahNameTo"
                                            name="oetPRBFrmWahNameTo"
                                            value="<?=$tPRBWahNameTo?>"
                                            data-validate-required="<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsEnterWah'); ?>"
                                            placeholder="<?php echo language('document/purchasebranch/purchasebranch','tPRBLabelFrmWah');?>"
                                            readonly
                                        >
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtPRBBrowseWahouseTo" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
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



               <!-- Panel สาขาปลายทาง -->
               <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPRBConditionDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabeAcpBchShip'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvPRBDataConditionDocShip" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPRBDataConditionDocShip" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <div class="form-group m-b-0">


                                <!--Agn Browse-->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
                                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetPRBAgnCodeShip" name="oetPRBAgnCodeShip" maxlength="5" value="<?=$tPRBAgnCodeShip?>">
                                        <input  type="text"
                                                class="form-control xControlForm xWPointerEventNone"
                                                id="oetPRBAgnNameShip" name="oetPRBAgnNameShip"
                                                maxlength="100"
                                                placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>"
                                                value="<?=$tPRBAgnNameShip?>"
                                                readonly>
                                        <span class="input-group-btn">
                                            <button id="oimPRBBrowseAgnShip" type="button" class="btn xCNBtnBrowseAddOn ">
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!--Agn Bch-->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmBranch')?></label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                                id="oetPRBFrmBchCodeShip"
                                                name="oetPRBFrmBchCodeShip"
                                                maxlength="5"
                                                value="<?=$tPRBBchCodeShip?>"
                                            >
                                            <input
                                                type="text"
                                                class="form-control xControlForm xWPointerEventNone"
                                                id="oetPRBFrmBchNameShip"
                                                name="oetPRBFrmBchNameShip"
                                                maxlength="100"
                                                placeholder="<?php echo language('document/purchasebranch/purchasebranch', 'tPRBLabelFrmBranch')?>"
                                                data-validate-required="<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsEnterBch'); ?>"
                                                value="<?=$tPRBBchNameShip?>"
                                                readonly
                                            >
                                            <span class="input-group-btn xWConditionSearchPdt">
                                                <button id="obtPRBBrowseBCHShip" type="button" class="btn xCNBtnBrowseAddOn ">
                                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <!-- Condition คลังสินค้า -->
                                <!-- <div class="form-group">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelFrmWah');?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetPRBFrmWahCodeShip" name="oetPRBFrmWahCodeShip" maxlength="5" value="<?=$tPRBWahCodeTo?>">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xWPointerEventNone"
                                            id="oetPRBFrmWahNameShip"
                                            name="oetPRBFrmWahNameShip"
                                            value="<?=$tPRBWahNameTo?>"
                                            data-validate-required="<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsEnterWah'); ?>"
                                            placeholder="<?php echo language('document/purchasebranch/purchasebranch','tPRBLabelFrmWah');?>"
                                            readonly
                                        >
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtPRBBrowseWahouseShip" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
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


            <!-- Panel อืนๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPRBInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/purchasebranch/purchasebranch','อื่นๆ');?></label>
                    <a class="xCNMenuplus " role="button" data-toggle="collapse"  href="#odvPRBDataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPRBDataInfoOther" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" value="1" id="ocbPRBFrmInfoOthStaDocAct" name="ocbPRBFrmInfoOthStaDocAct" maxlength="1" <?php echo ($nPRBStaDocAct == '1' || empty($nPRBStaDocAct)) ? 'checked' : ''; ?> checked = "checked">
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>
                                <!-- สถานะอ้างอิง -->
                                <?php if ($nPRBStaRef == 0) {
                                            $tSelect = "selected";
                                            $tSelect2 = "";
                                            $tSelect3 = "";
                                        }elseif ($nPRBStaRef == 1) {
                                            $tSelect = "";
                                            $tSelect2 = "selected";
                                            $tSelect3 = "";
                                        }elseif ($nPRBStaRef == 2) {
                                            $tSelect = "";
                                            $tSelect2 = "";
                                            $tSelect3 = "selected";
                                        }?>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthRef');?></label>
                                    <select class="selectpicker xWPRBDisabledOnApv form-control xControlForm" id="ocmPRBFrmInfoOthRef" name="ocmPRBFrmInfoOthRef" maxlength="1">
                                        <option value="0" <?php echo $tSelect;?>><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthRef0');?></option>
                                        <option value="1" <?php echo $tSelect2;?>><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthRef1');?></option>
                                        <option value="2" <?php echo $tSelect3;?>><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthRef2');?></option>
                                    </select>
                                </div>
                                <!-- จำนวนครั้งที่พิมพ์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthDocPrint');?></label>
                                    <input
                                        type="text"
                                        class="form-control xControlForm text-right"
                                        id="ocmPRBFrmInfoOthDocPrint"
                                        name="ocmPRBFrmInfoOthDocPrint"
                                        value="<?php echo $tPRBFrmDocPrint;?>"
                                        readonly
                                    >
                                </div>
                                <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthReAddPdt');?></label>
                                    <select class="form-control xControlForm selectpicker xWPRBDisabledOnApv" id="ocmPRBFrmInfoOthReAddPdt" name="ocmPRBFrmInfoOthReAddPdt">
                                        <option value="1" selected><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt1');?></option>
                                        <!-- <option value="2"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt2');?></option> -->
                                    </select>
                                </div>
                                <!-- การปัดเศษประเภทแนะนำ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelSuggesAddPdt');?></label>
                                    <select class="form-control xControlForm selectpicker xWPRBDisabledOnApv" id="ocmPRBSuggesAddPdt" name="ocmPRBSuggesAddPdt">
                                        <option value="1" selected><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelSuggesAddPdt1');?></option>
                                        <option value="2"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelSuggesAddPdt2');?></option>
                                        <option value="3"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelSuggesAddPdt3');?></option>
                                    </select>
                                </div>
                            <!-- เหตุผล -->
                                <!-- <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubReason'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="input100 xCNHide" id="oetPRBReasonCode" name="oetPRBReasonCode" value="<?=$tPRBRsnCode?>">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetPRBReasonName" name="oetPRBReasonName" value="<?=$tPRBRsnName?>" readonly data-validate-required="<?php echo language('document/adjuststocksub/adjuststocksub', 'tASTPlsEnterRsnCode'); ?>">
                                        <span class="input-group-btn xWConditionSearchPdt">
                                            <button id="obtPRBBrowseReason" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                                <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div> -->
                                <!-- เหตุผล -->

                                <!-- หมายเหตุ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthRemark');?></label>
                                    <textarea
                                        class="form-control xControlRmk xWConditionSearchPdt"
                                        id="otaPRBFrmInfoOthRmk"
                                        name="otaPRBFrmInfoOthRmk"
                                        rows="10"
                                        maxlength="200"
                                        style="resize: none;height:86px;"
                                    ><?php echo $tPRBFrmRmk?></textarea>
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
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvPRBShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>


                    var oSOCallDataTableFile = {
                        ptElementID     : 'odvPRBShowDataTable',
                        ptBchCode       : $('#oetPRBFrmBchCode').val(),
                        ptDocNo         : $('#oetPRBDocNo').val(),
                        ptDocKey        : 'TCNTPdtReqBchHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : 'JSxSoCallBackUploadFile',
                        ptStaApv        : $('#ohdPRBStaApv').val(),
                        ptStaDoc        : $('#ohdPRBStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oSOCallDataTableFile);
                </script>
            </div>
        </div>
        <div class="col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <!-- ตารางรายการสินค้า -->
                <div id="odvPRBDataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">

                                <div class="row p-t-10">

                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvPRBCSearchPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                                                <span class="input-group-btn">
                                                    <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvPRBCSearchPdtHTML()">
                                                        <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-6 text-right">

                                        <div class="row">

                                            <!--คลัง -->
                                            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4 text-right">
                                                <div class="input-group">
                                                    <input type="text" class="form-control xControlForm xCNHide" id="oetPRBFrmWahCodeShip" name="oetPRBFrmWahCodeShip" maxlength="5" value="<?=$tPRBWahCodeTo?>">
                                                    <input
                                                        type="text"
                                                        class="form-control xControlForm xWPointerEventNone"
                                                        id="oetPRBFrmWahNameShip"
                                                        name="oetPRBFrmWahNameShip"
                                                        value="<?=$tPRBWahNameTo?>"
                                                        data-validate-required="<?php echo language('document/purchasebranch/purchasebranch','tPRBPlsEnterWah'); ?>"
                                                        placeholder="<?php echo language('document/purchasebranch/purchasebranch','tPRBLabelFrmWah');?>"
                                                        readonly
                                                    >
                                                    <span class="xWConditionSearchPdt input-group-btn">
                                                        <button id="obtPRBBrowseWahouseShip" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                            <img class="xCNIconFind">
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>

                                            <!--ตัวเลือกการสร้างสินค้า-->
                                            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-8 text-right">
                                                <div id="odvPRBMngAuto" class="btn-group xCNDropDrownGroup">

                                                    <button type="button" class="btn xCNBTNMngTable xWConditionSearchPdt" data-toggle="dropdown">
                                                    <?php echo language('document/purchasebranch/purchasebranch','tPRBPDTOption')?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li id="oliAutoInsertTableDT">
                                                            <a data-toggle="modal" id="obtPRBAutoInsertTableDT">
                                                            <?php echo language('document/purchasebranch/purchasebranch','tPRBCreateAuto')?>
                                                            </a>
                                                        </li>

                                                        <li id="oliAutoInsertTableDT2">
                                                            <a data-toggle="modal" id="obtPRBAutoInsertTableDT2">
                                                            <?php echo language('document/purchasebranch/purchasebranch','tPRBCreateRent')?>
                                                            </a>
                                                        </li>
                                                    </ul>

                                                </div>

                                                <!--ตัวเลือก-->
                                                <div id="odvPRBMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup">

                                                    <button type="button" class="btn xCNBTNMngTable xWConditionSearchPdt" data-toggle="dropdown">
                                                        <?php echo language('common/main/main','tCMNOption')?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li id="oliPRBBtnDeleteMulti">
                                                            <a data-toggle="modal" data-target="#odvPRBModalDelPdtInDTTempMultiple"><?php echo language('common/main/main','tDelAll')?></a>
                                                        </li>
                                                        <li id="oliPRBNoStockDT">
                                                            <a id="obtPRBNoStockDT"><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelNoStock')?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!-- <button type="button" class="btn btn-primary" id="obtPRBNoStockDT">
                                                <?php //echo language('document/purchasebranch/purchasebranch','tPRBLabelNoStock')?>
                                            </button> -->

                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                                        <!--ค้นหาจากบาร์โค๊ด-->
                                        <div class="form-group" style="width: 85%;">
                                            <input type="text" class="form-control xControlForm" id="oetPRBInsertBarcode" autocomplete="off" name="oetPRBInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);"  placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า" >
                                        </div>

                                        <!--เพิ่มสินค้าแบบปกติ-->
                                        <div class="form-group">
                                            <div style="position: absolute;right: 15px;top:-5px;">
                                                <button type="button" id="obtPRBDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row p-t-10" id="odvPRBDataPdtTableDTTemp">
                                </div>
                            <!--ส่วนสรุปท้ายบิล-->
                            <div class="odvRowDataEndOfBill" id="odvRowDataEndOfBill">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label class="pull-left mark-font"><?=language('document/purchaseorder/purchaseorder','จำนวนรายการสินค้าที่สั่งรวมทั้งสิ้น');?></label>
                                        <label class="pull-right mark-font"><span class="mark-font xShowQtyFooter">0</span> <?=language('document/purchaseorder/purchaseorder','tPOItems');?></label>
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
</form>

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
    <div id="odvPRBModalAppoveDoc" class="modal fade">
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
                    <button onclick="JSxPRBApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
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
    <div class="modal fade" id="odvPRBPopupCancel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('document/purchasebranch/purchasebranch','tPRBCancelDoc')?></label>
                </div>
                <div class="modal-body">
                    <p id="obpMsgApv"><?php echo language('document/purchasebranch/purchasebranch','tPRBCancelDocWarnning')?></p>
                    <p><strong><?php echo language('document/purchasebranch/purchasebranch','tPRBCancelDocConfrim')?></strong></p>
                </div>
                <div class="modal-footer">
                    <button onclick="JSnPRBCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
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
    <div class="modal fade" id="odvPRBOrderAdvTblColumns" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <div class="modal-body" id="odvPRBModalBodyAdvTable">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                    <button id="obtPRBSaveAdvTableColums" type="button" class="btn btn-primary"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
<!-- ============================================================================================================================================================================= -->

<!-- ============================================================== View Modal Delete Product In DT DocTemp Multiple  ============================================================ -->
    <div id="odvPRBModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main','tModalDelete')?></label>
                </div>
                <div class="modal-body">
                    <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                    <input type="hidden" id="ohdConfirmPRBDocNoDelete"   name="ohdConfirmPRBDocNoDelete">
                    <input type="hidden" id="ohdConfirmPRBSeqNoDelete"   name="ohdConfirmPRBSeqNoDelete">
                    <input type="hidden" id="ohdConfirmPRBPdtCodeDelete" name="ohdConfirmPRBPdtCodeDelete">
                    <input type="hidden" id="ohdConfirmPRBPunCodeDelete" name="ohdConfirmPRBPunCodeDelete">

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
    <div id="odvPRBModalPleseselectSPL" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo language('document/purchasebranch/purchasebranch','tPRBSplNotFound')?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxFocusInputCustomer();">
                        <?=language('common/main/main', 'tCMNOK')?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
    <div id="odvPRBModalPDTNotFound" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo language('document/purchasebranch/purchasebranch','tPRBPdtNotFound')?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxNotFoundClose();" >
                        <?=language('common/main/main', 'tCMNOK')?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== พบสินค้ามากกว่าหนึ่งตัว ======================================================================== -->
<div id="odvPRBModalPDTMoreOne" class="modal fade">
        <div class="modal-dialog" role="document" style="width: 85%; margin: 1.75rem auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/purchasebranch/purchasebranch','tPRBSelectPdt')?></label>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                            <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTMoreOne(1)" data-dismiss="modal"><?php echo language('document/purchasebranch/purchasebranch','tPRBChoose')?></button>
                            <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTMoreOne(2)" data-dismiss="modal"><?php echo language('document/purchasebranch/purchasebranch','tPRBClose')?></button>
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

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvPRBModalChangeBCH" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo language('document/purchasebranch/purchasebranch','tPRBBchNotFound')?></p>
                </div>
                <div class="modal-footer">
                    <button type="button"  data-dismiss="modal" id="obtChangeBCH" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm');?></button>
                    <button type="button"  data-dismiss="modal" class="btn xCNBTNDefult"><?php echo language('common/main/main', 'tModalCancel');?></button>
                </div>
            </div>
        </div>
    </div>
<!-- =========================================== อ้างอิงเอกสารภายใน ============================================= -->
<div id="odvPRBModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/purchasebranch/purchasebranch','tPRBLabelNoStock')?></label>
            </div>

            <div class="modal-body">
                <div class="row" id="odvPRBFromRefIntDoc">

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>

        </div>
    </div>
</div>

<!-- =========================================== ไม่พบคลังสินค้า ============================================= -->
<div id="odvPRBModalWahNoFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/purchasebranch/purchasebranch','tPRBWahNotFound')?></label>
            </div>

            <div class="modal-body">
                <p><?php echo language('document/purchasebranch/purchasebranch','tPRBPlsSelectWah')?></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>

        </div>
    </div>
</div>
<!-- ============================================================================================================================================================================= -->

<!-- ======================================================================== View Modal AutoPdt  ======================================================================== -->
<div id="odvDOModalConfirmAutoPDT" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('document/purchasebranch/purchasebranch','tPRBResetConfirm')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <ul>
                            <li><?=language('document/purchasebranch/purchasebranch', 'tPRBAuToPdtCheck')?></li>
                        </ul>
                </div>
                <div class="modal-footer">
                    <button onclick="FSvPRBAutoWah()" type="button" class="btn xCNBTNPrimery">
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
<!-- ======================================================================== View Modal AutoPdt2  ======================================================================== -->
<div id="odvDOModalConfirmAutoPDT2" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('document/purchasebranch/purchasebranch','tPRBResetConfirm')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <ul>
                            <li><?=language('document/purchasebranch/purchasebranch', 'tPRBAuToPdtCheck')?></li>
                        </ul>
                </div>
                <div class="modal-footer">
                    <button onclick="FSvPRBAutoWah2()" type="button" class="btn xCNBTNPrimery">
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
<!-- ===================================================== Modal Corfirm Delete ===================================================== -->
<div id="odvPRBModalConfirmDel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
                </div>
                <div class="modal-body">
                    <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"><?php echo language('document/purchasebranch/purchasebranch','tPRBDelZeroConfirm')?></span>
                </div>
                <div class="modal-footer">
                    <button id="osmConfirmDelSingle" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
                </div>
            </div>
        </div>
    </div>
<!-- ======================================================================================================================================== -->



<script src="<?php echo base_url('application/modules/common/assets/src/jThaiBath.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jPurchasebranchAdd.php');?>
<?php //include("script/jTransferRequestBranchPdtAdvTableData.php");?>


<script>
    //บังคับให้เลือกลูกค้า
    function JSxFocusInputCustomer(){
        $('#oetPRBFrmCstName').focus();
    }

    //ค้นหาสินค้าใน temp
    function JSvPRBCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbPRBDocPdtAdvTableList tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    function JSxNotFoundClose() {
        $('#oetPRBInsertBarcode').focus();
    }

    //กดเลือกบาร์โค๊ด
    function JSxSearchFromBarcode(e,elem){
        var tValue = $(elem).val();

            JSxCheckPinMenuClose();
            if(tValue.length === 0){

            }else{
                // JCNxOpenLoading();
                $('#oetPRBInsertBarcode').attr('readonly',true);
                JCNSearchBarcodePdt(tValue);
                $('#oetPRBInsertBarcode').val('');
            }

        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
    function JCNSearchBarcodePdt(ptTextScan){
        // var tWhereCondition = "";
        // tWhereCondition += " AND PPCZ.FTPdtStaAlwPoHQ = 1 ";
        // tWhereCondition += " AND Products.FTPdtStkControl = 1 ";

        // if( tPISplCode != "" ){
        //     tWhereCondition = " AND FTPdtSetOrSN IN('1','2') ";
        // }
        var aWhereItem      = [];
        tPDTAlwSale         = ' AND PPCZ.FTPdtStaAlwPoHQ = 1 AND Products.FTPdtStkControl = 1 ';
        aWhereItem.push(tPDTAlwSale);

        var aMulti = [];
        $.ajax({
            type: "POST",
            url : "BrowseDataPDTTableCallView",
            data: {
                // aPriceType      : ['Price4Cst',tDOPplCode],
                aPriceType: ["Cost","tCN_Cost","Company","1"],
                NextFunc        : "",
                SPL             : $("#oetPRBFrmSplCode").val(),
                BCH             : $("#oetPRBFrmBchCode").val(),
                tInpSesSessionID : $('#ohdSesSessionID').val(),
                tInpUsrCode      : $('#ohdPRBUsrCode').val(),
                tInpLangEdit     : $('#ohdPRBLangEdit').val(),
                tInpSesUsrLevel  : $('#ohdSesUsrLevel').val(),
                tInpSesUsrBchCom : $('#ohdSesUsrBchCom').val(),
                tWhere              : aWhereItem,
                tTextScan           : ptTextScan,
                aPackDataForSerach  : {
                    tSearchPDTType : "T1,T3,T4,T5,T6,S2,S3,S4"
                }
            },
            cache   : false,
            timeout : 0,
            success : function(tResult){
                // $('#oetPRBInsertBarcode').attr('readonly',false);
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                if(oText == '800'){
                    $('#oetPRBInsertBarcode').attr('readonly',false);
                    $('#odvPRBModalPDTNotFound').modal('show');
                    $('#oetPRBInsertBarcode').val('');
                }else{
                    if(oText.length > 1){

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvPRBModalPDTMoreOne').modal('show');
                        $('#odvPRBModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
                        for(i=0; i<oText.length; i++){
                            var aNewReturn      = JSON.stringify(oText[i]);
                            var tTest = "["+aNewReturn+"]";
                            var oEncodePackData = window.btoa(unescape(encodeURIComponent(tTest)));
                            var tHTML = "<tr class='xCNColumnPDTMoreOne"+i+" xCNColumnPDTMoreOne' data-information='"+oEncodePackData+"' style='cursor: pointer;'>";
                                tHTML += "<td>"+oText[i].pnPdtCode+"</td>";
                                tHTML += "<td>"+oText[i].packData.PDTName+"</td>";
                                tHTML += "<td>"+oText[i].packData.PUNName+"</td>";
                                tHTML += "<td>"+oText[i].ptBarCode+"</td>";
                                tHTML += "</tr>";
                            $('#odvPRBModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick',function(e){
                            $('#odvPRBModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            // FSvPRBAddPdtIntoDocDTTemp(tJSON);
                            // FSvPRBAddBarcodeIntoDocDTTemp(tJSON);
                            $.ajax({
                                type: "POST",
                                url: "docPRBCheckAutoPdtInDTDocTempPlus",
                                data: {
                                            'aProduct'           : JSON.parse(tJSON),
                                            'tBchCode'       : $('#oetPRBFrmBchCode').val(),
                                            'tWahCode'       : $('#oetPRBFrmWahCodeShip').val(),
                                            'tSuggesType'        : $('#ocmPRBSuggesAddPdt').val()
                                        },
                                cache: false,
                                timeout: 0,
                                success: function (oResult){
                                    var aResult =  JSON.parse(oResult);
                                    if(aResult.rtCode == '1'){
                                    aNewData = [];
                                    aNewData.push(aResult.raItems);
                                    var aNewReturn  = JSON.stringify(aNewData);
                                    // FSvPRBNextFuncB4SelPDT(aNewReturn);
                                    FSvPRBAddPdtIntoDocDTTemp(aNewReturn);
                                    FSvPRBAddBarcodeIntoDocDTTemp(aNewReturn);
                                    }
                                    JCNxCloseLoading();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                                }
                            });
                        });

                        //คลิกได้เลย
                        $('.xCNColumnPDTMoreOne').on('click',function(e){
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
                            $('.xCNColumnPDTMoreOne').children(':last-child').css('text-align','left');

                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important;');
                            $(this).children().last().css('text-align','left');
                        });
                    }else{
                        //มีตัวเดียว
                        var aNewReturn  = JSON.stringify(oText);
                        // console.log('aNewReturn: '+aNewReturn);
                        // FSvPRBAddPdtIntoDocDTTemp(aNewReturn);
                        // FSvPRBAddBarcodeIntoDocDTTemp(aNewReturn);
                        $.ajax({
                                type: "POST",
                                url: "docPRBCheckAutoPdtInDTDocTempPlus",
                                data: {
                                            'aProduct'           : JSON.parse(aNewReturn),
                                            'tBchCode'       : $('#oetPRBFrmBchCode').val(),
                                            'tWahCode'       : $('#oetPRBFrmWahCodeShip').val(),
                                            'tSuggesType'        : $('#ocmPRBSuggesAddPdt').val()
                                        },
                                cache: false,
                                timeout: 0,
                                success: function (oResult){
                                    var aResult =  JSON.parse(oResult);
                                    if(aResult.rtCode == '1'){
                                    aNewData = [];
                                    aNewData.push(aResult.raItems);
                                    var aNewReturn  = JSON.stringify(aNewData);
                                    // FSvPRBNextFuncB4SelPDT(aNewReturn);
                                    FSvPRBAddPdtIntoDocDTTemp(aNewReturn);
                                    FSvPRBAddBarcodeIntoDocDTTemp(aNewReturn);
                                    }
                                    JCNxCloseLoading();
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                                }
                            });
                    }
                }
            },
            error: function (jqXHR,textStatus,errorThrown){
                // JCNxResponseError(jqXHR,textStatus,errorThrown);
                JCNSearchBarcodePdt(ptTextScan);
            }
        });
    }

    //เลือกสินค้า กรณีพบมากกว่าหนึ่งตัว
    function JCNxConfirmPDTMoreOne($ptType){
        if($ptType == 1){
            $("#odvPRBModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function( index ) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                $.ajax({
                    type: "POST",
                    url: "docPRBCheckAutoPdtInDTDocTempPlus",
                    data: {
                                'aProduct'           : JSON.parse(tJSON),
                                'tBchCode'       : $('#oetPRBFrmBchCode').val(),
                                'tWahCode'       : $('#oetPRBFrmWahCodeShip').val(),
                                'tSuggesType'        : $('#ocmPRBSuggesAddPdt').val()
                            },
                    cache: false,
                    timeout: 0,
                    success: function (oResult){
                        var aResult =  JSON.parse(oResult);
                        if(aResult.rtCode == '1'){
                        aNewData = [];
                        aNewData.push(aResult.raItems);
                        var aNewReturn  = JSON.stringify(aNewData);
                        // FSvPRBNextFuncB4SelPDT(aNewReturn);
                        FSvPRBAddPdtIntoDocDTTemp(aNewReturn);
                        FSvPRBAddBarcodeIntoDocDTTemp(aNewReturn);
                        }
                        JCNxCloseLoading();
                        $("#odvDOModalConfirmAutoPDT").modal("hide");
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
                // FSvPRBAddPdtIntoDocDTTemp(tJSON);
                // FSvPRBAddBarcodeIntoDocDTTemp(tJSON);
            });
        }else{
            $('#oetPRBInsertBarcode').attr('readonly',false);
            $('#oetPRBInsertBarcode').val('');
        }
    }

    //หลังจากค้นหาเสร็จแล้ว
    function FSvPRBAddBarcodeIntoDocDTTemp(ptPdtData){
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1){
            var ptXthDocNoSend  = "";
            if ($("#ohdPRBRoute").val() == "docPRBEventEdit") {
                ptXthDocNoSend  = $("#oetPRBDocNo").val();
            }
            var tPRBOptionAddPdt = $('#ocmPRBFrmInfoOthReAddPdt').val();
            var tPRBSuggesType  = $('#ocmPRBSuggesAddPdt').val();

            // var nKey            = parseInt($('#otbPRBDocPdtAdvTableList tr:last').attr('data-seqno'));
            var max = 0;
            $('#otbPRBDocPdtAdvTableList tr').each(function (indexInArray, valueOfElement) { 
                var nChaeckNumber = parseInt($(this).attr('data-seqno'));
                if(nChaeckNumber > max){
                    max = nChaeckNumber;
                }
            });
            var nKey    = max;

            $('#oetPRBInsertBarcode').attr('readonly',false);
            $('#oetPRBInsertBarcode').val('');
            $.ajax({
                type: "POST",
                url: "docPRBAddPdtIntoDTDocTemp",
                data: {
                    'tSelectBCH'        : $('#oetPRBFrmBchCode').val(),
                    'tPRBDocNo'          : ptXthDocNoSend,
                    'tPRBOptionAddPdt'   : tPRBOptionAddPdt,
                    'tPRBPdtData'        : ptPdtData,
                    'ohdSesSessionID'   : $('#ohdSesSessionID').val(),
                    'ohdPRBUsrCode'        : $('#ohdPRBUsrCode').val(),
                    'ohdPRBLangEdit'       : $('#ohdPRBLangEdit').val(),
                    'ohdSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
                    'ohdPRBSesUsrBchCode'  : $('#ohdPRBSesUsrBchCode').val(),
                    'tSeqNo'              : nKey,
                    'nVatRate'            : $('#ohdPRBFrmSplVatRate').val(),
                    'nVatCode'            : $('#ohdPRBFrmSplVatCode').val(),
                    'tPRBSuggesType'      : tPRBSuggesType
                },
                cache: false,
                timeout: 0,
                success: function (oResult){
                  var aResult =  JSON.parse(JSON.stringify(oResult));
                  var aResult2 = (JSON.parse(aResult));
                    for (var i = 0; i < aResult2.Item.raItems.length; i++) {
                        aNewData = aResult2.Item.raItems[i];
                        tPdtCode = aResult2.Item.raItems[i]['FTPdtCode'];
                        tSugges  = aResult2.Item.raItems[i]['Sugges'];
                        tBarCode = aResult2.Item.raItems[i]['FTXtdBarCode'];
                        $( "tr.otr"+tPdtCode+tBarCode ).find( "td#otdPdtQtySugges"+tPdtCode ).html( tSugges );
                        // $( "tr.otr"+tPdtCode+tBarCode ).find( "input.xCNPdtEditInLine " ).val( tSugges );
                    }
                    if(aResult['nStaEvent']==1){
                        JCNxCloseLoading();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxthowMsgSessionExpired();
        }
    }
    $("#obtPRBAutoInsertTableDT").click(function () {
        var nCurrentItem = $(".xWPdtItem").length;
        if(nCurrentItem > 0){
            $("#odvDOModalConfirmAutoPDT").modal("show");
        }else{
            FSvPRBAutoWah();
        }
    })

    $("#obtPRBAutoInsertTableDT2").click(function () {
        var nCurrentItem = $(".xWPdtItem").length;
        if(nCurrentItem > 0){
            $("#odvDOModalConfirmAutoPDT2").modal("show");
        }else{
            FSvPRBAutoWah2();
        }
    })

    function FSvPRBAutoWah(){
        $.ajax({
          type: "POST",
          url: "docPRBCheckAutoPdtInDTDocTemp",
          data: {
                    'tBchCode'           : $('#oetPRBFrmBchCode').val(),
                    'tWahCode'           : $('#oetPRBFrmWahCodeShip').val(),
                    'tSuggesType'        : $('#ocmPRBSuggesAddPdt').val()
                },
          cache: false,
          timeout: 0,
          success: function (oResult){
            var aResult =  JSON.parse(oResult);
            // console.log(aResult);
            if(aResult.rtCode == '1'){
                $("#odvTBodyPRBPdtAdvTableList").empty();
                for (var i = 0; i < aResult.raAutoPDT.length; i++) {
                    aNewData = [];
                    aNewData.push(aResult.raAutoPDT[i]);
                    var aNewReturn  = JSON.stringify(aNewData);
                    // console.log(aNewReturn);
                    FSvPRBNextFuncB4SelPDT(aNewReturn);
                }
            }else{
                $("#odvTBodyPRBPdtAdvTableList").empty();
                $('#otbPRBDocPdtAdvTableList tbody').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">'+'<?=language('common/main/main','tCMNNotFoundData')?>'+'</td></tr>');
                JSxPRBCountPdtItems();
            }
            JCNxCloseLoading();
            $("#odvDOModalConfirmAutoPDT").modal("hide");
            // JSvPRBLoadPdtDataTableHtml();
          },
          error: function (jqXHR, textStatus, errorThrown) {
              JCNxResponseError(jqXHR, textStatus, errorThrown);
          }
      });
    };

    function FSvPRBAutoWah2(){
        JCNxOpenLoading();
        $.ajax({
          type: "POST",
          url: "docPRBCheckAutoRentPdtInDTDocTemp",
          data: {
                    'tBchCode'           : $('#oetPRBFrmBchCode').val(),
                    'tWahCode'           : $('#oetPRBFrmWahCodeShip').val(),
                    'tSuggesType'        : $('#ocmPRBSuggesAddPdt').val()
                },
          cache: false,
          timeout: 0,
          success: function (oResult){
            var aResult =  JSON.parse(oResult);
            if(aResult.rtCode == '1'){
                $("#odvTBodyPRBPdtAdvTableList").empty();
                for (var i = 0; i < aResult.raAutoPDT.length; i++) {
                    aNewData = [];
                    aNewData.push(aResult.raAutoPDT[i]);
                    var aNewReturn  = JSON.stringify(aNewData);
                    // console.log(aNewReturn);
                    FSvPRBNextFuncB4SelPDT(aNewReturn);
                }
            }
            JCNxCloseLoading();
            $("#odvDOModalConfirmAutoPDT2").modal("hide");
            // JSvPRBLoadPdtDataTableHtml();
          },
          error: function (jqXHR, textStatus, errorThrown) {
              JCNxResponseError(jqXHR, textStatus, errorThrown);
          }
      });
    };
</script>
