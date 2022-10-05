<style>
    .xWEditInlineElement {
        text-align: right !important;
    }
</style>

<?php
if (isset($aDataUnitPackSize)) {
    $tPdtPriceRet   =   "";
    $tPdtPriceWhs   =   "";
    $tPdtPriceNet   =   "";
} else {
    $tPdtPriceRet   =   0;
    $tPdtPriceWhs   =   0;
    $tPdtPriceNet   =   0;
}
?>
<?php
$tPunCode = "";
$tPunName = "";
$tBarCode = $tDataBarCode;
if (isset($aDataPun['raItems'][0]['FTPunName'])) {
    $tPunName = $aDataPun['raItems'][0]['FTPunName'];
    $tPunCode = $aDataPun['raItems'][0]['FTPunCode'];
}
// print_r($aDataUnitPackSize['raItems']);
?>
<input type="hidden" id="ohdPdtSupplierPunCode" class="form-control" value="<?php echo $tPunCode; ?>">
<input type="hidden" id="ohdPdtSupplierPunName" class="form-control" value="<?php echo $tPunName; ?>">
<input type="hidden" id="ohdPdtSupplierBarCode" class="form-control" value="<?php echo $tBarCode; ?>">

<?php
//Decimal Show ลง 2 ตำแหน่ง
$nDecShow =  FCNxHGetOptionDecimalShow();
?>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 p-b-10">
    <span style="font-weight: bold;"><?php echo language('product/product/product', 'tPDTTabPackSizeUnit') ?></span><span> : <?php echo $tPunName ?></span><span style="font-weight: bold;">&nbsp;&nbsp;&nbsp;<?php echo language('product/product/product', 'tPDTViewPackMDSplBarCode') ?></span><span> <?php echo $tBarCode ?></span>
</div>
<div id="odvPdtNormalBarAdd" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 p-b-10 text-right">
    <button id="obtAddProductUnit" onclick="JSxPdtCallModalAddEditUnitSupplier()" class="xCNBTNPrimeryPlus" type="button">+</button>
</div>
<table class="table xWTablePackSize" id="otbTableSuppliere">
    <thead>
        <tr>
            <th nowrap class="text-center xCNTextBold" style="width:30%;"><?php echo language('product/product/product', 'tPDTHisPISupplier') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:15%;"><?php echo language('product/product/product', 'tPdtTabNormalPersonRespond') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:15%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowDay1') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowDay2') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowDay3') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowDay4') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowDay5') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowDay6') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowDay7') ?></th>

            <?php if ($aAlwEventPdt['tAutStaFull'] == 1 || $aAlwEventPdt['tAutStaDelete'] == 1) : ?>
                <th nowrap colspan="2" class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPdtTabNormalManage') ?></th>
            <?php endif; ?>
            <!-- <th class="xWDeleteBtnEditButton"></th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($aDataUnitPackSize['raItems'])) {
            foreach ($aDataUnitPackSize['raItems'] as $nKey => $aValue) {
        ?>
        <?php
            $tCheckDay1     = '';
            $tCheckDay2     = '';
            $tCheckDay3     = '';
            $tCheckDay4     = '';
            $tCheckDay5     = '';
            $tCheckDay6     = '';
            $tCheckDay7     = '';
            if ($aValue['FTPdtStaAlwOrdMon'] == '1') {
                $tCheckDay1 = 'checked';
            }
            if ($aValue['FTPdtStaAlwOrdTue'] == '1') {
                $tCheckDay2 = 'checked';
            }
            if ($aValue['FTPdtStaAlwOrdWed'] == '1') {
                $tCheckDay3 = 'checked';
            }
            if ($aValue['FTPdtStaAlwOrdThu'] == '1') {
                $tCheckDay4 = 'checked';
            }
            if ($aValue['FTPdtStaAlwOrdFri'] == '1') {
                $tCheckDay5 = 'checked';
            }
            if ($aValue['FTPdtStaAlwOrdSat'] == '1') {
                $tCheckDay6 = 'checked';
            }
            if ($aValue['FTPdtStaAlwOrdSun'] == '1') {
                $tCheckDay7 = 'checked';
            }
        ?>
                <tr id="otrSplDataUnitRow<?php echo $aValue['FTSplCode']; ?>" class="xWVenPackSizeRow xWSplDataUnitRow xWCheckNormalBarcodePdt" data-splcode="<?php echo $aValue['FTSplCode'] ?>" data-barcode="<?php echo $tBarCode ?>" data-puncode="<?php echo $tPunCode; ?>" data-punname="<?php echo $tPunName; ?>" data-pdtcode="<?php echo $aValue['FTPdtCode']; ?>">
                    <td nowrap class="xCNBorderRight">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                            <input type="hidden" class="form-control xWSplSplName" id="ohdSplSplNameRow<?php echo $aValue['FTSplCode']; ?>" value="<?php echo $aValue['FTSplName']; ?>">
                            <input type="hidden" class="form-control xWSplUsrName" id="ohdSplUsrNameRow<?php echo $aValue['FTSplCode']; ?>" value="<?php echo $aValue['FTUsrName']; ?>">
                            <input type="hidden" class="form-control xWSplUsrCode" id="ohdSplUsrCodeRow<?php echo $aValue['FTSplCode']; ?>" value="<?php echo $aValue['FTUsrCode']; ?>">

                            <input type="hidden" class="form-control xWSplStaDay1" id="ohdSplStaDay1Row<?php echo $aValue['FTSplCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwOrdMon']; ?>">
                            <input type="hidden" class="form-control xWSplStaDay2" id="ohdSplStaDay2Row<?php echo $aValue['FTSplCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwOrdTue']; ?>">
                            <input type="hidden" class="form-control xWSplStaDay3" id="ohdSplStaDay3Row<?php echo $aValue['FTSplCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwOrdWed']; ?>">
                            <input type="hidden" class="form-control xWSplStaDay4" id="ohdSplStaDay4Row<?php echo $aValue['FTSplCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwOrdThu']; ?>">
                            <input type="hidden" class="form-control xWSplStaDay5" id="ohdSplStaDay5Row<?php echo $aValue['FTSplCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwOrdFri']; ?>">
                            <input type="hidden" class="form-control xWSplStaDay6" id="ohdSplStaDay6Row<?php echo $aValue['FTSplCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwOrdSat']; ?>">
                            <input type="hidden" class="form-control xWSplStaDay7" id="ohdSplStaDay7Row<?php echo $aValue['FTSplCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwOrdSun']; ?>">

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                    <label><?php echo $aValue['FTSplName']; ?></label>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td nowrap class="text-left">
                        <label><?php echo $aValue['FTUsrName']; ?></label>
                    </td>
                    <td nowrap class="xWTdSplPriceRet text-center">
                        <input disabled <?php echo $tCheckDay1 ?> type="checkbox" id="ocbSplStaDay1" name="ocbSplStaDay1">
                    </td>
                    <td nowrap class="xWTdSplPriceRet text-center">
                        <input disabled <?php echo $tCheckDay2 ?> type="checkbox" id="ocbSplStaDay2" name="ocbSplStaDay2">
                    </td>
                    <td nowrap class="xWTdSplPriceRet text-center">
                        <input disabled <?php echo $tCheckDay3 ?> type="checkbox" id="ocbSplStaDay3" name="ocbSplStaDay3">
                    </td>
                    <td nowrap class="xWTdSplPriceRet text-center">
                        <input disabled <?php echo $tCheckDay4 ?> type="checkbox" id="ocbSplStaDay4" name="ocbSplStaDay4">
                    </td>
                    <td nowrap class="xWTdSplPriceRet text-center">
                        <input disabled <?php echo $tCheckDay5 ?> type="checkbox" id="ocbSplStaDay5" name="ocbSplStaDay5">
                    </td>
                    <td nowrap class="xWTdSplPriceRet text-center">
                        <input disabled <?php echo $tCheckDay6 ?> type="checkbox" id="ocbSplStaDay6" name="ocbSplStaDay6">
                    </td>
                    <td nowrap class="xWTdSplPriceRet text-center">
                        <input disabled <?php echo $tCheckDay7 ?> type="checkbox" id="ocbSplStaDay7" name="ocbSplStaDay7">
                    </td>

                    <?php if ($aAlwEventPdt['tAutStaFull'] == 1 || $aAlwEventPdt['tAutStaDelete'] == 1) : ?>
                        <td nowrap class="text-center">
                            <img onclick="JSxPdtDelSupplierUnitInTableTmp(1,'<?= $aValue['FTSplCode']; ?>')" class="xCNIconTable xWPdtDelUnitPackSize" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>">
                        </td>
                        <td nowrap class="text-center">
                            <img onclick="JSxPdtEditSupplierInTable(this)" class="xCNIconTable xCNIconEdit" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>">
                        </td>
                    <?php endif; ?>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td nowrap colspan="10" class="text-center xWTextNotfoundDataTablePdt"><?php echo language('product/product/product', 'tPDTViewPackMDMsgSplSupCodeNotFound') ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<script>
    // $(".xWCheckNormalBarcodePdt").unbind().click(function(){
    // $(".xWCheckNormalBarcodePdt").each(function (indexInArray, valueOfElement) { 
    //     $(this).remove("active");
    // });
    // $(this).addClass("active");
    // $("#oliPdtContentNormalVendor a").attr('data-toggle','tab');

    // var tPuncode = $(this).data('puncode');
    // var nStaSession = JCNxFuncChkSessionExpired();
    // if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
    //     if (tPuncode == "" || tPuncode === undefined) { tPuncode = ''; }
    //     $.ajax({
    //         type: "POST",
    //         url: "productGetNormalVendor",
    //         data: { tPuncode: tPuncode }, //tPuncode : tPuncode
    //         cache: false,
    //         timeout: 0,
    //         async: false,
    //         success: function(tResult) {
    //             $('#odvPdtNormalTable3').html(tResult);

    //             JCNxLayoutControll();
    //             JCNxCloseLoading();
    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             JCNxResponseError(jqXHR, textStatus, errorThrown);
    //         }
    //     });
    // } else {
    //     JCNxShowMsgSessionExpired();
    // }
    // });
</script>