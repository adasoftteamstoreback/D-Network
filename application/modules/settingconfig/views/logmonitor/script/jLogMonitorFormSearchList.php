<script type="text/javascript">
    var tUsrLevel = "<?= $this->session->userdata("tSesUsrLevel"); ?>";
    var tBchCodeMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
    var nCountBch = "<?= $this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits = "<?= $this->session->userdata("tLangEdit") ?>";
    var tWhere = "";
    if (nCountBch == 1) {
        $('#obtLOGAdvSearchBrowseBchFrom').attr('disabled', true);
        $('#obtLOGAdvSearchBrowseBchTo').attr('disabled', true);
    }
    if (tUsrLevel != "HQ") {
        tWhere = " AND TCNMBranch.FTBchCode IN (" + tBchCodeMulti + ") ";
    } else {
        tWhere = "";
    }
    $(document).ready(function() {
        $('#obtLOGSearchSync').addClass('disabled');
        $('.selectpicker').selectpicker();
        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            enableOnReadonly: false,
            disableTouchKeyboard: true,
            autoclose: true
        });

        // Doc Date From
        $('#obtLOGDateForm').unbind().click(function() {
            $('#oetLOGDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtLOGDateTo').unbind().click(function() {
            $('#oetLOGDateTo').datepicker('show');
        });

        // Advance search Display control
        $('#obtLOGAdvanceSearch').unbind().click(function() {
            if ($('#odvLOGAdvanceSearchContainer').hasClass('hidden')) {
                $('#odvLOGAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
            } else {
                $("#odvLOGAdvanceSearchContainer").slideUp(500, function() {
                    $(this).addClass('hidden');
                });
            }
        });
        // Clear Search Reset Filter
        $('#obtLOGSearchReset').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSxLOGClearAdvSearchData();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        //  Refresh Page
        $('#obtLOGSearchReFresh').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                // JSxLOGClearAdvSearchData();
                let pnPage = '';

                JSvLOGCallPageDataTable(pnPage);
                JSvLOGCallPageDataTableWebView(pnPage);

            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        //  Sync Log
        $('#obtLOGSearchSync').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
                if (aArrayConvert[0] == null || aArrayConvert[0] == '') {
                    alert('กรุณาเลือกข้อมูล');
                } else {
                    var tText = '';
                    var tTextCode = '';
                    for ($i = 0; $i < aArrayConvert[0].length; $i++) {
                        tText += aArrayConvert[0][$i].tName + '(' + aArrayConvert[0][$i].nCode + ') ';
                        tText += ' , ';

                        tTextCode += aArrayConvert[0][$i].nCode;
                        tTextCode += ' , ';

                    }
                    var aTexts = tTextCode.substring(0, tText.length - 2);
                    // var aDataSplit = aTexts.split(" , ");
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "monLogSendMQ",
                        data: {
                            tDataCode: aTexts
                        },
                        cache: false,
                        timeout: 0,
                        success: function(tResult) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['rtCode'] == 1) {
                                alert(aReturn['rtDesc'])
                            } else {
                                alert(aReturn['rtDesc'])
                            }
                            JSvCallPageLOGList('')
                            // if (tResult != "") {
                            //     $('#odvInforDataLog').html(tResult);
                            // }
                            // JSxLimNavDefult();
                            JCNxCloseLoading();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }

            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Option Branch Advance Filter 
        var oLOGBrowseBranch = function(poReturnInput) {
            var tAgnCode = $('#oetLOGAdvSearchAgnCodeFrom').val();
            var tWhereAgn = '';
            if (tAgnCode == '' || tAgnCode == null) {
                tWhereAgn = '';
            } else {
                tWhereAgn = " AND TCNMBranch.FTAgnCode = '" + tAgnCode + "'";
            }
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
                Where: {
                    Condition: [tWhere + tWhereAgn]
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
                }
            };
            return oOptionReturn;
        };

        //Option Agency
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
                NextFunc: {
                    FuncName: 'JSxNextFuncLOGAgn'
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

        //Option POS
        var oLOGBrowsePOS = function(poReturnInput) {
            var tInputReturnCode = poReturnInput.tReturnInputCode;
            var tInputReturnName = poReturnInput.tReturnInputName;
            var tLOGBchForm = $('#oetLOGAdvSearchBchCodeFrom').val();
            var tLOGBchTo = $('#oetLOGAdvSearchBchCodeTo').val();
            var tWherePOS = '';

            if (tLOGBchTo == '' && tLOGBchForm != '') {
                tLOGBchTo = tLOGBchForm;
            }

            if (tLOGBchForm != '' && tLOGBchTo != '') {
                var tWherePOS = 'AND ((TCNMPos.FTBchCode BETWEEN ' + tLOGBchForm + ' AND ' + tLOGBchTo + ') OR (TCNMPos.FTBchCode BETWEEN ' + tLOGBchTo + ' AND ' + tLOGBchForm + '))';
            } else {
                tWherePOS = '';
            }

            var oOptionReturn = {
                Title: ['settingconfig/backupandcleardata/backupandcleardata', 'tPOSTitle'],
                Table: {
                    Master: 'TCNMPos',
                    PK: 'FTPosCode'
                },
                Join: {
                    Table: ['TCNMPos_L', 'TCNMBranch_L'],
                    On: [
                        'TCNMPos_L.FTPosCode = TCNMPos.FTPosCode AND TCNMPos_L.FNLngID = ' + nLangEdits,
                        'TCNMBranch_L.FTBchCode = TCNMPos.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits
                    ]
                },
                Where: {
                    Condition: [tWherePOS]
                },
                GrideView: {
                    ColumnPathLang: 'settingconfig/backupandcleardata/backupandcleardata',
                    ColumnKeyLang: ['tPOSCode', 'tPOSName', 'tLOGTableBch'],
                    ColumnsSize: ['20%', '40%', '40%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMPos.FTPosCode', 'TCNMPos_L.FTPosName', 'TCNMBranch_L.FTBchName'],
                    DataColumnsFormat: ['', '', ''],
                    Perpage: 10,
                    OrderBy: ['TCNMPos.FDCreateOn DESC'],
                },
                CallBack: {
                    ReturnType: 'S',
                    Value: [tInputReturnCode, "TCNMPos.FTPosCode"],
                    Text: [tInputReturnName, "TCNMPos_L.FTPosName"],
                },
                debugSQL: true,
                BrowseLev: 1,
            }
            return oOptionReturn;
        }

        // Event Browse Branch From
        $('#obtLOGAdvSearchBrowseBchFrom').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSxCheckPinMenuClose();
                window.oLOGBrowseBranchFromOption = oLOGBrowseBranch({
                    'tReturnInputCode': 'oetLOGAdvSearchBchCodeFrom',
                    'tReturnInputName': 'oetLOGAdvSearchBchNameFrom'
                });
                JCNxBrowseData('oLOGBrowseBranchFromOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse Branch To
        $('#obtLOGAdvSearchBrowseBchTo').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                window.oLOGBrowseBranchToOption = oLOGBrowseBranch({
                    'tReturnInputCode': 'oetLOGAdvSearchBchCodeTo',
                    'tReturnInputName': 'oetLOGAdvSearchBchNameTo'
                });
                JCNxBrowseData('oLOGBrowseBranchToOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });
        // Event Click Document Date Form
        $('#obtLOGAdvSearchDocDateForm').unbind().click(function() {
            $('#oetLOGAdvSearcDocDateFrom').datepicker('show');
        });
        // Event Click Document Date To
        $('#obtLOGAdvSearchDocDateTo').unbind().click(function() {
            $('#oetLOGAdvSearcDocDateTo').datepicker('show');
        });
        // Event Key Up Input Text Search
        $('#oetLOGSearchAllDocument').keyup(function(event) {
            var nCodeKey = event.which;
            if (nCodeKey == 13) {
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                    JSvLOGCallPageDataTable();
                } else {
                    JCNxShowMsgSessionExpired();
                }
            }
        });

        //BrowseAgn
        $('#obtLOGAdvSearchBrowseAgn').click(function(e) {
            e.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSxCheckPinMenuClose();
                window.oDOBrowseAgencyOption = oBrowseAgn({
                    'tReturnInputCode': 'oetLOGAdvSearchAgnCodeFrom',
                    'tReturnInputName': 'oetLOGAdvSearchAgnNameFrom',
                });
                JCNxBrowseData('oDOBrowseAgencyOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse POS From
        $('#obtLOGAdvSearchBrowsePosFrom').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSxCheckPinMenuClose();
                window.oLOGBrowsePOSFromOption = oLOGBrowsePOS({
                    'tReturnInputCode': 'oetLOGAdvSearchPosCodeFrom',
                    'tReturnInputName': 'oetLOGAdvSearchPosNameFrom'
                });
                JCNxBrowseData('oLOGBrowsePOSFromOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse POS To
        $('#obtLOGAdvSearchBrowsePosTo').unbind().click(function() {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                window.oLOGBrowsePOSFromOption = oLOGBrowsePOS({
                    'tReturnInputCode': 'oetLOGAdvSearchPosCodeTo',
                    'tReturnInputName': 'oetLOGAdvSearchPosNameTo'
                });
                JCNxBrowseData('oLOGBrowsePOSFromOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Click Button Search All Document
        $('#obtLOGSerchAllDocument').unbind().click(function() {
            JCNxOpenLoading();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof nStaSession !== "undefined" && nStaSession == 1) {
                JSvLOGCallPageDataTable();
                JSvLOGCallPageDataTableWebView();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });
        // Event Click Submit Form Search Advance
        $("#obtLOGAdvSearchSubmitForm").unbind().click(function() {
            JCNxOpenLoading();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof nStaSession !== "undefined" && nStaSession == 1) {
                JSvLOGCallPageDataTable();
                JSvLOGCallPageDataTableWebView();
            } else {
                JCNxShowMsgSessionExpired();
            }
        });
    });
    // Function: ล้างค่า Input ทั้งหมดใน Advance Search
    // Create By: Wasin (Yoshi)
    // Create Date: 21/09/2021
    function JSxLOGClearAdvSearchData() {
        JCNxOpenLoading();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            $('#ofmLOGFromSerchAdv').find('input').val('');
            $('#oetLOGSearchAllDocument').val('');
            $('#ostLogMonitorFilterApplication').val(0).selectpicker("refresh");
            $('#ostLogMonitorFilterType').val(0).selectpicker("refresh");
            $('#ostLogMonitorFilterLevel').val(0).selectpicker("refresh");
            JSvLOGCallPageDataTable();
            JSvLOGCallPageDataTableWebView();
        } else {
            JCNxShowMsgSessionExpired();
        }
    }
    // Function: รวม Values ต่างๆของการค้นหาขั้นสูง
    // Create By: Wasin (Yoshi)
    // Create Date: 21/09/2021
    function JSoLOGGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchAll: $("#oetLOGSearchAllDocument").val(),
            tSearchGroupMonitor: $("#ostLogMonitorFilterGroup").val(),
            tSearchAgn: $("#oetLogMonitorAgnCode").val(),
            tSearchBch: $("#oetLogMonitorBchCode").val(),
            tSearchPos: $("#oetLogMonitorPosCode").val(),
            tSearchApp: $("#ostLogMonitorFilterApplication").val(),
            tSearchShift: $("#oetLogMonitorShiftCode").val(),
            tSearchMenu: $("#oetLogMonitorMenuCode").val(),
            tSearchType: $("#ostLogMonitorFilterType").val(),
            tSearchLevel: $("#ostLogMonitorFilterLevel").val(),
            tSearchUsr: $("#oetLogMonitorUsrCode").val(),
            tSearchDateFrom: $("#oetLOGDocDateFrom").val(),
            tSearchDateTo: $("#oetLOGDocDateTo").val(),
            tSearchTab: $("#oetLogMonitorTab").val()
        };
        return oAdvanceSearchData;
    }

    function JSxNextFuncLOGAgn() {
        $('#oetDOFrmBchCode').val('');
        $('#oetDOFrmBchName').val('');
        $('#oetDOFrmWahCode').val('');
        $('#oetDOFrmWahName').val('');
    }
</script>