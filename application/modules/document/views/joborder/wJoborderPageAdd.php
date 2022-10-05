<style>
    #odvJOBRowDataEndOfBill .panel-heading{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
    #odvJOBRowDataEndOfBill .panel-body{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    #odvJOBRowDataEndOfBill .list-group-item {
        padding-left: 0px !important;
        padding-right: 0px !important;
        border: 0px solid #ddd;
    }
    .mark-font, .panel-default > .panel-heading.mark-font{
        color: #232C3D !important;
        font-weight: 900;
    }

    .xCNHideTD {
        border: none;
    }

    .xCNViewDetailBtn{
        cursor: pointer;
        color: #0081c2;
    }


    .xCNStockConfirm_text{
        color       : #1b9f7f !important;
        font-weight : bold !important;
    }

    .xCNStockWaitConfirm_text{
        color       : #ca9d1b !important;
        font-weight : bold !important;
    }

</style>
<?php
    $tRoute = $tRoute;
    if (isset($tRoute) && $tRoute == 'docJOBEventEdit') {
        $nStaPage = 2; //ขาแก้ไข
        $aDetail = @$aDataGetDetail['raItems'][0];
        $aDataSumVat = $aSumVat['raItems'][0];

        //เอกอสารหลัก
        $tJOBDocNo = $aDetail['FTXshDocNo'];
        $dJOBDocDate = date("Y-m-d", strtotime($aDetail['FDXshDocDate']));
        $dJOBDocTime = $aDetail['FTXshDocTime'];
        $tJOBCreateBy = $aDetail['FTCreateBy'];
        $tJOBUsrNameCreateBy = $aDetail['FTNameCreateBy'];


        $tJOBApvCode = $aDetail['FTXshApvCode'];
        $tJOBUsrNameApv = $aDetail['ApvBy'];
        $tJOBStaDoc  = $aDetail['FTXshStaDoc'];
        $tJOBApv = $aDetail['FTXshStaApv'];

        //ตัวแทนขาย
        $tJOBAgnCode = $aDetail['FTAgnCode'];
        $tJOBAgnName = $aDetail['FTAgnName'];

        //สาขา
        $tJOBBchCode = $aDetail['FTBchCode'];
        $tJOBBchName = $aDetail['FTBchName'];

        //ลูกค้า
        $tCstCode = $aDetail['FTCstCode'];
        $tCstName = $aDetail['FTCstName'];
        $tCstTel = $aDetail['FTCstTel'];
        $tCstEmail = $aDetail['FTCstEmail'];

        //เอกสารอ้างอิง
        $tJOBRefType = $aDetail['FTXshRefType1'];
        $tDocRefNo = $aDetail['FTXshRefDocNo1'];
        if ($aDetail['FDXshRefDocDate1'] != '') {
            $tDocRefDate = date("Y-m-d", strtotime($aDetail['FDXshRefDocDate1']));
        }else{
            $tDocRefDate = '';
        }
        $tDocToPos = $aDetail['FTSpsName'];
        $tJOBRefType3 = $aDetail['FTXshRefType3'];
        $tDocRefNo3 = $aDetail['FTXshRefDocNo3'];
        $tDocRefDate3 = date("Y-m-d", strtotime($aDetail['FDXshRefDocDate3']));

        //ข้อมูลรถ
        $tCarCode = $aDetail['FTCarCode'];
        $tCarRegNo = $aDetail['FTCarRegNo'];
        $tCarEngineNo = $aDetail['FTCarEngineNo'];
        $tCarVIDRef = $aDetail['FTCarVIDRef'];
        $tCarType = $aDetail['FTCarType'];
        $tCarBrand = $aDetail['FTCarBrand'];
        $tCarModel = $aDetail['FTCarModel'];
        $tCarColor = $aDetail['FTCarColor'];
        $tCarGear = $aDetail['FTCarGear'];
        $tCarPowerType = $aDetail['FTCarPowerType'];
        $tCarEngineSize = $aDetail['FTCarEngineSize'];
        $tCarCategory = $aDetail['FTCarCategory'];
        $tCarMileage = $aDetail['FCXshCarMileage'];
        $tCarProvince = $aDetail['FTPvnName'];

        //ผู้ประเมิน
        $tUsrCode = $aDetail['FTUsrCode'];
        $tUsrName = $aDetail['FTNameCreateBy'];
        $tUsrDateCreate = date("Y-m-d", strtotime($aDetail['FDCreateOn']));
        $tDateCreate = $aDetail['FDCreateOn'];
        $tReadonly = '';
        $tDisabled = '';
        $dStartDateChk = date("Y-m-d", strtotime($aDetail['FDXshStartChk']));
        $dStartTimeChk = $aDetail['FDXshStartChkTime'];
        $dEndDateChk = date("Y-m-d", strtotime($aDetail['FDXshFinishChk']));
        $dEndTimeChk = $aDetail['FDXshFinishChkTime'];

        //อื่นๆ
        $nJOBStaDocAct = $aDetail['FNXshStaDocAct'];
        $tJOBFrmRmk = $aDetail['FTXshRmk'];
        $tJOBFrmRmk1 = $aDetail['FTXshCarChkRmk1'];
        $tJOBFrmRmk2 = $aDetail['FTXshCarChkRmk2'];
        $nStaUploadFile        = 2;
        $nXshGrand              = $aDetail['FCXshGrand'];
        $nXshTotal              = $aDetail['FCXshTotal'];
        $tXshDisChgTxt          = $aDetail['FTXshDisChgTxt'];
        $nXshDis                = $aDetail['FCXshDis'];
        $nXshTotalAfDisChgV     = $aDetail['FCXshTotalAfDisChgV'];
        $nXshVat                = $aDataSumVat['FCXsdVat'];

    }

    if ($tJOBStaDoc == 3) {
        $tClassStaDoc = 'text-danger';
        $tStaDoc = language('common/main/main', 'tStaDoc3');
    }else{
        if ($tJOBStaDoc == 1 && $tJOBApv == '') {
            $tClassStaDoc = 'text-warning';
            $tStaDoc = language('common/main/main', 'tStaDoc');
        }elseif ($tJOBStaDoc == 1 && $tJOBApv == 1) {
            $tClassStaDoc = 'text-success';
            $tStaDoc = language('common/main/main', 'tStaDoc1');
        }else{
            $tClassStaDoc = 'text-warning';
            $tStaDoc = language('common/main/main', 'tStaDoc');
        }
    }
?>
<input type="hidden" id="ohdJOBRoute" name="ohdJOBRoute" value="<?=$tRoute?>">
<input type="hidden" id="ohdJOBCheckClearValidate" name="ohdJOBCheckClearValidate" value="0">
<input type="hidden" id="ohdJOBCheckSubmitByButton" name="ohdJOBCheckSubmitByButton" value="0">

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

<form id="ofmJOBAddForm" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <!-- Job2Order -->
    <input type="hidden" id="ohdJOBOldAgnCode" name="ohdJOBOldAgnCode" value="<?=$tJOBAgnCode?>">
    <input type="hidden" id="ohdJOBOldBchCode" name="ohdJOBOldBchCode" value="<?=$tJOBBchCode?>">
    <input type="hidden" id="ohdJOBOldDecRefNo" name="ohdJOBOldDecRefNo" value="<?=$tDocRefNo?>">
    <input type="hidden" id="ohdJOBUseInRef" name="ohdJOBUseInRef" value="<?=@$tStaFindDocNoUse?>">

    <!-- sta Doc -->
    <input type="hidden" id="ohdJOBStaDoc" name="ohdJOBStaDoc" value="<?= $tJOBStaDoc ?>">
    <input type="hidden" id="ohdJOBStaApv" name="ohdJOBStaApv" value="<?= $tJOBApv ?>">
    <input type="hidden" id="ohdJOBStaApvCode" name="ohdJOBStaApvCode" value="<?= $tJOBApvCode ?>">

    <input type="hidden" id="ohdJOBCarCode" name="ohdJOBCarCode" value="<?=$tCarCode?>">
    <input type="hidden" id="ohdJOBCreateOn" name="ohdJOBCreateOn" value="<?=$tDateCreate?>">
    <input type="hidden" id="ohdJOBCreateDate" name="ohdJOBCreateDate" value="<?=date("Y-m-d", strtotime($tDateCreate))?>">
    <input type="hidden" id="ohdJOBOldDocRefCode" name="ohdJOBOldDocRefCode" value="<?php echo $tDocRefNo;?>">

    <button style="display:none" type="submit" id="obtSubmitJOB" onclick="JSoAddEditJOB('<?= $tRoute ?>')"></button>
    <div class="row">
    <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar"> <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvJOBHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder', 'tDODoucment'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvJOBDataStatusInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                      <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvJOBDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group xCNHide" style="text-align: right;">
                                    <label class="text-success xCNTitleFrom"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmAppove');?></label>
                                </div>
                                <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/deliveryorder/deliveryorder','tDOLabelFrmDocNo'); ?></label>
                                <?php if(isset($tJOBDocNo) && empty($tJOBDocNo)):?>
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" id="ocbJOBStaAutoGenCode" name="ocbJOBStaAutoGenCode" maxlength="1" checked="checked">
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder','tDOLabelFrmAutoGenCode');?></span>
                                    </label>
                                </div>
                                <?php endif;?>
                                <!-- เลขรหัสเอกสาร -->
                                <div class="form-group" style="cursor:not-allowed">
                                    <input
                                        type="text"
                                        class="form-control xControlForm xCNGenarateCodeTextInputValidate xCNInputWithoutSpcNotThai"
                                        id="oetJOBDocNo"
                                        name="oetJOBDocNo"
                                        maxlength="20"
                                        value="<?php echo $tJOBDocNo;?>"
                                        data-validate-required="<?php echo language('document/deliveryorder/deliveryorder','tSATPlsEnterOrRunDocNo'); ?>"
                                        data-validate-duplicate="<?php echo language('document/purchaseorder/purchaseorder','tPOPlsDocNoDuplicate'); ?>"
                                        placeholder="<?php echo language('document/deliveryorder/deliveryorder','tDOLabelFrmDocNo');?>"
                                        style="pointer-events:none"
                                        readonly
                                    >
                                    <input type="hidden" id="ohdJOBCheckDuplicateCode" name="ohdJOBCheckDuplicateCode" value="2">
                                </div>
                                <!-- วันที่ในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder','tDOLabelFrmDocDate');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNDatePicker xCNInputMaskDate"
                                            id="oetJOBDocDate"
                                            name="oetJOBDocDate"
                                            value="<?php echo $dJOBDocDate; ?>"
                                            placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocDate');?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtJOBDocDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- เวลาในการออกเอกสาร -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocTime');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNTimePicker xCNInputMaskTime"
                                            id="oetJOBDocTime"
                                            name="oetJOBDocTime"
                                            value="<?php echo $dJOBDocTime; ?>"
                                            placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmDocTime');?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtJOBDocTime" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- ผู้สร้างเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder','tDOLabelFrmCreateBy');?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdJOBCreateBy" name="ohdJOBCreateBy" value="<?php echo $tJOBCreateBy?>">
                                            <label><?php echo $tJOBUsrNameCreateBy?></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- สถานะเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmStaDoc'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <?php
                                                if($tRoute == "docJOBEventAdd"){
                                                    $tJOBLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc');
                                                }else{
                                                    $tJOBLabelStaDoc  = language('document/purchaseorder/purchaseorder', 'tPOLabelFrmValStaDoc'.$tJOBStaDoc);
                                                }
                                            ?>
                                            <label><?php echo $tJOBLabelStaDoc;?></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- สถานะอนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmStaApv'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                        <label class="<?php echo $tClassStaDoc;?>">
                                            <?php echo $tStaDoc;?>
                                        </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- ผู้อนุมัติเอกสาร -->
                                <div class="form-group" style="margin:0" id="odvJOBApvBy">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmApvBy'); ?></label>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                            <input type="hidden" id="ohdJOBApvCode" name="ohdJOBApvCode" maxlength="20" value="<?php echo $tJOBApvCode?>">
                                            <label>
                                                <?php echo (isset($tJOBUsrNameApv) && !empty($tJOBUsrNameApv))? $tJOBUsrNameApv : "-" ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ข้อมูลลูกค้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvJOBRefInfoPanel" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'ข้อมูลลูกค้า'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvJOBRefInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvJOBRefInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body" style="padding-top: 0px !important">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-t-20">
                                <!-- Browse ตัวแทนขาย -->
                                <div class="form-group xCNHide">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?></label>
                                    <div class="form-group">
                                        <input type="text" class="form-control xControlForm xCNHide" id="oetJOBAgnCode" name="oetJOBAgnCode" maxlength="5" value="<?= @$tJOBAgnCode; ?>">
                                        <input  type="text"
                                                class="form-control xControlForm xWPointerEventNone"
                                                id="oetJOBAgnName" name="oetJOBAgnName"
                                                maxlength="100"
                                                placeholder="<?php echo language('interface/connectionsetting/connectionsetting', 'tTBAgency') ?>"
                                                value="<?= @$tJOBAgnName; ?>"
                                                data-validate-required="<?php echo language('document/deliveryorder/deliveryorder','tDOPlsEnterSplCode'); ?>"
                                                readonly>
                                    </div>
                                </div>

                                <!-- Browse สาขา -->
                                <div class="form-group xCNHide">
                                <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch')?></label>
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                            id="ohdJOBBchCode"
                                            name="ohdJOBBchCode"
                                            maxlength="5"
                                            value="<?php echo @$tJOBBchCode?>"
                                            data-bchcodeold = "<?php echo @$tJOBBchCode?>"
                                        >
                                        <input
                                            type="text"
                                            class="form-control xControlForm xWPointerEventNone"
                                            id="oetJOBBchName"
                                            name="oetJOBBchName"
                                            maxlength="100"
                                            placeholder="<?php echo language('document/deliveryorder/deliveryorder', 'tDOLabelFrmBranch')?>"
                                            data-validate-required="<?php echo language('document/deliveryorder/deliveryorder','tDOPlsEnterBch'); ?>"
                                            value="<?php echo @$tJOBBchName?>"
                                            readonly
                                        >
                                    </div>
                                </div>
                                <!-- Browse ชื่อลูกค้า -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('customer/customer/customer','tCSTTitle'); ?></label>
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                                            id="ohdJOBCstCode"
                                            name="ohdJOBCstCode"
                                            maxlength="5"
                                            value="<?php echo $tCstCode;?>"
                                        >
                                        <input
                                            type="text"
                                            class="form-control xWPointerEventNone"
                                            id="oetJOBCstName"
                                            name="oetJOBCstName"
                                            maxlength="100"
                                            value="<?php echo $tCstName;?>"
                                            readonly
                                            data-validate-required="<?php echo language('document/inspectionafterservice/inspectionafterservice','tJOBCstValidate'); ?>"
                                            placeholder="<?php echo language('customer/customer/customer','tCSTTitle'); ?>"
                                        >
                                    </div>
                                </div>

                                <!-- ที่อยู่ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','ที่อยู่'); ?></label>
                                    <?php @$tAddress = $aCSTAddress[0]; ?>
                                    <?php if (@$tAddress['FTAddVersion'] == 1) { ?>
                                        <textarea name="otaJOBCstAddress" id="otaJOBCstAddress" cols="30" rows="4" readonly>
                                            <?=@$tAddress['FTAddV1No']?> <?=@$tAddress['FTAddV1Soi']?> <?=@$tAddress['FTAddV1Road']?> <?=@$tAddress['FTSudName']?> <?=@$tAddress['FTDstName']?> <?=@$tAddress['FTPvnName']?> <?=@$tAddress['FTAddV1PostCode']?>
                                        </textarea>
                                    <?php }elseif (@$tAddress['FTAddVersion'] == 2){ ?>
                                        <textarea name="otaJOBCstAddress" id="otaJOBCstAddress" cols="30" rows="4" readonly><?=@$tAddress['FTAddV2Desc1']?> <?=@$tAddress['FTAddV2Desc2']?></textarea>
                                    <?php }else{ ?>
                                        <textarea name="otaJOBCstAddress" id="otaJOBCstAddress" cols="30" rows="4" readonly>-</textarea>
                                    <?php } ?>
                                </div>

                                <!-- เบอร์โทรศัพท์ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCstTelNo'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc" id="oetJOBCstTel" name="oetJOBCstTel" placeholder="<?php echo language('customer/customer/customer','tCstTelNo'); ?>" value="<?php echo $tCstTel;?>" readonly>
                                </div>

                                <!-- e-mail -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTEmail'); ?></label>
                                    <input type="email" class="form-control xCNInputWhenStaCancelDoc" id="oetJOBCstMail" name="oetJOBCstMail" placeholder="<?php echo language('customer/customer/customer','tCSTEmail'); ?>" value="<?php echo $tCstEmail;?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel อ้างอิงเอกสาร -->
            <div class="panel panel-default xCNHide" style="margin-bottom: 25px;">
                <div id="odvJOBInfoCst" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/deliveryorder/deliveryorder','อ้างอิงเอกสาร');?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvJOBDataInfoCst" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvJOBDataInfoCst" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- อ้างอืงใบรับรถ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/joborder/joborder','tJOBDocRefIntNo'); ?></label>
                                    <div class="form-group">
                                        <input
                                            type="hidden"
                                            class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote xCNClaerValWhenCstChange"
                                            id="ohdJOBDocRefCode"
                                            name="ohdJOBDocRefCode"
                                            maxlength="5"
                                            value="<?php echo $tDocRefNo;?>"
                                        >
                                        <input
                                            type="text"
                                            class="form-control xWPointerEventNone xCNClaerValWhenCstChange"
                                            id="oetJOBDocRefCode"
                                            name="oetJOBDocRefCode"
                                            maxlength="100"
                                            value="<?php echo $tDocRefNo;?>"
                                            readonly
                                            data-validate-required="<?php echo language('document/joborder/joborder','tJOBDocRefIntNo'); ?>"
                                            placeholder="<?php echo language('customer/customer/customer','tIASDocRefTask'); ?>"
                                        >
                                    </div>
                                </div>

                                <!-- วันที่อ้างอิงใบสั่งงาน -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/joborder/joborder','tJOBDocRefIntDate'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetJOBDateStaService" name="oetJOBDateStaService" value="<?=$tDocRefDate ?>" placeholder="<?php echo language('document/joborder/joborder','tJOBDocRefIntDate'); ?>" readonly>
                                </div>

                                <!-- อ้างอิงเอกสารภายนอก -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice','tIASDocRefExt'); ?></label>
                                    <input type="hidden" id="ohdJOBDocRefExtCode" name="ohdJOBDocRefExtCode" value="<?=$tDocRefNo3?>" >
                                    <input
                                        type="text"
                                        class="form-control xControlForm xCNInputWhenStaCancelDoc"
                                        id="oetJOBDocRefExtCode"
                                        name="oetJOBDocRefExtCode"
                                        maxlength="100"
                                        value="<?php
                                                    if ($tJOBRefType3 == 3) {
                                                        echo $tDocRefNo3;
                                                    }else{
                                                        echo '';
                                                    }
                                                ?>"
                                        placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASDocRefExt'); ?>"
                                    >
                                </div>
                                <!-- วันที่อ้างอิงเอกสารภายนอก  -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice','tIASDocRefDateExt'); ?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNDatePicker xCNInputMaskDate"
                                            id="oetJOBDocRefExtDate"
                                            name="oetJOBDocRefExtDate"
                                            value="<?php
                                                    if ($tJOBRefType3 == 3) {
                                                        echo $tDocRefDate3;
                                                    }else{
                                                        echo '';
                                                    }
                                                ?>"
                                            placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASDocRefDateExt'); ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtJOBDocRefExtDate" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel อืนๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvJOBInfoOther" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/joborder/joborder','tJOBOther');?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvJOBDataInfoOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvJOBDataInfoOther" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                                <!-- สถานะความเคลื่อนไหว -->
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <?php
                                        $tJOBStaAct = '';
                                            if ($nJOBStaDocAct == 1) {
                                                $tJOBStaAct = 'checked';
                                            }elseif ($nJOBStaDocAct == 2) {
                                                $tJOBStaAct = '';
                                            }else{
                                                $tJOBStaAct = 'checked';
                                            }
                                        ?>
                                        <input type="checkbox" value="1" id="ocbJOBStaDocAct" name="ocbJOBStaDocAct" maxlength="1" <?php echo $tJOBStaAct;?>>
                                        <span>&nbsp;</span>
                                        <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthStaDocAct'); ?></span>
                                    </label>
                                </div>

                                <!-- ช่องให้บริการ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice','tIASServiceToPos'); ?></label>
                                    <input type="text" class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange" id="oetJOBServiceToPos" name="oetJOBServiceToPos" value="<?=$tDocToPos ?>" placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASServiceToPos'); ?>" readonly>
                                </div>

                                <!-- วันที่เริ่มตรวจสอบ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder','tIASLabelFrmDocDateBegin');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNDatePicker xCNInputMaskDate"
                                            id="oetJOBDocDateBegin"
                                            name="oetJOBDocDateBegin"
                                            value="<?php echo $dStartDateChk; ?>"
                                            placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASLabelFrmDocDateBegin'); ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtJOBDocDateBegin" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
                                </div>
                                <!-- วันที่เสร็จสิ้นการตรวจสอบ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('document/deliveryorder/deliveryorder','tIASLabelFrmDocDateEnd');?></label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control xControlForm xCNDatePicker xCNInputMaskDate"
                                            id="oetJOBDocDateEnd"
                                            name="oetJOBDocDateEnd"
                                            value="<?php echo $dEndDateChk; ?>"
                                            placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASLabelFrmDocDateEnd'); ?>"
                                        >
                                        <span class="input-group-btn">
                                            <button id="obtJOBDocDateEnd" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                                        </span>
                                    </div>
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
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvJOBDataFile" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvJOBDataFile" class="xCNMenuPanelData panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="odvJOBShowDataTable">


                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    var oJOBCallDataTableFile = {
                        ptElementID     : 'odvJOBShowDataTable',
                        ptBchCode       : $('#ohdJOBBchCode').val(),
                        ptDocNo         : $('#oetJOBDocNo').val(),
                        ptDocKey        : 'TSVTJob2OrdHD',
                        ptSessionID     : '<?= $this->session->userdata("tSesSessionID") ?>',
                        pnEvent         : <?= $nStaUploadFile ?>,
                        ptCallBackFunct : '',
                        ptStaApv        : $('#ohdJOBStaApv').val(),
                        ptStaDoc        : $('#ohdJOBStaDoc').val()
                    }
                    JCNxUPFCallDataTable(oJOBCallDataTableFile);
                </script>
            </div>

        </div>
        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9"> <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <!-- ข้อมูลรถ -->
                <div id="odvCPHDataPanelDetail" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div id="odvPdtRowNavMenu" class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="custom-tabs-line tabs-line-bottom left-aligned">
                                                <ul class="nav" role="tablist">

                                                    <!-- ข้อมูลรถ -->
                                                    <li id="odvCarInfo" class="xWMenu active xCNStaHideShow" data-menutype="MN" style="cursor: pointer;">
                                                        <a role="tab" data-toggle="tab" data-target="#odvCarInfoTab" aria-expanded="true"><?php echo language('document/inspectionafterservice/inspectionafterservice', 'tIASCarData') ?></a>
                                                    </li>

                                                    <!-- อ้างอิง -->
                                                    <li id="odvJOBDataDocRefInfo" class="xWMenu xWSubTab xCNStaHideShow" data-menutype="FHN" style="cursor: pointer;">
                                                        <a role="tab" data-toggle="tab" data-target="#odvJOBDataDocRefInfoTab" aria-expanded="false"><?php echo language('document/joborder/joborder', 'tJOBRefDocAll') ?></a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div id="odvCarInfoTab" class="tab-pane fade active in" style="padding: 0px !important;">
                                            <div class="row p-t-10">
                                                <div class="form-group">
                                                    <!-- ทะเบียน -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice','tIASServiceCarNo'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarNo"
                                                                    name="oetJOBCarNo"
                                                                    placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASServiceCarNo'); ?>"
                                                                    value="<?php echo $tCarRegNo;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- จังหวัด -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice','tIASCarProvince'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBProvinceName"
                                                                    name="oetJOBProvinceName"
                                                                    placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASCarProvince'); ?>"
                                                                    value="<?php echo $tCarProvince;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- เลขเครื่องยนต์ -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice','tIASCarEngineCode'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarEngineCode"
                                                                    name="oetJOBCarEngineCode"
                                                                    placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASCarEngineCode'); ?>"
                                                                    value="<?php echo $tCarEngineNo;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- เลขตัวถัง -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice','tIASCarPowerCode'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarPowerCode"
                                                                    name="oetJOBCarPowerCode"
                                                                    placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASCarPowerCode'); ?>"
                                                                    value="<?php echo $tCarVIDRef;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- ประเภท/ลักษณะ -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo','tCAITitle1'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarType"
                                                                    name="oetJOBCarType"
                                                                    placeholder="<?php echo language('product/carinfo/carinfo','tCAITitle1'); ?>"
                                                                    value="<?php echo $tCarType;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- ประเภท/เจ้าของ -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo','tCAITitle8'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarOwnerType"
                                                                    name="oetJOBCarOwnerType"
                                                                    placeholder="<?php echo language('product/carinfo/carinfo','tCAITitle8'); ?>"
                                                                    value="<?php echo $tCarCategory;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- ยี่ห้อ -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo','tCAITitle2'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarBrand"
                                                                    name="oetJOBCarBrand"
                                                                    placeholder="<?php echo language('product/carinfo/carinfo','tCAITitle2'); ?>"
                                                                    value="<?php echo $tCarBrand;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- รุ่น -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo','tCAITitle3'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarModel"
                                                                    name="oetJOBCarModel"
                                                                    placeholder="<?php echo language('product/carinfo/carinfo','tCAITitle3'); ?>"
                                                                    value="<?php echo $tCarModel;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- สี -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo','tCAITitle4'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarColor"
                                                                    name="oetJOBCarColor"
                                                                    placeholder="<?php echo language('product/carinfo/carinfo','tCAITitle4'); ?>"
                                                                    value="<?php echo $tCarColor;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- เกียร์ -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo','tCAITitle5'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarGear" name="oetJOBCarGear"
                                                                    placeholder="<?php echo language('product/carinfo/carinfo','tCAITitle5'); ?>"
                                                                    value="<?php echo $tCarGear;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- เครื่องยนต์ -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('product/carinfo/carinfo','tCAITitle6'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarEngineOil"
                                                                    name="oetJOBCarEngineOil"
                                                                    placeholder="<?php echo language('product/carinfo/carinfo','tCAITitle6'); ?>"
                                                                    value="<?php echo $tCarPowerType;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- ขนาดเครื่องยนต์ -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice','tIASCarCldVol'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarCldVol"
                                                                    name="oetJOBCarCldVol"
                                                                    placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASCarCldVol'); ?>"
                                                                    value="<?php echo $tCarEngineSize;?>"
                                                                    readonly>
                                                        </div>
                                                    </div>
                                                    <!-- เลขไมล์ -->
                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice','tIASCarMileAge'); ?></label>
                                                            <input  type="text"
                                                                    class="form-control xCNInputWhenStaCancelDoc xCNClaerValWhenCstChange"
                                                                    id="oetJOBCarMileAge"
                                                                    name="oetJOBCarMileAge"
                                                                    placeholder="<?php echo language('document/inspectionafterservice/inspectionafterservice','tIASCarMileAge'); ?>"
                                                                    value="<?php echo number_format($tCarMileage, 0);?>"
                                                                    readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    </div>

                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="odvJOBDataDocRefInfoTab" class="tab-pane fade" style="padding: 0px !important;">
                                            <div style="margin-top: 15px;">
                                                <div class="table-responsive">
                                                    <table class="table" id="otbJOBPdtAdvTable">
                                                        <thead>
                                                            <tr>
                                                                <th nowrap class="xCNTextBold text-center" width="20%"><?php echo language('document/joborder/joborder','tJOBDocRefType'); ?></th>
                                                                <th nowrap class="xCNTextBold text-center" width="30%"><?php echo language('document/joborder/joborder','tJOBDocRefName'); ?></th>
                                                                <th nowrap class="xCNTextBold text-center" width="30%"><?php echo language('document/joborder/joborder','tJOBDocRefCode'); ?></th>
                                                                <th nowrap class="xCNTextBold text-center" width="20%"><?php echo language('document/joborder/joborder','tJOBDocRefDate'); ?></th>
                                                                <th nowrap class="xCNTextBold text-center xCNHide" width="10%"><?php echo language('document/joborder/joborder','tJOBDocRefView'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                                <?php if (!empty($aAllDocRef['raItems'] && $aAllDocRef['raItems'] != "")) { ?>
                                                                    <?php foreach ($aAllDocRef['raItems'] as $key => $tDocRef) { ?>
                                                                        <?php
                                                                            $tType = $tDocRef['FTXshRefKey'];

                                                                            switch ($tType) {
                                                                                case 'QT':
                                                                                    $tTitle = language('document/quotation/quotation','tQTTitle');
                                                                                break;

                                                                                case 'Job4Apv':
                                                                                    $tTitle = language('document/inspectionafterservice/inspectionafterservice','tIASTitle');
                                                                                break;

                                                                                case 'Job5Score':
                                                                                    $tTitle = language('document/satisfactionsurvey/satisfactionsurvey','tSatSurveyTitle');
                                                                                break;

                                                                                case 'Job1Req':
                                                                                    $tTitle = language('document/jobrequest1/jobrequest1','tJR1Title');
                                                                                break;

                                                                                case 'Job3Chk':
                                                                                    $tTitle = language('document/prerepairresult/prerepairresult','tPreSurveyTitle');
                                                                                break;

                                                                                case 'SaleInt':
                                                                                    $tTitle = language('document/reimbursement/reimbursement','tRBMTitle');
                                                                                break;

                                                                                case 'SalTwo':
                                                                                    $tTitle = language('document/reimbursement/reimbursement','tRBMTitle2');
                                                                                break;

                                                                                case 'ABB':
                                                                                    $tTitle = 'เอกสารบิลขาย';
                                                                                break;

                                                                                default:
                                                                                    $tTitle = 'ไม่ได้ระบุ';
                                                                                break;
                                                                            }
                                                                        ?>
                                                                        <tr>
                                                                            <?php
                                                                                if ($tDocRef['FTXshRefType'] == 1) {
                                                                                    $tTitleType = "tJOBDocRefIn";
                                                                                }elseif ($tDocRef['FTXshRefType'] == 2) {
                                                                                    $tTitleType = "tJOBDocRefInDo";
                                                                                }else{
                                                                                    $tTitleType = "tJOBDocRefExt";
                                                                                }
                                                                            ?>
                                                                            <td><label class="pull-left"><?= language('document/joborder/joborder',$tTitleType);?></label></td>
                                                                            <td><label class="pull-left"><?=$tTitle;?></label></td>
                                                                            <td class="text-left"><?= $tDocRef['FTXshRefDocNo'] ?></td>
                                                                            <?php if($tDocRef['FDXshRefDocDate'] != ''){ ?>
                                                                            <td class="text-center"><?= date("d/m/Y", strtotime($tDocRef['FDXshRefDocDate']));?></td>
                                                                            <?php }else{ ?>
                                                                            <td class="text-center"></td>
                                                                            <?php } ?>
                                                                            <td class="text-center xCNHide" nowrap>
                                                                            <?php if ($tType == 'QT' || $tType == 'Job1Req' || $tType == 'Job3Chk' || $tType == 'Job4Apv' || $tType == 'Job5Score') { ?>
                                                                                <img
                                                                                    class="xCNIconTable"
                                                                                    style="width: 17px;"
                                                                                    src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>"
                                                                                    onClick="JSxGotoDocRefPage('<?= $tJOBAgnCode ?>','<?= $tJOBBchCode ?>','<?= $tDocRef['FTXshRefDocNo'] ?>','<?= $tType ?>')"
                                                                                >
                                                                            <?php }else{ ?>
                                                                                <img
                                                                                    class="xCNIconTable"
                                                                                    style="width: 17px; cursor:not-allowed;"
                                                                                    src="<?= base_url('application/modules/common/assets/images/icons/view2.png'); ?>"
                                                                                >
                                                                            <?php } ?>
                                                                            </td>
                                                                        </tr>

                                                                    <?php } ?>
                                                                <?php }else{ ?>
                                                                    <tr>
                                                                        <td class="text-center" colspan="4"><label><?= language('document/joborder/joborder','tJOBDocRefNotFound');?></label></td>

                                                                    </tr>
                                                                <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                     <!-- ตารางรายการคำถาม -->
                    <div class="panel panel-default" style="margin-bottom: 25px;">
                        <div class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div class="row" style="padding-top: 15px;">
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <label class="xCNLabelFrm"><?php echo language('document/inspectionafterservice/inspectionafterservice','สถานะยืนยันสั่งงาน'); ?></label>
                                        <select class="selectpicker form-control" id="ocmJOBStaStock" name="ocmJOBStaStock" style="display: inline !important;" onchange="JSxStaStock()">
                                            <option value='all' selected><?php echo language('document/joborder/joborder','ทั้งหมด'); ?></option>
                                            <option value='<?php echo language('document/joborder/joborder','tJOBStaProcessStk1'); ?>'><?php echo language('document/joborder/joborder','tJOBStaProcessStk1'); ?></option>
                                            <option value='<?php echo language('document/joborder/joborder','tJOBStaProcessStk'); ?>'><?php echo language('document/joborder/joborder','tJOBStaProcessStk'); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="table-responsive" id="otbJORDocPdtAdvTableList" style="padding-top: 25px;">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder','tJOBSeq'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder','tJOBPdtCode'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder','tJOBList'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder','สถานะยืนยันสั่งงาน'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder','tJOBPdtType'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder','tJOBQty'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder','tJOBPun'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder','tJOBPrice'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder','tJOBDiscount'); ?></th>
                                                <th nowrap class="xCNTextBold text-center"><?php echo language('document/joborder/joborder','tJOBPriceTotal'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <!--กรณีไม่พบข้อมูล-->
                                            <tr class="xCNDataNotFound" style="display:none">
                                                <td class='text-center xCNTextDetail2' colspan='100%'>ไม่พบข้อมูลตามเงื่อนไข</td>
                                            </tr>

                                            <?php
                                                $nSeq = 0;
                                                $tStaProcessStk = '';
                                            ?>
                                            <?php foreach ($aDataDetail['raItems'] as $key => $tVal) { ?>
                                            <tr class="xWContent">
                                                <?php if ($nSeq != $tVal['FNXsdSeqNo']) {?>
                                                    <td class="text-center" rowspan="<?=$tVal['PARTITIONBYDOC'];?>"><?=$tVal['FNXsdSeqNo'];?></td>
                                                <?php } ?>
                                                <?php
                                                    $tPsvType = "";
                                                    if ($tVal['FTPsvType'] == 1) {
                                                        $tPsvType = 'สินค้าทั่วไป';
                                                    }else if ($tVal['FTPsvType'] == 4 || $tVal['FTPsvType'] == 5) {
                                                        $tPsvType = 'ชุดบริการ';
                                                    }else if($tVal['FTPsvType'] == 2 || $tVal['FTPsvType'] == 3){
                                                        $tPsvType = 'สินค้าชุด';
                                                    }else{
                                                        $tPsvType = 'สินค้าทั่วไป';
                                                    }
                                                ?>
                                                <?php if ($tVal['PDTSetOrPDT'] == 1) { ?>

                                                    <?php
                                                        //ถ้าเป็นตัวสุดท้าย ก่อนขึ้น row ใหม่ไม่ต้องใส่ CSS
                                                        if($tVal['PARTITIONBYDOC'] > 1){
                                                            $tCssBorder = "border-bottom: 1px solid #ffffff !important;";
                                                        }else{
                                                            $tCssBorder = "";
                                                        }
                                                    ?>
                                                    <td rowspan="<?=$tVal['PARTITIONBYDOC'];?>"><?php echo $tVal['FTPdtCode'] ?></td>
                                                    <td style="<?=$tCssBorder?>"><?php echo $tVal['FTXsdPdtName'] ?></td>
                                                    <?php

                                                        if ($tVal['FTXsdStaApvTask'] != '') {
                                                            $tStaProcessStk = 'tJOBStaProcessStk1';
                                                            $tCssProcessStk = 'xCNStockConfirm_text';
                                                        }else{
                                                            $tStaProcessStk = 'tJOBStaProcessStk';
                                                            $tCssProcessStk = 'xCNStockWaitConfirm_text';
                                                        }
                                                    ?>
                                                    <td class="text-left" style="<?=$tCssBorder?>">
                                                        <input type="hidden" class="xWStaStock" value="<?php echo language('document/joborder/joborder',$tStaProcessStk)?>">
                                                        <label class="<?=$tCssProcessStk?>"><?php echo language('document/joborder/joborder',$tStaProcessStk)?></label>
                                                    </td>
                                                    <td style="<?=$tCssBorder?>"><?php echo $tPsvType ?></td>
                                                    <td class="text-right" rowspan="<?=$tVal['PARTITIONBYDOC'];?>"><?php echo number_format($tVal['FCXsdQty']) ?></td>
                                                    <td class="text-left" rowspan="<?=$tVal['PARTITIONBYDOC'];?>"><?php echo $tVal['FTPunName'] ?></td>
                                                    <td class="text-right" rowspan="<?=$tVal['PARTITIONBYDOC'];?>"><?php echo number_format($tVal['FCXsdSalePrice'],$nOptDecimalShow) ?></td>
                                                    <td class="text-right" rowspan="<?=$tVal['PARTITIONBYDOC'];?>"><?php echo number_format($tVal['FCXsdDis'],$nOptDecimalShow) ?></td>
                                                    <td class="text-right" rowspan="<?=$tVal['PARTITIONBYDOC'];?>"><?php echo number_format($tVal['FCXsdNetAfHD'],$nOptDecimalShow) ?></td>
                                                <?php }else{ ?>
                                                    <?php
                                                        //ถ้าเป็นตัวสุดท้าย ก่อนขึ้น row ใหม่ต้องใส่ CSS
                                                        if($tVal['PARTITIONBYDOC'] == $tVal['ROW_ID']){
                                                            $tCssBorder = "border-bottom: 1px solid #dee2e6 !important;";
                                                        }else{
                                                            $tCssBorder = "border-bottom: 1px solid #ffffff !important;";
                                                        }

                                                        $tPsvTypeInDTSet = "";
                                                        if ($tVal['FTPsvType'] == 1) {
                                                            $tPsvTypeInDTSet = language('document/joborder/joborder','tJOBChangeHavePrice');
                                                        }elseif ($tVal['FTPsvType'] == 2) {
                                                            $tPsvTypeInDTSet = language('document/joborder/joborder','tJOBCheckNoPrice');
                                                        }else{
                                                            $tPsvTypeInDTSet = language('document/joborder/joborder','tJOBServiceSet');
                                                        }
                                                    ?>
                                                    <td style="<?=$tCssBorder?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $tVal['FTXsdPdtName'] ?></td>
                                                    <td style="<?=$tCssBorder?>"><input type="hidden" class="xWStaStock" value="<?php echo language('document/joborder/joborder',$tStaProcessStk)?>"></td>
                                                    <td style="<?=$tCssBorder?>"><?php echo $tPsvTypeInDTSet ?></td>
                                                <?php } ?>
                                            </tr>
                                            <?php $nSeq = $tVal['FNXsdSeqNo']; ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row p-t-10" id="odvJOBRowDataEndOfBill" >
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="pull-left mark-font"><?= language('document/depositdoc/depositdoc', 'tDPSVatAndRmk'); ?></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div style='padding: 10px 10px 0px 10px;'>
                                                <!-- หมายเหตุ -->
                                                <div class="form-group">
                                                    <textarea class="form-control" id="otaJOBFrmInfoOthRmk" name="otaJOBFrmInfoOthRmk" maxlength="200"><?php echo $tJOBFrmRmk?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="mark-font"><?php echo language('document/joborder/joborder','tJOBMaintenance')?></label>
                                                    <textarea class="form-control" id="otaJOBFrmInfoOthRmk1" name="otaJOBFrmInfoOthRmk1" maxlength="200" readonly><?php echo $tJOBFrmRmk1?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" id="otaJOBFrmInfoOthRmk2" name="otaJOBFrmInfoOthRmk2" maxlength="200" readonly><?php echo $tJOBFrmRmk2?></textarea>
                                                </div>
                                            </div>

                                            <div class="panel-heading">
                                                <div class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBVatRate');?></div>
                                                <div class="pull-right mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBAmountVat');?></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body">
                                                <ul class="list-group" id="oulIVDataListVat">
                                                    <?php foreach ($aVatRate['raItems'] as $key => $tVal) { ?>
                                                        <label class="pull-left"><?=number_format($tVal['FCXsdVatRate'], $nOptDecimalShow)?>%</label>
                                                        <label class="pull-right" id="olbJOBSumFCXtdNet"><?=number_format($tVal['FCXsdVat'], $nOptDecimalShow)?></label><br>
                                                        <div class="clearfix"></div>
                                                    <?php } ?>

                                                </ul>
                                            </div>
                                            <div class="panel-heading">
                                                <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBTotalValVat');?></label>
                                                <label class="pull-right mark-font" id="olbJOBVatSum"><?=$nXshVat?></label>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                                        <div class="panel panel-default">
                                            <div class="panel-heading mark-font" id="odvJOBDataTextBath"></div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdNet');?></label>
                                                        <label class="pull-right mark-font" id="olbJOBSumFCXtdNet"><?=number_format($nXshTotal, $nOptDecimalShow)?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder','tPOTBDisChg');?></label>
                                                        <label class="pull-left" style="margin-left: 5px;" id="olbJOBDisChgHD"><?=$tXshDisChgTxt?></label>
                                                        <label class="pull-right" id="olbJOBSumFCXtdAmt"><?=number_format($nXshDis, $nOptDecimalShow)?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdNetAfHD');?></label>
                                                        <label class="pull-right" id="olbJOBSumFCXtdNetAfHD"><?=number_format($nXshTotalAfDisChgV, $nOptDecimalShow)?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label class="pull-left"><?= language('document/purchaseorder/purchaseorder','tPOTBSumFCXtdVat');?></label>
                                                        <label class="pull-right" id="olbJOBSumFCXtdVat"><?=number_format($nXshVat, $nOptDecimalShow)?></label>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="panel-heading">
                                                <label class="pull-left mark-font"><?= language('document/purchaseorder/purchaseorder','tPOTBFCXphGrand');?></label>
                                                <input type="hidden" id="ohdJOBCalFCXphGrand" value="<?php echo $nXshGrand;?>">
                                                <label class="pull-right mark-font" id="olbJOBCalFCXphGrand"><?php echo number_format($nXshGrand, $nOptDecimalShow);?></label>
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

<!-- =========================================== ยกเลิกเอกสาร  ============================================= -->
<div class="modal fade" id="odvJOB2PopupCancel">
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
                <button onclick="JSxJobDocumentCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?= language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?= language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url('application/modules/common/assets/src/jThaiBath.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jJoborderPageForm.php');?>


<!-- ควบคุม Checkbox -->
<script>
    $("document").ready(function(){
        JSxJOBSetRowSpan();

        var tTextTotal  = $('#olbJOBCalFCXphGrand').text().replace(/,/g, '');
        var tThaibath 	= ArabicNumberToText(tTextTotal);
        $('#odvJOBDataTextBath').text(tThaibath);
    })

    function JSxJOBSetRowSpan(){
        $('.xWJOBtr').each(function(){
            var tDataJOBCode        = $(this).data('seqno');
            var nContDataRowSpan    = $('.xWJOBLng'+tDataJOBCode).length;
            $('.xWJOBtd'+tDataJOBCode).attr('rowspan',nContDataRowSpan);
        });
    }

    $('.xCNCheckBoxPoint').click(function(elem) {
        var nSeqdt = $(this).attr('data-seqdt');
        var tDocno  = $(this).attr('data-docno');
        $('.xCNCheckBoxPoint'+tDocno+nSeqdt).prop( "checked", false );
        $(this).prop( "checked", true );
    });

    //กดเพื่อไปยังเอกสารอื่น
    function JSxGotoDocRefPage($ptJOBAgnCode, $ptJOBBchCode, $ptDocNo, $ptType){
        var tDocNo   = $('#oetJOBDocNo').val();
        var tBchCode = $('#ohdJOBBchCode').val();
        var tAgnCode = $('#oetJOBAgnCode').val();
        var tCstCode = $('#ohdJOBCstCode').val();

        if ($ptType == 'QT') { //ใบเสนอราคา
            var tRoute = 'docQuotation/0/0';
        }else if ($ptType == 'Job4Apv') { //ใบตรวจสภาพหลังบริการ
            var tRoute = 'docIAS/0/0';
        }else if($ptType == 'Job5Score'){
            var tRoute = 'docSatisfactionSurvey/0/0';
        }else if($ptType == 'Job3Chk'){
            var tRoute = 'docPreRepairResult/0/0';
        }else if($ptType == 'Job1Req'){
            var tRoute = 'docJR1/0/0';
        }

        $.ajax({
            type    : "GET",
            url     : tRoute,
            cache   : false,
            timeout : 5000,
            success: function (tResult) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tResult);

                localStorage.tCheckBackStage = 'Job2';
                localStorage.tDocno = tDocNo;
                localStorage.tBchCode = tBchCode;
                localStorage.tAgnCode = tAgnCode;
                localStorage.tCstCode = tCstCode;

                if ($ptType == 'QT') { //ใบเสนอราคา
                    JSvQTCallPageEdit($ptDocNo);
                }else if ($ptType == 'Job4Apv') {
                    JSvIASCallPageEdit($ptJOBAgnCode,$ptJOBBchCode,$ptDocNo);
                }else if($ptType == 'Job5Score'){
                    JSvSatSvCallPageEdit($ptJOBAgnCode,$ptJOBBchCode,$ptDocNo);
                }else if($ptType == 'Job3Chk'){
                    JSvPreSvCallPageEdit($ptJOBAgnCode, $ptJOBBchCode, $ptDocNo);
                }else if($ptType == 'Job1Req'){
                    var tRoute = 'docSatisfactionSurvey/0/0';
                }

            }
        });
    }
</script>


<!-- madal insert success -->
<div id="odvJOBModalAddSuccess" class="modal fade" tabindex="-1" role="dialog">
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

<!-- madal validate-->
<div id="odvJOBModalvalidate" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'แจ้งเตือน')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"><?php echo language('common/main/main', 'กรุณาตอบคำถามให้ครบถ้วน')?></span>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
<div id="odvJOBModalAppoveDoc" class="modal fade">
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
                    <button onclick="JSxJOBApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
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
<div class="modal fade" id="odvJOBPopupCancel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('document/deliveryorder/deliveryorder','tDOCancelDoc')?></label>
                </div>
                <div class="modal-body">
                    <p id="obpMsgApv"><?php echo language('document/deliveryorder/deliveryorder','tDOCancelDocWarnning')?></p>
                    <p><strong><?php echo language('document/deliveryorder/deliveryorder','tDOCancelDocConfrim')?></strong></p>
                </div>
                <div class="modal-footer">
                    <button onclick="JSnJOBCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
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
