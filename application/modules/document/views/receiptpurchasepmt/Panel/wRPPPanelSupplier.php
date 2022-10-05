<!-- Panel ผู้จำหน่าย -->    
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPTitlePanelSPL'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvRPPPanelSPL" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvRPPPanelSPL" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <input type="hidden" class="form-control" id="oetRPPSplAddrSeq" name="oetRPPSplAddrSeq">

            <!-- ชื่อผู้จำหน่าย -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('supplier\supplier\supplier', 'tName'); ?></label>
                <input
                    type="text"
                    class="form-control xCNInputWithoutSpc"
                    id="oetRPPSplName"
                    name="oetRPPSplName"
                    placeholder="<?= language('supplier\supplier\supplier', 'tName'); ?>"
                    value='<?=@$tRPPSPLName?>'
                    readonly
                >
            </div>

            <!-- ที่อยู่ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?=language('document/receiptpurchasepmt/receiptpurchasepmt','tRPPTitleAddress');?></label>
                <textarea
                    class="form-control xControlRmk xWConditionSearchPdt"
                    id="otaRPPAdress"
                    name="otaRPPAdress"
                    rows="10"
                    maxlength="200"
                    style="resize: none;height:86px;"
                    readonly
                ><?= @$tRPPAddress; ?></textarea>
            </div>

            <!-- ผู้ติดต่อ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPCtrName') ?></label>
                <div class="input-group">
                    <input 
                        type="text"
                        class="form-control xCNInputReadOnly xControlForm xCNHide"
                        id="oetRPPCtrCode"
                        name="oetRPPCtrCode"
                        maxlength="5"
                        value="<?= @$tRPPCtrCode; ?>"
                    >
                    <input 
                        type="text"
                        class="form-control xControlForm xWPointerEventNone"
                        id="oetRPPCtrName"
                        name="oetRPPCtrName"
                        maxlength="100"
                        placeholder="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPCtrName') ?>"
                        value="<?= @$tRPPCtrName ?>" readonly
                    >
                    <span class="input-group-btn">
                        <button id="oimRPPBrowseCtr" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                        </button>
                    </span>
                </div>
            </div>
    
            <!-- เบอร์โทร -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?=language('document/receiptpurchasepmt/receiptpurchasepmt','tRPPCtrPhone');?></label>
                <input
                    type="text"
                    class="form-control xCNInputWithoutSpc"
                    id="oetRPPCtrPhone"
                    name="oetRPPCtrPhone"
                    placeholder="<?= language('document/receiptpurchasepmt/receiptpurchasepmt', 'tRPPCtrPhone'); ?>"
                    value='<?=@$tRPPCtrPhone?>'
                    readonly
                >
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $('.xCNPanel_CreditTerm').hide();

    //เปลี่ยนประเภทการชำระ
    if('<?=@$tRPPCshorCrd ?>' == '2'){
       $('.xCNPanel_CreditTerm').show();
    }

    $('#ocmRPPPaymentType').on('change', function() {
        if(this.value == 1){
            $('.xCNPanel_CreditTerm').hide();
        }else{
            $('.xCNPanel_CreditTerm').show();
        }
    });


</script>