<script type="text/javascript">
    var tUsrLevel = "<?= $this->session->userdata("tSesUsrLevel"); ?>";
    var tBchCodeMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
    var nCountBch = "<?= $this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits = "<?= $this->session->userdata("tLangEdit") ?>";
    var tWhere = "";

    if (nCountBch == 1) {
        $('#obtPreSvAdvSearchBrowseBchFrom').attr('disabled', true);
        $('#obtPreSvAdvSearchBrowseBchTo').attr('disabled', true);
    }
    if (tUsrLevel != "HQ") {
        tWhere = " AND TCNMBranch.FTBchCode IN (" + tBchCodeMulti + ") ";
    } else {
        tWhere = "";
    }

    // Option Branch
    var oPreSvBrowseBranch = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var oOptionReturn = {
            Title: ['company/branch/branch', 'tBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L'],
                On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits, ]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMBranch_L.FTBchName ASC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"],
            },
        }
        return oOptionReturn;
    };

    // Event Browse Branch From
    $('#obtPreSvAdvSearchBrowseBchFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPreSvBrowseBranchFromOption = oPreSvBrowseBranch({
                'tReturnInputCode': 'oetPreSvAdvSearchBchCodeFrom',
                'tReturnInputName': 'oetPreSvAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oPreSvBrowseBranchFromOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtPreSvAdvSearchBrowseBchTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPreSvBrowseBranchToOption = oPreSvBrowseBranch({
                'tReturnInputCode': 'oetPreSvAdvSearchBchCodeTo',
                'tReturnInputName': 'oetPreSvAdvSearchBchNameTo'
            });
            JCNxBrowseData('oPreSvBrowseBranchToOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $(document).ready(function() {
        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            enableOnReadonly: false,
            disableTouchKeyboard: true,
            autoclose: true
        });

        // Doc Date From
        $('#obtPreSvAdvSearchDocDateForm').unbind().click(function() {
            $('#oetPreSvAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtPreSvAdvSearchDocDateTo').unbind().click(function() {
            $('#oetPreSvAdvSearcDocDateTo').datepicker('show');
        });

    });

    // Advance search Display control
    $('#obtPreSvAdvanceSearch').unbind().click(function() {
        if ($('#odvPreSvAdvanceSearchContainer').hasClass('hidden')) {
            $('#odvPreSvAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        } else {
            $("#odvPreSvAdvanceSearchContainer").slideUp(500, function() {
                $(this).addClass('hidden');
            });
        }
    });

    $('#obtPreSvSearchReset').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxPreSvClearAdvSearchData();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxPreSvClearAdvSearchData() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            $('#ofmPreSvFromSerchAdv').find('input').val('');
            $('#oetPreSvSearchAllDocument').val('');
            $('#ofmPreSvFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvPreSvCallPageDataTable();
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page  ====================================================
    $('#oetPreSvSearchAllDocument').keyup(function(event) {
        var nCodeKey = event.which;
        if (nCodeKey == 13) {
            event.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSvPreSvCallPageDataTable();
            } else {
                JCNxShowMsgSessionExpired();
            }
        }
    });

    $('#obtPreSvSerchAllDocument').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JSvPreSvCallPageDataTable();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $("#obtPreSvAdvSearchSubmitForm").unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JSvPreSvCallPageDataTable();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // =================================================================================================================================================

    // Function: Call Page DataTable
    function JSvPreSvCallPageDataTable(pnPage) {
        var oAdvanceSearch = JSoPreSvGetAdvanceSearchData();
        var nPageCurrent = pnPage;
        if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        $.ajax({
            type: "POST",
            url: "docPreRepairResultDataTable",
            data: {
                nPageCurrent: nPageCurrent,
                oAdvanceSearch: oAdvanceSearch
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#ostPreSvDataTableDocument').html(aReturnData['tViewDataTable']);
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
    // รวม Values ต่างๆของการค้นหาขั้นสูง
    function JSoPreSvGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchAll: $("#oetPreSvSearchAllDocument").val(),
            tSearchBchCodeFrom: $("#oetPreSvAdvSearchBchCodeFrom").val(),
            tSearchBchCodeTo: $("#oetPreSvAdvSearchBchCodeTo").val(),
            tSearchDocDateFrom: $("#oetPreSvAdvSearcDocDateFrom").val(),
            tSearchDocDateTo: $("#oetPreSvAdvSearcDocDateTo").val(),
            tSearchStaDoc: $("#ocmPreSvAdvSearchStaDoc").val(),
            tSearchStaDocAct: $("#ocmStaDocAct").val()
        };
        return oAdvanceSearchData;
    }
</script>