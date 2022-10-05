<!-- Panel ข้อมูลรถ--> 
<div class="panel panel-default" style="margin-bottom: 25px;">
    <div class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
        <label class="xCNTextDetail1"><?= language('document/invoice/invoice', 'ข้อมูลรถ'); ?></label>
        <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvCLMCar" aria-expanded="true">
            <i class="fa fa-plus xCNPlus"></i>
        </a>
    </div>
    <div id="odvCLMCar" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body xCNPDModlue">
            
            <!-- รถ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><span style = "color:red">*</span><?=language('document/invoice/invoice', 'ข้อมูลรถ') ?></label>
                <div class="input-group">
                    <input  type="text" class="form-control xCNInputReadOnly xControlForm xCNHide" id="oetCLMFrmCarCode" name="oetCLMFrmCarCode" maxlength="5" value="<?=@$tCLMCarCode?>">
                    <input
                        type="text" 
                        class="form-control xControlForm xWPointerEventNone" 
                        id="oetCLMFrmCarName" name="oetCLMFrmCarName"
                        maxlength="100"
                        placeholder="<?=language('document/jobrequest1/jobrequest1', 'กรุณาเลือกข้อมูลรถ') ?>" 
                        value="<?=@$tCLMCarRegNo?>" 
                        readonly
                    >
                    <span class="input-group-btn">
                        <button id="oimCLMBrowseCar" type="button" class="btn xCNBtnBrowseAddOn">
                            <img src="<?=base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                        </button>
                    </span>
                </div>
            </div>

            <!-- เลขไมล์ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?=language('document/jobrequest1/jobrequest1', 'เลขไมล์') ?></label>
                <input
                    type="text"
                    class="form-control text-right xCNInputReadOnly xCNInputNumericWithDecimal"
                    id="oetCLMCarMile"
                    name="oetCLMCarMile"
                    autocomplete="off"
                    maxlength="12"
                    placeholder="<?=language('document/jobrequest1/jobrequest1', 'เลขไมล์') ?>" 
                    value="<?=@$tCLMMile?>"
                >
            </div>

            <!-- หมายเหตุ -->
            <div class="form-group">
                <label class="xCNLabelFrm"><?=language('document/purchaseorder/purchaseorder','tPOLabelFrmInfoOthRemark');?></label>
                <textarea
                    class="form-control xControlRmk xWConditionSearchPdt"
                    id="otaCLMFrmInfoOthRmk"
                    name="otaCLMFrmInfoOthRmk"
                    rows="10"
                    maxlength="200"
                    style="resize: none;height:86px;"
                ><?=@$tCLMRmk?></textarea>
            </div>

        </div>
    </div>
</div>