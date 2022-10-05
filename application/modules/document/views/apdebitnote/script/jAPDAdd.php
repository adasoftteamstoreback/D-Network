<script type="text/javascript">
    nLangEdits  = '<?php echo $this->session->userdata("tLangEdit");?>';
    tUsrApv     = '<?php echo $this->session->userdata("tSesUsername");?>';
    JSxCheckPinMenuClose();

    // Disabled Enter in Form
    $(document).keypress(
        function(event){
            if (event.which == '13') {
                event.preventDefault();
            }
        }
    );

    $(document).ready(function(){

        $('.xCNMenuplus').unbind().click(function(){
            if($(this).hasClass('collapsed')){
                $('.xCNMenuplus').removeClass('collapsed').addClass('collapsed');
                $('.xCNMenuPanelData').removeClass('in');
            }
        });

        if(JSbAPDIsApv() || JSbAPDIsStaDoc('cancel')){
            JSxCMNVisibleComponent('#obtAPDCancel', false);
            JSxCMNVisibleComponent('#obtAPDApprove', false);
            JSxCMNVisibleComponent('#odvBtnAddEdit .btn-group', false);
        }

        var nCrTerm = $('#ocmAPDXphCshOrCrd').val();
        if (nCrTerm == 2) {
            $('.xCNPanel_CreditTerm').show();
        }else{
            $('.xCNPanel_CreditTerm').hide();
        }

        if(JCNbAPDIsUpdatePage()){
            // Doc No
            $("#oetAPDDocNo").attr("readonly", true);
            $("#odvAPDAutoGenDocNoForm input").attr("disabled", true);
            JSxCMNVisibleComponent('#odvAPDAutoGenDocNoForm', false);

            JSxCMNVisibleComponent('#obtCardShiftOutBtnApv', true);
            JSxCMNVisibleComponent('#obtCardShiftOutBtnCancelApv', true);
            JSxCMNVisibleComponent('#obtCardShiftOutBtnDocMa', true);

            if(JCNbAPDIsDocType('havePdt') && JSbAPDGetStaApv() == '2'){
                JSoAPDSubscribeMQ();
            }

            if(JSbAPDIsStaDoc('cancel')){ // ปิดปุ่มพิมพ์เมื่อมีการยกเลิกเอกสาร
                JSxCMNVisibleComponent('#obtAPDPrintDoc', false);
            }else{ // นอกนั้นให้เปิดปุ่ม
                JSxCMNVisibleComponent('#obtAPDPrintDoc', true);
            }
        }

        if(JCNbAPDIsCreatePage()){
            // Doc No
            $("#oetAPDDocNo").attr("disabled", true);
            $('#ocbAPDAutoGenCode').change(function(){
                if($('#ocbAPDAutoGenCode').is(':checked')) {
                    $("#oetAPDDocNo").attr("disabled", true);
                    $('#odvAPDDocNoForm').removeClass('has-error');
                    $('#odvAPDDocNoForm em').remove();
                }else{
                    $("#oetAPDDocNo").attr("disabled", false);
                }
            });
            JSxCMNVisibleComponent('#odvAPDAutoGenDocNoForm', true);

            JSxCMNVisibleComponent('#obtAPDPrintDoc', false);
            JSxCMNVisibleComponent('#obtCardShiftOutBtnApv', false);
            JSxCMNVisibleComponent('#obtCardShiftOutBtnCancelApv', false);
            JSxCMNVisibleComponent('#obtCardShiftOutBtnDocMa', false);
        }

        // เอกสารยังไม่มีการอนุมัติ หรือ ไม่ถูกยกเลิกให้เริ่มการทำงานนี้
        if(!(JSbAPDIsApv() || JSbAPDIsStaDoc('cancel'))){
            // Condition control onload
            if(JStCMNUserLevel() == 'HQ'){
                // Init
                $('#obtAPDBrowseMch').attr('disabled', false);
                $('#obtAPDBrowseShp').attr('disabled', true);
                $('#obtAPDBrowsePos').attr('disabled', true);
                $('#obtAPDBrowseWah').attr('disabled', false);
            }

            if(JStCMNUserLevel() == 'BCH'){
                // Init
                // $('#obtAPDBrowseBch').attr('disabled', true);
                $('#obtAPDBrowseMch').attr('disabled', false);
                $('#obtAPDBrowseShp').attr('disabled', true);
                $('#obtAPDBrowsePos').attr('disabled', true);
                $('#obtAPDBrowseWah').attr('disabled', false);
            }

            if(JStCMNUserLevel() == 'SHP'){
                // Init
                // $('#obtAPDBrowseBch').attr('disabled', true);
                $('#obtAPDBrowseMch').attr('disabled', true);
                $('#obtAPDBrowseShp').attr('disabled', true);
                $('#obtAPDBrowsePos').attr('disabled', false);
                $('#obtAPDBrowseWah').attr('disabled', true);
            }
        }

        $('#oliAPDMngPdtScan').click(function(){
            var tSplCode = $('#oetAPDSplCode').val();
            if(tSplCode === ''){
                var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
                FSvCMNSetMsgWarningDialog(tWarningMessage);
                return;
            }

            // Hide
            $('#oetAPDSearchPdtHTML').hide();
            $('#oimAPDMngPdtIconSearch').hide();
            // Show
            $('#oetAPDScanPdtHTML').show();
            $('#oimAPDMngPdtIconScan').show();
        });

        $('#oliAPDMngPdtSearch').click(function(){
            // Hide
            $('#oetAPDScanPdtHTML').hide();
            $('#oimAPDMngPdtIconScan').hide();
            // Show
            $('#oetAPDSearchPdtHTML').show();
            $('#oimAPDMngPdtIconSearch').show();
        });

        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        $('.xCNTimePicker').datetimepicker({
            format: 'HH:mm:ss'
        });

        $('.xWTooltipsBT').tooltip({'placement': 'bottom'});
        $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});

    });

    /*========================= Begin Browse Options =============================*/

    var oAgnOption  = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var tUsrLevSession      = '<?=$this->session->userdata("tSesUsrLevel"); ?>';
        var tWhereAgn           = '';

        var oOptionReturn  = {
            Title   : ['company/branch/branch', 'tBchAgnTitle'],
            Table   : {Master:'TCNMAgency', PK:'FTAgnCode'},
            Join    : {
                Table   : ['TCNMAgency_L'],
                On      : [' TCNMAgency.FTAgnCode = TCNMAgency_L.FTAgnCode AND TCNMAgency_L.FNLngID = '+nLangEdits]
            },
            Where   : {
                Condition : [tWhereAgn]
            },
            GrideView:{
                ColumnPathLang	: 'company/branch/branch',
                ColumnKeyLang	: ['tBchAgnCode', 'tBchAgnName'],
                ColumnsSize     : ['15%', '85%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat : ['', ''],
                Perpage			: 10,
                OrderBy			: ['TCNMAgency.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType      : 'S',
                Value           : [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text            : [tInputReturnName, "TCNMAgency_L.FTAgnName"]
            },
            NextFunc:{
                FuncName            : tNextFuncName,
                ArgReturn           : ['FTAgnCode']
            }
        };
        return oOptionReturn;
    }

    $('#obtAPDBrowseAgency').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oAPDBrowseAgnOption   = undefined;
            oAPDBrowseAgnOption          = oAgnOption({
                'tReturnInputCode'  : 'oetAPDAgnCode',
                'tReturnInputName'  : 'oetAPDAgnName',
                'tNextFuncName'     : 'JSxAPDSetConditionAfterSelectAGN'
            });
            JCNxBrowseData('oAPDBrowseAgnOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //หลังจากเลือก
    function JSxAPDSetConditionAfterSelectAGN(poDataNextFunc){
        var aData;
        if (poDataNextFunc  != "NULL") {
            $('#oetAPDBchCode,#oetAPDBchName').val('');
            $('#oetAPDWahCode,#oetAPDWahName').val('');
        }
    }


    // สาขา
    $('#obtAPDBrowseBch').click(function(){
        tUsrLevel       = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti       = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhere       = "";

        var tAgnCode    = $('#oetAPDAgnCode').val();
        if(tAgnCode != ""){
            tSQLWhere   = " AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
        }

        if(tUsrLevel != "HQ"){
            tSQLWhere   = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }

        JSxCheckPinMenuClose();
        tOldBchCkChange = $("#oetBchCode").val();
        nLangEdits      = <?php echo $this->session->userdata("tLangEdit")?>;
        oPmhBrowseBch   = {
            Title   : ['company/branch/branch', 'tBCHTitle'],
            Table   : {Master:'TCNMBranch', PK:'FTBchCode'},
            Join    : {
                Table: ['TCNMBranch_L'],
                On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
            },
            Where   : {
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
            },
            CallBack:{
                ReturnType: 'S',
                Value: ["oetAPDBchCode", "TCNMBranch.FTBchCode"],
                Text: ["oetAPDBchName", "TCNMBranch_L.FTBchName"]
            },
            NextFunc:{
                FuncName: 'JSxAPDCallbackAfterSelectBch',
                ArgReturn: ['FTBchCode', 'FTBchName']
            }
        };
        JCNxBrowseData('oPmhBrowseBch');
    });

    // กลุ่มร้านค้า
    $('#obtAPDBrowseMch').click(function(){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        tOldMchCkChange = $("#oetMchCode").val();
        // Option merchant
        var tBch = $("#oetAPDBchCode").val();
        if($("#oetAPDBchCode").val()){
            tBch = $("#oetAPDBchCode").val();
        }
        oAPDBrowseMch = {
            Title: ['company/warehouse/warehouse', 'tWAHBwsMchTitle'],
            Table: {Master:'TCNMMerchant', PK:'FTMerCode'},
            Join: {
                Table: ['TCNMMerchant_L'],
                On: ['TCNMMerchant.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits]
            },
            Where: {
                Condition: ["AND (SELECT COUNT(FTShpCode) FROM TCNMShop WHERE TCNMShop.FTMerCode = TCNMMerchant.FTMerCode AND TCNMShop.FTBchCode = '"+tBch+"') != 0"]
            },
            GrideView: {
                ColumnPathLang: 'company/warehouse/warehouse',
                ColumnKeyLang: ['tWAHBwsMchCode', 'tWAHBwsMchNme'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMMerchant.FTMerCode', 'TCNMMerchant_L.FTMerName'],
                DataColumnsFormat: ['',''],
                Perpage: 10,
                OrderBy: ['TCNMMerchant.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType: 'S',
                Value: ["oetAPDMchCode", "TCNMMerchant.FTMerCode"],
                Text: ["oetAPDMchName", "TCNMMerchant_L.FTMerName"]
            },
            NextFunc:{
                FuncName:'JSxAPDCallbackAfterSelectMer',
                ArgReturn:['FTMerCode', 'FTMerName']
            },
            BrowseLev: 1
            // DebugSQL : true
        };
        // Option merchant
        JCNxBrowseData('oAPDBrowseMch');
    });

    // ร้านค้า
    $('#obtAPDBrowseShp').click(function(){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        // Option Shop
        var tMch = $("#oetAPDMchCode").val();
        var tBch = $("#oetAPDBchCode").val();
        if($("#oetAPDBchCode").val()){
            tBch = $("#oetAPDBchCode").val();
        }

        oAPDBrowseShp = {
            Title : ['company/shop/shop', 'tSHPTitle'],
            Table:{Master: 'TCNMShop', PK: 'FTShpCode'},
            Join :{
                Table: ['TCNMShop_L', 'TCNMWaHouse_L'],
                On: ['TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop.FTBchCode = TCNMShop_L.FTBchCode AND TCNMShop_L.FNLngID = '+nLangEdits,
                    'TCNMShop.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMShop.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID= '+nLangEdits
                ]
            },
            Where:{
                Condition : [
                    function(){
                        var tSQL = "AND TCNMShop.FTBchCode = '"+tBch+"' AND TCNMShop.FTMerCode = '"+tMch+"'";
                        return tSQL;
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['25%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName', 'TCNMShop.FTWahCode', 'TCNMWaHouse_L.FTWahName', 'TCNMShop.FTShpType', 'TCNMShop.FTBchCode'],
                DataColumnsFormat: ['', '', '', '', '', ''],
                DisabledColumns:[2, 3, 4, 5],
                Perpage: 10,
                OrderBy: ['TCNMShop.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetAPDShpCode", "TCNMShop.FTShpCode"],
                Text: ["oetAPDShpName", "TCNMShop_L.FTShpName"]
            },
            NextFunc: {
                FuncName: 'JSxAPDCallbackAfterSelectShp',
                ArgReturn: ['FTBchCode', 'FTShpCode', 'FTShpType', 'FTWahCode', 'FTWahName']
            },
            BrowseLev: 1,
            // DebugSQL : true
        };
        // Option Shop
        JCNxBrowseData('oAPDBrowseShp');
    });

    // เครื่องจุดขาย
    $('#obtAPDBrowsePos').click(function(){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        // Option Shop
        var tBch = $("#oetAPDBchCode").val();
        if($("#oetAPDBchCode").val()){
            tBch = $("#oetAPDBchCode").val();
        }
        oAPDBrowsePos = {
            Title: ['pos/posshop/posshop', 'tPshTBPosCode'],
            Table: { Master:'TVDMPosShop', PK:'FTPosCode' },
            Join: {
                Table: ['TCNMPos', 'TCNMPosLastNo', 'TCNMWaHouse', 'TCNMWaHouse_L'],
                On:['TVDMPosShop.FTPosCode = TCNMPos.FTPosCode AND TVDMPosShop.FTBchCode = TCNMPos.FTBchCode' ,
                    'TVDMPosShop.FTPosCode = TCNMPosLastNo.FTPosCode',
                    'TVDMPosShop.FTPosCode = TCNMWaHouse.FTWahRefCode AND TVDMPosShop.FTBchCode = TCNMWaHouse.FTBchCode AND TCNMWaHouse.FTWahStaType = 6',
                    'TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TVDMPosShop.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID= '+nLangEdits
                ]
            },
            Where: {
                Condition: [
                    function(){
                        var tSQL = "AND TVDMPosShop.FTBchCode = '"+tBch+"' AND TVDMPosShop.FTShpCode = '"+$("#oetAPDShpCode").val()+"'";
                        return tSQL;
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'pos/posshop/posshop',
                ColumnKeyLang: ['tPshBRWShopTBCode', 'tPshBRWPosTBName'],
                ColumnsSize: ['25%', '75%'],
                WidthModal: 50,
                DataColumns: ['TVDMPosShop.FTPosCode', 'TCNMPosLastNo.FTPosComName', 'TVDMPosShop.FTShpCode', 'TVDMPosShop.FTBchCode', 'TCNMWaHouse.FTWahCode', 'TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat : ['', '', '', '', '', ''],
                DisabledColumns: [1, 2, 3, 4, 5],
                Perpage: 10,
                OrderBy: ['TVDMPosShop.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetAPDPosCode", "TVDMPosShop.FTPosCode"],
                Text: ["oetAPDPosName", "TCNMPosLastNo.FTPosCode"]
            },
            NextFunc: {
                FuncName: 'JSxAPDCallbackAfterSelectPos',
                ArgReturn: ['FTBchCode', 'FTShpCode', 'FTPosCode', 'FTWahCode', 'FTWahName']
            },
            BrowseLev: 1
        };
        // Option Shop
        JCNxBrowseData('oAPDBrowsePos');
    });

    // คลังสินค้า
    $('#obtAPDBrowseWah').click(function(){
        var tAPDBchCode   =  $('#oetAPDBchCode').val();

        JSxCheckPinMenuClose(); // Hidden Pin Menu
        // Option WareHouse
        oAPDBrowseWah = {
            Title: ['company/warehouse/warehouse', 'tWAHTitle'],
            Table: { Master:'TCNMWaHouse', PK:'FTWahCode'},
            Join: {
                Table: ['TCNMWaHouse_L'],
                On:['TCNMWaHouse_L.FTWahCode = TCNMWaHouse.FTWahCode AND  TCNMWaHouse_L.FTBchCode = TCNMWaHouse.FTBchCode AND TCNMWaHouse_L.FNLngID = '+nLangEdits]
            },
            Where: {
                Condition: [
                    function(){
                        var tSQL = "AND TCNMWaHouse.FTBchCode = '"+tAPDBchCode+"'";
                        if(($("#oetAPDShpCode").val() == '') && ($("#oetAPDPosCode").val() == '') ){ // Branch Wah
                            tSQL += " AND TCNMWaHouse.FTWahStaType IN (1,2,5)";
                        }

                        if( ($("#oetAPDShpCode").val() != '') && ($("#oetAPDPosCode").val() == '') ){ // Shop Wah
                            tSQL += " AND TCNMWaHouse.FTWahStaType IN (4)";
                            tSQL += " AND TCNMWaHouse.FTWahRefCode = '"+$('#oetAPDShpCode').val()+"'";
                        }

                        if( ($("#oetAPDShpCode").val() != '') && ($("#oetAPDPosCode").val() != '') ){ // Pos(vending) Wah
                            tSQL += " AND TCNMWaHouse.FTWahStaType IN (6)";
                            tSQL += " AND TCNMWaHouse.FTWahRefCode = '"+$('#oetAPDPosCode').val()+"'";
                        }
                        // console.log(tSQL);
                        return tSQL;
                    }
                ]
            },
            GrideView:{
                ColumnPathLang: 'company/warehouse/warehouse',
                ColumnKeyLang: ['tWahCode','tWahName'],
                DataColumns: ['TCNMWaHouse.FTWahCode','TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat: ['',''],
                ColumnsSize: ['15%','75%'],
                Perpage: 10,
                WidthModal: 50,
                OrderBy: ['TCNMWaHouse.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType: 'S',
                Value: ["oetAPDWahCode","TCNMWaHouse.FTWahCode"],
                Text: ["oetAPDWahName","TCNMWaHouse_L.FTWahName"]
            },
            NextFunc:{
                FuncName: 'JSxAPDCallbackAfterSelectWah',
                ArgReturn: []
            },
            RouteAddNew: 'warehouse',
            BrowseLev: nStaAPDBrowseType
        };
        // Option WareHouse
        JCNxBrowseData('oAPDBrowseWah');
    });

    // ผู้จำหน่าย
    $('#obtAPDBrowseSpl').click(function(){
        var tCDNAgnCode = '<?=$this->session->userdata('tSesUsrAgnCode')?>';
        JSxCheckPinMenuClose();

        var tWhere = '';
        if(tCDNAgnCode != ''){
            tWhere += " AND ( TCNMSpl.FTAgnCode = '"+tCDNAgnCode+"' OR  ISNULL(TCNMSpl.FTAgnCode,'')=''  )  ";
        }

        oAPDBrowseSpl = {
            Title: ['supplier/supplier/supplier', 'tSPLTitle'],
            Table: {Master:'TCNMSpl', PK:'FTSplCode'},
            Join: {
                Table: ['TCNMSpl_L', 'TCNMSplCredit' , 'VCN_VatActive'],
                On: [
                    'TCNMSpl_L.FTSplCode    = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
                    'TCNMSpl_L.FTSplCode    = TCNMSplCredit.FTSplCode',
                    'TCNMSpl.FTVatCode      = VCN_VatActive.FTVatCode'
                ]
            },
            Where:{
                Condition : ["AND TCNMSpl.FTSplStaActive = '1' "+tWhere]
            },
            GrideView:{
                ColumnPathLang: 'supplier/supplier/supplier',
                ColumnKeyLang: ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid', 'TCNMSpl.FTVatCode','VCN_VatActive.FCVatRate'],
                DataColumnsFormat: ['',''],
                DisabledColumns: [2, 3, 4, 5, 6 , 7],
                Perpage: 10,
                OrderBy: ['TCNMSpl.FDCreateOn DESC']
            },
            CallBack:{
                ReturnType: 'S',
                Value: ["oetAPDSplCode", "TCNMSpl.FTSplCode"],
                Text: ["oetAPDSplName", "TCNMSpl_L.FTSplName"]
            },
            NextFunc:{
                FuncName:'JSxAPDCallbackAfterSelectSpl',
                ArgReturn:['FNSplCrTerm', 'FCSplCrLimit', 'FTSplStaVATInOrEx', 'FTSplTspPaid', 'FTSplCode', 'FTSplName', 'FTVatCode' , 'FCVatRate']
            },
            RouteAddNew: 'supplier',
            BrowseLev: nStaAPDBrowseType

        };
        // Option WareHouse
        JCNxBrowseData('oAPDBrowseSpl');
    });

    $('#obtAPDBrowseReason').click(function(){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        // Option WareHouse
        oAPDBrowseReason = {
                Title: ['other/reason/reason', 'tRSNTitle'],
                Table: { Master:'TCNMRsn', PK:'FTRsnCode' },
                Join: {
                    Table: ['TCNMRsn_L'],
                    On: ['TCNMRsn_L.FTRsnCode = TCNMRsn.FTRsnCode AND TCNMRsn_L.FNLngID = '+nLangEdits]
                },
                Where: {
                    Condition : ["AND TCNMRsn.FTRsgCode = '003' "]
                },
                GrideView:{
                    ColumnPathLang: 'other/reason/reason',
                    ColumnKeyLang: ['tRSNTBCode', 'tRSNTBName'],
                    // ColumnsSize: ['15%', '85%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMRsn.FTRsnCode', 'TCNMRsn_L.FTRsnName'],
                    DisabledColumns: [],
                    DataColumnsFormat: ['', ''],
                    Perpage: 5,
                    OrderBy: ['TCNMRsn_L.FTRsnName'],
                    SourceOrder: "ASC"
                },
                CallBack:{
                    ReturnType: 'S',
                    Value: ["oetAPDReasonCode", "TCNMRsn.FTRsnCode"],
                    Text: ["oetAPDReasonName", "TCNMRsn_L.FTRsnName"]
                },
                /*NextFunc:{
                    FuncName:'JSxCSTAddSetAreaCode',
                    ArgReturn:['FTRsnCode']
                },*/
                // RouteFrom : 'cardShiftChange',
                RouteAddNew : 'reason',
                BrowseLev : nStaAPDBrowseType
        };
        // Option WareHouse
        JCNxBrowseData('oAPDBrowseReason');
    });

    // ===================== Start  Callback Browse ==================================

    // Function : Process after select branch
    // Creator  : 04/03/2022 Wasin
    function JSxAPDCallbackAfterSelectBch(poJsonData) {
        var bDataIsNull = poJsonData == 'NULL';
        if(JCNbAPDIsDocType('havePdt') && !bDataIsNull && JSbAPDHasRowInTemp()) {
            $('#odvAPDPopupChangeSplConfirm').modal('show');
        }
    }

    // Function : Process after select merchant
    // Creator  : 04/03/2022 Wasin
    function JSxAPDCallbackAfterSelectMer(poJsonData) {
        if (poJsonData != "NULL") {
            aData = JSON.parse(poJsonData);
            tAddBch = aData[0];
            tAddSeqNo = aData[1];
        }
        var tBchCode = $('#oetAPDBchCode').val();
        var tMchName = $('#oetAPDMchName').val();
        var tShpName = $('#oetAPDShpName').val();
        var tPosName = $('#oetAPDPosName').val();
        var tWahName = $('#oetAPDWahName').val();
        $('#obtAPDBrowseShp').attr('disabled', true);
        $('#obtAPDBrowsePos').attr('disabled', true);
        $('#obtAPDBrowseWah').attr('disabled', true);
        if(JStCMNUserLevel() == 'HQ' || JStCMNUserLevel() == 'BCH'){
            if(tMchName != ''){
                $('#obtAPDBrowseShp').attr('disabled', false);
                $('#obtAPDBrowseWah').attr('disabled', true);
            }else{
                $('#obtAPDBrowseWah').attr('disabled', false);
            }
            $('#oetAPDShpCode, #oetAPDShpName').val('');
            $('#oetAPDPosCode, #oetAPDPosName').val('');
            $('#oetAPDWahCode, #oetAPDWahName').val('');
        }
    }

    // Function :  Process after select shop
    // Creator  : 04/03/2022 Wasin
    function JSxAPDCallbackAfterSelectShp(poJsonData) {
        var aData, tResAddBch, tResAddSeqNo, tResWahCode, tResWahName;
        if (poJsonData != "NULL") {
            aData = JSON.parse(poJsonData);
            tResAddBch = aData[0];
            tResAddSeqNo = aData[1];
            tResWahCode = aData[3];
            tResWahName = aData[4];
        }else{
            $('#oetAPDWahCode, #oetAPDWahName').val('');
        }
        // console.log('aData: ', aData);
        $('#ohdAPDWahCodeInShp').val(tResWahCode);
        $('#ohdAPDWahNameInShp').val(tResWahName);
        var tBchCode = $('#oetAPDBchCode').val();
        var tMchName = $('#oetAPDMchName').val();
        var tShpName = $('#oetAPDShpName').val();
        var tPosName = $('#oetAPDPosName').val();
        var tWahName = $('#oetAPDWahName').val();

        $('#obtAPDBrowsePos').attr('disabled', true);
        $('#obtAPDBrowseWah').attr('disabled', false);

        if(JStCMNUserLevel() == 'HQ' || JStCMNUserLevel() == 'BCH'){
            if(tShpName != ''){
                $('#obtAPDBrowsePos').attr('disabled', false);
                $('#obtAPDBrowseWah').attr('disabled', true);
                $('#oetAPDWahCode').val(tResWahCode);
                $('#oetAPDWahName').val(tResWahName);
            }else{
                $('#oetAPDWahCode, #oetAPDWahName').val('');
            }
            $('#oetAPDPosCode, #oetAPDPosName').val('');
        }
    }

    // Function :  Process after select pos
    // Creator  : 04/03/2022 Wasin
    function JSxAPDCallbackAfterSelectPos(poJsonData) {
        var aData, tResAddBch, tResAddSeqNo, tResWahCode, tResWahName;
        if (poJsonData != "NULL") {
            aData = JSON.parse(poJsonData);
            tResAddBch = aData[0];
            tResAddSeqNo = aData[1];
            tResWahCode = aData[3];
            tResWahName = aData[4];
        }else{
            $('#oetAPDPosCode, #oetAPDPosName').val('');
            $('#oetAPDWahCode').val($('#ohdAPDWahCodeInShp').val());
            $('#oetAPDWahName').val($('#ohdAPDWahNameInShp').val());
            return;
        }
        var tBchCode = $('#oetAPDBchCode').val();
        var tMchName = $('#oetAPDMchName').val();
        var tShpName = $('#oetAPDShpName').val();
        var tPosName = $('#oetAPDPosName').val();
        var tWahName = $('#oetAPDWahName').val();
        $('#obtAPDBrowseWah').attr('disabled', false);
        if(JStCMNUserLevel() == 'HQ' || JStCMNUserLevel() == 'BCH' || JStCMNUserLevel() == 'SHP'){
            if(tPosName != ''){
                $('#obtAPDBrowseWah').attr('disabled', true);
                $('#oetAPDWahCode').val(tResWahCode);
                $('#oetAPDWahName').val(tResWahName);
            }
        }
    }

    // Function :  Process after select warehouse
    // Creator  : 04/03/2022 Wasin
    function JSxAPDCallbackAfterSelectWah(poJsonData) {
        var aData;
        if (poJsonData != "NULL") {
            aData = JSON.parse(poJsonData);
            tAddBch = aData[0];
            tAddSeqNo = aData[1];
        }
    }

    // Function :  Process after select supplier
    // Creator  : 04/03/2022 Wasin
    function JSxAPDCallbackAfterSelectSpl(ptJsonData) {
        var aData;
        if (ptJsonData != "NULL") {
            aData = JSON.parse(ptJsonData);
            var poParams = {
                FNSplCrTerm         : aData[0],
                FCSplCrLimit        : aData[1],
                FTSplStaVATInOrEx   : aData[2],
                FTSplTspPaid        : aData[3],
                FTSplCode           : aData[4],
                FTSplName           : aData[5],
                FTVatCode           : aData[6],
                FCVatRate           : aData[7]
            };
            JSxAPDSetPanelSpl(poParams);
        }
    }

    // ===================== End Callback Browse ==================================


    // Function : ล้าง Temp เอกสารทั้งหมด
    // Creator  : 04/03/2022 Wasin
    function JSxAPDClearTemp() {
        $('#odvAPDPopupChangeSplConfirm').modal('hide');
        if(JCNbAPDIsDocType('havePdt')) {
            $.ajax({
                type: "POST",
                url: "docAPDebitnoteClearTemp",
                data: {},
                cache: false,
                Timeout: 0,
                success: function (oResult) {
                    JSvAPDLoadPdtDataTableHtml(1, true);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    // Function : Process after shoose supplyer
    // Creator  : 04/03/2022 Wasin
    function JSxAPDSetPanelSpl(poParams) {
        console.log(poParams);
        // Set Name Supplier
        $('#oetAPDFrmSplNameShow').val(poParams.FTSplName);

        // Reset
        $("#ocmAPDXphVATInOrEx.selectpicker").val("1").selectpicker("refresh");
        $("#ocmAPDXphCshOrCrd.selectpicker").val("2").selectpicker("refresh");
        $("#ocmAPDHDPcSplXphDstPaid.selectpicker").val("1").selectpicker("refresh");
        $("#oetAPDHDPcSplXphCrTerm.selectpicker").val("");

        // รหัสภาษีจากผู้จำหน่าย
        $("#ohdAPDSplVatCode").val(poParams.FTVatCode);

        // ประเภทภาษี
        if(poParams.FTSplStaVATInOrEx === "1"){ // รวมใน
            $("#ocmAPDXphVATInOrEx.selectpicker").val("1").selectpicker("refresh");
        }else{ // แยกนอก
            $("#ocmAPDXphVATInOrEx.selectpicker").val("2").selectpicker("refresh");
        }

        // ประเภทชำระเงิน
        if(poParams.FCSplCrLimit > 0){ // เงินเชื่อ
            $("#ocmAPDXphCshOrCrd.selectpicker").val("2").selectpicker("refresh");
        }else{ // เงินสด
            $("#ocmAPDXphCshOrCrd.selectpicker").val("1").selectpicker("refresh");
        }

        // การชำระเงิน
        if(poParams.FTSplTspPaid === "1"){ // ต้นทาง
            $("#ocmAPDHDPcSplXphDstPaid.selectpicker").val("1").selectpicker("refresh");
        }else{ // ปลายทาง
            $("#ocmAPDHDPcSplXphDstPaid.selectpicker").val("2").selectpicker("refresh");
        }

        // ระยะเครดิต
        $("#oetAPDHDPcSplXphCrTerm").val(poParams.FNSplCrTerm);

        // Vat จาก SPL
        $('#ohdCNFrmSplVatCode').val(poParams.FTVatCode);
        $('#ohdCNFrmSplVatRate').val(poParams.FCVatRate);

        //เปลี่ยน VAT
        var tVatCode = poParams.FTVatCode;
        var tVatRate = poParams.FCVatRate;
        JSxChangeVatBySPL(tVatCode,tVatRate);
    }

    // Function : ทุกครั้งที่เปลี่ยน SPL ต้องเกิดการคำนวณ VAT ใหม่ที่อยู่ในสินค้า
    // Creator  : 04/03/2022 Wasin
    function JSxChangeVatBySPL(tVatCode,tVatRate){
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docAPDebitnoteChangeSPLAffectNewVAT",
            data: {
                'tBCHCode'      : $('#oetAPDBchCode').val(),
                'tCNDocNo'      : $("#oetAPDDocNo").val(),
                'tVatCode'      : tVatCode,
                'tVatRate'      : tVatRate
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                var tDocType = $('#ohdAPDDocType').val();
                if(tDocType != '7'){
                    JSvAPDLoadPdtDataTableHtml(1,true)
                }else{
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function : คำนวณท้ายบิล ใบลดหนี้ไม่มีสินค้า
    // Creator  : 04/03/2022 Wasin
    function JSoAPDCalEndOfBillNonePdt(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tValue          = $('#oetAPDNonePdtValue').val();
            var tVatCode        = $('#ohdAPDSplVatCode').val();
            var tSplVatType     = $('#ocmAPDXphVATInOrEx').val();
            $.ajax({
                type: "POST",
                url: "docAPDebitnoteCalEndOfBillNonePdt",
                data: {
                    tSplVatType : tSplVatType,
                    tVatCode    : tVatCode,
                    tValue      : tValue
                },
                cache: false,
                Timeout: 0,
                success: function (oResult) {
                    $('#ospAPDCalEndOfBillNonePdt').text(JSON.stringify(oResult)); // เก็บยอดท้ายบิล ใบลดหนี้ไม่มีสินค้า
                    $('#oulAPDListVatNonePdt').removeClass('xCNHide');
                    $('#odvAPDTextBath').text(oResult['tTotalValueText']);
                    $('#oulAPDListVatNonePdt #olbAPDVatrate').text(oResult['tVatrateText']); // ภาษีมูลค่าเพิ่ม
                    $('#oulAPDListVatNonePdt #oblAPDSumVat').text(oResult['cVat']); // ยอดภาษี
                    $('#olbAPDVatSum').text(oResult['cVat']); // ยอดรวมภาษีมูลค่าเพิ่ม
                    $('#olbAPDSumFCXtdNet').text(oResult['tValue']); // จำนวนเงินรวม
                    $('#olbAPDSumFCXtdVat').text(oResult['cVat']); // ยอดรวมภาษีมูลค่าเพิ่ม
                    $('#olbAPDCalFCXphGrand').text(oResult['cTotalValue']); // จำนวนเงินรวมทั้งสิ้น
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Function : เพิ่มสินค้าลงในตาราง DT
    // Creator  : 04/03/2022 Wasin
    function JSxAPDAddPdtInRow(poJsonData){
        for (var n = 0; n < poJsonData.length; n++) {
            var tdVal = $('.nItem'+n).data('otrval')
            if((tdVal != '') && (typeof tdVal == 'undefined')){
                nTRID = JCNnRandomInteger(100, 1000000);
                var aColDatas = JSON.parse(poJsonData[n]);
                var tPdtCode = aColDatas[0];
                var tPunCode = aColDatas[1];
                FSvAPDAddPdtIntoTableDT(tPdtCode, tPunCode);
            }
        }
    }


    

    // Function : Check Approve Document
    // Creator  : 04/03/2022 Wasin
    function JSbAPDIsApv(){
        var bStatus = false;
        if(($("#ohdAPDStaApv").val() == "1") || ($("#ohdAPDStaApv").val() == "2")){
            bStatus = true;
        }
        return bStatus;
    }

    // Function : Check Status Approve Document
    // Creator  : 04/03/2022 Wasin
    function JSbAPDGetStaApv(){
        return $("#ohdAPDStaApv").val();
    }
    
    // Function : Check Status Process Stock Document
    // Creator  : 04/03/2022 Wasin
    function JSbAPDIsStaPrcStk(){
        var bStatus = false;
        if($("#ohdAPDAjhStaPrcStk").val() == "1"){
            bStatus = true;
        }
        return bStatus;
    }
    
    // Function : Check Status Document
    // Creator  : 04/03/2022 Wasin
    function JSbAPDIsStaDoc(ptStaType){
        var bStatus = false;
        if(ptStaType == "complete"){
            if($("#ohdAPDStaDoc").val() == "1"){
                bStatus = true;
            }
            return bStatus;
        }
        if(ptStaType == "incomplete"){
            if($("#ohdAPDStaDoc").val() == "2"){
                bStatus = true;
            }
            return bStatus;
        }
        if(ptStaType == "cancel"){
            if($("#ohdAPDStaDoc").val() == "3"){
                bStatus = true;
            }
            return bStatus;
        }
        return bStatus;
    }

    // ============================= Begin Custom Form Validate ===================
    var bUniqueAPDCode;
    $.validator.addMethod(
        "uniqueAPDCode",
        function(tValue, oElement, aParams) {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof nStaSession !== "undefined" && nStaSession == 1) {
                var tAPDCode = tValue;
                $.ajax({
                    type: "POST",
                    url: "docAPDebitnoteUniqueValidate/docAPDCode",
                    data: "tAPDCode=" + tAPDCode,
                    dataType:"html",
                    success: function(ptMsg){
                        // If vatrate and vat start exists, set response to true
                        bUniqueAPDCode = (ptMsg == 'true') ? false : true;
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Custom validate uniqueAPDCode: ', jqXHR, textStatus, errorThrown);
                    },
                    async: false
                });
                return bUniqueAPDCode;
            }else {
                JCNxShowMsgSessionExpired();
            }

        },
        "AP Debitnote Doc Code is Already Taken"
    );

    // Override Error Message
    jQuery.extend(jQuery.validator.messages, {
        required    : "This field is required.",
        remote      : "Please fix this field.",
        email       : "Please enter a valid email address.",
        url         : "Please enter a valid URL.",
        date        : "Please enter a valid date.",
        dateISO     : "Please enter a valid date (ISO).",
        number      : "Please enter a valid number.",
        digits      : "Please enter only digits.",
        creditcard  : "Please enter a valid credit card number.",
        equalTo     : "Please enter the same value again.",
        accept      : "Please enter a value with a valid extension.",
        maxlength   : jQuery.validator.format("Please enter no more than {0} characters."),
        minlength   : jQuery.validator.format("Please enter at least {0} characters."),
        rangelength : jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
        range       : jQuery.validator.format("Please enter a value between {0} and {1}."),
        max         : jQuery.validator.format("Please enter a value less than or equal to {0}."),
        min         : jQuery.validator.format("Please enter a value greater than or equal to {0}.")
    });
    // ============================= End Custom Form Validate =====================

    

    // Function : Form validate
    // Creator  : 04/03/2022 Wasin
    function JSxValidateFormAddAPD() {
        $('#ofmAddAPD').validate({
            focusInvalid: false,
            onclick: false,
            onfocusout: false,
            onkeyup: false,
            rules: {
                oetAPDDocNo: {
                    required: true,
                    maxlength: 20,
                    uniqueAPDCode: JCNbAPDIsCreatePage()
                },
                obtAPDDocDate: {
                    required: true
                },
                obtAPDDocTime: {
                    required: true
                }
            },
            messages: {
                oetAPDDocNo: {
                    "required": $('#oetAPDDocNo').attr('data-validate-required')
                }
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
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
            highlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function (form) {
                JSxAPDAddUpdateAction();
            }
        });
    }

    // Function : Add or Update
    // Creator  : 10/03/2022 Wasin
    function JSxAPDAddUpdateAction(ptType = '') {

        var nStaSession = JCNxFuncChkSessionExpired();

        // โปรดเลือกผู้จำหน่ายก่อนทำรายการ
        var tSltSupplierMessage     = '<?=language('document/apdebitnote/apdebitnote','tSltSuppiler');?>';
        // โปรดเลือกคลังสินค้าก่อนทำรายการ
        var tSltWahourseMessage     = '<?=language('document/apdebitnote/apdebitnote','tSltWahourse');?>';
        // โปรดกรอกชื่อรายการ
        var tPlsFillName            = '<?=language('document/apdebitnote/apdebitnote','tPlsEnterName');?>';
        // โปรดกรอกจำนวนเงินรวม
        var tPlsFillAmt             = '<?=language('document/apdebitnote/apdebitnote','tPlsFillAmt');?>';
        // ไม่พบรายการสินค้าไม่สามารถดำเนินการต่อได้
        var tPdtNotfound            = '<?=language('document/apdebitnote/apdebitnote','tPdtNotfound');?>';

        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tSplCode = $('#oetAPDSplCode').val();
            if(tSplCode == ''){
                var tWarningMessage = tSltSupplierMessage;
                FSvCMNSetMsgWarningDialog(tWarningMessage);
                return;
            }

            if(JCNbAPDIsDocType('nonePdt')){
                var $tNonePdtName = $('#oetAPDNonePdtName').val();
                var $tNonePdtValue = $('#oetAPDNonePdtValue').val();

                if($tNonePdtName === ''){
                    var tWarningMessage = tPlsFillName;
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                }
                if($tNonePdtValue === ''){
                    var tWarningMessage = tPlsFillAmt;
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                }
            }

            if(JCNbAPDIsDocType('havePdt')){

                var tDocType = $('#ohdAPDDocType').val();
                var tWahCode = $('#oetAPDWahCode').val();
                if(tDocType != '7'){
                    if(tWahCode === ''){
                        var tWarningMessage = tSltWahourseMessage;
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;
                    }
                }

                if(!JSbAPDHasRowInTemp()){
                    var tPdtNotfound = tPdtNotfound;
                    FSvCMNSetMsgWarningDialog(tPdtNotfound);
                    return;
                }

            }

            var tNonePdtCode = $('#olbAPDNonePdtCode').text();
            var tNonePdtName = $('#oetAPDNonePdtName').val();
            var tCalEndOfBillNonePdt = $('#ospAPDCalEndOfBillNonePdt').text();

            $(".ocbListItem").removeAttr("disabled", true);
            $(".xCNBtnDateTime").removeAttr("disabled", true);
            $(".xCNDocBrowsePdt").removeAttr("disabled", true).removeClass("xCNBrowsePdtdisabled");
            $("#oetAPDSearchPdtHTML").removeAttr("disabled", false);
            $(".xCNBtnDateTime").removeAttr("disabled", true);
            $(".xCNDatePicker").removeAttr("disabled", true);
            $(".selectpicker").removeAttr("disabled", true);
            $(".form-control").removeAttr("disabled", true);
             
                $.ajax({
                type: "POST",
                url: $('#ohdRoute').val(),
                data: $("#ofmAddAPD").serialize()
                    + '&tPdtCode=' + tNonePdtCode
                    + '&tPdtName=' + tNonePdtName
                    + '&tCalEndOfBillNonePdt=' + tCalEndOfBillNonePdt,
                cache: false,
                timeout: 0,
                success: function (oResult) {
                    if (nStaAPDBrowseType != 1){
                        if (oResult.nStaEvent == "1") {

                            var oCDNCallDataTableFile = {
                                ptElementID : 'odvCDNShowDataTable',
                                ptBchCode   : $('#oetAPDBchCode').val(),
                                ptDocNo     : oResult.tCodeReturn,
                                ptDocKey    :'TAPTPdHD',
                            }
                            JCNxUPFInsertDataFile(oCDNCallDataTableFile);

                            if(ptType == 'approve'){

                            }else{
                                if(oResult.nStaCallBack == "1" || oResult.nStaCallBack == null){
                                    JSvCallPageAPDEdit(oResult.tCodeReturn);
                                    return;
                                }else if(oResult.nStaCallBack == "2"){
                                    JSvCallPageAPDAdd();
                                    return;
                                }else if(oResult.nStaCallBack == "3"){
                                    JSvCallPageAPDList();
                                    return;
                                }
                            }   
                        }else {
                            var tMsgBody = oResult.tStaMessg;
                            FSvCMNSetMsgWarningDialog(tMsgBody);
                        }
                    }else {
                        JCNxBrowseData(tCallAPDBackOption);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        }else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Function : ตรวจสอบว่า ผู้จำหน่ายใช้ภาษีแบบไหน
    // Creator  : 10/03/2022 Wasin
    function JSxAPDIsSplUseVatType(ptSplVatType) {
        var tSplVatType = $('#ocmAPDXphVATInOrEx').val();
        var bStatus = false;
        if(ptSplVatType == 'in'){
            if(tSplVatType == '1'){
                bStatus = true;
            }
        }
        if(ptSplVatType == 'ex'){
            if(tSplVatType == '2'){
                bStatus = true;
            }
        }
        return bStatus;
    }

    // Function : เพิ่มรายการสินค้าจากการแสกนบาร์โค๊ด
    // Creator  : 10/03/2022 Wasin
    function JSxAPDAddPdtFromScanBarCodeToDTTemp(){
        if(JCNbAPDIsDocType('havePdt')){
            var aPdtItems = [];
            var tPdtItems = JSON.stringify(aPdtItems);
            var tIsRefPI = '0';
            var tIsByScanBarCode = '1';
            FSvPDTAddPdtIntoTableDT(tPdtItems, tIsRefPI, tIsByScanBarCode);
        }
    }

    // Function : Has Row in Temp
    // Creator  : 10/03/2022 Wasin
    function JSbAPDHasRowInTemp(){
        var bStatus = false;
        var nTempRows = $('#ohdAPDTempRows').val();
        if(nTempRows > 0){
            bStatus = true;
        }else{
            bStatus = false;
        }
        return bStatus;
    }

    // Function : Change Spl Vat Type
    // Creator  : 10/03/2022 Wasin
    function JSbAPDChangeSplVatType(){

        if(JCNbAPDIsDocType('nonePdt')){
            JSoAPDCalEndOfBillNonePdt();
        }

        if(!JSbAPDHasRowInTemp()){
            return;
        }else{
            JSvAPDLoadPdtDataTableHtml(1, true);
        }
    }

    // Function : Print Document
    // Creator  : 10/03/2022 Wasin
    function JSxAPDPrintDoc(){
        let aInfor  = [
            {"Lang"         : '<?= FCNaHGetLangEdit(); ?>'}, // Lang ID
            {"ComCode"      : '<?= FCNtGetCompanyCode(); ?>'}, // Company Code
            {"BranchCode"   : '<?= FCNtGetAddressBranch($tUserBchCode); ?>' }, // สาขาที่ออกเอกสาร
            {"DocCode"      : '<?= $tDocNo; ?>'}, // เลขที่เอกสาร
            {"FormName"     : 'PC'},
            {"DocBchCode"   : '<?= $tUserBchCode;?>'}
        ];
        let tGrandText  = $('#odvAPDTextBath').text();
        window.open("<?php echo base_url(); ?>formreport/Frm_SQL_SMBillPd?infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand="+tGrandText,  '_blank');
    }

    // Function : Function Browse Pdt
    // Creator  : 10/03/2022 Wasin
    function JCNvAPDBrowsePdt() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tSplCode = $('#oetAPDSplCode').val();
            if(tSplCode === ''){
                var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
                FSvCMNSetMsgWarningDialog(tWarningMessage);
                return;
            }
            $.ajax({
                type: "POST",
                url: "BrowseDataPDT",
                data: {
                    Qualitysearch   : [],
                    PriceType       : ["Cost", "tCN_Cost", "Company", "1"],
                    SelectTier      : ["Barcode"],
                    ShowCountRecord : 10,
                    NextFunc        : "FSvPDTAddPdtIntoTableDT",
                    ReturnType      : "M",
                    SPL             : [$('#oetAPDSplCode').val(), ''],
                    BCH             : [$("#oetAPDBchCode").val(), ''],
                    MER             : [$('#oetAPDMchCode').val(), ''],
                    SHP             : [$("#oetAPDShpCode").val(), ''],
                    'aAlwPdtType' : ['T1','T3','T4','T5','T6','S2','S3','S4']
                },
                cache: false,
                timeout: 5000,
                success: function (tResult) {
                    $("#odvModalDOCPDT").modal({backdrop: "static", keyboard: false});
                    $("#odvModalDOCPDT").modal({show: true});

                    // remove localstorage
                    localStorage.removeItem("LocalItemDataPDT");
                    $("#odvModalsectionBodyPDT").html(tResult);

                    if(JCNbAPDIsDocType('havePdt')){
                        $("#odvModalDOCPDT #oliBrowsePDTSupply").css('display','none');
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Function : ฟังก์ชั่นเซทข้อมูล ผู้จำหน่าย
    // Creator  : 10/03/2022 Wasin
    function JSxAPDSetPanelSupplierData(poParams){
        // Reset Panel เป็นค่าเริ่มต้น
        $("#ocmAPDXphVATInOrEx.selectpicker").val("1").selectpicker("refresh");
        $("#ocmAPDXphCshOrCrd.selectpicker").val("2").selectpicker("refresh");
        $('#oetAPDRefPICode').val(poParams.FTRefIntDocNo);
        $('#oetAPDRefPIName').val(poParams.FTRefIntDocNo);
        $("#oetAPDXphRefIntDate").val(poParams.FTRefIntDocDate).datepicker("refresh")
        
        // ประเภทภาษี
        if(poParams.FTSplStaVATInOrEx == 1){
            // รวมใน
            $("#ocmAPDXphVATInOrEx.selectpicker").val("1").selectpicker("refresh");
        }else{
            // แยกนอก
            $("#ocmAPDXphVATInOrEx.selectpicker").val("2").selectpicker("refresh");
        }

        // ประเภทชำระเงิน
        if(poParams.FCSplCrTerm != '' || poParams.FCSplCrTerm > 0){
            // เงินเชื่อ
            $("#ocmAPDXphCshOrCrd.selectpicker").val("2").selectpicker("refresh");
            $('.xCNPanel_CreditTerm').show();
        }else{
            // เงินสด
            $("#ocmAPDXphCshOrCrd.selectpicker").val("1").selectpicker("refresh");
            $('.xCNPanel_CreditTerm').hide();
            
        }
        
        //ผู้ขาย
        $("#oetAPDSplCode").val(poParams.FTSplCode);
        $("#oetAPDSplName").val(poParams.FTSplName);
        $("#oetAPDFrmSplNameShow").val(poParams.FTSplName);

        $("#oetAPDHDPcSplXphCrTerm").val(poParams.FCSplCrTerm);
        $("#oetAPDHDPcSplXphBillDue").val(poParams.FTBillDate).datepicker("refresh");
        $("#oetAPDHDPcSplXphTnfDate").val(poParams.FDReftdate).datepicker("refresh");
        $("#oetAPDHDPcSplXphDueDate").val(poParams.FDDueDate).datepicker("refresh");
        $("#oetAPDHDPcSplXphCtrName").val(poParams.FTCtrname);
        $("#oetAPDHDPcSplXphRefTnfID").val(poParams.FTReftno);
        $("#oetAPDHDPcSplXphRefVehID").val(poParams.FTVehid);

        if (poParams.FTBchCode != '' && poParams.FTBchName != '') {
            $("#oetAPDBchCode").val(poParams.FTBchCode);
            $("#oetAPDBchCode").val(poParams.FTBchCode);
            $("#oetAPDBchName").val(poParams.FTBchName);
        }

        if (poParams.FTWahCode != '' && poParams.FTWahName != '') {
            $("#ohdAPDWahCode").val(poParams.FTWahCode);
            $("#ohdAPDWahName").val(poParams.FTWahName);
            $("#oetAPDWahCode").val(poParams.FTWahCode);
            $("#oetAPDWahName").val(poParams.FTWahName);
        }
    }




    // ================================================= Start Event About Document Ref =================================================
    
    // Function :  โหลด Table อ้างอิงเอกสารทั้งหมด
    // Creator  : 10/03/2022 Wasin
    FSxAPDCallPageHDDocRef();
    function FSxAPDCallPageHDDocRef(){
        var tDocNo  = $('#oetAPDDocNo').val();
        $.ajax({
            type    : "POST",
            url     : "docAPDebitnotePageHDDocRef",
            data    : {
                'ptDocNo'   : tDocNo,
            },
            cache   : false,
            timeout : 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                    $('#odvAPDTableHDRef').html(aResult['tViewPageHDRef']);
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

    // Function :  Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    // Creator  : 10/03/2022 Wasin
    JSxAPDEventCheckShowHDDocRef();
    function JSxAPDEventCheckShowHDDocRef(){
        var tAPDRefType = $('#ocbAPDRefType').val();
        if( tAPDRefType == '1' ){
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();
        }else{
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }

    // Function : เคลียร์ค่า Doc Ref Form
    // Creator  : 10/03/2022 Wasin
    function JSxAPDEventClearValueInFormHDDocRef(){
        $('#oetAPDRefDocNo').val('');
        $('#oetAPDRefDocDate').val('');
        $('#oetAPDRefIntDoc').val('');
        $('#oetAPDDocRefIntName').val('');
        $('#oetAPDRefKey').val('');

        var tRefDoc = $('#oetAPDRefDoc').val();
        if (tRefDoc == 'PI') {
            $("#ocbAPDRefDoc").val("1").selectpicker('refresh');
        }else if (tRefDoc == 'TXO'){
            $("#ocbAPDRefDoc").val("2").selectpicker('refresh');  
        }else{
            $("#ocbAPDRefDoc").val("1").selectpicker('refresh');
        }
        
        $("#ocbAPDRefType").val("1").selectpicker('refresh');
    }

    // Function : กดเพิ่มเอกสารอ้างอิง (ภายใน ภายนอก)
    // Creator  : 10/03/2022 Wasin
    $('#obtAPDAddDocRef').off('click').on('click',function(){
        $('#ofmAPDFormAddDocRef').validate().destroy();
        JSxAPDEventClearValueInFormHDDocRef();
        $('#odvAPDModalAddDocRef').modal('show');
    });

    // Function : เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    // Creator  : 10/03/2022 Wasin
    $('#ocbAPDRefType').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        JSxAPDEventCheckShowHDDocRef();
    });

    // Function : Event Change Document Ref Type
    // Creator  : 10/03/2022 Wasin
    $('#ocbAPDRefDoc').off('change').on('change',function(){
        var tRefDoc = $('#ocbAPDRefDoc').val();
        if (tRefDoc == 1) {
            $('#oetAPDRefDoc').val('PI');
        }else{
            $('#oetAPDRefDoc').val('TXO');
        }
    });

    // Function : Event  Click Browse Document Ref
    // Creator  : 10/03/2022 Wasin
    $('#obtAPDBrowseRefDoc').on('click',function(){
        /*Check เปิดปิด Menu ตาม Pin*/
        JSxCheckPinMenuClose(); 
        var tDocType    = $('#ocbAPDRefDoc').val();
        var tRefKeyOld  = $('#ohdRefKeyOld').val();
        var tRefKeyNew  = $('#oetAPDRefDoc').val();
        if (tRefKeyOld != '' && tRefKeyNew != '') {
            if (tRefKeyOld != tRefKeyNew) {
                $('#ohdAPDTypeChange').val('Ref');
                $('#odvAPDModalChangeData #ospAPDTxtWarningAlert').text('<?php echo language('document/apdebitnote/apdebitnote', 'tAPDChangeDocType') ?>');
                $('#odvAPDModalChangeData').modal('show')
            }else{
                JSxCallAPDRefIntDoc(tDocType);
            }
        }else{
            JSxCallAPDRefIntDoc(tDocType);
        }
    });

    // Function : Browse เอกสารอ้างอิงภายใน
    // Creator  : 10/03/2022 Wasin
    function JSxCallAPDRefIntDoc(ptDocType){
        var tBCHCode    = $('#oetAPDBchCode').val()
        var tBCHName    = $('#oetAPDBchName').val()
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docAPDebitnoteCallRefIntDoc",
            data    : {
                'tDocType'  : ptDocType,
                'tBCHCode'  : tBCHCode,
                'tBCHName'  : tBCHName,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                JCNxCloseLoading();
                $('#odvAPDFromRefIntDoc').html(oResult);
                if (ptDocType == 1 ) {
                    $('#odvAPDModalRefIntDoc #olbTextModalHead').text('<?php echo language('document/apdebitnote/apdebitnote', 'tAPDRefIntDocPITital') ?>');
                } else {
                    $('#odvAPDModalRefIntDoc #olbTextModalHead').text('<?php echo language('document/apdebitnote/apdebitnote', 'tAPDRefIntDocTXOTital') ?>');
                }
                $('#odvAPDModalRefIntDoc').modal({
                    backdrop : 'static' , 
                    show : true
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function : Confirm Selete Ref Doc IN
    // Creator  : 10/03/2022 Wasin
    $('#obtConfirmRefDocInt').click(function(){
        var tDoctypeRef     = $('.xPurchaseInvoiceRefInt.active').data('doctype');
        var tRefIntDocNo    = $('.xPurchaseInvoiceRefInt.active').data('docno');
        var tRefIntDocDate  = $('.xPurchaseInvoiceRefInt.active').data('docdate');
        var tRefIntBchCode  = $('.xPurchaseInvoiceRefInt.active').data('bchcode');
        var tBchCode        = $('.xPurchaseInvoiceRefInt.active').data('bchcode');
        var tBchCodeto      = $('.xPurchaseInvoiceRefInt.active').data('bchcodeto');
        var tBchName        = $('.xPurchaseInvoiceRefInt.active').data('bchname');
        var tBchNameto      = $('.xPurchaseInvoiceRefInt.active').data('bchnameto');
        var aSeqNo          = $('.ocbRefIntDocDT:checked').map(function(elm){
            return $(this).val();
        }).get();
        if(tRefIntDocNo != undefined){
            var tSplCode            =  $('.xPurchaseInvoiceRefInt.active').data('splcode');
            var tSplName            =  $('.xPurchaseInvoiceRefInt.active').data('splname');
            var tVatCode            =  $('.xPurchaseInvoiceRefInt.active').data('vatcode');
            var tSplStaVATInOrEx    =  $('.xPurchaseInvoiceRefInt.active').data('vatinroex');
            var cVatRate            =  $('.xPurchaseInvoiceRefInt.active').data('tsppaid');
            var tSplTspPaid         =  $('.xPurchaseInvoiceRefInt.active').data('crlimit');
            var cSplCrLimit         =  $('.xPurchaseInvoiceRefInt.active').data('crtrem');
            var nSplCrTerm          =  $('.xPurchaseInvoiceRefInt.active').data('crlimit');

            //กำหนดค่าให้สาขาปลายทาง
            $('#oetAPDBchCode').val(tBchCode);
            $('#oetAPDBchCode').val(tBchCode);
            $('#oetAPDBchName').val(tBchName);
            $("#obtAPDBrowseBch").prop("disabled",true);

            // $("#obtAPDBrowseAgencyTo").prop("disabled",true);

            var poParams    = {
                FNSplCrTerm         : nSplCrTerm,
                FCSplCrLimit        : cSplCrLimit,
                FTSplStaVATInOrEx   : tSplStaVATInOrEx,
                FTSplTspPaid        : tSplTspPaid,
                FTSplCode           : tSplCode,
                FTSplName           : tSplName,
                FTVatCode           : tVatCode,
                FCVatRate           : cVatRate,
                FTBchCode           : tBchCode,
                FTBchName           : tBchName,
            };
            JSxAPDSetPanelSupplierData(poParams);

            // Set In Modal Input Browse Document Ref
            $('#oetAPDDocRefInt').val(tRefIntDocNo);
            $('#oetAPDDocRefIntName').val(tRefIntDocNo);
            $('#oetAPDRefDocDate').val(tRefIntDocDate).datepicker("refresh");

            if (tDoctypeRef == 1) {
                $('#oetAPDRefDoc').val('PI');
                $('#ohdAPDDocTypeRef').val('PI');
            }else{
                $('#oetAPDRefDoc').val('TXO');
                $('#ohdAPDDocTypeRef').val('TXO');
            }

            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docAPDebitnoteCallRefIntDocInsertDTToTemp",
                data: {
                    'tAPDDocNo'         : $('#oetAPDDocNo').val(),
                    'tAPDFrmBchCode'    : $('#oetAPDBchCode').val(),
                    'tAPDOptionAddPdt'  : $('#ocmAPDOptionAddPdt').val(),
                    'tRefIntDocNo'      : tRefIntDocNo,
                    'tRefIntBchCode'    : tRefIntBchCode,
                    'aSeqNo'            : aSeqNo,
                    'tDoctype'          : tDoctypeRef
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    JSvAPDLoadPdtDataTableHtml();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            $('#oetAPDRefIntDoc').val('');
            $('#oetAPDRefIntDocDate').val('').datepicker("refresh");
        }
    });

    // Function : กดยืนยันบันทึกลง Temp
    // Creator  : 10/03/2022 Wasin
    $('#ofmAPDFormAddDocRef').off('click').on('click',function(){
        $('#ofmAPDFormAddDocRef').validate().destroy();
        $('#ofmAPDFormAddDocRef').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetAPDRefDocNo  : {"required" : true}
            },
            messages: {
                oetAPDRefDocNo  : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
            },
            errorElement    : "em",
            errorPlacement  : function (error, element) {
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
                JCNxOpenLoading();
                if($('#ocbAPDRefType').val() == 1){ 
                    //อ้างอิงเอกสารภายใน
                    var tDocNoRef   = $('#oetAPDDocRefInt').val();
                }else{ 
                    //อ้างอิงเอกสารภายนอก
                    var tDocNoRef   = $('#oetAPDRefDocNo').val();
                }
                var tDocRefTypeIn = $('#oetAPDRefDoc').val();
                $.ajax({
                    type : "POST",
                    url  : "docAPDebitnoteEventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld' : $('#oetAPDRefDocNoOld').val(),
                        'ptAPDDocNo'    : $('#oetAPDDocNo').val(),
                        'ptRefType'     : $('#ocbAPDRefType').val(),
                        'ptRefDocNo'    : tDocNoRef,
                        'pdRefDocDate'  : $('#oetAPDRefDocDate').val(),
                        'ptRefKey'      : $('#oetAPDRefKey').val(),
                        'tDocRefTypeIn' : tDocRefTypeIn
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        JSxAPDEventClearValueInFormHDDocRef();
                        $('#odvAPDModalAddDocRef').modal('hide');
                        FSxAPDCallPageHDDocRef();
                        JCNxCloseLoading();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        });
    });



</script>