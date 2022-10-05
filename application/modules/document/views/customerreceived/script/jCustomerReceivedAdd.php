
<script type="text/javascript">
    var nLangEdits        = '<?php echo $this->session->userdata("tLangEdit");?>';
    var tUsrApvName       = '<?php echo $this->session->userdata("tSesUsername");?>';
    var tSesUsrLevel      = '<?php echo $this->session->userdata('tSesUsrLevel');?>';
    var tUserBchCode      = '<?php echo $this->session->userdata("tSesUsrBchCode");?>';
    var tUserBchName      = '<?php echo $this->session->userdata("tSesUsrBchName");?>';
    var tUserWahCode      = '<?php echo $this->session->userdata("tSesUsrWahCode");?>';
    var tUserWahName      = '<?php echo $this->session->userdata("tSesUsrWahName");?>';
    var tRoute                  = $('#ohdCRVRoute').val();
    var tCRVSesSessionID        = $("#ohdSesSessionID").val();
    var tCRVStaPdtPick          = $('#oetCRVStaPdtPick').val();
    var oetCRVPshType          = $('#oetCRVPshType').val();
    if( oetCRVPshType == '1' ){
        $('#obtCRVApproveDoc').hide();
    }else{
        $('#obtCRVApproveDoc').show();
    }
    
    if( tCRVStaPdtPick == '1' ){
        $('#obtCRVApproveDoc').hide();
    }else{
        $('#obtCRVApproveDoc').show();
    }


    
    $(document).ready(function(){
        JSxCRVCallPageHDDocRef();
        JSxCRVEventCheckShowHDDocRef();
        JSxCRVControlFormWhenCancelOrApprove();

        $('#odvCRVContentHDRef').hide();
        $('#obtCRVAddDocRef').hide();

        var nCrTerm = $('#ocmCRVTypePayment').val();
        if (nCrTerm == 2) {
            $('.xCNPanel_CreditTerm').show();
        }else{
            $('.xCNPanel_CreditTerm').hide();
        }
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
        $('.selectpicker').selectpicker('refresh');

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        $("#obtCRVSubmitFromDoc").removeAttr("disabled");


        var dCurrentDate    = new Date();
        if($('#oetCRVDocDate').val() == ''){
            $('#oetCRVDocDate').datepicker("setDate",dCurrentDate);
        }

        if($('#oetCRVDepositDate').val() == ''){
            $('#oetCRVDepositDate').datepicker("setDate",dCurrentDate);
        }


        $('.xCNTimePicker').datetimepicker({
            format: 'HH:mm:ss'
        });

        // $('.xCNMenuplus').unbind().click(function(){
        //     if($(this).hasClass('collapsed')){
        //         $('.xCNMenuplus').removeClass('collapsed').addClass('collapsed');
        //         $('.xCNMenuPanelData').removeClass('in');
        //     }
        // });

        $('.xWTooltipsBT').tooltip({'placement': 'bottom'});

        $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});

        $(".xWConditionSearchPdt.disabled").attr("disabled","disabled");


        $('#obtCRVDocBrowsePdt').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                JCNvCRVBrowsePdt();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        if($('#oetCRVFrmBchCode').val() == ""){
            $("#obtCRVFrmBrowseTaxAdd").attr("disabled","disabled");
        }

        /** =================== Event Search Function ===================== */
            $('#oliCRVMngPdtScan').unbind().click(function(){
                var tCRVSplCode  = $('#oetCRVFrmSplCode').val();
                if(typeof(tCRVSplCode) !== undefined && tCRVSplCode !== ''){
                    //Hide
                    $('#oetCRVFrmFilterPdtHTML').hide();
                    $('#obtCRVMngPdtIconSearch').hide();

                    //Show
                    $('#oetCRVFrmSearchAndAddPdtHTML').show();
                    $('#obtCRVMngPdtIconScan').show();
                }else{
                    var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                }
            });
            $('#oliCRVMngPdtSearch').unbind().click(function(){
                //Hide
                $('#oetCRVFrmSearchAndAddPdtHTML').hide();
                $('#obtCRVMngPdtIconScan').hide();
                //Show
                $('#oetCRVFrmFilterPdtHTML').show();
                $('#obtCRVMngPdtIconSearch').show();
            });
        /** =============================================================== */

        /** ===================== Set Date Autometic Doc ========================  */
            var dCurrentDate    = new Date();
            var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
            var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes();

            if($('#oetCRVDocDate').val() == ''){
                $('#oetCRVDocDate').datepicker("setDate",dCurrentDate);
            }

            if($('#oetCRVDocTime').val() == ''){
                $('#oetCRVDocTime').val(tCurrentTime);
            }

            if($('#oetCRVDepositDate').val() == ''){
                $('#oetCRVDepositDate').datepicker("setDate",dCurrentDate);
            }

            if($('#oetCRVDepositTime').val() == ''){
                $('#oetCRVDepositTime').val(tCurrentTime);
            }
        /** =============================================================== */

        /** =================== Event Date Function  ====================== */
            $('#obtCRVDocDate').unbind().click(function(){
                $('#oetCRVDocDate').datepicker('show');
            });

            $('#obtCRVDocTime').unbind().click(function(){
                $('#oetCRVDocTime').datetimepicker('show');
            });

            $('#obtCRVBrowseRefIntDocDate').unbind().click(function(){
                $('#oetCRVRefIntDocDate').datepicker('show');
            });

            $('#obtCRVRefDocDate').unbind().click(function(){
                $('#oetCRVRefDocDate').datepicker('show');
            });

            $('#obtCRVRefDocExtDate').unbind().click(function(){
                $('#oetCRVRefDocExtDate').datepicker('show');
            });

            $('#obtCRVTransDate').unbind().click(function(){
                $('#oetCRVTransDate').datepicker('show');
            });

            $('#obtCRVDepositDate').unbind().click(function(){
                $('#oetCRVDepositDate').datepicker('show');
            });

            $('#obtCRVDepositTime').unbind().click(function(){
                $('#oetCRVDepositTime').datetimepicker('show');
            });
        /** =============================================================== */

        /** ================== Check Box Auto GenCode ===================== */
            $('#ocbCRVStaAutoGenCode').on('change', function (e) {
                if($('#ocbCRVStaAutoGenCode').is(':checked')){
                    $("#oetCRVDocNo").val('');
                    $("#oetCRVDocNo").attr("readonly", true);
                    $('#oetCRVDocNo').closest(".form-group").css("cursor","not-allowed");
                    $('#oetCRVDocNo').css("pointer-events","none");
                    $("#oetCRVDocNo").attr("onfocus", "this.blur()");
                    $('#ofmCRVFormAdd').removeClass('has-error');
                    $('#ofmCRVFormAdd .form-group').closest('.form-group').removeClass("has-error");
                    $('#ofmCRVFormAdd em').remove();
                }else{
                    $('#oetCRVDocNo').closest(".form-group").css("cursor","");
                    $('#oetCRVDocNo').css("pointer-events","");
                    $('#oetCRVDocNo').attr('readonly',false);
                    $("#oetCRVDocNo").removeAttr("onfocus");
                }
            });
        /** =============================================================== */
    });

    // ========================================== Brows Option Conditon ===========================================
        // ตัวแปร Option Browse Modal คลังสินค้า
        var oUsrOption      = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var aArgReturn          = poDataFnc.aArgReturn;

            var oOptionReturn   = {
                Title: ["document/depositboxreservation/depositboxreservation","tCRVUserDepositToBox"],
                Table: { Master:"TCNMUser", PK:"FTUsrCode"},
                Join: {
                    Table: ["TCNMUser_L"],
                    On: ["TCNMUser.FTUsrCode = TCNMUser_L.FTUsrCode AND TCNMUser_L.FNLngID = '"+nLangEdits+"'"]
                },
                GrideView:{
                    ColumnPathLang: 'document/depositboxreservation/depositboxreservation',
                    ColumnKeyLang: ['tCRVUserCode','tCRVUserName'],
                    DataColumns: ['TCNMUser.FTUsrCode','TCNMUser_L.FTUsrName'],
                    DataColumnsFormat: ['',''],
                    ColumnsSize: ['15%','75%'],
                    Perpage: 5,
                    WidthModal: 50,
                    OrderBy: ['TCNMUser_L.FTUsrName ASC'],
                },
                CallBack: {
                    ReturnType  : 'S',
                    Value       : [tInputReturnCode,"TCNMUser.FTUsrCode"],
                    Text        : [tInputReturnName,"TCNMUser_L.FTUsrName"]
                },
                RouteAddNew: 'warehouse'
            }
            return oOptionReturn;
        }


        // ตัวแปร Option Browse Modal สาขา
        var oBranchOption = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tAgnCode            = poDataFnc.tAgnCode;
            var aArgReturn          = poDataFnc.aArgReturn;
            tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
            tSQLWhereBch = "";
            tSQLWhereAgn = "";

            if(tUsrLevel != "HQ"){
                tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
            }

            if(tAgnCode != ""){
                tSQLWhereAgn = "AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
            }
            // ตัวแปร ออฟชั่นในการ Return
            var oOptionReturn       = {
                Title: ['authen/user/user', 'tBrowseBCHTitle'],
                Table: {
                    Master  : 'TCNMBranch',
                    PK      : 'FTBchCode'
                },
                Join: {
                    Table   : ['TCNMBranch_L'],
                    On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,]
                },
                Where : {
                    Condition : [tSQLWhereBch,tSQLWhereAgn]
                },
                GrideView: {
                    ColumnPathLang      : 'authen/user/user',
                    ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                    ColumnsSize         : ['10%', '75%'],
                    DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                    DataColumnsFormat   : ['', ''],
                    WidthModal          : 50,
                    Perpage             : 10,
                    OrderBy             : ['TCNMBranch.FTBchCode DESC']
                },
                //DebugSQL : true,
                CallBack: {
                    ReturnType  : 'S',
                    Value       : [tInputReturnCode, "TCNMBranch.FTBchCode"],
                    Text        : [tInputReturnName, "TCNMBranch_L.FTBchName"]
                },
            };
            return oOptionReturn;
        }

        var oCstOption = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tAgnCode            = poDataFnc.tAgnCode;
            var aArgReturn          = poDataFnc.aArgReturn;
            // ตัวแปร ออฟชั่นในการ Return
            var oOptionReturn       = {
                Title: ['document/depositboxreservation/depositboxreservation', 'tCRVCstTitle'],
                Table: {
                    Master  : 'TCNMCst',
                    PK      : 'FTCstCode'
                },
                Join: {
                    Table   : ['TCNMCst_L'],
                    On      : ['TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = ' + nLangEdits]
                },
                GrideView: {
                    ColumnPathLang      : 'document/depositboxreservation/depositboxreservation',
                    ColumnKeyLang       : ['tCRVCstCode', 'tCRVCstName'],
                    ColumnsSize         : ['10%', '75%'],
                    DataColumns         : ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName','TCNMCst.FTCstTel','TCNMCst.FTCstEmail'],
                    DataColumnsFormat   : ['', ''],
                    DisabledColumns   : [2,3],
                    WidthModal          : 50,
                    Perpage             : 10,
                    OrderBy             : ['TCNMCst.FTCstCode DESC']
                },
                NextFunc:{
                    FuncName:'JSxNextFuncCRVCst',
                    ArgReturn: aArgReturn
                },
                //DebugSQL : true,
                CallBack: {
                    ReturnType  : 'S',
                    Value       : [tInputReturnCode, "TCNMCst.FTCstCode"],
                    Text        : [tInputReturnName, "TCNMCst_L.FTCstName"]
                },
            };
            return oOptionReturn;
        }

        function JSxNextFuncCRVCst(paData) {
            var tCRVCstTel = '';
            var tCRVCstMail = '';
            if (typeof(paData) != 'undefined' && paData != "NULL") {
                var aCRVCstData = JSON.parse(paData);
                tCRVCstTel  = aCRVCstData[0];
                tCRVCstMail = aCRVCstData[1];
            }
            $('#oetCRVCstTel').val(tCRVCstTel);
            $('#oetCRVCstMail').val(tCRVCstMail);
        }
    // ============================================================================================================

    // ========================================== Brows Event Conditon ===========================================
        // Event Browse User Deposit
        $('#obtCRVBrowseUser').unbind().click(function(){
            // var nStaSession = JCNxFuncChkSessionExpired();
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oCRVBrowsUsrOption   = undefined;
                oCRVBrowsUsrOption          = oUsrOption({
                    'tReturnInputCode'  : 'oetCRVUsrCode',
                    'tReturnInputName'  : 'oetCRVUsrName',
                    'aArgReturn'        : []
                });
                JCNxBrowseData('oCRVBrowsUsrOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtCRVBrowseBCH').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetCRVAgnCode').val();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oCRVBrowseBranchOption  = undefined;
                oCRVBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'oetCRVFrmBchCode',
                    'tReturnInputName'  : 'oetCRVFrmBchName',
                    'tAgnCode'          : tAgnCode,
                    'aArgReturn'        : ['FTBchCode','FTBchName'],
                });
                JCNxBrowseData('oCRVBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

        });

        $('#obtCRVBrowseCst').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetCRVAgnCode').val();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oCRVBrowseCstOption  = undefined;
                oCRVBrowseCstOption         = oCstOption({
                    'tReturnInputCode'  : 'oetCRVCstCode',
                    'tReturnInputName'  : 'oetCRVCstName',
                    'aArgReturn'        : ['FTCstTel','FTCstEmail'],
                });
                JCNxBrowseData('oCRVBrowseCstOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

        });

        $('#obtCRVBrowseRefDocInt').on('click',function(){
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            var tDocType = $('#ocbCRVRefDoc').val();
            var tRefKeyOld = $('#ohdRefKeyOld').val();
            var tRefKeyNew = $('#oetCRVRefDoc').val();
            if (tRefKeyOld != '' && tRefKeyNew != '') {
                if (tRefKeyOld != tRefKeyNew) {
                    $('#ohdCRVTypeChange').val('Ref');
                    $('#odvCRVModalChangeData #ospCRVTxtWarningAlert').text('<?php echo language('document/depositboxreservation/depositboxreservation', 'tCRVChangeDocType') ?>');
                    $('#odvCRVModalChangeData').modal('show')
                }else{
                    JSxCallCRVRefIntDoc(tDocType);
                }
            }else{
                JSxCallCRVRefIntDoc(tDocType);
            }
        });

        $('#obtCRVChangeData').on('click',function(){
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            //เคลีย SPL
            $('#oetCRVFrmSplCode').val('');
            $('#oetCRVFrmSplName').val('');
            $('#oetCRVSplName').val('');
            $("#ocmCRVTypePayment").val("1").selectpicker('refresh');
            $("#ocmCRVFrmSplInfoVatInOrEx").val("1").selectpicker('refresh');

            var tCRVChangeType = $('#ohdCRVTypeChange').val()
            if (tCRVChangeType == 'Ref') {
                var tDocType = $('#ocbCRVRefDoc').val();
                JSxCallCRVRefIntDoc(tDocType);
            }else if(tCRVChangeType == 'Spl'){
                $('#otbCRVDocPdtAdvTableList .xWDelDocRef').click();
                $('#otbCRVDocRef .xWDelDocRef').click();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSxCheckPinMenuClose();
                    window.oCRVBrowseSplOption   = undefined;
                    oCRVBrowseSplOption          = oSplOption({
                        'tParamsAgnCode'    : '<?//=$this->session->userdata("tSesUsrAgnCode")?>',
                        'tReturnInputCode'  : 'oetCRVFrmSplCode',
                        'tReturnInputName'  : 'oetCRVFrmSplName',
                        'aArgReturn'        : ['FTSplCode', 'FTSplName']
                    });
                    JCNxBrowseData('oCRVBrowseSplOption');
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }else if(tCRVChangeType == 'Agn'){
                $('#ofmCRVFormAdd').find('input').val('');
                $('#ofmCRVFormAdd').find('select').val(1).selectpicker("refresh");
                $('#otbCRVDocPdtAdvTableList .xWDelDocRef').click();
                $('#otbCRVDocRef .xWDelDocRef').click();
                var nStaSession = JCNxFuncChkSessionExpired();
                if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                    JSxCheckPinMenuClose();
                    window.oCRVBrowseAgencyOption = oBrowseAgn({
                        'tReturnInputCode': 'oetCRVAgnCode',
                        'tReturnInputName': 'oetCRVAgnName',
                    });
                    JCNxBrowseData('oCRVBrowseAgencyOption');
                } else {
                    JCNxShowMsgSessionExpired();
                }
            }
        });

        //Browse เอกสารอ้างอิงภายใน
        function JSxCallCRVRefIntDoc(ptDocType){
            var tBCHCode = $('#oetCRVFrmBchCode').val()
            var tBCHName = $('#oetCRVFrmBchName').val()
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docCRVCallRefIntDoc",
                data: {
                    'tDocType'     : ptDocType,
                    'tBCHCode'      : tBCHCode,
                    'tBCHName'      : tBCHName,
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    JCNxCloseLoading();
                    $('#odvCRVFromRefIntDoc').html(oResult);
                    if (ptDocType == 1 ) {
                        $('#odvCRVModalRefIntDoc #olbTextModalHead').text('<?php echo language('document/purchaseorder/purchaseorder', 'อ้างอิงเอกสารใบสั่งซื้อ') ?>');
                    } else {
                        $('#odvCRVModalRefIntDoc #olbTextModalHead').text('<?php echo language('document/purchaseorder/purchaseorder', 'อ้างอิงเอกสารใบขาย') ?>');
                    }
                    
                    $('#odvCRVModalRefIntDoc').modal({
                        backdrop : 'static' , 
                        show : true
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }

        $('#obtConfirmRefDocInt').click(function(){
            
            var oListChecked = $(".ocbRefIntDocDT:checked");
            var oListCheckedLength = oListChecked.length;
            if(oListCheckedLength > 0){
                $('#odvCRVModalRefIntDoc').modal('hide');
                var tRefIntDocNo =  $('.xPurchaseInvoiceRefInt.active').data('docno');
                var tRefIntDocDate =  $('.xPurchaseInvoiceRefInt.active').data('docdate');
                var tRefIntDocTime =  $('.xPurchaseInvoiceRefInt.active').data('doctime');
                var tRefIntBchCode =  $('.xPurchaseInvoiceRefInt.active').data('bchcode');
                var tRefIntBchName =  $('.xPurchaseInvoiceRefInt.active').data('bchname');
                var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm){
                        return $(this).val();
                    }).get();

                var tSplStaVATInOrEx =  $('.xPurchaseInvoiceRefInt.active').data('vatinroex');
                var cSplCrLimit =  $('.xPurchaseInvoiceRefInt.active').data('crtrem');
                var nSplCrTerm =  $('.xPurchaseInvoiceRefInt.active').data('crlimit');
                var tSplCode =  $('.xPurchaseInvoiceRefInt.active').data('splcode');
                var tSplName =  $('.xPurchaseInvoiceRefInt.active').data('splname');
                var tDoctype =  $('.xPurchaseInvoiceRefInt.active').data('doctype');

                var poParams = {
                        FCSplCrLimit        : cSplCrLimit,
                        FTSplCode           : tSplCode,
                        FTSplName           : tSplName,
                        FTSplStaVATInOrEx   : tSplStaVATInOrEx,
                        FTRefIntDocNo       : tRefIntDocNo,
                        FTRefIntDocDate     : tRefIntDocDate,
                        FTRefIntDocTime     : tRefIntDocTime,
                        FTBchCode           : tRefIntBchCode,
                        FTBchName           : tRefIntBchName,
                        tDoctype            : tDoctype
                    };

                if (typeof tRefIntDocNo === "undefined") {
                    $('#odvCRVModalPONoFound').modal('show');
                } else {
                    JSxCRVSetPanelSupplierData(poParams);
                    $('#oetCRVRefIntDoc').val(tRefIntDocNo);
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docCRVCallRefIntDocInsertDTToTemp",
                        data: {
                            'tCRVDocNo'          : $('#oetCRVDocNo').val(),
                            'tCRVFrmBchCode'     : $('#oetCRVFrmBchCode').val(),
                            'tCRVOptionAddPdt'   : $('#ocmCRVFrmInfoOthReAddPdt').val(),
                            'tRefIntDocNo'      : tRefIntDocNo,
                            'tRefIntBchCode'    : tRefIntBchCode,
                            'aSeqNo'            : aSeqNo,
                            'tDoctype'          : tDoctype
                        },
                        cache: false,
                        Timeout: 0,
                        success: function (oResult){
                            // FSvCRVNextFuncB4SelPDT();
                            JSvCRVLoadPdtDataTableHtml();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }
            }else{
                FSvCMNSetMsgErrorDialog("กรุณาเลือกสินค้า");
                return;
            }
        });

        $('#obtConfirmPo').on('click',function(){
            $('#odvCRVModalRefIntDoc').modal('show');
        });

        // Function : ฟังก์ชั่นเซทข้อมูล ผู้จำหน่าย
        function JSxCRVSetPanelSupplierData(poParams){
            // Reset Panel เป็นค่าเริ่มต้น
            $("#ocmCRVFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
            $("#ocmCRVTypePayment.selectpicker").val("2").selectpicker("refresh");
            $("#oetCRVRefIntDoc").val(poParams.FTRefIntDocNo);
            $("#oetCRVRefDocDate").val(poParams.FTRefIntDocDate).datepicker("refresh");
            $("#ohdCRVRefDocTime").val(poParams.FTRefIntDocTime);
            // ประเภทภาษี
            if(poParams.FTSplStaVATInOrEx == 1){
                // รวมใน
                $("#ocmCRVFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
            }else{
                // แยกนอก
                $("#ocmCRVFrmSplInfoVatInOrEx.selectpicker").val("2").selectpicker("refresh");
            }

            // ประเภทชำระเงิน
            if(poParams.FCSplCrLimit > 0){
                // เงินเชื่อ
                $("#ocmCRVTypePayment.selectpicker").val("2").selectpicker("refresh");
                $('.xCNPanel_CreditTerm').show();
            }else{
                // เงินสด
                $("#ocmCRVTypePayment.selectpicker").val("1").selectpicker("refresh");
                $('.xCNPanel_CreditTerm').hide();

            }
            //สาขา
            // $("#oetCRVFrmBchCode").val(poParams.FTBchCode);
            // $("#oetCRVFrmBchName").val(poParams.FTBchName);

            //ผู้ขาย
            $("#oetCRVFrmSplCode").val(poParams.FTSplCode);
            $("#oetCRVFrmSplName").val(poParams.FTSplName);
            $("#oetCRVSplName").val(poParams.FTSplName);
            $("#oetCRVFrmSplInfoCrTerm").val(poParams.FCSplCrLimit);

            $("#ohdCRVDocType").val(poParams.tDoctype);
        }

//------------------------------------------------------------------------------------------------//

    // Validate From Add Or Update Document
    function JSxCRVValidateFormDocument(){
        if($("#ohdCRVCheckClearValidate").val() != 0){
            $('#ofmCRVFormAdd').validate().destroy();
        }

        $('#ofmCRVFormAdd').validate({
            focusInvalid: true,
            rules: {
                oetCRVDocNo : {
                    "required" : {
                        depends: function (oElement) {
                            if($("#ohdCRVRoute").val()  ==  "docCRVEventAdd"){
                                if($('#ocbCRVStaAutoGenCode').is(':checked')){
                                    return false;
                                }else{
                                    return true;
                                }
                            }else{
                                return false;
                            }
                        }
                    }
                },
                oetCRVFrmBchName    : {"required" : true},
                oetCRVFrmSplName : {"required" : true},
                oetCRVFrmWahName : {"required" : true},
            },
            messages: {
                oetCRVDocNo      : {"required" : $('#oetCRVDocNo').attr('data-validate-required')},
                oetCRVFrmBchName : {"required" : $('#oetCRVFrmBchName').attr('data-validate-required')},
                oetCRVFrmSplName : {"required" : $('#oetCRVFrmSplName').attr('data-validate-required')},
                oetCRVFrmWahName : {"required" : $('#oetCRVFrmWahName').attr('data-validate-required')},
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                error.addClass("help-block");
                if(element.prop("type") === "checkbox") {
                    error.appendTo(element.parent("label"));
                }else{
                    var tCheck  = $(element.closest('.form-group')).find('.help-block').length;
                    if(tCheck == 0) {
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function (form){
                if(!$('#ocbCRVStaAutoGenCode').is(':checked')){
                    JSxCRVValidateDocCodeDublicate();
                }else{
                    if($("#ohdCRVCheckSubmitByButton").val() == 1){
                        JSxCRVSubmitEventByButton();
                    }
                }
            },
        });
    }

    // Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
    function JSxCRVValidateDocCodeDublicate(){
        //JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName'    : 'TAPTDoHD',
                'tFieldName'    : 'FTXphDocNo',
                'tCode'         : $('#oetCRVDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                $("#ohdCRVCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdCRVCheckClearValidate").val() != 1) {
                    $('#ofmCRVFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdCRVRoute").val() == "docCRVEventAdd"){
                        if($('#ocbCRVStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdCRVCheckDuplicateCode").val() == 1) {
                                return false;
                            }else{
                                return true;
                            }
                        }
                    }else{
                        return true;
                    }
                });

                // Set Form Validate From Add Document
                $('#ofmCRVFormAdd').validate({
                    focusInvalid: false,
                    onclick: false,
                    onfocusout: false,
                    onkeyup: false,
                    rules: {
                        oetCRVDocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetCRVDocNo : {"dublicateCode"  : $('#oetCRVDocNo').attr('data-validate-duplicate')}
                    },
                    errorElement: "em",
                    errorPlacement: function (error, element) {
                        error.addClass("help-block");
                        if(element.prop("type") === "checkbox") {
                            error.appendTo(element.parent("label"));
                        }else{
                            var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                            if (tCheck == 0) {
                                error.appendTo(element.closest('.form-group')).trigger('change');
                            }
                        }
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).closest('.form-group').addClass("has-error");
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).closest('.form-group').removeClass("has-error");
                    },
                    submitHandler: function (form) {
                        if($("#ohdCRVCheckSubmitByButton").val() == 1) {
                            JSxCRVSubmitEventByButton();
                        }
                    }
                })

                if($("#ohdCRVCheckClearValidate").val() != 1) {
                    $("#ofmCRVFormAdd").submit();
                    $("#ohdCRVCheckClearValidate").val(1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function: Validate Success And Send Ajax Add/Update Document
    function JSxCRVSubmitEventByButton(ptType = ''){
        var tCRVDocNo = '';
        if($("#ohdCRVRoute").val() !=  "docCRVEventAdd"){
            var tCRVDocNo    = $('#oetCRVDocNo').val();
        }
        $('#obtCRVSubmitFromDoc').attr('disabled',true);
        $.ajax({
            type: "POST",
            url: "docCRVChkHavePdtForDocDTTemp",
            data: {
                'ptCRVDocNo'         : tCRVDocNo,
                'tCRVSesSessionID'   : $('#ohdSesSessionID').val(),
                'tCRVUsrCode'        : $('#ohdCRVUsrCode').val(),
                'tCRVLangEdit'       : $('#ohdCRVLangEdit').val(),
                'tSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                // JCNxCloseLoading();
                var aDataReturnChkTmp   = JSON.parse(oResult);
                $('.xWCRVDisabledOnApv').attr('disabled',false);
                if (aDataReturnChkTmp['nStaReturn'] == '1'){
                    $.ajax({
                        type    : "POST",
                        url     : $("#ohdCRVRoute").val(),
                        data    : $("#ofmCRVFormAdd").serialize(),
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            // JCNxCloseLoading();
                            var aDataReturnEvent    = JSON.parse(oResult);
                            if(aDataReturnEvent['nStaReturn'] == '1'){
                                var nCRVStaCallBack      = aDataReturnEvent['nStaCallBack'];
                                var nCRVDocNoCallBack    = aDataReturnEvent['tCodeReturn'];
                                var nCRVPayType          = $('#ocmCRVTypePayment').val();
                                var nCRVVatInOrEx        = $('#ocmCRVFrmSplInfoVatInOrEx').val();
                                var nCRVStaRef           = $('#ocmCRVFrmInfoOthRef').val();

                                var oCRVCallDataTableFile = {
                                    ptElementID : 'odvCRVShowDataTable',
                                    ptBchCode   : $('#oetCRVFrmBchCode').val(),
                                    ptDocNo     : nCRVDocNoCallBack,
                                    ptDocKey    :'TAPTDoHD',
                                }
                                JCNxUPFInsertDataFile(oCRVCallDataTableFile);
                                if(ptType == 'approve'){
                                    JSxCRVApproveDocument(false);
                                }else{
                                    switch(nCRVStaCallBack){
                                        case '1' :
                                            JSvCRVCallPageEdit(nCRVDocNoCallBack,nCRVPayType,nCRVVatInOrEx,nCRVStaRef);
                                        break;
                                        case '2' :
                                            JSvCRVCallPageAddDoc();
                                        break;
                                        case '3' :
                                            JSvCRVCallPageList();
                                        break;
                                        default :
                                            JSvCRVCallPageEdit(nCRVDocNoCallBack,nCRVPayType,nCRVVatInOrEx,nCRVStaRef);
                                    }
                                }
                                $("#obtCRVSubmitFromDoc").removeAttr("disabled");
                            }else{
                                var tMessageError = aDataReturnEvent['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                                $("#obtCRVSubmitFromDoc").removeAttr("disabled");
                            }
                        },
                        error   : function (jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }else if(aDataReturnChkTmp['nStaReturn'] == '800'){
                    var tMsgDataTempFound   = aDataReturnChkTmp['tStaMessg'];
                    FSvCMNSetMsgWarningDialog('<p class="text-left">'+tMsgDataTempFound+'</p>');
                }else{
                    var tMsgErrorFunction   = aDataReturnChkTmp['tStaMessg'];
                    FSvCMNSetMsgErrorDialog('<p class="text-left">'+tMsgErrorFunction+'</p>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //นับจำนวนรายการท้ายเอกสาร
    function JSxCRVCountPdtItems(){
        var nPdtItems = $('.xWPdtItem').length;
        $('.xShowQtyFooter').text(accounting.formatNumber(nPdtItems, 0, ','));
    }

    $('#ocmCRVTypePayment').on('change', function() {
        if (this.value == 1) {
            $('.xCNPanel_CreditTerm').hide();
        } else {
            $('.xCNPanel_CreditTerm').show();
        }
    });


    // Rabbit MQ
    function JSoCRVCallSubscribeMQ() {
        // Document variable
        var tLangCode = $("#ohdCRVLangEdit").val();
        var tUsrBchCode = $("#ohdCRVBchCode").val();
        var tUsrApv = '<?=$this->session->userdata('tSesUsername')?>';
        var tDocNo = $("#oetCRVDocNo").val();
        var tPrefix = "RESCRV";
        var tStaApv = $("#ohdCRVStaApv").val();
        var tStaDelMQ = 1;
        var tQName = tPrefix + "_" + tDocNo + "_" + tUsrApv;

        // MQ Message Config
        var poDocConfig = {
            tLangCode: tLangCode,
            tUsrBchCode: tUsrBchCode,
            tUsrApv: tUsrApv,
            tDocNo: tDocNo,
            tPrefix: tPrefix,
            tStaDelMQ: tStaDelMQ,
            tStaApv: tStaApv,
            tQName: tQName
        };

        // RabbitMQ STOMP Config
        var poMqConfig = {
            host: "ws://" + oSTOMMQConfig.host + ":15674/ws",
            username: oSTOMMQConfig.user,
            password: oSTOMMQConfig.password,
            vHost: oSTOMMQConfig.vhost
        };

        // Update Status For Delete Qname Parameter
        var poUpdateStaDelQnameParams = {
            ptDocTableName: "TAPTDoHD",
            ptDocFieldDocNo: "FTXphDocNo",
            ptDocFieldStaApv: "FTXphStaPrcStk",
            ptDocFieldStaDelMQ: "FTXphStaDelMQ",
            ptDocStaDelMQ: tStaDelMQ,
            ptDocNo: tDocNo
        };

        // Callback Page Control(function)
        var poCallback = {
            tCallPageEdit: "JSvCRVCallPageEdit",
            tCallPageList: "JSvCRVCallPageList"
        };

        // Check Show Progress %
        FSxCMNRabbitMQMessage(poDocConfig, poMqConfig, poUpdateStaDelQnameParams, poCallback);
    }

    //พิมพ์เอกสาร
    function JSxCRVPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tCRVBchCode); ?>'},
            {"DocCode"      : '<?=@$tCRVDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tCRVBchCode;?>'}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillReceive?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

    //โหลด Table อ้างอิงเอกสารทั้งหมด
    function JSxCRVCallPageHDDocRef(){
        // var tDocNo  = "";
        // if ($("#ohdCRVRoute").val() == "docCRVEventEdit") {
            // tDocNo = $('#oetCRVDocNo').val();
        // }

        $.ajax({
            type    : "POST",
            url     : "docCRVPageHDDocRef",
            data:{
                'ptDocNo' : $('#oetCRVDocNo').val()
            },
            cache   : false,
            timeout : 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                    $('#odvCRVTableHDRef').html(aResult['tViewPageHDRef']);
                    JCNxCloseLoading();
                }else{
                    var tMessageError = aResult['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // เวลา Click Tab1 ให้ Button Hide
    $('#oliCRVContentProduct').click(function() {
        $('#odvCRVContentHDRef').hide();
        $('#odvCRVContentProduct').show();
    });

    // เวลา Click Tab2 ให้ Button Show
    $('#oliCRVContentHDRef').click(function() {
        $('#odvCRVContentProduct').hide();
        $('#odvCRVContentHDRef').show();
        $('#obtCRVAddDocRef').show();
    });

    //Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    function JSxCRVEventCheckShowHDDocRef(){
        var tCRVRefType = $('#ocbCRVRefType').val();
        if( tCRVRefType == '1' ){
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();
        }else{
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }

    //กดเพิ่มเอกสารอ้างอิง (ภายใน ภายนอก)
    $('#obtCRVAddDocRef').off('click').on('click',function(){
        $('#ofmCRVFormAddDocRef').validate().destroy();
        JSxCRVEventClearValueInFormHDDocRef();
        $('.xWShowRefExt').hide();
        $('.xWShowRefInt').show();
        $('#odvCRVModalAddDocRef').modal('show');
    });

    //เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    $('#ocbCRVRefType').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        JSxCRVEventCheckShowHDDocRef();
    });

    $('#ocbCRVRefDoc').off('change').on('change',function(){
        var tRefDoc = $('#ocbCRVRefDoc').val();
        if (tRefDoc == 1) {
            $('#oetCRVRefDoc').val('PO');
        }else{
            $('#oetCRVRefDoc').val('ABB');
        }
        
    });

    //เคลียร์ค่า
    function JSxCRVEventClearValueInFormHDDocRef(){
        $('#oetCRVRefDocNo').val('');
        $('#oetCRVRefDocDate').val('');
        $('#oetCRVRefIntDoc').val('');
        $('#oetCRVDocRefIntName').val('');
        $('#oetCRVRefKey').val('');
        
        var tRefDoc = $('#oetCRVRefDoc').val();
        if (tRefDoc == 'PO') {
            $("#ocbCRVRefDoc").val("1").selectpicker('refresh');
        }else if (tRefDoc == 'ABB'){
            $("#ocbCRVRefDoc").val("2").selectpicker('refresh');  
        }else{
            $("#ocbCRVRefDoc").val("2").selectpicker('refresh');
        }
        
        $("#ocbCRVRefType").val("1").selectpicker('refresh');
    }

    //กดยืนยันบันทึกลง Temp
    $('#ofmCRVFormAddDocRef').off('click').on('click',function(){
        $('#ofmCRVFormAddDocRef').validate().destroy();
        $('#ofmCRVFormAddDocRef').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetCRVRefIntDoc    : {"required" : true}
            },
            messages: {
                oetCRVRefIntDoc    : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                error.addClass("help-block");
                if(element.prop("type") === "checkbox") {
                    error.appendTo(element.parent("label"));
                }else{
                    var tCheck  = $(element.closest('.form-group')).find('.help-block').length;
                    if(tCheck == 0) {
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function (form){
                //JCNxOpenLoading();

                var tDocType = $('#ocbCRVRefDoc').val();
                if (tDocType == 1) {
                    var tRefKey   = "PO";
                }else{
                    var tRefKey   = "ABB";
                }
                if($('#ocbCRVRefType').val() == 1){ //อ้างอิงเอกสารภายใน
                    var tDocNoRef = $('#oetCRVRefIntDoc').val();
                }else{ //อ้างอิงเอกสารภายนอก
                    var tDocNoRef = $('#oetCRVRefDocNo').val();
                    var tRefKey   = $('#oetCRVRefKey').val();
                }

                $.ajax({
                    type    : "POST",
                    url     : "docCRVEventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld'     : $('#oetCRVRefDocNoOld').val(),
                        'ptDocNo'           : $('#oetCRVDocNo').val(),
                        'ptRefType'         : $('#ocbCRVRefType').val(),
                        'ptRefDocNo'        : tDocNoRef,
                        'pdRefDocDate'      : $('#oetCRVRefDocDate').val()+' '+$('#ohdCRVRefDocTime').val(),
                        'ptRefKey'          : tRefKey
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult){
                        var aReturnData = JSON.parse(oResult);
                        JSxCRVEventClearValueInFormHDDocRef();
                        $('#odvCRVModalAddDocRef').modal('hide');

                        if (aReturnData['nStaEvent'] == 2) {
                            FSvCMNSetMsgErrorDialog("เลขที่เอกสารนี้ถูกอ้างอิงแล้ว");
                        }

                        JSxCRVCallPageHDDocRef();
                        JCNxCloseLoading();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    });

    // Function : Add Product Into Table Document DT Temp
function JCNvCRVBrowsePdt() {
    $.ajax({
        type: "POST",
        url: "BrowseDataPDT",
        data: {
            Qualitysearch: [],
            PriceType: [
                "Cost", "tCN_Cost", "Company", "1"
            ],
            SelectTier: ["Barcode"],
            ShowCountRecord: 10,
            NextFunc: "FSvCRVNextFuncB4SelPDT",
            ReturnType: "M",
            'aAlwPdtType' : ['T1','T3','T4','T5','T6','S2','S3','S4'],
            'Where' : [" AND Products.FTPdtStkControl = 1 "]
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $("#odvModalDOCPDT").modal({ backdrop: "static", keyboard: false });
            $("#odvModalDOCPDT").modal({ show: true });
            // remove localstorage
            localStorage.removeItem("LocalItemDataPDT");
            $("#odvModalsectionBodyPDT").html(tResult);
            $("#odvModalDOCPDT #oliBrowsePDTSupply").css('display', 'none');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

</script>
