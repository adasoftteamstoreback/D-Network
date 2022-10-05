<!-- Filter -->
<section>
    <div class="col-md-3 col-xs-3 col-sm-3">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOLabelFrmBranch')?></label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control xCNHide xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote"
                        id="oetDLVRefIntBchCode"
                        name="oetDLVRefIntBchCode"
                        maxlength="5"
                        value="<?=$tBCHCode?>"
                        data-bchcodeold = ""
                    >
                    <input
                        type="text"
                        class="form-control xWPointerEventNone"
                        id="oetDLVRefIntBchName"
                        name="oetDLVRefIntBchName"
                        maxlength="100"
                        value="<?=$tBCHName?>"
                        readonly
                    >
                    <input
                        type="hidden"
                        class="form-control xWPointerEventNone"
                        id="oetDLVRefIntRefDoc"
                        name="oetDLVRefIntRefDoc"
                        maxlength="100"
                        value="<?=$tRefDoc?>"

                    >
                    <span class="input-group-btn xWConditionSearchPdt">
                        <button id="obtDLVBrowseBchRefIntDoc" type="button" class="btn xCNBtnBrowseAddOn" >
                            <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- เลขที่เอกสาร -->
    <div class="col-md-2 col-xs-2 col-sm-2">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocNo')?></label>
                <input
                    type="text"
                    class="form-control"
                    id="oetDLVRefIntDocNo"
                    name="oetDLVRefIntDocNo"
                    maxlength="100"
                    value=""
                    placeholder="<?php echo language('document/purchaseorder/purchaseorder', 'tPOTBDocNo')?>"
                >
            </div>
        </div>
    </div>
    <!-- วันที่เอกสารเริ่ม -->
    <div class="col-md-2 col-xs-2 col-sm-2">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPORefIntDocDateFrm')?></label>
                    <div class="input-group">
                    <input
                        type="text"
                        class="form-control xCNDatePicker xCNInputMaskDate"
                        id="oetDLVRefIntDocDateFrm"
                        name="oetDLVRefIntDocDateFrm"
                        placeholder="YYYY-MM-DD"
                        value=""
                    >
                    <span class="input-group-btn">
                        <button id="obtDLVBrowseRefExtDocDateFrm" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- วันที่เอกสารสิ้นสุด -->
    <div class="col-md-2 col-xs-2 col-sm-2">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tPORefIntDocDateTo')?></label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control xCNDatePicker xCNInputMaskDate"
                        id="oetDLVRefIntDocDateTo"
                        name="oetDLVRefIntDocDateTo"
                        placeholder="YYYY-MM-DD"
                        value=""
                    >
                    <span class="input-group-btn">
                        <button id="obtDLVBrowseRefExtDocDateTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- สถานะเอกสาร -->
    <div class="col-md-2 col-xs-2 col-sm-2">
        <div class="form-group">
            <label class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder','tPORefIntDocStatus');?></label>
            <select class="selectpicker form-control" id="oetDLVRefIntStaDoc" name="oetDLVRefIntStaDoc" maxlength="1">
                <option value="1" ><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmValStaApv1');?></option>
                <option value="2" ><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmValStaApv');?></option>
                <option value="3" ><?php echo language('document/purchaseorder/purchaseorder','tPOLabelFrmValStaDoc3');?></option>
            </select>
        </div>
    </div>
    <!-- ปุ่มค้นหา -->
    <div class="col-md-1 col-xs-1 col-sm-1" style="padding-top: 24px;">
        <button id="obtRefIntDocFilter" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" ><?= language('document/purchaseorder/purchaseorder', 'tPORefIntDocFilter')?></button>
    </div>
</section>

<!-- Document -->
<section>
    <div id="odvRefIntDocHDDataTable"></div>
</section>

<!-- Items -->
<section>
    <div id="odvRefIntDocDTDataTable"></div>
</section>

<script>

    $(document).ready(function(){

        $('.selectpicker').selectpicker('refresh');

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        $('#obtDLVBrowseBchRefIntDoc').click(function(){
            $('#odvDLVModalRefIntDoc').modal('hide');
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oDLVBrowseRefBranchOption  = undefined;
                oDLVBrowseRefBranchOption         = oBranchRefOption({
                    'tReturnInputCode'  : 'oetDLVRefIntBchCode',
                    'tReturnInputName'  : 'oetDLVRefIntBchName',
                    'tNextFuncName'     : 'JSxDLVRefIntNextFunctBrowsBranch',
                    'tAgnCode'          : $('#oetDLVAgnCode').val(),
                    'aArgReturn'        : ['FTBchCode','FTBchName'],
                });
                JCNxBrowseData('oDLVBrowseRefBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // ตัวแปร Option Browse Modal สาขา
        var oBranchRefOption = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tAgnCode            = poDataFnc.tAgnCode;
            var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
            tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
            tWhere = "";
            if(tUsrLevel != "HQ"){
                tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
            }else{
                tWhere = "";
            }

            if(tAgnCode!=''){
                tSQLWhere = " AND TCNMBranch.FTAgnCode ='"+tAgnCode+"' ";
            }

            // ตัวแปร ออฟชั่นในการ Return
            var oOptionReturn       = {
                Title: ['authen/user/user', 'tBrowseBCHTitle'],
                Table: {
                    Master  : 'TCNMBranch',
                    PK      : 'FTBchCode'
                },
                Join :{
                    Table : ['TCNMBranch_L'],
                    On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
                },
                Where : {
                    Condition : [tWhere]
                },
                GrideView: {
                    ColumnPathLang      : 'authen/user/user',
                    ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                    ColumnsSize         : ['10%', '75%'],
                    DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                    DataColumnsFormat   : ['', ''],
                    DisabledColumns     : [2,3],
                    WidthModal          : 30,
                    Perpage             : 10,
                    OrderBy             : ['TCNMBranch.FTBchCode'],
                    SourceOrder         : "ASC"
                },
                CallBack: {
                    ReturnType  : 'S',
                    Value       : [tInputReturnCode, "TCNMBranch.FTBchCode"],
                    Text        : [tInputReturnName, "TCNMBranch_L.FTBchName"]
                },
                NextFunc: {
                    FuncName    : tNextFuncName,
                    ArgReturn   : aArgReturn
                }
            };
            return oOptionReturn;
        }

        $('#obtDLVBrowseRefExtDocDateFrm').unbind().click(function(){
            $('#oetDLVRefIntDocDateFrm').datepicker('show');
        });

        $('#obtDLVBrowseRefExtDocDateTo').unbind().click(function(){
            $('#oetDLVRefIntDocDateTo').datepicker('show');
        });

        JSxRefIntDocHDDataTable();
    });

    $('#odvDLVModalRefIntDoc').on('hidden.bs.modal', function () {
        $('#wrapper').css('overflow','auto');
        $('#odvDLVModalRefIntDoc').css('overflow','auto');
    });

    $('#odvDLVModalRefIntDoc').on('show.bs.modal', function () {
        $('#wrapper').css('overflow','hidden');
        $('#odvDLVModalRefIntDoc').css('overflow','auto');
    });

    function JSxDLVRefIntNextFunctBrowsBranch(ptData){
        JSxCheckPinMenuClose();
        $('#odvDLVModalRefIntDoc').modal("show");
    }

    $('#obtRefIntDocFilter').on('click',function(){
        JSxRefIntDocHDDataTable();
    });

    //เรียกตารางเลขที่เอกสารอ้างอิง
    function JSxRefIntDocHDDataTable(pnPage){
        if(pnPage == '' || pnPage == null){
            var pnNewPage = 1;
        }else{
            var pnNewPage = pnPage;
        }
        var nPageCurrent = pnNewPage;
        var tDLVRefIntBchCode       = $('#oetDLVRefIntBchCode').val();
        var tDLVRefIntDocNo         = $('#oetDLVRefIntDocNo').val();
        var tDLVRefIntDocDateFrm    = $('#oetDLVRefIntDocDateFrm').val();
        var tDLVRefIntDocDateTo     = $('#oetDLVRefIntDocDateTo').val();
        var tDLVRefIntStaDoc        = $('#oetDLVRefIntStaDoc').val();
        var tDLVRefIntIntRefDoc     = $('#oetDLVRefIntRefDoc').val();
        if (nPageCurrent==NaN) {
            nPageCurrent = 1;
        }

        $.ajax({
            type    : "POST",
            url     : "docDLVCallRefIntDocDataTable",
            data    : {
                'tDLVRefIntBchCode'     : tDLVRefIntBchCode,
                'tDLVRefIntDocNo'       : tDLVRefIntDocNo,
                'tDLVRefIntDocDateFrm'  : tDLVRefIntDocDateFrm,
                'tDLVRefIntDocDateTo'   : tDLVRefIntDocDateTo,
                'tDLVRefIntStaDoc'      : tDLVRefIntStaDoc,
                'nDLVRefIntPageCurrent' : nPageCurrent,
                'tDLVRefIntIntRefDoc'   : tDLVRefIntIntRefDoc,
                'tCstCode'              : $('#oetDLVCstCode').val()
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                $('#odvRefIntDocHDDataTable').html(oResult);
                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

</script>

