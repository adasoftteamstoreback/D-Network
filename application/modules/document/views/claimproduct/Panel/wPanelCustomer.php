<!-- Panel ข้อมูลลูกค้า--> 
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoice/invoice', 'ข้อมูลลูกค้า'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvCLMCustomer" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvCLMCustomer" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">

            <!-- ชื่อลูกค้า -->
            <div class="form-group">
                <label class="xCNLabelFrm"><span style = "color:red">*</span><?=language('document/jobrequest1/jobrequest1', 'tJR1LabelCustomer') ?></label>
                <div class="input-group">
                    <input  type="text" class="form-control xControlForm xCNHide" id="oetCLMFrmCstCode" name="oetCLMFrmCstCode" maxlength="5" value="<?=@$tCLMCstCode?>">
                    <input
                        type="text" 
                        class="form-control xControlForm xWPointerEventNone" 
                        id="oetCLMFrmCstName" name="oetCLMFrmCstName"
                        maxlength="100"
                        placeholder="<?=language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterCustomer') ?>" 
                        value="<?=@$tCLMCstName?>" 
                        data-validate-required="<?=language('document/jobrequest1/jobrequest1','tJR1PlsEnterCustomer'); ?>"
                        readonly
                    >
                    <span class="input-group-btn">
                        <button id="oimCLMBrowseCustomer" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?=base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                        </button>
                    </span>
                </div>
            </div>

            <!-- ที่อยู่ลูกค้า -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('document/jobrequest1/jobrequest1', 'tJR1LabelCstAddr') ?></label>
                <textarea
                    class="form-control xCNInputReadOnly"
                    id="oetCLMFrmCstAddr"
                    name="oetCLMFrmCstAddr"
                    rows="10"
                    maxlength="200"
                    readonly
                    placeholder="<?php echo language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterCstAddr') ?>" 
                    style="resize: none;height:86px;"
                ><?=@$tCLMCstADDL?></textarea>
            </div>

            <!-- เบอร์โทรติดต่อ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?=language('document/jobrequest1/jobrequest1', 'tJR1LabelCstTel') ?></label>
                <input
                    type="text"
                    class="form-control xCNInputReadOnly"
                    id="oetCLMFrmCstTel"
                    name="oetCLMFrmCstTel"
                    placeholder="<?=language('document/jobrequest1/jobrequest1', 'tJR1PlsEnterCstTel') ?>" 
                    readonly
                    value="<?=@$tCLMCstTel?>"
                >
            </div>

            <!-- อีเมล์ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?=language('document/jobrequest1/jobrequest1', 'tJR1LabelCstEmail') ?></label>
                <input
                    type="text"
                    class="form-control xCNInputReadOnly"
                    id="oetCLMFrmCstEmail"
                    name="oetCLMFrmCstEmail"
                    readonly
                    placeholder="<?=language('document/jobrequest1/jobrequest1', 'tJR1LabelCstEmail') ?>"
                    value="<?=@$tCLMCstEmail?>"
                >
            </div>
            
        </div>
    </div>
</div>