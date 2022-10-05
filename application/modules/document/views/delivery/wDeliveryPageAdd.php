<?php
    if ( isset($aDataDocHD) && $aDataDocHD['rtCode'] == '1' ) {
        $aDataDocHD              = $aDataDocHD['raItems'];
        $tDLVRoute               = "docDLVEventEdit";
        $nDLVAutStaEdit          = 1;
        $tDLVDocNo               = $aDataDocHD['FTXshDocNo'];
        $tDLVDocType             = $aDataDocHD['FNXshDocType'];
        $dDLVDocDate             = date("Y-m-d", strtotime($aDataDocHD['FDXshDocDate']));
        $dDLVDocTime             = date("H:i:s", strtotime($aDataDocHD['FDXshDocDate']));
        $tDLVCreateBy            = $aDataDocHD['FTCreateBy'];
        $tDLVUsrNameCreateBy     = $aDataDocHD['rtUserName_Create'];
        $tDLVStaDoc              = $aDataDocHD['FTXshStaDoc'];
        $tDLVStaApv              = $aDataDocHD['FTXshStaApv'];
        $tDLVStaPrcStk           = '';
        $tDLVStaDelMQ            = '';
        $tDLVSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
        $tDLVUsrCode             = $aDataDocHD['FTUsrCode'];
        $tDLVUsrName             = $aDataDocHD['rtUserName_Delivery'];
        $tDLVLangEdit            = $this->session->userdata("tLangEdit");
        $tDLVApvCode             = $aDataDocHD['FTXshApvCode'];
        $tDLVUsrNameApv          = $aDataDocHD['rtApvName'];
        $tDLVBchCode             = $aDataDocHD['FTBchCode']; //สาขาที่สร้าง
        $tDLVBchName             = $aDataDocHD['rtBchName_Create'];
        $tDLVBchFrmCode          = $aDataDocHD['FTXshBchFrm']; //สาขาต้นทาง
        $tDLVBchFrmName          = $aDataDocHD['rtBchName_From'];
        $tDLVBchToCode           = $aDataDocHD['FTXshBchTo']; //สาขาปลายทาง
        $tDLVBchToName           = $aDataDocHD['rtBchName_To'];
        $nDLVStaDocAct           = $aDataDocHD['FNXshStaDocAct'];
        $tDLVFrmDocPrint         = $aDataDocHD['FNXshDocPrint'];
        $tDLVFrmRmk              = $aDataDocHD['FTXshRmk'];
        $nStaUploadFile          = 2;
        $tDLVShipVia             = $aDataDocHD['FTXshShipVia'];  
        $dDLVDocDateDelivery     = date("Y-m-d", strtotime($aDataDocHD['FDXshDeliveryDate']));  
        $nStaShwAddress          = $nStaShwAddress;
        $tDLVFrmAgnCode          = $aDataDocHD['FTXshAgnFrm'];        
        $tDLVFrmAgnName          = $aDataDocHD['rtAgnName_From'];
        $tDLVToAgnCode           = $aDataDocHD['FTXshAgnTo'];     
        $tDLVToAgnName           = $aDataDocHD['rtAgnName_To'];
        $tDLVFTCstCode           = $aDataDocHD['FTCstCode'];
        $tDLVFTCstName           = $aDataDocHD['rtCstName'];
        $tDLVFTCstTel            = $aDataDocHDCst['raItems']['FTCstTel'];
        $tDLVFTCstEmail          = $aDataDocHDCst['raItems']['FTCstEmail'];

        //ที่อยู่จัดส่ง
        $tSHIP_FNAddSeqNo        = @$aDataDocAddr['raItems'][0]['FNAddSeqNo'];
        $tSHIP_FTAddV1No         = @$aDataDocAddr['raItems'][0]['FTAddV1No'];
        $tSHIP_FTAddV1Soi        = @$aDataDocAddr['raItems'][0]['FTAddV1Soi'];
        $tSHIP_FTAddV1Village    = @$aDataDocAddr['raItems'][0]['FTAddV1Village'];
        $tSHIP_FTAddV1Road       = @$aDataDocAddr['raItems'][0]['FTAddV1Road'];
        $tSHIP_FTSudName         = @$aDataDocAddr['raItems'][0]['FTSudName'];
        $tSHIP_FTDstName         = @$aDataDocAddr['raItems'][0]['FTDstName'];
        $tSHIP_FTPvnName         = @$aDataDocAddr['raItems'][0]['FTPvnName'];
        $tSHIP_FTAddV1PostCode   = @$aDataDocAddr['raItems'][0]['FTAddV1PostCode'];
        $tSHIP_FTAddTel          = @$aDataDocAddr['raItems'][0]['FTAddTel'];
        $tSHIP_FTAddFax          = @$aDataDocAddr['raItems'][0]['FTAddFax'];
        $tSHIP_FTAddTaxNo        = @$aDataDocAddr['raItems'][0]['FTAddTaxNo'];
        $tSHIP_FTAddV2Desc1      = @$aDataDocAddr['raItems'][0]['FTAddV2Desc1'];
        $tSHIP_FTAddV2Desc2      = @$aDataDocAddr['raItems'][0]['FTAddV2Desc2'];
        $tSHIP_FTAddName         = @$aDataDocAddr['raItems'][0]['FTAddName'];
    } else {
        $tDLVRoute               = "docDLVEventAdd";
        $nDLVAutStaEdit          = 0;
        $tDLVDocNo               = "";
        $tDLVDocType             = "1";
        $dDLVDocDate             = date("Y-m-d");
        $dDLVDocTime             = date('H:i:s');
        $tDLVCreateBy            = $this->session->userdata('tSesUsrUsername');
        $tDLVUsrNameCreateBy     = $this->session->userdata('tSesUsrUsername');
        $nDLVStaRef              = 0;
        $tDLVStaDoc              = 1;
        $tDLVStaApv              = NULL;
        $tDLVStaPrcStk           = NULL;
        $tDLVStaDelMQ            = NULL;
        $tDLVSesUsrBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
        $tDLVUsrCode             = "";
        $tDLVUsrName             = "";
        $tDLVLangEdit            = $this->session->userdata("tLangEdit");
        $tDLVApvCode             = "";
        $tDLVUsrNameApv          = "";
        $tDLVBchCode             = ""; //สาขาที่สร้าง
        $tDLVBchName             = "";
        $tDLVBchFrmCode          = ""; //สาขาต้นทาง
        $tDLVBchFrmName          = "";
        $tDLVBchToCode           = ""; //สาขาปลายทาง
        $tDLVBchToName           = "";
        $nDLVStaDocAct           = "1";
        $tDLVFrmDocPrint         = "";
        $tDLVFrmRmk              = "";
        $nStaUploadFile          = 1;
        $tDLVShipVia             = "";
        $dDLVDocDateDelivery     = date("Y-m-d");
        $nStaShwAddress          = $nStaShwAddress;
        $tDLVFrmAgnCode          = "";          
        $tDLVFrmAgnName          = "";
        $tDLVToAgnCode           = "";    
        $tDLVToAgnName           = "";
        $tDLVFTCstCode           = "";
        $tDLVFTCstName           = "";
        $tDLVFTCstTel            = "";
        $tDLVFTCstEmail          = "";

        //ที่อยู่จัดส่ง
        $tSHIP_FNAddSeqNo        = "";
        $tSHIP_FTAddV1No         = "";
        $tSHIP_FTAddV1Soi        = "";
        $tSHIP_FTAddV1Village    = "";
        $tSHIP_FTAddV1Road       = "";
        $tSHIP_FTSudName         = "";
        $tSHIP_FTDstName         = "";
        $tSHIP_FTPvnName         = "";
        $tSHIP_FTAddV1PostCode   = "";
        $tSHIP_FTAddTel          = "";
        $tSHIP_FTAddFax          = "";
        $tSHIP_FTAddTaxNo        = "";
        $tSHIP_FTAddV2Desc1      = "";
        $tSHIP_FTAddV2Desc2      = "";
        $tSHIP_FTAddName         = "";
    }

    //กำหนดค่า
    $tSesUsrLevel   = $this->session->userdata('tSesUsrLevel');
    $tUserBchName   = $this->session->userdata('tSesUsrBchNameDefault');
    $tUserBchCode   = $this->session->userdata('tSesUsrBchCodeDefault');
    $tUserWahName   = $this->session->userdata('tSesUsrWahName');
    $tUserWahCode   = $this->session->userdata('tSesUsrWahCode');
    $nLangEdit      = $this->session->userdata("tLangEdit");
    $tUsrApv        = $this->session->userdata("tSesUsername");
    $tUserLoginLevel= $this->session->userdata("tSesUsrLevel");
    $bIsApv         = empty($tDLVStaApv) ? false : true;
    $bIsCancel      = ($tDLVStaDoc == "3") ? true : false;
    $bIsApvOrCancel = ($bIsApv || $bIsCancel);
    $bIsMultiBch    = $this->session->userdata("nSesUsrBchCount") > 1;
?>

<script>
	var nLangEdit           = '<?=$nLangEdit; ?>';
	var tUsrApv             = '<?=$tUsrApv; ?>';
	var tUserLoginLevel     = '<?=$tUserLoginLevel; ?>';
	var bIsApv              = <?=($bIsApv) ? 'true' : 'false'; ?>;
	var bIsCancel           = <?=($bIsCancel) ? 'true' : 'false'; ?>;
	var bIsApvOrCancel      = <?=($bIsApvOrCancel) ? 'true' : 'false'; ?>;
    var tDLVStaDoc          = '<?=$tDLVStaDoc; ?>';
	var tDLVStaApv          = '<?=$tDLVStaApv; ?>';
	var bIsMultiBch         = <?=($bIsMultiBch) ? 'true' : 'false'; ?>;
</script>

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

    .mark-font, .panel-default > .panel-heading.mark-font{
        color           : #232C3D !important;
        font-weight     : 900;
    }

    fieldset.scheduler-border {
        border: 1px groove #ffffffa1 !important;
        padding: 0 20px 20px 20px !important;
        margin: 0 0 10px 0 !important;
    }

    legend.scheduler-border {
        text-align: left !important;
        width: auto;
        padding: 0 5px;
        border-bottom: none;
        font-weight: bold;
    }
</style>

<form id="ofmDLVFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <input type="hidden" id="ohdDLVRoute"                   name="ohdDLVRoute"                  value="<?=$tDLVRoute; ?>">
    <input type="hidden" id="ohdDLVODecimalShow"            name="ohdDLVODecimalShow"           value="<?=$nOptDecimalShow; ?>">
    <input type="hidden" id="ohdDLVStaDoc"                  name="ohdDLVStaDoc"                 value="<?=$tDLVStaDoc; ?>">
    <input type="hidden" id="ohdDLVStaApv"                  name="ohdDLVStaApv"                 value="<?=$tDLVStaApv; ?>">
    <input type="hidden" id="ohdDLVBchCode"                 name="ohdDLVBchCode"                value="<?=$tDLVBchCode; ?>">
    <input type="hidden" id="ohdDLVLangEdit"                name="ohdDLVLangEdit"               value="<?=$tDLVLangEdit; ?>">
    <input type="hidden" id="ohdSesSessionID"               name="ohdSesSessionID"              value="<?=$this->session->userdata('tSesSessionID') ?>">
    <input type="hidden" id="ohdDLVVATInOrEx"               name="ohdDLVVATInOrEx"              value="">
    <input type="hidden" id="ohdDLVDocType"                 name="ohdDLVDocType"                value="<?=$tDLVDocType?>">
    <input type="hidden" id="ohdDLVValidatePdt"             name="ohdDLVValidatePdt"            value="<?= language('document/productarrangement/productarrangement', 'tPAMPleaseSeletedPDTIntoTable') ?>">

    <button style="display:none" type="submit" id="obtDLVSubmitDocument" onclick="JSxDLVAddEditDocument()"></button>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDLVHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?=language('document/delivery/delivery', 'tDLVDoucment'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvDLVDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDLVDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmAppove'); ?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/delivery/delivery', 'tDLVLabelFrmDocNo'); ?></label>
                                <?php if (isset($tDLVDocNo) && empty($tDLVDocNo)) : ?>
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbDLVStaAutoGenCode" name="ocbDLVStaAutoGenCode" maxlength="1" checked="checked">
                                            <span>&nbsp;</span>
                                            <span class="xCNLabelFrm"><?php echo language('document/delivery/delivery', 'tDLVLabelFrmAutoGenCode'); ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input type="text" class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai" id="oetDLVDocNo" name="oetDLVDocNo" maxlength="20" value="<?php echo $tDLVDocNo; ?>" data-validate-required="<?php echo language('document/purchaseorder/purchaseorder', 'tDLVPlsEnterOrRunDocNo'); ?>" data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder', 'tDLVPlsDocNoDuplicate'); ?>" placeholder="<?php echo language('document/delivery/delivery', 'tDLVLabelFrmDocNo'); ?>" style="pointer-events:none" readonly>
                                    <input type="hidden" id="ohdDLVCheckDuplicateCode" name="ohdDLVCheckDuplicateCode" value="2">
                                </div>

                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/delivery/delivery', 'tDLVLabelFrmDocDate'); ?></label>
                                    <div class="input-group">
                                        <?php if ($dDLVDocDate == '') {
                                            $dDLVDocDate = '';
                                        } ?>
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetDLVDocDate" name="oetDLVDocDate" value="<?php echo $dDLVDocDate; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtDLVDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/delivery/delivery', 'tDLVLabelFrmDocTime'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNTimePicker xCNInputMaskTime" id="oetDLVDocTime" name="oetDLVDocTime" value="<?php echo $dDLVDocTime; ?>">
                                        <span class="input-group-btn">
                                            <button id="obtDLVDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?=language('document/delivery/delivery', 'tDLVLabelFrmCreateBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdDLVCreateBy" name="ohdDLVCreateBy" value="<?=$tDLVCreateBy ?>">
                                            <label><?=$tDLVUsrNameCreateBy ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/delivery/delivery', 'tDLVLabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                                if ($tDLVRoute == "docDLVEventAdd") {
                                                    $tDLVLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                                } else {
                                                    $tDLVLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc' . $tDLVStaDoc);
                                                }
                                            ?>
                                            <label><?php echo $tDLVLabelStaDoc; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/delivery/delivery', 'tDLVLabelFrmStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <label><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaApv' . $tDLVStaApv); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($tDLVDocNo) && !empty($tDLVDocNo)) : ?>
                                    <!-- ผู้อนุมัติเอกสาร -->
                                    <div class="form-group" style="margin:0">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <label class="xCNLabelFrm"><?php echo language('document/delivery/delivery', 'tDLVLabelFrmApvBy'); ?></label>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                                <input type="hidden" id="ohdDLVApvCode" name="ohdDLVApvCode" maxlength="20" value="<?php echo $tDLVApvCode ?>">
                                                <label>
                                                    <?php echo (isset($tDLVUsrNameApv) && !empty($tDLVUsrNameApv)) ? $tDLVUsrNameApv : "-" ?>
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

            <!-- Panel เงื่อนไข -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDLVCondition" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?=language('document/delivery/delivery', 'tDLVCondition'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvDLVConditionList" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDLVConditionList" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                  <div class="panel-body">
                      <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">

                            <!-- สาขาที่สร้าง -->
                            <?php
                                if($tDLVRoute == "docDLVEventAdd"){
                                    //สาขาที่สร้าง
                                    $tDLVDataInputBchCode       = $this->session->userdata('tSesUsrBchCodeDefault');
                                    $tDLVDataInputBchName       = $this->session->userdata('tSesUsrBchNameDefault');

                                    //สาขาต้นทาง
                                    $tDLVBchFrmCode             = $this->session->userdata('tSesUsrBchCodeDefault');
                                    $tDLVBchFrmName             = $this->session->userdata('tSesUsrBchNameDefault');

                                    //สาขาปลายทาง
                                    $tDLVBchToCode              = "";
                                    $tDLVBchToName              = "";
                                }else{
                                    //สาขาที่สร้าง
                                    $tDLVDataInputBchCode       = $tDLVBchCode;
                                    $tDLVDataInputBchName       = $tDLVBchName;

                                    //สาขาต้นทาง
                                    $tDLVBchFrmCode             = $tDLVBchFrmCode;
                                    $tDLVBchFrmName             = $tDLVBchFrmName;

                                    //สาขาปลายทาง
                                    $tDLVBchToCode              = $tDLVBchToCode;
                                    $tDLVBchToName              = $tDLVBchToName;
                                }
                            ?>
                            <script>
                                var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
                                if( tUsrLevel != "HQ" ){
                                    //BCH - SHP
                                    var tBchCount = '<?=$this->session->userdata("nSesUsrBchCount")?>';
                                    if(tBchCount < 2){
                                        $('#obtDLVBrowseBch').attr('disabled',true);
                                        $('#obtDLVBrowseFrmBch').attr('disabled',true);
                                    }
                                }
                            </script>
                            <div class="form-group">
                                <label class="xCNLabelFrm"><span class="text-danger">*</span>สาขาที่สร้าง</label>
                                <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetDLVBchCode" name="oetDLVBchCode" maxlength="20" value="<?=$tDLVDataInputBchCode?>">
                                    <input type="text" class="form-control xControlForm xWPointerEventNone" data-validate-required="กรุณาระบุสาขาที่สร้าง" id="oetDLVBchName" name="oetDLVBchName" maxlength="100" placeholder="สาขาที่สร้าง" value="<?=$tDLVDataInputBchName?>" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtDLVBrowseBch" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <!--ต้นทาง-->
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?= language('document/transferreceiptbranch/transferreceiptbranch', 'tTBIOrigin'); ?></legend>

                                <!-- ส่งจากตัวเเทนขาย/แฟรนไชส์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm">ส่งจากตัวเเทนขาย/แฟรนไชส์</label>
                                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetDLVFrmAgnCode" name="oetDLVFrmAgnCode" maxlength="20" value="<?=$tDLVFrmAgnCode?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDLVFrmAgnName" name="oetDLVFrmAgnName" maxlength="100" placeholder="ส่งจากตัวเเทนขาย/แฟรนไชส์" value="<?=$tDLVFrmAgnName?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtDLVBrowseFrmAgn" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!-- ส่งจากสาขา -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span class="text-danger">*</span>ส่งจากสาขา</label>
                                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetDLVFrmBchCode" name="oetDLVFrmBchCode" maxlength="20" value="<?=$tDLVBchFrmCode?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" data-validate-required="กรุณาระบุส่งจากสาขา" id="oetDLVFrmBchName" name="oetDLVFrmBchName" maxlength="100" placeholder="<?=language('monitor/deliveryorder/deliveryorder', 'tDOBchFrm') ?>" value="<?=$tDLVBchFrmName?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtDLVBrowseFrmBch" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!-- วันกำหนดส่งของ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span class="text-danger">*</span>วันกำหนดส่งของ</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xControlForm xCNDatePicker xCNInputMaskDate" id="oetDLVDateSent" name="oetDLVDateSent" value="<?=$dDLVDocDateDelivery?>">
                                        <span class="input-group-btn">
                                            <button id="obtDLVDateSent" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เลขที่พาหนะ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm">เลขที่พาหนะ</label>
                                    <input type="text" class="form-control xControlForm" id="oetDLVNumberCar" maxlength="50" name="oetDLVNumberCar" value="<?=$tDLVShipVia?>" placeholder="เลขที่พาหนะ">
                                </div>

                                <!-- พนักงานส่งของ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm">พนักงานส่งของ</label>
                                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetDLVCstDeliverlyCode" name="oetDLVCstDeliverlyCode" maxlength="20" value="<?=$tDLVUsrCode?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDLVCstDeliverlyName" name="oetDLVCstDeliverlyName" maxlength="100" placeholder="พนักงานส่งของ" value="<?=$tDLVUsrName?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtDLVBrowseCstDeliverly" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!-- เงื่อนไขในการจัดส่ง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm">เงื่อนไขในการจัดส่ง</label>
                                    <textarea class="form-control xControlRmk xWConditionSearchPdt" id="otaDLVFrmInfoOthRmk" name="otaDLVFrmInfoOthRmk" rows="10" maxlength="200" style="resize: none;height:86px;"><?=$tDLVFrmRmk ?></textarea>
                                </div>
                            </fieldset>

                            <!--ปลายทาง-->
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?= language('document/transferreceiptbranch/transferreceiptbranch', 'tTBITo'); ?></legend>

                                <!-- ตัวเเทนขายปลายทาง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm">ตัวเเทนขายปลายทาง/แฟรนไชส์</label>
                                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetDLVToAgnCode" name="oetDLVToAgnCode" maxlength="20" value="<?=$tDLVToAgnCode?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" id="oetDLVToAgnName" name="oetDLVToAgnName" maxlength="100" placeholder="ตัวเเทนขายปลายทาง/แฟรนไชส์" value="<?=$tDLVToAgnName?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtDLVBrowseToAgn" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!-- สาขาปลายทาง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span class="text-danger">*</span>สาขาปลายทาง</label>
                                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetDLVToBchCode" name="oetDLVToBchCode" maxlength="20" value="<?=$tDLVBchToCode?>">
                                        <input type="text" class="form-control xControlForm xWPointerEventNone" data-validate-required="กรุณาระบุสาขาปลายทาง" id="oetDLVToBchName" name="oetDLVToBchName" maxlength="100" placeholder="สาขาปลายทาง" value="<?=$tDLVBchToName?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtDLVBrowseToBch" type="button" class="btn xCNBtnBrowseAddOn ">
                                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- อีเมล -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm">อีเมล</label>
                                    <input type="text" class="form-control xControlForm" id="oetDLVCstEmail" name="oetDLVCstEmail" value="<?=$tDLVFTCstTel;?>" placeholder="อีเมล" readonly>
                                </div>

                                <!-- เบอร์โทร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm">เบอร์โทร</label>
                                    <input type="text" class="form-control xControlForm" id="oetDLVCstTel" name="oetDLVCstTel" value="<?=$tDLVFTCstEmail;?>" placeholder="เบอร์โทร" readonly>
                                </div>

                                <!-- ที่อยู่ใบกำกับภาษี -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5"></div>
                                            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">

                                                <!--ข้อมูลที่อยู่-->
                                                <input type="hidden" id="ohdDLVShipAddSeqNo"     name="ohdDLVShipAddSeqNo"    value="<?=@$tSHIP_FNAddSeqNo?>">
                                                <input type="hidden" id="ohdDLVShipAddTaxNo"     name="ohdDLVShipAddTaxNo"    value="<?=@$tSHIP_FTAddTaxNo?>">
                                                <input type="hidden" id="ohdDLVShipAddName"      name="ohdDLVShipAddName"     value="<?=@$tSHIP_FTAddName?>">
                                                <input type="hidden" id="ohdDLVShipTel"          name="ohdDLVShipTel"         value="<?=@$tSHIP_FTAddTel?>">
                                                <input type="hidden" id="ohdDLVShipFax"          name="ohdDLVShipFax"         value="<?=@$tSHIP_FTAddFax?>">

                                                <!-- ที่อยู่แบบแยก -->
                                                <input type="hidden" id="ohdDLVShipAddV1No"      name="ohdDLVShipAddV1No"     value="<?=@$tSHIP_FTAddV1No?>">
                                                <input type="hidden" id="ohdDLVShipV1Soi"        name="ohdDLVShipV1Soi"       value="<?=@$tSHIP_FTAddV1Soi?>">
                                                <input type="hidden" id="ohdDLVShipV1Village"    name="ohdDLVShipV1Village"   value="<?=@$tSHIP_FTAddV1Village?>">
                                                <input type="hidden" id="ohdDLVShipV1Road"       name="ohdDLVShipV1Road"      value="<?=@$tSHIP_FTAddV1Road?>">
                                                <input type="hidden" id="ohdDLVShipV1SubDistrict"name="ohdDLVShipV1SubDistrict" value="<?=@$tSHIP_FTSudName?>">
                                                <input type="hidden" id="ohdDLVShipV1District"   name="ohdDLVShipV1District"  value="<?=@$tSHIP_FTDstName?>">
                                                <input type="hidden" id="ohdDLVShipV1Province"   name="ohdDLVShipV1Province"  value="<?=@$tSHIP_FTPvnName?>">
                                                <input type="hidden" id="ohdDLVShipV1PostCode"   name="ohdDLVShipV1PostCode"  value="<?=@$tSHIP_FTAddV1PostCode?>">

                                                <!-- ที่อยู่แบบรวม -->
                                                <input type="hidden" id="ohdDLVShipAddV2Desc1"   name="ohdDLVShipAddV2Desc1"  value="<?=@$tSHIP_FTAddV2Desc1?>">
                                                <input type="hidden" id="ohdDLVShipAddV2Desc2"   name="ohdDLVShipAddV2Desc2"  value="<?=@$tSHIP_FTAddV2Desc2?>">
                                                <button type="button" id="obtDLVBrowseShip" class="btn xCNBTNSubSave" style="width:100%;" data-codebrowse="2">ที่อยู่จัดส่ง</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                      </div>
                  </div>
                </div>
            </div>

            <!-- Panel อืนๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDLVInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1">อื่นๆ</label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvDLVDataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDLVDataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" id="ocbDLVFrmInfoOthStaDocAct" name="ocbDLVFrmInfoOthStaDocAct" <?=($nDLVStaDocAct == '1') ? 'checked' : ''; ?>>
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?=language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>
                                <!-- สถานะอ้างอิง -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?=language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef'); ?></label>
                                    <select class="selectpicker form-control xControlForm" id="ocmDLVFrmInfoOthRef" name="ocmDLVFrmInfoOthRef" maxlength="1">
                                        <option value="0" selected><?=language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef0'); ?></option>
                                        <option value="1"><?=language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef1'); ?></option>
                                        <option value="2"><?=language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef2'); ?></option>
                                    </select>
                                </div>
                                <!-- จำนวนครั้งที่พิมพ์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?=language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthDocPrint'); ?></label>
                                    <input type="text" class="form-control xControlForm text-right" id="ocmDLVFrmInfoOthDocPrint" name="ocmDLVFrmInfoOthDocPrint" value="<?=$tDLVFrmDocPrint; ?>" readonly>
                                </div>
                                <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?=language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt'); ?></label>
                                    <select class="form-control xControlForm selectpicker" id="ocmDLVFrmInfoOthReAddPdt" name="ocmDLVFrmInfoOthReAddPdt">
                                        <option value="1" selected><?=language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt1'); ?></option>
                                        <option value="2"><?=language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt2'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel ไฟลแนบ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvDLVReferenceDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1">ไฟล์แนบ</label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvDLVDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDLVDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvDLVShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    var oSOCallDataTableFile = {
                        ptElementID     : 'odvDLVShowDataTable',
                        ptBchCode       : $('#oetDLVBchCode').val(),
                        ptDocNo         : $('#oetDLVDocNo').val(),
                        ptDocKey        : 'TARTDoHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdDLVStaApv').val(),
                        ptStaDoc        : $('#ohdDLVStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oSOCallDataTableFile);
                </script>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row">
                <div id="odvDLVDataPanelDetailPDT" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                            <ul class="nav" role="tablist">
                                                <!-- สินค้า -->
                                                <li class="xWMenu active xCNStaHideShow" style="cursor:pointer;">
                                                    <a role="tab" data-toggle="tab" data-target="#odvDLVContentProduct" aria-expanded="true"><?= language('document/document/document', 'ข้อมูลสินค้า') ?></a>
                                                </li>

                                                <!-- อ้างอิง -->
                                                <li class="xWMenu xWSubTab xCNStaHideShow" style="cursor:pointer;">
                                                    <a role="tab" data-toggle="tab" data-target="#odvDLVContentHDDocRef" aria-expanded="false"><?= language('document/document/document', 'เอกสารอ้างอิง') ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <!-- รายการสินค้า -->
                                    <div id="odvDLVContentProduct" class="tab-pane fade active in" style="padding: 0px !important;">
                                        <div class="row p-t-15">

                                            <!-- ชือผู้รับ/ลูกค้า -->
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><span class="text-danger">*</span>ชือผู้รับ/ลูกค้า</label>
                                                    <div class="input-group"><input type="text" class="form-control xControlForm xCNHide" id="oetDLVCstCode" name="oetDLVCstCode" maxlength="50" value="<?=@$tDLVFTCstCode?>">
                                                        <input type="text" class="form-control xControlForm xWPointerEventNone" data-validate-required="กรุณาระบุชือผู้รับ/ลูกค้า" id="oetDLVCstName" name="oetDLVCstName" maxlength="255" placeholder="กรุณาเลือกชือผู้รับ/ลูกค้า" value="<?=@$tDLVFTCstName?>" readonly>
                                                        <span class="input-group-btn">
                                                            <button id="obtDLVBrowseCustomers" type="button" class="btn xCNBtnBrowseAddOn">
                                                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPdtHTML" name="oetSearchPdtHTML" onkeyup="JSvDLVSearchPdtHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvDLVSearchPdtHTML()">
                                                                <img class="xCNIconBrowse" src="<?=base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right  xCNHideWhenCancelOrApprove">
                                              <div id="odvDLVMngDelPdtInTableDT" class="btn-group xCNDropDrownGroup">
                                                  <button type="button" class="btn xCNBTNMngTable xWConditionSearchPdt" data-toggle="dropdown">
                                                      <?=language('common/main/main', 'tCMNOption') ?>
                                                      <span class="caret"></span>
                                                  </button>
                                                  <ul class="dropdown-menu" role="menu">
                                                      <li id="oliDLVBtnDeleteMulti" class="disabled">
                                                          <a data-toggle="modal" data-target="#odvDLVModalDelPdtInDTTempMultiple"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                                      </li>
                                                  </ul>
                                              </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 xCNHideWhenCancelOrApprove">
                                                <!--ค้นหาจากบาร์โค๊ด-->
                                                <div class="form-group" style="width: 85%;">
                                                    <input type="text" class="form-control xControlForm" id="oetDLVInsertBarcode" autocomplete="off" name="oetDLVInsertBarcode" maxlength="50" value="" onkeypress="Javascript:if(event.keyCode==13) JSxSearchFromBarcode(event,this);" placeholder="เพิ่มสินค้าด้วยบาร์โค้ด หรือ รหัสสินค้า">
                                                </div>

                                                <!--เพิ่มสินค้าแบบปกติ-->
                                                <div class="form-group">
                                                    <div style="position: absolute;right: 15px;top:-5px;">
                                                        <button type="button" id="obtDLVDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-t-10" id="odvDLVDataPdtTableDTTemp">
                                        </div>

                                        <!--ส่วนสรุปท้ายบิล-->
                                        <div class="odvRowDataEndOfBill" id="odvRowDataEndOfBill">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <label class="pull-left mark-font">จำนวนส่งรวมทั้งสิ้น</label>
                                                    <label class="pull-right mark-font"><span class="mark-font xShowQtyFooter">0</span> <?= language('document/purchaseorder/purchaseorder', 'รายการ'); ?></label>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- อ้างอิงเอกสาร -->
                                    <div id="odvDLVContentHDDocRef" class="tab-pane fade" style="padding: 0px !important;">
                                        <div class="row p-t-15">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                                <div style="margin-top:-2px;">
                                                    <button type="button" id="obtDLVAddDocRef" class="xCNBTNPrimeryPlus xCNDocBrowsePdt xCNHideWhenCancelOrApprove">+</button>
                                                </div>
                                            </div>
                                            <div id="odvDLVTableHDRef"></div>
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

<!-- =========================================== View Modal Appove Document  =========================================== -->
<div id="odvDLVModalAppoveDoc" class="modal fade">
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
                <button onclick="JSxDLVApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== View Modal Cancel Document  =========================================== -->
<div class="modal fade" id="odvDLVPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/delivery/delivery', 'tDLVCancelDoc') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/delivery/delivery', 'tDLVCancelDocWarnning') ?></p>
                <p><strong><?php echo language('document/delivery/delivery', 'tDLVCancelDocConfrim') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnDLVCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== View Modal Delete Product In DT DocTemp Multiple  =========================================== -->
<div id="odvDLVModalDelPdtInDTTempMultiple" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelMultiple" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type="hidden" id="ohdConfirmDLVDocNoDelete" name="ohdConfirmDLVDocNoDelete">
                <input type="hidden" id="ohdConfirmDLVSeqNoDelete" name="ohdConfirmDLVSeqNoDelete">
                <input type="hidden" id="ohdConfirmDLVPdtCodeDelete" name="ohdConfirmDLVPdtCodeDelete">
                <input type="hidden" id="ohdConfirmDLVPunCodeDelete" name="ohdConfirmDLVPunCodeDelete">

            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelMultiple" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== Modal ไม่พบรหัสสินค้า =========================================== -->
<div id="odvDLVModalPDTNotFound" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('document/delivery/delivery', 'tDLVPdtNotFound') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal" onclick="JSxNotFoundClose();">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== พบสินค้ามากกว่าหนึ่งตัว =========================================== -->
<div id="odvDLVModalPDTMoreOne" class="modal fade">
    <div class="modal-dialog" role="document" style="width: 85%; margin: 1.75rem auto;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('document/delivery/delivery', 'tDLVSelectPdt') ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                        <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JCNxConfirmPDTMoreOne(1)" data-dismiss="modal"><?php echo language('document/delivery/delivery', 'tDLVChoose') ?></button>
                        <button class="btn xCNBTNDefult xCNBTNDefult2Btn" onclick="JCNxConfirmPDTMoreOne(2)" data-dismiss="modal"><?php echo language('document/delivery/delivery', 'tDLVClose') ?></button>
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

<!-- =========================================== อ้างอิงเอกสารภายใน =========================================== -->
<div id="odvDLVModalRefIntDoc" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 1200px;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard olbDLVModalRefIntDoc"></label>
            </div>
            <div class="modal-body">
                <div class="row" id="odvDLVFromRefIntDoc"></div>
            </div>
            <div class="modal-footer">
                <button id="obtConfirmRefDocInt" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================== อ้างอิงเอกสารภายใน (ภายใน หรือ ภายนอก) =========================================== -->
<div id="odvDLVModalAddDocRef" class="modal fade" tabindex="1" role="dialog" style='z-index:1045'>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="ofmDLVFormAddDocRef" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?=language('common/main/main', 'อ้างอิงเอกสาร') ?></label>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control xCNHide" id="oetDLVRefDocNoOld" name="oetDLVRefDocNoOld">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'ประเภทการอ้างอิงเอกสาร'); ?></label>
                                <select class="selectpicker form-control" id="ocbDLVRefType" name="ocbDLVRefType">
                                    <option value="1" selected><?=language('common/main/main', 'อ้างอิงภายใน'); ?></option>
                                    <option value="3"><?=language('common/main/main', 'อ้างอิงภายนอก'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main', 'เอกสาร'); ?></label>
                                <input type="hidden" id="ocbDLVRefDoc" name="ocbDLVRefDoc" value="1">
                                <input type="text" class="form-control" maxlength="20" value="ใบขาย" readonly>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefInt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" id="oetDLVDocRefInt" name="oetDLVDocRefInt" maxlength="20" value="">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetDLVDocRefIntName" name="oetDLVDocRefIntName" maxlength="20" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง') ?>" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtDLVBrowseRefDoc" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetDLVRefDocNo" name="oetDLVRefDocNo" placeholder="<?php echo language('common/main/main', 'เลขที่เอกสารอ้างอิง'); ?>" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('document/document/document', 'วันที่เอกสารอ้างอิง'); ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetDLVRefDocDate" name="oetDLVRefDocDate" placeholder="YYYY-MM-DD" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="obtDLVRefDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12 xWShowRefExt">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('common/main/main', 'ค่าอ้างอิง'); ?></label>
                                <input type="text" class="form-control" id="oetDLVRefKey" name="oetDLVRefKey" placeholder="<?php echo language('common/main/main', 'ค่าอ้างอิง'); ?>" maxlength="10" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="obtDLVConfirmAddDocRef" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="submit"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================== ไม่พบลูกค้า ============================================= -->
<div id="odvDLVModalPleseselectCST" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tMessageAlert') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>กรุณาเลือกชือผู้รับ/ลูกค้า ก่อนอ้างอิงเอกสาร</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNPrimery" data-dismiss="modal">
                    <?= language('common/main/main', 'tCMNOK') ?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- =========================================== กำหนดที่อยู่ ============================================= -->
<div id="odvDLVModalAddress" class="modal fade" tabindex="-1" role="dialog">
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
                                <input type="text" class="input100 xCNHide" id="ohdShipAddrCode" name="ohdShipAddrCode" value="">
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrName" name="ohdShipAddrName" value="" readonly placeholder="<?= language('company/company/company', 'tCMPAddrName'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtShipBrowseAddr" type="button" class="btn xCNBtnBrowseAddOn" >
                                        <img src="<?=  base_url() . '/application/modules/common/assets/images/icons/find-24.png'; ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- ที่อยู่แยก -->
                    <div class="xWDLVAddress1">
                        <div class="col-lg-12">
                            <!--หมายเลขประชำตัวผู้เสียภาษีของที่อยู่-->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPTaxNo'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrTaxNo" name="ohdShipAddrTaxNo" value="" readonly placeholder="<?= language('company/company/company', 'tCMPTaxNo'); ?>">
                            </div>

                            <!--บ้านเลขที่-->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPAddressNo'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrNoHouse" name="ohdShipAddrNoHouse" value="" readonly placeholder="<?= language('company/company/company', 'tCMPAddressNo'); ?>">
                            </div>

                            <!--หมู่บ้าน / อาคาร-->
                            <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPVillage'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrVillage" name="ohdShipAddrVillage" value="" readonly placeholder="<?= language('company/company/company', 'tCMPVillage'); ?>">
                            </div>

                            <!--ถนน-->
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPRoad'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrRoad" name="ohdShipAddrRoad" value="" readonly placeholder="<?= language('company/company/company', 'tCMPRoad'); ?>">
                            </div>

                            <!--ซอย-->
                            <div class="form-group">
                            <label class="xCNLabelFrm">ซอย</label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrSoi" name="ohdShipAddrSoi" value="" readonly placeholder="ซอย">
                            </div>

                        </div>

                        <!--แขวง / ตำบล-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPSubDistrict'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrSubDistrict" name="ohdShipAddrSubDistrict" value="" readonly placeholder="<?= language('company/company/company', 'tCMPSubDistrict'); ?>">
                            </div>
                        </div>

                        <!--เขต / อำเภอ-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPDistict'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrDistict" name="ohdShipAddrDistict" value="" readonly placeholder="<?= language('company/company/company', 'tCMPDistict'); ?>">
                            </div>
                        </div>

                        <!--จังหวัด-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPProvince'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrProvince" name="ohdShipAddrProvince" value="" readonly placeholder="<?= language('company/company/company', 'tCMPProvince'); ?>">
                            </div>
                        </div>

                        <!--รหัสไปรณีย์-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPZipCode'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipZipCode" name="ohdShipZipCode" value="" readonly placeholder="<?= language('company/company/company', 'tCMPZipCode'); ?>">
                            </div>
                        </div>

                        <!--เบอร์โทร-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPTel'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrTel" name="ohdShipAddrTel" value="" readonly placeholder="<?= language('company/company/company', 'tCMPTel'); ?>">
                            </div>
                        </div>

                        <!--เบอร์สาร-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'tCMPFax'); ?></label>
                                <input class="form-control xWPointerEventNone" type="text" id="ohdShipAddrFax" name="ohdShipAddrFax" value="" readonly placeholder="<?= language('company/company/company', 'tCMPFax'); ?>">
                            </div>
                        </div>

                    </div>

                    <!-- ที่อยู่รวม -->
                    <div class="xWDLVAddress2">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'ที่อยู่ 1'); ?></label>
                                <textarea class="form-control" id="ohdShipAddV2Desc1" name="ohdShipAddV2Desc1" maxlength="200" readonly></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('company/company/company', 'ที่อยู่ 2'); ?></label>
                                <textarea class="form-control" id="ohdShipAddV2Desc2" name="ohdShipAddV2Desc2" maxlength="200" readonly></textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmAddress" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" onclick="JSxConfirmAddress();" data-dismiss="modal"><?= language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?= language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url('application/modules/common/assets/src/jThaiBath.js') ?>"></script>
<script src="<?=base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?=base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jDeliveryAdd.php'); ?>