<!-- Panel เงื่อนไข-->
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoicebill/invoicebill', 'tIVBTitleEtc'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvIVBCondition" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvIVBCondition" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                    <!-- สถานะความเคลื่อนไหว -->
                    <div class="form-group">
                        <label class="fancy-checkbox">
                            <input type="checkbox" value="1" id="ocbIVBFrmInfoOthStaDocAct" name="ocbIVBFrmInfoOthStaDocAct" maxlength="1" <?php echo ($nIVBStaDocAct == '1' || empty($nIVBStaDocAct)) ? 'checked' : ''; ?>>
                            <span>&nbsp;</span>
                            <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthStaDocAct'); ?></span>
                        </label>
                    </div>
                    <?php
                    switch ($nIVBStaRef) {
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
                        <select class="selectpicker form-control xWPIDisabledOnApv xWConditionSearchPdt" disabled id="ocmIVBFrmInfoOthRef" name="ocmIVBFrmInfoOthRef" maxlength="1">
                            <option value="0" <?php echo $tOptionNoRef ?>><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef0'); ?></option>
                            <option value="1" <?php echo $tOptionSomeRef ?>><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef1'); ?></option>
                            <option value="2" <?php echo $tOptionAllRef ?>><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRef2'); ?></option>
                        </select>
                    </div>
                    <!-- จำนวนครั้งที่พิมพ์ -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthDocPrint'); ?></label>
                        <input type="text" class="form-control text-right" id="ocmIVBFrmInfoOthDocPrint" name="ocmIVBFrmInfoOthDocPrint" value="<?php echo $tIVBFrmDocPrint; ?>" readonly>
                    </div>
                    <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt'); ?></label>
                        <select class="form-control selectpicker xWPIDisabledOnApv xWConditionSearchPdt" id="ocmIVBFrmInfoOthReAddPdt" name="ocmIVBFrmInfoOthReAddPdt">
                            <option value="1"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt1'); ?></option>
                            <option value="2" selected><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthReAddPdt2'); ?></option>
                        </select>
                    </div>
                    <!-- หมายเหตุ -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRemark'); ?></label>
                        <textarea class="" id="otaIVBFrmInfoOthRmk" name="otaIVBFrmInfoOthRmk" rows="10" maxlength="200" placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmInfoOthRemark'); ?>" style="resize: none;height:86px;"><?php echo $tIVBRmk ?></textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<script>

</script>