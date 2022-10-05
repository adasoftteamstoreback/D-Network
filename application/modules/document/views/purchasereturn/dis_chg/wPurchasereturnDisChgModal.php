<input type="hidden" id="ohdPNDisChgType">
<div class="modal fade" id="odvPNDisChgPanel" style="max-width: 1500px; margin: 1.75rem auto; width: 85%;">
    <div class="modal-dialog" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title xWPNDisChgHeadPanel" style="display:inline-block"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!--div class="col-lg-4">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/transferwarehouseout/transferwarehouseout', 'ประเภท'); ?></label>
                            <select class="selectpicker form-control" id="ocmPNDisChgSelectType" name="ocmPNDisChgSelectType">
                                <option value='1' selected="true"><?php echo language('common/main/main', 'ลดบาท'); ?></option>
                                <option value='2'><?php echo language('common/main/main', 'ลด %'); ?></option>
                                <option value='3'><?php echo language('common/main/main', 'ชาร์จบาท'); ?></option>
                                <option value='4'><?php echo language('common/main/main', 'ชาร์ท %'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/purchasereturn/purchasereturn', 'มูลค่า'); ?></label>
                            <input class="form-control xCNInputNumericWithDecimal" type="text" id="oetPNDisChgInitNum" name="oetPNDisChgInitNum">
                        </div>
                    </div-->
                    <div class="col-lg-12">
                        <div class="btn-group pull-right" style="margin-bottom: 20px; width: 300px;">
                            <button 
                                type="button" 
                                id="obtPNAddDisChg" 
                                class="btn xCNBTNPrimery pull-right" 
                                onclick="JCNvPNAddDisChgRow()" 
                                style="width: 100%;"><?php echo language('common/main/main','tCMNAddDiscount');?></button>
                        </div>
                    </div>
                </div>
                <!-- Ref DisChg HD Table -->
                <div class="row">
                    <div class="col-md-12"><div id="odvPNDisChgHDTable"></div></div>
                </div>
                <!-- Ref DisChg HD Table -->
                
                <!-- Ref DisChg DT Table -->
                <div class="row">
                    <div class="col-md-12"><div id="odvPNDisChgDTTable"></div></div>
                </div>
                <!-- Ref DisChg DT Table -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tCancel'); ?>
                </button>
                <button onclick="JSxPNDisChgSave()" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tCMNOK'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Update lang for disChg -->
<!-- Create By Witsarut 07/01/2020 -->
<input type="hidden" id="oetDiscountcharg" value="<?php echo language('document/purchaseinvoice/purchaseinvoice','tPIAdvDiscountcharging')?>">
<input type="hidden" id="oetDiscountcharginglist" value="<?php echo language('document/purchaseinvoice/purchaseinvoice','tPIAdvDiscountcharginglist');?>">

<script type="text/html" id="oscPNTrNotFoundTemplate">
    <tr id="otrPNDisChgDTNotFound"><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
</script>

<script type="text/html" id="oscPNTrBodyTemplate"> 
    <tr class="xWPNDisChgTrTag">
        <input type="hidden" class="xWPNDisChgCreatedAt" value="{tCreatedAt}">
        <td nowrap class="text-center"><label class="xWPNDisChgIndex"></label></td>
        <td nowrap class="text-right"><label class="xWPNDisChgBeforeDisChg">{cBeforeDisChg}</label></td>
        <td nowrap class="text-right"><label class="xWPNDisChgValue">{cDisChgValue}</label></td>
        <td nowrap class="text-right"><label class="xWPNDisChgAfterDisChg">{cAfterDisChg}</label></td>
        <td nowrap style="padding-left: 5px !important;">
            <div class="form-group" style="margin-bottom: 0px !important;">
                <select class="dischgselectpicker form-control xWPNDisChgType" onchange="JSxPNCalcDisChg(this);">
                    <option value='1' selected="true"><?php echo language('common/main/main', 'tCMNBahtdiscount'); ?></option>
                    <option value='2'><?php echo language('common/main/main', 'tCMNDiscount'); ?> %</option>
                    <option value='3'><?php echo language('common/main/main', 'tCMNBahtCharger'); ?></option>
                    <option value='4'><?php echo language('common/main/main', 'tCMNCharger'); ?> %</option>
                </select>
            </div>
        </td>
        <td nowrap style="padding-left: 5px !important;">
            <div class="form-group" style="margin-bottom: 0px !important;">
                <input 
                    class="form-control 
                    xCNInputNumericWithDecimal xWPNDisChgNum" 
                    onchange="JSxPNCalcDisChg(this);"
                    onkeyup="javascript:if(event.keyCode==13) JSxPNCalcDisChg(this);"
                    type="text">
            </div>
        </td>
        <td nowrap class="text-center">
            <label class="xCNTextLink">
                <img class="xCNIconTable xWPNDisChgRemoveIcon" src="<?php echo  base_url('application/modules/common/assets/images/icons/delete.png'); ?>" title="Remove" onclick="JSxPNResetDisChgRemoveRow(this)">
            </label>
        </td>
    </tr>


</script>

<?php include('script/jPurchasereturnDisChgModal.php'); ?>





































































































































