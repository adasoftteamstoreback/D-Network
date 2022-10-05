<style>
    .col-xs-5ths,
    .col-sm-5ths,
    .col-md-5ths,
    .col-lg-5ths,
    .col-xs-2ths,
    .col-sm-2ths,
    .col-md-2ths,
    .col-lg-2ths {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-xs-2ths {
        width: 40%;
        float: left;
    }
    .col-xs-5ths {
        width: 20%;
        float: left;
    }

    @media (min-width: 768px) {
        .col-sm-5ths {
            width: 20%;
            float: left;
        }
        .col-sm-2ths {
            width: 40%;
            float: left;
        }
    }
    @media (min-width: 992px) {
        .col-md-5ths {
            width: 20%;
            float: left;
        }
        .col-sm-2ths {
            width: 40%;
            float: left;
        }
    }
    @media (min-width: 1200px) {
        .col-lg-2ths {
            width: 40%;
            float: left;
        }
    }
</style>
<?php

//Get parameter SPL
if (empty($tParameterSPL)) {
    $tSPLCode   = '';
} else {
    if ($tParameterSPL[0] == '') {
        $tSPLCode   = '';
    } else {
        $tSPLCode   = $tParameterSPL[0];
    }
}

//Get parameter BCH
$tBCHCode   = '';
$tBCHName   = '';
if( isset($tParameterBCH) && !empty($tParameterBCH) ){
    if( isset($tParameterBCH[0]) && !empty($tParameterBCH[0]) ){
        $tBCHCode = $tParameterBCH[0];
    }
    if( isset($tParameterBCH[1]) && !empty($tParameterBCH[1]) ){
        $tBCHName = $tParameterBCH[1];
    }
}
// if (empty($tParameterBCH)) {
//     $tBCHCode   = '';
//     $tBCHName   = '';
// } else {
//     if ($tParameterBCH[0] == '') {
//         $tBCHCode   = '';
//     } else {
//         $tBCHCode   = $tParameterBCH[0];
//     }
// }

//Get parameter MER
if (empty($tParameterMER)) {
    $tMERCode   = '';
} else {
    if ($tParameterMER[0] == '') {
        $tMERCode   = '';
    } else {
        $tMERCode   = $tParameterMER[0];
    }
}

//Get parameter SHP
if (empty($tParameterSHP)) {
    $tSHPCode   = '';
} else {
    if ($tParameterSHP[0] == '') {
        $tSHPCode   = '';
    } else {
        $tSHPCode   = $tParameterSHP[0];
    }
}

//Get tParameter DISTYPE
if (empty($tParameterDISTYPE)) {
    $tDISTYPE   = '';
} else {
    $tDISTYPE   = $tParameterDISTYPE;
}

//Get parameter not in item
if (empty($aNotinItem)) {
    $tTextNotinItem = '';
} else {
    $tTextNotinItem = '';
    for ($i = 0; $i < FCNnHSizeOf($aNotinItem); $i++) {
        $tTextNotinItem .= $aNotinItem[$i][0] . ':::' . $aNotinItem[$i][1] . ',';

        if ($i == FCNnHSizeOf($aNotinItem) - 1) {
            $tTextNotinItem = substr($tTextNotinItem, 0, -1);
        }
    }
}
?>

<!-- element name and value -->
<input type='hidden' name="odhEleNamePDT" id="odhEleNamePDT" value="<?= $tElementreturn[0] ?>">
<input type='hidden' name="odhEleValuePDT" id="odhEleValuePDT" value="<?= $tElementreturn[1] ?>">
<input type='hidden' name="odhEleNameNextFunc" id="odhEleNameNextFunc" value="<?= $tNameNextFunc ?>">
<input type='hidden' name="odhEleReturnType" id="odhEleReturnType" value="<?= $tReturnType ?>">
<input type='hidden' name="odhSelectTier" id="odhEleSelectTier" value="<?= $tSelectTier ?>">
<input type='hidden' name="odhTimeStorage" id="odhTimeStorage" value="<?= $tTimeLocalstorage ?>">
<input type='hidden' name="ohdSessionBCH" id="ohdSessionBCH" value="<?= $this->session->userdata("tSesUsrBchCode") ?>">
<input type='hidden' name="ohdSessionSHP" id="ohdSessionSHP" value="<?= $this->session->userdata("tSesUsrShpCode") ?>">
<input type='hidden' name="ohdNotinItem" id="ohdNotinItem" value="<?= $tTextNotinItem ?>">
<input type='hidden' name="ohdProductCode" id="ohdProductCode" value="<?= $tParameterProductCode ?>">
<input type='hidden' name="ohdAgenCode" id="ohdAgenCode" value="<?= $tParameterAgenCode ?>">

<div class="row">
    <!--layout search-->
    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
        <!--content tab-->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row xCNHide">

                    <!--ค้นหา-->
                    <!-- <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('common/main/main', 'tSearch') ?></label>
                            <select class="selectpicker form-control" id="ocmSearchPDTSelectbox" name="ocmSearchPDTSelectbox">
                                <option value="FTPdtName"><?= language('common/main/main', 'tCenterModalPDTNamePDT'); ?></option>
                                <option value="FTPdtCode"><?= language('common/main/main', 'tCenterModalPDTCodePDT'); ?></option>
                                <option value="FTBarCode"><?= language('common/main/main', 'tCenterModalPDTBarcode'); ?></option>
                                <option value="FTPgpCode"><?= language('common/main/main', 'tCenterModalPDTPGPFrom'); ?></option>
                                <option value="FTPtyCode"><?= language('common/main/main', 'tCenterModalPDTPTYFrom'); ?></option>
                                <option value="FTBuyer"><?= language('common/main/main', 'tCenterModalPDTPurchasing'); ?></option>
                            </select>
                        </div>
                        <style>
                            .bootstrap-select>.dropdown-toggle {
                                height: 35px;
                            }
                        </style>
                        <script>
                            $('.selectpicker').selectpicker();
                        </script>
                    </div>

                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label class="xCNLabelFrm"></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchPDTText" name="oetSearchPDTText" onkeyup="Javascript:if(event.keyCode==13) JSxGetPDTTable()" value="" placeholder="<?= language('common/main/main', 'tPlaceholder') ?>">
                                <span class="input-group-btn">
                                    <button class="btn xCNBtnSearch" type="button" onclick="JSxGetPDTTable()">
                                        <img class="xCNIconAddOn" src="<?= base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3" style="margin-top: 40px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="ocbPDTMoveon" id="ocbPDTMoveon" value="1" checked>
                            <label class="form-check-label">
                                <?= language('common/main/main', 'tCenterModalPDTMoveon') ?>
                            </label>
                        </div>
                    </div> -->
                </div>

                <?php
                    // print_r( FCNnHSizeOf($aAlwPdtType) );
                    $aAllPdtType = "";
                    if( FCNnHSizeOf($aAlwPdtType) > 0 ){
                        foreach($aAlwPdtType as $aValue){
                            $aAllPdtType .= $aValue.",";
                        }
                    }
                    $aAllPdtType = substr($aAllPdtType, 0, -1);
                    // var_dump(array_search("T6",$aAlwPdtType,TRUE));
                    if( FCNnHSizeOf($aAlwPdtType) == 1 ){
                        $tDisabledPdtType = "disabled";
                    }else{
                        $tDisabledPdtType = "";
                    }
                ?>

                <div class="row">
                    <div class="col-lg-5ths col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('product/product/product', 'tPdtTypeTitle'); ?></label>
                            <select class="selectpicker form-control" id="ocmBrowsePDTType" name="ocmBrowsePDTType" maxlength="2" <?=$tDisabledPdtType?> >
                                <?php if( FCNnHSizeOf($aAlwPdtType) > 1 || FCNnHSizeOf($aAlwPdtType) == 0 ){ ?>
                                    <option value="<?=$aAllPdtType?>" <?php if( $nStaDefaultPdtType == 'ALL' ){ echo "selected"; }?>><?php echo language('product/product/product', 'ทั้งหมด') ?></option>
                                <?php } ?>

                                <?php if(array_search("T1",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="T1" <?php if( $nStaDefaultPdtType == 'T1' ){ echo "selected"; }?>><?php echo language('product/product/product', 'tPdtTypeTitle1') ?></option>
                                <?php } ?>
                                <?php if(array_search("T2",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="T2" <?php if( $nStaDefaultPdtType == 'T2' ){ echo "selected"; }?>><?php echo language('product/product/product', 'tPdtTypeTitle2') ?></option>
                                <?php } ?>
                                <?php if(array_search("T3",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="T3" <?php if( $nStaDefaultPdtType == 'T3' ){ echo "selected"; }?>><?php echo language('product/product/product', 'tPdtTypeTitle3') ?></option>
                                <?php } ?>
                                <?php if(array_search("T4",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="T4" <?php if( $nStaDefaultPdtType == 'T4' ){ echo "selected"; }?>><?php echo language('product/product/product', 'tPdtTypeTitle4') ?></option>
                                <?php } ?>
                                <?php if(array_search("T5",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="T5" <?php if( $nStaDefaultPdtType == 'T5' ){ echo "selected"; }?>><?php echo language('product/product/product', 'tPdtTypeTitle5') ?></option>
                                <?php } ?>
                                <?php if(array_search("T6",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="T6" <?php if( $nStaDefaultPdtType == 'T6' ){ echo "selected"; }?>><?php echo language('product/product/product', 'tPdtTypeTitle6') ?></option>
                                <?php } ?>
                                <?php if(array_search("T7",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="T7" <?php if( $nStaDefaultPdtType == 'T7' ){ echo "selected"; }?>><?php echo language('product/product/product', 'tPdtTypeTitle7') ?></option>
                                <?php } ?>

                                <?php if(array_search("S2",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="S2" <?php if( $nStaDefaultPdtType == 'S2' ){ echo "selected"; }?>><?php echo language('product/product/product', 'สินค้าปกติชุด') ?></option>
                                <?php } ?>
                                <?php if(array_search("S3",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="S3" <?php if( $nStaDefaultPdtType == 'S3' ){ echo "selected"; }?>><?php echo language('product/product/product', 'สินค้าSerial') ?></option>
                                <?php } ?>
                                <?php if(array_search("S4",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="S4" <?php if( $nStaDefaultPdtType == 'S4' ){ echo "selected"; }?>><?php echo language('product/product/product', 'สินค้าSerial Set') ?></option>
                                <?php } ?>
                                <?php if(array_search("S5",$aAlwPdtType) !== FALSE || FCNnHSizeOf($aAlwPdtType) == 0){ ?>
                                    <option value="S5" <?php if( $nStaDefaultPdtType == 'S5' ){ echo "selected"; }?>><?php echo language('product/product/product', 'สินค้าศูนย์บริการ') ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <!--กรอกคำค้นหา-->
                    <div class="col-lg-2ths col-md-5 col-sm-5 col-xs-12">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('common/main/main', 'tSearch') ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNInputWithoutSingleQuote" autocomplete="off" id="oetSearchPDTText" name="oetSearchPDTText" onkeyup="Javascript:if(event.keyCode==13) JSxGetPDTTable()" value="" placeholder="<?= language('common/main/main', 'tPlaceholder') ?>">
                                <span class="input-group-btn">
                                    <button class="btn xCNBtnSearch" type="button" onclick="JSxGetPDTTable()">
                                        <img class="xCNIconAddOn" src="<?= base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5ths col-md-2 col-sm-2 col-xs-12">
                        <a id="oahBrowsePDTAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn" style="margin-top: 26px !important;width: 100%;" href="javascript:;"><?=language('common/main/main', 'tAdvanceSearch'); ?></a>
                    </div>
                    <div class="col-lg-5ths col-md-2 col-sm-2 col-xs-12">
                        <a id="obtResetBrowsePDT" class="btn xCNBTNDefult xCNBTNDefult1Btn" style="margin-top: 26px !important;width: 100%;" href="javascript:;"><?=language('common/main/main', 'tClearSearch'); ?></a>
                    </div>
                </div>

                <div id="odvBrowsePDTAdvanceSearchContainer" class="hidden">

                    <div class="row">
                        <!--สาขา-->
                        <?php
                            
                            // if ( $this->session->userdata("tSesUsrLevel") != "HQ" ){
                            //     if( $this->session->userdata("nSesUsrBchCount") <= 1 ){ //ค้นหาขั้นสูง
                            //         $tBCHCode   = $this->session->userdata("tSesUsrBchCodeDefault");
                            //         $tBCHName   = $this->session->userdata("tSesUsrBchNameDefault");
                            //         $tDisabled  = "disabled";
                            //     }else{
                            //         $tBCHCode   = '';
                            //         $tBCHName   = '';
                            //         $tDisabled  = "";
                            //     }
                            // } else {
                            //     $tBCHCode       = "";
                            //     $tBCHName       = "";
                            //     $tDisabled      = "";
                            // }
                            
                            $tDisabled  = "";
                            if ( $this->session->userdata("tSesUsrLevel") != "HQ" ){
                                if( $this->session->userdata("nSesUsrBchCount") <= 1 ){ //ค้นหาขั้นสูง
                                    $tDisabled  = "disabled";
                                }
                            }
                            
                        ?>
                        <div class="col-md-6 col-xs-12 col-sm-3 col-lg-4">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTBranch');?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBrowsePDTBchCode" name="oetBrowsePDTBchCode" value="<?=$tBCHCode?>">
                                    <input type="text" placeholder="<?=language('common/main/main','tCenterModalPDTBranch');?>" class="form-control xWPointerEventNone" id="oetBrowsePDTBchName" name="oetBrowsePDTBchName" value="<?=$tBCHName?>" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtBrowsePDTBch" <?=$tDisabled?> type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- หมวดหมู่สินค้า 1 -->
                        <div class="col-md-3 col-xs-12 col-sm-3 col-lg-4">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main','หมวดหมู่สินค้า 1');?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBrowsePDTCatCodeLv1" name="oetBrowsePDTCatCodeLv1" value="">
                                    <input type="text" placeholder="<?=language('common/main/main','หมวดหมู่สินค้า 1');?>" class="form-control xWPointerEventNone" id="oetBrowsePDTCatNameLv1" name="oetBrowsePDTCatNameLv1" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtBrowsePDTCatLv1" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- จบ หมวดหมู่สินค้า 1 -->

                        <!-- หมวดหมู่สินค้า 2 -->
                        <div class="col-md-3 col-xs-12 col-sm-3 col-lg-4">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main','หมวดหมู่สินค้า 2');?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBrowsePDTCatCodeLv2" name="oetBrowsePDTCatCodeLv2" value="">
                                    <input type="text" placeholder="<?=language('common/main/main','หมวดหมู่สินค้า 2');?>" class="form-control xWPointerEventNone" id="oetBrowsePDTCatNameLv2" name="oetBrowsePDTCatNameLv2" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtBrowsePDTCatLv2" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- จบ หมวดหมู่สินค้า 2 -->
                        
                    </div>

                    <div class="row">

                        <!--กลุ่มสินค้า-->
                        <div class="col-lg-5ths col-md-2 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTPGPFrom');?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBrowsePDTPGPFrmCode" name="oetBrowsePDTPGPFrmCode" value="">
                                    <input type="text" placeholder="<?=language('common/main/main','tCenterModalPDTPGPFrom');?>" class="form-control xWPointerEventNone" id="oetBrowsePDTPGPFrmName" name="oetBrowsePDTPGPFrmName" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtBrowsePDTFrmPGP" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!--ถึงกลุ่มสินค้า-->
                        <div class="col-lg-5ths col-md-2 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTPGPTo');?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBrowsePDTPGPToCode" name="oetBrowsePDTPGPToCode" value="">
                                    <input type="text" placeholder="<?=language('common/main/main','tCenterModalPDTPGPTo');?>" class="form-control xWPointerEventNone" id="oetBrowsePDTPGPToName" name="oetBrowsePDTPGPToName" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtBrowsePDTToPGP" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!--ประเภทสินค้า-->
                        <div class="col-lg-5ths col-md-2 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTPTYFrom');?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBrowsePDTPTYFrmCode" name="oetBrowsePDTPTYFrmCode" value="">
                                    <input type="text" placeholder="<?=language('common/main/main','tCenterModalPDTPTYFrom');?>" class="form-control xWPointerEventNone" id="oetBrowsePDTPTYFrmName" name="oetBrowsePDTPTYFrmName" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtBrowsePDTFrmPTY" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!--ถึงประเภทสินค้า-->
                        <div class="col-lg-5ths col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTPTYTo');?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBrowsePDTPTYToCode" name="oetBrowsePDTPTYToCode" value="">
                                    <input type="text" placeholder="<?=language('common/main/main','tCenterModalPDTPTYTo');?>" class="form-control xWPointerEventNone" id="oetBrowsePDTPTYToName" name="oetBrowsePDTPTYToName" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtBrowsePDTToPTY" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!--ที่เก็บ-->
                        <div class="col-lg-5ths col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTLOGSEQFrom');?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBrowsePDTLOGSEQCode" name="oetBrowsePDTLOGSEQCode" value="">
                                    <input type="text" placeholder="<?=language('common/main/main','tCenterModalPDTLOGSEQFrom');?>" class="form-control xWPointerEventNone" id="oetBrowsePDTLOGSEQName" name="oetBrowsePDTLOGSEQName" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtBrowsePDTLOGSEQ" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        

                        <!--สินค้าเคลือนไหว-->
                        <!-- <div class="col-md-5ths col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?php echo language('product/product/product', 'สถานะเคลื่อนไหว'); ?></label>
                                <select class="selectpicker form-control" id="ocbPDTMoveon" name="ocbPDTMoveon" maxlength="1">
                                    <option value=""><?php echo language('product/product/product', 'ทั้งหมด') ?></option>
                                    <option value="1" selected><?php echo language('product/product/product', 'เคลื่อนไหว') ?></option>
                                    <option value="2"><?php echo language('product/product/product', 'ไม่เคลื่อนไหว') ?></option>
                                </select>
                            </div>
                        </div> -->

                        <!--จากผู้จำหน่าย-->
                        <div class="col-lg-2 col-md-5 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTSUPFrom');?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBrowsePDTSPLFrmCode" name="oetBrowsePDTSPLFrmCode" value="">
                                    <input type="text" placeholder="<?=language('common/main/main','tCenterModalPDTSUPFrom');?>" class="form-control xWPointerEventNone" id="oetBrowsePDTSPLFrmName" name="oetBrowsePDTSPLFrmName" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtBrowsePDTSPLFrm" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!--จากผู้จำหน่าย-->
                        <div class="col-lg-2 col-md-5 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?=language('common/main/main','tCenterModalPDTSUPTo');?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetBrowsePDTSPLToCode" name="oetBrowsePDTSPLToCode" value="">
                                    <input type="text" placeholder="<?=language('common/main/main','tCenterModalPDTSUPTo');?>" class="form-control xWPointerEventNone" id="oetBrowsePDTSPLToName" name="oetBrowsePDTSPLToName" value="" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtBrowsePDTSPLTo" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                            <button id="obtFindBrowsePDT" class="btn xCNBTNPrimery" style="margin-top: 26px !important;width: 100%;"><?= language('common/main/main', 'tSearch') ?></button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--layout table-->
    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
        <div id="odvTableContentPDT" style="height:412px;">
            <img src="<?php echo base_url() ?>application/modules/common/assets/images/ada.loading.gif" class="xWImgLoading">
        </div>
    </div>

</div>

<script>

    $('.selectpicker').selectpicker();

    // Event Click On/Off Advance Search
    $('#oahBrowsePDTAdvanceSearch').unbind().click(function(){
        if($('#odvBrowsePDTAdvanceSearchContainer').hasClass('hidden')){
            $('#odvBrowsePDTAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvBrowsePDTAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    //ข้อมูลสินค้า
    JSxGetPDTTable();
    function JSxGetPDTTable(pnPage) {
        if (pnPage == '' || pnPage == null) {
            pnPage = 1;
        } else {
            pnPage = pnPage;
        }

        var SelectTier = $('#odhEleSelectTier').val();
        var aPriceType = '<?= $aPriceType[0] ?>';

        //สินค้าเคลื่อนไหว
        // if ($('#ocbPDTMoveon').is(":checked")) {
        //     var nPDTMoveon = 1;
        // } else {
        //     var nPDTMoveon = 2;
        // }

        var nPageTotal = $('#ospAllPDTRow').text();

        //ตัว รีโหลดเวลาโหลดสินค้า
        var tImage = "<img src='<?= base_url() ?>application/modules/common/assets/images/ada.loading.gif' class='xWImgLoading'>";
        $('#odvTableContentPDT').html(tImage);
        $('#odvTableContentPDT').css('height', '412px');

        //Option ค้นหา
        var aPackDataForSerach = {
            'tSearchText'               : $('#oetSearchPDTText').val(),
            'tSearchPDTType'            : $('#ocmBrowsePDTType').val(),
            'tSearchPDTBchCode'         : $('#oetBrowsePDTBchCode').val(),
            'tSearchPDTPGPFrmCode'      : $('#oetBrowsePDTPGPFrmCode').val(),
            'tSearchPDTPGPToCode'       : $('#oetBrowsePDTPGPToCode').val(),
            'tSearchPDTPTYFrmCode'      : $('#oetBrowsePDTPTYFrmCode').val(),
            'tSearchPDTPTYToCode'       : $('#oetBrowsePDTPTYToCode').val(),
            'tSearchPDTSPLFrmCode'      : $('#oetBrowsePDTSPLFrmCode').val(),
            'tSearchPDTSPLToCode'       : $('#oetBrowsePDTSPLToCode').val(),
            'tSearchPDTLOGSEQCode'      : $('#oetBrowsePDTLOGSEQCode').val(),
            'nPDTMoveon'                : 1, /*$('#ocbPDTMoveon').val()*/
            'tSearchPDTCatLv1'          : $('#oetBrowsePDTCatCodeLv1').val(),
            'tSearchPDTCatLv2'          : $('#oetBrowsePDTCatCodeLv2').val(),
            'tStaControlStk'            : '<?=$tStaControlStk?>'
        };

        $.ajax({
            type: "POST",
            url: "BrowseDataPDTTableCallView",
            data: {
                'tPagename'          : '<?= $tPagename ?>',
                'nPage'              : pnPage,
                'nRow'               : '<?= $nShowCountRecord ?>',
                'aPriceType'         : '<?= json_encode($aPriceType) ?>',
                'BCH'                : '<?= $tBCHCode ?>',
                'WAH'                : '<?= $tParameterWAH[0] ?>',
                'SHP'                : '<?= $tSHPCode ?>',
                'MER'                : '<?= $tMERCode ?>',
                'SPL'                : '<?= $tSPLCode ?>',
                'DISTYPE'            : '<?= $tDISTYPE ?>',
                'SelectTier'         : $('#odhEleSelectTier').val(),
                'ReturnType'         : $('#odhEleReturnType').val(),
                'aNotinItem'         : $('#ohdNotinItem').val(),
                'nTotalResult'       : nPageTotal,
                'ProductCode'        : '<?= $tParameterProductCode ?>',
                'AgenCode'           : '<?= $tParameterAgenCode ?>',
                'tNotInPdtType'      : '<?= json_encode($tNotInPdtType) ?>',
                'tWhere'             : '<?= json_encode($tWhere) ?>',
                'aPackDataForSerach' : aPackDataForSerach,
                'tTYPEPDT'           : '<?= $tTYPEPDT ?>',
                'tSNPDT'             : '<?= $tSNPDT ?>'
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                // console.log(tResult);

                $('#odvTableContentPDT').html(tResult);
                $('#odvTableContentPDT').css('height', 'auto');

                var tTimeStorage = $('#odhTimeStorage').val();
                var LocalItemDataPDT = localStorage.getItem("LocalItemDataPDT" + tTimeStorage);
                if (LocalItemDataPDT != '' || LocalItemDataPDT != null) {
                    var tResultPDT = JSON.parse(LocalItemDataPDT);
                    if (tResultPDT == null || tResultPDT == '') {

                    } else {
                        var nCount = tResultPDT.length;
                        for ($i = 0; $i < nCount; $i++) {
                            var tStringCheck = tResultPDT[$i].pnPdtCode + tResultPDT[$i].ptBarCode;
                            var tChcek = 'JSxPDTClickMuti' + tStringCheck;
                            $('.' + tChcek).addClass('xCNActivePDT');
                            $('.' + tChcek).find('td').attr('style', 'color: #FFF !important;');
                        }
                    }
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    //ตัวเลือกในการต่างๆ
    var nLangEdits      = '<?=$this->session->userdata("tLangEdit")?>';
    var tAgnCode        = '<?=$this->session->userdata("tSesUsrAgnCode"); ?>';
    var tConditionGRP   = '';
    var tConditionPTY   = '';
    var tConditionSPL   = '';
    var tConditionLOG   = '';
    if( tAgnCode != '' ){
        tConditionGRP += " AND TCNMPdtGrp.FTAgnCode = '"+tAgnCode+"' ";
        tConditionPTY += " AND TCNMPdtType.FTAgnCode = '"+tAgnCode+"' ";
        tConditionSPL += " AND TCNMSpl.FTAgnCode = '"+tAgnCode+"' ";
        tConditionLOG += " AND TCNMPdtLoc.FTAgnCode = '"+tAgnCode+"' ";
    }

    // ========================================= เลือกสาขา ========================================= //
    $('#obtBrowsePDTBch').click(function(){JCNxBrowseData('oBrowseBranch');})
    var oBrowseBranch = {
        Title   : ['authen/user/user','tBrowseBCHTitle'],
        Table   : {Master:'TCNMBranch',PK:'FTBchCode'},
        Join    : {
            Table   :	['TCNMBranch_L'],
            On      :   ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
		},
        GrideView   : { 
            ColumnPathLang	: 'authen/user/user',
            ColumnKeyLang	: ['tBrowseBCHCode','tBrowseBCHName'],
            ColumnsSize     : ['10%','75%'],
            DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
            DataColumnsFormat : ['',''],
            WidthModal      : 50,
            Perpage			: 10,
            OrderBy			: ['TCNMBranch.FTBchCode'],
            SourceOrder		: "ASC"
        },
        CallBack    : {
            ReturnType	: 'S',
            Value		: ["oetBrowsePDTBchCode","TCNMBranch.FTBchCode"],
            Text		: ["oetBrowsePDTBchName","TCNMBranch_L.FTBchName"],
        }
    }

    // ========================================= กลุ่มสินค้า ========================================= //
    $('#obtBrowsePDTFrmPGP').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowsePGPFrmOption = oPCPBrowsePGP({
                'tReturnInputCode'  : 'oetBrowsePDTPGPFrmCode',
                'tReturnInputName'  : 'oetBrowsePDTPGPFrmName',
                'tInputNextFunc'    : 'JSaNextFuncBrowsePGP'
            });
            JCNxBrowseData('oBrowsePGPFrmOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    $('#obtBrowsePDTToPGP').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowsePGPToOption = oPCPBrowsePGP({
                'tReturnInputCode'  : 'oetBrowsePDTPGPToCode',
                'tReturnInputName'  : 'oetBrowsePDTPGPToName',
                'tInputNextFunc'    : 'JSaNextFuncBrowsePGP'
            });
            JCNxBrowseData('oBrowsePGPToOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    var oPCPBrowsePGP = function(poReturnInput) {
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tInputNextFunc      = poReturnInput.tInputNextFunc;
        var oOptionReturn = {
            Title   : ['product/pdtgroup/pdtgroup', 'tPGPTitle'],
            Table   : { Master : 'TCNMPdtGrp', PK : 'FTPgpChain' },
            Join: {
                Table   : [ 'TCNMPdtGrp_L' ],
                On      : [ 'TCNMPdtGrp_L.FTPgpChain = TCNMPdtGrp.FTPgpChain AND TCNMPdtGrp_L.FNLngID = ' + nLangEdits ]
            },
            Where: {
                Condition: [tConditionGRP]
            },
            GrideView   : {
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['tBCHCode', 'tBCHName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMPdtGrp.FTPgpChain', 'TCNMPdtGrp_L.FTPgpName'],
                DataColumnsFormat   : ['', ''],
                Perpage             : 10,
                OrderBy             : ['TCNMPdtGrp.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMPdtGrp.FTPgpChain"],
                Text        : [tInputReturnName, "TCNMPdtGrp_L.FTPgpName"]
            },
            NextFunc:{
                FuncName    : tInputNextFunc,
                ArgReturn   : ['FTPgpChain', 'FTPgpName']
            },
        }
        return oOptionReturn;
    };
    //หลังจากเลือกกลุ่มสินค้า
    function JSaNextFuncBrowsePGP(poArgReturn){
        if(poArgReturn != "NULL"){
            var aReturn = JSON.parse(poArgReturn);
            $('#oetBrowsePDTPGPToCode').val(aReturn[0]);
            $('#oetBrowsePDTPGPToName').val(aReturn[1]);
        }
    }

    // ========================================= ประเภทสินค้า ========================================= //
    $('#obtBrowsePDTFrmPTY').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowsePTYFrmOption = oPCPBrowsePTY({
                'tReturnInputCode'  : 'oetBrowsePDTPTYFrmCode',
                'tReturnInputName'  : 'oetBrowsePDTPTYFrmName',
                'tInputNextFunc'    : 'JSaNextFuncBrowsePTY'
            });
            JCNxBrowseData('oBrowsePTYFrmOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    $('#obtBrowsePDTToPTY').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowsePTYToOption = oPCPBrowsePTY({
                'tReturnInputCode'  : 'oetBrowsePDTPTYToCode',
                'tReturnInputName'  : 'oetBrowsePDTPTYToName',
                'tInputNextFunc'    : 'JSaNextFuncBrowsePTY'
            });
            JCNxBrowseData('oBrowsePTYToOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    var oPCPBrowsePTY = function(poReturnInput) {
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tInputNextFunc      = poReturnInput.tInputNextFunc;
        var oOptionReturn = {
            Title   : ['product/pdttype/pdttype', 'tPTYTitle'],
            Table   : { Master : 'TCNMPdtType', PK : 'FTPtyCode' },
            Join: {
                Table  : ['TCNMPdtType_L'],
                On     : ['TCNMPdtType_L.FTPtyCode = TCNMPdtType.FTPtyCode AND TCNMPdtType_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition   : [tConditionPTY]
            },
            GrideView: {
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['tBCHCode', 'tBCHName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMPdtType.FTPtyCode', 'TCNMPdtType_L.FTPtyName'],
                DataColumnsFormat   : ['', ''],
                Perpage             : 10,
                OrderBy             : ['TCNMPdtType.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMPdtType.FTPtyCode"],
                Text        : [tInputReturnName, "TCNMPdtType_L.FTPtyName"]
            },
            NextFunc:{
                FuncName    : tInputNextFunc,
                ArgReturn   : ['FTPtyCode', 'FTPtyName']
            },
        }
        return oOptionReturn;
    };
    //หลังจากเลือกประเภทสินค้า
    function JSaNextFuncBrowsePTY(poArgReturn){
        if(poArgReturn != "NULL"){
            var aReturn = JSON.parse(poArgReturn);
            $('#oetBrowsePDTPTYToCode').val(aReturn[0]);
            $('#oetBrowsePDTPTYToName').val(aReturn[1]);
        }
    }

    // ========================================= ผู้จำหน่าย ========================================= //
    $('#obtBrowsePDTSPLFrm').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowseSPLFrmOption = oPCPBrowseSPL({
                'tReturnInputCode'  : 'oetBrowsePDTSPLFrmCode',
                'tReturnInputName'  : 'oetBrowsePDTSPLFrmName',
                'tInputNextFunc'    : 'JSaNextFuncBrowseSPL'
            });
            JCNxBrowseData('oBrowseSPLFrmOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    $('#obtBrowsePDTSPLTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowseSPLToOption = oPCPBrowseSPL({
                'tReturnInputCode'  : 'oetBrowsePDTSPLToCode',
                'tReturnInputName'  : 'oetBrowsePDTSPLToName',
                'tInputNextFunc'    : 'JSaNextFuncBrowseSPL'
            });
            JCNxBrowseData('oBrowseSPLToOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    var oPCPBrowseSPL = function(poReturnInput) {
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tInputNextFunc      = poReturnInput.tInputNextFunc;
        var oOptionReturn = {
            Title   : ['supplier/supplier/supplier', 'tSPLTitle'],
            Table   : {Master:'TCNMSpl', PK:'FTSplCode'},
            Join    : {
                Table   : ['TCNMSpl_L'],
                On      : ['TCNMSpl.FTSplCode = TCNMSpl_L.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits]
            },
            Where: {
                Condition: [tConditionSPL]
            },
            GrideView   :{
                ColumnPathLang  : 'supplier/supplier/supplier',
                ColumnKeyLang   : ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize     : ['15%', '75%'],
                WidthModal      : 50,
                DataColumns     : ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName'],
                DataColumnsFormat: ['', ''],
                DisabledColumns : [],
                Perpage         : 10,
                OrderBy         : ['TCNMSpl.FDCreateOn DESC']
            },
            CallBack:{
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMSpl.FTSplCode"],
                Text        : [tInputReturnName, "TCNMSpl_L.FTSplName"]
            },
            NextFunc:{
                FuncName    : tInputNextFunc,
                ArgReturn   : ['FTSplCode', 'FTSplName']
            },
        }
        return oOptionReturn;
    };
    //หลังจากเลือกผู้จำหน่าย
    function JSaNextFuncBrowseSPL(poArgReturn){
        if(poArgReturn != "NULL"){
            var aReturn = JSON.parse(poArgReturn);
            $('#oetBrowsePDTSPLToCode').val(aReturn[0]);
            $('#oetBrowsePDTSPLToName').val(aReturn[1]);
        }
    }

    // ========================================= เลือกที่เก็บ ========================================= //
    $('#obtBrowsePDTLOGSEQ').click(function(){JCNxBrowseData('oBrowseLOGSEQ');})
    var oBrowseLOGSEQ= {
        Title   : ['product/pdtlocation/pdtlocation','tLOCTitle'],
        Table   : {Master:'TCNMPdtLoc',PK:'FTPlcCode'},
        Join    : {  
                Table:['TCNMPdtLoc_L'],
                On:['TCNMPdtLoc_L.FTPlcCode = TCNMPdtLoc.FTPlcCode AND TCNMPdtLoc_L.FNLngID = '+nLangEdits]
        },
        Where: {
                Condition: [tConditionLOG]
        },
        GrideView   : { 
            ColumnPathLang	: 'product/pdtlocation/pdtlocation',
            ColumnKeyLang	: ['tLOCFrmLocCode','tLOCFrmLocName'],
            ColumnsSize     : ['10%','75%'],
            DataColumns		: ['TCNMPdtLoc.FTPlcCode','TCNMPdtLoc_L.FTPlcName'],
            DataColumnsFormat : ['',''],
            WidthModal      : 50,
            Perpage			: 10,
            OrderBy			: ['TCNMPdtLoc.FTPlcCode'],
            SourceOrder		: "ASC"
        },
        CallBack    : {
            ReturnType	: 'S',
            Value		: ["oetBrowsePDTLOGSEQCode","TCNMPdtLoc.FTPlcCode"],
            Text		: ["oetBrowsePDTLOGSEQName","TCNMPdtLoc_L.FTPlcName"],
        }
    }

    // ========================================= ล้างข้อมูล ========================================= //
    $('#obtResetBrowsePDT').click(function(){

        // ซ่อน Content ค้นหาขั้นสูง กรณีกดล้างข้อมูล
        var oContentAdvSearch = $('#odvBrowsePDTAdvanceSearchContainer');
        if( !oContentAdvSearch.hasClass('hidden') ){
            oContentAdvSearch.slideUp(500,function() {
                oContentAdvSearch.addClass('hidden');
            });
        }

        var nCountBch = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
        if(nCountBch != 1){ //ถ้ามีมากกว่า 1 สาขาต้อง reset 
            $('#oetBrowsePDTBchCode , #oetBrowsePDTBchName').val('');
        }

        $('#oetSearchPDTText').val('');

        $('#oetBrowsePDTPGPFrmCode , #oetBrowsePDTPGPFrmName').val('');
        $('#oetBrowsePDTPGPToCode , #oetBrowsePDTPGPToName').val('');

        $('#oetBrowsePDTPTYFrmCode , #oetBrowsePDTPTYFrmName').val('');
        $('#oetBrowsePDTPTYToCode , #oetBrowsePDTPTYToName').val('');

        $('#oetBrowsePDTSPLFrmCode , #oetBrowsePDTSPLFrmName').val('');
        $('#oetBrowsePDTSPLToCode , #oetBrowsePDTSPLToName').val('');

        $('#oetBrowsePDTLOGSEQCode , #oetBrowsePDTLOGSEQName').val('');

        // เคลียร์หน่วยสินค้า 1
        $('#oetBrowsePDTCatCodeLv1').val('');
        $('#oetBrowsePDTCatNameLv1').val('');
        // เคลียร์หน่วยสินค้า 2
        $('#oetBrowsePDTCatCodeLv2').val('');
        $('#oetBrowsePDTCatNameLv2').val('');
        // เคลียร์ประเภทสินค้า เป็นสินค้าศูนย์บริการ (Default สำหรับ Fitauto)
        $('#ocmBrowsePDTType').val('S5');
        $('#ocmBrowsePDTType').selectpicker('refresh');

        //ค้นหาอีกรอบ
        JSxGetPDTTable();
    });

    $('#ocmBrowsePDTType').on('change',function(){
        JSxGetPDTTable();
    });
    

    // ========================================= ค้นหาข้อมูล ========================================= //
    $('#obtFindBrowsePDT').click(function(){
        JSxGetPDTTable();
    });



    $('#obtBrowsePDTCatLv1').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowsePDTCategoryOption = oBrowsePDTCategory({
                'tReturnInputCode'  : 'oetBrowsePDTCatCodeLv1',
                'tReturnInputName'  : 'oetBrowsePDTCatNameLv1',
                'nCatLevel'         : 1
                // 'tInputNextFunc'    : 'JSaNextFuncBrowseSPL'
            });
            JCNxBrowseData('oBrowsePDTCategoryOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    $('#obtBrowsePDTCatLv2').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowsePDTCategoryOption = oBrowsePDTCategory({
                'tReturnInputCode'  : 'oetBrowsePDTCatCodeLv2',
                'tReturnInputName'  : 'oetBrowsePDTCatNameLv2',
                'nCatLevel'         : 2
                // 'tInputNextFunc'    : 'JSaNextFuncBrowseSPL'
            });
            JCNxBrowseData('oBrowsePDTCategoryOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    var oBrowsePDTCategory = function(poReturnInput) {
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        // var tInputNextFunc      = poReturnInput.tInputNextFunc;
        var nCatLevel           = poReturnInput.nCatLevel;
        var tCondition          = "";

        if( nCatLevel != "" ){
            tCondition += " AND TCNMPdtCatInfo.FNCatLevel = " + nCatLevel;
        }

        var oOptionReturn = {
            Title   : ['supplier/supplier/supplier', 'หมวดหมู่สินค้า ' + nCatLevel],
            Table   : {Master:'TCNMPdtCatInfo', PK:'FTCatCode'},
            Join    : {
                Table   : ['TCNMPdtCatInfo_L'],
                On      : ['TCNMPdtCatInfo.FTCatCode = TCNMPdtCatInfo_L.FTCatCode AND TCNMPdtCatInfo.FNCatLevel = TCNMPdtCatInfo_L.FNCatLevel AND TCNMPdtCatInfo_L.FNLngID = '+nLangEdits]
            },
            Where: {
                Condition: [ tCondition + " AND TCNMPdtCatInfo.FTCatStaUse = '1' "]
            },
            GrideView   :{
                ColumnPathLang  : 'supplier/supplier/supplier',
                ColumnKeyLang   : ['รหัสหมวดหมู่', 'ชื่อหมวดหมู่'],
                ColumnsSize     : ['15%', '75%'],
                WidthModal      : 50,
                DataColumns     : ['TCNMPdtCatInfo.FTCatCode', 'TCNMPdtCatInfo_L.FTCatName'],
                DataColumnsFormat: ['', ''],
                // DisabledColumns : [],
                Perpage         : 10,
                OrderBy         : ['TCNMPdtCatInfo.FDCreateOn DESC']
            },
            CallBack:{
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMPdtCatInfo.FTCatCode"],
                Text        : [tInputReturnName, "TCNMPdtCatInfo_L.FTCatName"]
            },
            // NextFunc:{
            //     FuncName    : tInputNextFunc,
            //     ArgReturn   : ['FTCatCode', 'FTCatName']
            // },
        }
        return oOptionReturn;
    };

</script>