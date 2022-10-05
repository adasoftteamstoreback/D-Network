<?php
//Decimal Save ลง 4 ตำแหน่ง
$nDecSave   = FCNxHGetOptionDecimalSave();

//Decimal Show ลง 2 ตำแหน่ง
$nDecShow =  FCNxHGetOptionDecimalShow();

if (isset($nStaAddOrEdit) && $nStaAddOrEdit == 1) {
    $tRoute                     = "productEventEdit";
    $tMenuTabDisable            = "";
    $tMenuTabToggle             = "tab";
    $tMenuTabToggleForForSet5   = "tab";
    $tMenuTabToggleForForSet2   = "tab";
    $nUnitCount = '';
    $tClassHiddenPrice = "";
} else {
    $tRoute                     = "productEventAdd";
    $tMenuTabDisable            = " disabled xCNCloseTabNav";
    $tMenuTabToggleForForSet5   = "false";
    $tMenuTabToggleForForSet2   = "false";
    $tMenuTabToggle             = "false";
    $tMenuTabToggleForSystem    = "false";
    $tClassHiddenPrice = "xCNHide";
    $nUnitCount = $aUnitCount[0]['FTPunCount'];
}

if (isset($aPdtRentalData) && $aPdtRentalData['rtCode'] == '1') {
    //Rental
    $tPdtRentType   = $aPdtRentalData['raItems']['FTPdtRentType'];
    $tPdtStaReqRet  = $aPdtRentalData['raItems']['FTPdtStaReqRet'];
    $tPdtStaPay     = $aPdtRentalData['raItems']['FTPdtStaPay'];
    $tPdtDeposit    = $aPdtRentalData['raItems']['FCPdtDeposit'];
    $tPdtRntShpCode = $aPdtRentalData['raItems']['FTShpCode'];
    $tPdtRntShpName = $aPdtRentalData['raItems']['FTShpName'];
} else {
    //Rental
    $tPdtRentType   = "";
    $tPdtStaReqRet  = "";
    $tPdtStaPay     = "";
    $tPdtDeposit    = "";
    $tPdtRntShpCode = "";
    $tPdtRntShpName = "";
}

// Set Data Info Tab
if (isset($aPdtInfoData) && $aPdtInfoData['rtCode'] == '1') {
    // TabInfo 1
    $tPdtCode       = $aPdtInfoData['raItems']['FTPdtCode'];
    $tPdtName       = $aPdtInfoData['raItems']['FTPdtName'];
    $tPdtNameOth    = $aPdtInfoData['raItems']['FTPdtNameOth'];
    $tPdtNameABB    = $aPdtInfoData['raItems']['FTPdtNameABB'];
    $tVatCode       = $aPdtInfoData['raItems']['FTVatCode'];
    $tVatRate       = number_format($aPdtInfoData['raItems']['FCVatRate'], $nDecShow) . "%";
    $tStaVatBuy     = $aPdtInfoData['raItems']['FTPdtStaVatBuy'];
    $tStkControl    = $aPdtInfoData['raItems']['FTPdtStkControl'];
    $tStaVat        = $aPdtInfoData['raItems']['FTPdtStaVat'];
    $tStaAlwReturn  = $aPdtInfoData['raItems']['FTPdtStaAlwReturn'];
    $tStaPoint      = $aPdtInfoData['raItems']['FTPdtPoint'];
    $tStaAlwDis     = $aPdtInfoData['raItems']['FTPdtStaAlwDis'];
    $tStaActive     = $aPdtInfoData['raItems']['FTPdtStaActive'];
    $tStaLot        = $aPdtInfoData['raItems']['FTPdtStaLot'];
    $tStaAlwWHTax   = $aPdtInfoData['raItems']['FTPdtStaAlwWHTax'];
    $tStaAlwBook    = $aPdtInfoData['raItems']['FTPdtStaAlwBook'];
    $dLotdate       = date('Y-m-d');

    //Napat(Jame) 10/09/2019
    $tPdtType       = $aPdtInfoData['raItems']['FTPdtType'];
    $tPdtSaleType   = $aPdtInfoData['raItems']['FTPdtSaleType'];

    //Napat(Jame) 13/11/2019
    $tPdtStaSetPri  = $aPdtInfoData['raItems']['FTPdtStaSetPri'];
    $tPdtStaSetShwDT = $aPdtInfoData['raItems']['FTPdtStaSetShwDT'];

    //Napat(Jame) 20/11/2020
    $tPdtStaSetPrcStk = $aPdtInfoData['raItems']['FTPdtStaSetPrcStk'];

    // TabInfo 2
    $tBchCode       = $aPdtInfoData['raItems']['FTBchCode'];
    $tBchName       = $aPdtInfoData['raItems']['FTBchName'];
    $tPdtMerCode    = $aPdtInfoData['raItems']['FTMerCode'];
    $tPdtMerName    = $aPdtInfoData['raItems']['FTMerName'];
    $tShpCode       = $aPdtInfoData['raItems']['FTShpCode'];
    $tShpName       = $aPdtInfoData['raItems']['FTShpName'];
    $tMgpCode       = $aPdtInfoData['raItems']['FTMgpCode'];
    $tMgpName       = $aPdtInfoData['raItems']['FTMgpName'];
    $tPgpChain      = $aPdtInfoData['raItems']['FTPgpChain'];
    $tPgpChainName  = $aPdtInfoData['raItems']['FTPgpChainName'];
    $tPtyCode       = $aPdtInfoData['raItems']['FTPtyCode'];
    $tPtyName       = $aPdtInfoData['raItems']['FTPtyName'];
    $tPbnCode       = $aPdtInfoData['raItems']['FTPbnCode'];
    $tPbnName       = $aPdtInfoData['raItems']['FTPbnName'];
    $tPmoCode       = $aPdtInfoData['raItems']['FTPmoCode'];
    $tPmoName       = $aPdtInfoData['raItems']['FTPmoName'];
    $tTcgCode       = $aPdtInfoData['raItems']['FTTcgCode'];
    $tTcgName       = $aPdtInfoData['raItems']['FTTcgName'];
    $tPdtSaleStart  = $aPdtInfoData['raItems']['FDPdtSaleStart'];
    $tPdtSaleStop   = $aPdtInfoData['raItems']['FDPdtSaleStop'];
    $tPdtPointTime  = $aPdtInfoData['raItems']['FCPdtPointTime'];
    $tPdtQtyOrdBuy  = $aPdtInfoData['raItems']['FCPdtQtyOrdBuy'];
    $tPdtMax        = $aPdtInfoData['raItems']['FCPdtMax'];
    $tPdtMin        = $aPdtInfoData['raItems']['FCPdtMin'];
    $tPdtCostDef    = number_format($aPdtInfoData['raItems']['FCPdtCostDef'], $nDecShow);
    $tPdtCostOth    = number_format($aPdtInfoData['raItems']['FCPdtCostOth'], $nDecShow);
    $tPdtCostStd    = $aPdtInfoData['raItems']['FCPdtCostStd'];
    $tPdtRmk        = $aPdtInfoData['raItems']['FTPdtRmk'];
    $tPdtForSystem  = $aPdtInfoData['raItems']['FTPdtForSystem'];
    $dGetDataNow    = "";
    $dGetDataFuture = "";
    $tConditionCode  = $aPdtInfoData['raItems']['FTRolCode'];
    $tConditionName  = $aPdtInfoData['raItems']['FTRolName'];
    $tPdtSetOrSN    = $aPdtInfoData['raItems']['FTPdtSetOrSN'];

    //nattakit nale 22-05-2020
    $tAgnCode      = $aPdtInfoData['raItems']['FTAgnCode'];
    $tAgnName      = $aPdtInfoData['raItems']['FTAgnName'];
    if (isset($aPdtCar[0])) {
        $aPdtCarMaDistance = $aPdtCar[0]['FCPsvMaDistance'];
        $aPdtCarQtyMonth = $aPdtCar[0]['FNPsvMaQtyMonth'];
        $aPdtCarQtyTime = $aPdtCar[0]['FCPsvQtyTime'];
        $aPdtCarWaDistance = $aPdtCar[0]['FCPsvWaDistance'];
        $aPdtCarWaQtyDay = $aPdtCar[0]['FNPsvWaQtyDay'];
        $aPdtCarWaCond = $aPdtCar[0]['FTPsvWaCond'];
    }else{
        $aPdtCarMaDistance = '';
        $aPdtCarQtyMonth = '';
        $aPdtCarQtyTime = '';
        $aPdtCarWaDistance = '';
        $aPdtCarWaQtyDay = '';
        $aPdtCarWaCond = '';
    }

} else {
    // TabInfo 1
    $tPdtCode       = "";
    $tPdtName       = "";
    $tPdtNameOth    = "";
    $tPdtNameABB    = "";
    $tVatCode       = $tVatCompany['tVatCode'];
    $tVatRate       = number_format($tVatCompany['tVatRate'], $nDecShow) . " %";
    $tStaVatBuy     = "";
    $tStkControl    = "";
    $tStaVat        = "";
    $tStaAlwReturn  = "";
    $tStaPoint      = "";
    $tStaAlwDis     = "";
    $tStaActive     = "";
    $tStaLot        = "";
    $tStaAlwWHTax   = "";
    $tStaAlwBook    = "";
    $dLotdate       = "";

    //Napat(Jame) 10/09/2019
    $tPdtType       = "";
    $tPdtSaleType   = "";

    //Napat(Jame) 13/11/2019
    $tPdtStaSetPri      = "1";
    $tPdtStaSetShwDT    = "2";

    //Napat(Jame) 20/11/2020
    $tPdtStaSetPrcStk   = "1";

    // TabInfo 2
    if ($this->session->userdata("tSesUsrLevel") == "SHP" || $this->session->userdata("tSesUsrLevel") == "BCH") {
        if (!FCNbUsrIsAgnLevel()) {
            $tBchCode       = $this->session->userdata("tSesUsrBchCodeDefault");
            $tBchName       = $this->session->userdata("tSesUsrBchNameDefault");
        } else {
            $tBchCode       = '';
            $tBchName       = '';
        }
        $tPdtMerCode    = $this->session->userdata('tSesUsrMerCode');
        $tPdtMerName    = $this->session->userdata('tSesUsrMerName');

        if ($this->session->userdata("tSesUsrLevel") == "SHP") {
            $tShpCode       = $this->session->userdata('tSesUsrShpCodeDefault');
            $tShpName       = $this->session->userdata('tSesUsrShpNameDefault');
        } else {
            $tShpCode       = "";
            $tShpName       = "";
        }
    } else {
        $tBchCode       = "";
        $tBchName       = "";
        $tPdtMerCode    = "";
        $tPdtMerName    = "";
        $tShpCode       = "";
        $tShpName       = "";
    }
    $tPdtSetOrSN    = "";
    $tMgpCode       = "";
    $tMgpName       = "";
    $tPgpChain      = "";
    $tPgpChainName  = "";
    $tPtyCode       = "";
    $tPtyName       = "";
    $tPbnCode       = "";
    $tPbnName       = "";
    $tPmoCode       = "";
    $tPmoName       = "";
    $tTcgCode       = "";
    $tTcgName       = "";
    $tPdtSaleStart  = "";
    $tPdtSaleStop   = "";
    $tPdtPointTime  = 0;
    $tPdtQtyOrdBuy  = 0;
    $tPdtMax        = 0;
    $tPdtMin        = 0;
    $tPdtCostDef    = "";
    $tPdtCostOth    = "";
    $tPdtCostStd    = "";
    $tPdtRmk        = "";
    $tPdtForSystem  = "";
    $dGetDataNow    = $dGetDataNow;
    $dGetDataFuture = $dGetDataFuture;
    $tConditionCode  = "";
    $tConditionName  = "";
    $tAgnCode      = $this->session->userdata('tSesUsrAgnCode');
    $tAgnName      = $this->session->userdata('tSesUsrAgnName');
    $tTextPdtCodeSet = '';
    $tTextPdtNameSet = '';
    $aPdtCarMaDistance = '';
    $aPdtCarQtyMonth = '';
    $aPdtCarQtyTime = '';
    $aPdtCarWaDistance = '';
    $aPdtCarWaQtyDay = '';
    $aPdtCarWaCond = '';
}

if($tPdtSetOrSN == '2'){
    $tMenuTabDisableForSet5       = "disabled xCNCloseTabNav";
    $tMenuTabToggleForForSet5     = "false";
}else{
    $tMenuTabDisableForSet5       = "";
}

if($tPdtSetOrSN == '5'){
    $tMenuTabDisableForSet2       = "disabled xCNCloseTabNav";
    $tMenuTabToggleForForSet2     = "false";
}else{
    $tMenuTabDisableForSet2       = "";
}

if ($aPdtCostDef != array()) {
    $aPdtCostDef = $aPdtCostDef;
} else {
    $aPdtCostDef = 0;
}

if ($aPDTCostExIn != array()) {
    $aPDTCostExIn = $aPDTCostExIn;
} else {
    $aPDTCostExIn = 0;
}


if ($tPdtForSystem != '4') {
    $tMenuTabDisableForSystem    = "disabled xCNCloseTabNav";
    $tMenuTabToggleForSystem     = "false";
} else {
    $tMenuTabDisableForSystem    = "";
    $tMenuTabToggleForSystem     = "tab";
}

if ($tPdtForSystem != '5') {
    $tMenuTabDisableForSystem5    = "disabled xCNCloseTabNav";
    $tMenuTabToggleForSystem5     = "false";
} else {
    $tMenuTabDisableForSystem5    = "";
    $tMenuTabToggleForSystem5     = "";
}

if ($tPdtType != '5') {
    $tMenuTabDisableForSystem1    = "disabled xCNCloseTabNav";
    $tMenuTabToggleForSystem1     = "false";
} else {
    $tMenuTabDisableForSystem1    = "";
    $tMenuTabToggleForSystem1     = "tab";
}
$ocheck = base_url() . 'application/modules/common/assets/images/icons/check.png';
?>

<style>
	.xWEJBoxFilter {
		border: 1px solid #ccc !important;
		position: relative !important;
		padding: 15px !important;
		margin-top: 10px !important;
		padding-bottom: 0px !important;
		margin-bottom: 10px !important;
	}

	.xWEJBoxFilter .xWEJLabelFilter {
		position: absolute !important;
		top: -15px;
		left: 15px !important;
		background: #fff !important;
		padding-left: 10px !important;
		padding-right: 10px !important;
	}
    .xWMenu{
        cursor: pointer;
    }

    .custom-tabs-line-sub ul {
    display: inline-block;
    vertical-align: middle;
    *vertical-align: auto;
    *zoom: 1;
    *display: inline;
    }

    .custom-tabs-line-sub ul>li {
        float: left;
    }

    .custom-tabs-line-sub ul>li a {
        color: #8d9093;
        font-weight: normal;
    }

    .custom-tabs-line-sub.tabs-line-bottom {
    border-bottom: 1px solid #eaeaea;
    }
    .custom-tabs-line-sub.tabs-line-bottom ul>li {
    margin-bottom: -1px;
    }

    .custom-tabs-line-sub.tabs-line-bottom .active a {
        border-top      : 1px solid #eaeaea;
        border-left     : 1px solid #eaeaea;
        border-right    : 1px solid #eaeaea;
        border-bottom   : 1px solid white;
    }

    .xWCustomActive.actived {
        background-color: #4095cbd4 /*#7798bc*/;
    }
    .xWCustomActive.actived td {
        color: #FFFFFF !important;
    }
    
</style>

<input type="hidden" id="ohdErrMsgNotHasUnit" value="<?php echo language('product/product/product', 'tErrMsgNotHasUnit') ?>">
<input type="hidden" id="ohdErrMsgNotHasBarCode" value="<?php echo language('product/product/product', 'tErrMsgNotHasBarCode') ?>">
<input type="hidden" id="ohdErrMsgDupUnitFact" value="<?php echo language('product/product/product', 'tErrMsgDupUnitFact') ?>">
<input type="hidden" id="ohdErrMsgNotHasUnitSmall" value="<?php echo language('product/product/product', 'ohdErrMsgNotHasUnitSmall') ?>">
<input type="hidden" id="oetUseType" name="oetUseType" value="<?php echo $nUseType; ?>">
<input type="hidden" id="oetBchCode" name="oetBchCode" value="<?php echo $nUsrBchCode; ?>">
<input type="hidden" id="oetShpCode" name="oetShpCode" value="<?php echo $nUsrShpCode; ?>">
<input type="hidden" id="oetStatus" name="oetStatus" value="">
<input type="hidden" id="ohdUnitCount" name="ohdUnitCount" value="<?php echo $nUnitCount; ?>">
<input type="hidden" id="ohdPdtSetOrSN" name="ohdPdtSetOrSN" value="<?php echo $tPdtSetOrSN; ?>">
<input type="hidden" id="ohdPdtDecimalShow" name="ohdPdtDecimalShow" value="<?php echo $nDecShow; ?>">
<input type="hidden" id="ohdtRount" name="ohdtRount" value="<?php echo $tRoute; ?>">

<link rel="stylesheet" href="<?=base_url(); ?>application/modules/product/assets/css/product/ada.product.css">
<form action="javascript:void(0);" class="validate-form" method="post" id="ofmAddEditProduct">
    <button type="submit" id="obtSubmitProduct" class="btn btn-primary xCNHide"></button>
    <input type="hidden" id="ohdStaAddOrEdit" class="form-control" value="<?php echo $nStaAddOrEdit; ?>">
    <div class="panel-body" style="padding-top:20px !important;">
        <div id="odvPdtRowNavMenu" class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="custom-tabs-line tabs-line-bottom left-aligned">
                    <ul class="nav" role="tablist">

                        <!--ข้อมูลหลัก-->
                        <li id="oliPdtDataAddInfo1" class="xWMenu active xCNStaHideShow" data-menutype="MN">
                            <a role="tab" data-toggle="tab" data-target="#odvPdtContentInfo1" aria-expanded="true"><?php echo language('product/product/product', 'tPDTTabInfo') ?></a>
                        </li>

                        <!--หมวดหมู่-->
                        <!-- <li id="oliPdtDataAddFashion" class="xWMenu xWSubTab xCNStaHideShow <?php echo $tMenuTabDisable; ?>" data-menutype="FHN">
                            <a role="tab" data-toggle="<?php echo $tMenuTabToggle;?>" data-target="#odvPdtContentFashion" aria-expanded="false"><?php echo language('product/pdtcat/pdtcat', 'tCATTitle') ?></a>
                        </li> -->
                        <li id="oliPdtDataAddFashion" class="xWMenu xWSubTab xCNStaHideShow" data-menutype="FHN">
                            <a role="tab" data-toggle="tab" data-target="#odvPdtContentFashion" aria-expanded="false"><?php echo language('product/pdtcat/pdtcat', 'tCATTitle') ?></a>
                        </li>

                        <!--สินค้าเช่า-->
                        <li id="oliPdtDataAddRental" class="xWMenu xWSubTab xCNStaHideShow <?php echo $tMenuTabDisableForSystem; ?>" data-menutype="RNT">
                            <a role="tab" data-toggle="<?php echo $tMenuTabToggleForSystem?>" data-target="#odvPdtContentRental" aria-expanded="false"><?php echo language('product/product/product', 'tPDTTabRental') ?></a>
                        </li>

                        <!--สินค้าชุด-->
                        <li id="oliPdtDataAddSet" class="xWMenu xWSubTab xCNStaHideShow <?php echo $tMenuTabDisable; echo $tMenuTabDisableForSet2; ?>" data-menutype="SET">
                            <a role="tab" data-toggle="<?php echo $tMenuTabToggleForForSet2?>" data-target="#odvPdtContentSet" aria-expanded="false"><?php echo language('product/product/product', 'tPDTTabSet') ?></a>
                        </li>

                        <!--ยา-->
                        <!-- <li id="oliPdtDataDrug" class="xWMenu xWSubTab <?php //echo $tMenuTabDisableForSystem1 ?>" data-menutype="DRUG" onclick="JSxPdtGetContent();">
                            <a role="tab" data-toggle="<?php //echo $tMenuTabToggleForSystem1; ?>" data-target="#odvPdtContentDrug" aria-expanded="false"><?php //echo language('product/product/product', 'tPdtDrug') ?></a>
                        </li> -->

                        <!--ข้อมูลบำรุงรักษา-->
                        <!-- <li id="oliPdtDataAddSVSet" class="xWMenu xWSubTab xCNStaHideShow <?php echo $tMenuTabDisableForSet5; echo $tMenuTabDisable; ?>" data-menutype="SV">
                            <a role="tab" data-toggle="<?php echo $tMenuTabToggleForForSet5?>" data-target="#odvPdtContentSVSet" aria-expanded="false"><?php echo language('product/product/product', 'tPDTTabSVSet') ?></a>
                        </li> -->

                    </ul>
                </div>
            </div>
        </div>
        <!-- Content tab Add Product -->
        <div id="odvPdtRowContentMenu" class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="tab-content">
                    <!-- Tab Content Product Info 1 -->
                    <div id="odvPdtContentInfo1" class="tab-pane fade active in">
                        <div class="row">
                            <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4">
                                <?php
                                    if ( isset($aPdtImgData['raItems']) && !empty($aPdtImgData['raItems']) ){
                                        $tFirtImage = $aPdtImgData['raItems'][0]['FTImgObj'];
                                        $aImgObj    = $aPdtImgData['raItems'];
                                    }else{
                                        $tFirtImage = '';
                                        $aImgObj    = '';
                                    }

                                    echo FCNtHGetContentUploadImage(@$tFirtImage,'Product','2');
                                    echo FCNtHGetContentTumblrImage(@$aImgObj,'Product');

                                    //ถ้าเป็นขาเพิ่ม จะเป็นเลือกสี
                                    if($tRoute == "productEventAdd"){
                                        echo FCNtHGetContentChooseColor(@$tFirtImage,'Product');
                                    }else{
                                        //ถ้าเป็นขาแก้ไข จะเช็คว่าเป็นสี หรือรูปภาพ
                                        $tPatchImg = FCNtHChkImgColor(@$tFirtImage);
                                        if( $tPatchImg != '0' ){ //เป็นรูปภาพ

                                        }else{ //เป็นสี
                                            echo FCNtHGetContentChooseColor(@$tFirtImage,'Product');
                                        }
                                    }
                                ?>
                            </div>

                            <div class="col-xs-12 col-sm-7 col-md-8 col-lg-8">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPdtForSystemTitle'); ?></label>
                                    <select class="selectpicker form-control" id="ocmPdtForSystem" name="ocmPdtForSystem" maxlength="1" onchange="JSxPdtRentSelectType(this.value)">
                                        <option value="1" <?php echo $tPdtForSystem == "1" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtForSystem1') ?></option>
                                        <!-- <option value="2" <?php echo $tPdtForSystem == "2" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtForSystem2') ?></option>
                                        <option value="3" <?php echo $tPdtForSystem == "3" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtForSystem3') ?></option> -->
                                        <option value="4" <?php echo $tPdtForSystem == "4" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtForSystem4') ?></option>
                                        <?php if (FCNbGetPdtFasionEnabled()) { ?>
                                            <option value="5" <?php echo $tPdtForSystem == "5" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtForSystem5') ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <label class="xCNLabelFrm"><span style="color:red">*</span> <?php echo language('product/product/product', 'tPDTCode'); ?></label>
                                <div id="odvProductAutoGenCode" class="form-group">
                                    <div class="validate-input">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbProductAutoGenCode" name="ocbProductAutoGenCode" checked="true" value="1">
                                            <span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div id="odvProductCodeForm" class="form-group">
                                    <input type="hidden" id="ohdCheckDuplicatePdtCode" name="ohdCheckDuplicatePdtCode" value="1">
                                    <div class="validate-input">
                                        <input type="text" class="form-control xCNGenarateCodeTextInputValidate" maxlength="20" id="oetPdtCode" name="oetPdtCode" data-is-created="<?php echo $tPdtCode; ?>" placeholder="<?php echo language('product/product/product', 'tPDTCode') ?>" autocomplete="off" value="<?php echo $tPdtCode; ?>" data-validate-required="<?php echo language('product/product/product', 'tPDTValidPdtCode'); ?>" data-validate-dublicateCode="<?php echo language('product/product/product', 'tPDTValidPdtCodeDup'); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span> <?php echo language('product/product/product', 'tPDTName'); ?></label>
                                    <input type="text" class="form-control" maxlength="100" id="oetPdtName" name="oetPdtName" value="<?php echo $tPdtName; ?>" placeholder="<?php echo language('product/product/product', 'tPDTName'); ?>" autocomplete="off" data-validate-required="<?php echo language('product/product/product', 'tPDTValidPdtName'); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTNameOth'); ?></label>
                                    <input type="text" id="oetPdtNameOth" class="form-control" maxlength="100" name="oetPdtNameOth" placeholder="<?php echo language('product/product/product', 'tPDTNameOth'); ?>" autocomplete="off" value="<?php echo $tPdtNameOth; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTNameABB'); ?></label>
                                    <input type="text" id="oetPdtNameABB" class="form-control" maxlength="50" name="oetPdtNameABB" value="<?php echo $tPdtNameABB; ?>" placeholder="<?php echo language('product/product/product', 'tPDTNameABB'); ?>" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPdtTypeTitle'); ?></label>
                                    <select class="selectpicker form-control" id="ocmPdtType" name="ocmPdtType" maxlength="1">
                                        <option value="1" <?php echo $tPdtType == "1" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtTypeTitle1') ?></option>
                                        <option value="2" <?php echo $tPdtType == "2" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtTypeTitle2') ?></option>
                                        <option value="3" <?php echo $tPdtType == "3" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtTypeTitle3') ?></option>
                                        <option value="4" <?php echo $tPdtType == "4" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtTypeTitle4') ?></option>
                                        <option value="5" <?php echo $tPdtType == "5" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtTypeTitle5') ?></option>
                                        <option value="6" <?php echo $tPdtType == "6" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtTypeTitle6') ?></option>
                                        <option value="7" <?php echo $tPdtType == "7" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtTypeTitle7') ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPdtSaleType'); ?></label>
                                    <select class="selectpicker form-control" id="ocmPdtSaleType" name="ocmPdtSaleType" maxlength="1" onchange="JSxPdtRentSelectType(this.value)">
                                        <option id="ocmPdtSaleType1" value="1" <?php echo $tPdtSaleType == "1" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtSaleType1') ?></option>
                                        <option id="ocmPdtSaleType2" value="2" <?php echo $tPdtSaleType == "2" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtSaleType2') ?></option>
                                        <option id="ocmPdtSaleType3" value="3" <?php echo $tPdtSaleType == "3" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtSaleType3') ?></option>
                                        <option id="ocmPdtSaleType4" value="4" <?php echo $tPdtSaleType == "4" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tPdtSaleType4') ?></option>
                                    </select>
                                </div>

                                <!-- Date Sale Start // Date Sale Stop -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <!-- Product Date Sale Start -->
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTSaleStart'); ?></label>
                                                    <div class="input-group">
                                                        <input type="text" id="oetPdtSaleStart" class="form-control xCNDatePicker xCNInputMaskDate text-center" autocomplete="off" name="oetPdtSaleStart" value="<?php if ($tPdtSaleStart != "") {
                                                                                                                                                                                                                        echo $tPdtSaleStart;
                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                        echo $dGetDataNow;
                                                                                                                                                                                                                    } ?> ">
                                                        <span class="input-group-btn">
                                                            <button id="obtPdtSaleStart" type="button" class="btn xCNBtnBrowseAddOn">
                                                                <img class="xCNIconFind">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- end Product Date Sale Start -->
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                <!-- Product Date Sale Stop -->
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTSaleStop'); ?></label>
                                                    <div class="input-group">
                                                        <input type="text" id="oetPdtSaleStop" class="form-control xCNDatePicker xCNInputMaskDate text-center" autocomplete="off" name="oetPdtSaleStop" value="<?php if ($tPdtSaleStop != "") {
                                                                                                                                                                                                                    echo $tPdtSaleStop;
                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                    echo $dGetDataFuture;
                                                                                                                                                                                                                } ?> ">
                                                        <span class="input-group-btn">
                                                            <button id="obtPdtSaleStop" type="button" class="btn xCNBtnBrowseAddOn">
                                                                <img class="xCNIconFind">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- end Product Date Sale Stop -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Date Sale Start // Date Sale Stop -->

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                        <!-- Vat -->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTVatrate'); ?></label>
                                            <div class="input-group">
                                                <input type="text" id="ocmPdtVatCode" class="form-control xCNHide" name="ocmPdtVatCode" value="<?php echo $tVatCode ?>">
                                                <input type="text" id="ocmPdtVatName" class="form-control text-right" name="ocmPdtVatName" value="<?php echo $tVatRate; ?>" readonly>
                                                <span class="input-group-btn">
                                                    <button id="obtBrowseVat" type="button" class="btn xCNBtnBrowseAddOn">
                                                        <img class="xCNIconFind">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                                                            <!-- มีภาษี -->
                                                            <label class="fancy-checkbox">
                                                                <script>
                                                                    var tStaCheckVatBuy = "<?php echo $tStaVatBuy; ?>";
                                                                    var tStaVat = "<?php echo $tStaVat; ?>";
                                                                    var tStaNewPdt = "<?php echo $nStaAddOrEdit ?>";
                                                                    if ((typeof(tStaCheckVatBuy) !== 'undefined' && tStaCheckVatBuy == '1' && typeof(tStaVat) !== 'undefined' && tStaVat == '1') || tStaNewPdt == '99') {
                                                                        $('#ocbPdtStaHaveVat').prop("checked", true);
                                                                    } else {
                                                                        $('#ocbPdtStaHaveVat').prop("checked", false);
                                                                    }
                                                                </script>
                                                                <input type="checkbox" id="ocbPdtStaHaveVat" name="ocbPdtStaHaveVat">
                                                                <span><?php echo language('product/product/product', 'มีภาษี') ?></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-2 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                                                            <!--ให้แต้ม -->
                                                            <script>
                                                                var tStaPoint = "<?php echo $tStaPoint; ?>";
                                                                if (typeof(tStaPoint) !== 'undefined' && tStaPoint == '1' || tStaNewPdt == '99') {
                                                                    $('#ocbPdtPoint').prop("checked", true);
                                                                } else {
                                                                    $('#ocbPdtPoint').prop("checked", false);
                                                                }
                                                            </script>
                                                            <label class="fancy-checkbox">
                                                                <input type="checkbox" id="ocbPdtPoint" name="ocbPdtPoint">
                                                                <span><?php echo language('product/product/product', 'tPDTGivePoint') ?></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-2 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                                                            <!-- ตัดสต็อก -->
                                                            <script>
                                                                var tStaCheckStkControl = "<?php echo $tStkControl; ?>";
                                                                if (typeof(tStaCheckStkControl) !== 'undefined' && tStaCheckStkControl == '1' || tStaNewPdt == '99') {
                                                                    $('#ocbPdtStkControl').prop("checked", true);
                                                                } else {
                                                                    $('#ocbPdtStkControl').prop("checked", false);
                                                                }
                                                            </script>
                                                            <label class="fancy-checkbox">
                                                                <input type="checkbox" id="ocbPdtStkControl" name="ocbPdtStkControl">
                                                                <span><?php echo language('product/product/product', 'tPDTStkControl') ?></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-2 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                                                            <!-- ใช้งาน LOT/BAT -->
                                                            <label class="fancy-checkbox">
                                                                <script>
                                                                    var tStaLot = "<?php echo $tStaLot; ?>";
                                                                    if (tStaLot == '1') {
                                                                        $('#ocbPdtStaLot').prop("checked", true);
                                                                    } else {
                                                                        $('#ocbPdtStaLot').prop("checked", false);
                                                                        $("#oliPdtContentControlLot").hide();
                                                                    }
                                                                </script>
                                                                <input type="checkbox" id="ocbPdtStaLot" name="ocbPdtStaLot">
                                                                <span><?=language('product/product/product', 'tPDTStaLots') ?></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-2 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                                                            <!-- อนุฌาต จอง-->
                                                            <label class="fancy-checkbox">
                                                                <script>
                                                                    var tStaAlwBook = "<?php echo $tStaAlwBook; ?>";
                                                                    if (tStaAlwBook == '1') {
                                                                        $('#ocbPdtStaAlwBook').prop("checked", true);
                                                                    } else {
                                                                        $('#ocbPdtStaAlwBook').prop("checked", false);
                                                                    }
                                                                </script>
                                                                <input type="checkbox" id="ocbPdtStaAlwBook" name="ocbPdtStaAlwBook">
                                                                <span><?php echo language('product/product/product', 'tPDTStaAlwBook') ?></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-2 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                                                            <!-- อนุญาตคืน -->
                                                            <label class="fancy-checkbox">
                                                                <script>
                                                                    var tStaAlwReturn = "<?php echo $tStaAlwReturn; ?>";
                                                                    if (typeof(tStaAlwReturn) !== 'undefined' && tStaAlwReturn == '1' || tStaNewPdt == '99') {
                                                                        $('#ocbPdtStaAlwReturn').prop("checked", true);
                                                                    } else {
                                                                        $('#ocbPdtStaAlwReturn').prop("checked", false);
                                                                    }
                                                                </script>
                                                                <input type="checkbox" id="ocbPdtStaAlwReturn" name="ocbPdtStaAlwReturn">
                                                                <span><?php echo language('product/product/product', 'tPDTAlwReturn') ?></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-2 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                                                            <!-- ลดราคา -->
                                                            <label class="fancy-checkbox">
                                                                <script>
                                                                    var tStaAlwDis = "<?php echo $tStaAlwDis; ?>";
                                                                    if (typeof(tStaAlwDis) !== 'undefined' && tStaAlwDis == '1' || tStaNewPdt == '99') {
                                                                        $('#ocbPdtStaAlwDis').prop("checked", true);
                                                                    } else {
                                                                        $('#ocbPdtStaAlwDis').prop("checked", false);
                                                                    }
                                                                </script>
                                                                <input type="checkbox" id="ocbPdtStaAlwDis" name="ocbPdtStaAlwDis">
                                                                <span><?php echo language('product/product/product', 'tPDTStaAlwDis') ?></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-2 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                                                            <!-- เคลื่อนไหว -->
                                                            <label class="fancy-checkbox">
                                                                <script>
                                                                    var tStaActive = "<?php echo $tStaActive; ?>";
                                                                    if (typeof(tStaActive) !== 'undefined' && tStaActive == '1' || tStaNewPdt == '99') {
                                                                        $('#ocbPdtStaActive').prop("checked", true);
                                                                    } else {
                                                                        $('#ocbPdtStaActive').prop("checked", false);
                                                                    }
                                                                </script>
                                                                <input type="checkbox" id="ocbPdtStaActive" name="ocbPdtStaActive">
                                                                <span><?php echo language('product/product/product', 'tPDTStaActive') ?></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-4 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                                                            <!-- อนุฌาต หักภาษี ณ. ที่จ่าย-->
                                                            <label class="fancy-checkbox">
                                                                <script>
                                                                    var tStaAlwWHTax = "<?php echo $tStaAlwWHTax; ?>";
                                                                    if (tStaAlwWHTax == '1') {
                                                                        $('#ocbPdtStaAlwWHTax').prop("checked", true);
                                                                    } else {
                                                                        $('#ocbPdtStaAlwWHTax').prop("checked", false);
                                                                    }
                                                                </script>
                                                                <input type="checkbox" id="ocbPdtStaAlwWHTax" name="ocbPdtStaAlwWHTax">
                                                                <span><?php echo language('product/product/product', 'tPDTStaAlwWHTax') ?></span>
                                                            </label>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- แถบล่าง-->
                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                            <ul class="nav" role="tablist">
                                <li id="oliPdtContentProductUnit" class="xWMenu active" data-menutype="MN">
                                    <a role="tab" data-toggle="tab" data-target="#odvPdtContentNormal" aria-expanded="true"><?php echo language('product/product/product', 'tPDTTabNormal'); ?></a>
                                </li>
                                <li id="oliPdtPdtContentMore" class="xWMenu " data-menutype="MN">
                                    <a role="tab" data-toggle="tab" data-target="#odvPdtContentMore" aria-expanded="true"><?php echo language('product/product/product', 'tPDTTabOther'); ?></a>
                                </li>
                                <li id="oliPdtContentCost" class="xWMenu " data-menutype="MN">
                                    <a role="tab" data-toggle="tab" data-target="#odvPdtContentCost" aria-expanded="true"><?php echo language('product/product/product', 'tPDTCost'); ?></a>
                                </li>
                                <li id="oliPdtContentSetUpStock" class="xWMenu <?php echo $tMenuTabDisable; ?>" data-menutype="MN">
                                    <a role="tab" data-toggle="<?php echo $tMenuTabToggle; ?>" data-target="#odvPdtContentSetUpStock" aria-expanded="true"><?php echo language('product/product/product', 'tPdtSetUpStock'); ?></a>
                                </li>
                                <li id="oliPdtContentPurchaseAdmissionHistory" class="xWMenu <?php echo $tMenuTabDisable; ?>" data-menutype="MN">
                                    <a role="tab" data-toggle="<?php echo $tMenuTabToggle; ?>" data-target="#odvPdtContentPurchaseAdmissionHistory" aria-expanded="true"><?php echo language('product/product/product', 'tPDTTabHisPI'); ?></a>
                                </li>
                                <li id="oliPdtContentControlLot" class="xWMenu <?php echo $tMenuTabDisable; ?>" data-menutype="MN">
                                    <a role="tab" data-toggle="<?php echo $tMenuTabToggle; ?>" data-target="#odvPdtContentControlLot" aria-expanded="true"><?php echo language('product/product/product', 'tPDTTabControlLot'); ?></a>
                                </li>
                            </ul>
                        </div>
                        <!-- end แถบล่าง -->

                        <!-- content ล่าง-->
                        <div class="tab-content">
                            <div id="odvPdtContentProductUnit" class="tab-pane fade">
                                <!-- หน่วยสินค้า -->
                                <div class="row">
                                    <div id="odvPdtSetPackSizeAdd" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-b-10 text-right" style="margin-top:-10px;">
                                        <button id="obtAddProductUnit" class="xCNBTNPrimeryPlus" type="button">+</button>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div id="odvPdtSetPackSizeTable" class="table-responsive"></div>
                                    </div>
                                </div>
                                <!-- End หน่วยสินค้า -->
                            </div>

                            <div id="odvPdtContentNormal" class="tab-pane fade active in" style="padding-left : 0px;">
                            <!-- แถบสำหรับทั่วไป-->
                            <div class="custom-tabs-line-sub tabs-line-bottom left-aligned">
                                <ul class="nav" role="tablist">
                                    <li id="oliPdtContentNormalProductUnit" class="xWMenu xWMenuTapNormal active" data-menutype="TMN">
                                        <a role="tab" data-toggle="tab" data-target="#odvPdtTabContentNormal" aria-expanded="true"><?php echo language('product/product/product', 'tPDTTabPackSizeUnit'); ?></a>
                                    </li>
                                    <li id="oliPdtPdtContentNormalBarCode" class="xWMenu xWMenuTapNormal " data-menutype="TBC">
                                        <a role="tab" data-toggle="false" data-target="#odvPdtTabContentBarcode" aria-expanded="true"><?php echo language('product/product/product', 'tPDTViewPackBarcode'); ?></a>
                                    </li>
                                    <li id="oliPdtContentNormalVendor" class="xWMenu xWMenuTapNormal " data-menutype="TVD">
                                        <a role="tab" data-toggle="false" data-target="#odvPdtTabContentVendor" aria-expanded="true"><?php echo language('product/product/product', 'tPDTTabVendorDetail'); ?></a>
                                    </li>
                                    <li id="oliPdtContentSetUpStock" class="xWMenu xWMenuTapNormal  <?php echo $tClassHiddenPrice ?>" data-menutype="TSP">
                                        <a role="tab" data-toggle="<?php echo $tMenuTabToggle; ?>" data-target="#odvPdtTabContentSumPrice" aria-expanded="true"><?php echo language('product/product/product', 'tPDTTabSumPrice'); ?></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div id="odvPdtTabContentNormal" class="tab-pane fade active in">
                                    <!-- หน่วยสินค้า -->
                                    <div class="row">
                                        <div id="odvPdtNormalAdd" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-b-10 text-right" style="margin-top:-10px;">
                                            <button id="obtAddProductNormal" class="xCNBTNPrimeryPlus" type="button" onclick="JSxPdtCallModalAddEditUnitPack()">+</button>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px;">
                                            <div id="odvPdtNormalTable" class="table-responsive"></div>
                                        </div>
                                    </div>
                                    <!-- End หน่วยสินค้า -->
                                </div>
                                <div id="odvPdtTabContentBarcode" class="tab-pane fade">
                                    <!-- บาร์โค้ด -->
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px;">
                                            <!-- <div id="odvPdtNormalTable2" class="table-responsive"></div> -->
                                            <div id="odvPdtNormalTable2"></div>
                                        </div>
                                    </div>
                                    <!-- End บาร์โค้ด -->
                                </div>
                                <div id="odvPdtTabContentVendor" class="tab-pane fade">
                                    
                                    <!-- ข้อมูลผู้จำหน่าย -->
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px;">
                                            <div id="odvPdtNormalTable3" class="table-responsive"></div>
                                        </div>
                                    </div>
                                    <!-- End ข้อมูลผู้จำหน่าย -->
                                </div>
                                <div id="odvPdtTabContentSumPrice" class="tab-pane fade">
                                    <!-- ราคาขาย -->
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                                                    <div class="row">
                                                        <!-- Browse สาขา -->
                                                        <div class="col-xs-12 col-md-3 col-lg-3">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                                                                <label
                                                                    class="xCNLabelFrm"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tFromDocDate'); ?></label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input class="form-control input100 xCNDatePicker2" type="text" id="oetPDTPCPSearchDocDateFrom"
                                                                        name="oetPDTPCPSearchDocDateFrom"
                                                                        placeholder="<?php echo language('product/pdtcheckprice/pdtcheckprice', 'tFromDocDate'); ?>">
                                                                    <span class="input-group-btn">
                                                                        <button id="obtPDTPCPSearchDocDateFrom" type="button" class="btn xCNBtnDateTime">
                                                                            <img
                                                                                src="<?php echo base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Browse ร้านค้า -->
                                                        <div class="col-xs-12 col-md-3 col-lg-3">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                                                                <label
                                                                    class="xCNLabelFrm"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tToDocDate'); ?></label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input class="form-control input100 xCNDatePicker2" type="text" id="oetPDTPCPSearchDocDateTo"
                                                                        name="oetPDTPCPSearchDocDateTo"
                                                                        placeholder="<?php echo language('product/pdtcheckprice/pdtcheckprice', 'tToDocDate'); ?>">
                                                                    <span class="input-group-btn">
                                                                        <button id="obtPDTPCPSearchDocDateTo" type="button" class="btn xCNBtnDateTime">
                                                                            <img
                                                                                src="<?php echo base_url(); ?>application/modules/common/assets/images/icons/icons8-Calendar-100.png">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Browse คลังสินค้า -->
                                                        <div class="col-xs-12 col-md-3 col-lg-3">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                                                                <label class="xCNLabelFrm"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPplFrom'); ?></label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input class="form-control xCNHide" id="oetPCPPplCodeFrom" name="oetPCPPplCodeFrom" maxlength="5">
                                                                    <input class="form-control xWPointerEventNone" type="text" id="oetPCPPplNameFrom"name="oetPCPPplNameFrom" readonly
                                                                        placeholder="<?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPplFrom'); ?>"
                                                                    >
                                                                    <span class="input-group-btn">
                                                                        <button id="obtPCPBrowsePplFrom" type="button" class="btn xCNBtnBrowseAddOn">
                                                                            <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Browse สินค้า -->
                                                        <div class="col-xs-12 col-md-3 col-lg-3">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                                                                <label class="xCNLabelFrm"><?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPplTo'); ?></label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input class="form-control xCNHide" id="oetPCPPplCodeTo" name="oetPCPPplCodeTo" maxlength="5">
                                                                    <input class="form-control xWPointerEventNone" type="text" id="oetPCPPplNameTo" name="oetPCPPplNameTo" readonly
                                                                        placeholder="<?php echo language('product/pdtcheckprice/pdtcheckprice', 'tPCPPplTo'); ?>"
                                                                    >
                                                                    <span class="input-group-btn">
                                                                        <button id="obtPCPBrowsePplTo" type="button" class="btn xCNBtnBrowseAddOn">
                                                                            <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                                    <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 no-padding">
                                                                <label></label>
                                                            </div>
                                                        <!-- ปุ่มกรองข้อมูล -->
                                                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 text-right">
                                                        <a id="oahPCPAdvanceSearchSubmit" class="btn xCNBTNPrimery pull-right"
                                                                href="javascript:;"
                                                                onclick="JSxPDTGetPrictPdtListTable($('#oetPdtCode').val())"><?php echo language('common/main/main', 'tSearch'); ?></a>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px;">
                                            <div id="odvPdtNormalTable4" class="table-responsive"></div>
                                        </div>
                                    </div>
                                    <!-- End ราคาขาย -->
                                </div>
                            </div>
                            </div>

                            <div id="odvPdtContentMore" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">

                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                <!-- เงื่อนไขสินค้าใช้เฉพาะ -->
                                                <div class="panel panel-default" style="margin-bottom: 25px;">
                                                    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                                                        <label class="xCNTextDetail1"><?php echo language('product/product/product', 'tPdtSpecificProductConditions'); ?></label>
                                                    </div>
                                                    <div id="odvDataPromotion" class="panel-collapse collapse in" role="tabpanel">
                                                        <div class="panel-body xCNPDModlue">

                                                            <!-- Product Control Branch -->
                                                            <div class="form-group <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
                                                                                    endif; ?>">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPdtAgency') ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" id="oetPdtAgnCode" class="form-control xCNHide" name="oetPdtAgnCode" value="<?php echo @$tAgnCode; ?>">
                                                                    <input type="text" id="oetPdtAgnName" class="form-control" name="oetPdtAgnName" value="<?php echo @$tAgnName; ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <?php
                                                                        // Last Update : 21/05/2020 nale  ถ้าเข้ามาเป็น User ระดับ HQ ให้เลือก Agency ได้
                                                                        if ($this->session->userdata('nSesUsrBchCount') > 0) {
                                                                            $tDisableBrowseAgency = 'disabled';
                                                                        } else {
                                                                            $tDisableBrowseAgency = '';
                                                                        }

                                                                        ?>
                                                                        <button id="obtBrowseAgency" type="button" class="btn xCNBtnBrowseAddOn" <?php echo @$tDisableBrowseAgency; ?>>
                                                                            <img class="xCNIconFind">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!-- End Product Control Branch -->

                                                            <!-- Product Control Branch -->
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTBranch') ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" id="oetPdtBchCode" class="form-control xCNHide" name="oetPdtBchCode" value="<?php echo @$tBchCode; ?>">
                                                                    <input type="text" id="oetPdtBchName" class="form-control" name="oetPdtBchName" value="<?php echo @$tBchName; ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <?php
                                                                        // Last Update : 21/05/2020 nale  ถ้าเข้ามาเป็น User ระดับ Branch และ อยู่แค่ 1 สาขา
                                                                        if ($this->session->userdata('tSesUsrLevel') == 'SHP' && FCNbUsrIsMerLevel() == false) {
                                                                            $tDisableBrowseBranch = 'disabled';
                                                                        } else {
                                                                            $tDisableBrowseBranch = '';
                                                                        }
                                                                        ?>
                                                                        <button id="obtBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn" <?= $tDisableBrowseBranch ?>>
                                                                            <img class="xCNIconFind">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!-- End Product Control Branch -->

                                                            <!-- Product Merchant -->
                                                            <div class="form-group <?= !FCNbGetIsShpEnabled() ? 'xCNHide' : '' ?>">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTMerchant') ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" id="oetPdtMerCode" class="form-control xCNHide" name="oetPdtMerCode" value="<?php echo @$tPdtMerCode; ?>">
                                                                    <input type="text" id="oetPdtMerName" class="form-control" name="oetPdtMerName" value="<?php echo @$tPdtMerName; ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <?php
                                                                        $tDisableBrowseMechant  = '';
                                                                        if ($tRoute == 'productEventAdd') {
                                                                            // เข้ามาในกรณีก็ต่อเมือ Session User Level เป็นระดับร้านค้า และ Session User Merchant Code ต้องไม่เท่ากับค่าว่าง
                                                                            if ($this->session->userdata("tSesUsrLevel") == "SHP" || $this->session->userdata("tSesUsrLevel") == "BCH") {
                                                                                $tCheckMerCode    = $this->session->userdata('tSesUsrMerCode');
                                                                                if (isset($tCheckMerCode) && !empty($tCheckMerCode)) {
                                                                                    $tDisableBrowseMechant  = ' disabled';
                                                                                }
                                                                            }
                                                                        } else {
                                                                            // เข้ามาในกรณีก็ต่อเมือ Session User Level ระดับร้านค้า และ Session User Merchant Code และ ข้อมูลที่มาจาก DataBase ต้องไม่เท่ากับค่าว่าง
                                                                            if ($this->session->userdata("tSesUsrLevel") == "SHP" || $this->session->userdata("tSesUsrLevel") == "BCH") {
                                                                                $tCheckSessionMerCode   = $this->session->userdata('tSesUsrMerCode');
                                                                                $tCheckUserMerCode      = $tPdtMerCode;
                                                                                if ((isset($tCheckSessionMerCode) && !empty($tCheckSessionMerCode)) && (isset($tCheckUserMerCode) && !empty($tCheckUserMerCode))) {
                                                                                    $tDisableBrowseMechant  = ' disabled';
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <button id="obtBrowseMerchant" type="button" class="btn xCNBtnBrowseAddOn" <?php echo @$tDisableBrowseMechant; ?>>
                                                                            <img class="xCNIconFind">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!-- End Product Merchant -->

                                                            <!-- Product Shop -->
                                                            <div class="form-group <?= !FCNbGetIsShpEnabled() ? 'xCNHide' : '' ?>">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tRETPDTSHP') ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" id="oetPdtInfoShpCode" class="form-control xCNHide" name="oetPdtInfoShpCode" value="<?php echo @$tShpCode ?>">
                                                                    <input type="text" id="oetPdtInfoShpName" class="form-control" name="oetPdtInfoShpName" value="<?php echo @$tShpName ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <?php
                                                                        // เช็คปิดปลุ่ม Browse Shop ในกรณีที่เข้ามาแบบ Add Page จะเช็คข้อมูลจาก Session Shop Code ว่ามีค่าเท่านั้นจึงจะปุ่ม แต่ถ้าเข้ามาแบบ Edit จะเช็คข้อมูลจาก Shop Type และ Session
                                                                        // Last Update : 08/10/2019 Wasin(Yoshi)
                                                                        // $tDisableBrowseShop = '';
                                                                        // if($tRoute == 'productEventAdd'){
                                                                        //     // Call In Event Add
                                                                        //     if($this->session->userdata("nSesUsrShpCount") == 1){
                                                                        //         $tCheckShpCode  = $this->session->userdata('tSesUsrShpCodeDefault');
                                                                        //         if(isset($tCheckShpCode) && !empty($tCheckShpCode)){
                                                                        //             $tDisableBrowseShop = ' disabled';
                                                                        //         }
                                                                        //     }
                                                                        // }else{
                                                                        //     // Call In Event Edit
                                                                        //     if($this->session->userdata("nSesUsrShpCount") == 1){
                                                                        //         $tCheckSessionShpCode   = $this->session->userdata('tSesUsrShpCodeDefault');
                                                                        //         $tCheckUserShpCode      = $tShpCode;
                                                                        //         if((isset($tCheckSessionShpCode) && !empty($tCheckSessionShpCode)) && (isset($tCheckUserShpCode) && !empty($tCheckUserShpCode))){
                                                                        //             $tDisableBrowseShop  = ' disabled';
                                                                        //         }
                                                                        //     }else{
                                                                        //         if($tPdtForSystem == 4 && $tPdtRentType == 2){
                                                                        //             $tDisableBrowseShop = ' disabled';
                                                                        //         }
                                                                        //     }
                                                                        // }
                                                                        // nattakit nale 21/05/2020
                                                                        if ($this->session->userdata("nSesUsrShpCount") == 1) {
                                                                            $tDisableBrowseShop = 'disabled';
                                                                        } else {
                                                                            $tDisableBrowseShop = '';
                                                                        }
                                                                        ?>
                                                                        <button id="obtBrowsePdtInfoShp" type="button" class="btn xCNBtnBrowseAddOn" <?php echo @$tDisableBrowseShop; ?>>
                                                                            <img class="xCNIconFind">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!-- End Product Shop -->

                                                            <!-- Product Merchant -->
                                                            <div class="form-group  <?= !FCNbGetIsShpEnabled() ? 'xCNHide' : '' ?>">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tRETPDTMGP') ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" id="oetPdtInfoMgpCode" class="form-control xCNHide" name="oetPdtInfoMgpCode" value="<?php echo $tMgpCode ?>">
                                                                    <input type="text" id="oetPdtInfoMgpName" class="form-control" name="oetPdtInfoMgpName" value="<?php echo $tMgpName ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <?php
                                                                        $tDisableBrowseMgp  = '';
                                                                        if ($tRoute == 'productEventAdd') {
                                                                            if ($this->session->userdata("tSesUsrLevel") == "BCH" || $this->session->userdata("tSesUsrLevel") == "SHP") {
                                                                                $tCheckMerCode  = $this->session->userdata('tSesUsrMerCode');
                                                                                if (isset($tCheckMerCode) && empty($tCheckMerCode)) {
                                                                                    $tDisableBrowseMgp  = ' disabled';
                                                                                }
                                                                            }
                                                                        } else {
                                                                            if ($tPdtMerCode == '') {
                                                                                $tDisableBrowseMgp  = ' disabled';
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <button id="obtBrowsePdtInfoMgp" type="button" class="btn xCNBtnBrowseAddOn" <?php echo @$tDisableBrowseMgp; ?>>
                                                                            <img class="xCNIconFind">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!-- End Product Merchant -->

                                                            <!-- เงื่อนไขควบคุมการจ่ายโดย -->
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPdtConditionsControl') ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control xCNHide" id="oetConditionControlCode" name="oetConditionControlCode" value="<?php echo @$tConditionCode ?>">
                                                                    <input type="text" class="form-control xWPointerEventNone" id="oetConditionControlName" name="oetConditionControlName" placeholder="" value="<?php echo @$tConditionName ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <button id="oimBrowseConControl" type="button" class="btn xCNBtnBrowseAddOn">
                                                                            <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End เงื่อนไขสินค้าใช้เฉพาะ -->
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                <!-- ข้อมูลเพิ่มเติมเกี่ยวกับสินค้า -->
                                                <div class="panel panel-default" style="margin-bottom: 25px;">
                                                    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                                                        <label class="xCNTextDetail1"><?php echo language('product/product/product', 'tPdtAboutProduct'); ?></label>
                                                    </div>
                                                    <div id="odvDataPromotion" class="panel-collapse collapse in" role="tabpanel">
                                                        <div class="panel-body xCNPDModlue">

                                                            <!-- Product Group -->
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTGroup') ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" id="oetPdtPgpChain" class="form-control xCNHide" name="oetPdtPgpChain" value="<?php echo $tPgpChain; ?>">
                                                                    <input type="text" id="oetPdtPgpChainName" class="form-control" name="oetPdtPgpChainName" value="<?php echo $tPgpChainName; ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <button id="obtBrowsePdtGrp" type="button" class="btn xCNBtnBrowseAddOn">
                                                                            <img class="xCNIconFind">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!-- End Product Group -->

                                                            <!-- Product Type -->
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTType') ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" id="oetPdtPtyCode" class="form-control xCNHide" name="oetPdtPtyCode" value="<?php echo $tPtyCode; ?>">
                                                                    <input type="text" id="oetPdtPtyName" class="form-control" name="oetPdtPtyName" value="<?php echo $tPtyName; ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <button id="obtBrowsePdtType" type="button" class="btn xCNBtnBrowseAddOn">
                                                                            <img class="xCNIconFind">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!-- End Product Type -->

                                                            <!-- Product Brand -->
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTBrand') ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" id="oetPdtPbnCode" class="form-control xCNHide" name="oetPdtPbnCode" value="<?php echo $tPbnCode; ?>">
                                                                    <input type="text" id="oetPdtPbnName" class="form-control" name="oetPdtPbnName" value="<?php echo $tPbnName; ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <button id="obtBrowsePdtBrand" type="button" class="btn xCNBtnBrowseAddOn">
                                                                            <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!-- End Product Brand -->

                                                            <!-- รุ่น -->
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTModal'); ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" id="oetPdtPmoCode" class="form-control xCNHide" name="oetPdtPmoCode" value="<?php echo $tPmoCode; ?>">
                                                                    <input type="text" id="oetPdtPmoName" class="form-control" name="oetPdtPmoName" value="<?php echo $tPmoName; ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <button id="obtBrowsePdtModel" type="button" class="btn xCNBtnBrowseAddOn">
                                                                            <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!--End รุ่น -->


                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ข้อมูลเพิ่มเติมเกี่ยวกับสินค้า -->
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                <!-- กำหนดสินค้าด่วน -->
                                                <div class="panel panel-default" style="margin-bottom: 25px;">
                                                    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                                                        <label class="xCNTextDetail1"><?php echo language('product/product/product', 'tPdtDefineExpressPdt'); ?></label>
                                                    </div>
                                                    <div class="panel-collapse collapse in" role="tabpanel">
                                                        <div class="panel-body xCNPDModlue">
                                                            <!-- กลุ่มสินค้าด่วน -->
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPdtExpressGroup'); ?></label>
                                                                <div class="input-group">
                                                                    <input type="text" id="oetPdtTcgCode" name="oetPdtTcgCode" class="form-control xCNHide" value="<?php echo $tTcgCode; ?>">
                                                                    <input type="text" id="oetPdtTcgName" name="oetPdtTcgName" class="form-control" value="<?php echo $tTcgName; ?>" readonly>
                                                                    <span class="input-group-btn">
                                                                        <button id="obtBrowsePdtTouchGrp" type="button" class="btn xCNBtnBrowseAddOn">
                                                                            <img class="xCNIconFind">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <!-- End กลุ่มสินค้าด่วน -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End กำหนดสินค้าด่วน -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End เพิ่มเติม -->

                            <!-- ต้นทุน -->
                            <div id="odvPdtContentCost" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?php 
                                            $aChkInvStaRead = FCNaHCheckAlwFunc('docInvoice/0/0');
                                            $tInputType     = "";
                                            if($aChkInvStaRead['tAutStaRead'] == '1'){
                                                $tInputType = "text";
                                            }else{
                                                $tInputType = "password";
                                            }
                                        ?>
                                        <!-- เงื่อนไขต้นทุน -->
                                        <div class="panel panel-default" style="margin-bottom: 25px;">
                                            <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                                                <label class="xCNTextDetail1"><?php echo language('product/product/product', 'tPdtCostConditions'); ?></label>
                                            </div>
                                            <div class="panel-collapse collapse in" role="tabpanel">
                                                <div class="panel-body xCNPDModlue">
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTCostDef'); ?></label>
                                                        <input 
                                                            type="<?=@$tInputType;?>"
                                                            class="form-control text-right xCNInputMaskCurrency"
                                                            id="oetPdtCostDef"
                                                            name="oetPdtCostDef"
                                                            maxlength="18"
                                                            placeholder="0.00"
                                                            value="<?php echo number_format($aPdtCostDef[0]['FCXpdSetPrice'], $nDecShow); ?>"
                                                            readonly
                                                        >
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTCostStd'); ?></label>
                                                        <?php 
                                                        if ($tPdtCostStd == null) {
                                                            $tPdtCostStd = 0;
                                                        } else {
                                                            $tPdtCostStd = $tPdtCostStd;
                                                        } ?>
                                                        <input 
                                                            type="<?=@$tInputType;?>"
                                                            class="form-control text-right xCNInputMaskCurrency"
                                                            id="oetPdtCostStd"
                                                            name="oetPdtCostStd"
                                                            maxlength="18"
                                                            placeholder="0.00"
                                                            data-validate="<?php echo language('product/product/product', 'tPDTValidPdtCostStd'); ?>"
                                                            value="<?php echo number_format($tPdtCostStd, $nDecShow); ?>"
                                                            readonly
                                                        >
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTCostEx'); ?></label>
                                                        <?php if ($aPDTCostExIn[0]['FCPdtCostEx'] == null) {
                                                            $nCostEx = 0;
                                                        } else {
                                                            $nCostEx = $aPDTCostExIn[0]['FCPdtCostEx'];
                                                        } ?>
                                                        <input
                                                            type="<?=@$tInputType;?>"
                                                            class="form-control text-right xCNInputMaskCurrency"
                                                            id="oetPDTCostEx"
                                                            name="oetPDTCostEx"
                                                            maxlength="18"
                                                            placeholder="0.00"
                                                            value="<?php echo number_format($nCostEx, $nDecShow); ?>"
                                                            readonly
                                                        >
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTCostIn'); ?></label>
                                                        <input
                                                            type="<?=@$tInputType;?>"
                                                            class="form-control text-right xCNInputMaskCurrency"
                                                            id="oetPDTCostIn"
                                                            name="oetPDTCostIn"
                                                            maxlength="18"
                                                            placeholder="0.00"
                                                            value="<?php echo number_format($aPDTCostExIn[0]['FCPdtCostIn'], $nDecShow); ?>"
                                                            readonly
                                                        >
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTRmk'); ?></label>
                                                        <textarea class="form-control" maxlength="200" rows="4" id="otaPdtRmk" name="otaPdtRmk"><?php echo $tPdtRmk; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End เงื่อนไขต้นทุน -->
                                    </div>
                                </div>
                            </div>
                            <!-- End ต้นทุน -->

                            <div id="odvPdtContentSetUpStock" class="tab-pane fade">
                                <!-- ตั้งค่าสต็อค -->
                                <div class="table-responsive xCNTableScrollY">
                                    <div id="odvStockConditions"></div>
                                </div>
                                <!-- End ตั้งค่าสต็อค -->
                            </div>
                            <div id="odvPdtContentPurchaseAdmissionHistory" class="tab-pane fade">
                                <!-- ประวัติการซื้อ/รับเข้า -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="table-responsive">
                                            <table id="otbPdtDataHisPI" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th nowrap class="text-center xCNTextBold" style="width:20%;"><?php echo language('product/product/product', 'tPDTHisPIDocNo'); ?></th>
                                                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTHisPIDocDate'); ?></th>
                                                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTHisPISupplier'); ?></th>
                                                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTHisPIRef'); ?></th>
                                                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTHisPIUnit'); ?></th>
                                                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTHisPIQty'); ?></th>
                                                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTHisPIQtyAll'); ?></th>
                                                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTHisPISetPrice'); ?></th>
                                                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTHisPIDis'); ?></th>
                                                        <th nowrap class="text-center xCNTextBold"><?php echo language('product/product/product', 'tPDTHisPIChg'); ?></th>
                                                        <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPDTHisPINet'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($aPdtHisPI) && !empty($aPdtHisPI)) : ?>
                                                        <?php foreach ($aPdtHisPI as $nKey => $aValue) : ?>
                                                            <tr nowrap>
                                                                <td class="text-center"><?php echo $aValue['FTXphDocNo']; ?></td>
                                                                <td class="text-center"><?php echo $aValue['FDXphDocDate']; ?></td>
                                                                <td class="text-left"><?php echo $aValue['FTSplName']; ?></td>
                                                                <td class="text-left"><?php echo $aValue['FTXphRefExt']; ?></td>
                                                                <td class="text-left"><?php echo $aValue['FTPunName']; ?></td>
                                                                <td class="text-center"><?php echo number_format($aValue['FCXpdQty'], 0); ?></td>
                                                                <td class="text-center"><?php echo number_format($aValue['FCXpdQtyAll'], 0); ?></td>
                                                                <td class="text-right"><?php echo number_format($aValue['FCXpdSetPrice'], $nDecShow); ?></td>
                                                                <td class="text-right"><?php echo number_format($aValue['FCXpdDis'], $nDecShow); ?></td>
                                                                <td class="text-right"><?php echo number_format($aValue['FCXpdChg'], $nDecShow); ?></td>
                                                                <td class="text-right"><?php echo number_format($aValue['FCXpdNet'], $nDecShow); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td class='text-center xCNTextDetail2' colspan='100'><?php echo language('common/main/main', 'tCMNNotFoundData'); ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- End ประวัติการซื้อ/รับเข้า -->
                            </div>

                            <!-- สินค้าประเภทควบคุมล็อต -->
                            <div id="odvPdtContentControlLot" class="tab-pane fade"></div>
                            <!-- End สินค้าประเภทควบคุมล็อต -->

                        </div>
                        <!-- end content ล่าง -->
                    </div>

                    <!-- Tab Content Product Info 2 -->
                    <div id="odvPdtContentInfo2" class="tab-pane fade">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <!-- Product Product Qty Buy -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTQtyOrdBuy'); ?></label>
                                    <input type="text" id="oetPdtQtyOrdBuy" class="form-control text-right xCNInputNumericWithoutDecimal" name="oetPdtQtyOrdBuy" maxlength="18" placeholder="0" value="<?php echo number_format($tPdtQtyOrdBuy, $nDecShow); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <!-- Product Product Max -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTMax'); ?></label>
                                    <input type="text" id="oetPdtMax" class="form-control text-right xCNInputNumericWithoutDecimal" name="oetPdtMax" maxlength="18" placeholder="0" value="<?php echo number_format($tPdtMax, $nDecShow); ?>">
                                </div>
                                <!-- Product Product Min -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTMin'); ?></label>
                                    <input type="text" id="oetPdtMin" class="form-control text-right xCNInputNumericWithoutDecimal" name="oetPdtMin" maxlength="18" placeholder="0" value="<?php echo number_format($tPdtMin, $nDecShow); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content Drug -->
                    <div id="odvPdtContentDrug" class="tab-pane fade"></div>

                    <!-- Tab Content Fashion -->
                    <div id="odvPdtContentFashion" class="tab-pane fade"></div>

                    <!-- Tab Content Product Rental -->
                    <div id="odvPdtContentRental" class="tab-pane fade">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tRETPDTType'); ?></label>
                                    <select class="selectpicker form-control" id="ocmRetPdtType" name="ocmRetPdtType" maxlength="1" onchange="JSxPdtRentSelectType(this.value)">
                                        <option value=""><?php echo language('product/product/product', 'tRETPDTType') ?></option>
                                        <option value="1" <?php echo $tPdtRentType == "1" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tRETPDTType1') ?></option>
                                        <option value="2" <?php echo $tPdtRentType == "2" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tRETPDTType2') ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tRENPDTSta'); ?></label>
                                    <select class="selectpicker form-control" id="ocmRetPdtSta" name="ocmRetPdtSta" maxlength="1">
                                        <option value=""><?php echo language('product/product/product', 'tRENPDTSta') ?></option>
                                        <option value="1" <?php echo $tPdtStaReqRet == "1" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tRENPDTSta1') ?></option>
                                        <option value="2" <?php echo $tPdtStaReqRet == "2" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tRENPDTSta2') ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tRENPDTStaPay'); ?></label>
                                    <select class="selectpicker form-control" id="ocmRetPdtStaPay" name="ocmRetPdtStaPay" maxlength="1">
                                        <option value=""><?php echo language('product/product/product', 'tRENPDTStaPay') ?></option>
                                        <option value="1" <?php echo $tPdtStaPay == "1" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tRENPDTStaPay1') ?></option>
                                        <option value="2" <?php echo $tPdtStaPay == "2" ? "selected" : ""; ?>><?php echo language('product/product/product', 'tRENPDTStaPay2') ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tRENPDTDeposit'); ?></label>
                                    <input type="text" id="oetRetPdtDeposit" class="form-control text-right xCNInputNumericWithoutDecimal" name="oetRetPdtDeposit" maxlength="18" placeholder="0" value="<?php echo $tPdtDeposit ?>">
                                </div>

                                <div class="xWPdtRetBrwShp">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tRETPDTSHP') ?></label>
                                        <div class="input-group">
                                            <input type="text" id="oetModalShopCode" class="form-control xCNHide" name="oetModalShpCode" value="<?php echo $tPdtRntShpCode ?>">
                                            <input type="text" id="oetModalShopName" class="form-control xCNInputWithoutSpcNotThai" name="oetModalShpName" value="<?php echo $tPdtRntShpName ?>" readonly>
                                            <span class="input-group-btn">
                                                <button id="obtBrowsePdtRetShop" type="button" class="btn xCNBtnBrowseAddOn">
                                                    <img class="xCNIconFind">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content Product Add Set -->
                    <div id="odvPdtContentSet" class="tab-pane fade">
                        <input id="oetPdtSetPdtCodeDup" class="xCNHide" value="">
                        <div id="odvPdtSetMenuSelectPdt" class="row">
                            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9" style="margin-bottom:10px;">
                                <!-- <label id="olbPdtSetInfo" class="xCNLabelFrm xCNLinkClick"><?= language('product/product/product', 'tPDTTabSet') ?> : <?php echo $tPdtName; ?> </label> -->

                                <div id="odvPdtSetAndPdtName" class="row">
                                    <div class="col-lg-4" id="odvtTmpImgForPdtSetPage">
                                        <?php
                                        echo FCNtHGetImagePageListProductTab(@$tFirtImage);
                                        // if(!isset($tTmpImgForPdtSetPage)){
                                        //     $tPatchImg              = base_url().'application/modules/common/assets/images/Noimage.png';
                                        //     $tTmpImgForPdtSetPage   = '<img src="'.$tPatchImg.'" class="img img-respornsive" style="width: 100%">';
                                        //     echo $tTmpImgForPdtSetPage;
                                        // }else{
                                        //     echo $tTmpImgForPdtSetPage;
                                        // }
                                        ?>
                                    </div>
                                    <div class="col-lg-8">
                                        <label id="olbPdtSetAndPdtName" onclick="JSxPdtSetCallDataTable();" class="xCNLabelFrm xCNLinkClick" style="font-size: 22px !important;"><?= language('product/product/product', 'tPDTTabSet') ?> : <?php echo $tPdtName; ?> </label>
                                        <label id="olbPdtSetAdd" class="xCNLabelFrm xCNHide"> / <?= language('common/main/main', 'tAdd') ?></label>
                                        <label id="olbPdtSetEdit" class="xCNLabelFrm xCNHide"> / <?= language('common/main/main', 'tEdit') ?></label>

                                        <div id="odvPdtSetSubMenuSta" class="row">
                                            <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                                <label class="xCNLabelFrm" style="margin-right: 10px;"><?= language('product/product/product', 'tPdtStaSetPri') ?></label>
                                                <select id="ocmPdtStaSetPri" name="ocmPdtStaSetPri" class="selectpicker form-control  xWPdtStaSetPri">
                                                    <option value="1" <?php if ($tPdtStaSetPri == '1') {
                                                                            echo "selected";
                                                                        } ?>><?= language('product/product/product', 'tPdtStaSetPri1') ?></option>
                                                    <option value="2" <?php if ($tPdtStaSetPri == '2') {
                                                                            echo "selected";
                                                                        } ?>><?= language('product/product/product', 'tPdtStaSetPri2') ?></option>
                                                </select>
                                            </div>

                                            <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                                <label class="xCNLabelFrm" style="margin-right: 10px;"><?= language('product/product/product', 'tPdtStaSetShwDT') ?></label>
                                                <select id="ocmPdtStaSetShwDT" name="ocmPdtStaSetShwDT" class="selectpicker form-control xWPdtStaSetShwDT">
                                                    <option value="1" <?php if ($tPdtStaSetShwDT == '1') {
                                                                            echo "selected";
                                                                        } ?>><?= language('product/product/product', 'tPdtStaSetShwDT1') ?></option>
                                                    <option value="2" <?php if ($tPdtStaSetShwDT == '2') {
                                                                            echo "selected";
                                                                        } ?>><?= language('product/product/product', 'tPdtStaSetShwDT2') ?></option>
                                                </select>
                                            </div>

                                            <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                                <label class="xCNLabelFrm" style="margin-right: 10px;"><?= language('product/product/product', 'tPdtStaSetPrcStk') ?></label>
                                                <select id="ocmPdtStaSetPrcStk" name="ocmPdtStaSetPrcStk" class="selectpicker form-control xWPdtStaSetPrcStk">
                                                    <option value="1" <?php if ($tPdtStaSetPrcStk == '1') {
                                                                            echo "selected";
                                                                        } ?>><?= language('product/product/product', 'tPdtStaSetPrcStk1') ?></option>
                                                    <!-- <option value="2" <?php if ($tPdtStaSetPrcStk == '2') {
                                                                                echo "selected";
                                                                            } ?>><?= language('product/product/product', 'tPdtStaSetPrcStk2') ?></option> -->
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right" style="margin-bottom:10px;">
                                <button id="obtPdtSetAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                                <button id="obtPdtSetBack" class="btn xCNHide" type="button" style="background-color: #D4D4D4; color: #000000;"><?= language('common/main/main', 'tCancel') ?></button>
                                <button id="obtPdtSetSave" class="btn xCNHide xCNBTNSubSave" type="submit" style="color: white;"><?= language('common/main/main', 'tAdd') ?></button>
                            </div>
                        </div>

                        <div id="odvPdtSetTable" class="row" style="margin-top:10px">
                            <!-- DataTable Product Set -->
                            <div id="odvPdtSetDataTable" class="table-responsive"></div>
                            <!-- End DataTable Product Set -->
                            <?php 
                            if (isset($tTextPdtCodeSet) && isset($tTextPdtNameSet)) { ?>
                                <input type="hidden" id="ohdPdtSetCode" name="ohdPdtSetCode" value="<?php echo substr(@$tTextPdtCodeSet, 0, -1); ?>">
                                <input type="hidden" id="ohdPdtSetName" name="ohdPdtSetName" value="<?php echo substr(@$tTextPdtNameSet, 0, -1); ?>">
                            <?php }else{ ?>
                                <input type="hidden" id="ohdPdtSetCode" name="ohdPdtSetCode" value="">
                                <input type="hidden" id="ohdPdtSetName" name="ohdPdtSetName" value="">
                            <?php } ?>                                                    
                            <input type="hidden" id="ohdPdtSetStaEditInline" name="ohdPdtSetStaEditInline" value="0">
                        </div>

                    </div>

                    <!-- Tab Content Product Add SV Set -->
                    <div id="odvPdtContentSVSet" class="tab-pane fade">
                        <input id="oetPdtSetPdtCodeDup" class="xCNHide" value="">
                        <div id="odvPdtSetMenuSelectPdt" class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom:10px;">
                                <div id="odvPdtSetAndPdtName" class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" id="odvtTmpImgForPdtSVSetPage">
                                        <?php
                                            echo FCNtHGetImagePageListProductTab(@$tFirtImage);
                                        ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <label id="olbPdtSetSVAndPdtName" onclick="JSxPdtSetCallSVDataTable();" class="xCNLabelFrm xCNLinkClick" style="font-size: 22px !important;"><?= language('product/product/product', 'tPDTTitle') ?> : <?php echo $tPdtName; ?> </label>
                                                <label id="olbPdtSVSetAdd" class="xCNLabelFrm xCNHide"> / <?= language('common/main/main', 'tAdd') ?></label>
                                                <label id="olbPdtSVSetEdit" class="xCNLabelFrm xCNHide"> / <?= language('common/main/main', 'tEdit') ?></label>
                                            </div>
                                            <br>
                                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" id="odvSetShwDT">
                                                <div class="form-group">
                                                    <label class="xCNLabelFrm" style="margin-right: 10px;"><?= language('product/product/product', 'tPdtStaSetShwDT') ?></label>
                                                    <select id="ocmPdtStaSetShwDTSV" name="ocmPdtStaSetShwDTSV" class="selectpicker form-control xWPdtStaSetShwDTSV">
                                                        <option value="1" <?php if ($tPdtStaSetShwDT == '1') {
                                                            echo "selected";
                                                        } ?>><?= language('product/product/product', 'tPdtStaSetShwDT1') ?></option>
                                                        <option value="2" <?php if ($tPdtStaSetShwDT == '2') {
                                                            echo "selected";
                                                        } ?>><?= language('product/product/product', 'tPdtStaSetShwDT2') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="odvPdtSetSVSubMenuSta">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="custom-tabs-line-sub tabs-line-bottom left-aligned">
                                                        <ul class="nav" role="tablist">
                                                            <li id="oliPdtSetSVServiceRound" class="xWMenu xWMenuTapNormal active" data-menutype="SV1">
                                                                <a role="tab" data-toggle="tab" data-target="#odvPdtSetSVTabContent1" aria-expanded="true"><?=language('product/product/product', 'tPDTSVServiceRound');?></a>
                                                            </li>
                                                            <li id="oliPdtSetSVDurationCondition" class="xWMenu xWMenuTapNormal" data-menutype="SV2">
                                                                <a role="tab" data-toggle="tab" data-target="#odvPdtSetSVTabContent2" aria-expanded="false"><?=language('product/product/product', 'tPDTSVDurationCondition');?></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="tab-content">
                                                        <div id="odvPdtSetSVTabContent1" class="tab-pane fade active in">
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                    <div class="row">
                                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label class="xCNLabelFrm"> <?php echo language('product/product/product', 'tPDTSVDistance'); ?></label>
                                                                                <input 
                                                                                    type="text"
                                                                                    class="form-control text-right xCNInputNumericWithDecimal"
                                                                                    id="oetPdtSVDistance"
                                                                                    name="oetPdtSVDistance"
                                                                                    maxlength="100"
                                                                                    placeholder="<?php echo language('product/product/product', 'tPDTSVKilomate'); ?>"
                                                                                    data-validate-required="<?php echo language('product/product/product', 'tPDTValidPdtName'); ?>"
                                                                                    autocomplete="off"
                                                                                    value="<?php echo (@$aPdtCarMaDistance); ?>"
                                                                                >
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label class="xCNLabelFrm"> <?php echo language('product/product/product', 'tPDTSVDuration'); ?></label>
                                                                                <input
                                                                                    type="text"
                                                                                    class="form-control text-right xCNInputNumericWithDecimal"
                                                                                    maxlength="100"
                                                                                    id="oetPdtSVDuration"
                                                                                    name="oetPdtSVDuration"
                                                                                    placeholder="<?php echo language('product/product/product', 'tPDTSVMount'); ?>"
                                                                                    data-validate-required="<?php echo language('product/product/product', 'tPDTValidPdtName'); ?>"
                                                                                    autocomplete="off"
                                                                                    value="<?php echo (@$aPdtCarQtyMonth); ?>"
                                                                                >
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label class="xCNLabelFrm"> <?php echo language('product/product/product', 'tPDTSVEstService'); ?></label>
                                                                                <input
                                                                                    type="text"
                                                                                    class="form-control text-right xCNInputNumericWithDecimal"
                                                                                    maxlength="100"
                                                                                    id="oetPdtSVEst"
                                                                                    name="oetPdtSVEst"
                                                                                    placeholder="<?php echo language('product/product/product', 'tPDTSVHour'); ?>"
                                                                                    autocomplete="off"
                                                                                    data-validate-required="<?php echo language('product/product/product', 'tPDTValidPdtName'); ?>"
                                                                                    value="<?php echo (@$aPdtCarQtyTime); ?>"
                                                                                >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="odvPdtSetSVTabContent2" class="tab-pane fade">
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                    <div class="row">
                                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label class="xCNLabelFrm"> <?php echo language('product/product/product', 'tPDTSVDurationKilo'); ?></label>
                                                                                <input
                                                                                    type="text"
                                                                                    class="form-control text-right xCNInputNumericWithDecimal"
                                                                                    maxlength="100"
                                                                                    id="oetPdtSVDuratKilo"
                                                                                    name="oetPdtSVDuratKilo"
                                                                                    placeholder="<?php echo language('product/product/product', 'tPDTSVKilomate'); ?>"
                                                                                    autocomplete="off"
                                                                                    data-validate-required="<?php echo language('product/product/product', 'tPDTValidPdtName'); ?>"
                                                                                    value="<?php echo (@$aPdtCarWaDistance); ?>"
                                                                                >
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label class="xCNLabelFrm"> <?php echo language('product/product/product', 'tPDTSVDurationTime'); ?></label>
                                                                                <input
                                                                                    type="text"
                                                                                    class="form-control text-right xCNInputNumericWithDecimal"
                                                                                    maxlength="100"
                                                                                    id="oetPdtSVTime"
                                                                                    name="oetPdtSVTime"
                                                                                    placeholder="<?php echo language('product/product/product', 'tPDTSVDays'); ?>"
                                                                                    autocomplete="off"
                                                                                    data-validate-required="<?php echo language('product/product/product', 'tPDTValidPdtName'); ?>"
                                                                                    value="<?php echo (@$aPdtCarWaQtyDay); ?>"
                                                                                >
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label class="xCNLabelFrm"> <?php echo language('product/product/product', 'tPDTSVDurationCondition'); ?></label>
                                                                                <input
                                                                                    type="text"
                                                                                    class="form-control"
                                                                                    maxlength="100"
                                                                                    id="oetPdtSVCondit"
                                                                                    name="oetPdtSVCondit"
                                                                                    placeholder="<?php echo language('product/product/product', 'tPDTSVDurationCondition'); ?>"
                                                                                    autocomplete="off"
                                                                                    data-validate-required="<?php echo language('product/product/product', 'tPDTValidPdtName'); ?>"
                                                                                    value="<?php echo (@$aPdtCarWaCond); ?>"
                                                                                >
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
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right" style="margin-bottom:10px;">
                                <!-- <button id="obtPdtSVSetAdd" class="xCNBTNPrimeryPlus" type="button">+</button> -->
                                <button id="obtPdtSVSetBack" class="btn xCNHide" type="button" style="background-color: #D4D4D4; color: #000000;"><?= language('common/main/main', 'tCancel') ?></button>
                                <button id="obtPdtSVSetSave" class="btn xCNHide xCNBTNSubSave" type="submit"><?= language('common/main/main', 'tAdd') ?></button>
                            </div>
                        </div>
                        <div class="text-right xWAddMargin" style="margin-bottom:50px;">
                                <button id="obtPdtSVSetAdd" class="xCNBTNPrimeryPlus" type="button">+</button>
                        </div>

                        <div id="odvPdtSetTable" style="margin-top:10px">
                            <!-- DataTable Product Set -->
                            <div id="odvPdtSetSVDataTable"></div>
                            <!-- End DataTable Product Set -->

                            <?php if (isset($tTextPdtCodeSet) && isset($tTextPdtNameSet)) { ?>
                                <input type="hidden" id="ohdPdtSVCode" name="ohdPdtSVCode" value="<?php echo substr(@$tTextPdtCodeSet, 0, -1); ?>">
                                <input type="hidden" id="ohdPdtSVName" name="ohdPdtSVName" value="<?php echo substr(@$tTextPdtNameSet, 0, -1); ?>">
                            <?php }else{ ?>
                                <input type="hidden" id="ohdPdtSVCode" name="ohdPdtSVCode" value="">
                                <input type="hidden" id="ohdPdtSVName" name="ohdPdtSVName" value="">
                            <?php } ?>       
                        </div>

                    </div>

                    <!-- Tab Content Product Add Event Not Sale -->
                    <div id="odvPdtContentEvnNotSale" class="tab-pane fade">
                        <div id="odvPdtEvnNotSaleMenu" class="row text-right">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label id="olbDelAllPdtEvnNotSale" class="xCNTextBold xWPdtTextLink text-right" style="padding-right:20px">
                                    <i class="fa fa-trash-o"></i> <?php echo language('product/product/product', 'tPDTDelAllEventNoSle') ?>
                                </label>
                                <label id="olbAddPdtEvnNotSale" class="xCNTextBold xWPdtTextLink">
                                    <i class="fa fa-plus"></i> <?php echo language('product/product/product', 'tPDTAddEventNoSle'); ?>
                                </label>
                            </div>
                        </div>
                        <div id="odvPdtEvnNotSaleTable" class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div id="odvPdtEvnNotSaleDataTable" class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPDTEvnCode') ?></th>
                                                <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPDTEvnType') ?></th>
                                                <th nowrap class="text-center xCNTextBold" style="width:20%;"><?php echo language('product/product/product', 'tPDTEvnName') ?></th>
                                                <th nowrap class="text-center xCNTextBold" style="width:15%;"><?php echo language('product/product/product', 'tPDTEvnDateStart') ?></th>
                                                <th nowrap class="text-center xCNTextBold" style="width:15%;"><?php echo language('product/product/product', 'tPDTEvnTimeStart') ?></th>
                                                <th nowrap class="text-center xCNTextBold" style="width:15%;"><?php echo language('product/product/product', 'tPDTEvnDateStop') ?></th>
                                                <th nowrap class="text-center xCNTextBold" style="width:15%;"><?php echo language('product/product/product', 'tPDTEvnTimeStop') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($aPdtInfoGetEvnNoSale) && $aPdtInfoGetEvnNoSale['rtCode'] == '1') : ?>
                                                <?php $tEvnCode = "";
                                                $tTextEvnCode = "";
                                                $tTextEvnName = ""; ?>
                                                <?php foreach ($aPdtInfoGetEvnNoSale['raItems'] as $key => $aPdtEvnValue) : ?>
                                                    <?php if ($tEvnCode != $aPdtEvnValue['FTEvnCode']) : ?>
                                                        <tr class="xWEvnNotSaleRow">
                                                            <td nowrap class="text-center"><?php echo $aPdtEvnValue['FTEvnCode'] ?></td>
                                                            <td nowrap class="text-center">
                                                                <?php echo ($aPdtEvnValue['FTEvnType'] == 1) ? language('product/product/product', 'tPDTEvnNotSaleLangTime') : language('product/product/product', 'tPDTEvnNotSaleLangDate') ?>
                                                            </td>
                                                            <td nowrap class="text-left"><?php echo $aPdtEvnValue['FTEvnName'] ?></td>
                                                            <td nowrap class="text-center"><?php echo ($aPdtEvnValue['FTEvnType'] == 1) ? '-' : date("Y-m-d", strtotime($aPdtEvnValue['FDEvnDStart'])) ?></td>
                                                            <td nowrap class="text-center"><?php echo ($aPdtEvnValue['FTEvnType'] == 1) ? date("H:i:s", strtotime($aPdtEvnValue['FTEvnTStart'])) : '-' ?></td>
                                                            <td nowrap class="text-center"><?php echo ($aPdtEvnValue['FTEvnType'] == 1) ? '-' : date("Y-m-d", strtotime($aPdtEvnValue['FDEvnDFinish'])) ?></td>
                                                            <td nowrap class="text-center"><?php echo ($aPdtEvnValue['FTEvnType'] == 1) ? date("H:i:s", strtotime($aPdtEvnValue['FTEvnTFinish'])) : '-' ?></td>
                                                        </tr>
                                                        <?php
                                                        $tTextEvnCode .= $aPdtEvnValue['FTEvnCode'] . ',';
                                                        $tTextEvnName .= $aPdtEvnValue['FTEvnName'] . ',';
                                                        ?>
                                                    <?php else : ?>
                                                        <tr class="xWEvnNotSaleRow">
                                                            <td nowrap class="text-center"></td>
                                                            <td nowrap class="text-center">
                                                                <?php echo ($aPdtEvnValue['FTEvnType'] == 1) ? language('product/product/product', 'tPDTEvnNotSaleLangTime') : language('product/product/product', 'tPDTEvnNotSaleLangDate') ?>
                                                            </td>
                                                            <td nowrap class="text-left"><?php echo $aPdtEvnValue['FTEvnName'] ?></td>
                                                            <td nowrap class="text-center"><?php echo ($aPdtEvnValue['FTEvnType'] == 1) ? '-' : date("Y-m-d", strtotime($aPdtEvnValue['FDEvnDStart'])) ?></td>
                                                            <td nowrap class="text-center"><?php echo ($aPdtEvnValue['FTEvnType'] == 1) ? date("H:i:s", strtotime($aPdtEvnValue['FTEvnTStart'])) : '-' ?></td>
                                                            <td nowrap class="text-center"><?php echo ($aPdtEvnValue['FTEvnType'] == 1) ? '-' : date("Y-m-d", strtotime($aPdtEvnValue['FDEvnDFinish'])) ?></td>
                                                            <td nowrap class="text-center"><?php echo ($aPdtEvnValue['FTEvnType'] == 1) ? date("H:i:s", strtotime($aPdtEvnValue['FTEvnTFinish'])) : '-' ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php $tEvnCode = $aPdtEvnValue['FTEvnCode']; ?>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr class="xWPdtEvnNoSaleNoData">
                                                    <td class="text-center xCNTextDetail2" colspan="99"><?php echo language('common/main/main', 'tCMNNotFoundData') ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php 
                                    if (isset($tTextEvnCode)) {
                                        $tTextEvnCode = $tTextEvnCode;
                                    } else{
                                        $tTextEvnCode = '';
                                    }

                                    if (isset($tTextEvnName)) {
                                        $tTextEvnName = $tTextEvnName;
                                    } else{
                                        $tTextEvnName = '';
                                    }
                                ?>
                                <input type="hidden" id="ohdPdtEvnNoSleCode" name="ohdEvnNoSleCode" value="<?php echo substr(@$tTextEvnCode, 0, -1); ?>">
                                <input type="hidden" id="ohdPdtEvnNoSleName" name="ohdEvnNoSleName" value="<?php echo substr(@$tTextEvnName, 0, -1); ?>">
                            </div>
                        </div>
                    </div>

                    <div id="odvModallAllPriceList"></div>



                </div>
            </div>
        </div>
</form>

<!--สินค้า LOT / BATCH -->
<div class="modal fade" id="odvModalStockLots">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('product/product/product', 'tPdtStockLotsHead'); ?></label>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow: auto;">
            <form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddStockLot">
                <div class="text-right">
                    <button type="button" class="btn" style="background-color: #D4D4D4; color: #000000;" data-dismiss="modal">
                        <?=language('product/product/product', 'tPdtStockLotsCancel')?>
                    </button>
                    <button type="submit" class="btn xCNBTNSubSave">
                        <?=language('product/product/product', 'tPdtStockLotsSave')?>
                    </button>
                </div>
                    <!-- Berowse LOG/BATCH -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('product/product/product','tPdtStockIdLot')?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNHide" id="oetStockLotCode" name="oetStockLotCode">
                                <input type="text" class="form-control xWPointerEventNone" id="oetStockLotNo" name="oetStockLotNo"
                                data-validate-required="<?php echo language('product/product/product','tPdtStockLotBatchRq');?>" readonly>
                            <span class="input-group-btn">
                                <button id="oimLotBrowseLot" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                </button>
                            </span>
                        </div>
                    </div>
                    <!-- end Berowse LOG/BATCH -->

                    <!-- Lot COST -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"> <?php echo language('product/product/product','tPdtStockLotQty')?></label>
                            <input type="text" class="form-control text-right xCNInputNumericWithDecimal" id="oetStockLotCost" name="oetStockLotCost" maxlength="18"
                                data-validate-required="<?php echo language('product/product/product','tPdtStockLotCost');?>"
                                autocomplete="off">
                    </div>
                    <!-- end จำนวนต่ำสุด -->

                    <!-- Start Date Start -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPdtStockLotDateStart'); ?></label>
                            <div class="input-group">
                                <input type="text" id="oetPdtLotDateStart" class="form-control text-center xCNDatePicker2 xCNInputMaskDate" autocomplete="off" name="oetPdtLotDateStart" value= "<?php echo date_format(date_create($dLotdate),"Y-m-d");?>">
                                 <span class="input-group-btn">
                                    <button id="obtPdtLotDateStart" type="button" class="btn xCNBtnDateTime">
                                        <img class="xCNIconCalendar">
                                    </button>
                                </span>
                            </div>
                    </div>
                    <!--End Date Start -->

                    <!--Start Date End -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPdtStockIdLotDateEnd'); ?></label>
                            <div class="input-group">
                                <input type="text" id="oetPdtLotDateStop" class="form-control xCNDatePicker2 xCNInputMaskDate text-center" autocomplete="off" name="oetPdtLotDateStop" value="<?php echo date_format(date_create($dLotdate),"Y-m-d");?>">
                                <span class="input-group-btn">
                                    <button id="obtPdtLotDateStop" type="button" class="btn xCNBtnDateTime">
                                        <img class="xCNIconCalendar">
                                    </button>
                                </span>
                            </div>
                    </div>
                    <!-- end Date End -->
                </form>
            </div>
        </div>
    </div>
</div>

<!--ลบข้อมูลสินค้า LOT / BATCH -->
<div id="odvModalDeleteStockLot" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelSingleLot" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<div id="odvModallAllData">
    <!-- View Modal Manage Pack Size -->
    <div id="odvModalMngUnitPackSize" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label class="xCNTextModalHeard"><?php echo language('product/product/product', 'tPDTViewPackManage'); ?></label>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                            <!-- <button onclick="JSxPDTChangeUnit()" class="btn xCNBTNPrimery xCNBTNPrimery2Btn"><?php //echo language('product/product/product', 'tPdtChangeUnit');
                                                                                                                    ?></button> -->
                            <button onclick="JSxPdtSaveMngPszUnitInTable()" class="btn xCNBTNPrimery xCNBTNPrimery2Btn"><?php echo language('product/product/product', 'tPDTViewPackSaveManage'); ?></button>
                            <button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal"><?php echo language('product/product/product', 'tPDTViewPackCancelManage'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="panel-body" style="padding:10px">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <!-- Modal Manage PackSize Title Unit -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTHisPIUnit'); ?></label>
                                    <div class="input-group">
                                        <input type="text" id="ohdModalPszUnitCodeOld" class="form-control xCNHide" name="ohdModalPszUnitCodeOld">
                                        <input type="text" id="ohdModalPszUnitCode" class="form-control xCNHide" name="oetModalPszSizeCode">
                                        <input type="text" id="ohdModalPszUnitName" class="form-control" name="oetModalPszSizeName" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtModalUnitBrowse" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!-- <div class="form-group text-right">
                                    <input type="hidden" id="ohdModalPszUnitCode" class="form-control" name="ohdModalPszUnitCode">
                                    <input type="hidden" id="ohdModalPszUnitName" class="form-control" name="ohdModalPszUnitName">
                                    <label id="olbModalPszUnitTitle" class="xCNTitleFrom" data-puncode="" style="margin-bottom: 0px;"></label>
                                </div> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <!-- Modal Manage PackSize Unit Fact -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDUnitFact'); ?></label>
                                    <input type="text" id="oetModalPszUnitFact" class="form-control text-right xCNInputNumericWithDecimal" maxlength="18" name="oetModalPszUnitFact">
                                </div>
                                <!-- Modal Manage PackSize Grade -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDGrade'); ?></label>
                                    <input type="text" id="oetModalPszGrade" class="form-control text-right xCNInputWithoutSpc" name="oetModalPszGrade">
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDWeight'); ?></label>
                                    <input type="text" id="oetModalPszWeight" class="form-control text-right xCNInputNumericWithDecimal" name="oetModalPszWeight">
                                </div>
                                <!-- Modal Manage PackSize Browse Size -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDSize'); ?></label>
                                    <div class="input-group">
                                        <input type="text" id="oetModalPszSizeCode" class="form-control xCNHide" name="oetModalPszSizeCode">
                                        <input type="text" id="oetModalPszSizeName" class="form-control" name="oetModalPszSizeName" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtModalPszBrowseSize" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <!-- Modal Manage PackSize Browse Color -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDColor'); ?></label>
                                    <div class="input-group">
                                        <input type="text" id="oetModalPszClrCode" class="form-control xCNHide" name="oetModalPszClrCode">
                                        <input type="text" id="oetModalPszClrName" class="form-control" name="oetModalPszClrName" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtModalPszBrowseColor" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <hr>
                                <!-- Modal Manage PackSize Unit Dim -->
                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDUnitDim'); ?></label>
                                <div class="form-group">
                                    <label class="xCNLabelFrm" style="font-size:18px !important"><?php echo language('product/product/product', 'tPDTViewPackMDWidth'); ?></label>
                                    <input type="text" id="oetModalPszUnitDimWidth" class="form-control" name="oetModalPszUnitDimWidth">
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm" style="font-size:18px !important"><?php echo language('product/product/product', 'tPDTViewPackMDLength'); ?></label>
                                    <input type="text" id="oetModalPszUnitDimLength" class="form-control" name="oetModalPszUnitDimLength">
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm" style="font-size:18px !important"><?php echo language('product/product/product', 'tPDTViewPackMDHeight'); ?></label>
                                    <input type="text" id="oetModalPszUnitDimHeight" class="form-control" name="oetModalPszUnitDimHeight">
                                </div>

                                <hr>
                                <!-- Modal Manage PackSize Package Dim -->
                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDPkgDim'); ?></label>

                                <div class="form-group">
                                    <label class="xCNLabelFrm" style="font-size:18px !important"><?php echo language('product/product/product', 'tPDTViewPackMDWidth'); ?></label>
                                    <input type="text" id="oetModalPszPackageDimWidth" class="form-control" name="oetModalPszPackageDimWidth">
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm" style="font-size:18px !important"><?php echo language('product/product/product', 'tPDTViewPackMDLength'); ?></label>
                                    <input type="text" id="oetModalPszPackageDimLength" class="form-control" name="oetModalPszPackageDimLength">
                                </div>
                                <div class="form-group">
                                    <label class="xCNLabelFrm" style="font-size:18px !important"><?php echo language('product/product/product', 'tPDTViewPackMDHeight'); ?></label>
                                    <input type="text" id="oetModalPszPackageDimHeight" class="form-control" name="oetModalPszPackageDimHeight">
                                </div>


                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <!-- Modal Manage PackSize StaAlwPick -->
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="ocbModalPszStaAlwPick" name="ocbModalPszStaAlwPick">
                                    <span><?php echo language('product/product/product', 'tPDTViewPackMDStaAlwPick') ?></span>
                                </label>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <!-- Modal Manage PackSize StaAlwPoHQ -->
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="ocbModalPszStaAlwPoHQ" name="ocbModalPszStaAlwPoHQ">
                                    <span><?php echo language('product/product/product', 'tPDTViewPackMDStaAlwPoHQ') ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <!-- Modal Manage PackSize StaAlwBuy -->
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="ocbModalPszStaAlwBuy" name="ocbModalPszStaAlwBuy">
                                    <span><?php echo language('product/product/product', 'tPDTViewPackMDStaAlwBuy') ?></span>
                                </label>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <!-- Modal Manage PackSize StaAlwSale -->
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="ocbModalPszStaAlwSale" name="ocbModalPszStaAlwSale">
                                    <span><?php echo language('product/product/product', 'tPDTViewPackMDStaAlwSale') ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Modal Add หน่วยสินค้า -->
    <div class="modal fade" id="odvModalAddSup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('product/product/product', 'tPdtTabNormalAddSupPDt')?></label>
                </div>
                <form action="javascript:void(0);" id="ofmModalUnitPack" class="validate-form">
                <div class="modal-body" style="max-height: calc(100vh - 180px);overflow-y: auto;">
                    <div><span style="font-weight: bold; color: black;"><?php echo language('product/product/product', 'tPDTName')?> : </span><span id="opdPdtCostSupNamePDTTitle"></span></div>
                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('product/product/product', 'tPDTTabPackSizeUnit'); ?></label>
                        <div class="input-group">
                            <input type="text" id="ohdCostSupType" class="form-control xCNHide" name="ohdCostSupType">
                            <input type="text" id="oetPDTCostSupCodeOld" class="form-control xCNHide" name="oetPDTCostSupCodeOld">
                            <input type="text" id="oetPDTCostSupCode" class="form-control xCNHide" name="oetPDTCostSupCode" value="">
                            <input type="text" id="oetPDTCostSupName" class="form-control" name="oetPDTCostSupName" value="" readonly data-validate-required="<?php echo language('product/product/product', 'tPDTValidPdtPsz'); ?>">
                            <span class="input-group-btn">
                                <button id="obtModalCostSupBrowse" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img class="xCNIconFind">
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('product/product/product', 'tPDTUnitFact'); ?></label>
                        <input type="text" id="oetPDTUnitPerCost" class="form-control text-right xCNInputNumericWithDecimal" name="oetPDTUnitPerCost" maxlength="18" data-validate-required="<?php echo language('product/product/product', 'tPDTViewPackUnitPackFactor'); ?>">
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDColor'); ?></label>
                        <div class="input-group">
                            <input type="text" id="oetPDTColorCode" class="form-control xCNHide" name="oetPDTColorCode">
                            <input type="text" id="oetPDTColorName" class="form-control" name="oetPDTColorName" readonly>
                            <span class="input-group-btn">
                                <button id="obtModalCostSupBrowseColor" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group" style="display:none;">
                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewNorMalPackSize'); ?></label>
                        <div class="input-group">
                            <input type="text" id="oetPDTSizeCode" class="form-control xCNHide" name="oetPDTSizeCode">
                            <input type="text" id="oetPDTSizeName" class="form-control" name="oetPDTSizeName" readonly>
                            <span class="input-group-btn">
                                <button id="obtModalCostSupBrowseSize" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewNorMalPackSize'); ?></label>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <input type="text" class="form-control" id="oetPdtUnitDimWidth" name="oetPdtUnitDimWidth" placeholder="กว้าง" autocomplete="off">
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <input type="text" class="form-control" id="oetPdtUnitDimLength" name="oetPdtUnitDimLength" placeholder="ลึก" autocomplete="off">
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <input type="text" class="form-control" id="oetPdtUnitDimHeight" name="oetPdtUnitDimHeight" placeholder="สูง" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'ขนาดบรรจุภัณฑ์สินค้า'); ?></label>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <input type="text" class="form-control" id="oetPdtPkgDimWidth" name="oetPdtPkgDimWidth" placeholder="กว้าง" autocomplete="off">
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <input type="text" class="form-control" id="oetPdtPkgDimLength" name="oetPdtPkgDimLength" placeholder="ลึก" autocomplete="off">
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <input type="text" class="form-control" id="oetPdtPkgDimHeight" name="oetPdtPkgDimHeight" placeholder="สูง" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDWeight'); ?></label>
                        <input type="text" id="oetPDTWeigh" class="form-control" name="oetPDTWeigh" maxlength="18" >
                    </div>
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'เกรด'); ?></label>
                        <input type="text" id="oetPDTGrade" class="form-control" name="oetPDTGrade" maxlength="18" >
                    </div>
                    <span id="ospConfirmDelete" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                    <input type='hidden' id="ohdConfirmIDDelete">

                    <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                        <!-- อนุญาตสั่งสินค้าจาก สนญ. -->
                        <label class="fancy-checkbox">
                            <script>
                                var tStaCheckVatBuy = "<?php echo $tStaVatBuy; ?>";
                                var tStaVat = "<?php echo $tStaVat; ?>";
                                var tStaNewPdt = "<?php echo $nStaAddOrEdit ?>";
                                if ((typeof(tStaCheckVatBuy) !== 'undefined' && tStaCheckVatBuy == '1' && typeof(tStaVat) !== 'undefined' && tStaVat == '1') || tStaNewPdt == '99') {
                                    $('#ocbPdtAllowOrderBch').prop("checked", true);
                                } else {
                                    $('#ocbPdtAllowOrderBch').prop("checked", false);
                                }
                            </script>
                            <input type="checkbox" id="ocbPdtAllowOrderBch" name="ocbPdtAllowOrderBch">
                            <span><?php echo language('product/product/product', 'tPDTViewNormalAlwHQ') ?></span>
                        </label>
                    </div>

                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                        <!--อนุญาตสั่งจากผู้จำหน่าย -->
                        <script>
                            var tStaPoint = "<?php echo $tStaPoint; ?>";
                            if (typeof(tStaPoint) !== 'undefined' && tStaPoint == '1' || tStaNewPdt == '99') {
                                $('#ocbPdtAllowOrderVendor').prop("checked", true);
                            } else {
                                $('#ocbPdtAllowOrderVendor').prop("checked", false);
                            }
                        </script>
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbPdtAllowOrderVendor" name="ocbPdtAllowOrderVendor">
                            <span><?php echo language('product/product/product', 'tPDTViewNormalAlwBuyer') ?></span>
                        </label>
                    </div>

                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 xCNHide" style="margin-top:10px;">
                        <!-- อนุญาตซื้อ -->
                        <script>
                            var tStaCheckStkControl = "<?php echo $tStkControl; ?>";
                            if (typeof(tStaCheckStkControl) !== 'undefined' && tStaCheckStkControl == '1' || tStaNewPdt == '99') {
                                $('#ocbPdtAllowBuy').prop("checked", true);
                            } else {
                                $('#ocbPdtAllowBuy').prop("checked", false);
                            }
                        </script>
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbPdtAllowBuy" name="ocbPdtAllowBuy">
                            <span><?php echo language('product/product/product', 'tPdtTabNormalAllowBuy') ?></span>
                        </label>
                    </div>

                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                        <!-- อนุญาตขาย -->
                        <script>
                            var tStaCheckStkControl = "<?php echo $tStkControl; ?>";
                            if (typeof(tStaCheckStkControl) !== 'undefined' && tStaCheckStkControl == '1' || tStaNewPdt == '99') {
                                $('#ocbPdtAllowSale').prop("checked", true);
                            } else {
                                $('#ocbPdtAllowSale').prop("checked", false);
                            }
                        </script>
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbPdtAllowSale" name="ocbPdtAllowSale">
                            <span><?php echo language('product/product/product', 'tPDTViewPackMDBarAlwSale') ?></span>
                        </label>
                    </div>

                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                        <!-- อนุญาตจัด -->
                        <label class="fancy-checkbox">
                            <script>
                                var tStaLot = "<?php echo $tStaLot; ?>";
                                if (tStaLot == '1') {
                                    $('#ocbPdtAllowManage').prop("checked", true);
                                } else {
                                    $('#ocbPdtAllowManage').prop("checked", false);
                                }
                            </script>
                            <input type="checkbox" id="ocbPdtAllowManage" name="ocbPdtAllowManage">
                            <span><?=language('product/product/product', 'tPdtTabNormalDefrag') ?></span>
                        </label>
                    </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal">
                        <?php echo language('common/main/main', 'tModalCancel')?>
                    </button>
                    <!-- <button id="osmConfirm" onclick="JSxPdtSaveNormalUnitPackAdd()" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button">
                        <?php echo language('common/main/main', 'tModalConfirm')?>
                    </button> -->
                    <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xWPDTSubmitAddPackUnit"><?php echo language('product/product/product', 'tModalConfirm'); ?></button>
                </div>
            </form>
            </div>
        </div>
    </div>
<!-- END Modal Add หน่วยสินค้า -->

<!-- Modal Add บาร์โค้ด -->
<div class="modal fade" id="odvModalAddSupBarCode">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('product/product/product', 'tPdtTabNormalAddSupPDtBarCode')?></label>
                </div>
                <form action="javascript:void(0);" id="ofmModalUnitBarCode" class="validate-form">
                <div class="modal-body">
                    <div><span style="font-weight: bold; color: black;"><?php echo language('product/product/product', 'tPDTName')?> : </span><span id="opdPdtCostSupBarNamePDTTitle1"></span><span> > </span><span id="opdPdtCostSupNameBarPDTTitle2"></span></div>
                        <input type="text" id="ohdCostSupBarPunCode" class="form-control xCNHide" name="ohdCostSupBarPunCode">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('product/product/product', 'tPDTViewPackMDBarCode'); ?></label>
                        <input type="text" id="oetPDTUnitBarBarCodeOld" class="form-control xCNHide" name="oetPDTUnitBarBarCodeOld">
                        <input type="text" id="oetPDTUnitBarBarCode" class="form-control" name="oetPDTUnitBarBarCode" maxlength="25"  data-validate-required="<?php echo language('product/product/product', 'tPDTViewPackUnitPackBarCode'); ?>">
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDBarLocation'); ?></label>
                        <div class="input-group">
                            <input type="text" id="ohdCostSupBarType" class="form-control xCNHide" name="ohdCostSupBarType">
                            <input type="text" id="oetPDTUnitBarLocCodeOld" class="form-control xCNHide" name="oetPDTUnitBarLocCodeOld">
                            <input type="text" id="oetPDTUnitBarLocCode" class="form-control xCNHide" name="oetPDTUnitBarLocCode" value="">
                            <input type="text" id="oetPDTUnitBarLocName" class="form-control" name="oetPDTUnitBarLocName" value="" readonly>
                            <span class="input-group-btn">
                                <button id="obtModalUnitBarLocBrowse" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img class="xCNIconFind">
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-lg-3 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                        <!-- อนุญาตขาย -->
                        <label class="fancy-checkbox">
                            <script>
                                var tStaCheckVatBuy = "<?php echo $tStaVatBuy; ?>";
                                var tStaVat = "<?php echo $tStaVat; ?>";
                                var tStaNewPdt = "<?php echo $nStaAddOrEdit ?>";
                                if ((typeof(tStaCheckVatBuy) !== 'undefined' && tStaCheckVatBuy == '1' && typeof(tStaVat) !== 'undefined' && tStaVat == '1') || tStaNewPdt == '99') {
                                    $('#ocbPdtBarAlwSale').prop("checked", true);
                                } else {
                                    $('#ocbPdtBarAlwSale').prop("checked", false);
                                }
                            </script>
                            <input type="checkbox" id="ocbPdtBarAlwSale" name="ocbPdtBarAlwSale">
                            <span><?php echo language('product/product/product', 'tPDTViewPackMDBarAlwSale') ?></span>
                        </label>
                    </div>

                    <div class="col-lg-3 col-md-6 col-xs-12 col-sm-12" style="margin-top:10px;">
                        <!--สถานะใช้งาน -->
                        <script>
                            var tStaPoint = "<?php echo $tStaPoint; ?>";
                            if (typeof(tStaPoint) !== 'undefined' && tStaPoint == '1' || tStaNewPdt == '99') {
                                $('#ocbPdtBarAlwUsed').prop("checked", true);
                            } else {
                                $('#ocbPdtBarAlwUsed').prop("checked", false);
                            }
                        </script>
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbPdtBarAlwUsed" name="ocbPdtBarAlwUsed">
                            <span><?php echo language('product/product/product', 'tPDTViewPackMDBarStaUse') ?></span>
                        </label>
                    </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal">
                        <?php echo language('common/main/main', 'tModalCancel')?>
                    </button>
                    <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xWPDTSubmitAddBarUnit"><?php echo language('product/product/product', 'tModalConfirm'); ?></button>
                </div>
                </form>
            </div>
        </div>
    </div>
<!-- END Modal Add บาร์โค้ด -->


<!-- Modal Add ผู้จำหน่าย -->
<div class="modal fade" id="odvModalAddSupSupplier">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <label class="xCNTextModalHeard"><?php echo language('product/product/product', 'tPdtTabNormalAddSupPDtSupplier')?></label>
                </div>
                <form action="javascript:void(0);" id="ofmModalUnitSupplier" class="validate-form">
                <div class="modal-body">
                    <div><span style="font-weight: bold; color: black;"><?php echo language('product/product/product', 'tPDTName')?> : </span><span id="opdPdtCostSupSupplierNamePDTTitle1"></span><span> > </span><span id="opdPdtCostSupSupplierNamePDTTitle2"></span> > <span id="opdPdtCostSupSupplierNamePDTTitle3"></span></div>
                        <input type="text" id="ohdCostSupPunCode" class="form-control xCNHide" name="ohdCostSupPunCode">

                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('product/product/product', 'tPDTHisPISupplier'); ?></label>
                        <div class="input-group">
                            <input type="text" id="ohdCostSupSuplierType" class="form-control xCNHide" name="ohdCostSupSuplierType">
                            <input type="text" id="oetPDTUnitSupplierCodeOld" class="form-control xCNHide" name="oetPDTUnitSupplierCodeOld">
                            <input type="text" id="oetPDTUnitSupplierCode" class="form-control xCNHide" name="oetPDTUnitSupplierCode" value="">
                            <input type="text" id="oetPDTUnitSupplierName" class="form-control" name="oetPDTUnitSupplierName" value="" readonly data-validate-required="<?php echo language('product/product/product', 'tPDTViewPackUnitPackBarCode'); ?>">
                            <span class="input-group-btn">
                                <button id="obtModalSupplierBrowse" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img class="xCNIconFind">
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPdtTabNormalPersonRespond'); ?></label>
                        <div class="input-group">
                            <input type="text" id="oetPDTSupplierUsrCode" class="form-control xCNHide" name="oetPDTSupplierUsrCode" value="">
                            <input type="text" id="oetPDTSupplierUsrName" class="form-control" name="oetPDTSupplierUsrName" value="" readonly>
                            <span class="input-group-btn">
                                <button id="obtModalSuplierSupplierUsrBrowse" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img class="xCNIconFind">
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-left" style="margin-top:10px;">
                        <!-- อนุญาติสั่งวัน จ. -->
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbSupAlwMon" name="ocbSupAlwMon" checked>
                            <span><?php echo language('product/product/product', 'tPdtTabNormalAllowDay1') ?></span>
                        </label>
                    </div>

                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-left" style="margin-top:10px;">
                        <!--อนุญาติสั่งวัน อ. -->
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbSupAlwTue" name="ocbSupAlwTue" checked>
                            <span><?php echo language('product/product/product', 'tPdtTabNormalAllowDay2') ?></span>
                        </label>
                    </div>

                    </div>

                    <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-left" style="margin-top:10px;">
                        <!-- อนุญาติสั่งวัน พ. -->
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbSupAlwWed" name="ocbSupAlwWed" checked>
                            <span><?php echo language('product/product/product', 'tPdtTabNormalAllowDay3') ?></span>
                        </label>
                    </div>

                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-left" style="margin-top:10px;">
                        <!--อนุญาติสั่งวัน พฤ. -->
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbSupAlwThu" name="ocbSupAlwThu" checked>
                            <span><?php echo language('product/product/product', 'tPdtTabNormalAllowDay4') ?></span>
                        </label>
                    </div>

                    </div>

                    <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-left" style="margin-top:10px;">
                        <!-- อนุญาติสั่งวัน ศ. -->
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbSupAlwFri" name="ocbSupAlwFri" checked>
                            <span><?php echo language('product/product/product', 'tPdtTabNormalAllowDay5') ?></span>
                        </label>
                    </div>

                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-left" style="margin-top:10px;">
                        <!--อนุญาติสั่งวัน ส. -->
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbSupAlwSat" name="ocbSupAlwSat" checked>
                            <span><?php echo language('product/product/product', 'tPdtTabNormalAllowDay6') ?></span>
                        </label>
                    </div>

                    </div>

                    <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-left" style="margin-top:10px;">
                        <!-- อนุญาติสั่งวัน อา. -->
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbSupAlwSun" name="ocbSupAlwSun" checked>
                            <span><?php echo language('product/product/product', 'tPdtTabNormalAllowDay7') ?></span>
                        </label>
                    </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal">
                        <?php echo language('common/main/main', 'tModalCancel')?>
                    </button>
                    <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xWPDTSubmitAddSupplier"><?php echo language('product/product/product', 'tModalConfirm'); ?></button>
                </div>
                </form>
            </div>
        </div>
    </div>
<!-- END Modal Add ผู้จำหน่าย -->


    <!-- View Modal Add/Edit BarCode -->
    <div id="odvModalAddEditBarCode" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header xCNModalHead">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label class="xCNTextModalHeard"><?php echo language('product/product/product', 'tPDTViewPackMngBarCode'); ?></label>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#FFF;opacity:1;margin-top:-11px;">
                                <span aria-hidden="true" style="font-size: 30px !important;">×</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="panel-body" style="padding:10px">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <form action="javascript:void(0);" id="ofmModalAebBarCode" class="validate-form">
                                    <button type="submit" id="obtModalAebBarCodeSubmit" class="xCNHide"></button>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <!-- Modal Add/Edit BarCode -->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><span style="color:red">*</span> <?php echo language('product/product/product', 'tPDTViewPackMDBarCode'); ?></label>
                                            <input type="text" id="oetModalAebOldBarCode" class="form-control xCNHide" name="oetModalAebOldBarCode">
                                            <input type="text" id="oetModalAebBarCode" class="form-control" name="oetModalAebBarCode" autocomplete="off" maxlength="25" placeholder="<?php echo language('product/product/product', 'tPDTViewPackMDPachBarCode'); ?>" data-validate-required="<?php echo language('product/product/product', 'tPDTViewPackMDPachBarCode'); ?>"> <!-- xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote -->
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <!-- Modal Add/Edit BarCode Loacation -->
                                        <div class="form-group">
                                            <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDBarLocation'); ?></label>
                                            <div class="input-group">
                                                <input type="text" id="oetModalAebPlcCode" class="form-control xCNHide" name="oetModalAebPlcCode">
                                                <input type="text" id="oetModalAebPlcName" class="form-control" name="oetModalAebPlcName" data-validate-required="<?php echo language('product/product/product', 'tPDTViewPackMDPachBarLocation') ?>" readonly>
                                                <span class="input-group-btn">
                                                    <button id="obtModalAebBrowsePdtLocation" type="button" class="btn xCNBtnBrowseAddOn">
                                                        <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <!-- Modal Selected Supplier -->
                                        <div id="odvMdAesSelectSupplier" class="form-group">
                                            <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPDTViewPackMDSplSupplier'); ?></label>
                                            <div class="input-group">
                                                <input type="text" id="oetModalAesSplCode" class="form-control xCNHide" name="oetModalAesSplCode">
                                                <input type="text" id="oetModalAesSplName" class="form-control" name="oetModalAesSplName" data-validate="<?php echo language('product/product/product', 'tPDTViewPackMDMsgSplNotSltSupplier') ?>" readonly>
                                                <span class="input-group-btn">
                                                    <button id="obtModalAebBrowsePdtSupplier" type="button" class="btn xCNBtnBrowseAddOn">
                                                        <img src="<?php echo base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <!-- Modal Add/Edit BarCode StaUse -->
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbModalAebBarStaUse" name="ocbModalAebBarStaUse">
                                            <span><?php echo language('product/product/product', 'tPDTViewPackMDBarStaUse') ?></span>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <!-- Modal Add/Edit BarCode StaUse -->
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbModalAebBarStaAlwSale" name="ocbModalAebBarStaAlwSale">
                                            <span><?php echo language('product/product/product', 'tPDTViewPackMDBarAlwSale') ?></span>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <!-- Modal Set Status Supplier Allow PO -->
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" id="ocbModalAesSplStaAlwPO" name="ocbModalAesSplStaAlwPO">
                                            <span><?php echo language('product/product/product', 'tPDTViewPackMDSplStaAlwPO') ?></span>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center" style="margin-top:15px;">
                                                <button onclick="JSxPdtModalBarCodeClear()" class="btn xCNBTNDefult xCNBTNDefult2Btn"><?php echo language('product/product/product', 'tPDTViewPackBTNReset'); ?></button>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center" style="margin-top:15px;">
                                                <input type="hidden" name="oetEditData" id="oetEditData" value="0" />
                                                <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xWPDTSubmitAddBar"><?php echo language('product/product/product', 'tPDTViewPackSaveManage'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <input type="hidden" id="ohdModalFTPunCode" class="form-control">
                                        <input type="hidden" id="ohdModalFTPdtCode" class="form-control">

                                        <div class="alert alert-info" role="alert">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3"><?php echo language('product/product/product', 'tPDTSetPdtCode') ?></div>
                                                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><span id="ospTxtPdtCode"></span></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3"><?php echo language('product/product/product', 'tPDTSetPdtName') ?></div>
                                                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><span id="ospTxtPdtName"></span></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3"><?php echo language('product/product/product', 'tPDTTitleUnit') ?></div>
                                                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><span id="ospTxtPunName"></span></div>
                                            </div>
                                        </div>

                                        <div class="xWModalBarCodeDataTable"></div>
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

<script src="<?php echo base_url(); ?>application/modules/common/assets/js/jquery.mask.js"></script>
<script src="<?php echo base_url(); ?>application/modules/common/assets/src/jFormValidate.js"></script>
<!-- <script src="<?= base_url('application/modules/common/assets/js/bootstrap-colorpicker.min.js') ?>"></script> -->
<?php include "script/jProductAdd.php"; ?>
<script type="text/javascript">

    $(document).ready(function() {
      var nPdtType = $('#ocmPdtType').val();
      if(nPdtType == 7){
        $("#ocmPdtSaleType1").attr('disabled',true);
        $("#ocmPdtSaleType3").attr('disabled',true);
        $("#ocmPdtSaleType4").attr('disabled',true);
        $("#ocmPdtSaleType").val(2);
        $("#ocmPdtSaleType").selectpicker('refresh');
      }else if(nPdtType == 2){
        $('#ocbPdtStkControl').prop('checked', false);
        $("#ocbPdtStkControl").attr('disabled',true);
        $("#ocbPdtStkControl").parent().find("span").addClass('xCNDocDisabled');
      }
      else {
        $("#ocmPdtSaleType1").attr('disabled',false);
        $("#ocmPdtSaleType3").attr('disabled',false);
        $("#ocmPdtSaleType4").attr('disabled',false);
        // $("#ocmPdtSaleType").val(1);
        $("#ocmPdtSaleType").selectpicker('refresh');
      }

      $('#obtPDTPCPSearchDocDateFrom').click(function() {
        $('#oetPDTPCPSearchDocDateFrom').datepicker('show');
        });

        $('#obtPDTPCPSearchDocDateTo').click(function() {
            $('#oetPDTPCPSearchDocDateTo').datepicker('show');
        });
    });


    // เวลา Click Tab ให้ Button Show
    $('.xCNStaHideShow').click(function() {
        $('#odvBtnPdtAddEdit').show();
    });

    if($("#ohdtRount").val() == 'productEventAdd'){
        // JSxPdtSetCallSVDataTable();
        // JSxPdtSetCallDataTable();  
    }else{
        // JSxPdtSetCallSVDataTable();
        // JSxPdtSetCallDataTable();  
    }

    // JSxPdtSetCallSVDataTable();
    // JSxPdtSetCallDataTable();

    // เวลา Click Tab ให้ Button Show
    $('#obtBrowsePdtRetShop').click(function() {
        JCNxBrowseData('oCmpBrowseProduct');
    });
    var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
    var nUseType = $('#oetUseType').val();
    var nShpCode = $('#oetShpCode').val();
    var nBchCode = $('#oetBchCode').val();

    //เช็คผู้ใช้
    switch (nUseType) {
        case "HQ":
            tWhereIn = " "
            break;
        case "BCH":
            tWhereIn = "AND TCNMShop.FTBchCode = "+nBchCode+" "
            break;
        case "SHP":
            tWhereIn = "AND TCNMShop.FTBchCode = "+nBchCode+" AND TCNMShop.FTShpCode = "+nShpCode+" "
            break;
        default:
            tWhereIn = " "
    }

    // //Option Product
    var oCmpBrowseProduct = {
        Title: ['company/shop/shop', 'tSHPTitle'],
        Table: {
            Master: 'TCNMShop',
            PK: 'FTShpCode'
        },
        Join: {
            Table: ['TCNMShop_L'],
            On: ['TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop_L.FNLngID = ' + nLangEdits, ]
        },
        Where: {
            Condition: [" AND TCNMShop.FTShpType = '5'", tWhereIn]
        },
        GrideView: {
            ColumnPathLang: 'company/shop/shop',
            ColumnKeyLang: ['tShopCode', 'tShopName'],
            DataColumns: ['TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName'],
            Perpage: 10,
            OrderBy: ['TCNMShop.FTShpCode'],
            SourceOrder: "ASC"
        },
        CallBack: {
            ReturnType: 'S',
            Value: ["oetModalShopCode", "TCNMShop.FTShpCode"],
            Text: ["oetModalShopName", "TCNMShop_L.FTShpName"],
        },
    }

    $('#ocmPdtType').change(function(){
        var nPdtType = $('#ocmPdtType').val();
        if(nPdtType == 7){
          $("#ocmPdtSaleType1").attr('disabled',true);
          $("#ocmPdtSaleType3").attr('disabled',true);
          $("#ocmPdtSaleType4").attr('disabled',true);
          $("#ocmPdtSaleType").val(2);
          $("#ocmPdtSaleType").selectpicker('refresh');
        }else {
          $("#ocmPdtSaleType1").attr('disabled',false);
          $("#ocmPdtSaleType3").attr('disabled',false);
          $("#ocmPdtSaleType4").attr('disabled',false);
          $("#ocmPdtSaleType").val(1);
          $("#ocmPdtSaleType").selectpicker('refresh');
        }

        if(nPdtType == 2){
          $('#ocbPdtStkControl').prop('checked', false);
          $("#ocbPdtStkControl").attr('disabled',true);
          $("#ocbPdtStkControl").parent().find("span").addClass('xCNDocDisabled');
        }else{
          $('#ocbPdtStkControl').prop("checked", true);
          $("#ocbPdtStkControl").parent().find("span").removeClass('xCNDocDisabled');
          $("#ocbPdtStkControl").attr('disabled',false);
        }
    });

    // Fucntion: Slide Panal
    $('.xCNMenuplus').unbind().click(function() {
        //เปิดแค่ panal เดียว
        if ($(this).hasClass('collapsed')) {
            $('.xCNMenuplus').removeClass('collapsed').addClass('collapsed');
            $('.xCNMenuPanelData').removeClass('in');
        }
    });


    tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
    tAgnCode = "<?=$this->session->userdata("tSesUsrAgnCode"); ?>";
    tSQLWhere = "";
    if(tUsrLevel != "HQ"){
        tSQLWhere = " AND (TCNMLot.FTAgnCode IN ("+tAgnCode+") OR ISNULL(TCNMLot.FTAgnCode,'') = '')";
    }

    $('#oimLotBrowseLot').click(function(){JCNxBrowseData('oBrowseLot')});
    var oBrowseLot = {
        Title   : ['service/pdtlot/pdtlot','tLOTTitle'],
        Table   : {Master:'TCNMLot',PK:'FTLotNo',PKName:'FTLotBatchNo'},
        Where   : {
                    Condition : [tSQLWhere]
                },
        GrideView:{
            ColumnPathLang	: 'product/product/product',
            ColumnKeyLang	: ['tLOTCode','DOT ยาง No.'],
            ColumnsSize     : ['15%','75%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMLot.FTLotNo','TCNMLot.FTLotBatchNo'],
            DataColumnsFormat : ['',''],
            Perpage			: 10,
            OrderBy			: ['TCNMLot.FTLotNo DESC'],
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetStockLotCode","TCNMLot.FTLotNo"],
            Text		: ["oetStockLotNo","TCNMLot.FTLotBatchNo"],
        }
    }
</script>
