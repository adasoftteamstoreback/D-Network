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
$tPunCode   = "";
$tPunName   = "";
if (isset($aDataUnitPackSize['raItems'])) {
    foreach ($aDataUnitPackSize['raItems'] as $nKey => $aValue) {
        if ($tPunCode == "") {
            $tPunCode = $aValue['FTPunCode'];
            $tPunName = $aValue['FTPunName'];
        } else {
            $tPunCode = $tPunCode . "," . $aValue['FTPunCode'];
            $tPunName = $tPunName . "," . $aValue['FTPunName'];
        }
    }
}
?>
<input type="hidden" id="ohdPdtUnitCode" class="form-control" value="<?php echo $tPunCode; ?>">
<input type="hidden" id="ohdPdtUnitName" class="form-control" value="<?php echo $tPunName; ?>">

<?php
//Decimal Show ลง 2 ตำแหน่ง
$nDecShow =  FCNxHGetOptionDecimalShow();
?>
<table class="table xWTablePackSize" id="otbTableVendor">
    <thead>
        <tr>
            <th nowrap class="text-center xCNTextBold" style="width:30%;"><?php echo language('product/product/product', 'tPDTHisPIUnit') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:15%;"><?php echo language('product/product/product', 'tPDTUnitFact') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:5%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowBch') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:5%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowVendor') ?></th>
            <!-- <th nowrap class="text-center xCNTextBold" style="width:5%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowBuy') ?></th> -->
            <th nowrap class="text-center xCNTextBold" style="width:5%;"><?php echo language('product/product/product', 'tPdtTabNormalAllowSale') ?></th>
            <th nowrap class="text-center xCNTextBold" style="width:5%;"><?php echo language('product/product/product', 'tPdtTabNormalDefrag') ?></th>

            <?php if ($aAlwEventPdt['tAutStaFull'] == 1 || $aAlwEventPdt['tAutStaDelete'] == 1) : ?>
                <th nowrap colspan="2" class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product', 'tPdtTabNormalManage') ?></th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($aDataUnitPackSize['raItems'])) {
            foreach ($aDataUnitPackSize['raItems'] as $nKey => $aValue) {
                $tCheckHQ   = '';
                $tCheckBuy  = '';
                $tCheckSpl  = '';
                $tCheckSale = '';
                $tCheckPick = '';
                if ($aValue['FTPdtStaAlwPoHQ'] == '1') {
                    $tCheckHQ = 'checked';
                }
                if ($aValue['FTPdtStaAlwBuy'] == '1') {
                    $tCheckBuy = 'checked';
                }
                if ($aValue['FTPdtStaAlwPoSPL'] == '1') {
                    $tCheckSpl = 'checked';
                }
                if ($aValue['FTPdtStaAlwSale'] == '1') {
                    $tCheckSale = 'checked';
                }
                if ($aValue['FTPdtStaAlwPick'] == '1') {
                    $tCheckPick = 'checked';
                }

                $aPdtUnitDim       = explode(';',$aValue['FTPdtUnitDim']);
                $tPdtUnitDimWidth  = (isset($aPdtUnitDim[0]) ? $aPdtUnitDim[0] : '' );
                $tPdtUnitDimLength = (isset($aPdtUnitDim[1]) ? $aPdtUnitDim[1] : '' );
                $tPdtUnitDimHeight = (isset($aPdtUnitDim[2]) ? $aPdtUnitDim[2] : '' );

                $aPdtPkgDim       = explode(';',$aValue['FTPdtPkgDim']);
                $tPdtPkgDimWidth  = (isset($aPdtPkgDim[0]) ? $aPdtPkgDim[0] : '' );
                $tPdtPkgDimLength = (isset($aPdtPkgDim[1]) ? $aPdtPkgDim[1] : '' );
                $tPdtPkgDimHeight = (isset($aPdtPkgDim[2]) ? $aPdtPkgDim[2] : '' );

        ?>
                <tr id="otrPdtDataUnitRow<?php echo $aValue['FTPunCode']; ?>" class="xWPdtPackSizeRow xWChkPackActive xWPdtDataUnitRow xWCheckNormalPdt xWCustomActive" data-puncode="<?php echo $aValue['FTPunCode']; ?>" data-punname="<?php echo $aValue['FTPunName']; ?>" data-pdtcode="<?php echo $aValue['FTPdtCode']; ?>">
                    <td nowrap class="xCNBorderRight">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                            <input type="hidden" class="form-control xWPdtPunCode" id="ohdPdtPunCodeRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTPunCode']; ?>">
                            <input type="hidden" class="form-control xWPdtGrade" id="ohdPdtGrandRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTPdtGrade']; ?>">
                            <input type="hidden" class="form-control xWPdtWeight" id="ohdPdtWeightRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo number_format($aValue['FCPdtWeight'], $nDecShow); ?>">
                            <input type="hidden" class="form-control xWPdtClrCode" id="ohdPdtClrCodeRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTClrCode']; ?>">
                            <input type="hidden" class="form-control xWPdtClrName" id="ohdPdtClrNameRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTClrName']; ?>">
                            <input type="hidden" class="form-control xWPdtSizeCode" id="ohdPdtSizeCodeRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTPszCode']; ?>">
                            <input type="hidden" class="form-control xWPdtSizeName" id="ohdPdtSizeNameRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTPszName']; ?>">
                            <input type="hidden" class="form-control xWPdtSizeName" id="ohdPdtUnitFactRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FCPdtUnitFact']; ?>">
                            <input type="hidden" class="form-control xWPdtStaAlwPick" id="ohdPdtStaAlwPickRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwPick']; ?>">
                            <input type="hidden" class="form-control xWPdtStaAlwPoHQ" id="ohdPdtStaAlwPoHQRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwPoHQ']; ?>">
                            <input type="hidden" class="form-control xWPdtStaAlwBuy" id="ohdPdtStaAlwBuyRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwBuy']; ?>">
                            <input type="hidden" class="form-control xPdtStaAlwSale" id="ohdPdtStaAlwSaleRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwSale']; ?>">
                            <input type="hidden" class="form-control xPdtStaAlwSale" id="ohdPdtStaAlwSplRow<?php echo $aValue['FTPunCode']; ?>" value="<?php echo $aValue['FTPdtStaAlwPoSPL']; ?>">

                            <input type="hidden" class="form-control" id="ohdPdtUnitDimWidth<?php echo $aValue['FTPunCode']; ?>" value="<?=$tPdtUnitDimWidth?>">
                            <input type="hidden" class="form-control" id="ohdPdtUnitDimLength<?php echo $aValue['FTPunCode']; ?>" value="<?=$tPdtUnitDimLength?>">
                            <input type="hidden" class="form-control" id="ohdPdtUnitDimHeight<?php echo $aValue['FTPunCode']; ?>" value="<?=$tPdtUnitDimHeight?>">

                            <input type="hidden" class="form-control" id="ohdPdtPkgDimWidth<?php echo $aValue['FTPunCode']; ?>" value="<?=$tPdtPkgDimWidth?>">
                            <input type="hidden" class="form-control" id="ohdPdtPkgDimLength<?php echo $aValue['FTPunCode']; ?>" value="<?=$tPdtPkgDimLength?>">
                            <input type="hidden" class="form-control" id="ohdPdtPkgDimHeight<?php echo $aValue['FTPunCode']; ?>" value="<?=$tPdtPkgDimHeight?>">

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                    <label><?php echo $aValue['FTPunName']; ?></label>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td nowrap class="text-right">
                        <div class="xWEditInLine xCNHide">
                            <input style="text-align: right !important;" type="text" class="form-control text-right xCNInputNumericWithoutDecimal xWPdtUnitFact" id="oetPdtUnitFact<?php echo $aValue['FTPunCode']; ?>" name="oetPdtUnitFact<?php echo $aValue['FTPunCode']; ?>" placeholder="<?php echo language('product/product/product', 'tPDTViewPackUnitFact'); ?>" autocomplete="off" value="<?php echo number_format($aValue['FCPdtUnitFact'], $nDecShow); ?>">
                        </div>
                        <label><?php echo Number_format($aValue['FCPdtUnitFact'],$nDecShow); ?></label>
                    </td>
                    <td nowrap class="text-center">
                        <input disabled <?php echo $tCheckHQ ?> type="checkbox" id="ocbPdtStaHQ" name="ocbPdtStaHQ">
                    </td>
                    <td nowrap class="xWTdPdtPriceRet text-center">
                        <input disabled <?php echo $tCheckSpl ?> type="checkbox" id="ocbPdtStaBuy" name="ocbPdtStaSpl">
                    </td>
                    <!-- <td nowrap class="xWTdPdtPriceRet text-center">
                        <input disabled <?php echo $tCheckBuy ?> type="checkbox" id="ocbPdtStaBuy" name="ocbPdtStaBuy">
                    </td> -->
                    <td nowrap class="xWTdPdtPriceRet text-center">
                        <input disabled <?php echo $tCheckSale ?> type="checkbox" id="ocbPdtStaSale" name="ocbPdtStaSale">
                    </td>
                    <td nowrap class="xWTdPdtPriceRet text-center">
                        <input disabled <?php echo $tCheckPick ?> type="checkbox" id="ocbPdtStaPick" name="ocbPdtStaPick">
                    </td>

                    <?php if ($aAlwEventPdt['tAutStaFull'] == 1 || $aAlwEventPdt['tAutStaDelete'] == 1) : ?>
                        <td nowrap class="text-center">
                            <img onclick="JSxPdtDelPszUnitInTableTmp(1,'<?= $aValue['FTPunCode']; ?>')" class="xCNIconTable xWPdtDelUnitPackSize" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>">
                        </td>
                        <td nowrap class="text-center">
                            <img onclick="JSxPdtEditUnitInTable(this)" class="xCNIconTable xCNIconEdit" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>">
                        </td>
                    <?php endif; ?>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td nowrap colspan="7" class="text-center xWTextNotfoundDataTablePdt"><?php echo language('product/product/product', 'tPDTViewPackMDMsgSplPunCodeNotFound') ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<script>
    $(".xWCheckNormalPdt").unbind().click(function() {
        $(".xWCheckNormalPdt").each(function(indexInArray, valueOfElement) {
            $(this).removeClass("actived");
        });
        $(this).addClass("actived");
        $("#oliPdtPdtContentNormalBarCode a").attr('data-toggle', 'tab');

        var tPuncode = $(this).data('puncode');
        var tPunName = $(this).data('punname');
        var tPdtCode = $("#oetPdtCode").val();
        JsxCallNormalBarCodeDataTable(tPdtCode,tPuncode);
    });
</script>