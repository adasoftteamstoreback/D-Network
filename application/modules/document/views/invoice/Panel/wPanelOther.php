 <!-- Panel อืนๆ -->
 <div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOth');?></label>
        <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvIVDataInfoOther" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvIVDataInfoOther" class="xCNMenuPanelData panel-collapse collapse <?=($tIVStaApv == 1) ? 'in' : ''?>" role="tabpanel">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                    <!-- สถานะความเคลื่อนไหว -->
                    <div class="form-group">
                        <label class="fancy-checkbox">
                            <input type="checkbox" value="1" id="ocbIVFrmInfoOthStaDocAct" name="ocbIVFrmInfoOthStaDocAct" maxlength="1"  <?=($nIVStaDocAct == 1) ? 'checked' : ''; ?>>
                            <span>&nbsp;</span>
                            <span class="xCNLabelFrm"><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOthStaDocAct'); ?></span>
                        </label>
                    </div>
                    <!-- สถานะอ้างอิง -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef');?></label>
                        <select class="selectpicker form-control" id="ocmIVFrmInfoOthRef" name="ocmIVFrmInfoOthRef" maxlength="1">
                            <option value="0" <?php if(@$nIVFNXshStaRef=='0'){ echo 'selected'; } ?>><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef0');?></option>
                            <option value="1" <?php if(@$nIVFNXshStaRef=='1'){ echo 'selected'; } ?>><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef1');?></option>
                            <option value="2" <?php if(@$nIVFNXshStaRef=='2'){ echo 'selected'; } ?>><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef2');?></option>
                        </select>
                    </div>
                    <!-- จำนวนครั้งที่พิมพ์ -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOthDocPrint');?></label>
                        <input
                            type="text"
                            class="form-control text-right"
                            id="oetIVFrmInfoOthDocPrint"
                            name="oetIVFrmInfoOthDocPrint"
                            value="<?=@$tIVFNXshDocPrint?>"
                            readonly
                        >
                    </div>
                    <!-- กรณีเพิ่มสินค้ารายการเดิม -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOthReAddPdt');?></label>
                        <select class="form-control selectpicker" id="ocmIVFrmInfoOthReAddPdt" name="ocmIVFrmInfoOthReAddPdt">
                            <option value="1" selected><?=language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthReAddPdt1');?></option>
                            <option value="2"><?=language('document/saleorder/saleorder', 'tSOLabelFrmInfoOthReAddPdt2');?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>