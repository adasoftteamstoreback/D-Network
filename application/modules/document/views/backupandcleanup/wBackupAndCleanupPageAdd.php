<?php
$tRoute = $tRoute;
if (isset($tRoute) && $tRoute == 'docSatisfactionSurveyEventEdit') {
    $nStaPage = 2; //ขาแก้ไข
    $aDetailSurvey = $aDataGetDetail['raItems'][0];

    //เอกอสารหลัก
    $tSatDocNo = $aDetailSurvey['FTXshDocNo'];
    $dSatDocDate = date("Y-m-d", strtotime($aDetailSurvey['FDXshDocDate']));
    $dSatDocTime = $aDetailSurvey['FTXshDocTime'];
    $tSatCreateBy = $aDetailSurvey['FTCreateBy'];
    $tSatUsrNameCreateBy = $aDetailSurvey['FTNameCreateBy'];


    $tSatApvCode = $aDetailSurvey['FTXshApvCode'];
    $tSatUsrNameApv = $aDetailSurvey['ApvBy'];
    $tSatRefType = $aDetailSurvey['FTXshRefType'];
    $tSatStaDoc  = $aDetailSurvey['FTXshStaDoc'];
    $tSatApv = $aDetailSurvey['FTXshStaApv'];

    //ตัวแทนขาย
    $tSatAgnCode = $aDetailSurvey['FTAgnCode'];
    $tSatAgnName = $aDetailSurvey['FTAgnName'];

    //สาขา
    $tSatBchCode = $aDetailSurvey['FTBchCode'];
    $tSatBchName = $aDetailSurvey['FTBchName'];

    //ลูกค้า
    $tCstCode = $aDetailSurvey['FTCstCode'];
    $tCstName = $aDetailSurvey['FTCstName'];
    $tCstTel = $aDetailSurvey['FTCstTel'];
    $tCstEmail = $aDetailSurvey['FTCstEmail'];

    //เอกสารอ้างอิง
    $tDocRefNo = $aDetailSurvey['FTXshRefDocNo'];
    $tDocRefDate = $aDetailSurvey['FDXshRefDocDate'];
    $tCarBrand = $aDetailSurvey['FTCarBrand'];
    $tCarModel = $aDetailSurvey['FTCarModel'];
    $tCarRegNo = $aDetailSurvey['FTCarRegNo'];
    $tBchCode = $aDetailSurvey['FTBchCode'];

    //ผู้ประเมิน
    $tUsrCode = $aDetailSurvey['FTUsrCode'];
    $tUsrName = $aDetailSurvey['SatSvBy'];
    $tUsrDateCreate = date("Y-m-d", strtotime($aDetailSurvey['FDCreateOn']));
    $tDateCreate = $aDetailSurvey['FDCreateOn'];
    $tReadonly = '';
    $tDisabled = '';

    //อื่นๆ
    $nSatStaDocAct = $aDetailSurvey['FNXshStaDocAct'];
    $tSatFrmRmk = $aDetailSurvey['FTXshRmk'];

    // คำตอบ
    $nRateScore = $aDetailSurvey['FNXshScoreValue'];
    $tSatComment = $aDetailSurvey['FTXshAdditional'];
    $nStaUploadFile        = 2;
} else {
    if ($aDataGetDetail['tReturn'] == 1) {
        $nStaPage = 1; //ขาเพิ่ม
        //เอกอสารหลัก
        $tSatDocNo = '';
        $dSatDocDate = '';
        $dSatDocTime = '';
        $tSatCreateBy = $this->session->userdata('tSesUsrUsername');
        $tSatUsrNameCreateBy = $this->session->userdata('tSesUsrUsername');

        $tUsrLevel = $this->session->userdata('tSesUsrLevel');

        $tSatAgnCode = '';

        if ($tUsrLevel != "HQ") {
            $tSatAgnCode = $this->session->userdata('tSesUsrAgnCode');
            $tSatAgnName = $this->session->userdata('tSesUsrAgnName');
        }

        $tSatStaDoc  = '';
        $tClassStaDoc = '';
        $tStaDoc = '';
        $tSatApvCode = '';
        $tSatApv = '';
        $tSatUsrNameApv = '';
        $tSatRefType = '';

        //สาขา
        $tSatBchCode = '';
        $tSatBchName = '';

        //ลูกค้า
        $tCstCode = '';
        $tCstName = '';
        $tCstTel = '';
        $tCstEmail = '';

        //เอกสาร
        $tDocRefNo = '';
        $tDocRefDate = '';
        $tCarBrand = '';
        $tCarModel = '';
        $tCarRegNo = '';

        //ผู้ประเมิน
        $tUsrCode = $this->session->userdata('tSesUserCode');
        $tUsrName = $this->session->userdata('tSesUsrUsername');
        $tUsrDateCreate = '';
        $tDateCreate = '';
        $tReadonly = '';
        $tDisabled = '';

        //อื่นๆ
        $nSatStaDocAct = '';
        $tSatFrmRmk = '';

        // คำตอบ
        $nRateScore = '';
        $tSatComment = '';
        $nStaUploadFile        = 1;
    } else {
        $nStaPage = 1; //ขาเพิ่ม
        //เอกอสารหลัก
        $tSatDocNo = '';
        $dSatDocDate = '';
        $dSatDocTime = '';
        $tSatCreateBy = $this->session->userdata('tSesUsrUsername');
        $tSatUsrNameCreateBy = $this->session->userdata('tSesUsrUsername');

        $tUsrLevel = $this->session->userdata('tSesUsrLevel');

        $tSatAgnCode = $aDataGetDetail['tAgnCode'];
        $tSatAgnName = $aDataGetDetail['tAgnName'];

        // if( $tUsrLevel != "HQ" ){
        //     $tSatAgnCode = $this->session->userdata('tSesUsrAgnCode');
        //     $tSatAgnName = $this->session->userdata('tSesUsrAgnName');
        // }

        $tSatStaDoc  = '';
        $tClassStaDoc = '';
        $tStaDoc = '';
        $tSatApvCode = '';
        $tSatApv = '';
        $tSatUsrNameApv = '';
        $tSatRefType = '';

        //สาขา
        $tSatBchCode = $aDataGetDetail['tBchCode'];
        $tSatBchName = $aDataGetDetail['tBchName'];

        //ลูกค้า
        $tCstCode = $aDataGetDetail['tCstCode'];
        $tCstName = $aDataGetDetail['tCstName'];
        $tCstTel = $aDataGetDetail['tCstTel'];
        $tCstEmail = $aDataGetDetail['tCstEmail'];

        //เอกสาร
        $tDocRefNo = $aDataGetDetail['tDocNo'];
        $tDocRefDate = $aDataGetDetail['dDocDate'];
        $tCarBrand = $aDataGetDetail['tCarBrand'];
        $tCarModel = $aDataGetDetail['tCarModel'];
        $tCarRegNo = $aDataGetDetail['tCarRegNo'];

        //ผู้ประเมิน
        $tUsrCode = $this->session->userdata('tSesUserCode');
        $tUsrName = $this->session->userdata('tSesUsrUsername');
        $tUsrDateCreate = '';
        $tDateCreate = '';
        $tReadonly = '';
        $tDisabled = '';

        //อื่นๆ
        $nSatStaDocAct = '';
        $tSatFrmRmk = '';

        // คำตอบ
        $nRateScore = '';
        $tSatComment = '';
        $nStaUploadFile        = 1;
    }
}

if ($tSatStaDoc == 3) {
    $tClassStaDoc = 'text-danger';
    $tStaDoc = language('common/main/main', 'tStaDoc3');
} else {
    if ($tSatStaDoc == 1 && $tSatApv == '') {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    } elseif ($tSatStaDoc == 1 && $tSatApv == 1) {
        $tClassStaDoc = 'text-success';
        $tStaDoc = language('common/main/main', 'tStaDoc1');
    } else {
        $tClassStaDoc = 'text-warning';
        $tStaDoc = language('common/main/main', 'tStaDoc');
    }
}
?>
<input type="hidden" id="ohdSatSvRoute" name="ohdSatSvRoute" value="<?= $tRoute ?>">
<input type="hidden" id="ohdSatSvCheckClearValidate" name="ohdSatSvCheckClearValidate" value="0">
<input type="hidden" id="ohdSatSvCheckSubmitByButton" name="ohdSatSvCheckSubmitByButton" value="0">

<form id="ofmSatSurveyAddForm" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <div class="panel-body" style="padding-top:20px !important;">
        <!-- Panel Header Nav -->
        <div id="odvBACURowNavMenu" class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="custom-tabs-line tabs-line-bottom left-aligned">
                    <ul class="nav" role="tablist">
                        <!--การสำรองและล้างข้อมูล-->
                        <li id="oliBACUDataPurge" class="xWMenu active xCNStaHideShow" data-menutype="MN" style='cursor: pointer;'>
                            <a role="tab" data-toggle="tab" data-target="#odvBACUContentInfo1" aria-expanded="true"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUTitle') ?></a>
                        </li>
                        <!--ประวัติ-->
                        <li id="oliBACUHistory" class="xWMenu xWSubTab xCNStaHideShow" data-menutype="FHN" style='cursor: pointer;'>
                            <a role="tab" id='BACUHistory' data-toggle="tab" data-target="#odvBACUContentInfo2" aria-expanded="false"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUTableTitleHistory') ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Job2Order -->
        <input type="hidden" id="ohdSatSvOldAgnCode" name="ohdSatSvOldAgnCode" value="<?= $tSatAgnCode ?>">
        <input type="hidden" id="ohdSatSvOldBchCode" name="ohdSatSvOldBchCode" value="<?= $tSatBchCode ?>">
        <input type="hidden" id="ohdSatSvOldDecRefNo" name="ohdSatSvOldDecRefNo" value="<?= $tDocRefNo ?>">
        <!-- end -->

        <!-- sta Doc -->
        <input type="hidden" id="ohdSatStaDoc" name="ohdSatStaDoc" value="<?= $tSatStaDoc ?>">
        <input type="hidden" id="ohdSatStaApv" name="ohdSatStaApv" value="<?= $tSatApv ?>">
        <input type="hidden" id="ohdSatStaApvCode" name="ohdSatStaApvCode" value="<?= $tSatApvCode ?>">
        <!-- end -->

        <button style="display:none" type="submit" id="obtSubmitSat"></button>
        <!-- Tab Content Backup and Cleanup Info 1 -->
        <div class="tab-content">
            <div id="odvBACUContentInfo1" class="tab-pane fade active in">
                <div class="row">
                    <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar">
                        <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->
                        <!-- Panel รหัสเอกสารและสถานะเอกสาร -->
                        <div class="panel panel-default" style="margin-bottom: 25px;">
                            <div id="odvSatHeadStatusInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                                <label class="xCNTextDetail1"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUTitleCondition'); ?></label>
                                <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvSatDataStatusInfo" aria-expanded="true">
                                    <i class="fa fa-plus xCNPlus"></i>
                                </a>
                            </div>
                            <div id="odvSatDataStatusInfo" class="xCNMenuPanelData panel-collapse collapse in" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <!-- ประเภท -->
                                            <!-- ocmSOFrmSplInfoPaymentType -->
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACULabelType'); ?></label>
                                                <select class="selectpicker form-control" id="ocmBACUType" name="ocmBACUType" maxlength="1">
                                                    <option value="1" selected><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionType1'); ?></option>
                                                    <option value="3"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionType2'); ?></option>
                                                    <option value="2"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionType3'); ?></option>
                                                </select>
                                            </div>

                                            <!-- Server -->
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACULabelServer'); ?></label>
                                                <select class="selectpicker form-control" id="ocmBACUServer" name="ocmBACUServer" maxlength="1">
                                                    <option value="1" selected><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionServer1'); ?></option>
                                                    <option value="2"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionServer2'); ?></option>
                                                    <option value="3"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionServer3'); ?></option>
                                                    <option value="4"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionServer4'); ?></option>
                                                </select>
                                            </div>

                                            <!-- From Search Advanced  Agency -->
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?= language('document/backupandcleanup/backupandcleanup', 'tBACUPanelAgency'); ?></label>
                                                <div class="input-group">
                                                    <?php
                                                    if ($this->session->userdata("tSesUsrLevel") != "HQ") {
                                                        $tSatAgnCode = $this->session->userdata('tSesUsrAgnCode');
                                                        $tSatAgnName = $this->session->userdata('tSesUsrAgnName');
                                                        $tDisabled = 'disabled';
                                                    } else {
                                                        $tSatAgnCode = '';
                                                        $tSatAgnName = '';
                                                        $tDisabled = '';
                                                    }

                                                    ?>
                                                    <input class="form-control xCNHide" type="text" id="oetBACUAdvSearchAgnCode" name="oetBACUAdvSearchAgnCode" maxlength="5" value="<?php echo $tSatAgnCode ?>">
                                                    <input class="form-control xWPointerEventNone" type="text" id="oetBACUAdvSearchAgnName" name="oetBACUAdvSearchAgnName" placeholder="<?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUPanelAgency'); ?>" readonly value="<?php echo $tSatAgnName ?>">
                                                    <span class="input-group-btn">
                                                        <button id="obtBACUAdvSearchBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn" <?php echo $tDisabled ?>><img class="xCNIconFind"></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <!-- สาขา -->
                                            <div class="form-group" style="margin-bottom: 0px;">
                                                <label class="xCNLabelFrm"><?php echo language('authen/user/user', 'tUSRBranch') ?></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control xCNHide" id="oetBACUBranchCode" name="oetBACUBranchCode" placeholder="<?php echo language('authen/user/user', 'tUSRBranch') ?>" data-validate-required="<?php echo  language('authen/user/user', 'tUSRVldBchMore'); ?>" readonly>
                                                    <input type="text" class="form-control" id="oetBACUBranchName" name="oetBACUBranchName" value="" readonly>
                                                    <span class="input-group-btn">
                                                        <button id="oimBACUBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn">
                                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div style="white-space:nowrap;width:100%;overflow-x:auto;margin-bottom: 10px;">
                                                <div id="odvBACUBranchShow" style="margin-bottom: 10px;margin-top: 10px;">
                                                    <?php if (isset($aResUsrGroup['raItems']) && !empty($aResUsrGroup['raItems'])) { ?>
                                                        <?php
                                                        $tBchName = "";
                                                        foreach ($aResUsrGroup['raItems'] as $key => $aValue) {

                                                            if (!empty($aValue['FTBchName']) && strpos($tBchName, $aValue['FTBchName']) !== 0) { // เช็คค่าซ้ำ
                                                                $tBchName .= $aValue['FTBchName'];
                                                        ?>
                                                                <span class="label label-info m-r-5 xWCheckBACUBch"><?= $aValue['FTBchName']; ?></span>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    <?php } else { ?>
                                                        <?php if (!empty($tBchName)) { ?>
                                                            <?php foreach (explode(",", $tBchName) as $key => $aValue) { ?>
                                                                <span class="label label-info m-r-5 xWCheckBACUBch"><?= $aValue; ?></span>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <!-- Condition เครื่องจุดขาย -->
                                            <!-- ของเดิมเป็น  tASTPosName  เปลี่ยนเป็น tASTPosCode -->
                                            <div class="form-group" style="margin-bottom: 0px;" id = 'odvBACUPosCode'>
                                                <label class="xCNLabelFrm"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACULabelPos') ?></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control xCNHide" id="oetBACUPosCode" name="oetBACUPosCode" placeholder="<?php echo language('document/backupandcleanup/backupandcleanup', 'tBACULabelPos') ?>" data-validate-required="<?php echo  language('authen/user/user', 'tUSRVldBchMore'); ?>" readonly>
                                                    <input type="text" class="form-control" id="oetBACUPosName" name="oetBACUPosName" value="" readonly>
                                                    <span class="input-group-btn">
                                                        <button id="oimBACUBrowsePos" type="button" class="btn xCNBtnBrowseAddOn">
                                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div style="white-space:nowrap;width:100%;overflow-x:auto;margin-bottom: 10px;">
                                                <div id="odvBACUPosShow" style="margin-bottom: 10px;margin-top: 10px;">
                                                    <?php if (isset($aResUsrGroup['raItems']) && !empty($aResUsrGroup['raItems'])) { ?>
                                                        <?php
                                                        $tBchName = "";
                                                        foreach ($aResUsrGroup['raItems'] as $key => $aValue) {

                                                            if (!empty($aValue['FTPosName']) && strpos($tBchName, $aValue['FTPosName']) !== 0) { // เช็คค่าซ้ำ
                                                                $tBchName .= $aValue['FTPosName'];
                                                        ?>
                                                                <span class="label label-info m-r-5"><?= $aValue['FTPosName']; ?></span>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    <?php } else { ?>
                                                        <?php if (!empty($tBchName)) { ?>
                                                            <?php foreach (explode(",", $tBchName) as $key => $aValue) { ?>
                                                                <span class="label label-info m-r-5"><?= $aValue; ?></span>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <!-- กลุ่ม -->
                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACULabelGroup'); ?></label>
                                                <select class="selectpicker form-control" id="ocmBACUGroup" name="ocmBACUGroup" maxlength="1">
                                                    <option value="1" selected><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionGroup1'); ?></option>
                                                    <option value="4"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionGroup2'); ?></option>
                                                    <option value="2"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionGroup3'); ?></option>
                                                    <option value="3"><?php echo language('document/backupandcleanup/backupandcleanup', 'tBACUOptionGroup4'); ?></option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9">
                        <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
                        <div class="row">
                            <!-- ตารางรายการ -->
                            <div id="odvCPHDataPanelDetail" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="panel panel-default" style="margin-bottom: 25px;">
                                    <div class="panel-collapse collapse in" role="tabpanel">
                                        <div class="panel-body">
                                            <div class="row p-t-10">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <div style="position: absolute;left: 15px;top:-5px;">
                                                            <!--ค้นหา-->
                                                            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                                            </div>
                                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetBACUSearchPdtHTML" name="oetBACUSearchPdtHTML" onkeyup="JSvBACUSearchDataHTML()" placeholder="<?= language('common/main/main', 'tPlaceholder'); ?>">
                                                                        <span class="input-group-btn">
                                                                            <button id="oimMngPdtIconSearch" class="btn xCNBtnSearch" type="button" onclick="JSvBACUSearchDataHTML()">
                                                                                <img class="xCNIconBrowse" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                                                    <div class="form-group">

                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group" id='odvBACUAdvDataTable'>
                                                        <div style="padding-top: 25px;">
                                                            <div class="table-responsive" id="otbBACUDataTable">
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
            </div>
            <!-- Tab Content History -->
            <div id="odvBACUContentInfo2" class="fade tab-content">
                <div class="panel-headline">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control xCNInpuTXOthoutSingleQuote" type="text" id="oetBACUSearchAllDocument" name="oetBACUSearchAllDocument" placeholder="<?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACFillTextSearch') ?>" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button id="obtBACUSerchAllDocument" type="button" class="btn xCNBtnDateTime"><img class="xCNIconSearch"></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button id="obtBACUAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tAdvanceSearch'); ?></button>
                            <button id="obtBACUSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn"><?php echo language('common/main/main', 'tClearSearch'); ?></button>
                        </div>
                        <div id="odvBACUAdvanceSearchContainer" class="hidden" style="margin-bottom:20px;">
                            <form id="ofmBACUFromSerchAdv" class="validate-form" action="javascript:void(0)" method="post">
                                <div class="row">

                                    <?php
                                    if ($this->session->userdata("tSesUsrLevel") != "HQ") {
                                        if ($this->session->userdata("nSesUsrBchCount") <= 1) { //ค้นหาขั้นสูง
                                            $tBCHCode   = $this->session->userdata("tSesUsrBchCodeDefault");
                                            $tBCHName   = $this->session->userdata("tSesUsrBchNameDefault");
                                        } else {
                                            $tBCHCode   = '';
                                            $tBCHName   = '';
                                        }
                                    } else {
                                        $tBCHCode   = '';
                                        $tBCHName   = '';
                                    }
                                    ?>

                                    <!-- From Search Advanced  Branch -->
                                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <?php
                                            if ($this->session->userdata("tSesUsrLevel") != "HQ") {
                                                if ($this->session->userdata("nSesUsrBchCount") <= 1) { //ค้นหาขั้นสูง
                                                    $tBCHCode     = $this->session->userdata("tSesUsrBchCodeDefault");
                                                    $tBCHName     = $this->session->userdata("tSesUsrBchNameDefault");
                                                } else {
                                                    $tBCHCode     = '';
                                                    $tBCHName     = '';
                                                }
                                            } else {
                                                $tBCHCode         = '';
                                                $tBCHName         = '';
                                            }
                                            ?>
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOAdvSearchBranch'); ?></label>
                                            <div class="input-group">
                                                <input class="form-control xCNHide" type="text" id="oetBACUHisAdvSearchBchCodeFrom" name="oetBACUHisAdvSearchBchCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                                <input class="form-control xWPointerEventNone" type="text" id="oetBACUHisAdvSearchBchNameFrom" name="oetBACUHisAdvSearchBchNameFrom" placeholder="<?php echo language('document/saleorder/saleorder', 'tSOAdvSearchBranch'); ?>" readonly value="<?= $tBCHName; ?>">
                                                <span class="input-group-btn">
                                                    <button id="obtBACUHisAdvSearchBrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder', 'tSOAdvSearchBranchTo'); ?></label>
                                            <div class="input-group">
                                                <input class="form-control xCNHide" id="oetBACUHisAdvSearchBchCodeTo" name="oetBACUHisAdvSearchBchCodeTo" maxlength="5" value="<?= $tBCHCode; ?>">
                                                <input class="form-control xWPointerEventNone" type="text" id="oetBACUHisAdvSearchBchNameTo" name="oetBACUHisAdvSearchBchNameTo" placeholder="<?php echo language('document/saleorder/saleorder', 'tSOAdvSearchBranchTo'); ?>" readonly value="<?= $tBCHName; ?>">
                                                <span class="input-group-btn">
                                                    <button id="obtBACUHisAdvSearchBrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                     <!-- From Search Advanced  Date -->
                                    <div class="col-xs-12 col-md-6 col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-6">
                                                <label class="xCNLabelFrm"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPurgDateFrom'); ?></label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input class="form-control input100 xCNDatePicker" type="text" id="oetSearchDocDateFrom" name="oetSearchDocDateFrom" autocomplete="off" placeholder="<?php echo language('document/topupVending/topupVending', 'tFrom'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="obtSearchDocDateFrom" type="button" class="btn xCNBtnDateTime">
                                                                <img src="<?php echo base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-6">
                                                <label class="xCNLabelFrm"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACPurgDateTo'); ?></label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input class="form-control input100 xCNDatePicker" type="text" id="oetSearchDocDateTo" name="oetSearchDocDateTo" autocomplete="off" placeholder="<?php echo language('document/topupVending/topupVending', 'tTo'); ?>">
                                                        <span class="input-group-btn">
                                                            <button id="obtSearchDocDateTo" type="button" class="btn xCNBtnDateTime">
                                                                <img src="<?php echo base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACAgnFC'); ?></label>
                                            <div class="input-group">
                                                <input class="form-control xCNHide" type="text" id="oetBACUHisAdvSearchAgnCodeFrom" name="oetBACUHisAdvSearchAgnCodeFrom" maxlength="5" value="<?= $tBCHCode; ?>">
                                                <input class="form-control xWPointerEventNone" type="text" id="oetBACUHisAdvSearchAgnNameFrom" name="oetBACUHisAdvSearchAgnNameFrom" placeholder="<?php echo language('settingconfig/backupandcleardata/backupandcleardata', 'tBACAgnFC'); ?>" readonly value="<?= $tBCHName; ?>">
                                                <span class="input-group-btn">
                                                    <button id="obtBACUHistoryAdvSearchBrowseAgn" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- From Search Advanced  AGN -->
                                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label class="xCNLabelFrm">&nbsp;</label>
                                            <button id="obtBACUAdvSearchSubmitForm" class="btn xCNBTNPrimery" style="width:100%"><?php echo language('common/main/main', 'tSearch'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="panel-body">
                        <section id="ostBACUHistoryDataTableDocument"></section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include('script/jBackupAndCleanupPageForm.php'); ?>


<!-- ควบคุม Checkbox -->
<script>
    $("document").ready(function() {

    })

    $('.xCNCheckBoxPoint').click(function(elem) {
        var nSeqdt = $(this).attr('data-seqdt');
        var tDocno = $(this).attr('data-docno');
        $('.xCNCheckBoxPoint' + tDocno + nSeqdt).prop("checked", false);
        $(this).prop("checked", true);
    });

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    //ค้นหาสินค้าใน temp
    function JSvBACUSearchDataHTML() {
        var value = $("#oetBACUSearchPdtHTML").val().toLowerCase();
        $("#otbBACUDataTable tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    $("#ocmBACUServer").change(function() {
        JSvBACUGetAdvData();
    });

    $("#ocmBACUGroup").change(function() {
        JSvBACUGetAdvData();
    });

    $("#ocmBACUType").change(function() {
        JSvBACUGetAdvData();
    });
</script>


<!-- madal insert success -->
<div id="odvSatModalAddSuccess" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'แจ้งเตือน') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"><?php echo language('common/main/main', 'บันทึกการประเมินสำเร็จ') ?></span>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- madal validate-->
<div id="odvSatModalvalidate" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'แจ้งเตือน') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"><?php echo language('common/main/main', 'กรุณาตอบคำถามให้ครบถ้วน') ?></span>
            </div>
            <div class="modal-footer">
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== View Modal Appove Document  ======================================================================== -->
<div id="odvSatModalAppoveDoc" class="modal fade">
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
                <button onclick="JSxSatApproveDocument(true)" type="button" class="btn xCNBTNPrimery">
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
<div class="modal fade" id="odvSATPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('document/deliveryorder/deliveryorder', 'tDOCancelDoc') ?></label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv"><?php echo language('document/deliveryorder/deliveryorder', 'tDOCancelDocWarnning') ?></p>
                <p><strong><?php echo language('document/deliveryorder/deliveryorder', 'tDOCancelDocConfrim') ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnSATCancelDocument(true)" type="button" class="btn xCNBTNPrimery">
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