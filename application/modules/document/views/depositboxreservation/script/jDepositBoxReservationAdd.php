
<script type="text/javascript">
    var nLangEdits        = '<?php echo $this->session->userdata("tLangEdit");?>';
    var tUsrApvName       = '<?php echo $this->session->userdata("tSesUsername");?>';
    var tSesUsrLevel      = '<?php echo $this->session->userdata('tSesUsrLevel');?>';
    var tUserBchCode      = '<?php echo $this->session->userdata("tSesUsrBchCode");?>';
    var tUserBchName      = '<?php echo $this->session->userdata("tSesUsrBchName");?>';
    var tUserWahCode      = '<?php echo $this->session->userdata("tSesUsrWahCode");?>';
    var tUserWahName      = '<?php echo $this->session->userdata("tSesUsrWahName");?>';
    var tRoute                 = $('#ohdDBRRoute').val();
    var tDBRSesSessionID        = $("#ohdSesSessionID").val();


    $(document).ready(function(){
        JSxDBRCallPageHDDocRef();
        JSxDBREventCheckShowHDDocRef();
        JSxDBRControlFormWhenCancelOrApprove();

        $('#odvDBRContentHDRef').hide();
        $('#obtDBRAddDocRef').hide();

        var nCrTerm = $('#ocmDBRTypePayment').val();
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

        $("#obtDBRSubmitFromDoc").removeAttr("disabled");


        var dCurrentDate    = new Date();
        if($('#oetDBRDocDate').val() == ''){
            $('#oetDBRDocDate').datepicker("setDate",dCurrentDate);
        }

        if($('#oetDBRDepositDate').val() == ''){
            $('#oetDBRDepositDate').datepicker("setDate",dCurrentDate);
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


        $('#obtDBRDocBrowsePdt').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                JCNvDBRBrowsePdt();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        if($('#oetDBRFrmBchCode').val() == ""){
            $("#obtDBRFrmBrowseTaxAdd").attr("disabled","disabled");
        }

        /** =================== Event Search Function ===================== */
            $('#oliDBRMngPdtScan').unbind().click(function(){
                var tDBRSplCode  = $('#oetDBRFrmSplCode').val();
                if(typeof(tDBRSplCode) !== undefined && tDBRSplCode !== ''){
                    //Hide
                    $('#oetDBRFrmFilterPdtHTML').hide();
                    $('#obtDBRMngPdtIconSearch').hide();

                    //Show
                    $('#oetDBRFrmSearchAndAddPdtHTML').show();
                    $('#obtDBRMngPdtIconScan').show();
                }else{
                    var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                }
            });
            $('#oliDBRMngPdtSearch').unbind().click(function(){
                //Hide
                $('#oetDBRFrmSearchAndAddPdtHTML').hide();
                $('#obtDBRMngPdtIconScan').hide();
                //Show
                $('#oetDBRFrmFilterPdtHTML').show();
                $('#obtDBRMngPdtIconSearch').show();
            });
        /** =============================================================== */

        /** ===================== Set Date Autometic Doc ========================  */
            var dCurrentDate    = new Date();
            var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
            var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes();

            if($('#oetDBRDocDate').val() == ''){
                $('#oetDBRDocDate').datepicker("setDate",dCurrentDate);
            }

            if($('#oetDBRDocTime').val() == ''){
                $('#oetDBRDocTime').val(tCurrentTime);
            }

            if($('#oetDBRDepositDate').val() == ''){
                $('#oetDBRDepositDate').datepicker("setDate",dCurrentDate);
            }

            if($('#oetDBRDepositTime').val() == ''){
                $('#oetDBRDepositTime').val(tCurrentTime);
            }
        /** =============================================================== */

        /** =================== Event Date Function  ====================== */
            $('#obtDBRDocDate').unbind().click(function(){
                $('#oetDBRDocDate').datepicker('show');
            });

            $('#obtDBRDocTime').unbind().click(function(){
                $('#oetDBRDocTime').datetimepicker('show');
            });

            $('#obtDBRBrowseRefIntDocDate').unbind().click(function(){
                $('#oetDBRRefIntDocDate').datepicker('show');
            });

            $('#obtDBRRefDocDate').unbind().click(function(){
                $('#oetDBRRefDocDate').datepicker('show');
            });

            $('#obtDBRRefDocExtDate').unbind().click(function(){
                $('#oetDBRRefDocExtDate').datepicker('show');
            });

            $('#obtDBRTransDate').unbind().click(function(){
                $('#oetDBRTransDate').datepicker('show');
            });

            $('#obtDBRDepositDate').unbind().click(function(){
                $('#oetDBRDepositDate').datepicker('show');
            });

            $('#obtDBRDepositTime').unbind().click(function(){
                $('#oetDBRDepositTime').datetimepicker('show');
            });
        /** =============================================================== */

        /** ================== Check Box Auto GenCode ===================== */
            $('#ocbDBRStaAutoGenCode').on('change', function (e) {
                if($('#ocbDBRStaAutoGenCode').is(':checked')){
                    $("#oetDBRDocNo").val('');
                    $("#oetDBRDocNo").attr("readonly", true);
                    $('#oetDBRDocNo').closest(".form-group").css("cursor","not-allowed");
                    $('#oetDBRDocNo').css("pointer-events","none");
                    $("#oetDBRDocNo").attr("onfocus", "this.blur()");
                    $('#ofmDBRFormAdd').removeClass('has-error');
                    $('#ofmDBRFormAdd .form-group').closest('.form-group').removeClass("has-error");
                    $('#ofmDBRFormAdd em').remove();
                }else{
                    $('#oetDBRDocNo').closest(".form-group").css("cursor","");
                    $('#oetDBRDocNo').css("pointer-events","");
                    $('#oetDBRDocNo').attr('readonly',false);
                    $("#oetDBRDocNo").removeAttr("onfocus");
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
                Title: ["document/depositboxreservation/depositboxreservation","tDBRUserDepositToBox"],
                Table: { Master:"TCNMUser", PK:"FTUsrCode"},
                Join: {
                    Table: ["TCNMUser_L"],
                    On: ["TCNMUser.FTUsrCode = TCNMUser_L.FTUsrCode AND TCNMUser_L.FNLngID = '"+nLangEdits+"'"]
                },
                GrideView:{
                    ColumnPathLang: 'document/depositboxreservation/depositboxreservation',
                    ColumnKeyLang: ['tDBRUserCode','tDBRUserName'],
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
                Title: ['document/depositboxreservation/depositboxreservation', 'tDBRCstTitle'],
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
                    ColumnKeyLang       : ['tDBRCstCode', 'tDBRCstName'],
                    ColumnsSize         : ['10%', '75%'],
                    DataColumns         : ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName','TCNMCst.FTCstTel','TCNMCst.FTCstEmail'],
                    DataColumnsFormat   : ['', ''],
                    DisabledColumns   : [2,3],
                    WidthModal          : 50,
                    Perpage             : 10,
                    OrderBy             : ['TCNMCst.FTCstCode DESC']
                },
                NextFunc:{
                    FuncName:'JSxNextFuncDBRCst',
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

        function JSxNextFuncDBRCst(paData) {
            var tDBRCstTel = '';
            var tDBRCstMail = '';
            if (typeof(paData) != 'undefined' && paData != "NULL") {
                var aDBRCstData = JSON.parse(paData);
                tDBRCstTel  = aDBRCstData[0];
                tDBRCstMail = aDBRCstData[1];
            }
            $('#oetDBRCstTel').val(tDBRCstTel);
            $('#oetDBRCstMail').val(tDBRCstMail);
        }
    // ============================================================================================================

    // ========================================== Brows Event Conditon ===========================================
        // Event Browse User Deposit
        $('#obtDBRBrowseUser').unbind().click(function(){
            // var nStaSession = JCNxFuncChkSessionExpired();
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oDBRBrowsUsrOption   = undefined;
                oDBRBrowsUsrOption          = oUsrOption({
                    'tReturnInputCode'  : 'oetDBRUsrCode',
                    'tReturnInputName'  : 'oetDBRUsrName',
                    'aArgReturn'        : []
                });
                JCNxBrowseData('oDBRBrowsUsrOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtDBRBrowseBCH').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetDBRAgnCode').val();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oDBRBrowseBranchOption  = undefined;
                oDBRBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'oetDBRFrmBchCode',
                    'tReturnInputName'  : 'oetDBRFrmBchName',
                    'tAgnCode'          : tAgnCode,
                    'aArgReturn'        : ['FTBchCode','FTBchName'],
                });
                JCNxBrowseData('oDBRBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

        });

        $('#obtDBRBrowseCst').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetDBRAgnCode').val();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oDBRBrowseCstOption  = undefined;
                oDBRBrowseCstOption         = oCstOption({
                    'tReturnInputCode'  : 'oetDBRCstCode',
                    'tReturnInputName'  : 'oetDBRCstName',
                    'aArgReturn'        : ['FTCstTel','FTCstEmail'],
                });
                JCNxBrowseData('oDBRBrowseCstOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

        });

        $('#obtDBRBrowseRefDocInt').on('click',function(){
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            var tDocType = $('#ocbDBRRefDoc').val();
            var tRefKeyOld = $('#ohdRefKeyOld').val();
            var tRefKeyNew = $('#oetDBRRefDoc').val();
            if (tRefKeyOld != '' && tRefKeyNew != '') {
                if (tRefKeyOld != tRefKeyNew) {
                    $('#ohdDBRTypeChange').val('Ref');
                    $('#odvDBRModalChangeData #ospDBRTxtWarningAlert').text('<?php echo language('document/depositboxreservation/depositboxreservation', 'tDBRChangeDocType') ?>');
                    $('#odvDBRModalChangeData').modal('show')
                }else{
                    JSxCallDBRRefIntDoc(tDocType);
                }
            }else{
                JSxCallDBRRefIntDoc(tDocType);
            }
        });

        $('#obtDBRChangeData').on('click',function(){
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            //เคลีย SPL
            $('#oetDBRFrmSplCode').val('');
            $('#oetDBRFrmSplName').val('');
            $('#oetDBRSplName').val('');
            $("#ocmDBRTypePayment").val("1").selectpicker('refresh');
            $("#ocmDBRFrmSplInfoVatInOrEx").val("1").selectpicker('refresh');

            var tDBRChangeType = $('#ohdDBRTypeChange').val()
            if (tDBRChangeType == 'Ref') {
                var tDocType = $('#ocbDBRRefDoc').val();
                JSxCallDBRRefIntDoc(tDocType);
            }else if(tDBRChangeType == 'Spl'){
                $('#otbDBRDocPdtAdvTableList .xWDelDocRef').click();
                $('#otbDBRDocRef .xWDelDocRef').click();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSxCheckPinMenuClose();
                    window.oDBRBrowseSplOption   = undefined;
                    oDBRBrowseSplOption          = oSplOption({
                        'tParamsAgnCode'    : '<?//=$this->session->userdata("tSesUsrAgnCode")?>',
                        'tReturnInputCode'  : 'oetDBRFrmSplCode',
                        'tReturnInputName'  : 'oetDBRFrmSplName',
                        'aArgReturn'        : ['FTSplCode', 'FTSplName']
                    });
                    JCNxBrowseData('oDBRBrowseSplOption');
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }else if(tDBRChangeType == 'Agn'){
                $('#ofmDBRFormAdd').find('input').val('');
                $('#ofmDBRFormAdd').find('select').val(1).selectpicker("refresh");
                $('#otbDBRDocPdtAdvTableList .xWDelDocRef').click();
                $('#otbDBRDocRef .xWDelDocRef').click();
                var nStaSession = JCNxFuncChkSessionExpired();
                if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                    JSxCheckPinMenuClose();
                    window.oDBRBrowseAgencyOption = oBrowseAgn({
                        'tReturnInputCode': 'oetDBRAgnCode',
                        'tReturnInputName': 'oetDBRAgnName',
                    });
                    JCNxBrowseData('oDBRBrowseAgencyOption');
                } else {
                    JCNxShowMsgSessionExpired();
                }
            }
        });

        //Browse เอกสารอ้างอิงภายใน
        function JSxCallDBRRefIntDoc(ptDocType){
            var tBCHCode = $('#oetDBRFrmBchCode').val()
            var tBCHName = $('#oetDBRFrmBchName').val()
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docDBRCallRefIntDoc",
                data: {
                    'tDocType'     : ptDocType,
                    'tBCHCode'      : tBCHCode,
                    'tBCHName'      : tBCHName,
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    JCNxCloseLoading();
                    $('#odvDBRFromRefIntDoc').html(oResult);
                    if (ptDocType == 1 ) {
                        $('#odvDBRModalRefIntDoc #olbTextModalHead').text('<?php echo language('document/purchaseorder/purchaseorder', 'อ้างอิงเอกสารใบสั่งซื้อ') ?>');
                    } else {
                        $('#odvDBRModalRefIntDoc #olbTextModalHead').text('<?php echo language('document/purchaseorder/purchaseorder', 'อ้างอิงเอกสารใบขาย') ?>');
                    }
                    
                    $('#odvDBRModalRefIntDoc').modal({
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
                $('#odvDBRModalRefIntDoc').modal('hide');
                var tRefIntDocNo =  $('.xPurchaseInvoiceRefInt.active').data('docno');
                var tRefIntDocDate =  $('.xPurchaseInvoiceRefInt.active').data('docdate');
                var tRefIntDocTime =  $('.xPurchaseInvoiceRefInt.active').data('doctime');
                var tRefIntBchCode =  $('.xPurchaseInvoiceRefInt.active').data('bchcode');
                var tRefIntBchName =  $('.xPurchaseInvoiceRefInt.active').data('bchname');
                var tRefIntCstCode =  $('.xPurchaseInvoiceRefInt.active').data('cstcode');
                var tRefIntCstName =  $('.xPurchaseInvoiceRefInt.active').data('cstname');
                var tRefIntCstTel =  $('.xPurchaseInvoiceRefInt.active').data('csttel');
                var tRefIntCstMail =  $('.xPurchaseInvoiceRefInt.active').data('cstmail');
                var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm){
                        return $(this).val();
                    }).get();

                var poParams = {
                        FTRefIntDocNo       : tRefIntDocNo,
                        FTRefIntDocDate     : tRefIntDocDate,
                        FTRefIntDocTime     : tRefIntDocTime,
                        FTBchCode           : tRefIntBchCode,
                        FTBchName           : tRefIntBchName,
                        FTCstCode           : tRefIntCstCode,
                        FTCstName           : tRefIntCstName,
                        FTCstTel            : tRefIntCstTel,
                        FTCstEmail          : tRefIntCstMail,
                        tDoctype            : 1
                    };

                if (typeof tRefIntDocNo === "undefined") {
                    $('#odvDBRModalPONoFound').modal('show');
                } else {
                    JSxDBRSetPanelSupplierData(poParams);
                    $('#oetDBRRefIntDoc').val(tRefIntDocNo);
                    $('#ohdDBRRefDocIntName').val(tRefIntDocNo);
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docDBRCallRefIntDocInsertDTToTemp",
                        data: {
                            'tDBRDocNo'          : $('#oetDBRDocNo').val(),
                            'tDBRFrmBchCode'     : $('#oetDBRFrmBchCode').val(),
                            'tDBROptionAddPdt'   : $('#ocmDBRFrmInfoOthReAddPdt').val(),
                            'tRefIntDocNo'      : tRefIntDocNo,
                            'tRefIntBchCode'    : tRefIntBchCode,
                            'aSeqNo'            : aSeqNo,
                            'tDoctype'          : 1
                        },
                        cache: false,
                        Timeout: 0,
                        success: function (oResult){
                            // FSvDBRNextFuncB4SelPDT();
                            JSvDBRLoadPdtDataTableHtml();
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
            $('#odvDBRModalRefIntDoc').modal('show');
        });

        // Function : ฟังก์ชั่นเซทข้อมูล ผู้จำหน่าย
        function JSxDBRSetPanelSupplierData(poParams){
            // Reset Panel เป็นค่าเริ่มต้น
            $("#ocmDBRFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
            $("#ocmDBRTypePayment.selectpicker").val("2").selectpicker("refresh");
            $("#oetDBRRefIntDoc").val(poParams.FTRefIntDocNo);
            $("#oetDBRRefDocDate").val(poParams.FTRefIntDocDate).datepicker("refresh");
            $("#ohdDBRRefDocTime").val(poParams.FTRefIntDocTime);

            //สาขา
            $("#oetDBRFrmBchCode").val(poParams.FTBchCode);
            $("#oetDBRFrmBchName").val(poParams.FTBchName);
            
            //ลูกค้า
            $("#oetDBRCstCode").val(poParams.FTCstCode);
            $("#oetDBRCstName").val(poParams.FTCstName);
            $("#oetDBRCstTel").val(poParams.FTCstTel);
            $("#oetDBRCstMail").val(poParams.FTCstEmail);

            $("#ohdDBRDocType").val(poParams.tDoctype);
        }

//------------------------------------------------------------------------------------------------//

    // Validate From Add Or Update Document
    function JSxDBRValidateFormDocument(){
        if($("#ohdDBRCheckClearValidate").val() != 0){
            $('#ofmDBRFormAdd').validate().destroy();
        }

        $('#ofmDBRFormAdd').validate({
            focusInvalid: true,
            rules: {
                oetDBRDocNo : {
                    "required" : {
                        depends: function (oElement) {
                            if($("#ohdDBRRoute").val()  ==  "docDBREventAdd"){
                                if($('#ocbDBRStaAutoGenCode').is(':checked')){
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
                oetDBRFrmBchName    : {"required" : true},
                oetDBRCstName : {"required" : true},
            },
            messages: {
                oetDBRDocNo      : {"required" : $('#oetDBRDocNo').attr('data-validate-required')},
                oetDBRFrmBchName : {"required" : $('#oetDBRFrmBchName').attr('data-validate-required')},
                oetDBRCstName : {"required" : $('#oetDBRCstName').attr('data-validate-required')},
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
                if(!$('#ocbDBRStaAutoGenCode').is(':checked')){
                    JSxDBRValidateDocCodeDublicate();
                }else{
                    if($("#ohdDBRCheckSubmitByButton").val() == 1){
                        JSxDBRSubmitEventByButton();
                    }
                }
            },
        });
    }

    // Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
    function JSxDBRValidateDocCodeDublicate(){
        //JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName'    : 'TRTTBookHD',
                'tFieldName'    : 'FTXshDocNo',
                'tCode'         : $('#oetDBRDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                $("#ohdDBRCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdDBRCheckClearValidate").val() != 1) {
                    $('#ofmDBRFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdDBRRoute").val() == "docDBREventAdd"){
                        if($('#ocbDBRStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdDBRCheckDuplicateCode").val() == 1) {
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
                $('#ofmDBRFormAdd').validate({
                    focusInvalid: false,
                    onclick: false,
                    onfocusout: false,
                    onkeyup: false,
                    rules: {
                        oetDBRDocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetDBRDocNo : {"dublicateCode"  : $('#oetDBRDocNo').attr('data-validate-duplicate')}
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
                        if($("#ohdDBRCheckSubmitByButton").val() == 1) {
                            JSxDBRSubmitEventByButton();
                        }
                    }
                })

                if($("#ohdDBRCheckClearValidate").val() != 1) {
                    $("#ofmDBRFormAdd").submit();
                    $("#ohdDBRCheckClearValidate").val(1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function: Validate Success And Send Ajax Add/Update Document
    function JSxDBRSubmitEventByButton(ptType = ''){
        var tDBRDocNo = '';
        if($("#ohdDBRRoute").val() !=  "docDBREventAdd"){
            var tDBRDocNo    = $('#oetDBRDocNo').val();
        }
        $('#obtDBRSubmitFromDoc').attr('disabled',true);
        $.ajax({
            type: "POST",
            url: "docDBRChkHavePdtForDocDTTemp",
            data: {
                'ptDBRDocNo'         : tDBRDocNo,
                'tDBRSesSessionID'   : $('#ohdSesSessionID').val(),
                'tDBRUsrCode'        : $('#ohdDBRUsrCode').val(),
                'tDBRLangEdit'       : $('#ohdDBRLangEdit').val(),
                'tSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                // JCNxCloseLoading();
                var aDataReturnChkTmp   = JSON.parse(oResult);
                $('.xWDBRDisabledOnApv').attr('disabled',false);
                if (aDataReturnChkTmp['nStaReturn'] == '1'){
                    $.ajax({
                        type    : "POST",
                        url     : $("#ohdDBRRoute").val(),
                        data    : $("#ofmDBRFormAdd").serialize()+ "&" + $('#ohdDBRRefDocIntName').val(),
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            // JCNxCloseLoading();
                            var aDataReturnEvent    = JSON.parse(oResult);
                            if(aDataReturnEvent['nStaReturn'] == '1'){
                                var nDBRStaCallBack      = aDataReturnEvent['nStaCallBack'];
                                var nDBRDocNoCallBack    = aDataReturnEvent['tCodeReturn'];
                                var nDBRPayType          = $('#ocmDBRTypePayment').val();
                                var nDBRVatInOrEx        = $('#ocmDBRFrmSplInfoVatInOrEx').val();
                                var nDBRStaRef           = $('#ocmDBRFrmInfoOthRef').val();

                                var oDBRCallDataTableFile = {
                                    ptElementID : 'odvDBRShowDataTable',
                                    ptBchCode   : $('#oetDBRFrmBchCode').val(),
                                    ptDocNo     : nDBRDocNoCallBack,
                                    ptDocKey    :'TAPTDoHD',
                                }
                                JCNxUPFInsertDataFile(oDBRCallDataTableFile);
                                if(ptType == 'approve'){
                                    JSxDBRApproveDocument(false);
                                }else{
                                    switch(nDBRStaCallBack){
                                        case '1' :
                                            JSvDBRCallPageEdit(nDBRDocNoCallBack,nDBRPayType,nDBRVatInOrEx,nDBRStaRef);
                                        break;
                                        case '2' :
                                            JSvDBRCallPageAddDoc();
                                        break;
                                        case '3' :
                                            JSvDBRCallPageList();
                                        break;
                                        default :
                                            JSvDBRCallPageEdit(nDBRDocNoCallBack,nDBRPayType,nDBRVatInOrEx,nDBRStaRef);
                                    }
                                }
                                $("#obtDBRSubmitFromDoc").removeAttr("disabled");
                            }else{
                                var tMessageError = aDataReturnEvent['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                                $("#obtDBRSubmitFromDoc").removeAttr("disabled");
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
    function JSxDBRCountPdtItems(){
        var nPdtItems = $('.xWPdtItem').length;
        $('.xShowQtyFooter').text(accounting.formatNumber(nPdtItems, 0, ','));
    }

    $('#ocmDBRTypePayment').on('change', function() {
        if (this.value == 1) {
            $('.xCNPanel_CreditTerm').hide();
        } else {
            $('.xCNPanel_CreditTerm').show();
        }
    });


    // Rabbit MQ
    function JSoDBRCallSubscribeMQ() {
        // Document variable
        var tLangCode = $("#ohdDBRLangEdit").val();
        var tUsrBchCode = $("#ohdDBRBchCode").val();
        var tUsrApv = '<?=$this->session->userdata('tSesUsername')?>';
        var tDocNo = $("#oetDBRDocNo").val();
        var tPrefix = "RESDBR";
        var tStaApv = $("#ohdDBRStaApv").val();
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
            tCallPageEdit: "JSvDBRCallPageEdit",
            tCallPageList: "JSvDBRCallPageList"
        };

        // Check Show Progress %
        FSxCMNRabbitMQMessage(poDocConfig, poMqConfig, poUpdateStaDelQnameParams, poCallback);
    }

    //พิมพ์เอกสาร
    function JSxDBRPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tDBRBchCode); ?>'},
            {"DocCode"      : '<?=@$tDBRDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tDBRBchCode;?>'}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_ALLReserveRT?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

    //พิมพ์เอกสาร
    function JSxDBRPrintDocABB(){
        $.ajax({
            type    : "POST",
            url     : 'docDBRUpdatePrictCount',
            data: {
                'tDBRDocNo'          : $('#oetDBRDocNo').val(),
                'tPrintCount'        : 1
            },
            cache   : false,
            timeout : 0,
            success : function(oResult){
                var tOrginalRight   = 0;
                var tCopyRight      = 0;
                var tGrandText = '<?=$tDBRDocrefSGrand; ?>';
                var aInfor = [
                    {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
                    {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
                    {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tDBRDocrefSBch); ?>'},
                    {"DocCode"      : '<?=@$tDBRDocrefS; ?>'}, // เลขที่เอกสาร
                    {"DocBchCode"   : '<?=@$tDBRDocrefSBch;?>'}
                ];
                window.open("<?=base_url(); ?>formreport/Frm_PSInvoiceSale_Back_ABB?infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand="+tGrandText + "&PrintOriginal="+tOrginalRight + "&PrintCopy="+tCopyRight + "&PrintByPage=" + 'ALL', '_blank');
                var tChkTax = $("#ohdDBRDocRefTAX").val();
                if(tChkTax != ''){
                    $("#obtDBRPrintDoc2").prop('disabled',false);
                }else{
                    $("#obtDBRPrintDoc").prop('disabled',false);
                    $("#obtDBRPrintDoc").text('พิมพ์ใบจองช่องฝาก');
                }
            },
            error   : function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });  
    }

    //พิมพ์เอกสาร
    function JSxDBRPrintDocTAX(){
        $.ajax({
            type    : "POST",
            url     : 'docDBRUpdatePrictCount',
            data: {
                'tDBRDocNo'          : $('#oetDBRDocNo').val(),
                'tPrintCount'        : 2
            },
            cache   : false,
            timeout : 0,
            success : function(oResult){
        $("#obtDBRPrintDoc").prop('disabled',false);
        var nPrintOnlyPage = 'ALL';
        var tOrginalRight   = 0;
        var tCopyRight      = 0;
        var tGrandText = '<?=$tDBRDocrefTAXGrand; ?>';

        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tDBRDocrefTAXBch); ?>'},
            {"DocCode"      : '<?=@$tDBRDocrefTAX; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tDBRDocrefTAXBch;?>'}
        ];
        window.open("<?=base_url();?>formreport/Frm_PSInvoiceSale?StaPrint=1&infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand="+tGrandText + "&PrintOriginal="+tOrginalRight + "&PrintCopy="+tCopyRight + "&PrintByPage=" + nPrintOnlyPage, '_blank');
            },
            error   : function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });  
    }

    //โหลด Table อ้างอิงเอกสารทั้งหมด
    function JSxDBRCallPageHDDocRef(){
        var tDocNo  = "";
        if ($("#ohdDBRRoute").val() == "docDBREventEdit") {
            tDocNo = $('#oetDBRDocNo').val();
        }

        $.ajax({
            type    : "POST",
            url     : "docDBRPageHDDocRef",
            data:{
                'ptDocNo' : tDocNo
            },
            cache   : false,
            timeout : 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                $('#odvDBRTableHDRef').html(aResult['tViewPageHDRef']);
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

    //กดเพิ่มเอกสารอ้างอิง (ภายใน ภายนอก)
    $('#obtDBRAddDocRef').off('click').on('click',function(){
        $('#ofmDBRFormAddDocRef').validate().destroy();
        JSxDBREventClearValueInFormHDDocRef();
        $('.xWShowRefExt').hide();
        $('.xWShowRefInt').show();
        $('#odvDBRModalAddDocRef').modal('show');
    });

    //เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    $('#ocbDBRRefType').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        JSxDBREventCheckShowHDDocRef();
    });

    //เคลียร์ค่า
    function JSxDBREventClearValueInFormHDDocRef(){
        $('#oetDBRRefDocNo').val('');
        $('#oetDBRRefDocDate').val('');
        $('#oetDBRRefIntDoc').val('');
        $('#oetDBRDocRefIntName').val('');
        $('#oetDBRRefKey').val('');
        $("#ocbDBRRefDoc").val("1").selectpicker('refresh');
    }

    //กดยืนยันบันทึกลง Temp
    $('#ofmDBRFormAddDocRef').off('click').on('click',function(){
        $('#ofmDBRFormAddDocRef').validate().destroy();
        $('#ofmDBRFormAddDocRef').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetDBRRefIntDoc    : {"required" : true}
            },
            messages: {
                oetDBRRefIntDoc    : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
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

                var tDocType = $('#ocbDBRRefDoc').val();
                var tRefKey   = "SO";

                if($('#ocbDBRRefType').val() == 1){ //อ้างอิงเอกสารภายใน
                    var tDocNoRef = $('#oetDBRRefIntDoc').val();
                }else{ //อ้างอิงเอกสารภายนอก
                    var tDocNoRef = $('#oetDBRRefDocNo').val();
                    var tRefKey   = $('#oetDBRRefKey').val();
                }

                $.ajax({
                    type    : "POST",
                    url     : "docDBREventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld'     : $('#oetDBRRefDocNoOld').val(),
                        'ptDocNo'           : $('#oetDBRDocNo').val(),
                        'ptRefType'         : $('#ocbDBRRefType').val(),
                        'ptRefDocNo'        : tDocNoRef,
                        'pdRefDocDate'      : $('#oetDBRRefDocDate').val()+' '+$('#ohdDBRRefDocTime').val(),
                        'ptRefKey'          : tRefKey
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult){
                        var aReturnData = JSON.parse(oResult);
                        // JSxDBREventClearValueInFormHDDocRef();
                        $('#odvDBRModalAddDocRef').modal('hide');

                        if (aReturnData['nStaEvent'] == 2) {
                            FSvCMNSetMsgErrorDialog("เลขที่เอกสารนี้ถูกอ้างอิงแล้ว");
                        }

                        JSxDBRCallPageHDDocRef();
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
function JCNvDBRBrowsePdt() {
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
            NextFunc: "FSvDBRNextFuncB4SelPDT",
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

// แนะนำช่องฝาก
function JSxDBRSuggestLay() {
    try {
        var tCheckIteminTable = $('#otbDBRDocPdtAdvTableList .xWPdtItem').length;
        var tBchCode    = $('#oetDBRFrmBchCode').val();
        var tRefInDocNo = $('#ohdDBRRefDocSO').val();
            if (tCheckIteminTable > 0) {
                if (tRefInDocNo != '') {
                    $("#oetRecommendLay").attr('disabled',true);
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docDBRSuggestLay",
                        data: {
                            tBchCode    : tBchCode,
                            tRefInDocNo : tRefInDocNo
                        },
                        cache: false,
                        timeout: 0,
                        success: function(tResult) {
                            var aReturnData = JSON.parse(tResult);
                            if(aReturnData['nStaEvent'] == 1){
                                localStorage.setItem("SuggestLay",0);
                                JSxDBRGetMsgSuggestLay(tRefInDocNo);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                } else {
                    FSvCMNSetMsgErrorDialog("ไม่มีเลขที่เอกสารอ้างอิง");
                    $("#oetRecommendLay").attr('disabled',false);
                }
            } else {
                FSvCMNSetMsgWarningDialog($('#ohdDBRValidatePdt').val());
            }
    } catch (err) {
        console.log("JSxDBRApproveDocument Error: ", err);
    }
}

function JSxDBRGetMsgSuggestLay(ptRefInDocNo) {
    try {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docDBRGetMsgSuggestLay",
            data: {
                tRefInDocNo: ptRefInDocNo,
                tDBRDocNo:$('#oetDBRDocNo').val(),
                tDBRBchCode:$('#oetDBRFrmBchCode').val()
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == 1){
                    $("#oetRecommendLay").attr('disabled',false);
                    JCNxCloseLoading();
                    JSvDBRLoadPdtDataTableHtml();
                    FSvCMNSetMsgSucessDialog("แนะนำช่องฝากสำเร็จ");
                }else if(aReturnData['nStaEvent'] == 900){
                    var LocalItemData = localStorage.getItem("SuggestLay");
                    if (LocalItemData <= 20) {
                        localStorage.setItem("SuggestLay",parseFloat(LocalItemData)+1);
                        setTimeout(function() {
                            JSxDBRGetMsgSuggestLay(ptRefInDocNo);
                        }, 1000);
                    }else{
                        JCNxCloseLoading();
                        FSvCMNSetMsgErrorDialog("ไม่สำเร็จ");
                        $("#oetRecommendLay").attr('disabled',false);
                    }
                }else{
                    FSvCMNSetMsgErrorDialog("ไม่มีข้อมูลแนะนำช่องฝาก");
                    $("#oetRecommendLay").attr('disabled',false);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } catch (err) {
        console.log("JSxDBRApproveDocument Error: ", err);
    }
}
</script>
