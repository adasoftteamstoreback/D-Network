<!-- Panel เงื่อนไข-->
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoicebill/invoicebill', 'tIVBTitleEtc'); ?></label>
        <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvIVCConditionOrher" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvIVCConditionOrher" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                    <!-- สถานะความเคลื่อนไหว -->
                    <div class="form-group">
                        <label class="fancy-checkbox">
                            <input type="checkbox" value="1" id="ocbIVCFrmInfoOthStaDocAct" name="ocbIVCFrmInfoOthStaDocAct" maxlength="1" <?php echo ($nIVCStaDocAct == '1' || empty($nIVCStaDocAct)) ? 'checked' : ''; ?>>
                            <span>&nbsp;</span>
                            <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthStaDocAct'); ?></span>
                        </label>
                    </div>
                    <?php
                    switch ($nIVCStaRef) {
                        case '1':
                            $tOptionNoRef       = "";
                            $tOptionSomeRef     = "selected";
                            $tOptionAllRef      = "";
                            break;
                        case '2':
                            $tOptionNoRef       = "";
                            $tOptionSomeRef     = "";
                            $tOptionAllRef      = "selected";
                            break;
                        default:
                            $tOptionNoRef       = "selected";
                            $tOptionSomeRef     = "";
                            $tOptionAllRef      = "";
                    }
                    ?>
                    <!-- สถานะอ้างอิง -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef'); ?></label>
                        <select class="selectpicker form-control xWPIDisabledOnApv xWConditionSearchPdt" disabled id="ocmIVCFrmInfoOthRef" name="ocmIVCFrmInfoOthRef" maxlength="1">
                            <option value="0" <?php echo $tOptionNoRef ?>><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef0'); ?></option>
                            <option value="1" <?php echo $tOptionSomeRef ?>><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef1'); ?></option>
                            <option value="2" <?php echo $tOptionAllRef ?>><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef2'); ?></option>
                        </select>
                    </div>
                    <!-- จำนวนครั้งที่พิมพ์ -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthDocPrint'); ?></label>
                        <input type="text" class="form-control text-right" id="ocmIVCFrmInfoOthDocPrint" name="ocmIVCFrmInfoOthDocPrint" value="<?php echo $tIVCFrmDocPrint; ?>" readonly>
                    </div>
                    <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                    <div class="form-group xCNHide" >
                        <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt'); ?></label>
                        <select class="form-control selectpicker xWPIDisabledOnApv xWConditionSearchPdt" id="ocmIVCFrmInfoOthReAddPdt" name="ocmIVCFrmInfoOthReAddPdt">
                            <option value="1"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt1'); ?></option>
                            <option value="2" selected><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt2'); ?></option>
                        </select>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>



<script>

</script>