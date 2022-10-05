<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js') ?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js') ?>"></script>

<script>
    JSvBACUGetAdvData();
    var nLangEdits = '<?php echo $this->session->userdata("tLangEdit"); ?>';
    var dCurrentDate = new Date();
    var tCurrentTime = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes();
    var tUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
    if (tUsrLevel != "HQ") {
        $('#oimSatSvBrowseAgn').attr("disabled", true);
    }


    $("#ocmBACUType").selectpicker("refresh");
    $("#ocmBACUServer").selectpicker("refresh");
    $("#ocmBACUGroup").selectpicker("refresh");

    // วันที่ประเมิน
    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard: true,
        autoclose: true
    });
    if ($('#oetSatDocTime').val() == '') {
        $('#oetSatDocTime').val(tCurrentTime);
    }

    if ($('#oetSatDocDate').val() == '') {
        $('#oetSatDocDate').datepicker("setDate", dCurrentDate);
    }

    if ($('#oetSatSvDate').val() == '') {
        $('#oetSatSvDate').datepicker("setDate", dCurrentDate);
    }

    // Log Date From
    $('#obtSearchDocDateFrom').unbind().click(function() {
        $('#oetSearchDocDateFrom').datepicker('show');
    });

    // Log Date To
    $('#obtSearchDocDateTo').unbind().click(function() {
        $('#oetSearchDocDateTo').datepicker('show');
    });

    $('#obtSatDocDate').unbind().click(function() {
        $('#oetSatDocDate').datepicker('show');
    });

    $('#obtSatSvDate').unbind().click(function() {
        $('#oetSatSvDate').datepicker('show');
    });

    $('#obtSatDocTime').unbind().click(function() {
        $('#oetSatDocTime').datetimepicker('show');
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    $('#ocbSatStaAutoGenCode').on('change', function(e) {
        if ($('#ocbSatStaAutoGenCode').is(':checked')) {
            $("#oetSatDocNo").val('');
            $("#oetSatDocNo").attr("readonly", true);
            $('#oetSatDocNo').closest(".form-group").css("cursor", "not-allowed");
            $('#oetSatDocNo').css("pointer-events", "none");
            $("#oetSatDocNo").attr("onfocus", "this.blur()");
            $('#ofmSatSurveyAddForm').removeClass('has-error');
            $('#ofmSatSurveyAddForm .form-group').closest('.form-group').removeClass("has-error");
            $('#ofmSatSurveyAddForm em').remove();
        } else {
            $('#oetSatDocNo').closest(".form-group").css("cursor", "");
            $('#oetSatDocNo').css("pointer-events", "");
            $('#oetSatDocNo').attr('readonly', false);
            $("#oetSatDocNo").removeAttr("onfocus");
        }
    });

    // Checkall ตอนเริ่มเสมอ
    $('#ocmCENCheckDeleteAll').trigger('click');

    //end

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    // Event Onclick Browse Agency
    $('#obtBACUAdvSearchBrowseAgn').click(function() {
        JSxCheckPinMenuClose();
        window.oBrowseAgencyOption = undefined;
        oBrowseAgencyOption = oBrowseAgency({
            'tReturnCode': 'oetBACUAdvSearchAgnCode',
            'tReturnName': 'oetBACUAdvSearchAgnName'
        });
        JCNxBrowseData('oBrowseAgencyOption');
    });

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    // Browse Agency
    var oBrowseAgency = function(poDataFnc) {
        var tReturnCode = poDataFnc.tReturnCode;
        var tReturnName = poDataFnc.tReturnName;
        var oOptionReturn = {
            Title: ['ticket/agency/agency', 'tAggTitle'],
            Table: {
                Master: 'TCNMAgency',
                PK: 'FTAgnCode'
            },
            Join: {
                Table: ['TCNMAgency_L'],
                On: [
                    'TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits
                ]
            },
            GrideView: {
                ColumnPathLang: 'ticket/agency/agency',
                ColumnKeyLang: ['tAggCode', 'tAggName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMAgency.FDCreateOn'],
                SourceOrder: "DESC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tReturnCode, "TCNMAgency.FTAgnCode"],
                Text: [tReturnName, "TCNMAgency_L.FTAgnName"],
            },
        }
        return oOptionReturn;
    }

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    // Event Onclick Browse Agency
    $('#obtBACUHistoryAdvSearchBrowseAgn').click(function() {
        JSxCheckPinMenuClose();
        window.oBrowseAgencyOption = undefined;
        oBrowseAgencyOption = oBrowseAgency({
            'tReturnCode': 'oetBACUHisAdvSearchAgnCodeFrom',
            'tReturnName': 'oetBACUHisAdvSearchAgnNameFrom'
        });
        JCNxBrowseData('oBrowseAgencyOption');
    });

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    // Browse Agency
    var oBrowseAgency = function(poDataFnc) {
        var tReturnCode = poDataFnc.tReturnCode;
        var tReturnName = poDataFnc.tReturnName;
        var oOptionReturn = {
            Title: ['ticket/agency/agency', 'tAggTitle'],
            Table: {
                Master: 'TCNMAgency',
                PK: 'FTAgnCode'
            },
            Join: {
                Table: ['TCNMAgency_L'],
                On: [
                    'TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits
                ]
            },
            GrideView: {
                ColumnPathLang: 'ticket/agency/agency',
                ColumnKeyLang: ['tAggCode', 'tAggName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMAgency.FDCreateOn'],
                SourceOrder: "DESC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tReturnCode, "TCNMAgency.FTAgnCode"],
                Text: [tReturnName, "TCNMAgency_L.FTAgnName"],
            },
        }
        return oOptionReturn;
    }

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    // กำหนดมีได้หลาย สาขา (Multi-select boxes)
    $('#oimBACUBrowseBranch').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBchOption = undefined;
            oBchOption = oBrowseBranch({
                'tReturnInputBranchCode': 'oetBACUBranchCode',
                'tReturnInputBranchName': 'oetBACUBranchName',
                'tNextFuncName': 'JSxConsNextFuncBrowseUsrBranch',
                'aArgReturn': ['FTBchCode', 'FTBchName'] //,'FTMerCode','FTMerName'
            });
            JCNxBrowseMultiSelect('oBchOption');
            // JCNxBrowseData('oBchOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    // Option Browse Branch
    var oBrowseBranch = function(poReturnInputBranch) {
        let tInputReturnBranchCode = poReturnInputBranch.tReturnInputBranchCode;
        let tInputReturnBranchName = poReturnInputBranch.tReturnInputBranchName;
        let tBranchNextFunc = poReturnInputBranch.tNextFuncName;
        let aBranchArgReturn = poReturnInputBranch.aArgReturn;

        let tSesUsrBchCodeMulti = "<?= $this->session->userdata('tSesUsrBchCodeMulti') ?>";
        let tSesUsrLevel = "<?= $this->session->userdata('tSesUsrLevel') ?>";
        let tWhereCondiotion = "";

        if (tSesUsrLevel != "HQ") {
            tWhereCondiotion = " AND TCNMBranch.FTBchCode IN (" + tSesUsrBchCodeMulti + ") ";
        }

        let oBranchOptionReturn = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L'], //,'TCNMMerchant_L'
                On: [
                    'TCNMBranch.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits
                    // 'TCNMBranch.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits
                ]
            },
            Where: {
                Condition: [tWhereCondiotion]
            },
            Filter: {
                Selector: 'oetUsrAgnCode',
                Table: 'TCNMBranch',
                Key: 'FTAgnCode'
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'], //,'TCNMBranch.FTMerCode','TCNMMerchant_L.FTMerName'
                DataColumnsFormat: ['', ''],
                // DisabledColumns	: [2,3],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC'],
            },
            NextFunc: {
                FuncName: tBranchNextFunc,
                ArgReturn: aBranchArgReturn
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnBranchCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnBranchName, "TCNMBranch_L.FTBchName"]
            },
        };
        return oBranchOptionReturn;
    }

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    // Nextfunch Browser Branch
    function JSxConsNextFuncBrowseUsrBranch(poDataNextFunc) {
        $('#odvBACUBranchShow').html('');
        if (typeof(poDataNextFunc[0]) != 'undefined' && poDataNextFunc[0] != null) { //poDataNextFunc[0] != "NULL"
            var tHtml = '';
            var tBchCodeStr = '';
            var ncountbch = 0;
            for ($i = 0; $i < poDataNextFunc.length; $i++) {
                var aText = JSON.parse(poDataNextFunc[$i]);
                tHtml += '<span class="label label-info m-r-5 xWCheckBACUBch">' + aText[1] + '</span>';
                ncountbch += 1;
            }

            var tNumChk = $(".xWCheckBACUBch").length;

            var tDataNumChk = tNumChk;

            // กรณีเลือก 1 สาขา
            if (ncountbch == 1) {
                $('#oimBACUBrowsePos').prop('disabled', false);
                $('#odvBACUPosCode').show();
            } else {
                $('#oimBACUBrowsePos').prop('disabled', true);
                $("#oetBACUPosName").val('');
                $("#oetBACUPosCode").val('');
                $("#odvBACUPosShow").empty();
                $('#odvBACUPosCode').hide();
            }
            $('#odvBACUBranchShow').html(tHtml);

        } else {

            // $('#obtUsrBrowseMerchant').prop('disabled',true);
            $('#oimBrowseShop').prop('disabled', true);
        }

        $('#oetUsrMerCode').val('');
        $('#oetUsrMerName').val('');

        $('#oetRoleCode').val('');
        $('#oetRoleName').val('');
        $('#odvUsrRoleShow').html('');

        $('#oetShopName').val('');
        $('#oetShopCode').val('');
        $('#odvShopShow').html('');

    }

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    // กำหนดมีได้หลาย จุดขาย (Multi-select boxes)
    $('#oimBACUBrowsePos').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPosOption = undefined;
            oPosOption = oBrowsePos({
                'tReturnInputPosCode': 'oetBACUPosCode',
                'tReturnInputPosName': 'oetBACUPosName',
                'tNextFuncName': 'JSxConsNextFuncBrowsePos',
                'aArgReturn': ['FTPosCode', 'FTPosName'] //,'FTMerCode','FTMerName'
            });
            JCNxBrowseMultiSelect('oPosOption');
            // JCNxBrowseData('oPosOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    // Option Browse Pos
    var oBrowsePos = function(poReturnInputPos) {
        let tInputReturnPosCode = poReturnInputPos.tReturnInputPosCode;
        let tInputReturnPosName = poReturnInputPos.tReturnInputPosName;
        let tBranchNextFunc = poReturnInputPos.tNextFuncName;
        let aBranchArgReturn = poReturnInputPos.aArgReturn;
        let tbchcode = $("#oetBACUBranchCode").val();

        let tSesUsrBchCodeMulti = "<?= $this->session->userdata('tSesUsrBchCodeMulti') ?>";
        let tSesUsrLevel = "<?= $this->session->userdata('tSesUsrLevel') ?>";
        let tWhereCondiotion = "";

        if (tSesUsrLevel != "HQ") {
            tWhereCondiotion = " AND TCNMBranch.FTBchCode IN (" + tSesUsrBchCodeMulti + ") ";
        }

        if (tbchcode != '') {
            tWhereCondiotion = " AND TCNMPos.FTBchCode IN (" + tbchcode + ") ";
        }

        let oBranchOptionReturn = {
            Title: ["pos/posshop/posshop", "tPshTitle"],
            Table: {
                Master: 'TCNMPos',
                PK: 'FTPosCode'
            },
            Join: {
                Table: ['TCNMPos_L'], //,'TCNMMerchant_L'
                On: [
                    'TCNMPos.FTPosCode = TCNMPos_L.FTPosCode AND TCNMPos.FTBchCode = TCNMPos_L.FTBchCode AND TCNMPos_L.FNLngID = ' + nLangEdits
                    // 'TCNMBranch.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits
                ]
            },
            Where: {
                Condition: [tWhereCondiotion]
            },
            GrideView: {
                ColumnPathLang: 'pos/posshop/posshop',
                ColumnKeyLang: ['tPshBRWShopTBCode', 'tPshTBPosCode'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMPos.FTPosCode', 'TCNMPos_L.FTPosName'], //,'TCNMBranch.FTMerCode','TCNMMerchant_L.FTMerName'
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMPos.FTPosCode DESC'],
            },
            NextFunc: {
                FuncName: tBranchNextFunc,
                ArgReturn: aBranchArgReturn
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnPosCode, "TCNMPos.FTPosCode"],
                Text: [tInputReturnPosName, "TCNMPos_L.FTPosName"]
            },
        };
        return oBranchOptionReturn;
    }

    // Create By Sittikorn 23/05/2022
    // Last Updated By Sittikorn 23/05/2022
    // Nextfunch Browser Pos
    function JSxConsNextFuncBrowsePos(poDataNextFunc) {
        $('#odvBACUPosShow').html('');
        if (typeof(poDataNextFunc[0]) != 'undefined' && poDataNextFunc[0] != null) { //poDataNextFunc[0] != "NULL"
            var tHtml = '';
            var tBchCodeStr = '';
            for ($i = 0; $i < poDataNextFunc.length; $i++) {
                var aText = JSON.parse(poDataNextFunc[$i]);
                tHtml += '<span class="label label-info m-r-5">' + aText[1] + '</span>';

            }

            var tNumChk = $("input[type=checkbox]:checked").length;

            var tDataNumChk = tNumChk - 1;


            // กรณีเลือก 1 สาขา
            if (tDataNumChk == 1) {
                $('#oimBrowseShop').prop('disabled', false);
            } else {
                $('#oimBrowseShop').prop('disabled', true);
            }
            $('#odvBACUPosShow').html(tHtml);

        } else {

            // $('#obtUsrBrowseMerchant').prop('disabled',true);
            $('#oimBrowseShop').prop('disabled', true);
        }

        $('#oetUsrMerCode').val('');
        $('#oetUsrMerName').val('');

        $('#oetRoleCode').val('');
        $('#oetRoleName').val('');
        $('#odvUsrRoleShow').html('');

        $('#oetShopName').val('');
        $('#oetShopCode').val('');
        $('#odvShopShow').html('');

    }

    //กดแท็บ ประวัติ
    $('#oliBACUHistory').unbind().click(function() {
        JSvBACUGetAdvHistoryData();
        $('#obtBACUPurgeApprove').hide();
    });

    //กดแท็บ ล้างข้อมูล
    $('#oliBACUDataPurge').unbind().click(function() {
        $('#obtBACUPurgeApprove').show();
    });

    // Advance search Display control
    $('#obtBACUAdvSearchSubmitForm').unbind().click(function() {
        JSvBACUGetAdvHistoryData();
    });

    // Advance search Display control
    $('#obtBACUAdvanceSearch').unbind().click(function() {
        if ($('#odvBACUAdvanceSearchContainer').hasClass('hidden')) {
            $('#odvBACUAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        } else {
            $("#odvBACUAdvanceSearchContainer").slideUp(500, function() {
                $(this).addClass('hidden');
            });
        }
    });
    // Event Key Up Input Text Search
    $('#oetBACUSearchAllDocument').keyup(function(event) {
        var nCodeKey = event.which;
        if (nCodeKey == 13) {
            event.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSvBACUGetAdvHistoryData();
            } else {
                JCNxShowMsgSessionExpired();
            }
        }
    });

    //เข้ามาหน้าการสำรองและการล้างข้อมูล Lst
    function JSvBACUGetAdvHistoryData() {
        JCNxOpenLoading();
        var aSearchAll = JSoBACUGetAdvanceSearchDataHistory();

        $.ajax({
            type: "POST",
            url: "docBackupCleanupGetHistoryData",
            data: {
                aSearchAll: aSearchAll,
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#ostBACUHistoryDataTableDocument').html(tResult);
                }
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Clear Search Reset Filter
    $('#obtBACUSearchReset').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxBACUHisClearAdvSearchData();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Function: ล้างค่า Input ทั้งหมดใน Advance Search
    // Create By: Wasin (Yoshi)
    // Create Date: 21/09/2021
    function JSxBACUHisClearAdvSearchData() {
        JCNxOpenLoading();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            $('#oetBACUSearchAllDocument').val('');
            $('#oetSearchDocDateFrom').val('');
            $('#oetSearchDocDateTo').val('');
            $('#oetBACUHisAdvSearchBchCodeFrom').val('');
            $('#oetBACUHisAdvSearchBchNameFrom').val('');
            $('#oetBACUHisAdvSearchBchCodeTo').val('');
            $('#oetBACUHisAdvSearchBchNameTo').val('');
            $('#oetBACUHisAdvSearchAgnCodeFrom').val('');
            $('#oetBACUHisAdvSearchAgnNameFrom').val('');
            JSvBACUGetAdvHistoryData();
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    // Function: รวม Values ต่างๆของการค้นหาขั้นสูง
    // Create By: Off
    // Create Date: 06/09/2022
    function JSoBACUGetAdvanceSearchDataHistory() {
        var oAdvanceSearchData = {
            tSearchAll: $("#oetBACUSearchAllDocument").val(),
            tSearchType: $("#ocmBACUType").val(),
            tSearchBchCodeFrom: $("#oetBACUHisAdvSearchBchCodeFrom").val(),
            tSearchBchCodeTo: $("#oetBACUHisAdvSearchBchCodeTo").val(),
            tSearchDocDateFrom: $("#oetSearchDocDateFrom").val(),
            tSearchDocDateTo: $("#oetSearchDocDateTo").val(),
            tSearchGroup: $("#ocmBACUServer").val(),
            tSearchAgnCode: $("#oetBACUHisAdvSearchAgnCodeFrom").val(),
            tSearchPrgType: $("#ocmBACUGroup").val()
        };
        return oAdvanceSearchData;
    }

    // Event Click Button Search All Document
    $('#obtBACUSerchAllDocument').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JSvBACUGetAdvHistoryData();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //เข้ามาหน้าการสำรองและการล้างข้อมูล Lst
    function JSvBACUGetAdvData() {
        var aSearchAll = JSoBACUGetAdvanceSearchData();

        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docBackupCleanupGetData",
            data: {
                aSearchAll: aSearchAll,
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#otbBACUDataTable').html(tResult);
                }
                var nChkBACYType = $("#ocmBACUType").val();
                if (nChkBACYType == '1') {
                    $("input[type=checkbox]").attr("disabled", true);
                    // $("span .ospListItem").css("cursor: not-allowed !important");
                } else {
                    $("input[type=checkbox]").attr("disabled", false);
                }
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function: รวม Values ต่างๆของการค้นหาขั้นสูง
    // Create By: Off
    // Create Date: 06/09/2022
    function JSoBACUGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchType: $("#ocmBACUType").val(),
            tSearchGroup: $("#ocmBACUServer").val(),
            tSearchAgnCode: $("#oetBACUAdvSearchAgnCode").val(),
            tSearchPrgType: $("#ocmBACUGroup").val()
        };
        return oAdvanceSearchData;
    }

    // Event Click Appove Document
    $('#obtBACUPurgeApprove').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            var nChkPurge = 0;
            $('.ocbListItem:checked').each(function() {
                if (this.checked) {
                    nChkPurge = 1;
                    return false;
                }
            });
            if ($("#ocmBACUType").val() == '1') {
                nChkPurge = 1;
            }

            if (nChkPurge != 0) {
                JSxBACUApproveDocument(false);
            } else {
                FSvCMNSetMsgErrorDialog('กรุณาเลือกข้อมูลที่จะ Purge');
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ยืนยัน Purge ข้อมูล
    function JSxBACUApproveDocument(pbIsConfirm) {
        try {
            if (pbIsConfirm) {
                $("#odvBACUModalAppoveDoc").modal('hide');
                var tAgnCode = $('#oetBACUAdvSearchAgnCode').val();
                var tBchCode = $('#oetBACUBranchCode').val();
                var tPosCode = $('#oetBACUPosCode').val();
                var tBACUType = $('#ocmBACUType').val();

                var aNewPurge = [];
                if ($("#ocmBACUType").val() == '1') {
                    aNewPurge.push({
                        ptAgnCode: tAgnCode,
                        pnPrgDocType: '',
                        ptPrgTblHD: ''
                    });
                } else {
                    $('.ocbListItem:checked').each(function() {
                        if (this.checked) {
                            var pnPrgDocType = $(this).parent().parent().parent().data('doctype');
                            var tGetTable = $(this).parent().parent().parent().data('tplhd');
                            var myArray = [{
                                "pnPrgDocType": pnPrgDocType,
                                "ptPrgTblHD": tGetTable
                            }];

                            aNewPurge.push({
                                ptAgnCode: tAgnCode,
                                pnPrgDocType: pnPrgDocType,
                                ptPrgTblHD: tGetTable
                            });
                        }
                    });
                }
                console.log(aNewPurge);

                $.ajax({
                    type: "POST",
                    url: "docBackupCleanupPurgeData",
                    data: {
                        tBACUType: tBACUType,
                        tPosCode: tPosCode,
                        tBchCode: tBchCode,
                        tAgnCode: tAgnCode,
                        aNewPurge: aNewPurge
                    },
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        $("#odvBACUModalAppoveDoc").modal("hide");
                        $('.modal-backdrop').remove();
                        var aReturnData = JSON.parse(tResult);
                        // console.log(aReturnData);
                        if (aReturnData['nStaEvent'] == '1') {
                            $('#odvBACUPurgeSuccess').modal('show');
                            // JSoBACUCallSubscribeMQ();
                        } else {
                            var tMessageError = aReturnData['tStaMessg'];
                            FSvCMNSetMsgErrorDialog(tMessageError);
                            JCNxCloseLoading();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                $("#odvBACUModalAppoveDoc").modal('show');
            }
        } catch (err) {
            console.log("JSxDOApproveDocument Error: ", err);
        }
    }

    //เมื่อกดปุ่มยืนยันให้วิ่งไปที่หน้า ประวัติ
    $('#obtBACUModalMsgConfirm').off('click');
    $('#obtBACUModalMsgConfirm').on('click', function() {
        $('#BACUHistory').click();
    });

    // Rabbit MQ
    function JSoBACUCallSubscribeMQ() {
        // Document variable
        var tLangCode = '';
        var tUsrBchCode = '';
        var tUsrApv = '<?= $this->session->userdata('tSesUsername') ?>';
        var tDocNo = '';
        var tPrefix = "CN_QTask";
        var tStaApv = '';
        var tStaDelMQ = 1;
        var tQName = tPrefix;

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

    // Event Browse Branch From
    $('#obtBACUHisAdvSearchBrowseBchFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBACUHisBrowseBranchFromOption = oBACUHisBrowseBranch({
                'tReturnInputCode': 'oetBACUHisAdvSearchBchCodeFrom',
                'tReturnInputName': 'oetBACUHisAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oBACUHisBrowseBranchFromOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch From
    $('#obtBACUHisAdvSearchBrowseBchTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oBACUHisBrowseBranchFromOption = oBACUHisBrowseBranch({
                'tReturnInputCode': 'oetBACUHisAdvSearchBchCodeTo',
                'tReturnInputName': 'oetBACUHisAdvSearchBchNameTo'
            });
            JCNxBrowseData('oBACUHisBrowseBranchFromOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Option Branch
    var oBACUHisBrowseBranch = function(poReturnInput) {
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
                Perpage: 20,
                OrderBy: ['TCNMBranch_L.FTBchName ASC'],
            },
            // Where   : {
            //     Condition : [tWhere]
            // },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"],
            },
            NextFunc: {
                FuncName: 'JSxNextFuncBranch',
                ArgReturn: ['FTBchCode', 'FTBchName']
            },
        }
        return oOptionReturn;
    };

    // default branch from to Branch To
    function JSxNextFuncBranch(oReturn) {
        var aReturn = JSON.parse(oReturn);
        var tBchCode = aReturn[0];
        var tBchName = aReturn[1];

        if ($('#oetBACUHisAdvSearchBchCodeFrom').val() == "") {
            $('#oetBACUHisAdvSearchBchCodeFrom').val(tBchCode);
            $('#oetBACUHisAdvSearchBchNameFrom').val(tBchName);
        }

        if ($('#oetBACUHisAdvSearchBchCodeTo').val() == "") {
            $('#oetBACUHisAdvSearchBchCodeTo').val(tBchCode);
            $('#oetBACUHisAdvSearchBchNameTo').val(tBchName);
        }
    }
</script>