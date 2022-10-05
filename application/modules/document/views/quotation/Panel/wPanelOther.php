 <!-- Panel อืนๆ -->
 <div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOth');?></label>
        <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvTQDataInfoOther" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvTQDataInfoOther" class="xCNMenuPanelData panel-collapse collapse <?=($tTQStaApv == 1) ? 'in' : ''?>" role="tabpanel">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                    <!-- สถานะความเคลื่อนไหว -->
                    <div class="form-group">
                        <label class="fancy-checkbox">
                            <input type="checkbox" value="1" id="ocbQTFrmInfoOthStaDocAct" name="ocbQTFrmInfoOthStaDocAct" maxlength="1"  <?=($nQTStaDocAct == '1' || empty($nQTStaDocAct)) ? 'checked' : ''; ?>>
                            <span>&nbsp;</span>
                            <span class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthStaDocAct'); ?></span>
                        </label>
                    </div>
                    <!-- สถานะอ้างอิง -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef');?></label>
                        <select class="selectpicker form-control xCNSelectDisabledPicker" id="ocmQTFrmInfoOthRef" name="ocmQTFrmInfoOthRef" maxlength="1">
                            <option value="0" <?php if(@$nQTFNXshStaRef=='0'){ echo 'selected'; } ?>><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef0');?></option>
                            <option value="1" <?php if(@$nQTFNXshStaRef=='1'){ echo 'selected'; } ?>><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef1');?></option>
                            <option value="2" <?php if(@$nQTFNXshStaRef=='2'){ echo 'selected'; } ?>><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef2');?></option>
                        </select>
                    </div>
                    <!-- จำนวนครั้งที่พิมพ์ -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthDocPrint');?></label>
                        <input
                            type="text"
                            class="form-control text-right"
                            id="oetTQFrmInfoOthDocPrint"
                            name="oetTQFrmInfoOthDocPrint"
                            value="<?=@$tQTFNXshDocPrint?>"
                            readonly
                        >
                    </div>
                    <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOthReAddPdt');?></label>
                        <select class="form-control selectpicker xCNSelectDisabledPicker" id="ocmTQFrmInfoOthReAddPdt" name="ocmTQFrmInfoOthReAddPdt">
                            <option value="1" selected><?=language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthReAddPdt1');?></option>
                            <option value="2"><?=language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthReAddPdt2');?></option>
                        </select>
                    </div>
                    <!-- หมายเหตุ -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?= language('common/main/main', 'tRemark'); ?></label>
                        <textarea class="form-control xCNInputWithoutSpc" id="otaQTRemark" name="otaQTRemark" maxlength="200"><?=@$tQTFTXshRmk;?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>