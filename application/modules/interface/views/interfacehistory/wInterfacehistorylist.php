<div class="panel-heading">
	<div class="row">

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('common/main/main','tSearch')?></label>
                <div class="input-group">
                    <input
                        class="form-control xCNInputWithoutSingleQuote"
                        type="text"
                        id="oetSearchAll"
                        name="oetSearchAll"
                        placeholder="<?php echo language('document/adjuststock/adjuststock','tASTFillTextSearch')?>"
                        onkeyup="Javascript:if(event.keyCode==13) JSvCallPageIFHDataTable()"
                        autocomplete="off"
                    >
                    <span class="input-group-btn">
                        <button type="button" class="btn xCNBtnDateTime" onclick="JSvCallPageIFHDataTable()">
                            <img class="xCNIconSearch">
                        </button>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('report/report/report','tRptDateFrom')?></label>
                <div class="input-group">
                    <input
                        class="form-control xCNDatePicker"
                        type="text"
                        id="oetIFHDocDateFrom"
                        name="oetIFHDocDateFrom"
                        autocomplete="off"
                        placeholder="<?php echo language('interface/interfacehistory', 'tIFHDatefrom'); ?>"
                    >
                    <span class="input-group-btn" >
                        <button  type="button" id="obtIFHDocDateFrom" class="btn xCNBtnDateTime "> <img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('report/report/report','tRptDateTo')?></label>
                <div class="input-group">
                    <input
                        class="form-control xCNDatePicker"
                        type="text"
                        id="oetIFHDocDateTo"
                        name="oetIFHDocDateTo"
                        autocomplete="off"
                        placeholder="<?php echo language('interface/interfacehistory', 'tIFHDateto'); ?>"
                    >
                    <span class="input-group-btn" >
                        <button  type="button" id="obtIFHDocDateTo" class="btn xCNBtnDateTime" onclick=""><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-1">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('report/report/report','???????????????')?></label>
                <select class="selectpicker form-control" id="ocmIFHStaDone" name="ocmIFHStaDone">
                    <option value=''><?php echo language('common\main\main','tAll'); ?></option>
                    <option value='1'><?php echo language('interface/interfacehistory','tIFHSuccess'); ?></option>
                    <option value='2'><?php echo language('interface/interfacehistory','tIFHFail'); ?></option>
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-1">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('report/report/report','??????????????????')?></label>
                <select class="selectpicker form-control" id="ocmIFHType" name="ocmIFHType">
                    <option value=''><?php echo language('common\main\main','tAll'); ?></option>
                    <option value='1'><?php echo language('interface/interfacehistory','tIFHImport'); ?></option>
                    <option value='2'><?php echo language('interface/interfacehistory','tIFHExport'); ?></option>
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-1">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('report/report/report','??????????????????')?></label>
                <select class="selectpicker form-control" id="ocmIFHInfCode" name="ocmIFHInfCode">
                    <option value=''><?php echo language('common\main\main','tAll'); ?></option>
                    <?php if(!empty($aDataMasterImport)){
                        foreach($aDataMasterImport as $aData){ ?>
                    <option value='<?php echo $aData['FTApiCode']; ?>' class="xWIFHDocType xWIFHDoctype_<?=$aData['FTApiTxnType'];?>"><?php echo $aData['FTApiName']; ?></option>
                    <?php } }  ?>
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <label class="xCNLabelFrm"></label>
            <div class="form-group">
				<button type="button" id="obtResetSearch" class="btn btn-primary xCNBTNImportRole xCNBTNDefult xCNBTNDefult2Btn" style='width:46%;margin-right: 10px;'>
                    <?=language('common\main\main','tClearSearch'); ?>
				</button>
				<button type="button" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" style='width:46%' onclick="JSvCallPageIFHDataTable()">
                    <?=language('common\main\main','tSearch'); ?>
				</button>
			</div>
        </div>

    </div>
</div>

<div class="panel-body">
	<div id="odvContentIFHDatatable"></div>
</div>

<script>
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
    });

    $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true    
    });

    $('#obtIFHDocDateTo').on('click',function(){
        $('#oetIFHDocDateTo').datepicker('show');
    });

    $('#obtIFHDocDateFrom').on('click',function(){
        $('#oetIFHDocDateFrom').datepicker('show');
    });

    //Added by Napat(Jame) 03/04/63
    $('#ocmIFHType').on('change',function(e){
        if(this.value != ''){
            $('.xWIFHDocType').css('display','none');
            $('.xWIFHDoctype_' + this.value).css('display','block');
        }else{
            $('.xWIFHDocType').css('display','block');
        }
    });

    //??????????????????????????????
    $('#obtResetSearch').on('click',function(e){
        $('#oetSearchAll').val('');
        $('#oetIFHDocDateFrom').val('');
        $('#oetIFHDocDateTo').val('');
        $('.selectpicker').val('').selectpicker('refresh');
    });
</script>
