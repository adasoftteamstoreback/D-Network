<?php
/* HD INFO */
$tXshDocNo         = (empty($aDataDocHD['FTXshDocNo']) ? '-' : $aDataDocHD['FTXshDocNo']);
$tAppName          = (empty($aDataDocHD['FTAppName']) ? '-' : $aDataDocHD['FTAppName']);
$nStaDocAct        = $aDataDocHD['FNXshStaDocAct'];
$dXshDocDate       = (empty($aDataDocHD['FDXshDocDate']) ? '-' : $aDataDocHD['FDXshDocDate']);
$tXshDocTime       = (empty($aDataDocHD['FTXshDocTime']) ? '-' : $aDataDocHD['FTXshDocTime']);
$tXshStaApv        = $aDataDocHD['FTXshStaApv'];
$tCreateBy         = (empty($aDataDocHD['FTCreateBy']) ? '-' : $aDataDocHD['FTCreateBy']);
$tApvName          = (empty($aDataDocHD['FTApvName']) ? '-' : $aDataDocHD['FTApvName']);
$tBchCode          = (empty($aDataDocHD['FTBchCode']) ? '-' : $aDataDocHD['FTBchCode']);
$tBchName          = (empty($aDataDocHD['FTBchName']) ? '-' : $aDataDocHD['FTBchName']);
$tAgnCode          = (empty($aDataDocHD['FTAgnCode']) ? '-' : $aDataDocHD['FTAgnCode']);
$tAgnName          = (empty($aDataDocHD['FTAgnName']) ? '-' : $aDataDocHD['FTAgnName']);
$tDocVatFull       = (empty($aDataDocHD['FTXshDocVatFull']) ? '' : $aDataDocHD['FTXshDocVatFull']);
$tXshVATInOrEx     = (empty($aDataDocHD['FTXshVATInOrEx']) ? '' : $aDataDocHD['FTXshVATInOrEx']);
$tXshStaPrcDoc     = $aDataDocHD['FTXshStaPrcDoc'];
$tChnName          = (empty($aDataDocHD['FTChnName']) ? '' : $aDataDocHD['FTChnName']);
$tXshETaxStatus    = $aDataDocHD['FTXshETaxStatus'];
$tUsrCreateName    = (empty($aDataDocHD['FTUsrCreateName']) ? '' : $aDataDocHD['FTUsrCreateName']);
$nXshDocType       = $aDataDocHD['FNXshDocType'];
$tXshStaDoc       = $aDataDocHD['FTXshStaDoc'];


/* CST INFO */
$tCstCode          = (empty($aDataDocHD['FTCstCode']) ? '-' : $aDataDocHD['FTCstCode']);
$tCstName          = (empty($aDataDocHD['FTCstName']) ? '-' : $aDataDocHD['FTCstName']);
//  $tCstTaxNo         = (empty($aDataDocHD['FTCstTaxNo']) ? '-' : $aDataDocHD['FTCstTaxNo']);
$tCstTel           = (empty($aDataDocHD['FTCstTel']) ? '-' : $aDataDocHD['FTCstTel']);
$tCstEmail         = (empty($aDataDocHD['FTCstEmail']) ? '-' : $aDataDocHD['FTCstEmail']);

/* DOC REF INFO */
$tXshRefInt        = (empty($aDataDocHD['FTXshRefInt']) ? '-' : $aDataDocHD['FTXshRefInt']);
$tXshRefIntDate    = (empty($aDataDocHD['FDXshRefIntDate']) ? '-' : $aDataDocHD['FDXshRefIntDate']);
$tXshRefExt        = (empty($aDataDocHD['FTXshRefExt']) ? '-' : $aDataDocHD['FTXshRefExt']);
$tXshRefExtDate    = (empty($aDataDocHD['FDXshRefExtDate']) ? '-' : $aDataDocHD['FDXshRefExtDate']);

/* ANOTHER INFO */
$tXshRmk           = (empty($aDataDocHD['FTXshRmk']) ? '-' : $aDataDocHD['FTXshRmk']);

if ($nXshDocType == '1') {
    $tXshDocType = '';
} else {
    $tXshDocType = 'CN';
}

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
<form id="ofmTransferreceiptFormAdd" class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <input type="hidden" id="ohdABBXshDocVatFull" name="ohdABBXshDocVatFull" value="<?= $tDocVatFull ?>">
    <input type="hidden" id="ohdABBStaFirstEnter" name="ohdABBStaFirstEnter" value="1">

    <input type="hidden" id="ohdABBXshStaApv" name="ohdABBXshStaApv" value="<?= $tXshStaApv ?>">
    <input type="hidden" id="ohdABBBchCode" name="ohdABBBchCode" value="<?= $tBchCode ?>">
    <input type="hidden" id="ohdABBUsrLogin" name="ohdABBUsrLogin" value="<?= $this->session->userdata("tSesUserCode") ?>">
    <input type="hidden" id="ohdCSSXshStaPrcDoc" name="ohdCSSXshStaPrcDoc" value="<?= $tXshStaPrcDoc ?>">

    <div class="row">
        <div class="xWLeft col-xs-12 col-sm-3 col-md-3 col-lg-3" id="odvSideBar">
            <!-- Class xWLeft กับ id odvSideBar  ใช้ในการควบคุม เปิดปิด Side Bar  -->

            <!-- Panel ข้อมูลเอกสาร -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocInfoTitle'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvABBDocumentInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                    <!-- ** ========================== Start ปุ่ม ปิด Side Bar =============================================== * -->
                    <button onclick="JCNxCloseDiv()" class="xCNButtonSideBar">
                        <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                    </button>
                    <!-- ** ========================== End ปุ่ม ปิด Side Bar =============================================== * -->
                </div>
                <div id="odvABBDocumentInfo" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBApp'); ?></strong></label>
                            <div class="col-md-8"><?= $tAppName ?></div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocNo'); ?></strong></label>
                            <div id="odvABBDocNo" class="col-md-8"><?= $tXshDocNo ?></div>
                        </div>

                        <!-- <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocType'); ?></strong></label>
                            <div class="col-md-8">เอกสารขาย</div>
                        </div> -->

                        <!-- <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBChannel'); ?></strong></label>
                            <div class="col-md-8"><?= $tChnName ?></div>
                        </div> -->

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocDate'); ?></strong></label>
                            <div class="col-md-8"><?= date_format(date_create($dXshDocDate), 'd/m/Y') ?> <?= $tXshDocTime ?></div>
                        </div>

                        <?php

                        if ($tXshStaDoc == '1') {
                            $tDivClass  = "xWABBGreenBG";
                            $tSpanClass = "xWABBGreenColor";
                            $tLabel     = "สมบูรณ์";
                        } else if ($tXshStaDoc == '2') {
                            $tDivClass  = "xWABBYellowBG";
                            $tSpanClass = "xWABBYellowColor";
                            $tLabel     = "ไม่สมบูรณ์";
                        } else if ($tXshStaDoc == '3') {
                            $tDivClass  = "xWABBRedBG";
                            $tSpanClass = "xWABBRedColor";
                            $tLabel     = "ยกเลิก";
                        } else {
                            $tDivClass  = "xWABBYellowBG";
                            $tSpanClass = "xWABBYellowColor";
                            $tLabel     = "ยังไม่ทำ";
                        }
                        ?>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBStaApv'); ?></strong></label>
                            <div class="col-md-8"> <span class="xWABBStatusColor <?php echo $tSpanClass; ?> "><?php echo $tLabel; ?></span></div>
                        </div>

                        <!-- <div class=" mb-3 row">
                                    <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocTime'); ?></strong></label>
                                    <div class="col-md-8"><?= $tXshDocTime ?></div>
                            </div> -->

                        <!-- <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBStaApv'); ?></strong></label>
                            <div class="col-md-8">
                                <?php
                                switch ($tXshStaPrcDoc) {
                                    case '5':
                                        $tDivClass  = "xWABBGreenBG";
                                        $tSpanClass = "xWABBGreenColor";
                                        $tLabel     = "ยืนยันจัดส่ง";
                                        break;
                                    case '4':
                                        $tDivClass  = "xWABBYellowBG";
                                        $tSpanClass = "xWABBYellowColor";
                                        $tLabel     = "รอลูกค้ามารับ";
                                        break;
                                    case '3':
                                        $tDivClass  = "xWABBYellowBG";
                                        $tSpanClass = "xWABBYellowColor";
                                        $tLabel     = "รอจัดส่ง";
                                        break;
                                    case '2':
                                        $tDivClass  = "xWABBYellowBG";
                                        $tSpanClass = "xWABBYellowColor";
                                        $tLabel     = "สร้างใบจัด";
                                        break;
                                    default:
                                        $tDivClass  = "xWABBGrayBG";
                                        $tSpanClass = "xWABBGrayColor";
                                        $tLabel     = "รอจัดสินค้า";
                                }
                                echo '<div class="xWABBDotStatus ' . $tDivClass . '"></div> <span class="xWABBStatusColor ' . $tSpanClass . '">' . $tLabel . '</span>';
                                ?>
                            </div>
                        </div> -->

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBTaxType'); ?></strong></label>
                            <div class="col-md-8"><?= language('document/abbsalerefund/abbsalerefund', 'tABBVATInOrEx' . $tXshVATInOrEx); ?></div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBUsrCreate'); ?></strong></label>
                            <div class="col-md-8"><?= $tUsrCreateName ?></div>
                        </div>

                        <!-- <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBUsrApv'); ?></strong></label>
                            <div class="col-md-8"><?= $tApvName ?></div>
                        </div> -->

                        <hr style='margin: 5px;'>

                        <div class="mb-3 row <?php if (!FCNbGetIsAgnEnabled()) : echo 'xCNHide';
                                                endif; ?>">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBBrowseAgnTitle'); ?></strong></label>
                            <div class="col-md-8">
                                <?php
                                if ($tAgnCode != "-") {
                                    echo "(" . $tAgnCode . ") " . $tAgnName;
                                } else {
                                    echo "-";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBBrowseBchTitle'); ?></strong></label>
                            <div class="col-md-8">(<?= $tBchCode ?>) <?= $tBchName ?></div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Panel ข้อมูลเอกสาร -->

            <!-- Panel ข้อมูลลูกค้า -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/abbsalerefund/abbsalerefund', 'tABBCustomerTitle'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvABBCustomerInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvABBCustomerInfo" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBCstCode'); ?></strong></label>
                            <div class="col-md-8"><?= $tCstCode ?></div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBCstName'); ?></strong></label>
                            <div class="col-md-8"><?= $tCstName ?></div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBCstTel'); ?></strong></label>
                            <div class="col-md-8"><?= $tCstTel ?></div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBCstEmail'); ?></strong></label>
                            <div class="col-md-8"><?= $tCstEmail ?></div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Panel ข้อมูลลูกค้า -->

            <!-- Panel อ้างอิงเอกสาร -->
            <!-- <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocRefTitle'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvABBDocRefInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvABBDocRefInfo" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">

                        <div class="mb-3 row">
                            <label class="col-md-12 col-form-label" style="margin-bottom: 0px;"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocRefIn'); ?></strong></label>
                            <div class="col-md-12" style='padding-left:25px;padding-right:25px;'><?= $tXshRefInt ?></div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-12 col-form-label" style="margin-bottom: 0px;"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocDateRefIn'); ?></strong></label>
                            <div class="col-md-12" style='padding-left:25px;padding-right:25px;'><?= $tXshRefIntDate ?></div>
                        </div>

                        <hr style='margin: 5px;'>

                        <div class="mb-3 row">
                            <label class="col-md-12 col-form-label" style="margin-bottom: 0px;"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocRefOut'); ?></strong></label>
                            <div class="col-md-12" style='padding-left:25px;padding-right:25px;'><?= $tXshRefExt ?></div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-12 col-form-label" style="margin-bottom: 0px;"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBDocDateRefOut'); ?></strong></label>
                            <div class="col-md-12" style='padding-left:25px;padding-right:25px;'><?= $tXshRefExtDate ?></div>
                        </div>

                    </div>
                </div>
            </div> -->
            <!-- End Panel อ้างอิงเอกสาร -->

            <!-- Panel อื่นๆ -->
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?= language('document/abbsalerefund/abbsalerefund', 'tABBAnotherTitle'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvABBAnotherInfo" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvABBAnotherInfo" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">

                        <div class="mb-3 row">
                            <label class="col-md-12 col-form-label" style="margin-bottom: 0px;"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBRemark'); ?></strong></label>
                            <div class="col-md-12" style='padding-left:25px;padding-right:25px;'><?= $tXshRmk ?></div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Panel อื่นๆ -->

        </div>



        <div class="xWRight col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <!-- Class xWRight ใช้ในการควบคุม เปิดปิด Side Bar  -->
            <div class="row">
                <!-- ตารางสินค้า -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom:25px;position:relative;min-height:200px;">
                        <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                            <div class="panel-body">
                                <div style="margin-top: 10px;">

                                    <!-- ค้นหา -->
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                                            <div class="form-group">
                                                <label class="xCNLabelFrm"><?= language('common/main/main', 'tSearchNew'); ?></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetABBFilterPdt" name="oetABBFilterPdt" onkeyup="JSvABBDOCSearchPdtHTML()" autocomplete="off" placeholder="<?= language('document/document/document', 'tDocSearchPlaceHolder'); ?>">
                                                    <span class="input-group-btn">
                                                        <button class="btn xCNBtnSearch" type="button" onclick="JSvDOCSearchPdtHTML()">
                                                            <img class="xCNIconAddOn" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- End ค้นหา -->

                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right"></div>
                                    </div>

                                    <!-- แสดงรายการสินค้า -->
                                    <div class="row p-t-10" id="odvABBProductDataTableContent"></div>
                                    <!-- END แสดงรายการสินค้า -->

                                    <?php include('wABBSaleRefundEndOfBill.php'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- จบตารางสินค้า -->

                <!-- Panel การชำระเงิน -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom: 25px;">
                        <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <label class="xCNTextDetail1"><?= language('document/abbsalerefund/abbsalerefund', 'tABBRevTitle'); ?></label>
                        </div>
                        <div id="odvCSSReceiveInfo" class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table id="otbCSSPdtTableList" class="table xWPdtTableFont">
                                        <thead>
                                            <tr class="xCNCenter xCNPanelHeadColorWhite">
                                                <th nowrap style="color: #232C3D !important;"><?= language('document/document/document', 'tDocNumber') ?></th>
                                                <th nowrap style="color: #232C3D !important;"><?= language('document/abbsalerefund/abbsalerefund', 'tABBTableRcvList') ?></th>
                                                <th nowrap style="color: #232C3D !important;"><?= language('document/abbsalerefund/abbsalerefund', 'tABBTableRcvRef') ?></th>
                                                <th nowrap style="color: #232C3D !important;"><?= language('document/document/document', 'tDocPdtQty') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($aDataDocRC['tCode'] == '1') {
                                                $cXrcNetSum = 0;
                                                foreach ($aDataDocRC['aItems'] as $nDataTableKey => $aDataVal) {
                                                    $cXrcNetSum += $aDataVal['FCXrcNet'];
                                            ?>
                                                    <tr class="text-center xCNTextDetail2 xWPdtItem">
                                                        <td nowrap style="color: #232C3D !important;" class="text-center"><?= ($nDataTableKey + 1) ?></td>
                                                        <td nowrap style="color: #232C3D !important;" class="text-left"><?= "(" . $aDataVal['FTRcvCode'] . ") " . $aDataVal['FTRcvName']; ?></td>
                                                        <td nowrap style="color: #232C3D !important;" class="text-left"><?= $aDataVal['FTXrcRefNo1'] ?></td>
                                                        <td nowrap style="color: #232C3D !important;" class="text-right"><?= number_format($aDataVal['FCXrcNet'], $nOptDecimalShow) ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                                <tr class="text-center xCNTextDetail2 xWPdtItem">
                                                    <td nowrap style="color: #232C3D !important;" class="text-right" colspan="3"><strong><?= language('document/abbsalerefund/abbsalerefund', 'tABBTableRcvTotal') ?></strong></td>
                                                    <td nowrap style="color: #232C3D !important;" class="text-right"><strong><?= number_format($cXrcNetSum, $nOptDecimalShow) ?></strong></td>
                                                </tr>
                                            <?php
                                            } else {
                                            ?>
                                                <tr>
                                                    <td class="text-center xCNTextDetail2 xWTWITextNotfoundDataPdtTable" colspan="100%"><?= language('common/main/main', 'tCMNNotFoundData') ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Panel การชำระเงิน -->

                <!-- Panel การอ้างอิงเอกสาร -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default" style="margin-bottom: 25px;">
                        <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <label class="xCNTextDetail1"><?= language('document/abbsalerefund/abbsalerefund', 'tABBTitleHDDocRef'); ?></label>
                        </div>
                        <div id="odvCSSReceiveInfo" class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">

                                <div class="table-responsive" style="max-height: 260px;">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="xCNCenter xCNPanelHeadColorWhite">
                                                <th nowrap style="color: #232C3D !important;" class="xCNTextBold" style="width:15%;"><?= language('document/taxinvoice/taxinvoice', 'tABBTitleRefType') ?></th>
                                                <th nowrap style="color: #232C3D !important;" class="xCNTextBold"><?= language('document/taxinvoice/taxinvoice', 'tABBTitleRefDocNo') ?></th>
                                                <th nowrap style="color: #232C3D !important;" class="xCNTextBold" style="width:15%;"><?= language('document/taxinvoice/taxinvoice', 'tABBTitleRefDocDate') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($aDataDocRef['tCode'] == '1') { ?>
                                                <?php foreach ($aDataDocRef['aItems'] as $aValue) { ?>
                                                    <tr class="xCNTextDetail2">
                                                        <td class="text-left"><label><?= language('document/taxinvoice/taxinvoice', 'tABBRefType' . $aValue['FTXshRefType']) ?></label></td>
                                                        <td class="text-left"><label><?= $aValue['FTXshRefDocNo'] ?></label></td>
                                                        <td class="text-center"><label><?= date_format(date_create($aValue['FDXshRefDocDate']), 'Y-m-d') ?></label></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr class="xCNTextDetail2">
                                                    <td class="text-center" colspan="100%"><label><?= language('common/main/main', 'tCMNNotFoundData') ?></label></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Panel การอ้างอิงเอกสาร -->

            </div>
        </div>

    </div>

</form>

<!-- ============================================ Modal Enter S/N ============================================ -->
<div id="odvABBModalChkPdtSN" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="z-index: 7000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?= language('document/abbsalerefund/abbsalerefund', 'tABBSerialTitle') ?></label>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('document/abbsalerefund/abbsalerefund', 'tABBScanBarCode') ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetABBScanBarCode" name="oetABBScanBarCode" onkeyup="Javascript:if(event.keyCode==13) JSxABBEventScanPdtSN()" autocomplete="off" placeholder="<?php echo language('document/abbsalerefund/abbsalerefund', 'tABBScanBarCode'); ?>">
                                <span class="input-group-btn">
                                    <button class="btn xCNBtnSearch" type="button" onclick="JSxABBEventScanPdtSN()">
                                        <img class="xCNIconAddOn" src="<?php echo base_url() . '/application/modules/common/assets/images/icons/search-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <hr>

                    </div>

                    <div class="col-xs-12 col-md-12 col-lg-12">
                        <div id="odvABBPdtSNList"></div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-7">
                        <div id="odvABBCountPdtSN" class="text-left"></div>
                    </div>
                    <div class="col-md-5">
                        <button id="obtABBConfirmChkPdtSN" type="button" class="btn xCNBTNPrimery"><?php echo language('common/main/main', 'tModalConfirm'); ?></button>
                        <button id="obtABBCancelChkPdtSN" type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?php echo language('common/main/main', 'tCancel'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================================= -->

<!-- ================================================================= View Modal Appove Document ================================================================= -->
<div id="odvABBModalAppoveDoc" class="modal fade xCNModalApprove">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?= language('common/main/main', 'tApproveTheDocument'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?= language('common/main/main', 'tMainApproveStatus'); ?></p>
                <ul>
                    <li><?= language('common/main/main', 'tMainApproveStatus1'); ?></li>
                    <li><?= language('common/main/main', 'tMainApproveStatus2'); ?></li>
                    <li><?= language('common/main/main', 'tMainApproveStatus3'); ?></li>
                    <li><?= language('common/main/main', 'tMainApproveStatus4'); ?></li>
                </ul>
                <p><?= language('common/main/main', 'tMainApproveStatus5'); ?></p>
                <p><strong><?= language('common/main/main', 'tMainApproveStatus6'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button id="obtABBConfirmApvDoc" type="button" class="btn xCNBTNPrimery"><?= language('common/main/main', 'tModalConfirm'); ?></button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?= language('common/main/main', 'tModalCancel'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================================================================================================================== -->

<?php include('script/jABBSaleRefundPageAdd.php'); ?>