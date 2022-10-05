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
if(isset($aDataPun['raItems'][0]['FTPunName'])){
    $tPunName = $aDataPun['raItems'][0]['FTPunName'];
    $tPunCode = $aDataPun['raItems'][0]['FTPunCode'];
}
?>
<input type="hidden" id="ohdPdtUnitCode" class="form-control" value="<?php echo $tPunCode; ?>">
<input type="hidden" id="ohdPdtUnitName" class="form-control" value="<?php echo $tPunName; ?>">
<input type="hidden" id="ohdPdtBarBarName" class="form-control" value="<?php echo $tPunName; ?>">
<input type="hidden" id="ohdPdtBarBarCode" class="form-control" value="<?php echo $tPunCode; ?>">

<?php
//Decimal Show ลง 2 ตำแหน่ง
$nDecShow =  FCNxHGetOptionDecimalShow();
?>

<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 p-b-10">
    <span style="font-weight: bold;"><?php echo language('product/product/product', 'tPDTTabPackSizeUnit') ?></span><span> : <?php echo $tPunName ?></span>
</div>
<div id="odvPdtNormalBarAdd" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 p-b-10 text-right" style="margin-top:-10px;">
    <button id="obtAddProductBarcode" onclick="JSxPdtCallModalAddEditUnitBarcode()" class="xCNBTNPrimeryPlus" type="button">+</button>
</div>
<table class="table xWTablePackSize" id="otbTableBarCode">
    <thead>
        <tr>
            <th nowrap class="text-center xCNTextBold" style="width:30%;"><?php echo language('product/product/product', 'tPDTViewPackBarcode') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:15%;"><?php echo language('product/product/product', 'tPdtTabNormalKeep') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:15%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowSale') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tFhnPdtDataTableUse1') ?></th>

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
                $tCheckUsed     = '';
                $tCheckSale     = '';
                if ($aValue['FTBarStaAlwSale'] == '1') {
                    $tCheckSale = 'checked';
                }
                if ($aValue['FTBarStaUse'] == '1') {
                    $tCheckUsed = 'checked';
                }
                ?>
                <tr id="otrPdtDataUnitRow<?php echo $aValue['FTBarCode']; ?>" class="xWBarPackSizeRow xWChkBarActive xWPdtDataUnitRow xWCustomActive xWCheckNormalBarcodePdt" data-barcode="<?php echo $aValue['FTBarCode'] ?>" data-puncode="<?php echo $tPunCode ?>" data-punname="<?php echo $tPunName; ?>" data-pdtcode="<?php echo $aValue['FTPdtCode']; ?>">
                    <td nowrap class="xCNBorderRight">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                            <!-- <input type="hidden" class="form-control xWPdtBarCode" id="ohdPdtBarCodeRow<?php echo $aValue['FTBarCode']; ?>" value="<?php echo $aValue['nCountBarCode']; ?>"> -->
                            <input type="hidden" class="form-control xWBarPunCode" id="ohdBarPunCodeRow<?php echo $aValue['FTBarCode']; ?>" value="<?php echo $tPunCode; ?>">
                            <input type="hidden" class="form-control xWBarBarCode" id="ohdBarBarCodeRow<?php echo $aValue['FTBarCode']; ?>" value="<?php echo $aValue['FTBarCode']; ?>">
                            <input type="hidden" class="form-control xWBarPlcCode" id="ohdBarPlcCodeRow<?php echo $aValue['FTBarCode']; ?>" value="<?php echo $aValue['FTPlcCode']; ?>">
                            <input type="hidden" class="form-control xWBarPlcName" id="ohdBarPlcNameRow<?php echo $aValue['FTBarCode']; ?>" value="<?php echo $aValue['FTPlcName']; ?>">
                            <input type="hidden" class="form-control xWBarStaUse" id="ohdBarStaUseRow<?php echo $aValue['FTBarCode']; ?>" value="<?php echo $aValue['FTBarStaUse']; ?>">
                            <input type="hidden" class="form-control xBarStaAlwSale" id="ohdBarStaAlwSaleRow<?php echo $aValue['FTBarCode']; ?>" value="<?php echo $aValue['FTBarStaAlwSale']; ?>">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                    <label><?php echo $aValue['FTBarCode']; ?></label>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td nowrap class="text-center">
                        <label><?php echo $aValue['FTPlcName']; ?></label>
                    </td>
                    <td nowrap class="xWTdPdtPriceRet text-center">
                        <input disabled <?php echo $tCheckSale ?> type="checkbox" id="ocbBarChkSale" name="ocbBarChkSale">
                    </td>
                    <td nowrap class="xWTdPdtPriceRet text-center">
                        <input disabled <?php echo $tCheckUsed ?> type="checkbox" id="ocbBarChkUse" name="ocbBarChkUse">
                    </td>

                    <?php if ($aAlwEventPdt['tAutStaFull'] == 1 || $aAlwEventPdt['tAutStaDelete'] == 1) : ?>
                        <td nowrap class="text-center">
                            <img onclick="JSxPdtDelBarCodeUnitInTableTmp(1,'<?= $aValue['FTBarCode']; ?>')" class="xCNIconTable xWPdtDelUnitPackSize" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>">
                        </td>
                        <td nowrap class="text-center">
                            <img onclick="JSxPdtEditBarcodeInTable(this)" class="xCNIconTable xCNIconEdit" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>">
                        </td>
                    <?php endif; ?>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td nowrap colspan="7" class="text-center xWTextNotfoundDataTablePdt"><?php echo language('product/product/product', 'tPDTViewPackMDMsgSplBarCodeNotFound') ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<script>
    $(".xWCheckNormalBarcodePdt").unbind().click(function() {
        $(".xWCheckNormalBarcodePdt").each(function(indexInArray, valueOfElement) {
            $(this).removeClass("actived");
        });
        $(this).addClass("actived");
        $("#oliPdtContentNormalVendor a").attr('data-toggle', 'tab');

        var tPuncode = $(this).data('puncode');
        var tPdtCode = $("#oetPdtCode").val();
        var tBarCode = $(this).data('barcode');
        
        JsxCallNormalVendorDataTable(tPdtCode,tPuncode,tBarCode);
    });
</script>