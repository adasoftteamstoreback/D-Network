
<script type="text/javascript">
    var nLangEdits        = '<?php echo $this->session->userdata("tLangEdit");?>';
    var tUsrApvName       = '<?php echo $this->session->userdata("tSesUsername");?>';
    var tSesUsrLevel      = '<?php echo $this->session->userdata('tSesUsrLevel');?>';
    var tUserBchCode      = '<?php echo $this->session->userdata("tSesUsrBchCode");?>';
    var tUserBchName      = '<?php echo $this->session->userdata("tSesUsrBchName");?>';
    var tUserWahCode      = '<?php echo $this->session->userdata("tSesUsrWahCode");?>';
    var tUserWahName      = '<?php echo $this->session->userdata("tSesUsrWahName");?>';
    var tRoute                 = $('#ohdDORoute').val();
    var tDOSesSessionID        = $("#ohdSesSessionID").val();


    $(document).ready(function(){
        JSxDOCallPageHDDocRef();
        JSxDOEventCheckShowHDDocRef();
        JSxDOControlFormWhenCancelOrApprove();

        $('#odvDOContentHDRef').hide();
        $('#obtDOAddDocRef').hide();

        var nCrTerm = $('#ocmDOTypePayment').val();
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
        $("#obtDOSubmitFromDoc").removeAttr("disabled");


        var dCurrentDate    = new Date();
        if($('#oetDODocDate').val() == ''){
            $('#oetDODocDate').datepicker("setDate",dCurrentDate);
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


        $('#obtDODocBrowsePdt').unbind().click(function(){
            // var nStaSession = JCNxFuncChkSessionExpired();
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                if($('#oetDOFrmSplCode').val()!=""){
                JSxCheckPinMenuClose();
                JCNvDOBrowsePdt();
                }else{
                    $('#odvDOModalPleseselectSPL').modal('show');
                }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        if($('#oetDOFrmBchCode').val() == ""){
            $("#obtDOFrmBrowseTaxAdd").attr("disabled","disabled");
        }

        /** =================== Event Search Function ===================== */
            $('#oliDOMngPdtScan').unbind().click(function(){
                var tDOSplCode  = $('#oetDOFrmSplCode').val();
                if(typeof(tDOSplCode) !== undefined && tDOSplCode !== ''){
                    //Hide
                    $('#oetDOFrmFilterPdtHTML').hide();
                    $('#obtDOMngPdtIconSearch').hide();

                    //Show
                    $('#oetDOFrmSearchAndAddPdtHTML').show();
                    $('#obtDOMngPdtIconScan').show();
                }else{
                    var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                }
            });
            $('#oliDOMngPdtSearch').unbind().click(function(){
                //Hide
                $('#oetDOFrmSearchAndAddPdtHTML').hide();
                $('#obtDOMngPdtIconScan').hide();
                //Show
                $('#oetDOFrmFilterPdtHTML').show();
                $('#obtDOMngPdtIconSearch').show();
            });
        /** =============================================================== */

        /** ===================== Set Date Autometic Doc ========================  */
            var dCurrentDate    = new Date();
            var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
            var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes();

            if($('#oetDODocDate').val() == ''){
                $('#oetDODocDate').datepicker("setDate",dCurrentDate);
            }

            if($('#oetDODocTime').val() == ''){
                $('#oetDODocTime').val(tCurrentTime);
            }
        /** =============================================================== */

        /** =================== Event Date Function  ====================== */
            $('#obtDODocDate').unbind().click(function(){
                $('#oetDODocDate').datepicker('show');
            });

            $('#obtDODocTime').unbind().click(function(){
                $('#oetDODocTime').datetimepicker('show');
            });

            $('#obtDOBrowseRefIntDocDate').unbind().click(function(){
                $('#oetDORefIntDocDate').datepicker('show');
            });

            $('#obtDORefDocDate').unbind().click(function(){
                $('#oetDORefDocDate').datepicker('show');
            });

            $('#obtDORefDocExtDate').unbind().click(function(){
                $('#oetDORefDocExtDate').datepicker('show');
            });

            $('#obtDOTransDate').unbind().click(function(){
                $('#oetDOTransDate').datepicker('show');
            });
        /** =============================================================== */

        /** ================== Check Box Auto GenCode ===================== */
            $('#ocbDOStaAutoGenCode').on('change', function (e) {
                if($('#ocbDOStaAutoGenCode').is(':checked')){
                    $("#oetDODocNo").val('');
                    $("#oetDODocNo").attr("readonly", true);
                    $('#oetDODocNo').closest(".form-group").css("cursor","not-allowed");
                    $('#oetDODocNo').css("pointer-events","none");
                    $("#oetDODocNo").attr("onfocus", "this.blur()");
                    $('#ofmDOFormAdd').removeClass('has-error');
                    $('#ofmDOFormAdd .form-group').closest('.form-group').removeClass("has-error");
                    $('#ofmDOFormAdd em').remove();
                }else{
                    $('#oetDODocNo').closest(".form-group").css("cursor","");
                    $('#oetDODocNo').css("pointer-events","");
                    $('#oetDODocNo').attr('readonly',false);
                    $("#oetDODocNo").removeAttr("onfocus");
                }
            });
        /** =============================================================== */
    });

    // ========================================== Brows Option Conditon ===========================================
        // ตัวแปร Option Browse Modal คลังสินค้า
        var oWahOption      = function(poDataFnc){
            var tDOBchCode          = poDataFnc.tDOBchCode;
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var aArgReturn          = poDataFnc.aArgReturn;

            var oOptionReturn   = {
                Title: ["company/warehouse/warehouse","tWAHTitle"],
                Table: { Master:"TCNMWaHouse", PK:"FTWahCode"},
                Join: {
                    Table: ["TCNMWaHouse_L"],
                    On: ["TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse.FTBchCode=TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = '"+nLangEdits+"'"]
                },
                Where: {
                    Condition : [" AND (TCNMWaHouse.FTWahStaType IN (1,2,5) AND  TCNMWaHouse.FTBchCode='"+tDOBchCode+"')"]
                },
                GrideView:{
                    ColumnPathLang: 'company/warehouse/warehouse',
                    ColumnKeyLang: ['tWahCode','tWahName'],
                    DataColumns: ['TCNMWaHouse.FTWahCode','TCNMWaHouse_L.FTWahName'],
                    DataColumnsFormat: ['',''],
                    ColumnsSize: ['15%','75%'],
                    Perpage: 5,
                    WidthModal: 50,
                    OrderBy: ['TCNMWaHouse_L.FTWahName ASC'],
                },
                CallBack: {
                    ReturnType  : 'S',
                    Value       : [tInputReturnCode,"TCNMWaHouse.FTWahCode"],
                    Text        : [tInputReturnName,"TCNMWaHouse_L.FTWahName"]
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
                    Table   : ['TCNMBranch_L','TCNMWaHouse_L'],
                    On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                             'TCNMBranch.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMBranch.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID ='+nLangEdits,]
                },
                Where : {
                    Condition : [tSQLWhereBch,tSQLWhereAgn]
                },
                GrideView: {
                    ColumnPathLang      : 'authen/user/user',
                    ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                    ColumnsSize         : ['10%', '75%'],
                    DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName','TCNMWaHouse_L.FTWahCode','TCNMWaHouse_L.FTWahName'],
                    DataColumnsFormat   : ['', ''],
                    DisabledColumns   : [2,3],
                    WidthModal          : 50,
                    Perpage             : 10,
                    OrderBy             : ['TCNMBranch.FTBchCode DESC']
                },
                NextFunc:{
                    FuncName:'JSxNextFuncDOBch'
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

        function JSxNextFuncDOBch() {
            $('#oetDOFrmWahCode').val('');
            $('#oetDOFrmWahName').val('');
        }

        //Option Agency
        var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
        var oBrowseAgn = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;

        var oOptionReturn = {
            Title: ['ticket/agency/agency', 'tAggTitle'],
            Table: {
                Master: 'TCNMAgency',
                PK: 'FTAgnCode'
            },
            Join: {
                Table: ['TCNMAgency_L'],
                On: ['TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'ticket/agency/agency',
                ColumnKeyLang: ['tAggCode', 'tAggName'],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMAgency.FDCreateOn DESC'],
            },
            NextFunc:{
                    FuncName:'JSxNextFuncDOAgn'
                },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text: [tInputReturnName, "TCNMAgency_L.FTAgnName"],
            },
            RouteAddNew: 'agency',
            BrowseLev: 1,
        }
        return oOptionReturn;
    }

    function JSxNextFuncDOAgn() {
        $('#oetDOFrmBchCode').val('');
        $('#oetDOFrmBchName').val('');
        $('#oetDOFrmWahCode').val('');
        $('#oetDOFrmWahName').val('');
    }

    var tStaUsrLevel = '<?php echo $this->session->userdata("tSesUsrLevel"); ?>';

        // ตัวแปร Option Browse Modal ตัวแทนจำหน่าย
        var oSplOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tParamsAgnCode      = poDataFnc.tParamsAgnCode;

        if( tParamsAgnCode != "" ){
            tWhereAgency = " AND ( TCNMSpl.FTAgnCode = '"+tParamsAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') = '' ) ";
        }else{
            tWhereAgency = "";
        }

        var oOptionReturn       = {
            Title: ['supplier/supplier/supplier', 'tSPLTitle'],
            Table: {Master:'TCNMSpl', PK:'FTSplCode'},
            Join: {
                Table: ['TCNMSpl_L', 'TCNMSplCredit'],
                On: [
                    'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
                    'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode',
                ]
            },
            Where:{
                Condition : ["AND TCNMSpl.FTSplStaActive = '1' " + tWhereAgency]
            },
            GrideView:{
                ColumnPathLang: 'supplier/supplier/supplier',
                ColumnKeyLang: ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSpl.FTSplStaVATInOrEx','TCNMSplCredit.FNSplCrTerm'],
                DataColumnsFormat: ['',''],
                DisabledColumns: [2, 3],
                Perpage: 10,
                OrderBy: ['TCNMSpl_L.FTSplName ASC']
            },
            CallBack:{
                ReturnType: 'S',
                Value   : [tInputReturnCode,"TCNMSpl.FTSplCode"],
                Text    : [tInputReturnName,"TCNMSpl_L.FTSplName"]
            },
            NextFunc:{
                FuncName:'JSxNextFuncDOSpl',
                ArgReturn:['FTSplName', 'FTSplStaVATInOrEx', 'FNSplCrTerm']
            },
            RouteAddNew: 'supplier'
        };
        return oOptionReturn;
    }

    function JSxNextFuncDOSpl(paData) {
        $("#oetDOSplName").val("");
        $("#oetDOFrmSplInfoCrTerm").val("");
        var tDOSplName = '';
        var tDOTypePayment = '';
        var tDOFrmSplInfoCrTerm = '';
        var tDOFrmSplInfoVatInOrEx = '';
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aDOSplData = JSON.parse(paData);
            tDOSplName = aDOSplData[0];
            tDOFrmSplInfoVatInOrEx = aDOSplData[1];
            tDOTypePayment = aDOSplData[2]
            tDOFrmSplInfoCrTerm = aDOSplData[2]
        }
        $("#oetDOSplName").val(tDOSplName);
        $("#oetDOFrmSplInfoCrTerm").val(tDOFrmSplInfoCrTerm);

        //ประเภทการชำระเงิน
        if (tDOTypePayment > 0) {
            $("#ocmDOTypePayment").val("2").selectpicker('refresh');
            $('.xCNPanel_CreditTerm').show();
        }else{
            $("#ocmDOTypePayment").val("1").selectpicker('refresh');
            $('.xCNPanel_CreditTerm').hide();
        }

        //ประเภทภาษี
        if (tDOFrmSplInfoVatInOrEx == 1) {
            //รวมใน
            $("#ocmDOFrmSplInfoVatInOrEx").val("1").selectpicker('refresh');
        }else{
            //แยกนอก
            $("#ocmDOFrmSplInfoVatInOrEx").val("2").selectpicker('refresh');
        }


    }
    // ============================================================================================================

    // ========================================== Brows Event Conditon ===========================================
        // Event Browse Warehouse
        $('#obtDOBrowseWahouse').unbind().click(function(){
            // var nStaSession = JCNxFuncChkSessionExpired();
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oDOBrowseWahOption   = undefined;
                oDOBrowseWahOption          = oWahOption({
                    'tDOBchCode'        : $('#oetDOFrmBchCode').val(),
                    'tReturnInputCode'  : 'oetDOFrmWahCode',
                    'tReturnInputName'  : 'oetDOFrmWahName',
                    'aArgReturn'        : []
                });
                JCNxBrowseData('oDOBrowseWahOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtDOBrowseBCH').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetDOAgnCode').val();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oDOBrowseBranchOption  = undefined;
                oDOBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'oetDOFrmBchCode',
                    'tReturnInputName'  : 'oetDOFrmBchName',
                    'tAgnCode'          : tAgnCode,
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oDOBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

        });

        //BrowseAgn
        
        $('#oimDOBrowseAgn').click(function(e) {
            var tCheckIteminTable = $('#otbDODocPdtAdvTableList .xWPdtItem').length;
            if (tCheckIteminTable > 0) {
                $('#ohdDOTypeChange').val('Agn');
                $('#odvDOModalChangeData #ospDOTxtWarningAlert').text('<?php echo language('document/deliveryorder/deliveryorder', 'tDOChangeAgn') ?>');
                $('#odvDOModalChangeData').modal('show')
            }else{
                e.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                    JSxCheckPinMenuClose();
                    window.oDOBrowseAgencyOption = oBrowseAgn({
                        'tReturnInputCode': 'oetDOAgnCode',
                        'tReturnInputName': 'oetDOAgnName',
                    });
                    JCNxBrowseData('oDOBrowseAgencyOption');
                } else {
                    JCNxShowMsgSessionExpired();
                }
            }
        });

        // Event Browse Supplier
        $('#obtDOBrowseSupplier').unbind().click(function(){
            var tSplCode = $('#oetDOFrmSplCode').val();
            if (tSplCode != '') {
                $('#ohdDOTypeChange').val('Spl');
                $('#odvDOModalChangeData #ospDOTxtWarningAlert').text('<?php echo language('document/deliveryorder/deliveryorder', 'tDOChangeSpl') ?>');
                $('#odvDOModalChangeData').modal('show')
            }else{
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSxCheckPinMenuClose();
                    window.oDOBrowseSplOption   = undefined;
                    oDOBrowseSplOption          = oSplOption({
                        'tParamsAgnCode'    : '<?=$this->session->userdata("tSesUsrAgnCode")?>',
                        'tReturnInputCode'  : 'oetDOFrmSplCode',
                        'tReturnInputName'  : 'oetDOFrmSplName',
                        'aArgReturn'        : ['FTSplCode', 'FTSplName']
                    });
                    JCNxBrowseData('oDOBrowseSplOption');
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });

        $('#obtDOBrowseRefDocInt').on('click',function(){
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            var tDocType = $('#ocbDORefDoc').val();
            var tRefKeyOld = $('#ohdRefKeyOld').val();
            var tRefKeyNew = $('#oetDORefDoc').val();
            if (tRefKeyOld != '' && tRefKeyNew != '') {
                if (tRefKeyOld != tRefKeyNew) {
                    $('#ohdDOTypeChange').val('Ref');
                    $('#odvDOModalChangeData #ospDOTxtWarningAlert').text('<?php echo language('document/deliveryorder/deliveryorder', 'tDOChangeDocType') ?>');
                    $('#odvDOModalChangeData').modal('show')
                }else{
                    JSxCallDORefIntDoc(tDocType);
                }
            }else{
                JSxCallDORefIntDoc(tDocType);
            }
        });

        $('#obtDOChangeData').on('click',function(){
            JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
            //เคลีย SPL
            $('#oetDOFrmSplCode').val('');
            $('#oetDOFrmSplName').val('');
            $('#oetDOSplName').val('');
            $("#ocmDOTypePayment").val("1").selectpicker('refresh');
            $("#ocmDOFrmSplInfoVatInOrEx").val("1").selectpicker('refresh');

            var tDOChangeType = $('#ohdDOTypeChange').val()
            if (tDOChangeType == 'Ref') {
                var tDocType = $('#ocbDORefDoc').val();
                JSxCallDORefIntDoc(tDocType);
            }else if(tDOChangeType == 'Spl'){
                $('#otbDODocPdtAdvTableList .xWDelDocRef').click();
                $('#otbDODocRef .xWDelDocRef').click();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSxCheckPinMenuClose();
                    window.oDOBrowseSplOption   = undefined;
                    oDOBrowseSplOption          = oSplOption({
                        'tParamsAgnCode'    : '<?//=$this->session->userdata("tSesUsrAgnCode")?>',
                        'tReturnInputCode'  : 'oetDOFrmSplCode',
                        'tReturnInputName'  : 'oetDOFrmSplName',
                        'aArgReturn'        : ['FTSplCode', 'FTSplName']
                    });
                    JCNxBrowseData('oDOBrowseSplOption');
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }else if(tDOChangeType == 'Agn'){
                $('#ofmDOFormAdd').find('input').val('');
                $('#ofmDOFormAdd').find('select').val(1).selectpicker("refresh");
                $('#otbDODocPdtAdvTableList .xWDelDocRef').click();
                $('#otbDODocRef .xWDelDocRef').click();
                var nStaSession = JCNxFuncChkSessionExpired();
                if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                    JSxCheckPinMenuClose();
                    window.oDOBrowseAgencyOption = oBrowseAgn({
                        'tReturnInputCode': 'oetDOAgnCode',
                        'tReturnInputName': 'oetDOAgnName',
                    });
                    JCNxBrowseData('oDOBrowseAgencyOption');
                } else {
                    JCNxShowMsgSessionExpired();
                }
            }
        });

        //Browse เอกสารอ้างอิงภายใน
        function JSxCallDORefIntDoc(ptDocType){
            var tBCHCode = $('#oetDOFrmBchCode').val()
            var tBCHName = $('#oetDOFrmBchName').val()
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docDOCallRefIntDoc",
                data: {
                    'tDocType'     : ptDocType,
                    'tBCHCode'      : tBCHCode,
                    'tBCHName'      : tBCHName,
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    JCNxCloseLoading();
                    $('#odvDOFromRefIntDoc').html(oResult);
                    if (ptDocType == 1 ) {
                        $('#odvDOModalRefIntDoc #olbTextModalHead').text('<?php echo language('document/purchaseorder/purchaseorder', 'อ้างอิงเอกสารใบสั่งซื้อ') ?>');
                    } else {
                        $('#odvDOModalRefIntDoc #olbTextModalHead').text('<?php echo language('document/purchaseorder/purchaseorder', 'อ้างอิงเอกสารใบขาย') ?>');
                    }
                    
                    $('#odvDOModalRefIntDoc').modal({
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
                $('#odvDOModalRefIntDoc').modal('hide');
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
                    $('#odvDOModalPONoFound').modal('show');
                } else {
                    JSxDOSetPanelSupplierData(poParams);
                    $('#oetDORefIntDoc').val(tRefIntDocNo);
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docDOCallRefIntDocInsertDTToTemp",
                        data: {
                            'tDODocNo'          : $('#oetDODocNo').val(),
                            'tDOFrmBchCode'     : $('#oetDOFrmBchCode').val(),
                            'tDOOptionAddPdt'   : $('#ocmDOFrmInfoOthReAddPdt').val(),
                            'tRefIntDocNo'      : tRefIntDocNo,
                            'tRefIntBchCode'    : tRefIntBchCode,
                            'aSeqNo'            : aSeqNo,
                            'tDoctype'          : tDoctype
                        },
                        cache: false,
                        Timeout: 0,
                        success: function (oResult){
                            // FSvDONextFuncB4SelPDT();
                            JSvDOLoadPdtDataTableHtml();
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
            $('#odvDOModalRefIntDoc').modal('show');
        });

        // Function : ฟังก์ชั่นเซทข้อมูล ผู้จำหน่าย
        function JSxDOSetPanelSupplierData(poParams){
            // Reset Panel เป็นค่าเริ่มต้น
            $("#ocmDOFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
            $("#ocmDOTypePayment.selectpicker").val("2").selectpicker("refresh");
            $("#oetDORefIntDoc").val(poParams.FTRefIntDocNo);
            $("#oetDORefDocDate").val(poParams.FTRefIntDocDate).datepicker("refresh");
            $("#ohdDORefDocTime").val(poParams.FTRefIntDocTime);
            // ประเภทภาษี
            if(poParams.FTSplStaVATInOrEx == 1){
                // รวมใน
                $("#ocmDOFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
            }else{
                // แยกนอก
                $("#ocmDOFrmSplInfoVatInOrEx.selectpicker").val("2").selectpicker("refresh");
            }

            // ประเภทชำระเงิน
            if(poParams.FCSplCrLimit > 0){
                // เงินเชื่อ
                $("#ocmDOTypePayment.selectpicker").val("2").selectpicker("refresh");
                $('.xCNPanel_CreditTerm').show();
            }else{
                // เงินสด
                $("#ocmDOTypePayment.selectpicker").val("1").selectpicker("refresh");
                $('.xCNPanel_CreditTerm').hide();

            }
            //สาขา
            // $("#oetDOFrmBchCode").val(poParams.FTBchCode);
            // $("#oetDOFrmBchName").val(poParams.FTBchName);

            //ผู้ขาย
            $("#oetDOFrmSplCode").val(poParams.FTSplCode);
            $("#oetDOFrmSplName").val(poParams.FTSplName);
            $("#oetDOSplName").val(poParams.FTSplName);
            $("#oetDOFrmSplInfoCrTerm").val(poParams.FCSplCrLimit);

            $("#ohdDODocType").val(poParams.tDoctype);
        }

//------------------------------------------------------------------------------------------------//

    // Validate From Add Or Update Document
    function JSxDOValidateFormDocument(){
        if($("#ohdDOCheckClearValidate").val() != 0){
            $('#ofmDOFormAdd').validate().destroy();
        }

        $('#ofmDOFormAdd').validate({
            focusInvalid: true,
            rules: {
                oetDODocNo : {
                    "required" : {
                        depends: function (oElement) {
                            if($("#ohdDORoute").val()  ==  "docDOEventAdd"){
                                if($('#ocbDOStaAutoGenCode').is(':checked')){
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
                oetDOFrmBchName    : {"required" : true},
                oetDOFrmSplName : {"required" : true},
                oetDOFrmWahName : {"required" : true},
            },
            messages: {
                oetDODocNo      : {"required" : $('#oetDODocNo').attr('data-validate-required')},
                oetDOFrmBchName : {"required" : $('#oetDOFrmBchName').attr('data-validate-required')},
                oetDOFrmSplName : {"required" : $('#oetDOFrmSplName').attr('data-validate-required')},
                oetDOFrmWahName : {"required" : $('#oetDOFrmWahName').attr('data-validate-required')},
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
                if(!$('#ocbDOStaAutoGenCode').is(':checked')){
                    JSxDOValidateDocCodeDublicate();
                }else{
                    if($("#ohdDOCheckSubmitByButton").val() == 1){
                        JSxDOSubmitEventByButton();
                    }
                }
            },
        });
    }

    // Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
    function JSxDOValidateDocCodeDublicate(){
        //JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName'    : 'TAPTDoHD',
                'tFieldName'    : 'FTXphDocNo',
                'tCode'         : $('#oetDODocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                $("#ohdDOCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdDOCheckClearValidate").val() != 1) {
                    $('#ofmDOFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdDORoute").val() == "docDOEventAdd"){
                        if($('#ocbDOStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdDOCheckDuplicateCode").val() == 1) {
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
                $('#ofmDOFormAdd').validate({
                    focusInvalid: false,
                    onclick: false,
                    onfocusout: false,
                    onkeyup: false,
                    rules: {
                        oetDODocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetDODocNo : {"dublicateCode"  : $('#oetDODocNo').attr('data-validate-duplicate')}
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
                        if($("#ohdDOCheckSubmitByButton").val() == 1) {
                            JSxDOSubmitEventByButton();
                        }
                    }
                })

                if($("#ohdDOCheckClearValidate").val() != 1) {
                    $("#ofmDOFormAdd").submit();
                    $("#ohdDOCheckClearValidate").val(1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function: Validate Success And Send Ajax Add/Update Document
    function JSxDOSubmitEventByButton(ptType = ''){
        var tDODocNo = '';
        if($("#ohdDORoute").val() !=  "docDOEventAdd"){
            var tDODocNo    = $('#oetDODocNo').val();
        }
        $('#obtDOSubmitFromDoc').attr('disabled',true);
        $.ajax({
            type: "POST",
            url: "docDOChkHavePdtForDocDTTemp",
            data: {
                'ptDODocNo'         : tDODocNo,
                'tDOSesSessionID'   : $('#ohdSesSessionID').val(),
                'tDOUsrCode'        : $('#ohdDOUsrCode').val(),
                'tDOLangEdit'       : $('#ohdDOLangEdit').val(),
                'tSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                // JCNxCloseLoading();
                var aDataReturnChkTmp   = JSON.parse(oResult);
                $('.xWDODisabledOnApv').attr('disabled',false);
                if (aDataReturnChkTmp['nStaReturn'] == '1'){
                    $.ajax({
                        type    : "POST",
                        url     : $("#ohdDORoute").val(),
                        data    : $("#ofmDOFormAdd").serialize(),
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            // JCNxCloseLoading();
                            var aDataReturnEvent    = JSON.parse(oResult);
                            if(aDataReturnEvent['nStaReturn'] == '1'){
                                var nDOStaCallBack      = aDataReturnEvent['nStaCallBack'];
                                var nDODocNoCallBack    = aDataReturnEvent['tCodeReturn'];
                                var nDOPayType          = $('#ocmDOTypePayment').val();
                                var nDOVatInOrEx        = $('#ocmDOFrmSplInfoVatInOrEx').val();
                                var nDOStaRef           = $('#ocmDOFrmInfoOthRef').val();

                                var oDOCallDataTableFile = {
                                    ptElementID : 'odvDOShowDataTable',
                                    ptBchCode   : $('#oetDOFrmBchCode').val(),
                                    ptDocNo     : nDODocNoCallBack,
                                    ptDocKey    :'TAPTDoHD',
                                }
                                JCNxUPFInsertDataFile(oDOCallDataTableFile);
                                if(ptType == 'approve'){
                                    JSxDOApproveDocument(false);
                                }else{
                                    switch(nDOStaCallBack){
                                        case '1' :
                                            JSvDOCallPageEdit(nDODocNoCallBack,nDOPayType,nDOVatInOrEx,nDOStaRef);
                                        break;
                                        case '2' :
                                            JSvDOCallPageAddDoc();
                                        break;
                                        case '3' :
                                            JSvDOCallPageList();
                                        break;
                                        default :
                                            JSvDOCallPageEdit(nDODocNoCallBack,nDOPayType,nDOVatInOrEx,nDOStaRef);
                                    }
                                }
                                $("#obtDOSubmitFromDoc").removeAttr("disabled");
                            }else{
                                var tMessageError = aDataReturnEvent['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                                $("#obtDOSubmitFromDoc").removeAttr("disabled");
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
    function JSxDOCountPdtItems(){
        var nPdtItems = $('.xWPdtItem').length;
        $('.xShowQtyFooter').text(accounting.formatNumber(nPdtItems, 0, ','));
    }

    $('#ocmDOTypePayment').on('change', function() {
        if (this.value == 1) {
            $('.xCNPanel_CreditTerm').hide();
        } else {
            $('.xCNPanel_CreditTerm').show();
        }
    });


    // Rabbit MQ
    function JSoDOCallSubscribeMQ() {
        // Document variable
        var tLangCode = $("#ohdDOLangEdit").val();
        var tUsrBchCode = $("#ohdDOBchCode").val();
        var tUsrApv = '<?=$this->session->userdata('tSesUsername')?>';
        var tDocNo = $("#oetDODocNo").val();
        var tPrefix = "RESDO";
        var tStaApv = $("#ohdDOStaApv").val();
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
            tCallPageEdit: "JSvDOCallPageEdit",
            tCallPageList: "JSvDOCallPageList"
        };

        // Check Show Progress %
        FSxCMNRabbitMQMessage(poDocConfig, poMqConfig, poUpdateStaDelQnameParams, poCallback);
    }

    //พิมพ์เอกสาร
    function JSxDOPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tDOBchCode); ?>'},
            {"DocCode"      : '<?=@$tDODocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tDOBchCode;?>'}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillReceive?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

    //โหลด Table อ้างอิงเอกสารทั้งหมด
    function JSxDOCallPageHDDocRef(){
        var tDocNo  = "";
        if ($("#ohdDORoute").val() == "docDOEventEdit") {
            tDocNo = $('#oetDODocNo').val();
        }

        $.ajax({
            type    : "POST",
            url     : "docDOPageHDDocRef",
            data:{
                'ptDocNo' : tDocNo
            },
            cache   : false,
            timeout : 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                $('#odvDOTableHDRef').html(aResult['tViewPageHDRef']);
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
    $('#obtDOAddDocRef').off('click').on('click',function(){
        $('#ofmDOFormAddDocRef').validate().destroy();
        JSxDOEventClearValueInFormHDDocRef();
        $('.xWShowRefExt').hide();
        $('.xWShowRefInt').show();
        $('#odvDOModalAddDocRef').modal('show');
    });

    //เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    $('#ocbDORefType').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        JSxDOEventCheckShowHDDocRef();
    });

    $('#ocbDORefDoc').off('change').on('change',function(){
        var tRefDoc = $('#ocbDORefDoc').val();
        if (tRefDoc == 1) {
            $('#oetDORefDoc').val('PO');
        }else{
            $('#oetDORefDoc').val('ABB');
        }
        
    });

    //เคลียร์ค่า
    function JSxDOEventClearValueInFormHDDocRef(){
        $('#oetDORefDocNo').val('');
        $('#oetDORefDocDate').val('');
        $('#oetDORefIntDoc').val('');
        $('#oetDODocRefIntName').val('');
        $('#oetDORefKey').val('');
        
        var tRefDoc = $('#oetDORefDoc').val();
        if (tRefDoc == 'PO') {
            $("#ocbDORefDoc").val("1").selectpicker('refresh');
        }else if (tRefDoc == 'ABB'){
            $("#ocbDORefDoc").val("2").selectpicker('refresh');  
        }else{
            $("#ocbDORefDoc").val("2").selectpicker('refresh');
        }
        
        $("#ocbDORefType").val("1").selectpicker('refresh');
    }

    //กดยืนยันบันทึกลง Temp
    $('#ofmDOFormAddDocRef').off('click').on('click',function(){
        $('#ofmDOFormAddDocRef').validate().destroy();
        $('#ofmDOFormAddDocRef').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetDORefIntDoc    : {"required" : true}
            },
            messages: {
                oetDORefIntDoc    : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
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

                var tDocType = $('#ocbDORefDoc').val();
                if (tDocType == 1) {
                    var tRefKey   = "PO";
                }else{
                    var tRefKey   = "ABB";
                }
                if($('#ocbDORefType').val() == 1){ //อ้างอิงเอกสารภายใน
                    var tDocNoRef = $('#oetDORefIntDoc').val();
                }else{ //อ้างอิงเอกสารภายนอก
                    var tDocNoRef = $('#oetDORefDocNo').val();
                    var tRefKey   = $('#oetDORefKey').val();
                }

                $.ajax({
                    type    : "POST",
                    url     : "docDOEventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld'     : $('#oetDORefDocNoOld').val(),
                        'ptDocNo'           : $('#oetDODocNo').val(),
                        'ptRefType'         : $('#ocbDORefType').val(),
                        'ptRefDocNo'        : tDocNoRef,
                        'pdRefDocDate'      : $('#oetDORefDocDate').val()+' '+$('#ohdDORefDocTime').val(),
                        'ptRefKey'          : tRefKey
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult){
                        var aReturnData = JSON.parse(oResult);
                        JSxDOEventClearValueInFormHDDocRef();
                        $('#odvDOModalAddDocRef').modal('hide');

                        if (aReturnData['nStaEvent'] == 2) {
                            FSvCMNSetMsgErrorDialog("เลขที่เอกสารนี้ถูกอ้างอิงแล้ว");
                        }

                        JSxDOCallPageHDDocRef();
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

</script>
