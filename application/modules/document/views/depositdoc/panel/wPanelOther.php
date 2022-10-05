 <!-- Panel อืนๆ -->
 <div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?=language('document/saleorder/saleorder','tSOLabelFrmInfoOth');?></label>
        <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse"  href="#odvDPSDataInfoOther" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvDPSDataInfoOther" class="xCNMenuPanelData panel-collapse collapse <?=($tDPSStaApv == 1) ? 'in' : ''?>" role="tabpanel">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-xs-12">
                    <!-- สถานะความเคลื่อนไหว -->
                    <div class="form-group">
                        <label class="fancy-checkbox">
                            <input type="checkbox" value="1" id="ocbDPSFrmInfoOthStaDocAct" name="ocbDPSFrmInfoOthStaDocAct" maxlength="1" <?php if($nDPSStaDocAct == '1'){ echo 'checked'; } ?>>
                            <span>&nbsp;</span>
                            <span class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthStaDocAct'); ?></span>
                        </label>
                    </div>
                    <!-- สถานะอ้างอิง -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef');?></label>
                        <select class="selectpicker form-control" id="ocmDPSFrmInfoOthRef" name="ocmDPSFrmInfoOthRef" maxlength="1">
                            <option value="0" <?php if(@$nDPSFNXshStaRef=='0'){ echo 'selected'; } ?>><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef0');?></option>
                            <option value="1" <?php if(@$nDPSFNXshStaRef=='1'){ echo 'selected'; } ?>><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef1');?></option>
                            <option value="2" <?php if(@$nDPSFNXshStaRef=='2'){ echo 'selected'; } ?>><?php echo language('document/saleorder/saleorder','tSOLabelFrmInfoOthRef2');?></option>
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
                            value="<?=@$tDPSFNXshDocPrint?>"
                            readonly
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>