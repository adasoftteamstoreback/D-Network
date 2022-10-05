<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script type="text/javascript">
    var nLangEdits        = '<?php echo $this->session->userdata("tLangEdit");?>';
    var tUsrApvName       = '<?php echo $this->session->userdata("tSesUsername");?>';
    var tSesUsrLevel      = '<?php echo $this->session->userdata('tSesUsrLevel');?>';
    var tUserBchCode      = '<?php echo $this->session->userdata("tSesUsrBchCode");?>';
    var tUserBchName      = '<?php echo $this->session->userdata("tSesUsrBchName");?>';
    var tUserWahCode      = '<?php echo $this->session->userdata("tSesUsrWahCode");?>';
    // var tUserWahName      = '<?php //echo $this->session->userdata("tSesUsrWahName");?>';
    var tRoute                 = $('#ohdPRBRoute').val();
    var tPRBSesSessionID        = $("#ohdSesSessionID").val();


    $(document).ready(function(){
        var nCrTerm = $('#ocmPRBTypePayment').val();
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

        $("#obtPRBSubmitFromDoc").removeAttr("disabled");
        var dCurrentDate    = new Date();
        if($('#oetPRBDocDate').val() == ''){
            $('#oetPRBDocDate').datepicker("setDate",dCurrentDate);
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


        $('#obtPRBDocBrowsePdt').unbind().click(function(){
            // var nStaSession = JCNxFuncChkSessionExpired();
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                if($('#oetPRBFrmSplCode').val()!=""){
                JSxCheckPinMenuClose();
                JCNvPRBBrowsePdt();
                }else{
                    $('#odvPRBModalPleseselectSPL').modal('show');
                }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        if($('#oetPRBFrmBchCode').val() == ""){
            $("#obtPRBFrmBrowseTaxAdd").attr("disabled","disabled");
        }

        /** =================== Event Search Function ===================== */
            $('#oliPRBMngPdtScan').unbind().click(function(){
                var tPRBSplCode  = $('#oetPRBFrmSplCode').val();
                if(typeof(tPRBSplCode) !== undefined && tPRBSplCode !== ''){
                    //Hide
                    $('#oetPRBFrmFilterPdtHTML').hide();
                    $('#obtPRBMngPdtIconSearch').hide();

                    //Show
                    $('#oetPRBFrmSearchAndAddPdtHTML').show();
                    $('#obtPRBMngPdtIconScan').show();
                }else{
                    var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                }
            });
            $('#oliPRBMngPdtSearch').unbind().click(function(){
                //Hide
                $('#oetPRBFrmSearchAndAddPdtHTML').hide();
                $('#obtPRBMngPdtIconScan').hide();
                //Show
                $('#oetPRBFrmFilterPdtHTML').show();
                $('#obtPRBMngPdtIconSearch').show();
            });
        /** =============================================================== */

        /** ===================== Set Date Autometic Doc ========================  */
            var dCurrentDate    = new Date();
            var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
            var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes() + " " + tAmOrPm;

            if($('#oetPRBDocDate').val() == ''){
                $('#oetPRBDocDate').datepicker("setDate",dCurrentDate);
            }

            if($('#oetPRBDocTime').val() == ''){
                $('#oetPRBDocTime').val(tCurrentTime);
            }
        /** =============================================================== */

        /** =================== Event Date Function  ====================== */
            $('#obtPRBDocDate').unbind().click(function(){
                $('#oetPRBDocDate').datepicker('show');
            });

            $('#obtPRBDocTime').unbind().click(function(){
                $('#oetPRBDocTime').datetimepicker('show');
            });

            $('#obtPRBBrowseRefIntDocDate').unbind().click(function(){
                $('#oetPRBRefIntDocDate').datepicker('show');
            });

            $('#obtPRBRefDocExtDate').unbind().click(function(){
                $('#oetPRBRefDocExtDate').datepicker('show');
            });

            $('#obtPRBTransDate').unbind().click(function(){
                $('#oetPRBTransDate').datepicker('show');
            });
        /** =============================================================== */

        /** ================== Check Box Auto GenCode ===================== */
            $('#ocbPRBStaAutoGenCode').on('change', function (e) {
                if($('#ocbPRBStaAutoGenCode').is(':checked')){
                    $("#oetPRBDocNo").val('');
                    $("#oetPRBDocNo").attr("readonly", true);
                    $('#oetPRBDocNo').closest(".form-group").css("cursor","not-allowed");
                    $('#oetPRBDocNo').css("pointer-events","none");
                    $("#oetPRBDocNo").attr("onfocus", "this.blur()");
                    $('#ofmPRBFormAdd').removeClass('has-error');
                    $('#ofmPRBFormAdd .form-group').closest('.form-group').removeClass("has-error");
                    $('#ofmPRBFormAdd em').remove();
                }else{
                    $('#oetPRBDocNo').closest(".form-group").css("cursor","");
                    $('#oetPRBDocNo').css("pointer-events","");
                    $('#oetPRBDocNo').attr('readonly',false);
                    $("#oetPRBDocNo").removeAttr("onfocus");
                }
            });
        /** =============================================================== */
    });

    // ========================================== Brows Option Conditon ===========================================
        // ตัวแปร Option Browse Modal คลังสินค้า
        var oWahOption      = function(poDataFnc){
            var tPRBBchCode          = poDataFnc.tPRBBchCode;
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
                    Condition : [" AND (TCNMWaHouse.FTWahStaType IN (1,2,5) AND  TCNMWaHouse.FTBchCode='"+tPRBBchCode+"')"]
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

        var oWahNoStockOption      = function(poDataFnc){
            var tPRBBchCode          = poDataFnc.tPRBBchCode;
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tNextFuncName       = poDataFnc.tNextFuncName;


            var oOptionReturn   = {
                Title: ["company/warehouse/warehouse","tWAHTitle"],
                Table: { Master:"TCNMWaHouse", PK:"FTWahCode"},
                Join: {
                    Table: ["TCNMWaHouse_L"],
                    On: ["TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse.FTBchCode=TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = '"+nLangEdits+"'"]
                },
                Where: {
                    Condition : [" AND (TCNMWaHouse.FTWahStaType IN (1,2,5) AND  TCNMWaHouse.FTBchCode='"+tPRBBchCode+"')"]
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
                NextFunc:{
                    FuncName:tNextFuncName
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

        var oBranchOptionTo = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName    = poDataFnc.tNextFuncName;
            var tAgnCode            = poDataFnc.tAgnCode;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tBchCodeLock          = poDataFnc.tBchCodeLock;

            tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
            tSQLWhereBch = "";
            tSQLWhereAgn = "";

            // if(tUsrLevel != "HQ"){
            //     tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+")";
            // }

            if(tAgnCode != ""){
                tSQLWhereAgn = " AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
            }

            if(tBchCodeLock!=''){
                tSQLWhereBch += " AND TCNMBranch.FTBchCode <> '"+tBchCodeLock+"' ";
            }
            tSQLWhereBch += " AND ((TCNMBranch.FTBchType  = 1 AND TCNMBranch.FTBchStaHQ = 1  ) OR  TCNMBranch.FTBchType = 2)";
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
                    OrderBy             : ['TCNMBranch.FTBchCode'],
                    SourceOrder         : "ASC"
                },
                NextFunc:{
                    FuncName:tNextFuncName
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
        var oBranchOption = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName    = poDataFnc.tNextFuncName;
            var tAgnCode            = poDataFnc.tAgnCode;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tBchCodeLock          = poDataFnc.tBchCodeLock;

            tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
            tSQLWhereBch = "";
            tSQLWhereAgn = "";

            if(tUsrLevel != "HQ"){
                tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+")";
            }

            if(tAgnCode != ""){
                tSQLWhereAgn = " AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
            }

            if(tBchCodeLock!=''){
                tSQLWhereBch += " AND TCNMBranch.FTBchCode <> '"+tBchCodeLock+"' ";
            }
            //tSQLWhereBch += " AND ((TCNMBranch.FTBchType  = 1 AND TCNMBranch.FTBchStaHQ = 1  ) OR  TCNMBranch.FTBchType = 2)";
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
                    OrderBy             : ['TCNMBranch.FTBchCode'],
                    SourceOrder         : "ASC"
                },
                NextFunc:{
                    FuncName:tNextFuncName
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

        // ตัวแปร Option Browse Modal สาขา
        var oBranchOptionShip = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName    = poDataFnc.tNextFuncName;
            var tAgnCode            = poDataFnc.tAgnCode;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tBchCodeLock          = poDataFnc.tBchCodeLock;

            tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
            tSQLWhereBch = "";
            tSQLWhereAgn = "";

            if(tUsrLevel != "HQ"){
                tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
            }

            if(tAgnCode != ""){
                tSQLWhereAgn = " AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
            }

            if(tBchCodeLock!=''){
                tSQLWhereBch += " AND TCNMBranch.FTBchCode <> '"+tBchCodeLock+"' ";
            }
            //tSQLWhereBch += " AND ((TCNMBranch.FTBchType  = 1 AND TCNMBranch.FTBchStaHQ = 1  ) OR  TCNMBranch.FTBchType = 2)";
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
                    OrderBy             : ['TCNMBranch.FTBchCode'],
                    SourceOrder         : "ASC"
                },
                NextFunc:{
                    FuncName:tNextFuncName
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
            // $('#oetPRBFrmWahCodeTo').val('');
            // $('#oetPRBFrmWahNameTo').val('');
        }

        function JSxNextFuncDOBchTo() {
            $('#oetPRBFrmWahCodeTo').val('');
            $('#oetPRBFrmWahNameTo').val('');
        }

        function JSxNextFuncDOBchShip() {

        }

        //Option Agency
        var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
        var oBrowseAgn = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var tAgnCodeLock = poReturnInput.tAgnCodeLock;
        var tFuncName = poReturnInput.tFuncName;

        if( tAgnCodeLock != "" ){
            tWhereAgency = " AND (TCNMAgency.FTAgnCode != '"+tAgnCodeLock+"') ";
        }else{
            tWhereAgency = "";
        }
        //tWhereAgency += "AND ((TCNMAgency.FTBchType  = 1 AND TCNMAgency.FTBchStaHQ = 1  ) OR  TCNMAgency.FTBchType = 2)";
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
            Where:{
                Condition : [
                    // tWhereAgency
                ]
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
                    FuncName:tFuncName
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

    function JSxNextFuncPRBAgn() {
        $('#oetPRBFrmBchCode').val('');
        $('#oetPRBFrmBchName').val('');
        $('#oetPRBFrmWahCode').val('');
        $('#oetPRBFrmWahName').val('');
    }

    function JSxNextFuncPRBAgnTo() {
        $('#oetPRBFrmBchCodeTo').val('');
        $('#oetPRBFrmBchNameTo').val('');
        $('#oetPRBFrmWahCodeTo').val('');
        $('#oetPRBFrmWahNameTo').val('');
    }

    function JSxNextFuncPRBAgnShip() {
        $('#oetPRBFrmBchCodeShip').val('');
        $('#oetPRBFrmBchNameShip').val('');
        $('#oetPRBFrmWahCodeShip').val('');
        $('#oetPRBFrmWahNameShip').val('');
    }

    var tStaUsrLevel = '<?php echo $this->session->userdata("tSesUsrLevel"); ?>';



    // ========================================== Brows Event Conditon ===========================================
        // Event Browse Warehouse
        $('#obtPRBBrowseWahouseTo').unbind().click(function(){
            // var nStaSession = JCNxFuncChkSessionExpired();
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oDOBrowseWahOption   = undefined;
                oDOBrowseWahOption          = oWahOption({
                    'tPRBBchCode'        : $('#oetPRBFrmBchCodeTo').val(),
                    'tReturnInputCode'  : 'oetPRBFrmWahCodeTo',
                    'tReturnInputName'  : 'oetPRBFrmWahNameTo',
                    'aArgReturn'        : []
                });
                JCNxBrowseData('oDOBrowseWahOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse Warehouse
        $('#obtPRBBrowseWahouseShip').unbind().click(function(){
        // var nStaSession = JCNxFuncChkSessionExpired();
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oDOBrowseWahOption   = undefined;
                oDOBrowseWahOption          = oWahOption({
                    'tPRBBchCode'        : $('#oetPRBFrmBchCode').val(),
                    'tReturnInputCode'  : 'oetPRBFrmWahCodeShip',
                    'tReturnInputName'  : 'oetPRBFrmWahNameShip',
                    'aArgReturn'        : []
                });
                JCNxBrowseData('oDOBrowseWahOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtPRBBrowseBCH').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetPRBAgnCode').val();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oDOBrowseBranchOption  = undefined;
                oDOBrowseBranchOption         = oBranchOptionShip({
                    'tReturnInputCode'  : 'oetPRBFrmBchCode',
                    'tReturnInputName'  : 'oetPRBFrmBchName',
                    'tAgnCode'          : tAgnCode,
                    'tBchCodeLock'      : '',
                    'tNextFuncName'     : 'JSxNextFuncDOBch',
                    'aArgReturn'        : ['FTBchCode','FTBchName'],

                });
                JCNxBrowseData('oDOBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

        });

        $('#obtPRBBrowseBCHTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetPRBAgnCodeTo').val();
        var tBchCodeLock = $('#oetPRBFrmBchCodeShip').val();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oDOBrowseBranchOption  = undefined;
                oDOBrowseBranchOption         = oBranchOptionTo({
                    'tReturnInputCode'  : 'oetPRBFrmBchCodeTo',
                    'tReturnInputName'  : 'oetPRBFrmBchNameTo',
                    'tAgnCode'          : tAgnCode,
                    'tBchCodeLock'      : tBchCodeLock,
                    'tNextFuncName'     : 'JSxNextFuncDOBchTo',
                    'aArgReturn'        : ['FTBchCode','FTBchName'],
                });
                JCNxBrowseData('oDOBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

        });

        $('#obtPRBBrowseBCHShip').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetPRBAgnCodeShip').val();
        var tBchCodeLock = $('#oetPRBFrmBchCodeTo').val();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oDOBrowseBranchOption  = undefined;
                oDOBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'oetPRBFrmBchCodeShip',
                    'tReturnInputName'  : 'oetPRBFrmBchNameShip',
                    'tAgnCode'          : tAgnCode,
                    'tBchCodeLock'      : '',
                    'tNextFuncName'     : 'JSxNextFuncDOBchShip',
                    'aArgReturn'        : ['FTBchCode','FTBchName'],
                });
                JCNxBrowseData('oDOBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

        });

        //BrowseAgn
        $('#oimPRBBrowseAgn').click(function(e) {
            e.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSxCheckPinMenuClose();
                window.oPRBBrowseAgencyOption = oBrowseAgn({
                    'tReturnInputCode': 'oetPRBAgnCode',
                    'tReturnInputName': 'oetPRBAgnName',
                    'tAgnCodeLock' : '',
                    'tFuncName' : 'JSxNextFuncPRBAgn',
                    'aArgReturn'      : ['FTAgnCode','FTAgnName'],
                });
                JCNxBrowseData('oPRBBrowseAgencyOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        //BrowseAgn To
        $('#oimPRBBrowseAgnTo').click(function(e) {
            e.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSxCheckPinMenuClose();
                window.oPRBBrowseAgencyOption = oBrowseAgn({
                    'tReturnInputCode': 'oetPRBAgnCodeTo',
                    'tReturnInputName': 'oetPRBAgnNameTo',
                    'tAgnCodeLock' : $('#oetPRBAgnCodeShip').val(),
                    'tFuncName' : 'JSxNextFuncPRBAgnTo',
                    'aArgReturn'      : ['FTAgnCode','FTAgnName'],
                });
                JCNxBrowseData('oPRBBrowseAgencyOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });


        //BrowseAgn Ship
        $('#oimPRBBrowseAgnShip').click(function(e) {
            e.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSxCheckPinMenuClose();
                window.oPRBBrowseAgencyOption = oBrowseAgn({
                    'tReturnInputCode': 'oetPRBAgnCodeShip',
                    'tReturnInputName': 'oetPRBAgnNameShip',
                    'tAgnCodeLock' : $('#oetPRBAgnCodeTo').val(),
                    'tFuncName' : 'JSxNextFuncPRBAgnShip',
                    'aArgReturn'      : ['FTAgnCode','FTAgnName'],
                });
                JCNxBrowseData('oPRBBrowseAgencyOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });




// เหตุผล
$('#obtPRBBrowseReason').click(function(){
    // $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd, #odvModalDOCPDT, #odvModalWanning)").remove();
    // Option WareHouse
    oAdjStkSubBrowseReason = {
            Title: ['other/reason/reason', 'tRSNTitle'],
            Table: { Master:'TCNMRsn', PK:'FTRsnCode' },
            Join: {
                Table: ['TCNMRsn_L'],
                On: ['TCNMRsn.FTRsnCode = TCNMRsn_L.FTRsnCode AND TCNMRsn_L.FNLngID = '+nLangEdits]
            },
            Where: {
                Condition : ["AND TCNMRsn.FTRsgCode = '016' "] // Type โอน
            },
            GrideView:{
                ColumnPathLang: 'other/reason/reason',
                ColumnKeyLang: ['tRSNTBCode', 'tRSNTBName'],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMRsn.FTRsnCode', 'TCNMRsn_L.FTRsnName'],
                DisabledColumns: [],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMRsn.FDCreateOn'],
                SourceOrder: "DESC"
            },
            CallBack:{
                ReturnType: 'S',
                Value: ["oetPRBReasonCode", "TCNMRsn.FTRsnCode"],
                Text: ["oetPRBReasonName", "TCNMRsn_L.FTRsnName"]
            },
            /*NextFunc:{
                FuncName:'JSxCSTAddSetAreaCode',
                ArgReturn:['FTRsnCode']
            },*/
            // RouteFrom : 'cardShiftChange',
            RouteAddNew : 'reason',
            BrowseLev : 0
    };
    // Option WareHouse
    JCNxBrowseData('oAdjStkSubBrowseReason');
});


        $('#obtPRBNoStockDT').on('click',function(){
            JSxCallNoStock();
        });

        //Browse เอกสารอ้างอิงภายใน
        function JSxCallNoStock(){
            var tBCHCode = $('#oetPRBFrmBchCode').val()
            var tBCHName = $('#oetPRBFrmBchName').val()

            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docPRBCallRefIntDoc",
                data: {
                    'tBCHCode'      : tBCHCode,
                    'tBCHName'      : tBCHName,
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    JCNxCloseLoading();
                    $('#odvPRBFromRefIntDoc').html(oResult);
                    $('#odvPRBModalRefIntDoc').modal({backdrop : 'static' , show : true});
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }

        $('#obtConfirmRefDocInt').click(function(){
            var tRefIntDocNo =  $('.xPurchaseInvoiceRefInt.active').data('docno');
            var tRefIntDocDate =  $('.xPurchaseInvoiceRefInt.active').data('docdate');
            var tRefIntBchCode =  $('.xPurchaseInvoiceRefInt.active').data('bchcode');
            var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm){
                    return $(this).val();
                }).get();

            var tSplStaVATInOrEx =  $('.xPurchaseInvoiceRefInt.active').data('vatinroex');
            var cSplCrLimit =  $('.xPurchaseInvoiceRefInt.active').data('crtrem');
            var nSplCrTerm =  $('.xPurchaseInvoiceRefInt.active').data('crlimit');
            var tSplCode =  $('.xPurchaseInvoiceRefInt.active').data('splcode');
            var tSplName =  $('.xPurchaseInvoiceRefInt.active').data('splname');

            var poParams = {
                    FCSplCrLimit        : cSplCrLimit,
                    FTSplCode           : tSplCode,
                    FTSplName           : tSplName,
                    FTSplStaVATInOrEx   : tSplStaVATInOrEx,
                    FTRefIntDocNo       : tRefIntDocNo,
                    FTRefIntDocDate     : tRefIntDocDate,
                };
            JSxPRBSetPanelSupplierData(poParams);

            $('#oetPRBRefIntDoc').val(tRefIntDocNo);
            JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docPRBCallRefIntDocInsertDTToTemp",
                    data: {
                        'tPRBDocNo'          : $('#oetPRBDocNo').val(),
                        'tPRBFrmBchCode'     : $('#oetPRBFrmBchCode').val(),
                        'tRefIntDocNo'      : tRefIntDocNo,
                        'tRefIntBchCode'    : tRefIntBchCode,
                        'aSeqNo'            : aSeqNo
                    },
                    cache: false,
                    Timeout: 0,
                    success: function (oResult){
                        JSvPRBLoadPdtDataTableHtml();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });

        });

        // Function : ฟังก์ชั่นเซทข้อมูล ผู้จำหน่าย
        function JSxPRBSetPanelSupplierData(poParams){
            // Reset Panel เป็นค่าเริ่มต้น
            $("#ohdPRBFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
            $("#ocmPRBTypePayment.selectpicker").val("2").selectpicker("refresh");
            $("#oetPRBRefDocIntName").val(poParams.FTRefIntDocNo);
            $("#oetPRBRefIntDocDate").val(poParams.FTRefIntDocDate).datepicker("refresh");

            // ประเภทภาษี
            if(poParams.FTSplStaVATInOrEx === "1"){
                // รวมใน
                $("#ohdPRBFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
            }else{
                // แยกนอก
                $("#ohdPRBFrmSplInfoVatInOrEx.selectpicker").val("2").selectpicker("refresh");
            }

            // ประเภทชำระเงิน
            if(poParams.FCSplCrLimit > 0){
                // เงินเชื่อ
                $("#ocmPRBTypePayment.selectpicker").val("2").selectpicker("refresh");
            }else{
                // เงินสด
                $("#ocmPRBTypePayment.selectpicker").val("1").selectpicker("refresh");
            }

            //ผู้ขาย
            $("#oetPRBFrmSplCode").val(poParams.FTSplCode);
            $("#oetPRBFrmSplName").val(poParams.FTSplName);
            $("#oetPRBSplName").val(poParams.FTSplName);
            $("#oetPRBFrmSplInfoCrTerm").val(poParams.FCSplCrLimit);
        }

//------------------------------------------------------------------------------------------------//

    // Validate From Add Or Update Document
    function JSxPRBValidateFormDocument(){
        if($("#ohdPRBCheckClearValidate").val() != 0){
            $('#ofmPRBFormAdd').validate().destroy();
        }

        $('#ofmPRBFormAdd').validate({
            focusInvalid: true,
            rules: {
                oetPRBDocNo : {
                    "required" : {
                        depends: function (oElement) {
                            if($("#ohdPRBRoute").val()  ==  "docPRBEventAdd"){
                                if($('#ocbPRBStaAutoGenCode').is(':checked')){
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
                oetPRBFrmBchName    : {"required" : true},
                oetPRBFrmBchNameTo : {"required" : true},
                oetPRBFrmBchNameShip : {"required" : true},
                oetPRBFrmWahNameTo : {"required" : true},
                oetPRBFrmWahNameShip : {"required" : true},

            },
            messages: {
                oetPRBDocNo      : {"required" : $('#oetPRBDocNo').attr('data-validate-required')},
                oetPRBFrmBchName : {"required" : $('#oetPRBFrmBchName').attr('data-validate-required')},
                oetPRBFrmBchNameShip : {"required" : $('#oetPRBFrmBchNameShip').attr('data-validate-required')},
                oetPRBFrmWahNameTo : {"required" : $('#oetPRBFrmBchNameShip').attr('data-validate-required')},
                oetPRBFrmWahNameShip : {"required" : $('#oetPRBFrmBchNameShip').attr('data-validate-required')},
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
                if(!$('#ocbPRBStaAutoGenCode').is(':checked')){
                    JSxPRBValidateDocCodeDublicate();
                }else{
                    if($("#ohdPRBCheckSubmitByButton").val() == 1){
                        JSxPRBSubmitEventByButton();
                    }
                }
            },
        });
    }

    // Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
    function JSxPRBValidateDocCodeDublicate(){
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName'    : 'TCNTPdtReqBchHD',
                'tFieldName'    : 'FTXthDocNo',
                'tCode'         : $('#oetPRBDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                $("#ohdPRBCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdPRBCheckClearValidate").val() != 1) {
                    $('#ofmPRBFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdPRBRoute").val() == "docPRBEventAdd"){
                        if($('#ocbPRBStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdPRBCheckDuplicateCode").val() == 1) {
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
                $('#ofmPRBFormAdd').validate({
                    focusInvalid: false,
                    onclick: false,
                    onfocusout: false,
                    onkeyup: false,
                    rules: {
                        oetPRBDocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetPRBDocNo : {"dublicateCode"  : $('#oetPRBDocNo').attr('data-validate-duplicate')}
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
                        if($("#ohdPRBCheckSubmitByButton").val() == 1) {
                            JSxPRBSubmitEventByButton();
                        }
                    }
                })

                if($("#ohdPRBCheckClearValidate").val() != 1) {
                    $("#ofmPRBFormAdd").submit();
                    $("#ohdPRBCheckClearValidate").val(1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function: Validate Success And Send Ajax Add/Update Document
    function JSxPRBSubmitEventByButton(ptType = ''){
        var tPRBDocNo = '';

        if($("#ohdPRBRoute").val() !=  "docPRBEventAdd"){
            var tPRBDocNo    = $('#oetPRBDocNo').val();
        }
        $("#obtPRBSubmitFromDoc").attr('disabled','true');
        var tPRBAgnCode = $("#oetPRBAgnCode").val();
        var tPRBFrmBchCode = $("#oetPRBFrmBchCode").val();

        $.ajax({
            type: "POST",
            url: "docPRBChkHavePdtForDocDTTemp",
            data: {
                'ptBchCode'          : tPRBFrmBchCode,
                'ptAgnCode'          : tPRBAgnCode,
                'ptPRBDocNo'         : tPRBDocNo,
                'tPRBSesSessionID'   : $('#ohdSesSessionID').val(),
                'tPRBUsrCode'        : $('#ohdPRBUsrCode').val(),
                'tPRBLangEdit'       : $('#ohdPRBLangEdit').val(),
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
                        url     : $("#ohdPRBRoute").val(),
                        data    : $("#ofmPRBFormAdd").serialize(),
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            // JCNxCloseLoading();
                            var aDataReturnEvent    = JSON.parse(oResult);
                            if(aDataReturnEvent['nStaReturn'] == '1'){
                                var nDOStaCallBack      = aDataReturnEvent['nStaCallBack'];
                                var nDODocNoCallBack    = aDataReturnEvent['tCodeReturn'];

                                var oDOCallDataTableFile = {
                                    ptElementID : 'odvPRBShowDataTable',
                                    ptBchCode   : $('#oetPRBFrmBchCode').val(),
                                    ptDocNo     : nDODocNoCallBack,
                                    ptDocKey    :'TCNTPdtReqBchHD',
                                }
                                JCNxUPFInsertDataFile(oDOCallDataTableFile);
                                if(ptType == 'approve'){
                                    var tPRBDocNo = $('#oetPRBDocNo').val();
                                    var tAgnCode = $('#oetPRBAgnCode').val();
                                    var tBchCode = $('#oetPRBFrmBchCode').val();
                                    var tRefInPRBcNo = $('#oetPRBRefDocIntName').val();

                                    $.ajax({
                                        type: "POST",
                                        url: "docPRBApproveDocument",
                                        data: {
                                            tPRBDocNo: tPRBDocNo,
                                            tAgnCode : tAgnCode,
                                            tBchCode: tBchCode,
                                            tRefInPRBcNo: tRefInPRBcNo
                                        },
                                        cache: false,
                                        timeout: 0,
                                        success: function(tResult) {
                                            $("#odvPRBModalAppoveDoc").modal("hide");
                                            $('.modal-backdrop').remove();
                                            var aReturnData = JSON.parse(tResult);
                                            if (aReturnData.nStaEvent == "1") {
                                                //  FSvCMNSetMsgSucessDialog(aReturnData.tStaMessg);
                                                JSvPRBCallPageEdit(tBchCode,tAgnCode,tPRBDocNo);
                                                    }else{
                                                FSvCMNSetMsgWarningDialog(aReturnData.tStaMessg);
                                                }
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                                        }
                                    });
                                }else{
                                    switch(nDOStaCallBack){
                                        case '1' :
                                            JSvPRBCallPageEdit(tPRBFrmBchCode,tPRBAgnCode,nDODocNoCallBack);
                                        break;
                                        case '2' :
                                            JSvPRBCallPageAddDoc();
                                        break;
                                        case '3' :
                                            JSvPRBCallPageList();
                                        break;
                                        default :
                                            JSvPRBCallPageEdit(tPRBFrmBchCode,tPRBAgnCode,nDODocNoCallBack);
                                    }
                                }
                                $("#obtPRBSubmitFromDoc").removeAttr("disabled");
                            }else{
                                var tMessageError = aDataReturnEvent['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                                $("#obtPRBSubmitFromDoc").removeAttr("disabled");
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
    function JSxPRBCountPdtItems(){
        var nPdtItems = $('.xWPdtItem').length;
        $('.xShowQtyFooter').text(accounting.formatNumber(nPdtItems, 0, ','));
    }

    $('#ocmPRBTypePayment').on('change', function() {
        if (this.value == 1) {
            $('.xCNPanel_CreditTerm').hide();
        } else {
            $('.xCNPanel_CreditTerm').show();
        }
    });

    //พิมพ์เอกสาร
    function JSxPRBPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tPRBBchCode); ?>'},
            {"DocCode"      : '<?=@$tPRBDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tPRBBchCode;?>'}
        ];
        // var tGrandText = $('#odvTQDataTextBath').text();
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillRqFBch?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }



</script>
