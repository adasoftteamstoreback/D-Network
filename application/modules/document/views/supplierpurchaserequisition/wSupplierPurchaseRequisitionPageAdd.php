<?php
    $tSesUsrLevel   = $this->session->userdata('tSesUsrLevel');
    
    if(isset($aDataDocHD) && $aDataDocHD['rtCode'] == '1'){
        $aDataDocHD             = @$aDataDocHD['raItems'];
        $tPRSRoute               = "docPRSEventEdit";
        $nPRSAutStaEdit          = 1;
        $tPRSDocNo               = $aDataDocHD['FTXphDocNo'];
        $dPRSDocDate             = date("Y-m-d",strtotime($aDataDocHD['FDXphDocDate']));
        $dPRSDocTime             = date("H:i",strtotime($aDataDocHD['FDXphDocDate']));
        $tPRSCreateBy            = $aDataDocHD['CreateBy'];
        $tPRSUsrNameCreateBy     = $aDataDocHD['CreateByName'];
        $tPRSStaDoc              = $aDataDocHD['FTXphStaDoc'];
        $tPRSStaApv              = $aDataDocHD['FTXphStaApv'];
        $tPRSStaPrcStk           = '';
        $tPRSStaDelMQ            = '';
        $tPRSSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
        $tPRSUsrCode             = $this->session->userdata('tSesUsername');
        $tPRSLangEdit            = $this->session->userdata("tLangEdit");
        $tPRSApvCode             = $aDataDocHD['FTXphApvCode'];
        $tPRSUsrNameApv          = $aDataDocHD['FTXphApvName'];
        $tPRSRefPoDoc            = "";
        $nPRSStaRef              = $aDataDocHD['FNXphStaRef'];
        $tPRSBchCode             = $aDataDocHD['FTBchCode'];
        $tPRSBchName             = $aDataDocHD['FTBchName'];
        $tPRSWahCode             = $aDataDocHD['FTWahCode'];
        $tPRSWahName             = $aDataDocHD['rtWahName'];
        $nPRSStaDocAct           = $aDataDocHD['FNXphStaDocAct'];
        $tPRSFrmDocPrint         = $aDataDocHD['FNXphDocPrint'];
        $tPRSFrmRmk              = $aDataDocHD['FTXphRmk'];
        $tPRSSplCode             = $aDataDocHD['FTSplCode'];
        $tPRSSplName             = $aDataDocHD['FTSplName'];
        $tPRSVatInOrEx           = $aDataDocHD['FTXphVATInOrEx'];
        $tPRSSplPayMentType      = $aDataDocHD['FTXphCshOrCrd'];
        $tPRSSplCrTerm           = $aDataDocHD['FNXphCrTerm'];
        $tPRSSplCtrName          = $aDataDocHD['FTXphCtrName'];
        $dPRSSplTnfDate          = $aDataDocHD['FDXphTnfDate'];
        $tPRSSplRefTnfID         = $aDataDocHD['FTXphRefTnfID'];
        $tPRSSplRefVehID         = $aDataDocHD['FTXphRefVehID'];
        $tPRSSplRefInvNo         = $aDataDocHD['FTXphRefInvNo'];
        $nStaUploadFile          = 2;
        $nPRSStaDocAct           = $aDataDocHD['FNXphStaDocAct'];
        $tPRSDataInputBchCode    = $aDataDocHD['rtShipName'];
        $tPRSDataInputBchName    = $aDataDocHD['FTXphShipTo'];
        $tPRSAgnCode             = $aDataDocHD['rtAgnCode'];
        $tPRSAgnName             = $aDataDocHD['rtAgnName'];
        $tPRSAgnCodeTo           = $aDataDocHD['rtAgnCodeTo'];
        $tPRSAgnNameTo           = $aDataDocHD['rtAgnNameTo'];
        $tPRSAgnNameTo           = $aDataDocHD['rtAgnNameTo'];
        $tPRSStaPrcDoc           = $aDataDocHD['FTXphStaPrcDoc'];   
    }else{
        $tPRSRoute               = "docPRSEventAdd";
        $nPRSAutStaEdit          = 0;
        $tPRSDocNo               = "";
        $dPRSDocDate             = "";
        $dPRSDocTime             = date('H:i:s');
        $tPRSCreateBy            = $this->session->userdata('tSesUsrUsername');
        $tPRSUsrNameCreateBy     = $this->session->userdata('tSesUsrUsername');
        $nPRSStaRef              = 0;
        $tPRSStaDoc              = 1;
        $tPRSStaApv              = NULL;
        $tPRSStaPrcStk           = NULL;
        $tPRSStaDelMQ            = NULL;
        $tPRSSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
        $tPRSUsrCode             = $this->session->userdata('tSesUsername');
        $tPRSLangEdit            = $this->session->userdata("tLangEdit");
        $tPRSApvCode             = "";
        $tPRSUsrNameApv          = "";
        $tPRSRefPoDoc            = "";
        $tPRSBchCode             = $this->session->userdata('tSesUsrBchCodeDefault');
        $tPRSBchName             = $this->session->userdata('tSesUsrBchNameDefault');
        $tPRSWahCode             = "";
        $tPRSWahName             = "";
        $nPRSStaDocAct           = "";
        $tPRSFrmDocPrint         = "";
        $tPRSFrmRmk              = "";
        $tPRSVatInOrEx           = "";
        $tPRSSplPayMentType      = "";
        $tPRSSplDstPaid          = "1";
        $tPRSSplCrTerm           = "";
        $dPRSSplDueDate          = "";
        $dPRSSplBillDue          = "";
        $tPRSSplCtrName          = "";
        $dPRSSplTnfDate          = "";
        $tPRSSplRefTnfID         = "";
        $tPRSSplRefVehID         = "";
        $tPRSSplRefInvNo         = "";
        $tPRSSplQtyAndTypeUnit   = "";
        $nStaUploadFile          = 1;
        $nPRSStaDocAct           = "";
        $tPRSDataInputBchCode    = "";
        $tPRSDataInputBchName    = "";
        $tPRSAgnCode             = $this->session->userdata('tSesUsrAgnCode');
        $tPRSAgnName             = $this->session->userdata('tSesUsrAgnName');
        $tPRSAgnCodeTo           = $this->session->userdata('tSesUsrAgnCode');
        $tPRSAgnNameTo           = $this->session->userdata('tSesUsrAgnName');
        $tPRSStaPrcDoc           = "";

        $aSPLConfig              = $aSPLConfig;
        if($this->session->userdata('bIsHaveAgn') == true && $this->session->userdata('tAgnType') == 2){ //แฟรนไซส์
            $tPRSSplCode         = $aSPLConfig['rtSPLCode'];
            $tPRSSplName         = $aSPLConfig['rtSPLName'];
        }else{ //สำนักงานใหญ่
            $tPRSSplCode         = "";
            $tPRSSplName         = "";
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

    .mark-font, .panel-default > .panel-heading.mark-font{
        color           : #232C3D !important;
        font-weight     : 900;
    }

</style>

<form id="ofmPRSFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <input type="hidden" id="ohdPRSPage"                name="ohdPRSPage" value="1">
    <input type="hidden" id="ohdPRSRoute"               name="ohdPRSRoute" value="<?=$tPRSRoute;?>">
    <input type="hidden" id="ohdPRSCheckClearValidate"  name="ohdPRSCheckClearValidate" value="0">
    <input type="hidden" id="ohdPRSCheckSubmitByButton" name="ohdPRSCheckSubmitByButton" value="0">
    <input type="hidden" id="ohdPRSDecimalShow"         name="ohdPRSDecimalShow" value="<?=$nOptDecimalShow?>">
    <input type="hidden" id="ohdPRSStaDoc"              name="ohdPRSStaDoc" value="<?=$tPRSStaDoc;?>">
    <input type="hidden" id="ohdPRSStaApv"              name="ohdPRSStaApv" value="<?=$tPRSStaApv;?>">
    <input type="hidden" id="ohdPRSStaPrcDoc"           name="ohdPRSStaPrcDoc" value="<?=$tPRSStaPrcDoc;?>">
    <input type="hidden" id="ohdPRSSesUsrBchCode"       name="ohdPRSSesUsrBchCode" value="<?=$tPRSSesUsrBchCode; ?>">
    <input type="hidden" id="ohdPRSBchCode"             name="ohdPRSBchCode" value="<?=$tPRSBchCode; ?>">
    <input type="hidden" id="ohdPRSUsrCode"             name="ohdPRSUsrCode" value="<?=$tPRSUsrCode?>">
    <input type="hidden" id="ohdPRSApvCodeUsrLogin"     name="ohdPRSApvCodeUsrLogin" value="<?=$tPRSUsrCode; ?>">
    <input type="hidden" id="ohdPRSLangEdit"            name="ohdPRSLangEdit" value="<?=$tPRSLangEdit; ?>">
    <input type="hidden" id="ohdSesSessionID"           name="ohdSesSessionID" value="<?=$this->session->userdata('tSesSessionID')?>"  >
    <input type="hidden" id="ohdSesSessionName"         name="ohdSesSessionName" value="<?=$this->session->userdata('tSesUsrUsername')?>"  >
    <input type="hidden" id="ohdSesUsrLevel"            name="ohdSesUsrLevel" value="<?=$this->session->userdata('tSesUsrLevel')?>"  >
    <input type="hidden" id="ohdSesUsrBchCom"           name="ohdSesUsrBchCom" value="<?=$this->session->userdata('tSesUsrBchCom')?>"  >
    <input type="hidden" id="ohdPRSSubmitWithImp"       name="ohdPRSSubmitWithImp" value="0">
    <input type="hidden" id="ohdPRSValidatePdt"         name="ohdPRSValidatePdt" value="<?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSPleaseSeletedPDTIntoTable')?>">
    <input type="hidden" id="ohdPRSValidatePdtImp"      name="ohdPRSValidatePdtImp" value="<?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSNotFoundPdtCodeAndBarcodeImpList')?>">
    <button style="display:none" type="submit" id="obtPRSSubmitDocument" onclick="JSxPRSAddEditDocument()"></button>

    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPRSHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSDoucment'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvPRSDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPRSDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmAppove');?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelFrmDocNo'); ?></label>
                                <?php if(isset($tPRSDocNo) && empty($tPRSDocNo)):?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbPRSStaAutoGenCode" name="ocbPRSStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelFrmAutoGenCode');?></span>
                                        </label>
                                    </div>
                                <?php endif;?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input
                                        type="text"
                                        class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai"
                                        id="oetPRSDocNo"
                                        name="oetPRSDocNo"
                                        maxlength="20"
                                        value="<?php echo $tPRSDocNo;?>"
                                        data-validate-required="<?php echo language('document/purchaseorder/purchaseorder','tPRSPlsEnterOrRunDocNo'); ?>"
                                        data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder','tPRSPlsDocNoDuplicate'); ?>"
                                        placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelFrmDocNo');?>"
                                        style="pointer-events:none"
                                        readonly
                                    >
                                    <input type="hidden" id="ohdPRSCheckDuplicateCode" name="ohdPRSCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelFrmDocDate');?></label>
                                    <div class="input-group">
                                        <?php if ($dPRSDocDate == '') {
                                            $dPRSDocDate = '';
                                        } ?>
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNDatePicker xCNInputMaskDate"
                                            id="oetPRSDocDate"
                                            name="oetPRSDocDate"
                                            value="<?php echo $dPRSDocDate; ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPRSDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSLabelFrmDocTime');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNTimePicker xCNInputMaskTime"
                                            id="oetPRSDocTime"
                                            name="oetPRSDocTime"
                                            value="<?php echo $dPRSDocTime; ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPRSDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelFrmCreateBy');?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdPRSCreateBy" name="ohdPRSCreateBy" value="<?php echo $tPRSCreateBy?>">
                                            <label><?php echo $tPRSUsrNameCreateBy?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSLabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                                if($tPRSRoute == "docPRSEventAdd"){
                                                    $tPRSLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                                }else{
                                                    $tPRSLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc'.$tPRSStaDoc);
                                                }
                                            ?>
                                            <label><?php echo $tPRSLabelStaDoc;?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSLabelFrmStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaApv'.$tPRSStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <?php if(@$tPRSTypeDocument == 1){ //ใบขอซื้อ ?>
                                    
                                <?php }else{ //ใบขอซิ้อแฟรนไซด์ ?>
                                    <!-- สถานะยืนยัน(สำนักงานใหญ์) -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSTitleStaPrcDocFull'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <label><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSStaPrcDoc'.$tPRSStaPrcDoc); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <!-- สถานะอ้างอิงเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSLabelFrmStaRef'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmStaRef'.$nPRSStaRef); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <?php if(isset($tPRSDocNo) && !empty($tPRSDocNo)):?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSLabelFrmApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdPRSApvCode" name="ohdPRSApvCode" maxlength="20" value="<?php echo $tPRSApvCode?>">
                                                <label>
                                                    <?php echo (isset($tPRSUsrNameApv) && !empty($tPRSUsrNameApv))? $tPRSUsrNameApv : "-" ?>
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

            <!-- Panel สาขาต้นทาง -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPRSReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSLabeAcpBch');?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvPRSDataReferenceDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPRSDataReferenceDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-10">
                            <div class="form-group m-b-0">
                                <?php
                                    $tPRSDataInputBchCode   = "";
                                    $tPRSDataInputBchName   = "";
                                    if($tPRSRoute  == "docPRSEventAdd"){
                                        $tPRSDataInputBchCode    = $this->session->userdata('tSesUsrBchCodeDefault');
                                        $tPRSDataInputBchName    = $this->session->userdata('tSesUsrBchNameDefault');
                                        $tDisabledBch = '';
                                    }else{
                                        $tPRSDataInputBchCode    = $aDataDocHD['FTXphShipTo'];
                                        $tPRSDataInputBchName    = $aDataDocHD['rtShipName'];
                                        $tDisabledBch = 'disabled';
                                    }
                                ?>

                                <!--สาขา-->
                                <script>
                                    var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                                    if( tUsrLevel != "HQ" ){
                                        $('#oimPRSBrowseAgn').attr("disabled", true);
                                        $('#obtPRSBrowseAgencyTo').attr('disabled',true);
                                        $('#obtPRSBrowseBCHTo').attr('disabled',true);
                                    }

                                    var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                                    if( tUsrLevel != "HQ" ){
                                        //BCH - SHP
                                        var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount");?>';
                                        if(tBchCount < 2){
                                            $('#obtPRSBrowseBCH').attr('disabled',true);
                                        }
                                    }
                                </script>

                                <!--ตัวแทนขาย-->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
                                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetPRSAgnCode" name="oetPRSAgnCode" maxlength="5" value="<?= @$tPRSAgnCode; ?>">
                                        <input  type="text"
                                                class="form-control xControlForm xWPointerEventNone"
                                                id="oetPRSAgnName" name="oetPRSAgnName"
                                                maxlength="100"
                                                placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>"
                                                value="<?= @$tPRSAgnName; ?>"
                                                readonly>
                                        <span class="input-group-btn">
                                            <button id="oimPRSBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn <?= @$tDisabled ?>">
                                                <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!--สาขา-->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSLabelFrmBranch')?></label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                                id="oetPRSFrmBchCode"
                                                name="oetPRSFrmBchCode"
                                                maxlength="5"
                                                value="<?php echo @$tPRSBchCode?>"
                                                data-bchcodeold = "<?php echo @$tPRSBchCode?>"
                                            >
                                            <input
                                                type="text"
                                                class="form-control xControlForm xWPointerEventNone"
                                                id="oetPRSFrmBchName"
                                                name="oetPRSFrmBchName"
                                                maxlength="100"
                                                placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSLabelFrmBranch')?>"
                                                data-validate-required="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSPlsEnterBch'); ?>"
                                                value="<?php echo @$tPRSBchName?>"
                                                readonly
                                            >
                                            <span class="input-group-btn xWConditionSearchPdt">
                                                <button id="obtPRSBrowseBCH" type="button" class="btn xCNBtnBrowseAddOn ">
                                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel จากผู้จำหน่าย / ผู้ขาย -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPRSConditionDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition', 'tPRSLabelFormSpl'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvPRSDataConditionDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPRSDataConditionDoc" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelSplName');?></label>
                                    <input
                                        type="text"
                                        class="form-control xControlForm"
                                        id="oetPRSSplName"
                                        name="oetPRSSplName"
                                        value="<?php echo $tPRSSplName;?>"
                                        placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelSplName');?>"
                                        readonly
                                    >
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelPayType');?></label>
                                    <select class="selectpicker xWPRSDisabledOnApv form-control xControlForm xWConditionSearchPdt" id="ocmPRSTypePayment" name="ocmPRSTypePayment" maxlength="1">
                                    <?php if ($tPRSSplPayMentType == 1) {
                                            $tSelect = "selected";
                                            $tSelect2 = "";
                                        }elseif ($tPRSSplPayMentType == 2) {
                                            $tSelect2 = "selected";
                                            $tSelect = "";
                                        }else{
                                            $tSelect = "";
                                            $tSelect2 = "";
                                        } ?>
                                            <option value="1" <?= $tSelect?>><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelPayCash');?></option>
                                            <option value="2" <?= $tSelect2?>><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelPayCredit');?></option>
                                    </select>
                                </div>
                                <div class="form-group xCNPanel_CreditTerm">
                                    <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelCredit');?></label>
                                    <input
                                        type="text"
                                        class="form-control xControlForm text-right"
                                        id="oetPRSFrmSplInfoCrTerm"
                                        name="oetPRSFrmSplInfoCrTerm"
                                        maxlength="20"
                                        value="<?php echo $tPRSSplCrTerm;?>"
                                        placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelCredit');?>"
                                    >
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmSplInfoVatInOrEx');?></label>
                                    <select class="selectpicker xWPRSDisabledOnApv form-control xControlForm xWConditionSearchPdt" id="ocmPRSFrmSplInfoVatInOrEx" name="ocmPRSFrmSplInfoVatInOrEx" maxlength="1">
                                        <?php if ($tPRSVatInOrEx == 1) {
                                            $tSelect = "selected";
                                            $tSelect2 = "";
                                        }elseif ($tPRSVatInOrEx == 2) {
                                            $tSelect2 = "selected";
                                            $tSelect = "";
                                        }else{
                                            $tSelect = "";
                                            $tSelect2 = "";
                                        } ?>
                                        <option value="1" <?php echo $tSelect;?>><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmSplInfoVatInclusive');?></option>
                                        <option value="2" <?php echo $tSelect2;?>><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmSplInfoVatExclusive');?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel สาขาปลายทาง -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPIBranchToInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/purchaseorder/purchaseorder','tPOPanelBranchTo');?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvPIDataBranchToInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPIDataBranchToInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div id="" class="row"  style="max-height:350px;overflow-x:auto">
                            <div class="col-xs-12 col-sm-12 col-col-md-12 col-lg-12">

                               <!-- Condition ตัวแทนขาย -->
                               <div class="form-group " >
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOPanelAgency');?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNHide" id="oetPRSAgnCodeTo" name="oetPRSAgnCodeTo" maxlength="5" value="<?php echo $tPRSAgnCodeTo?>">
                                        <input type="text" class="form-control xWPointerEventNone" id="oetPRSAgnNameTo" placeholder="<?php echo language('document/purchaseorder/purchaseorder','tPOPanelAgency');?>" name="oetPRSAgnNameTo" lavudate-label="<?php echo language('document/purchaseorder/purchaseorder', 'tPOPanelAgency');?>" value="<?php echo $tPRSAgnNameTo?>" readonly>
                                        <span class="xWConditionSearchPdt input-group-btn">
                                            <button id="obtPRSBrowseAgencyTo" type="button" class="xWConditionSearchPdt btn xCNBtnBrowseAddOn">
                                                <img class="xCNIconFind">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            <!-- Condition สาขา -->
                            <div class="form-group m-b-0">
                                    <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch')?></label>
                                                    <div class="input-group">
                                                        <input
                                                            type="text"
                                                            class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                                            id="oetPRSToBchCode"
                                                            name="oetPRSToBchCode"
                                                            maxlength="5"
                                                            placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch')?>"
                                                            value="<?=$tPRSDataInputBchCode?>"
                                                            data-bchcodeold = ""
                                                        >
                                                        <input
                                                            type="text"
                                                            class="form-control xWPointerEventNone"
                                                            id="oetPRSToBchName"
                                                            name="oetPRSToBchName"
                                                            maxlength="100"
                                                            placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch')?>"
                                                            value="<?=$tPRSDataInputBchName?>"
                                                            readonly
                                                        >
                                                        <span class="input-group-btn xWConditionSearchPdt">
                                                            <button id="obtPRSBrowseBCHTo" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt  "   >
                                                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>

                                    </div>




                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Panel การขนส่ง -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPISupplierInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelTSTitle');?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvPIDataSupplierInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPIDataSupplierInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div id="odvRowPanelSplInfo" class="row"  style="max-height:350px;overflow-x:auto">
                            <div class="col-xs-12 col-sm-12 col-col-md-12 col-lg-12">
                                <!-- ชื่อผู้ติดต่อ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelTSName');?></label>
                                    <input
                                        type="text"
                                        class="form-control xControlForm"
                                        id="oetPRSFrmSplInfoCtrName"
                                        name="oetPRSFrmSplInfoCtrName"
                                        maxlength="20"
                                        value="<?php echo $tPRSSplCtrName;?>"
                                        placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelTSName');?>"
                                    >
                                </div>

                                <!-- เลขอ้างอิงใบขนส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelRefTSDocCode');?></label>
                                    <input
                                        type="text"
                                        class="form-control xControlForm"
                                        id="oetPRSFrmSplInfoRefTnfID"
                                        name="oetPRSFrmSplInfoRefTnfID"
                                        maxlength="20"
                                        value="<?php echo $tPRSSplRefTnfID;?>"
                                        placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelRefTSDocCode');?>"
                                    >
                                </div>
                                <!-- วันที่ในการขนส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelRefTSDate');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNDatePicker xCNInputMaskDate"
                                            id="oetPRSFrmSplInfoTnfDate"
                                            name="oetPRSFrmSplInfoTnfDate"
                                            value="<?php echo $dPRSSplTnfDate;?>"
                                            placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSPHDRefTSCode'); ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtPRSTransDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- อ้างอิงเลขที่ยานพาหนะขนส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelRefVehicleCode');?></label>
                                    <input
                                        type="text"
                                        class="form-control xControlForm"
                                        id="oetPRSFrmSplInfoRefVehID"
                                        name="oetPRSFrmSplInfoRefVehID"
                                        maxlength="20"
                                        value="<?php echo $tPRSSplRefVehID;?>"
                                        placeholder="<?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelRefVehicleCode');?>"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel อืนๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvPRSInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','อื่นๆ');?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvPRSDataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvPRSDataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" value="1" id="ocbPRSFrmInfoOthStaDocAct" name="ocbPRSFrmInfoOthStaDocAct" maxlength="1" <?php echo ($nPRSStaDocAct == '1' || empty($nPRSStaDocAct)) ? 'checked' : ''; ?> checked = "checked">
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>
                                <!-- สถานะอ้างอิง -->
                                <?php if ($nPRSStaRef == 0) {
                                            $tSelect = "selected";
                                            $tSelect2 = "";
                                            $tSelect3 = "";
                                        }elseif ($nPRSStaRef == 1) {
                                            $tSelect = "";
                                            $tSelect2 = "selected";
                                            $tSelect3 = "";
                                        }elseif ($nPRSStaRef == 2) {
                                            $tSelect = "";
                                            $tSelect2 = "";
                                            $tSelect3 = "selected";
                                        }?>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthRef');?></label>
                                    <select class="selectpicker xWPRSDisabledOnApv form-control xControlForm" id="ocmPRSFrmInfoOthRef" name="ocmPRSFrmInfoOthRef" maxlength="1">
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
                                        id="ocmPRSFrmInfoOthDocPrint"
                                        name="ocmPRSFrmInfoOthDocPrint"
                                        value="<?php echo $tPRSFrmDocPrint;?>"
                                        readonly
                                    >
                                </div>
                                <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthReAddPdt');?></label>
                                    <select class="form-control xControlForm selectpicker xWPRSDisabledOnApv" id="ocmPRSFrmInfoOthReAddPdt" name="ocmPRSFrmInfoOthReAddPdt">
                                        <option value="1" selected><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt1');?></option>
                                        <option value="2"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt2');?></option>
                                    </select>
                                </div>
                                <!-- หมายเหตุ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthRemark');?></label>
                                    <textarea
                                        class="form-control xControlRmk xWConditionSearchPdt"
                                        id="otaPRSFrmInfoOthRmk"
                                        name="otaPRSFrmInfoOthRmk"
                                        rows="10"
                                        maxlength="200"
                                        style="resize: none;height:86px;"
                                    ><?php echo $tPRSFrmRmk?></textarea>
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
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvPRSShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    var oPRSCallDataTableFile = {
                        ptElementID     : 'odvPRSShowDataTable',
                        ptBchCode       : $('#oetPRSFrmBchCode').val(),
                        ptDocNo         : $('#oetPRSDocNo').val(),
                        ptDocKey        : 'TCNTPdtReqSplHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdPRSStaApv').val(),
                        ptStaDoc        : $('#ohdPRSStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oPRSCallDataTableFile);
                </script>
            </div>
        </div>

        <div class="col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <!-- ตารางรายการสินค้า -->
                <div id="odvPRSDataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                            <ul class="nav" role="tablist">
                                                <!-- สินค้า -->
                                                <li class="xWMenu active xCNStaHideShow" style="cursor:pointer;">
                                                    <a role="tab" data-toggle="tab" data-target="#odvPRSContentProduct" aria-expanded="true"><?= language('document/expenserecord/expenserecord', 'ข้อมูลสินค้า') ?></a>
                                                </li>

                                                <!-- อ้างอิง -->
                                                <li class="xWMenu xCNStaHideShow" style="cursor:pointer;">
                                                    <a role="tab" data-toggle="tab" data-target="#odvPRSContentHDRef" aria-expanded="false"><?= language('document/expenserecord/expenserecord', 'เอกสารอ้างอิง') ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content">
                                    <!-- ผู้จำหน่าย -->
                                    <div id="odvPRSContentProduct" class="tab-pane fade active in" style="padding: 0px !important;">
                                        <div class="row p-t-10">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <label class="xCNLabelFrm"><?=language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelSplName');?></label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xControlForm xCNHide" id="oetPRSFrmSplCode" name="oetPRSFrmSplCode" value="<?=$tPRSSplCode;?>">
                                                        <input
                                                            type="text"
                                                            class="form-control xControlForm"
                                                            id="oetPRSFrmSplName"
                                                            name="oetPRSFrmSplName"
                                                            value="<?=$tPRSSplName;?>"
                                                            placeholder="<?=language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSLabelSplName') ?>"
                                                            data-validate-required="<?=language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSPlsEnterSplCode'); ?>"
                                                            readonly
                                                        >
                                                        <span class="input-group-btn">
                                                            <button id="obtPRSBrowseSupplier" type="button" class="btn xCNBtnBrowseAddOn">
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
                                                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvPRSCSearchPdtHTML()" placeholder="<?=language('common/main/main','tPlaceholder');?>">
                                                        <span class="input-group-btn">
                                                            <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvPRSCSearchPdtHTML()">
                                                                <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right xCNMsgDeletePDTInTableDT">
                                                <!--ตัวเลือก-->
                                                <div id="odvPRSMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup">
                                                    <button type="button" class="btn xCNBTNMngTable xWConditionSearchPdt" data-toggle="dropdown">
                                                        <?php echo language('common/main/main','tCMNOption')?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li id="oliPRSBtnDeleteMulti">
                                                            <a data-toggle="modal" data-target="#odvPRSModalDelPdtInDTTempMultiple"><?php echo language('common/main/main','tDelAll')?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 xCNMsgInsertPDTInTableDT">
                                                <!--ค้นหาจากบาร์โค๊ด-->
                                                <div class="form-group" style="width: 85%;">
                                                    <input type="text" class="form-control xControlForm" id="oetPRSInsertBarcode" autocomplete="off" name="oetPRSInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);"  placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า" >
                                                </div>

                                                <!--เพิ่มสินค้าแบบปกติ-->
                                                <div class="form-group">
                                                    <div style="position: absolute;right: 15px;top:-5px;">
                                                        <button type="button" id="obtPRSDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-t-10" id="odvPRSDataPdtTableDTTemp"></div>

                                        <!--ส่วนสรุปท้ายบิล-->
                                        <div id="odvRowDataEndOfBill">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <label class="pull-left mark-font"><?=language('document/purchaseorder/purchaseorder','จำนวนขอซื้อรวมทั้งสิ้น');?></label>
                                                    <label class="pull-right mark-font"><span class="mark-font xShowQtyFooter">0</span> <?=language('document/purchaseorder/purchaseorder','tPOItems');?></label>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- อ้างอิง -->
                                    <div id="odvPRSContentHDRef" class="tab-pane fade" style="padding: 0px !important;">
                                        <div class="row p-t-15">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                                <div style="margin-top:-2px;">
                                                    <button type="button" id="obtPRSAddDocRef" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                                </div>
                                            </div>
                                            <div id="odvPRSTableHDRef"></div>
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

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
<div id="odvPRSModalAppoveDoc" class="modal fade">
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
                <button onclick="JSxPRSApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal Cancel Document  ======================================================================== -->
<div class="modal fade" id="odvPRSPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSCancelDoc')?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSCancelDocWarnning')?></p>
                <p><strong><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSCancelDocConfrim')?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnPRSCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =====================================================================  Modal Advance Table Product DT Temp ==================================================================-->
<div class="modal fade" id="odvPRSOrderAdvTblColumns" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <div class="modal-body" id="odvPRSModalBodyAdvTable">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                <button id="obtPRSSaveAdvTableColums" type="button" class="btn btn-primary"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== View Modal Delete Product In DT DocTemp Multiple  ============================================================ -->
<div id="odvPRSModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main','tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type="hidden" id="ohdConfirmPRSDocNoDelete"   name="ohdConfirmPRSDocNoDelete">
                <input type="hidden" id="ohdConfirmPRSSeqNoDelete"   name="ohdConfirmPRSSeqNoDelete">
                <input type="hidden" id="ohdConfirmPRSPdtCodeDelete" name="ohdConfirmPRSPdtCodeDelete">
                <input type="hidden" id="ohdConfirmPRSPunCodeDelete" name="ohdConfirmPRSPunCodeDelete">

            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal ไม่พบตัวแทนขาย   ======================================================================== -->
<div id="odvPRSModalPleseselectSPL" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSSplNotFound')?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxFocusInputCustomer();">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal ไม่พบลูกค้า   ======================================================================== -->
<div id="odvPRSModalRefinPleseselectSPL" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/deliveryorder/deliveryorder','tDOSplRefNotFound')?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxFocusInputCustomer();">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== Modal ไม่พบรหัสสินค้า ======================================================================== -->
<div id="odvPRSModalPDTNotFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tMessageAlert')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSPdtNotFound')?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxNotFoundClose();" >
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== พบสินค้ามากกว่าหนึ่งตัว ======================================================================== -->
<div id="odvPRSModalPDTMoreOne" class="modal fade">
    <div class="modal-dialog" role="document" style="width: 85%; margin: 1.75rem auto;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSSelectPdt')?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTMoreOne(1)" data-dismiss="modal"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSChoose')?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTMoreOne(2)" data-dismiss="modal"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSClose')?></button>
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

<!-- =========================================== อ้างอิงเอกสารภายใน ============================================= -->
<div id="odvPRSModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/purchaseorder/purchaseorder','อ้างอิงเอกสารใบสั่งสินค้าไปยังสำนักงานใหญ่')?></label>
            </div>

            <div class="modal-body">
                <div class="row" id="odvPRSFromRefIntDoc"></div>
            </div>

            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>

        </div>
    </div>
</div>

<!-- =========================================== ไม่พบคลังสินค้า ============================================= -->
<div id="odvPRSModalWahNoFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSWahNotFound')?></label>
            </div>

            <div class="modal-body">
                <p><?php echo language('document/supplierpurchaserequisition/supplierpurchaserequisition','tPRSPlsSelectWah')?></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?=language('common/main/main', 'tCMNOK')?>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ===========================================  อ้างอิงเอกสารภายใน (ภายใน หรือ ภายนอก) =========================================== -->
<div id="odvPRSModalAddDocRef" class="modal fade" tabindex="-1" role="dialog" style='z-index:1045'>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="ofmPRSFormAddDocRef" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?=language('common/main/main', 'อ้างอิงเอกสาร') ?></label>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control xCNHide" id="oetPRSRefDocNoOld" name="oetPRSRefDocNoOld">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'ประเภทการอ้างอิงเอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbPRSRefType" name="ocbPRSRefType">
                                    <option value="1" selected><?=language('common/main/main', 'อ้างอิงภายใน'); ?></option>
                                    <option value="3"><?=language('common/main/main', 'อ้างอิงภายนอก'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'เอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbPRSRefDoc" name="ocbPRSRefDoc">
                                    <option value="1" selected><?=language('common/main/main', 'ใบสั่งสินค้าสำนักงานใหญ่'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetPRSDocRefInt" name="oetPRSDocRefInt" maxlength="20" value="">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetPRSDocRefIntName" name="oetPRSDocRefIntName" maxlength="20" placeholder="<?=language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?>" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtPRSBrowseRefDoc" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><span class="text-danger">*</span><?=language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetPRSRefDocNo" name="oetPRSRefDocNo" placeholder="<?=language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?>" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('document/expenserecord/expenserecord', 'วันที่เอกสารอ้างอิง'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetPRSRefDocDate" name="oetPRSRefDocDate" placeholder="YYYY-MM-DD" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="obtPRSRefDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'ค่าอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetPRSRefKey" name="oetPRSRefKey" placeholder="<?=language('common/main/main', 'ค่าอ้างอิง'); ?>" maxlength="10" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="obtPRSConfirmAddDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="submit"><?=language('common/main/main', 'tModalConfirm') ?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?=base_url('application/modules/common/assets/src/jThaiBath.js')?>"></script>
<script src="<?=base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?=base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jSupplierPurchaseRequisitionAdd.php');?>
<?php include("script/jSupplierPurchaseRequisitionAdvTableData.php");?>

<script>
    //บังคับให้เลือกลูกค้า
    function JSxFocusInputCustomer(){
        $('#oetPRSFrmCstName').focus();
    }

    //ค้นหาสินค้าใน temp
    function JSvPRSCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbPRSDocPdtAdvTableList tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    function JSxNotFoundClose() {
        $('#oetPRSInsertBarcode').focus();
    }

    //กดเลือกบาร์โค๊ด
    function JSxSearchFromBarcode(e,elem){
        var tValue = $(elem).val();
        if($('#oetPRSFrmSplName').val() != ""){
            JSxCheckPinMenuClose();
            if(tValue.length === 0){

            }else{
                // JCNxOpenLoading();
                $('#oetPRSInsertBarcode').attr('readonly',true);
                JCNSearchBarcodePdt(tValue);
                $('#oetPRSInsertBarcode').val('');
            }
        }else{
            $('#odvPRSModalPleseselectSPL').modal('show');
            $('#oetPRSInsertBarcode').val('');
        }
        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
    function JCNSearchBarcodePdt(ptTextScan){

        var tWhereCondition = "";
        var aMulti          = [];
        $.ajax({
            type    : "POST",
            url     : "BrowseDataPDTTableCallView",
            data    : {
                aPriceType: ["Cost","tCN_Cost","Company","1"],
                NextFunc        : "",
                SPL             : $("#oetPRSFrmSplCode").val(),
                BCH             : $("#oetPRSFrmBchCode").val(),
                tInpSesSessionID : $('#ohdSesSessionID').val(),
                tInpUsrCode      : $('#ohdPRSUsrCode').val(),
                tInpLangEdit     : $('#ohdPRSLangEdit').val(),
                tInpSesUsrLevel  : $('#ohdSesUsrLevel').val(),
                tInpSesUsrBchCom : $('#ohdSesUsrBchCom').val(),
                Where            : [tWhereCondition],
                tTextScan       : ptTextScan
            },
            cache   : false,
            timeout : 0,
            success : function(tResult){
                // $('#oetPRSInsertBarcode').attr('readonly',false);
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                if(oText == '800'){
                    $('#oetPRSInsertBarcode').attr('readonly',false);
                    $('#odvPRSModalPDTNotFound').modal('show');
                    $('#oetPRSInsertBarcode').val('');
                }else{
                    if(oText.length > 1){

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvPRSModalPDTMoreOne').modal('show');
                        $('#odvPRSModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
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
                            $('#odvPRSModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick',function(e){
                            $('#odvPRSModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            FSvPRSAddPdtIntoDocDTTemp(tJSON); //Client
                            FSvPRSAddBarcodeIntoDocDTTemp(tJSON);
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
                            $('.xCNColumnPDTMoreOne').children(':last-child').css('text-align','right');

                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important;');
                            $(this).children().last().css('text-align','right');
                        });
                    }else{
                        //มีตัวเดียว
                        var aNewReturn  = JSON.stringify(oText);
                        console.log('aNewReturn: '+aNewReturn);
                        // var aNewReturn  = '[{"pnPdtCode":"00009","ptBarCode":"ca2020010003","ptPunCode":"00001","packData":{"SHP":null,"BCH":null,"PDTCode":"00009","PDTName":"ขนม_03","PUNCode":"00001","Barcode":"ca2020010003","PUNName":"ขวด","PriceRet":"17.00","PriceWhs":"0.00","PriceNet":"0.00","IMAGE":"D:/xampp/htdocs/Moshi-Moshi/application/modules/product/assets/systemimg/product/00009/Img200128172902CEHHRSS.jpg","LOCSEQ":"","Remark":"ขนม_03","CookTime":0,"CookHeat":0}}]';
                        FSvPRSAddPdtIntoDocDTTemp(aNewReturn); //Client
                        // JCNxCloseLoading();
                        // $('#oetPRSInsertBarcode').attr('readonly',false);
                        // $('#oetPRSInsertBarcode').val('');
                        FSvPRSAddBarcodeIntoDocDTTemp(aNewReturn); //Server
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
            $("#odvPRSModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function( index ) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                FSvPRSAddPdtIntoDocDTTemp(tJSON);
                FSvPRSAddBarcodeIntoDocDTTemp(tJSON);
            });
        }else{
            $('#oetPRSInsertBarcode').attr('readonly',false);
            $('#oetPRSInsertBarcode').val('');
        }
    }

    //หลังจากค้นหาเสร็จแล้ว
    function FSvPRSAddBarcodeIntoDocDTTemp(ptPdtData){
        var nStaSession = 1;
        if (typeof nStaSession !== "undefined" && nStaSession == 1){
            // JCNxOpenLoading();
            var ptXthDocNoSend  = "";
            if ($("#ohdPRSRoute").val() == "docPRSEventEdit") {
                ptXthDocNoSend  = $("#oetPRSDocNo").val();
            }
            var tPRSOptionAddPdt = $('#ocmPRSFrmInfoOthReAddPdt').val();
            var nKey            = parseInt($('#otbPRSDocPdtAdvTableList tr:last').attr('data-seqno'));

            $('#oetPRSInsertBarcode').attr('readonly',false);
            $('#oetPRSInsertBarcode').val('');

            $.ajax({
                type    : "POST",
                url     : "docPRSAddPdtIntoDTDocTemp",
                data    : {
                    'tSelectBCH'            : $('#oetPRSFrmBchCode').val(),
                    'tPRSDocNo'             : ptXthDocNoSend,
                    'tPRSOptionAddPdt'      : tPRSOptionAddPdt,
                    'tPRSPdtData'           : ptPdtData,
                    'ohdSesSessionID'       : $('#ohdSesSessionID').val(),
                    'ohdPRSUsrCode'         : $('#ohdPRSUsrCode').val(),
                    'ohdPRSLangEdit'        : $('#ohdPRSLangEdit').val(),
                    'ohdSesUsrLevel'        : $('#ohdSesUsrLevel').val(),
                    'ohdPRSSesUsrBchCode'   : $('#ohdPRSSesUsrBchCode').val(),
                    'tSeqNo'                : nKey,
                    'nVatRate'              : $('#ohdPRSFrmSplVatRate').val(),
                    'nVatCode'              : $('#ohdPRSFrmSplVatCode').val()
                },
                cache: false,
                timeout: 0,
                success: function (oResult){
                    // JSvPRSLoadPdtDataTableHtml();
                  var aResult =  JSON.parse(oResult);

                    if(aResult['nStaEvent']==1){
                        JCNxCloseLoading();
                        // $('#oetPRSInsertBarcode').attr('readonly',false);
                        // $('#oetPRSInsertBarcode').val('');
                        // if(tPRSOptionAddPdt=='1'){
                        //     JSvPRSCallEndOfBill();
                        // }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // JCNxResponseError(jqXHR, textStatus, errorThrown);
                    FSvPRSAddBarcodeIntoDocDTTemp(ptPdtData);
                }
            });
        }else{
            JCNxphowMsgSessionExpired();
        }
    }

    //กดเพิ่มเอกสารอ้างอิง (ภายใน ภายนอก)
    $('#obtPRSAddDocRef').off('click').on('click',function(){
        $('#ofmPRSFormAddDocRef').validate().destroy();
        JSxPRSEventClearValueInFormHDDocRef();
        $('#odvPRSModalAddDocRef').modal('show');
    });

    //เคลียร์ค่า
    function JSxPRSEventClearValueInFormHDDocRef(){
        $('#oetPRSRefDocNo').val('');
        $('#oetPRSRefDocDate').val('');
        $('#oetPRSDocRefInt').val('');
        $('#oetPRSDocRefIntName').val('');
        $('#oetPRSRefKey').val('');
    }

    //เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    $('#ocbPRSRefType').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        JSxPRSEventCheckShowHDDocRef();
    });

    //กดเลือกอ้างอิงเอกสารภายใน (ใบสั่งสินค้าสำนักงานใหญ่)
    $('#obtPRSBrowseRefDoc').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tPRSRefType = $('#ocbPRSRefDoc').val();
            if( tPRSRefType == '1' ){ //ใบสั่งสินค้าสำนักงานใหญ่
                JSxCallGetPRBRefIntDoc();
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //Browse => ใบสั่งสินค้าสำนักงานใหญ่
    function JSxCallGetPRBRefIntDoc(){
        JSxCallPRSRefIntDoc();
    }

    //Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    JSxPRSEventCheckShowHDDocRef();
    function JSxPRSEventCheckShowHDDocRef(){
        var tPRSRefType = $('#ocbPRSRefType').val();
        if( tPRSRefType == '1' ){
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();
        }else{
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }

    //โหลด Table อ้างอิงเอกสารทั้งหมด
    FSxPRSCallPageHDDocRef();
    function FSxPRSCallPageHDDocRef(){
        var tDocNo = $('#oetPRSDocNo').val();
        $.ajax({
            type    : "POST",
            url     : "docPRSPageHDDocRef",
            data:{
                'ptDocNo' : tDocNo
            },
            cache   : false,
            timeout : 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                $('#odvPRSTableHDRef').html(aResult['tViewPageHDRef']);
                    JCNxCloseLoading();
                }else{
                    var tMessageError = aResult['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กดยืนยันบันทึกลง Temp
    $('#ofmPRSFormAddDocRef').off('click').on('click',function(){
        $('#ofmPRSFormAddDocRef').validate().destroy();
        $('#ofmPRSFormAddDocRef').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetPRSRefDocNo    : {"required" : true}
            },
            messages: {
                oetPRSRefDocNo    : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
            },
            errorElement    : "em",
            errorPlacement  : function (error, element) {
                error.addClass("help-block");
                if(element.prop("type") === "checkbox") {
                    error.appendTo(element.parent("label"));
                }else{
                    var tCheck  = $(element.closest('.form-group')).find('.help-block').length;
                    if(tCheck == 0) {
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function (form){
                JCNxOpenLoading();

                if($('#ocbPRSRefType').val() == 1){ //อ้างอิงเอกสารภายใน
                    var tDocNoRef = $('#oetPRSDocRefInt').val();
                }else{ //อ้างอิงเอกสารภายนอก
                    var tDocNoRef = $('#oetPRSRefDocNo').val();
                }

                $.ajax({
                    type    : "POST",
                    url     : "docPRSEventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld'     : $('#oetPRSRefDocNoOld').val(),
                        'ptPRSDocNo'        : $('#oetPRSDocNo').val(),
                        'ptRefType'         : $('#ocbPRSRefType').val(),
                        'ptRefDocNo'        : tDocNoRef,
                        'pdRefDocDate'      : $('#oetPRSRefDocDate').val(),
                        'ptRefKey'          : $('#oetPRSRefKey').val()
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        JSxPRSEventClearValueInFormHDDocRef();
                        $('#odvPRSModalAddDocRef').modal('hide');

                        FSxPRSCallPageHDDocRef();
                        JCNxCloseLoading();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    });
</script>
