<?php 
    if(empty($aDataDTNonePdt)){
        $tPdtName   = "เพิ่มหนี้ยอดเงิน";
        $tSetPrice  = "0.00";
    }else{
        $tPdtName   = $aDataDTNonePdt['FTXpdPdtName'];
        $tSetPrice  = $aDataDTNonePdt['FCXpdSetPrice'];
    }
?>
<div class="table-responsive">
    <table class="table table-striped xWPdtTableFont" id="otbAPDDOCNonePdtTable">
        <thead>
            <tr class="xCNCenter">
                <th><?php echo language('document/apdebitnote/apdebitnote', 'tAPDTBCode'); ?></th>
                <th><?php echo language('document/apdebitnote/apdebitnote', 'tAPDTBListName'); ?></th>
                <th><?php echo language('document/apdebitnote/apdebitnote', 'tAPDTotalCash'); ?></th>
            </tr>
        </thead>
        <tbody id="odvTBodyAPDPdt">
            <?php if(!empty($oDataDT)): ?>
                <tr id="otrAPDNonePdtActiveForm" class="xCNHide">
                    <td>
                        <label id="olbAPDNonePdtCode"><?php echo $oDataDT->FTPdtCode; ?></label>
                    </td>
                    <td nowrap="" class="text-right" style="border : 0px !important;position:relative;">
                        <input 
                            id="oetAPDNonePdtName"
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
                        <input 
                            id="oetAPDNonePdtValue"
                            value="<?php echo $tSetPrice; ?>" 
                            type="text" 
                            maxlength="12"
                            class="xCNPdtFont xWShowInLine1 xWShowValueFCXtdQty1 xWEditInlineElement xCNInputNumericWithDecimal text-right" 
                            data-field="FCXtdQty" 
                            data-seq="1" 
                            <?php echo (empty($tStaApv) && $tStaDoc != 3) ? '' : 'disabled'; ?>
                            onkeyup="JSoAPDCalEndOfBillNonePdt()"
                            onchange="JSoAPDCalEndOfBillNonePdt()"
                            style="background:#F9F9F9;border-top: 0px !important;border-left: 0px !important;border-right: 0px !important;box-shadow: inset 0 0px 0px;"
                        >
                    </td>
                </tr>
                <tr class="text-center" id="otrAPDNonePdtMessageForm"><td colspan="100%"><?php echo language('document/apdebitnote/apdebitnote', 'tAPDMsgPleseShooseSpl'); ?></td></tr>
            <?php endif;?>
        </tbody>
    </table>
</div>
<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php  include("script/jAPDebitnoteNonePdtAdvTableData.php");?>

