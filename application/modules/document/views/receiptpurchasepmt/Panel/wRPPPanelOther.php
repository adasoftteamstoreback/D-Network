<!-- Panel อื่นๆ -->
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOth'); ?></label>
        <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvRPPInfoOth" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvRPPInfoOth" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body xCNPDModlue">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                    <!-- สถานะความเคลื่อนไหว -->
                    <div class="form-group">
                        <label class="fancy-checkbox">
                            <input 
                                type="checkbox"
                                id="ocbRPPFrmInfoOthStaDocAct"
                                name="ocbRPPFrmInfoOthStaDocAct"
                                value="1"
                                maxlength="1" <?php echo ($nRPPStaDocAct == '1' || empty($nRPPStaDocAct)) ? 'checked' : ''; ?>
                            >
                            <span>&nbsp;</span>
                            <span class="xCNLabelFrm"><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthStaDocAct'); ?></span>
                        </label>
                    </div>

                    <?php
                        switch ($nRPPStaRef) {
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
                        <label class="xCNLabelFrm"><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthRef'); ?></label>
                        <select class="selectpicker form-control xWPIDisabledOnApv xWConditionSearchPdt" disabled id="ocmRPPFrmInfoOthRef" name="ocmRPPFrmInfoOthRef" maxlength="1">
                            <option value="0" <?php echo $tOptionNoRef ?>><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthRef0'); ?></option>
                            <option value="1" <?php echo $tOptionSomeRef ?>><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthRef1'); ?></option>
                            <option value="2" <?php echo $tOptionAllRef ?>><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthRef2'); ?></option>
                        </select>
                    </div>
                    <!-- จำนวนครั้งที่พิมพ์ -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthDocPrint'); ?></label>
                        <input type="text" class="form-control text-right" id="ocmRPPFrmInfoOthDocPrint" name="ocmRPPFrmInfoOthDocPrint" value="<?php echo $tRPPFrmDocPrint; ?>" readonly>
                    </div>
                    <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthReAddPdt'); ?></label>
                        <select class="form-control selectpicker xWPIDisabledOnApv xWConditionSearchPdt" id="ocmRPPFrmInfoOthReAddPdt" name="ocmRPPFrmInfoOthReAddPdt">
                            <option value="1"><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthReAddPdt1'); ?></option>
                            <option value="2" selected><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthReAddPdt2'); ?></option>
                        </select>
                    </div>
                    <!-- หมายเหตุ -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthRemark'); ?></label>
                        <textarea class="" id="otaRPPFrmInfoOthRmk" name="otaRPPFrmInfoOthRmk" rows="10" maxlength="200" placeholder="<?php echo language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPLabelFrmInfoOthRemark'); ?>" style="resize: none;height:86px;"><?php echo $tRPPRmk ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>