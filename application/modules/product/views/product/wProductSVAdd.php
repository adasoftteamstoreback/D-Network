<?php
//Decimal Show ลง 2 ตำแหน่ง
$nDecShow =  FCNxHGetOptionDecimalShow();
if (isset($aItems)) {
    $tStaEnter              = "2"; //Edit
    $tPdtCodeSub            = $aItems[0]['FTPdtCodeSub'];
    $tPdtNameSet            = $aItems[0]['FTPdtName'];
    $cPdtSetQty             = $aItems[0]['FCPsvQty'];
    $tPunCode               = $aItems[0]['FTPunCode'];
    $tPunName               = $aItems[0]['FTPunName'];
    $tPdtSVSetUnitFact      = $aItems[0]['FCPsvFactor'];
    $nPdtSvSetType          = $aItems[0]['FTPsvType'];
    $nPdtSvSetStaSug        = $aItems[0]['FTPsvStaSuggest'];
    if (count($aAnwser) > 0) {
        $aAnwserShow            = $aAnwser;
        $nChkType               = $aAnwser[0]['FTPdtChkType'];
    } else {
        $aAnwserShow          = [];
        $nChkType           = "";
    }
} else {
    $tStaEnter      = "1"; //Add
    $tPdtCodeSub    = "";
    $tPdtNameSet    = "";
    $cPdtSetQty     = 1;
    $tPunCode       = "";
    $tPunName       = "";
    $tPdtSVSetUnitFact = "";
    $nPdtSvSetType          = "1";
    $nPdtSvSetStaSug          = "1";
    $aAnwserShow          = [];
    $nChkType           = "2";
}
?>
<style>
    .xWSmgMoveIcon {
        cursor: move !important;
        border-radius: 0px;
        box-shadow: none;
        padding: 0px 10px;
    }
</style>
<script>
    var nStaChk = $('#oetPdtSetStaEnter').val();
    if (nStaChk == "2") {
        $('.xWBtnPdtSetAddProduct').attr('disabled', true);
    }
</script>

<div class="">
    <input type="hidden" id="ohdCheckType" name="ohdCheckType" value="<?php echo $nPdtSvSetType ?>">
    <input type="hidden" id="ohdtStaEnter" name="ohdtStaEnter" value="<?php echo $tStaEnter ?>">
    <input type="hidden" id="ohdtAnwserType" name="ohdtAnwserType" value="<?php echo $nChkType ?>">
    <input type="hidden" id="ohdtOldAnwserType" name="ohdtOldAnwserType" value="<?php echo $nChkType ?>">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <input type="text" id="oetPdtSetStaEnter" name="oetPdtSetStaEnter" class="form-control xCNHide" value="<?= $tStaEnter ?>">
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('product/product/product', 'tPDTSVType'); ?></label>
                <select class="form-control" id="ocmPdtSvType" name="ocmPdtSvType" maxlength="1">
                    <option value="1" <?php if ($nPdtSvSetType == '1') { ?> selected <?php } ?>>
                        <?php echo language('product/product/product', 'tPDTSVType1') ?></option>
                    <option value="2" <?php if ($nPdtSvSetType == '2') { ?> selected <?php } ?>>
                        <?php echo language('product/product/product', 'tPDTSVType2') ?></option>
                </select>
            </div>
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('product/product/product', 'tPDTCode'); ?></label>
                <div class="input-group">
                    <input type="text" id="oetPdtSVPdtCode" name="oetPdtSVPdtCode" required class="form-control" value="<?= $tPdtCodeSub ?>" placeholder="<?= language('product/product/product', 'tPDTCode'); ?>" data-validate="<?= language('product/product/product', 'tPDTSETValidPdtCode'); ?>" readonly>
                    <span class="input-group-btn">
                        <button type="button" class="btn xCNBtnBrowseAddOn xWBtnPdtSetAddProduct" onclick="JSxPdtSetBrowseProductSV()"><img class="xCNIconFind"></button>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="xCNLabelFrm"><span style="color:red">*</span> <?= language('product/product/product', 'tPDTName'); ?></label>
                <input type="text" class="form-control" maxlength="100" id="oetPdtSVPdtName" name="oetPdtSVPdtName" value="<?= $tPdtNameSet ?>" readonly placeholder="<?= language('product/product/product', 'tPDTName'); ?>">
            </div>

            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span> <?= language('product/product/product', 'tPDTSetPstQty'); ?></label>
                        <input type="number" class="form-control text-right" maxlength="18" id="oetPdtSVPstQty" name="oetPdtSVPstQty" value="<?= number_format($cPdtSetQty, 4); ?>" placeholder="<?= language('product/product/product', 'tPDTSetPstQty'); ?>" autocomplete="off" data-validate="<?= language('product/product/product', 'tPDTSETValidPstQty'); ?>">
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?= language('product/product/product', 'tPdtSreachType4'); ?></label>
                        <input type="text" class="form-control xCNHide" id="oetPdtSVUnitFact" name="oetPdtSVUnitFact" value="<?= $tPdtSVSetUnitFact ?>" readonly>
                        <input type="text" class="form-control xCNHide" id="oetPdtSVUnitCode" name="oetPdtSVUnitCode" value="<?= $tPunCode ?>" readonly>
                        <input type="text" class="form-control" id="oetPdtSVUnitName" name="oetPdtSVUnitName" value="<?= $tPunName ?>" placeholder="<?= language('product/product/product', 'tPDTTBUnit'); ?>" readonly>
                    </div>
                </div>
            </div>

            <?php
            if ($nPdtSvSetStaSug == '1') {
                $tchecked = 'checked';
            } else {
                $tchecked = '';
            }
            ?>
            <div id="odvProductSvStatus" class="form-group">
                <input type="hidden" id="ohdCheckStatus" name="ohdCheckStatus" value="<?php echo $nPdtSvSetStaSug ?>">
                <div class="validate-input">
                    <label class="fancy-checkbox">
                        <input type="checkbox" <?php echo $tchecked ?> id="ocbPdtSVStatus" name="ocbPdtSVStatus">
                        <span> <?php echo language('product/product/product', 'tPDTSVRecomment'); ?></span>
                    </label>
                </div>
            </div>


            <div class="xWPdtDetailList">
                <label class="xCNLabelFrm"><?= language('product/product/product', 'tPDTSVResult'); ?></label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input xCNRadioType" type="radio" name="ocbADDType" id="ocbADDType" value="1">
                    <label class="form-check-label"><?php echo language('service/question/question', 'tPDTSVOptionType1'); ?></label>
                    <input class="form-check-input xCNRadioType" type="radio" style='margin-left:10px;' name="ocbADDType" id="ocbADDType" value="2">
                    <label class="form-check-label"><?php echo language('service/question/question', 'tPDTSVOptionType2'); ?></label>
                    <input class="form-check-input xCNRadioType" style='margin-left:10px;' type="radio" name="ocbADDType" id="ocbADDType" value="3">
                    <label class="form-check-label"><?php echo language('service/question/question', 'tPDTSVOptionType3'); ?></label>
                </div>


                <div class="xWSmgSortContainer" id="odvSmgSlipHeadContainer">
                    <label class="xCNLabelFrm"><?= language('product/product/product', 'tPDTSVFillDetail'); ?></label>
                    <?php foreach ($aAnwserShow as $nHIndex => $oHeadItem) : $nHIndex++; ?>
                        <div class="form-group xWSmgItemSelect" id="<?php echo $nHIndex; ?>">
                            <div class="input-group validate-input" id='odvClassType'>
                                <span class="input-group-btn">
                                    <div class="btn xWSmgMoveIcon xWMoveIcon" type="button"><i class="icon-move fa fa-arrows"></i></div>
                                </span>
                                <div class="row">
                                    <div class="col-md-12 XWMsgType<?php echo $nHIndex; ?>">
                                        <input type="text" class="form-control xWSmgDyForm" maxlength="100" id="oetSVPdtValue<?php echo $nHIndex; ?>" name="oetSVPdtValue[<?php echo $nHIndex; ?>]" value="<?php echo $oHeadItem['FTPdtChkResult']; ?>">
                                        <input type="hidden" id="ohdMsgSeq<?php echo $nHIndex; ?>" name="ohdMsgSeq[<?php echo $nHIndex; ?>]" value="<?php echo $oHeadItem['FNPdtSrvSeq']; ?>">
                                    </div>
                                </div>
                                <span class="input-group-btn">
                                    <img class="xCNIconTable xWIconDelete" src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/delete.png' ?>" title="<?php echo language('pos/slipMessage/slipmessage', 'tSMGTBDelete'); ?>" onclick="JSxPdtSVEventDeleteDetail(this, event)">
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="odvAddMore">
                    <p class="text-primary text-right" onclick="JSxPdtSVAddRow()" style="margin-right: 25px;cursor: pointer;"><i class="fa fa-plus" style="font-size: 15px;"></i> <strong><?php echo language('pos/slipMessage/slipmessage', 'tSMGAddRow'); ?></strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ===================================================== Modal Delete Product SV Detail Set ===================================================== -->
<div id="odvModalDeletePdtSVSetDetail" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete') ?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelPdtSVSet" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelPdtSVSet" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm') ?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel') ?></button>
            </div>
        </div>
    </div>
</div>
<!-- ====================================================== End Modal Delete Product Set ======================================================= -->
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>
<?php include "script/jProductSVAdd.php"; ?>