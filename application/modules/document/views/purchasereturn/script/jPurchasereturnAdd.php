
<script type="text/javascript">
    nLangEdits  = '<?php echo $this->session->userdata("tLangEdit");?>';
    tUsrApv     = '<?php echo $this->session->userdata("tSesUsername");?>';

    // Disabled Enter in Form
    $(document).keypress(
        function(event){
            if (event.which == '13') {
                event.preventDefault();
            }
        }
    );

    //ค้นหาสินค้าใน temp
    function JSvPNDOCSearchPdtHTML() {
        var tValue = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbPNDOCPdtTable tbody tr").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(tValue) > -1);
        });
    }

    $(document).ready(function(){

        $('.xCNMenuplus').unbind().click(function(){
            if($(this).hasClass('collapsed')){
                $('.xCNMenuplus').removeClass('collapsed').addClass('collapsed');
                $('.xCNMenuPanelData').removeClass('in');
            }
        });

        if(JSbPNIsApv() || JSbPNIsStaDoc('cancel')){
            JSxCMNVisibleComponent('#obtPNCancel', false);
            JSxCMNVisibleComponent('#obtPNApprove', false);
            JSxCMNVisibleComponent('#odvBtnAddEdit .btn-group', false);
        }

        // console.log('JCNbPNIsDocType: ', JCNbPNIsDocType('havePdt'));
        if(JCNbPNIsUpdatePage()){
            // Doc No
            $("#oetPNDocNo").attr("readonly", true);
            $("#odvPNAutoGenDocNoForm input").attr("disabled", true);
            JSxCMNVisibleComponent('#odvPNAutoGenDocNoForm', false);

            JSxCMNVisibleComponent('#obtCardShiftOutBtnApv', true);
            JSxCMNVisibleComponent('#obtCardShiftOutBtnCancelApv', true);
            JSxCMNVisibleComponent('#obtCardShiftOutBtnDocMa', true);

            if(JCNbPNIsDocType('havePdt') && JSbPNGetStaApv() == '2'){
                JSoPNSubscribeMQ();
            }

            if(JSbPNIsStaDoc('cancel')){ // ปิดปุ่มพิมพ์เมื่อมีการยกเลิกเอกสาร
                JSxCMNVisibleComponent('#obtPNPrintDoc', false);
            }else{ // นอกนั้นให้เปิดปุ่ม
                JSxCMNVisibleComponent('#obtPNPrintDoc', true);
            }

        }

        if(JCNbPNIsCreatePage()){
            // Doc No
            $("#oetPNDocNo").attr("disabled", true);
            $('#ocbPNAutoGenCode').change(function(){
                if($('#ocbPNAutoGenCode').is(':checked')) {
                    $("#oetPNDocNo").attr("disabled", true);
                    $('#odvPNDocNoForm').removeClass('has-error');
                    $('#odvPNDocNoForm em').remove();
                }else{
                    $("#oetPNDocNo").attr("disabled", false);
                }
            });
            JSxCMNVisibleComponent('#odvPNAutoGenDocNoForm', true);

            JSxCMNVisibleComponent('#obtPNPrintDoc', false);
            JSxCMNVisibleComponent('#obtCardShiftOutBtnApv', false);
            JSxCMNVisibleComponent('#obtCardShiftOutBtnCancelApv', false);
            JSxCMNVisibleComponent('#obtCardShiftOutBtnDocMa', false);
        }

        // console.log('JStCMNUserLevel: ', JStCMNUserLevel());
        if(!(JSbPNIsApv() || JSbPNIsStaDoc('cancel'))){ // เอกสารยังไม่มีการอนุมัติ หรือ ไม่ถูกยกเลิกให้เริ่มการทำงานนี้
            // Condition control onload
            if(JStCMNUserLevel() == 'HQ'){
                // Init
                $('#obtPNBrowseMch').attr('disabled', false);
                $('#obtPNBrowseShp').attr('disabled', true);
                $('#obtPNBrowsePos').attr('disabled', true);
                $('#obtPNBrowseWah').attr('disabled', false);
            }

            if(JStCMNUserLevel() == 'BCH'){
                // Init
                // $('#obtPNBrowseBch').attr('disabled', true);
                $('#obtPNBrowseMch').attr('disabled', false);
                $('#obtPNBrowseShp').attr('disabled', true);
                $('#obtPNBrowsePos').attr('disabled', true);
                $('#obtPNBrowseWah').attr('disabled', false);
            }

            if(JStCMNUserLevel() == 'SHP'){
                // Init
                // console.log('SHP');
                // $('#obtPNBrowseBch').attr('disabled', true);
                $('#obtPNBrowseMch').attr('disabled', true);
                $('#obtPNBrowseShp').attr('disabled', true);
                $('#obtPNBrowsePos').attr('disabled', false);
                $('#obtPNBrowseWah').attr('disabled', true);
            }
        }
        $('#oliPNMngPdtScan').click(function(){

            var tSplCode = $('#oetPNSplCode').val();
            if(tSplCode === ''){
                var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
                FSvCMNSetMsgWarningDialog(tWarningMessage);
                return;
            }

            // Hide
            $('#oetPNSearchPdtHTML').hide();
            $('#oimPNMngPdtIconSearch').hide();
            // Show
            $('#oetPNScanPdtHTML').show();
            $('#oimPNMngPdtIconScan').show();
        });

        $('#oliPNMngPdtSearch').click(function(){
            // Hide
            $('#oetPNScanPdtHTML').hide();
            $('#oimPNMngPdtIconScan').hide();
            // Show
            $('#oetPNSearchPdtHTML').show();
            $('#oimPNMngPdtIconSearch').show();
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

    // สาขา
    $('#obtPNBrowseBch').click(function(){
        tUsrLevel   = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti   = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhere   = "";
        if(tUsrLevel != "HQ"){
            tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }

        JSxCheckPinMenuClose();
        tOldBchCkChange = $("#oetBchCode").val();
        nLangEdits = <?php echo $this->session->userdata("tLangEdit")?>;
        oPmhBrowseBch = {
            Title: ['company/branch/branch', 'tBCHTitle'],
            Table: {Master:'TCNMBranch', PK:'FTBchCode'},
            Join: {
                Table: ['TCNMBranch_L'],
                On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
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
            },
            CallBack:{
                ReturnType: 'S',
                Value: ["oetPNBchCode", "TCNMBranch.FTBchCode"],
                Text: ["oetPNBchName", "TCNMBranch_L.FTBchName"]
            },
            NextFunc:{
                FuncName: 'JSxPNCallbackAfterSelectBch',
                ArgReturn: ['FTBchCode', 'FTBchName']
            }
        };
        JCNxBrowseData('oPmhBrowseBch');
    });

    // กลุ่มร้านค้า
    $('#obtPNBrowseMch').click(function(){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        tOldMchCkChange = $("#oetMchCode").val();
        // Option merchant
        var tBch = $("#oetPNBchCode").val();
        if($("#oetPNBchCode").val()){
            tBch = $("#oetPNBchCode").val();
        }
        oPNBrowseMch = {
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
                Value: ["oetPNMchCode", "TCNMMerchant.FTMerCode"],
                Text: ["oetPNMchName", "TCNMMerchant_L.FTMerName"]
            },
            NextFunc:{
                FuncName:'JSxPNCallbackAfterSelectMer',
                ArgReturn:['FTMerCode', 'FTMerName']
            },
            BrowseLev: 1
            // DebugSQL : true
        };
        // Option merchant
        JCNxBrowseData('oPNBrowseMch');
    });

    // ร้านค้า
    $('#obtPNBrowseShp').click(function(){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        // Option Shop
        var tMch = $("#oetPNMchCode").val();
        var tBch = $("#oetPNBchCode").val();
        if($("#oetPNBchCode").val()){
            tBch = $("#oetPNBchCode").val();
        }

        oPNBrowseShp = {
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
                Value: ["oetPNShpCode", "TCNMShop.FTShpCode"],
                Text: ["oetPNShpName", "TCNMShop_L.FTShpName"]
            },
            NextFunc: {
                FuncName: 'JSxPNCallbackAfterSelectShp',
                ArgReturn: ['FTBchCode', 'FTShpCode', 'FTShpType', 'FTWahCode', 'FTWahName']
            },
            BrowseLev: 1,
            // DebugSQL : true
        };
        // Option Shop
        JCNxBrowseData('oPNBrowseShp');
    });

    // เครื่องจุดขาย
    $('#obtPNBrowsePos').click(function(){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        // Option Shop
        var tBch = $("#oetPNBchCode").val();
        if($("#oetPNBchCode").val()){
            tBch = $("#oetPNBchCode").val();
        }
        oPNBrowsePos = {
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
                        var tSQL = "AND TVDMPosShop.FTBchCode = '"+tBch+"' AND TVDMPosShop.FTShpCode = '"+$("#oetPNShpCode").val()+"'";
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
                Value: ["oetPNPosCode", "TVDMPosShop.FTPosCode"],
                Text: ["oetPNPosName", "TCNMPosLastNo.FTPosCode"]
            },
            NextFunc: {
                FuncName: 'JSxPNCallbackAfterSelectPos',
                ArgReturn: ['FTBchCode', 'FTShpCode', 'FTPosCode', 'FTWahCode', 'FTWahName']
            },
            BrowseLev: 1
        };
        // Option Shop
        JCNxBrowseData('oPNBrowsePos');
    });

    // คลังสินค้า
    $('#obtPNBrowseWah').click(function(){
        var tPNBchCode   =  $('#oetPNBchCode').val();

        JSxCheckPinMenuClose(); // Hidden Pin Menu
        // Option WareHouse
        oPNBrowseWah = {
            Title: ['company/warehouse/warehouse', 'tWAHTitle'],
            Table: { Master:'TCNMWaHouse', PK:'FTWahCode'},
            Join: {
                Table: ['TCNMWaHouse_L'],
                On:['TCNMWaHouse_L.FTWahCode = TCNMWaHouse.FTWahCode AND  TCNMWaHouse_L.FTBchCode = TCNMWaHouse.FTBchCode AND TCNMWaHouse_L.FNLngID = '+nLangEdits]
            },
            Where: {
                Condition: [
                    function(){
                        var tSQL = "AND TCNMWaHouse.FTBchCode = '"+tPNBchCode+"'";
                        if(($("#oetPNShpCode").val() == '') && ($("#oetPNPosCode").val() == '') ){ // Branch Wah
                            tSQL += " AND TCNMWaHouse.FTWahStaType IN (1,2,5)";
                        }

                        if( ($("#oetPNShpCode").val() != '') && ($("#oetPNPosCode").val() == '') ){ // Shop Wah
                            tSQL += " AND TCNMWaHouse.FTWahStaType IN (4)";
                            tSQL += " AND TCNMWaHouse.FTWahRefCode = '"+$('#oetPNShpCode').val()+"'";
                        }

                        if( ($("#oetPNShpCode").val() != '') && ($("#oetPNPosCode").val() != '') ){ // Pos(vending) Wah
                            tSQL += " AND TCNMWaHouse.FTWahStaType IN (6)";
                            tSQL += " AND TCNMWaHouse.FTWahRefCode = '"+$('#oetPNPosCode').val()+"'";
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
                Value: ["oetPNWahCode","TCNMWaHouse.FTWahCode"],
                Text: ["oetPNWahName","TCNMWaHouse_L.FTWahName"]
            },
            NextFunc:{
                FuncName: 'JSxPNCallbackAfterSelectWah',
                ArgReturn: []
            },
            RouteAddNew: 'warehouse',
            BrowseLev: nStaPNBrowseType
        };
        // Option WareHouse
        JCNxBrowseData('oPNBrowseWah');
    });

    // ผู้จำหน่าย
    $('#obtPNBrowseSpl').click(function(){
        var tCDNAgnCode = '<?=$this->session->userdata('tSesUsrAgnCode')?>';
        JSxCheckPinMenuClose();

        var tWhere = '';
        if(tCDNAgnCode != ''){
            tWhere += " AND ( TCNMSpl.FTAgnCode = '"+tCDNAgnCode+"' OR  ISNULL(TCNMSpl.FTAgnCode,'')=''  )  ";
        }

        oPNBrowseSpl = {
            Title: ['supplier/supplier/supplier', 'tSPLTitle'],
            Table: {Master:'TCNMSpl', PK:'FTSplCode'},
            Join: {
                Table: ['TCNMSpl_L', 'TCNMSplCredit' , 'VCN_VatActive'],
                On: [
                    'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
                    'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode',
                    'TCNMSpl.FTVatCode = VCN_VatActive.FTVatCode'
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
                Value: ["oetPNSplCode", "TCNMSpl.FTSplCode"],
                Text: ["oetPNSplName", "TCNMSpl_L.FTSplName"]
            },
            NextFunc:{
                FuncName:'JSxPNCallbackAfterSelectSpl',
                ArgReturn:['FNSplCrTerm', 'FCSplCrLimit', 'FTSplStaVATInOrEx', 'FTSplTspPaid', 'FTSplCode', 'FTSplName', 'FTVatCode' , 'FCVatRate']
            },
            RouteAddNew: 'supplier',
            BrowseLev: nStaPNBrowseType

        };
        // Option WareHouse
        JCNxBrowseData('oPNBrowseSpl');
    });

    $('#obtPNBrowseReason').click(function(){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        // Option WareHouse
        oPNBrowseReason = {
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
                    Value: ["oetPNReasonCode", "TCNMRsn.FTRsnCode"],
                    Text: ["oetPNReasonName", "TCNMRsn_L.FTRsnName"]
                },
                /*NextFunc:{
                    FuncName:'JSxCSTAddSetAreaCode',
                    ArgReturn:['FTRsnCode']
                },*/
                // RouteFrom : 'cardShiftChange',
                RouteAddNew : 'reason',
                BrowseLev : nStaPNBrowseType
        };
        // Option WareHouse
        JCNxBrowseData('oPNBrowseReason');
    });

    //หลังจากเลือกสาขา
    function JSxPNCallbackAfterSelectBch(poJsonData) {
        var bDataIsNull = poJsonData == 'NULL';
        if(JCNbPNIsDocType('havePdt') && !bDataIsNull && JSbPNHasRowInTemp()) {
            $('#odvPNPopupChangeSplConfirm').modal('show');
        }
    }

    //หลังจากเลือกกลุ่มธุรกิจ
    function JSxPNCallbackAfterSelectMer(poJsonData) {

        if (poJsonData != "NULL") {
            aData = JSON.parse(poJsonData);
            tAddBch = aData[0];
            tAddSeqNo = aData[1];
        }

        var tBchCode = $('#ohdPNBchCode').val();
        var tMchName = $('#oetPNMchName').val();
        var tShpName = $('#oetPNShpName').val();
        var tPosName = $('#oetPNPosName').val();
        var tWahName = $('#oetPNWahName').val();

        $('#obtPNBrowseShp').attr('disabled', true);
        $('#obtPNBrowsePos').attr('disabled', true);
        $('#obtPNBrowseWah').attr('disabled', true);

        if(JStCMNUserLevel() == 'HQ' || JStCMNUserLevel() == 'BCH'){
            if(tMchName != ''){
                $('#obtPNBrowseShp').attr('disabled', false);
                $('#obtPNBrowseWah').attr('disabled', true);
            }else{
                $('#obtPNBrowseWah').attr('disabled', false);
            }
            $('#oetPNShpCode, #oetPNShpName').val('');
            $('#oetPNPosCode, #oetPNPosName').val('');
            $('#oetPNWahCode, #oetPNWahName').val('');
        }
    }

    //หลังจากเลือกร้านค้า
    function JSxPNCallbackAfterSelectShp(poJsonData) {

        var aData, tResAddBch, tResAddSeqNo, tResWahCode, tResWahName;
        if (poJsonData != "NULL") {
            aData = JSON.parse(poJsonData);
            tResAddBch = aData[0];
            tResAddSeqNo = aData[1];
            tResWahCode = aData[3];
            tResWahName = aData[4];
        }else{
            $('#oetPNWahCode, #oetPNWahName').val('');
        }
        // console.log('aData: ', aData);
        $('#ohdPNWahCodeInShp').val(tResWahCode);
        $('#ohdPNWahNameInShp').val(tResWahName);
        var tBchCode = $('#ohdPNBchCode').val();
        var tMchName = $('#oetPNMchName').val();
        var tShpName = $('#oetPNShpName').val();
        var tPosName = $('#oetPNPosName').val();
        var tWahName = $('#oetPNWahName').val();

        $('#obtPNBrowsePos').attr('disabled', true);
        $('#obtPNBrowseWah').attr('disabled', false);

        if(JStCMNUserLevel() == 'HQ' || JStCMNUserLevel() == 'BCH'){
            if(tShpName != ''){
                $('#obtPNBrowsePos').attr('disabled', false);
                $('#obtPNBrowseWah').attr('disabled', true);
                $('#oetPNWahCode').val(tResWahCode);
                $('#oetPNWahName').val(tResWahName);
            }else{
                $('#oetPNWahCode, #oetPNWahName').val('');
            }
            $('#oetPNPosCode, #oetPNPosName').val('');
        }
    }

    //หลังจากเลือกจุดขาย
    function JSxPNCallbackAfterSelectPos(poJsonData) {
        var aData, tResAddBch, tResAddSeqNo, tResWahCode, tResWahName;
        if (poJsonData != "NULL") {
            aData = JSON.parse(poJsonData);
            tResAddBch = aData[0];
            tResAddSeqNo = aData[1];
            tResWahCode = aData[3];
            tResWahName = aData[4];
        }else{
            $('#oetPNPosCode, #oetPNPosName').val('');
            $('#oetPNWahCode').val($('#ohdPNWahCodeInShp').val());
            $('#oetPNWahName').val($('#ohdPNWahNameInShp').val());
            return;
        }
        // console.log('aData Pos: ', aData);

        var tBchCode = $('#ohdPNBchCode').val();
        var tMchName = $('#oetPNMchName').val();
        var tShpName = $('#oetPNShpName').val();
        var tPosName = $('#oetPNPosName').val();
        var tWahName = $('#oetPNWahName').val();

        $('#obtPNBrowseWah').attr('disabled', false);

        if(JStCMNUserLevel() == 'HQ' || JStCMNUserLevel() == 'BCH' || JStCMNUserLevel() == 'SHP'){
            if(tPosName != ''){
                $('#obtPNBrowseWah').attr('disabled', true);
                $('#oetPNWahCode').val(tResWahCode);
                $('#oetPNWahName').val(tResWahName);
            }
        }
    }

    //หลังจากเลือกคลังขาย
    function JSxPNCallbackAfterSelectWah(poJsonData) {
        var aData;
        if (poJsonData != "NULL") {
            aData = JSON.parse(poJsonData);
            tAddBch = aData[0];
            tAddSeqNo = aData[1];
        }

    }

    //หลังจากเลือกจุดขาย
    function JSxPNCallbackAfterSelectSpl(ptJsonData) {
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

            JSxPNSetPanelSpl(poParams);
        }
    }

    //ล้าง Temp เอกสารทั้งหมด
    function JSxPNClearTemp() {

        $('#odvPNPopupChangeSplConfirm').modal('hide');

        if(JCNbPNIsDocType('havePdt')) {
            $.ajax({
                type: "POST",
                url: "docPNClearTemp",
                data: {},
                cache: false,
                Timeout: 0,
                success: function (oResult) {
                    JSvPNLoadPdtDataTableHtml(1, true);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //เลือกผู้จำหน่าย
    function JSxPNSetPanelSpl(poParams) {

        // Reset
        $("#ocmPNXphVATInOrEx.selectpicker").val("1").selectpicker("refresh");
        $("#ocmPNXphCshOrCrd.selectpicker").val("2").selectpicker("refresh");
        $("#ocmPNHDPcSplXphDstPaid.selectpicker").val("1").selectpicker("refresh");
        $("#oetPNHDPcSplXphCrTerm").val("");

        // รหัสภาษีจากผู้จำหน่าย
        $("#ohdPNSplVatCode").val(poParams.FTVatCode);

        // ประเภทภาษี
        if(poParams.FTSplStaVATInOrEx === "1"){ // รวมใน
            $("#ocmPNXphVATInOrEx.selectpicker").val("1").selectpicker("refresh");
        }else{ // แยกนอก
            $("#ocmPNXphVATInOrEx.selectpicker").val("2").selectpicker("refresh");
        }
        // ประเภทชำระเงิน
        if(poParams.FCSplCrLimit > 0){ // เงินเชื่อ
            $("#ocmPNXphCshOrCrd.selectpicker").val("2").selectpicker("refresh");
        }else{ // เงินสด
            $("#ocmPNXphCshOrCrd.selectpicker").val("1").selectpicker("refresh");
        }
        // การชำระเงิน
        if(poParams.FTSplTspPaid === "1"){ // ต้นทาง
            $("#ocmPNHDPcSplXphDstPaid.selectpicker").val("1").selectpicker("refresh");
        }else{ // ปลายทาง
            $("#ocmPNHDPcSplXphDstPaid.selectpicker").val("2").selectpicker("refresh");
        }

        // ระยะเครดิต
        $("#oetPNHDPcSplXphCrTerm").val(poParams.FNSplCrTerm);

        // วงเงินเครดิต
        $('#oetPNHDPcSplCreditLimit').val(poParams.FCSplCrLimit);
        

        // Vat จาก SPL
        $('#ohdCNFrmSplVatCode').val(poParams.FTVatCode);
        $('#ohdCNFrmSplVatRate').val(poParams.FCVatRate);

        //เปลี่ยน VAT
        var tVatCode = poParams.FTVatCode;
        var tVatRate = poParams.FCVatRate;
        JSxChangeVatBySPL(tVatCode,tVatRate);
    }

    //ทุกครั้งที่เปลี่ยน SPL ต้องเกิดการคำนวณ VAT ใหม่ที่อยู่ในสินค้า
    function JSxChangeVatBySPL(tVatCode,tVatRate){
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docPNChangeSPLAffectNewVAT",
            data: {
                'tBCHCode'      : $('#oetPNBchCode').val(),
                'tCNDocNo'      : $("#oetPNDocNo").val(),
                'tVatCode'      : tVatCode,
                'tVatRate'      : tVatRate
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                console.log(oResult);
                var tDocType = $('#ohdPNDocType').val();
                if(tDocType != '7'){
                    JSvPNLoadPdtDataTableHtml(1,true)
                }else{
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //คำนวณท้ายบิล ใบลดหนี้ไม่มีสินค้า
    function JSoPNCalEndOfBillNonePdt(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tValue          = $('#oetPNNonePdtValue').val();
            var tVatCode        = $('#ohdPNSplVatCode').val();
            var tSplVatType     = $('#ocmPNXphVATInOrEx').val();
            $.ajax({
                type: "POST",
                url: "docPNCalEndOfBillNonePdt",
                data: {
                    tSplVatType     : tSplVatType,
                    tVatCode        : tVatCode,
                    tValue          : tValue
                },
                cache: false,
                Timeout: 0,
                success: function (oResult) {
                    alert
                    $('#ospPNCalEndOfBillNonePdt').text(JSON.stringify(oResult)); // เก็บยอดท้ายบิล ใบลดหนี้ไม่มีสินค้า

                    $('#oulPNListVatNonePdt').removeClass('xCNHide');
                    $('#odvPNTextBath').text(oResult['tTotalValueText']);
                    $('#oulPNListVatNonePdt #olbPNVatrate').text(oResult['tVatrateText']); // ภาษีมูลค่าเพิ่ม
                    $('#oulPNListVatNonePdt #oblPNSumVat').text(oResult['cVat']); // ยอดภาษี
                    $('#olbCrdditNoteVatSum').text(oResult['cVat']); // ยอดรวมภาษีมูลค่าเพิ่ม
                    $('#olbCrdditNoteSumFCXtdNet').text(oResult['tValue']); // จำนวนเงินรวม
                    $('#olbCrdditNoteSumFCXtdVat').text(oResult['cVat']); // ยอดรวมภาษีมูลค่าเพิ่ม
                    $('#olbCrdditNoteCalFCXphGrand').text(oResult['cTotalValue']); // จำนวนเงินรวมทั้งสิ้น
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        }else {
            JCNxShowMsgSessionExpired();
        }
    }

    function JSxPNAddPdtInRow(poJsonData){
        for (var n = 0; n < poJsonData.length; n++) {

            var tdVal = $('.nItem'+n).data('otrval')

            if((tdVal != '') && (typeof tdVal == 'undefined')){

                nTRID = JCNnRandomInteger(100, 1000000);

                var aColDatas = JSON.parse(poJsonData[n]);
                var tPdtCode = aColDatas[0];
                var tPunCode = aColDatas[1];
                FSvPNAddPdtIntoTableDT(tPdtCode, tPunCode);

            }
        }
    }

    //อนุมัติเอกสาร
    function JSbPNIsApv(){
        var bStatus = false;
        if(($("#ohdPNStaApv").val() == "1") || ($("#ohdPNStaApv").val() == "2")){
            bStatus = true;
        }
        return bStatus;
    }

    //ตรวจสอบสถานะเอกสาร
    function JSbPNGetStaApv(){
        return $("#ohdPNStaApv").val();
    }

    //ตรวจสอบสถานะเอกสาร
    function JSbPNIsStaPrcStk(){
        var bStatus = false;
        if($("#ohdPNAjhStaPrcStk").val() == "1"){
            bStatus = true;
        }
        return bStatus;
    }

    //เช็คสถานะเอกสาร
    function JSbPNIsStaDoc(ptStaType){
        var bStatus = false;
        if(ptStaType == "complete"){
            if($("#ohdPNStaDoc").val() == "1"){
                bStatus = true;
            }
            return bStatus;
        }
        if(ptStaType == "incomplete"){
            if($("#ohdPNStaDoc").val() == "2"){
                bStatus = true;
            }
            return bStatus;
        }
        if(ptStaType == "cancel"){
            if($("#ohdPNStaDoc").val() == "3"){
                bStatus = true;
            }
            return bStatus;
        }
        return bStatus;
    }

    var bUniquePNCode;
    $.validator.addMethod(
        "uniquePNCode",
        function(tValue, oElement, aParams) {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof nStaSession !== "undefined" && nStaSession == 1) {

                var tPNCode = tValue;
                $.ajax({
                    type: "POST",
                    url: "docPNUniqueValidate/docPNCode",
                    data: "tPNCode=" + tPNCode,
                    dataType:"html",
                    success: function(ptMsg)
                    {
                        // If vatrate and vat start exists, set response to true
                        bUniquePNCode = (ptMsg == 'true') ? false : true;
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Custom validate uniquePNCode: ', jqXHR, textStatus, errorThrown);
                    },
                    async: false
                });
                return bUniquePNCode;

            }else {
                JCNxShowMsgSessionExpired();
            }

        },
        "Credit Note Doc Code is Already Taken"
    );

    // Override Error Message
    jQuery.extend(jQuery.validator.messages, {
        required: "This field is required.",
        remote: "Please fix this field.",
        email: "Please enter a valid email address.",
        url: "Please enter a valid URL.",
        date: "Please enter a valid date.",
        dateISO: "Please enter a valid date (ISO).",
        number: "Please enter a valid number.",
        digits: "Please enter only digits.",
        creditcard: "Please enter a valid credit card number.",
        equalTo: "Please enter the same value again.",
        accept: "Please enter a value with a valid extension.",
        maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
        minlength: jQuery.validator.format("Please enter at least {0} characters."),
        rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
        range: jQuery.validator.format("Please enter a value between {0} and {1}."),
        max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
        min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
    });

    /*============================= End Custom Form Validate =====================*/

    //ตรวจสอบเงื่อนไขก่อนอนุมัติ
    function JSxValidateFormAddPN() {
        $('#ofmAddPN').validate({
            focusInvalid: false,
            onclick: false,
            onfocusout: false,
            onkeyup: false,
            rules: {
                oetPNDocNo: {
                    required: true,
                    maxlength: 20,
                    uniquePNCode: JCNbPNIsCreatePage()
                },
                obtPNDocDate: {
                    required: true
                },
                obtPNDocTime: {
                    required: true
                }
            },
            messages: {
                oetPNDocNo: {
                    "required": $('#oetPNDocNo').attr('data-validate-required')
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
                JSxPNAddUpdateAction();
            }
        });
    }

    //เพิ่ม หรือ แก้ไข
    function JSxPNAddUpdateAction(ptType = '') {

        var nStaSession = JCNxFuncChkSessionExpired();

        // โปรดเลือกผู้จำหน่ายก่อนทำรายการ
        var tSltSupplierMessage     = '<?=language('document/purchasereturn/purchasereturn','tSltSuppiler');?>';
        // โปรดเลือกคลังสินค้าก่อนทำรายการ
        var tSltWahourseMessage     = '<?=language('document/purchasereturn/purchasereturn','tSltWahourse');?>';
        // โปรดกรอกชื่อรายการ
        var tPlsFillName            = '<?=language('document/purchasereturn/purchasereturn','tPlsEnterName');?>';
        // โปรดกรอกจำนวนเงินรวม
        var tPlsFillAmt             = '<?=language('document/purchasereturn/purchasereturn','tPlsFillAmt');?>';
        // ไม่พบรายการสินค้าไม่สามารถดำเนินการต่อได้
        var tPdtNotfound            = '<?=language('document/purchasereturn/purchasereturn','tPdtNotfound');?>';


        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tSplCode = $('#oetPNSplCode').val();
            if(tSplCode === ''){
                var tWarningMessage = tSltSupplierMessage;
                FSvCMNSetMsgWarningDialog(tWarningMessage);
                return;
            }

            if(JCNbPNIsDocType('nonePdt')){
                var $tNonePdtName = $('#oetPNNonePdtName').val();
                var $tNonePdtValue = $('#oetPNNonePdtValue').val();

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

            if(JCNbPNIsDocType('havePdt')){

                var tDocType = $('#ohdPNDocType').val();
                var tWahCode = $('#oetPNWahCode').val();
                if(tDocType != '7'){
                    if(tWahCode === ''){
                        var tWarningMessage = tSltWahourseMessage;
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;
                    }
                }

                if(!JSbPNHasRowInTemp()){
                    var tPdtNotfound = tPdtNotfound;
                    FSvCMNSetMsgWarningDialog(tPdtNotfound);
                    return;
                }

            }


            var tNonePdtCode = $('#olbPNNonePdtCode').text();
            var tNonePdtName = $('#oetPNNonePdtName').val();
            var tCalEndOfBillNonePdt = $('#ospPNCalEndOfBillNonePdt').text();
            var tBchCode = $('#oetPNBchCode').val();
            $.ajax({
                type: "POST",
                url: '<?php echo $tRoute; ?>',
                data: $("#ofmAddPN").serialize()
                    + '&tPdtCode=' + tNonePdtCode
                    + '&tPdtName=' + tNonePdtName
                    + '&tCalEndOfBillNonePdt=' + tCalEndOfBillNonePdt,
                cache: false,
                timeout: 0,
                success: function (oResult) {
                    if (nStaPNBrowseType != 1){
                        if (oResult.nStaEvent == "1") {
                            var nPNDocNoCallBack    = oResult.tCodeReturn;
                            var oPNCallDataTableFile = {
                                ptElementID : 'odvPNShowDataTable',
                                ptBchCode   : tBchCode,
                                ptDocNo     : nPNDocNoCallBack,
                                ptDocKey    :'TAPTPnHD',
                            }
                            JCNxUPFInsertDataFile(oPNCallDataTableFile);

                            if(ptType == 'approve'){

                            }else{
                                if(oResult.nStaCallBack == "1" || oResult.nStaCallBack == null){
                                    JSvCallPagePNEdit(oResult.tCodeReturn);
                                    return;
                                }
                                if(oResult.nStaCallBack == "2"){
                                    JSvCallPagePNAdd();
                                    return;
                                }
                                if(oResult.nStaCallBack == "3"){
                                    JSvCallPagePNList();
                                    return;
                                }
                            }
                        }else {
                            var tMsgBody = oResult.tStaMessg;
                            FSvCMNSetMsgWarningDialog(tMsgBody);
                        }
                    }else {
                        JCNxBrowseData(tCallPNBackOption);
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

    //ตรวจสอบว่า ผู้จำหน่ายใช้ภาษีแบบไหน
    function JSxPNIsSplUseVatType(ptSplVatType) {
        var tSplVatType = $('#ocmPNXphVATInOrEx').val();
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

    //เพิ่มรายการสินค้าจากการแสกนบาร์โค๊ด
    function JSxPNAddPdtFromScanBarCodeToDTTemp(){
        if(JCNbPNIsDocType('havePdt')){
            var aPdtItems = [];
            var tPdtItems = JSON.stringify(aPdtItems);
            var tIsRefPI = '0';
            var tIsByScanBarCode = '1';
            FSvPDTAddPdtIntoTableDT(tPdtItems, tIsRefPI, tIsByScanBarCode);
        }
    }

    // Has Row in Temp
    function JSbPNHasRowInTemp(){
        var bStatus = false;
        var nTempRows = $('#ohdPNTempRows').val();
        if(nTempRows > 0){
            bStatus = true;
        }else{
            bStatus = false;
        }
        return bStatus;
    }

    //เปลี่ยนผู้จำหนาย
    function JSbPNChangeSplVatType(){

        if(JCNbPNIsDocType('nonePdt')){
            JSoPNCalEndOfBillNonePdt();
        }

        if(!JSbPNHasRowInTemp()){
            return;
        }else{
            JSvPNLoadPdtDataTableHtml(1, true);
        }
    }

    //พิมพ์
    function JSxPNPrintDoc(){
        let tGrandText  = $('#odvPNTextBath').text();
        let aInfor      = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tUserBchCode); ?>'},
            {"DocCode"      : '<?=@$tDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tUserBchCode;?>'},
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillPn?infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand="+tGrandText, '_blank');
    }

    //เลือกสินค้า
    function JCNvPNBrowsePdt() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tSplCode = $('#oetPNSplCode').val();
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
                    SPL             : [$('#oetPNSplCode').val(), ''],
                    BCH             : [$("#oetPNBchCode").val(), ''],
                    MER             : [$('#oetPNMchCode').val(), ''],
                    SHP             : [$("#oetPNShpCode").val(), ''],
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

                    if(JCNbPNIsDocType('havePdt')){
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

    $('#obtPNBrowseRefIntDoc').on('click',function(){
        JSxCheckPinMenuClose();
        JSxCallPurchasereturnRefIntDoc();
    });

    //อ้างอิงเอกสารภายใน
    function JSxCallPurchasereturnRefIntDoc(){
            var tPNTypeRef         = $('#ocmPNSelectBrowse').val();
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docPNCallRefIntDoc",
                data: {
                    'tBCHCode'      : $('#oetPNBchCode').val(),
                    'tBCHName'      : $('#oetPNBchName').val(),
                    'tPNTypeRef'    : tPNTypeRef,
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    JCNxCloseLoading();
                    $('#odvPNFromRefIntDoc').html(oResult);
                    $('#odvPNModalRefIntDoc').modal({backdrop : 'static' , show : true});
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
        var tBchCodeTo =  $('.xPurchaseInvoiceRefInt.active').data('bchcodeto');
        var tBchNameTo =  $('.xPurchaseInvoiceRefInt.active').data('bchnameto');
        var nCheckRefBrowse = $('#ocmPNSelectBrowse').val();
        var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm){
                    return $(this).val();
            }).get();
            if(tRefIntDocNo != undefined){
            var tSplCode =  $('.xPurchaseInvoiceRefInt.active').data('splcode');
            var tSplName =  $('.xPurchaseInvoiceRefInt.active').data('splname');
            var tVatCode =  $('.xPurchaseInvoiceRefInt.active').data('vatcode');
            var tSplStaVATInOrEx =  $('.xPurchaseInvoiceRefInt.active').data('vatinroex');
            var cVatRate =  $('.xPurchaseInvoiceRefInt.active').data('tsppaid');
            var tSplTspPaid =  $('.xPurchaseInvoiceRefInt.active').data('crlimit');
            var cSplCrLimit =  $('.xPurchaseInvoiceRefInt.active').data('crtrem');
            var nSplCrTerm =  $('.xPurchaseInvoiceRefInt.active').data('crlimit');
            
                var poParams = {
                        FNSplCrTerm         : nSplCrTerm,
                        FCSplCrLimit        : cSplCrLimit,
                        FTSplStaVATInOrEx   : tSplStaVATInOrEx,
                        FTSplTspPaid        : tSplTspPaid,
                        FTSplCode           : tSplCode,
                        FTSplName           : tSplName,
                        FTVatCode           : tVatCode,
                        FCVatRate           : cVatRate
                    };
                    // console.log(poParams);
                    // JSxPNSetPanelSupplierData(poParams);
                    JSxPNSetPanelSpl(poParams);
                    
            $('#oetPNSplCode').val(tSplCode);
            $('#oetPNSplName').val(tSplName);
            $('#oetPNRefPICode').val(tRefIntDocNo);
            $('#oetPNRefPIName').val(tRefIntDocNo);
            
            $('#oetPNXphRefIntDate').datepicker("setDate",tRefIntDocDate); 
            // $('#oetPNToBchCode').val(tBchCodeTo);
            // $('#oetPNToBchName').val(tBchNameTo);
            JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "docPNCallRefIntDocInsertDTToTemp",
                    data: {
                        'tPNDocNo'          : $('#oetPNDocNo').val(),
                        'tPNFrmBchCode'     : $('#oetPNBchCode').val(),
                        'tRefIntDocNo'      : tRefIntDocNo,
                        'tRefIntBchCode'    : tRefIntBchCode,
                        'aSeqNo'            : aSeqNo,
                        'nCheckRefBrowse'   : nCheckRefBrowse
                    },
                    cache: false,
                    Timeout: 0,
                    success: function (oResult){
                        JSvPNLoadPdtDataTableHtml(1,true);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }else{
                $('#oetPNRefPICode').val('');
                $('#oetPNRefPIName').val('');
                $('#oetPNXphRefIntDate').val('').datepicker("refresh");
            }

    });

    $('#ocmPNSelectBrowse').change(function(){
        if($(this).val() == '0'){
            var text = '<?=language('document/purchasereturn/purchasereturn','tPNRefRectPurchDoc');?>';
        }else{
            var text = '<?=language('document/purchasereturn/purchasereturn','tPNRefRectDODoc');?>';
        }
        $("#lbDocrefType").text(text);
        $("#labelmodalheadpn").text(text);
        $("#oetPNRefPIName").attr('placeholder',text);
    });
</script>
