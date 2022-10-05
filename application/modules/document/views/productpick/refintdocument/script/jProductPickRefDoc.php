<script>
    $(document).ready(function() {

        $('.selectpicker').selectpicker('refresh');

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard: true,
            autoclose: true
        });

        $('#obtPCKBrowseBchRefIntDoc').click(function() {
            $('#odvPCKModalRefIntDoc').modal('hide');
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                window.oPCKBrowseBranchOption = undefined;
                oPCKBrowseBranchOption = oBranchOption_BranchOut({
                    'tReturnInputCode': 'oetPCKRefIntBchCode',
                    'tReturnInputName': 'oetPCKRefIntBchName',
                    'tNextFuncName': 'JSxPCKRefIntNextFunctBrowsBranch',
                    'tPCKAgnCode': '',
                    'aArgReturn': ['FTBchCode', 'FTBchName', 'FTWahCode', 'FTWahName'],
                });
                JCNxBrowseData('oPCKBrowseBranchOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtPCKBrowseRefExtDocDateFrm').unbind().click(function() {
            $('#oetPCKRefIntDocDateFrm').datepicker('show');
        });

        $('#obtPCKBrowseRefExtDocDateTo').unbind().click(function() {
            $('#oetPCKRefIntDocDateTo').datepicker('show');
        });

        JSxRefIntDocHDDataTable();
    });

    // ตัวแปร Option Browse Modal สาขา
    var oBranchOption_BranchOut = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tNextFuncName = poDataFnc.tNextFuncName;
        var tAgnCode = poDataFnc.tPCKAgnCode;
        var aArgReturn = poDataFnc.aArgReturn;

        tUsrLevel = "<?= $this->session->userdata('tSesUsrLevel') ?>";
        tBchMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if (tUsrLevel != "HQ") {
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN (" + tBchMulti + ")";
        } else {
            tSQLWhereBch = " AND TCNMBranch.FTBchType != 4 ";
        }

        if (tAgnCode != "") {
            tSQLWhereAgn = " AND TCNMBranch.FTAgnCode IN (" + tAgnCode + ")";
        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L', 'TCNMWaHouse_L'],
                On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                    'TCNMBranch.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMBranch.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID =' + nLangEdits,
                ]
            },
            Where: {
                Condition: [tSQLWhereBch, tSQLWhereAgn]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName', 'TCNMWaHouse_L.FTWahCode', 'TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [2, 3],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC'],
            },
            NextFunc: {
                FuncName: tNextFuncName
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
        };
        return oOptionReturn;
    }


    $('#odvPCKModalRefIntDoc').on('hidden.bs.modal', function() {
        $('#wrapper').css('overflow', 'auto');
        $('#odvPCKModalRefIntDoc').css('overflow', 'auto');

    });

    $('#odvPCKModalRefIntDoc').on('show.bs.modal', function() {
        $('#wrapper').css('overflow', 'hidden');
        $('#odvPCKModalRefIntDoc').css('overflow', 'auto');
    });

    function JSxPCKRefIntNextFunctBrowsBranch(ptData) {
        JSxCheckPinMenuClose();
        $('#odvPCKModalRefIntDoc').modal("show");
    }

    $('#obtRefIntDocFilter').on('click', function() {
        JSxRefIntDocHDDataTable();
    });

    function JSxRefIntDocHDDataTable(pnPage) {
        if (pnPage == '' || pnPage == null) {
            var pnNewPage = 1;
        } else {
            var pnNewPage = pnPage;
        }
        var nPageCurrent = pnNewPage;
        var tPCKRefIntBchCode = $('#oetPCKRefIntBchCode').val();
        var tPCKRefIntDocNo = $('#oetPCKRefIntDocNo').val();
        var tPCKRefIntDocDateFrm = $('#oetPCKRefIntDocDateFrm').val();
        var tPCKRefIntDocDateTo = $('#oetPCKRefIntDocDateTo').val();
        var tPCKRefIntStaDoc = $('#oetPCKRefIntStaDoc').val();
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docPCKRefIntDocDataTable",
            data: {
                'tPCKRefIntBchCode': tPCKRefIntBchCode,
                'tPCKRefIntDocNo': tPCKRefIntDocNo,
                'tPCKRefIntDocDateFrm': tPCKRefIntDocDateFrm,
                'tPCKRefIntDocDateTo': tPCKRefIntDocDateTo,
                'tPCKRefIntStaDoc': tPCKRefIntStaDoc,
                'nPCKRefIntPageCurrent': nPageCurrent,
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                $('#odvRefIntDocHDDataTable').html(oResult);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
</script>