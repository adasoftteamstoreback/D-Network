<input type="hidden" id="ohdJR1DisChgType">
<div class="modal fade" id="odvJR1DisChgPanel" style="max-width: 1500px; margin: 1.75rem auto; width: 85%;">
    <div class="modal-dialog" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title xWJR1DisChgHeadPanel" style="display:inline-block"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="btn-group pull-right" style="margin-bottom: 20px; width: 300px;">
                            <button 
                                type="button" 
                                id="obtJR1AddDisChg" 
                                class="btn xCNBTNPrimery pull-right" 
                                onclick="JCNvJR1AddDisChgRow()" 
                                style="width: 100%;"><?php echo language('document/purchaseinvoice/purchaseinvoice','tPIMDAddEditDisChg') ?></button>
                        </div>
                    </div>
                </div>
                <!-- Ref DisChg HD Table -->
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div id="odvJR1DisChgHDTable"></div>
                    </div>
                </div>
                <!-- Ref DisChg DT Table -->
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div id="odvJR1DisChgDTTable"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main','tCancel');?>
                </button>
                <button onclick="JSxJR1DisChgSave()" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main','tCMNOK');?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/html" id="oscJR1TrBodyTemplate">
    <tr class="xWJR1DisChgTrTag">
        <input type="hidden" class="xWJR1DisChgCreatedAt">
        <td nowrap class="text-center"><label class="xWJR1DisChgIndex"></label></td>
        <td nowrap class="text-right"><label class="xWJR1DisChgBeforeDisChg">{cBeforeDisChg}</label></td>
        <td nowrap class="text-right"><label class="xWJR1DisChgValue">{cDisChgValue}</label></td>
        <td nowrap class="text-right"><label class="xWJR1DisChgAfterDisChg">{cAfterDisChg}</label></td>
        <td nowrap style="padding-left: 5px !important;">
            <div class="form-group" style="margin-bottom: 0px !important;">
                <select class="dischgselectpicker form-control xWJR1DisChgType" onchange="JSxJR1CalcDisChg(this); JCNxJR1DisChgSetCreateAt(this);">
                    <option value='1' selected="true"><?php echo language('common/main/main','tCMNBahtdiscount');?></option>
                    <option value='2'><?php echo language('common/main/main', 'tCMNDiscount'); ?>%</option>
                    <option value='3'><?php echo language('common/main/main', 'tCMNBahtCharger'); ?></option>
                    <option value='4'><?php echo language('common/main/main', 'tCMNCharger');?>%</option>
                </select>
            </div>
        </td>
        <td nowrap style="padding-left: 5px !important;">
            <div class="form-group" style="margin-bottom: 0px !important;">
                <input 
                    class="form-control 
                    xCNInputNumericWithDecimal xWJR1DisChgNum" 
                    onchange="JSxJR1CalcDisChg(this); JCNxJR1DisChgSetCreateAt(this)"
                    onkeyup="javascript:if(event.keyCode==13) JSxJR1CalcDisChg(this); JCNxJR1DisChgSetCreateAt(this)"
                    type="text">
            </div>
        </td>
        <td nowrap class="text-center">
            <label class="xCNTextLink">
                <img class="xCNIconTable xWJR1DisChgRemoveIcon" src="<?=base_url('application/modules/common/assets/images/icons/delete.png'); ?>" title="Remove" onclick="JSxJR1ResetDisChgRemoveRow(this)">
            </label>
        </td>
    </tr>
</script>
<?php include('script/jJobReq1DisChgModal.php'); ?>