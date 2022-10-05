<script type="text/javascript">
    $('.selection-2').selectpicker();

    $('#ocmRcnGroup').change(function() {
        $('#ocmRcnGroup-error').hide();
    });

    if ($('#ohdCheckStatus').val() != 1) {
        $("#ocbCalendarStatus").prop('checked', false);;
    }

    $(document).ready(function() {
        $('.selectpicker').selectpicker();
        $('.xCNDatePicker2').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            orientation: "bottom"
        });

        if (JSbCalendarIsCreatePage()) {
            $("#oetCarCode").attr("disabled", true);
            $('#ocbCalendarAutoGenCode').change(function() {
                if ($('#ocbCalendarAutoGenCode').is(':checked')) {
                    $('#oetCarCode').val('');
                    $("#oetCarCode").attr("disabled", true);
                    $('#odvCalendarCodeForm').removeClass('has-error');
                    $('#odvCalendarCodeForm em').remove();
                } else {
                    $("#oetCarCode").attr("disabled", false);
                }
            });
            JSxCalendarVisibleComponent('#odvCalendarAutoGenCode', true);
        }

        if (JSbCalendarIsUpdatePage()) {
            $("#oetCarCode").attr("readonly", true);
            $('#odvCalendarAutoGenCode input').attr('disabled', true);
            JSxCalendarVisibleComponent('#odvCalendarAutoGenCode', false);
        }

        $('#oetCarCode').blur(function() {
            JSxCheckCarCodeDupInDB();
        });

        // Doc Date From
        $('#obtCarHistoryAdvSearcDocDate').unbind().click(function(){
            $('#oetCarHistoryAdvSearcDocDate').datepicker('show');
        });

        // Doc Date To
        $('#obtCarHistoryAdvSearcJointDate').unbind().click(function(){
            $('#oetCarHistoryAdvSearcJointDate').datepicker('show');
        });

        // Event Tab
        $('#odvCarPanelBody .xCNCARTab').unbind().click(function() {
            let tPosRoute = '<?php echo @$tRoute; ?>';
            if (tPosRoute == 'carEventAdd') {
                return;
            } else {
                let tTypeTab = $(this).data('typetab');
                if (typeof(tTypeTab) !== undefined && tTypeTab == 'main') {
                    JCNxOpenLoading();
                    setTimeout(function() {
                        $('#odvPosMainMenu #odvBtnAddEdit').show();
                        JCNxCloseLoading();
                        return;
                    }, 500);
                } else if (typeof(tTypeTab) !== undefined && tTypeTab == 'sub') {
                    $('#odvPosMainMenu #odvBtnAddEdit').hide();
                    let tTabTitle = $(this).data('tabtitle');
                    switch (tTabTitle) {
                        case 'posinfouser':
                            JCNxOpenLoading();
                            setTimeout(function() {
                                JCNxCloseLoading();
                                return;
                            }, 500);
                            break;
                    }
                }
            }
        });

        $('#obtCarStartDate').click(function(event) {
            $('#oetCarStart').datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true,
                enableOnReadonly: false,
                startDate: '1900-01-01',
                disableTouchKeyboard: true,
                autoclose: true,
            });
            $('#oetCarStart').datepicker('show');
            event.preventDefault();
        });

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        $('#obtCarStartDate').click(function(event) {
            $('#oetCarStart').datepicker('show');
            event.preventDefault();
        });

        $('#obtCarFinishDate').click(function(event) {
            $('#oetCarFinish').datepicker('show');
            event.preventDefault();
        });

        $('#oliInforUserTap').click(function(event) {
          if($('#odvAllCarHistoryContentPage').children().length <= 0){
                $.ajax({
                    type: "POST",
                    url: "carOrderHistoryDataTable",
                    data: {
                        tSearchAll: "",
                        nPageCurrent: 1,
                        nPosCode: $("#oetCarCode").val(),
                        tCarRegNo: $("#oetCarNoreq").val()
                    },
                    cache: false,
                    Timeout: 0,
                    success: function(tResult){
                        $('#odvAllCarHistoryContentPage').html(tResult);
                        $('#obtSearchCarOrderHistory').click(function(){
                            var tCarCode = $('#oetCarCode').val();
                            JCNxOpenLoading();
                            JSvCarOrderHistoryDataTable(1,tCarCode);
                        });
                        $('#oetSearchOrderCarHistory').keypress(function(event){
                            if(event.keyCode == 13){
                                var tCarCode = $('#oetCarCode').val();
                                JCNxOpenLoading();
                                JSvCarOrderHistoryDataTable(1,tCarCode);
                            }
                        });
                        JSxCarHistoryNavDefult();
                        JCNxLayoutControll();
                        JCNxCloseLoading();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
                JCNxLayoutControll();
                JCNxCloseLoading();
            }
        });

        $('#oliInforUserTap2').click(function(event) {
            if($('#odvCarHistoryContentPage').children().length <= 0){
            $.ajax({
                    type: "POST",
                    url: "carHistoryDataTable",
                    data: {
                        tSearchAll: "",
                        nPageCurrent: 1,
                        nPosCode: $("#oetCarCode").val(),
                        tCarRegNo: $("#oetCarNoreq").val()
                    },
                    cache: false,
                    Timeout: 0,
                    success: function(tResult){
                        $('#odvCarHistoryContentPage').html(tResult);
                        $('#obtSearchCarHistory').click(function(){
                            var tCarCode = $('#oetCarCode').val();
                            JCNxOpenLoading();
                            JSvCarHistoryDataTable(1,tCarCode);
                        });
                        $('#oetSearchCarHistory').keypress(function(event){
                            if(event.keyCode == 13){
                                var tCarCode = $('#oetCarCode').val();
                                JCNxOpenLoading();
                                JSvCarHistoryDataTable(1,tCarCode);
                            }
                        });
                        JSxCarHistoryNavDefult();
                        JCNxLayoutControll();
                        JCNxCloseLoading();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });

                JCNxLayoutControll();
                JCNxCloseLoading();
            }
        });

        //ประวัติตามบิลขาย กดค้นหา
        $('#obtSearchCarSaleHistory').click(function(){
            JCNxOpenLoading();
            JSxLoadCarHistoryDataTable();
        });

        //ประวัติตามบิลขาย กดที่แท็บ
        $('#oliInforUserTap3').click(function(event) {
            if($('#odvCarSaleHistoryContentPage').children().length <= 0){
                JSxLoadCarHistoryDataTable();
                JCNxLayoutControll();
                JCNxCloseLoading();
            }
        });

        //ประวัติตามบิลขาย โหลดตาราง
        function JSxLoadCarHistoryDataTable(){
            $.ajax({
                type    : "POST",
                url     : "carSaleHistoryDataTable",
                data    : {
                    nCarCode    : $("#oetCarCode").val(),
                    tSearchAll  : $("#oetSearchCarSaleHistory").val(),
                },
                cache   : false,
                Timeout : 0,
                success : function(tResult){
                    $('#odvCarSaleHistoryContentPage').html(tResult);
                    JSxCarHistoryNavDefult();
                    JCNxLayoutControll();
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    });

    //Functionality: Event Check Calendar Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 20/05/2021 Off
    //Return: -
    //ReturnType: -
    function JSxCheckCarCodeDupInDB() {
        if (!$('#ocbCalendarAutoGenCode').is(':checked')) {
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: {
                    tTableName: "TSVMCar",
                    tFieldName: "FTCarCode",
                    tCode: $("#oetCarCode").val()
                },
                async: false,
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateCarCode").val(aResult["rtCode"]);
                    JSxCalendarSetValidEventBlur();
                    $('#ofmAddCalendar').submit();
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
    function JSxCalendarSetValidEventBlur() {
        $('#ofmAddCalendar').validate().destroy();

        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if ($("#ohdCheckDuplicateCarCode").val() == 1) {
                return false;
            } else {
                return true;
            }
        }, '');

        // From Summit Validate
        $('#ofmAddCalendar').validate({
            rules: {
                oetCarCode: {
                    "required": {
                        // ตรวจสอบเงื่อนไข validate
                        depends: function(oElement) {
                            if ($('#ocbCalendarAutoGenCode').is(':checked')) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    },
                    "dublicateCode": {}
                },
                oetCldName: {
                    "required": {}
                }
            },
            messages: {
                oetCarCode: {
                    "required": $('#oetCarCode').attr('data-validate-required'),
                    "dublicateCode": $('#oetCarCode').attr('data-validate-dublicateCode')
                },
                oetCldName: {
                    "required": $('#oetCldName').attr('data-validate-required'),
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
            submitHandler: function(form) {}
        });
    }

    //--------------------------------------------------------------------- เลือกค่าต่างๆ

    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit") ?>';
    var tStaUsrLevel    = '<?php echo $this->session->userdata("tSesUsrLevel"); ?>';

    //เลือกผู้ครอบครอง
    $('#oimBrowseUser').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowseUserWindow = undefined;
            window.oBrowseUserWindow = oBrowseUser({
                'tReturnInputCode': 'oetCarUserCode',
                'tReturnInputName': 'oetCarUserName',
            });
            JCNxBrowseData('oBrowseUserWindow');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //เลือกอ้างอิงสาขา
    $('#oimBrowseRefBCH').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowseRefBCHOption = undefined;
            window.oBrowseRefBCHOption = oBrowseRefBCH({
                'tReturnInputCode': 'oetCarRefBCHCode',
                'tReturnInputName': 'oetCarRefBCHName',
            });
            JCNxBrowseData('oBrowseRefBCHOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //เลือกจังหวัด
    $('#oimBrowseProvince').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBrowseProvinceWindow = undefined;
            oBrowseProvinceWindow = oBrowseProvince({
                'tReturnInputCode': 'oetCarProvinceCode',
                'tReturnInputName': 'oetCarProvinceName',
            });
            JCNxBrowseData('oBrowseProvinceWindow');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //อ้างอิงสาขา
    var oBrowseRefBCH = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tWhereModal         = "";
        var tUsrLevel           = "<?php echo $this->session->userdata("tSesUsrLevel");?>";
        var tBchMulti 	        = "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var tWhereOwnerCar      = "";
        if($('#oetCarUserCode').val() == '' || $('#oetCarUserCode').val() == null){
            var tWhereOwnerCar = '';
        }else{
            var tWhereOwnerCar = $('#oetCarUserCode').val();
        }
        tWhereModal 	+= " AND TCNMCstBch.FTCstCode IN ('"+tWhereOwnerCar+"') AND TCNMCstBch.FTCbrStatus = '1' ";
        var oOptionReturn       = {
            Title : ['company/branch/branch','tBCHTitle'],
            Table : {Master:'TCNMCstBch',PK:'FTCbrBchCode'},
            Where : {
                Condition : [tWhereModal]
            },
            GrideView:{
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['tBCHCode','tBCHName'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMCstBch.FTCbrBchCode','TCNMCstBch.FTCbrBchName','TCNMCstBch.FTCstCode'],
                DataColumnsFormat   : ['',''],
                DisabledColumns     : [2],
                Perpage             : 10,
                OrderBy             : ['TCNMCstBch.FTCbrBchCode ASC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMCstBch.FTCbrBchCode"],
                Text		: [tInputReturnName,"TCNMCstBch.FTCbrBchName"],
            },
            BrowseLev: 1,
        }
        return oOptionReturn;
    };

    //จังหวัด
    var oBrowseProvince = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var oOptionReturn = {
            Title: ['service/car/car', 'tCARProvince'],
            Table: {
                Master: 'TCNMProvince',
                PK: 'FTPvnCode'
            },
            Join: {
                Table: ['TCNMProvince_L'],
                On: ['TCNMProvince_L.FTPvnCode = TCNMProvince.FTPvnCode AND TCNMProvince_L.FNLngID = ' + nLangEdits, ]
            },
            GrideView: {
                ColumnPathLang: 'service/car/car',
                ColumnKeyLang: ['tCARUsrNo', 'tCARProvinceName'],
                ColumnsSize: ['30%', '40%'],
                DataColumns: ['TCNMProvince.FTPvnCode', 'TCNMProvince_L.FTPvnName'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [0],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMProvince.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMProvince.FTPvnCode"],
                Text: [tInputReturnName, "TCNMProvince_L.FTPvnName"],
            },
            RouteAddNew: 'Province',
            BrowseLev: 1,
        }
        return oOptionReturn;
    }

    //ผู้ครอบครอง
    var oBrowseUser = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;

        var oOptionReturn = {
            Title: ['service/car/car', 'tCAROwner'],
            Table: {
                Master: 'TCNMCst',
                PK: 'FTCstCode'
            },
            Join: {
                Table   : ['TCNMCst_L','TCNMCstBch'],
                On      : [ 'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = ' + nLangEdits, 
                            'TCNMCstBch.FTCstCode = TCNMCst_L.FTCstCode']
                        },
            Where: {
                Condition: [ "AND (TCNMCstBch.FNCbrSeq <= 1 OR ISNULL(TCNMCstBch.FNCbrSeq,'') = '') "]
            },
            GrideView: {
                ColumnPathLang  : 'service/car/car',
                ColumnKeyLang   : ['tCARUsrNo', 'tCARUsrName'],
                ColumnsSize     : ['15%', '80%',''],
                DataColumns     : ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName','TCNMCstBch.FNCbrSeq'],
                DataColumnsFormat: ['', '',''],
                DisabledColumns : [2],
                WidthModal      : 50,
                Perpage         : 10,
                OrderBy         : ['TCNMCst.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMCst.FTCstCode"],
                Text        : [tInputReturnName, "TCNMCst_L.FTCstName"],
            } ,
            NextFunc:{
                FuncName    : 'JSxNextFuncBrowseUser',
                ArgReturn   : ['FTCstCode', 'FTCstName','FNCbrSeq']
            },
            BrowseLev: 1,
        }
        return oOptionReturn;
    }

    //หลังจากเลือกผู้ครอบครอง
    function JSxNextFuncBrowseUser(ptDataNextFunc){
        if(typeof(ptDataNextFunc) != undefined && ptDataNextFunc != "NULL"){
            $('#oetCarRefBCHCode , #oetCarRefBCHName').val('');
        }

        var oResult = JSON.parse(ptDataNextFunc);
        if(oResult[2] == '' || oResult[2] == null || oResult[2] == undefined){
            $('#ohdSeqNoInBCH').val(0); //เป็นผู้ครอบครองแบบไม่มีสาขา
            $('#oetCarRefBCHCode').val('');
            $('#oetCarRefBCHName').val('');
            $('.xCNCarRefBCH').addClass('xCNHide');
        }else{
            $('#ohdSeqNoInBCH').val(1); //เป็นผู้ครอบครองแบบมีสาขา
            $('#oetCarRefBCHCode').val('');
            $('#oetCarRefBCHName').val('');
            $('.xCNCarRefBCH').removeClass('xCNHide');
        }
    }

    //BrowseCarType
    $('.xWBrowseCarType').click(function(e) {
        var tOptionCar = $(this).attr("option");
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
            var oBrowseCarType = function(poReturnInput) {
                var tInputReturnCode = poReturnInput.tReturnInputCode;
                var tInputReturnName = poReturnInput.tReturnInputName;
                var tSQLWhere = "AND TSVMCarInfo.FTCaiType = " + tOptionCar;
                var tCarBrand = $("#oetCarOptionID2").val();
                var tCarModel = $("#oetCarOptionID3").val();
                var tSQLWhereParent = "";
                if(tOptionCar == '3'){
                    //var tSQLWhereParent = "";
                    if ($("#oetCarOptionID2").val()=='') {
                      var tSQLWhereParent = "";
                    }else {
                      var tSQLWhereParent = "AND TSVMCarInfo.FTCarParent = " + tCarBrand;
                    }
                }else{
                    var tSQLWhereParent = "";
                }

                var oOptionReturn = {
                    Title: ['service/car/car', 'tCAROption'+tOptionCar],
                    Table: {
                        Master: 'TSVMCarInfo',
                        PK: 'FTCaiCode'
                    },
                    Join: {
                        Table: ['TSVMCarInfo_L'],
                        On: ['TSVMCarInfo_L.FTCaiCode = TSVMCarInfo.FTCaiCode AND TSVMCarInfo_L.FNLngID = ' + nLangEdits]
                    },
                    Where: {
                        Condition: [tSQLWhere,tSQLWhereParent]
                    },
                    GrideView: {
                        ColumnPathLang: 'product/carinfo/carinfo',
                        ColumnKeyLang: ['tCAICode' + tOptionCar, 'tCAIName' + tOptionCar],
                        ColumnsSize: ['15%', '85%'],
                        WidthModal: 50,
                        DataColumns: ['TSVMCarInfo.FTCaiCode', 'TSVMCarInfo_L.FTCaiName'],
                        DataColumnsFormat: ['', ''],
                        Perpage: 10,
                        OrderBy: ['TSVMCarInfo.FDCreateOn DESC'],
                    },
                    CallBack: {
                        ReturnType: 'S',
                        Value: [tInputReturnCode, "TSVMCarInfo.FTCaiCode"],
                        Text: [tInputReturnName, "TSVMCarInfo_L.FTCaiName"],
                    },
                    RouteAddNew: 'agency',
                    BrowseLev: 1,
                }
                return oOptionReturn;
            }
            window.oPdtBrowseCarTypeOption = oBrowseCarType({
                'tReturnInputCode': 'oetCarOptionID' + tOptionCar,
                'tReturnInputName': 'oetCarOptionName' + tOptionCar,
            });
            JCNxBrowseData('oPdtBrowseCarTypeOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //--------------------------------------------------------------------- ค้นหาขั้นสูง

    // Advance search Display control
    $('#obtCarHistoryAdvanceSearch').unbind().click(function(){
        if($('#odvCarHistoryAdvanceSearchContainer').hasClass('hidden')){
            $('#odvCarHistoryAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvCarHistoryAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    // หลังจากกดค้นหาขั้นสูง
    $("#obtCarHistoryAdvSearchSubmitForm").unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvCarHistoryDataTable(1,$("#oetCarCode").val());
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // เลือกสาขา
    $('#obtCarHistoryAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPOBrowseBranchFromOption  = oPOBrowseBranch({
                'tReturnInputCode'  : 'oetCarHistoryAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetCarHistoryAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oPOBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtCarHistoryAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPOBrowseBranchToOption  = oPOBrowseBranch({
                'tReturnInputCode'  : 'oetCarHistoryAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetCarHistoryAdvSearchBchNameTo'
            });
            JCNxBrowseData('oPOBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Reset Advance Searsc
    $('#obtCarHistorySearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCarHistoryClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ฟังก์ชั่นล้างค่า Input Advance Search
    function JSxCarHistoryClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmCarHistoryFromSerchAdv').find('input').val('');
            $('#ofmCarHistoryFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvCarHistoryDataTable(1,$("#oetCarCode").val());
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // เลือกสาขา
    var oPOBrowseBranch = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tWhereModal = "";
        var tUsrLevel   = "<?php echo $this->session->userdata("tSesUsrLevel");?>";
        var tBchMulti 	= "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        if(tUsrLevel != "HQ"){
            tWhereModal 	+= " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }
        var oOptionReturn       = {
            Title : ['company/branch/branch','tBCHTitle'],
            Table : {Master:'TCNMBranch',PK:'FTBchCode'},
            Join :{
                Table : ['TCNMBranch_L'],
                On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
            },
            Where : {
                Condition : [tWhereModal]
            },
            GrideView:{
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['tBCHCode','tBCHName'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['',''],
                Perpage             : 10,
                OrderBy             : ['TCNMBranch_L.FTBchName ASC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"],
            },
            BrowseLev: 1,
        }
        return oOptionReturn;
    };
</script>
