<script src="<?=base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script type="text/javascript">
    var nLangEdits        = '<?=$this->session->userdata("tLangEdit");?>';
    var tUsrApvName       = '<?=$this->session->userdata("tSesUsername");?>';
    var tSesUsrLevel      = '<?=$this->session->userdata('tSesUsrLevel');?>';
    var tUserBchCode      = '<?=$this->session->userdata("tSesUsrBchCode");?>';
    var tUserBchName      = '<?=$this->session->userdata("tSesUsrBchName");?>';
    var tUserWahCode      = '<?=$this->session->userdata("tSesUsrWahCode");?>';
    var tRoute            = $('#ohdPRSRoute').val();
    var tPRSSesSessionID  = $("#ohdSesSessionID").val();

    $(document).ready(function(){
        var nCrTerm = $('#ocmPRSTypePayment').val();
        if (nCrTerm == 2) {
            $('.xCNPanel_CreditTerm').show();
        }else{
            $('.xCNPanel_CreditTerm').hide();
        }
        JSxCheckPinMenuClose(); 

        $('.selectpicker').selectpicker('refresh');

        $('.xCNDatePicker').datepicker({
            format                  : "yyyy-mm-dd",
            todayHighlight          : true,
            enableOnReadonly        : false,
            disableTouchKeyboard    : true,
            autoclose               : true
        });
        $("#obtPRSSubmitFromDoc").removeAttr("disabled");

        var dCurrentDate    = new Date();

        if($('#oetPRSDocDate').val() == ''){
            $('#oetPRSDocDate').datepicker("setDate",dCurrentDate);
        }

        $('.xCNTimePicker').datetimepicker({
            format: 'HH:mm:ss'
        });

        $('.xWTooltipsBT').tooltip({'placement': 'bottom'});

        $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});

        $(".xWConditionSearchPdt.disabled").attr("disabled","disabled");

        //เพิ่มสินค้าจากการปุ่ม + 
        $('#obtPRSDocBrowsePdt').unbind().click(function(){
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                if($('#oetPRSFrmSplCode').val()!=""){
                    JSxCheckPinMenuClose();
                    JCNvPRSBrowsePdt();
                }else{
                    $('#odvPRSModalPleseselectSPL').modal('show');
                }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        var dCurrentDate    = new Date();
        var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
        var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes() + " " + tAmOrPm;

        if($('#oetPRSDocDate').val() == ''){
            $('#oetPRSDocDate').datepicker("setDate",dCurrentDate);
        }

        if($('#oetPRSDocTime').val() == ''){
            $('#oetPRSDocTime').val(tCurrentTime);
        }

        $('#obtPRSDocDate').unbind().click(function(){
            $('#oetPRSDocDate').datepicker('show');
        });

        $('#obtPRSDocTime').unbind().click(function(){
            $('#oetPRSDocTime').datetimepicker('show');
        });

        $('#obtPRSBrowseRefIntDocDate').unbind().click(function(){
            $('#oetPRSRefIntDocDate').datepicker('show');
        });

        $('#obtPRSRefDocExtDate').unbind().click(function(){
            $('#oetPRSRefDocExtDate').datepicker('show');
        });

        $('#obtPRSTransDate').unbind().click(function(){
            $('#oetPRSTransDate').datepicker('show');
        });

        $('#ocbPRSStaAutoGenCode').on('change', function (e) {
            if($('#ocbPRSStaAutoGenCode').is(':checked')){
                $("#oetPRSDocNo").val('');
                $("#oetPRSDocNo").attr("readonly", true);
                $('#oetPRSDocNo').closest(".form-group").css("cursor","not-allowed");
                $('#oetPRSDocNo').css("pointer-events","none");
                $("#oetPRSDocNo").attr("onfocus", "this.blur()");
                $('#ofmPRSFormAdd').removeClass('has-error');
                $('#ofmPRSFormAdd .form-group').closest('.form-group').removeClass("has-error");
                $('#ofmPRSFormAdd em').remove();
            }else{
                $('#oetPRSDocNo').closest(".form-group").css("cursor","");
                $('#oetPRSDocNo').css("pointer-events","");
                $('#oetPRSDocNo').attr('readonly',false);
                $("#oetPRSDocNo").removeAttr("onfocus");
            }
        });
    });

    var oBranchOptionAgn = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tAgnCode            = poDataFnc.tAgnCode;
        var aArgReturn          = poDataFnc.aArgReturn;

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if(tUsrLevel != "HQ"){
            // tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+")";
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
                FuncName:'JSxNextFuncPRSBch'
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

    function JSxNextFuncPRSBch() {
        $('#oetPRSFrmWahCode').val('');
        $('#oetPRSFrmWahName').val('');
    }

    //Option Agency
    var nLangEdits      = <?=$this->session->userdata("tLangEdit") ?>;
    var oBrowseAgn      = function(poReturnInput) {
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
                    FuncName:'JSxNextFuncPRSAgn'
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

    function JSxNextFuncPRSAgn() {
        $('#oetPRSFrmBchCode').val('');
        $('#oetPRSFrmBchName').val('');
        $('#oetPRSFrmWahCode').val('');
        $('#oetPRSFrmWahName').val('');
    }

    var tStaUsrLevel    = '<?=$this->session->userdata("tSesUsrLevel"); ?>';

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
                FuncName:'JSxNextFuncPRSSpl',
                ArgReturn:['FTSplName', 'FTSplStaVATInOrEx', 'FNSplCrTerm']
            },
            RouteAddNew: 'supplier'
        };
        return oOptionReturn;
    }

    function JSxNextFuncPRSSpl(paData) {
        $("#oetPRSSplName").val("");
        $("#oetPRSFrmSplInfoCrTerm").val("");
        var tPRSSplName = '';
        var tPRSTypePayment = '';
        var tPRSFrmSplInfoCrTerm = '';
        var tPRSFrmSplInfoVatInOrEx = '';
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aPRSSplData = JSON.parse(paData);
            tPRSSplName = aPRSSplData[0];
            tPRSFrmSplInfoVatInOrEx = aPRSSplData[1];
            tPRSTypePayment = aPRSSplData[2]
            tPRSFrmSplInfoCrTerm = aPRSSplData[2]
        }
        $("#oetPRSSplName").val(tPRSSplName);
        $("#oetPRSFrmSplInfoCrTerm").val(tPRSFrmSplInfoCrTerm);

        //ประเภทการชำระเงิน
        if (tPRSTypePayment > 0) {
            $("#ocmPRSTypePayment").val("2").selectpicker('refresh');
        }else{
            $("#ocmPRSTypePayment").val("1").selectpicker('refresh');
        }

        //ประเภทภาษี
        if (tPRSFrmSplInfoVatInOrEx == 1) {
            //รวมใน
            $("#ocmPRSFrmSplInfoVatInOrEx").val("1").selectpicker('refresh');
        }else{
            //แยกนอก
            $("#ocmPRSFrmSplInfoVatInOrEx").val("2").selectpicker('refresh');
        }


    }

    $('#obtPRSBrowseBCH').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetPRSAgnCode').val();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oPRSBrowseBranchOption  = undefined;
                oPRSBrowseBranchOption         = oBranchOptionAgn({
                    'tReturnInputCode'  : 'oetPRSFrmBchCode',
                    'tReturnInputName'  : 'oetPRSFrmBchName',
                    'tAgnCode'          : tAgnCode,
                    'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
                });
                JCNxBrowseData('oPRSBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

    });

    //BrowseAgn
    $('#oimPRSBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPRSBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetPRSAgnCode',
                'tReturnInputName': 'oetPRSAgnName',
            });
            JCNxBrowseData('oPRSBrowseAgencyOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Supplier
    $('#obtPRSBrowseSupplier').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPRSBrowseSplOption   = undefined;
            oPRSBrowseSplOption          = oSplOption({
                'tParamsAgnCode'    : '<?=$this->session->userdata("tSesUsrAgnCode")?>',
                'tReturnInputCode'  : 'oetPRSFrmSplCode',
                'tReturnInputName'  : 'oetPRSFrmSplName',
                'aArgReturn'        : ['FTSplCode', 'FTSplName']
            });
            JCNxBrowseData('oPRSBrowseSplOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //Browse เอกสารอ้างอิงภายใน
    function JSxCallPRSRefIntDoc(){
        var tBCHCode = $('#oetPRSFrmBchCode').val()
        var tBCHName = $('#oetPRSFrmBchName').val()
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docPRSCallRefIntDoc",
            data: {
                'tBCHCode'      : tBCHCode,
                'tBCHName'      : tBCHName,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                JCNxCloseLoading();
                $('#odvPRSFromRefIntDoc').html(oResult);
                $('#odvPRSModalRefIntDoc').modal({backdrop : 'static' , show : true});
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }   

    //กดยืนยันที่จะเลือกเอกสารอ้างอิง
    $('#obtConfirmRefDocInt').click(function(){
        var tRefIntDocNo    =  $('.xPRSRefInt.active').data('docno');
        var tRefIntDocDate  =  $('.xPRSRefInt.active').data('docdate');
        var tRefIntBchCode  =  $('.xPRSRefInt.active').data('bchcode');
        var aSeqNo          =  $('.ocbRefIntDocDT:checked').map(function(elm){
            return $(this).val();
        }).get();

        var tSplStaVATInOrEx =  $('.xPRSRefInt.active').data('vatinroex');
        var cSplCrLimit      =  $('.xPRSRefInt.active').data('crtrem');
        var nSplCrTerm       =  $('.xPRSRefInt.active').data('crlimit');
        // var tSplCode      =  $('.xPRSRefInt.active').data('splcode');
        // var tSplName      =  $('.xPRSRefInt.active').data('splname');
        var tSplName         =  $('#oetPRSFrmSplName').val();
        var tSplCode         =  $('#oetPRSFrmSplCode').val();

        var poParams = {
            FCSplCrLimit        : cSplCrLimit,
            FTSplCode           : tSplCode,
            FTSplName           : tSplName,
            FTSplStaVATInOrEx   : tSplStaVATInOrEx,
            FTRefIntDocNo       : tRefIntDocNo,
            FTRefIntDocDate     : tRefIntDocDate,
        };
        JSxPRSSetPanelSupplierData(poParams);

        $('#oetPRSRefIntDoc').val(tRefIntDocNo);
        JCNxOpenLoading();

        //เอกสารอ้างอิง
        $('#oetPRSDocRefInt').val(tRefIntDocNo);
        $('#oetPRSDocRefIntName').val(tRefIntDocNo);
        $('#oetPRSRefDocDate').val(tRefIntDocDate);
        $('#oetPRSRefKey').val('PRHQ');

        $.ajax({
            type    : "POST",
            url     : "docPRSCallRefIntDocInsertDTToTemp",
            data    : {
                'tPRSDocNo'             : $('#oetPRSDocNo').val(),
                'tPRSFrmBchCode'        : $('#oetPRSFrmBchCode').val(),
                'tPRSOptionAddPdt'      : $('#ocmPRSFrmInfoOthReAddPdt').val(),
                'tRefIntDocNo'          : tRefIntDocNo,
                'tRefIntBchCode'        : tRefIntBchCode,
                'aSeqNo'                : aSeqNo
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                console.log(oResult);
                JSvPRSLoadPdtDataTableHtml();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

    });

    // Function : ฟังก์ชั่นเซทข้อมูล ผู้จำหน่าย
    function JSxPRSSetPanelSupplierData(poParams){
        // Reset Panel เป็นค่าเริ่มต้น
        $("#ocmPRSFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
        $("#ocmPRSTypePayment.selectpicker").val("2").selectpicker("refresh");
        $("#oetPRSRefDocIntName").val(poParams.FTRefIntDocNo);
        $("#oetPRSRefIntDocDate").val(poParams.FTRefIntDocDate).datepicker("refresh");

        // ประเภทภาษี
        if(poParams.FTSplStaVATInOrEx === "1"){
            // รวมใน
            $("#ocmPRSFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
        }else{
            // แยกนอก
            $("#ocmPRSFrmSplInfoVatInOrEx.selectpicker").val("2").selectpicker("refresh");
        }

        // ประเภทชำระเงิน
        if(poParams.FCSplCrLimit > 0){
            // เงินเชื่อ
            $("#ocmPRSTypePayment.selectpicker").val("2").selectpicker("refresh");
        }else{
            // เงินสด
            $("#ocmPRSTypePayment.selectpicker").val("1").selectpicker("refresh");
        }

        //ผู้ขาย
        $("#oetPRSFrmSplCode").val(poParams.FTSplCode);
        $("#oetPRSFrmSplName").val(poParams.FTSplName);
        $("#oetPRSSplName").val(poParams.FTSplName);
        $("#oetPRSFrmSplInfoCrTerm").val(poParams.FCSplCrLimit);
    }

    // Validate From Add Or Update Document
    function JSxPRSValidateFormDocument(){
        if($("#ohdPRSCheckClearValidate").val() != 0){
            $('#ofmPRSFormAdd').validate().destroy();
        }

        $('#ofmPRSFormAdd').validate({
            focusInvalid: true,
            rules: {
                oetPRSDocNo : {
                    "required" : {
                        depends: function (oElement) {
                            if($("#ohdPRSRoute").val()  ==  "docPRSEventAdd"){
                                if($('#ocbPRSStaAutoGenCode').is(':checked')){
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
                oetPRSFrmBchName    : {"required" : true},
                oetPRSFrmSplName : {"required" : true},
                oetPRSToBchName : {"required" : true},
            },
            messages: {
                oetPRSDocNo      : {"required" : $('#oetPRSDocNo').attr('data-validate-required')},
                oetPRSFrmBchName : {"required" : $('#oetPRSFrmBchName').attr('data-validate-required')},
                oetPRSFrmSplName : {"required" : $('#oetPRSFrmSplName').attr('data-validate-required')},
                oetPRSToBchName : {"required" : $('#oetPRSToBchName').attr('data-validate-required')},
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
                if(!$('#ocbPRSStaAutoGenCode').is(':checked')){
                    JSxPRSValidateDocCodeDublicate();
                }else{
                    if($("#ohdPRSCheckSubmitByButton").val() == 1){
                        JSxPRSSubmitEventByButton();
                    }
                }
            },
        });
    }

    // Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
    function JSxPRSValidateDocCodeDublicate(){
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName'    : 'TCNTPdtReqSplHD',
                'tFieldName'    : 'FTXphDocNo',
                'tCode'         : $('#oetPRSDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                $("#ohdPRSCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdPRSCheckClearValidate").val() != 1) {
                    $('#ofmPRSFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdPRSRoute").val() == "docPRSEventAdd"){
                        if($('#ocbPRSStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdPRSCheckDuplicateCode").val() == 1) {
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
                $('#ofmPRSFormAdd').validate({
                    focusInvalid: false,
                    onclick: false,
                    onfocusout: false,
                    onkeyup: false,
                    rules: {
                        oetPRSDocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetPRSDocNo : {"dublicateCode"  : $('#oetPRSDocNo').attr('data-validate-duplicate')}
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
                        if($("#ohdPRSCheckSubmitByButton").val() == 1) {
                            JSxPRSSubmitEventByButton();
                        }
                    }
                })

                if($("#ohdPRSCheckClearValidate").val() != 1) {
                    $("#ofmPRSFormAdd").submit();
                    $("#ohdPRSCheckClearValidate").val(1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    $('#obtPRSBrowseAgencyTo').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPdtBrowseAgencyOption = oPdtBrowseAgency({
                'tReturnInputCode': 'oetPRSAgnCodeTo',
                'tReturnInputName': 'oetPRSAgnNameTo'
            });
            JCNxBrowseData('oPdtBrowseAgencyOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //เลือกสาขา
    var oPdtBrowseAgency = function(poReturnInput) {
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
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text: [tInputReturnName, "TCNMAgency_L.FTAgnName"],
            },
            BrowseLev: 1
        }
        return oOptionReturn;
    }

    $('#obtPRSBrowseBCHTo').click(function(){
        // JCNxBrowseData('oBrowse_BCH');
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPOBrowseBranchOption  = undefined;
            oPOBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'oetPRSToBchCode',
                'tReturnInputName'  : 'oetPRSToBchName',
                'tNextFuncName'     : '',
                'tPOAgnCode'        : $('#oetPRSAgnCodeTo').val(),
                'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
            });
            JCNxBrowseData('oPOBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ตัวแปร Option Browse Modal สาขา
    var oBranchOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tPOAgnCode          = poDataFnc.tPOAgnCode;

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhere = "";
        if(tUsrLevel != "HQ"){
            tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }

        if(tPOAgnCode!=''){
            tSQLWhere = " AND TCNMBranch.FTAgnCode ='"+tPOAgnCode+"' ";
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
                Condition : [tSQLWhere]
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
            },
            RouteAddNew: 'branch',
            BrowseLev: 1
        };
        return oOptionReturn;
    }

    //บันทึกเอกสาร
    function JSxPRSSubmitEventByButton(ptType = ''){
        var tPRSDocNo = '';

        if($("#ohdPRSRoute").val() !=  "docPRSEventAdd"){
            var tPRSDocNo    = $('#oetPRSDocNo').val();
        }
        $('#obtPRSSubmitFromDoc').attr('disabled',true);
        $.ajax({
            type: "POST",
            url: "docPRSChkHavePdtForDocDTTemp",
            data: {
                'ptPRSDocNo'         : tPRSDocNo,
                'tPRSSesSessionID'   : $('#ohdSesSessionID').val(),
                'tPRSUsrCode'        : $('#ohdPRSUsrCode').val(),
                'tPRSLangEdit'       : $('#ohdPRSLangEdit').val(),
                'tSesUsrLevel'       : $('#ohdSesUsrLevel').val(),
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                // JCNxCloseLoading();
                var aDataReturnChkTmp   = JSON.parse(oResult);
                $('.xWPRSDisabledOnApv').attr('disabled',false);
                if (aDataReturnChkTmp['nStaReturn'] == '1'){
                    $.ajax({
                        type    : "POST",
                        url     : $("#ohdPRSRoute").val(),
                        data    : $("#ofmPRSFormAdd").serialize(),
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            // JCNxCloseLoading();
                            var aDataReturnEvent    = JSON.parse(oResult);
                            if(aDataReturnEvent['nStaReturn'] == '1'){
                                var nPRSStaCallBack      = aDataReturnEvent['nStaCallBack'];
                                var nPRSDocNoCallBack    = aDataReturnEvent['tCodeReturn'];

                                /*var oPRSCallDataTableFile = {
                                    ptElementID : 'odvPRSShowDataTable',
                                    ptBchCode   : $('#oetPRSFrmBchCode').val(),
                                    ptDocNo     : nPRSDocNoCallBack,
                                    ptDocKey    :'TCNTPdtReqSplHD',
                                }
                                JCNxUPFInsertDataFile(oPRSCallDataTableFile);*/
                                
                                if(ptType == 'approve'){
                                    JSxPRSApproveDocument(false);
                                }else{
                                    switch(nPRSStaCallBack){
                                        case '1' :
                                            JSvPRSCallPageEdit(nPRSDocNoCallBack);
                                        break;
                                        case '2' :
                                            JSvPRSCallPageAddDoc();
                                        break;
                                        case '3' :
                                            JSvPRSCallPageList();
                                        break;
                                        default :
                                            JSvPRSCallPageEdit(nPRSDocNoCallBack);
                                    }
                                }
                                $("#obtPRSSubmitFromDoc").removeAttr("disabled");
                            }else{
                                var tMessageError = aDataReturnEvent['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                                $("#obtPRSSubmitFromDoc").removeAttr("disabled");
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
    function JSxPRSCountPdtItems(){
        var nPdtItems = $('.xWPdtItem').length;
        $('.xShowQtyFooter').text(accounting.formatNumber(nPdtItems, 0, ','));
    }

    $('#ocmPRSTypePayment').on('change', function() {
        if (this.value == 1) {
            $('.xCNPanel_CreditTerm').hide();
        } else {
            $('.xCNPanel_CreditTerm').show();
        }
    });

    //พิมพ์เอกสาร
    function JSxPRSPrintDoc(){
        var aInfor       = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tPRSBchCode); ?>'},
            {"DocCode"      : '<?=@$tPRSDocNo; ?>'},
            {"DocBchCode"   : '<?=@$tPRSBchCode;?>'},
        ];

        if($('#ohdPRSTypeDocument').val() == 2){ //ใบขอซื้อแบบแฟรนไชส์
            window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillPqFC?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
        }else{
            window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillPqSpl?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
        }

    }
    
</script>
