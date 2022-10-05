<?php 
if(empty($aDataDTNonePdt)){
    $tPdtName = "ใบลดหนี้";
    $tSetPrice = "0.00";
}else{
    $tPdtName = $aDataDTNonePdt['FTXpdPdtName'];
    $tSetPrice = $aDataDTNonePdt['FCXpdSetPrice'];
}
?>
<div class="table-responsive">
    <table class="table table-striped xWPdtTableFont" id="otbPNDOCNonePdtTable">
        <thead>
            <tr class="xCNCenter">
                <th><?php echo language('document/purchasereturn/purchasereturn', 'tPNTBCode'); ?></th>
                <th><?php echo language('document/purchasereturn/purchasereturn', 'tPNTBListName'); ?></th>
                <th><?php echo language('document/purchasereturn/purchasereturn', 'tPNTotalCash'); ?></th>
            </tr>
        </thead>
        <tbody id="odvTBodyPNPdt">
            
        <?php if(!empty($oDataDT)){ ?>
            <tr id="otrPNNonePdtActiveForm" class="xCNHide">
                <td>
                    <label id="olbPNNonePdtCode"><?php echo $oDataDT->FTPdtCode; ?></label>
                </td>
                <td nowrap="" class="text-right" style="border : 0px !important;position:relative;">
                    <input 
                        id="oetPNNonePdtName"
                        value="<?php echo $tPdtName; ?>" 
                        type="text" 
                        maxlength="150"
                        class="xCNPdtFont xWShowInLine1 xWShowValueFCXtdQty1 xWEditInlineElement" 
                        data-field="FCXtdQty" 
                        data-seq="1" 
                        <?php echo (empty($tStaApv) && $tStaDoc != 3) ? '' : 'disabled'; ?>
                        style="background:#F9F9F9;border-top: 0px !important;border-left: 0px !important;border-right: 0px !important;box-shadow: inset 0 0px 0px;">
                </td>
                <td nowrap="" class="text-right" style="border : 0px !important;position:relative;">
                    <!--onkeyup="javascript: if(event.keyCode == 13) {JSoPNCalEndOfBillNonePdt()}"-->
                    <input 
                        id="oetPNNonePdtValue"
                        value="<?php echo $tSetPrice; ?>" 
                        type="text" 
                        maxlength="8"
                        class="xCNPdtFont xWShowInLine1 xWShowValueFCXtdQty1 xWEditInlineElement xCNInputNumericWithDecimal" 
                        data-field="FCXtdQty" 
                        data-seq="1" 
                        <?php echo (empty($tStaApv) && $tStaDoc != 3) ? '' : 'disabled'; ?>
                        onkeyup="JSoPNCalEndOfBillNonePdt()"
                        onchange="JSoPNCalEndOfBillNonePdt()"
                        style="background:#F9F9F9;border-top: 0px !important;border-left: 0px !important;border-right: 0px !important;box-shadow: inset 0 0px 0px;">
                </td>
            </tr>
            <tr class="text-center" id="otrPNNonePdtMessageForm"><td colspan="100%"><?php echo language('document/purchasereturn/purchasereturn', 'tPNMsgPleseShooseSpl'); ?></td></tr>
        <?php } ?>
        
        </tbody>
    </table>
</div>

<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<?php  include("script/jPurchasereturnNonePdtAdvTableData.php");?>




