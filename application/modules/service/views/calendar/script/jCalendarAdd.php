<script type="text/javascript">
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
        event.preventDefault();
        return false;
        }
    });

    $('.selection-2').selectpicker();

    $('#ocmRcnGroup').change(function() {
        $('#ocmRcnGroup-error').hide();
    });

    if($('#ohdCheckStatus').val() != 1){
        $("#ocbCalendarStatus").prop('checked', false);;
    }

    $(document).ready(function () {
        if(JSbCalendarIsCreatePage()){
            $("#oetCldCode").attr("disabled", true);
            $('#ocbCalendarAutoGenCode').change(function(){
                if($('#ocbCalendarAutoGenCode').is(':checked')) {
                    $('#oetCldCode').val('');
                    $("#oetCldCode").attr("disabled", true);
                    $('#odvCalendarCodeForm').removeClass('has-error');
                    $('#odvCalendarCodeForm em').remove();
                }else{
                    $("#oetCldCode").attr("disabled", false);
                }
            });
            JSxCalendarVisibleComponent('#odvCalendarAutoGenCode', true);
        }

        if(JSbCalendarIsUpdatePage()){
            // Sale Person Code
            $("#oetCldCode").attr("readonly", true);
            $('#odvCalendarAutoGenCode input').attr('disabled', true);
            JSxCalendarVisibleComponent('#odvCalendarAutoGenCode', false);    
        }
        // Event Tab
        $('#odvCldPanelBody .xCNCLDTab').unbind().click(function(){
            let tPosRoute       = '<?php echo @$tRoute;?>';
            if(tPosRoute == 'calendarEventAdd'){
                return;
            }else{
                let tTypeTab    = $(this).data('typetab');
                if(typeof(tTypeTab) !== undefined && tTypeTab == 'main'){
                    JCNxOpenLoading();
                    setTimeout(function(){
                        $('#odvPosMainMenu #odvBtnAddEdit').show();
                        JCNxCloseLoading();
                        return;
                    },500);
                }else if(typeof(tTypeTab) !== undefined && tTypeTab == 'sub'){
                    $('#odvPosMainMenu #odvBtnAddEdit').hide();
                    let tTabTitle   = $(this).data('tabtitle');
                    switch(tTabTitle){
                        case 'posinfouser':
                            JCNxOpenLoading();
                            setTimeout(function(){
                                JCNxCloseLoading();
                                return;
                            },500);
                        break;
                    }
                }   
            }
        });
    });

    //Functionality : (event) Add/Edit Calendar
    //Parameters : form
    //Creator : 19/05/2021 Off
    //Return : object Status Event And Event Call Back
    //Return Type : object
    function JSnAddEditCalendar(ptRoute){
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            let nStaChkAutoGen  = $('#ocbCalendarAutoGenCode').is(':checked');
            if(nStaChkAutoGen == true){
                $('#ofmAddCalendar').validate().destroy();
                $('#ofmAddCalendar').validate({
                    rules: {
                        oetObjCode: {
                            "required": {
                                depends: function(oElement) {
                                    if (ptRoute == "calendarEventAdd") {
                                        if ($('#ocbCalendarAutoGenCode').is(':checked')) {
                                            return false;
                                        } else {
                                            return true;
                                        }
                                    } else {
                                        return true;
                                    }
                                }
                            },
                            "dublicateCode": {}
                        },
                        oetCldName: { "required": {} },
                        oetCldBchName: { "required": {} },
                        ocmObjGroup: { "required": {} },
                    },
                    messages: {
                        oetObjCode: {
                            "required": $('#oetObjCode').attr('data-validate-required'),
                            "dublicateCode": $('#oetObjCode').attr('data-validate-dublicateCode')
                        },
                        oetObjName: {
                            "required": $('#oetObjName').attr('data-validate-required'),
                        }
                    },
                    errorElement: "em",
                    errorPlacement: function(error, element) {
                        error.addClass("help-block");
                        if (element.prop("type") === "checkbox") {
                            error.appendTo(element.parent("label"));
                        } else {
                            var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                            if (tCheck == 0) {
                                error.appendTo(element.closest('.form-group')).trigger('change');
                            }
                        }
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).closest('.form-group').addClass("has-error").removeClass("has-success");
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        var nStaCheckValid = $(element).parents('.form-group').find('.help-block').length
                        if (nStaCheckValid != 0) {
                            $(element).closest('.form-group').addClass("has-success").removeClass("has-error");
                        }
                    },
                    submitHandler: function(form) {
                        JCNxOpenLoading();
                        $.ajax({
                            type: "POST",
                            url: ptRoute,
                            data: $('#ofmAddCalendar').serialize(),
                            async: false,
                            cache: false,
                            timeout: 0,
                            success: function(tResult) {
                                if (nStaCldBrowseType != 1) {
                                    var aReturn = JSON.parse(tResult);
                                    if (aReturn['nStaEvent'] == 1) {
                                        if (aReturn['nStaCallBack'] == '1' || aReturn['nStaCallBack'] == null) {
                                            JSvCallPageCalendarEdit(aReturn['tCodeReturn']);
                                        } else if (aReturn['nStaCallBack'] == '2') {
                                            JSvCallPageCalendarAdd();
                                        } else if (aReturn['nStaCallBack'] == '3') {
                                            JSvCallPageCalendarList();
                                        }
                                    } else {
                                        alert(aReturn['tStaMessg']);
                                    }
                                } else {
                                    JCNxCloseLoading();
                                    JCNxBrowseData(tCallCldBackOption);
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                JCNxResponseError(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }
                });
            }else{
                JSxCheckCalendarCodeDupInDB();
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //Functionality: Event Check Calendar Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 20/05/2021 Off
    //Return: -
    //ReturnType:
    function JSxCheckCalendarCodeDupInDB(){
        let nStaChkAutoGen  = $('#ocbCalendarAutoGenCode').is(':checked');
        if(nStaChkAutoGen == false){
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName  : "TSVMPos",
                    tFieldName  : "FTSpsCode",
                    tCode       : $("#oetCldCode").val()
                },
                async : false,
                cache: false,
                timeout: 0,
                success: function(tResult){
                    let aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateCldCode").val(aResult["rtCode"]);
                    JSxCalendarSetValidEventBlur();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //Functionality: Set Validate Event Blur
    //Parameters: Validate Event Blur
    //Creator: 20/05/2021 Off
    //Return: -
    //ReturnType: -
    function JSxCalendarSetValidEventBlur(){
        $('#ofmAddCalendar').validate().destroy();
        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if($("#ohdCheckDuplicateCldCode").val() == 1){
                return false;
            }else{
                return true;
            }
        },'');
        // From Summit Validate
        $('#ofmAddCalendar').validate({
            rules: {
                oetCldCode : {
                    "required" :{
                        // ตรวจสอบเงื่อนไข validate
                        depends: function(oElement) {
                            if($('#ocbCalendarAutoGenCode').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }
                        }
                    },
                    "dublicateCode" :{}
                },
                oetCldName:     {"required" :{}}
            },
            messages: {
                oetCldCode : {
                    "required"      : $('#oetCldCode').attr('data-validate-required'),
                    "dublicateCode" : $('#oetCldCode').attr('data-validate-dublicateCode')
                },
                oetCldName : {
                    "required"      : $('#oetCldName').attr('data-validate-required'),
                }
            },
            errorElement: "em",
            errorPlacement: function (error, element ) {
                error.addClass( "help-block" );
                if ( element.prop( "type" ) === "checkbox" ) {
                    error.appendTo( element.parent( "label" ) );
                } else {
                    var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                    if(tCheck == 0){
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function ( element, errorClass, validClass ) {
                $( element ).closest('.form-group').addClass( "has-error" ).removeClass( "has-success" );
            },
            unhighlight: function(element, errorClass, validClass) {
                var nStaCheckValid  = $(element).parents('.form-group').find('.help-block').length
                if(nStaCheckValid != 0){
                    $(element).closest('.form-group').addClass( "has-success" ).removeClass( "has-error" );
                }
            },
            submitHandler: function(form){
                var tRoute  = $('#ohdCalendarRoute').val();
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: tRoute,
                    data: $('#ofmAddCalendar').serialize(),
                    async: false,
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        if (nStaCldBrowseType != 1) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                if (aReturn['nStaCallBack'] == '1' || aReturn['nStaCallBack'] == null) {
                                    JSvCallPageCalendarEdit(aReturn['tCodeReturn']);
                                } else if (aReturn['nStaCallBack'] == '2') {
                                    JSvCallPageCalendarAdd();
                                } else if (aReturn['nStaCallBack'] == '3') {
                                    JSvCallPageCalendarList();
                                }
                            } else {
                                alert(aReturn['tStaMessg']);
                            }
                        } else {
                            JCNxCloseLoading();
                            JCNxBrowseData(tCallCldBackOption);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        });
    }








    //BrowseAgn 
    $('#oimBrowseAgn').click(function(e){
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPdtBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode'  : 'oetCldAgnCode',
                'tReturnInputName'  : 'oetCldAgnName',
            });
            JCNxBrowseData('oPdtBrowseAgencyOption');
        }else{
            JCNxShowMsgSessionExpired(); 
        }
    });

    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit")?>;

    //Option Agn
    var oBrowseAgn = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;


        var oOptionReturn   = {
            Title : ['ticket/agency/agency', 'tAggTitle'],
            Table:{Master:'TCNMAgency', PK:'FTAgnCode'},
            Join :{
            Table: ['TCNMAgency_L'],
                On: ['TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = '+nLangEdits]
            },
            GrideView:{
                ColumnPathLang	: 'ticket/agency/agency',
                ColumnKeyLang	: ['tAggCode', 'tAggName'],
                ColumnsSize     : ['15%', '85%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat : ['', ''],
                Perpage			: 10,
                OrderBy			: ['TCNMAgency.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType  : 'S',
                Value		: [tInputReturnCode,"TCNMAgency.FTAgnCode"],
                Text		: [tInputReturnName,"TCNMAgency_L.FTAgnName"],
            },
            NextFunc:{
            FuncName: 'JSxAdjStkSubCallbackAfterSelectAgn',
            ArgReturn: ['FTAgnCode', 'FTAgnName']
            },
            RouteAddNew : 'agency',
            BrowseLev : 1,
        }
        return oOptionReturn;
    }

    function JSxAdjStkSubCallbackAfterSelectAgn(poJsonData) {
        if (poJsonData != "NULL") {
            $('#oetCldBchCode').val('');
            $('#oetCldBchName').val('');
        }
    }

    // สาขา 
    $('#oimBrowseBch').click(function(){
        var tUsrLevel = $('#ohdCldUsrLevel').val();
        var tBchMulti = "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var tSQLWhere = "";
        if(tUsrLevel != "HQ"){
            tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }else{  //สำนักงานใหญ่
            if($('#oetCldAgnCode').val() == '' || $('#oetCldAgnCode').val() == null){
                tSQLWhere += "";
            }else{
                tSQLWhere += " AND (TCNMBranch.FTAgnCode = " + $('#oetCldAgnCode').val() +" OR ISNULL(TCNMBranch.FTAgnCode,'') = '' )";
            }
        }
        // Option Branch
        oPmhBrowseBch = {
            Title: ['company/branch/branch', 'tBCHTitle'],
            Table: {Master:'TCNMBranch', PK:'FTBchCode'},
            Join: {
                Table: ['TCNMBranch_L'],
                On: ['TCNMBranch.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
            },
            Where: {
                Condition: [tSQLWhere]
            },
            GrideView:{
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [],
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC'],
                // SourceOrder: "DESC"
            },
            CallBack:{
                ReturnType  : 'S',
                Value       : ["oetCldBchCode", "TCNMBranch.FTBchCode"],
                Text        : ["oetCldBchName", "TCNMBranch_L.FTBchName"]
            },
            // NextFunc:{
            //     FuncName: 'JSxAdjStkSubCallbackAfterSelectBch',
            //     ArgReturn: ['FTBchCode', 'FTBchName']
            // },
            // RouteFrom: 'promotion',
            RouteAddNew: 'branch',
            BrowseLev: 2
        };
        // Option Branch
        JCNxBrowseData('oPmhBrowseBch');

    });


    //BrowseAvg 
    $('#oimBrowseAvg').click(function(e){
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPdtBrowseAvgOption = oBrowseAvg({
                'tReturnInputCode'  : 'oetCldAvgCode',
                'tReturnInputName'  : 'oetCldAvgName',
            });
            JCNxBrowseData('oPdtBrowseAvgOption');
        }else{
            JCNxShowMsgSessionExpired(); 
        }
    });

    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit")?>;

    //Option Avg
    var oBrowseAvg = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;

        var oOptionReturn   = {
            Title : ['service/calendar/calendar', 'tCLDApv'],
            Table: {
                Master: 'TCNMUser',
                PK: 'FTUsrCode'
            },
            Join: {
                Table: ['TCNMUser_L'],
                On: ['TCNMUser_L.FTUsrCode = TCNMUser.FTUsrCode AND TCNMUser_L.FNLngID = ' + nLangEdits, ]
            },
            GrideView: {
            ColumnPathLang: 'service/calendar/calendar',
            ColumnKeyLang: ['tCLDApvCode', 'tCLDApvName', 'tCLDRemark'],
            ColumnsSize: ['30%', '40%', '30%'],
            DataColumns: ['TCNMUser.FTUsrCode', 'TCNMUser_L.FTUsrName', 'TCNMUser_L.FTUsrRmk'],
            DataColumnsFormat: ['', '', ''],
            WidthModal: 50,
            Perpage: 10,
            OrderBy: ['TCNMUser.FDCreateOn DESC'],
        },
            CallBack:{
                ReturnType  : 'S',
                Value		: [tInputReturnCode,"TCNMUser.FTUsrCode"],
                Text		: [tInputReturnName,"TCNMUser_L.FTUsrName"],
            },
            RouteAddNew : 'agency',
            BrowseLev : 1,
        }
        return oOptionReturn;
    }


    var tStaUsrLevel    = '<?php  echo $this->session->userdata("tSesUsrLevel"); ?>';

    if(tStaUsrLevel == 'BCH' || tStaUsrLevel == 'SHP'){
        $('#oimBrowseAgn').attr("disabled", true);
    }

</script>