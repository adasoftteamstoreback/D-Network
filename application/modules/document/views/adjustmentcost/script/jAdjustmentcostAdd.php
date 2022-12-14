<script>
    var nLangEdits = '<?php echo $this->session->userdata("tLangEdit"); ?>';
    var tUsrApv = '<?php echo $this->session->userdata("tSesUsername"); ?>';
    var tSesUsrLevel = '<?php echo $this->session->userdata('tSesUsrLevel'); ?>';
    var tUserBchCode = '<?php echo $this->session->userdata("tSesUsrBchCodeDefault"); ?>';
    var tUserBchName = '<?php echo $this->session->userdata("tSesUsrBchNameDefault"); ?>';
    var tUserWahCode = '<?php echo $this->session->userdata("tSesUsrWahCode"); ?>';
    var tUserWahName = '<?php echo $this->session->userdata("tSesUsrWahName"); ?>';
    var tUsrAgnCode = '<?php echo $this->session->userdata("tSesUsrAgnCode"); ?>';
    var tUsrLevel = "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
    var tBchCodeMulti = "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
    var tSesUserCode = "<?php echo $this->session->userdata("tSesUserCode"); ?>";
    $(document).ready(function() {
        JSxCheckPinMenuClose();
        $('.selectpicker').selectpicker('refresh');


        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            startDate: '1900-01-01',
            disableTouchKeyboard: true,
            autoclose: true
        });

        $('.xCNTimePicker').datetimepicker({
            format: 'HH:mm:ss'
        });

        $('.xWTooltipsBT').tooltip({
            'placement': 'bottom'
        });

        $('[data-toggle="tooltip"]').tooltip({
            'placement': 'top'
        });

        $(".xWConDisDocument .disabled").attr("disabled", "disabled");

        // ================================ Event Date Function  ===============================
        $('#obtADCDocDate').unbind().click(function() {
            $('#oetADCDocDate').datepicker('show');
        });

        $('#obtADCDocTime').unbind().click(function() {
            $('#oetADCDocTime').datetimepicker('show');
        });

        $('#obtADCEffectiveDate').unbind().click(function() {
            $('#oetADCEffectiveDate').datepicker('show');
        });

        $('#obtADCRefIntDate').unbind().click(function() {
            $('#oetADCRefIntDate').datepicker('show');
        });

        $("#obtADCPurchase").prop("disabled", false);
        $("#obtADCAddDoc").prop("disabled", true);

        // =====================================================================================

        // ================================== Set Date Default =================================
        var dCurrentDate = new Date();
        var tAmOrPm = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
        var tCurrentTime = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes() + " " + tAmOrPm;

        if ($('#oetADCDocDate').val() == '') {
            $('#oetADCDocDate').datepicker("setDate", dCurrentDate);
        }

        if ($('#oetADCDocTime').val() == '') {
            $('#oetADCDocTime').val(tCurrentTime);
        }

        if ($('#oetADCEffectiveDate').val() == '') {
            $('#oetADCEffectiveDate').datepicker("setDate", dCurrentDate);
        }
        // =====================================================================================

        // =============================== Check Box Auto GenCode ==============================
        $('#ocbADCStaAutoGenCode').on('change', function(e) {
            if ($('#ocbADCStaAutoGenCode').is(':checked')) {
                $("#oetADCDocNo").val('');
                $("#oetADCDocNo").attr("readonly", true);
                $('#oetADCDocNo').closest(".form-group").css("cursor", "not-allowed");
                $('#oetADCDocNo').css("pointer-events", "none");
                $("#oetADCDocNo").attr("onfocus", "this.blur()");
                $('#ofmADCFormAdd').removeClass('has-error');
                $('#ofmADCFormAdd .form-group').closest('.form-group').removeClass("has-error");
                $('#ofmADCFormAdd em').remove();
            } else {
                $('#oetADCDocNo').closest(".form-group").css("cursor", "");
                $('#oetADCDocNo').css("pointer-events", "");
                $('#oetADCDocNo').attr('readonly', false);
                $("#oetADCDocNo").removeAttr("onfocus");
            }
        });
        // =====================================================================================

        $('#ocbADCPurchase').on('change', function(e) {
            $("#ocbADCAddDoc").prop("checked", false);
            if ($('#ocbADCPurchase').is(':checked')) {
                $("#obtADCPurchase").prop("disabled", false);
                $("#obtADCAddDoc").prop("disabled", true);
                $("#ohdADCAddDocCode").val('');
                $("#oetADCAddDocName").val('');
            } else {
                $("#ohdADCPurchaseCode").val('');
                $("#oetADCPurchaseName").val('');
                $("#obtADCPurchase").prop("disabled", true);
            }
        });

        $('#ocbADCAddDoc').on('change', function(e) {
            $("#ocbADCPurchase").prop("checked", false);
            if ($('#ocbADCAddDoc').is(':checked')) {
                $("#obtADCAddDoc").prop("disabled", false);
                $("#obtADCPurchase").prop("disabled", true);
                $("#ohdADCPurchaseCode").val('');
                $("#oetADCPurchaseName").val('');
            } else {
                $("#ohdADCAddDocCode").val('');
                $("#oetADCAddDocName").val('');
                $("#obtADCAddDoc").prop("disabled", true);
            }
        });

        var type = $('#ocmADCDocType').val();
        var dActDate = $('#oetADCEffectiveDate').val();
        if (type == 10) {
            $('#olbDoctype10').show();
            $('#ospDoctype10').text(dActDate);
            $('#olbDoctype12').hide();
        } else {
            $('#olbDoctype12').show();
            $('#ospDoctype12').text(dActDate);
            $('#olbDoctype10').hide();
        }

    });

    //BrowseBch
    $('#obtADCBrowseBranch').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oADCBrowseBranchOption = oADCBrowseBranch({
                'tReturnInputCode': 'ohdADCBchCode',
                'tReturnInputName': 'ohdADCBchName',
                'tAgnCodeWhere': tUsrAgnCode,
            });
            JCNxBrowseData('oADCBrowseBranchOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });


    //Option Branch
    var oADCBrowseBranch = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var tAgnCodeWhere = poReturnInput.tAgnCodeWhere;

        $nCountBCH = '<?= $this->session->userdata('nSesUsrBchCount') ?>';

        if ($nCountBCH > 1) {
            //?????????????????????????????????????????? 1 
            tWhereBCH = " AND TCNMBranch.FTBchCode IN ( " + tBchCodeMulti + " ) ";
        } else {
            tWhereBCH = '';
        }

        if (tAgnCodeWhere == '' || tAgnCodeWhere == null) {
            tWhereAgn = '';
        } else {
            tWhereAgn = " AND TCNMBranch.FTAgnCode = '" + tAgnCodeWhere + "'";
        }


        var oOptionReturn = {
            Title: ['company/branch/branch', 'tBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L', 'TCNMAgency_L'],
                On: [
                    'TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                    'TCNMAgency_L.FTAgnCode = TCNMBranch.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [tWhereBCH + tWhereAgn]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['15%', '75%'],
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName', 'TCNMAgency_L.FTAgnName', 'TCNMBranch.FTAgnCode'],
                DataColumnsFormat: ['', '', '', ''],
                DisabledColumns: [2, 3],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"],
            },
            NextFunc: {
                FuncName: 'JSxNextFuncBranch',
                ArgReturn: ['FTBchCode']
            },
            RouteAddNew: 'branch',
            BrowseLev: 1,
        }
        return oOptionReturn;
    }

    function JSxNextFuncBranch() {
        $('#ohdADCPurchaseCode').val('');
        $('#oetADCPurchaseName').val('');
        $('#ohdADCAddDocCode').val('');
        $('#oetADCAddDocName').val('');
    }
    //Browse Purchase
    $('#obtADCPurchase').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oADCPurchaseOption = oADCPurchase({
                'tReturnInputCode': 'ohdADCPurchaseCode',
                'tReturnInputName': 'oetADCPurchaseName',
                'tAgnCodeWhere': tUsrAgnCode,
            });
            JCNxBrowseData('oADCPurchaseOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //Option Purchase
    var oADCPurchase = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var tAgnCodeWhere = poReturnInput.tAgnCodeWhere;
        var tBchCode = $('#ohdADCBchCode').val();
        if (tBchCode != '') {
            tWhereBCH = " AND TAPTPiHD.FTBchCode = '" + tBchCode + "' ";
        } else {
            tWhereBCH = '';
        }

        var oOptionReturn = {
            Title: ['document/adjustmentcost/adjustmentcost', 'tPITitle'],
            Table: {
                Master: 'TAPTPiHD',
                PK: 'FTXphDocNo'
            },
            Join: {
                Table: ['TCNMBranch_L','TAPTPiHDDocRef','TCNMSpl_L'],
                On: [
                    'TCNMBranch_L.FTBchCode = TAPTPiHD.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                    'TAPTPiHDDocRef.FTXshDocNo = TAPTPiHD.FTXphDocNo',
                    'TAPTPiHD.FTSplCode = TCNMSpl_L.FTSplCode AND TCNMSpl_L.FNLngID = ' + nLangEdits
                    // 'TCNMUser_L.FTUsrCode = TAPTPoHD.FTCreateBy AND POHD.FTXphApvCode = TCNMUser_L.FTUsrCode AND TCNMUser_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [tWhereBCH + "AND TAPTPiHD.FTXphStaApv = '1'"]
            },
            GrideView: {
                ColumnPathLang: 'document/purchaseorder/purchaseorder',
                ColumnKeyLang: ['tPOTBBchCreate', 'tPOTBDocNo', 'tPOTBDocDate', 'tPORefIntDoc', 'tPORefIntDocDate', 'tPOLabelFrmSplInfoDoc'],
                ColumnsSize: ['10%', '20%', '15%', '20%', '15%', '20%'],
                DataColumns: ['TCNMBranch_L.FTBchName', 'TAPTPiHD.FTXphDocNo','TAPTPiHD.FDXphDocDate','TAPTPiHDDocRef.FTXshRefDocNo','TAPTPiHDDocRef.FDXshRefDocDate','TCNMSpl_L.FTSplName'],
                DataColumnsFormat: ['', '', '', '', '', ''],
                DisabledColumns: [],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TAPTPiHD.FDXphDocDate DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TAPTPiHD.FTXphDocNo"],
                Text: [tInputReturnName, "TAPTPiHD.FTXphDocNo"],
            },
            NextFunc: {
                FuncName        : 'FSxNextFuncPurchase',
                ArgReturn       : ['FTXphDocNo','FDXphDocDate']
            },
        }
        return oOptionReturn;
    }


    function FSxNextFuncPurchase(poDataNextFunc) {
        var tFTXphDocNo  = '';
        var tFTXphDocDate  = '';
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tFTXphDocNo = aDataNextFunc[0];
            tFTXphDocDate = $.datepicker.formatDate('yy-mm-dd', new Date(aDataNextFunc[1]));
        }
        $("#oetADCRefInt").val(tFTXphDocNo);
        $("#oetADCRefIntDate").val(tFTXphDocDate);
    }

    function FSxNextFuncDoc(poDataNextFunc) {
        var tFTXphDocNo  = '';
        var tFTXphDocDate  = '';
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tFTXphDocNo = aDataNextFunc[0];
            tFTXphDocDate = $.datepicker.formatDate('yy-mm-dd', new Date(aDataNextFunc[1]));
        }
        $("#oetADCRefInt").val(tFTXphDocNo);
        $("#oetADCRefIntDate").val(tFTXphDocDate);
    }

    // Functionality : ??????????????????????????????????????? 
    // Parameter : function parameters
    // Create : 25/02/2021 Sooksanti(Nont)
    // Return : -
    // Return Type : -
    function JSxADCNumberRows($tTableBody) {
        var tNO = 0;
        var aPdtCodeDup = [];
        var tBodyVotFound = '';
        if($('#ohdADCCountDocRemark').val() > 0){
                $('.xWRemark').removeClass('hidden');
                $("#otbDOCPdtTable").find('tbody .xWRemark1').removeClass('hidden');
            }else{
                $('.xWRemark').addClass('hidden');
                $("#otbDOCPdtTable").find('tbody .xWRemark1').addClass('hidden');
        }

        $tTableBody.find("tr").each(function(ind, el) {
            $(el).find("td:eq(1)").html(++tNO);
            var tPdtCode = $(el).find("td:eq(2)").text();
            aPdtCodeDup.push(tPdtCode);
        });
        var tPdtCodeDup = aPdtCodeDup.toString();
        var tItemData = '';
        if (tPdtCodeDup == '') {
            tItemData += '<tr class="xWNotfoundDataTablePdt">' +
                '<td class="text-center xCNTextDetail2 xWTextNotfoundDataTablePdt" colspan="100%">' + '<?php echo language('document/adjustmentcost/adjustmentcost', 'tADCNotFoundData'); ?>' + '</td>' +
                '</tr>'
            $("#odvADCTable").prepend(tItemData);
        } else {
            $('.xWNotfoundDataTablePdt').remove()
        }
        $('#ohdADCPdtDupCode').val(tPdtCodeDup);
    }


    // Functionality : ???????????????????????????????????????????????????????????????????????? ???????????????????????????????????????/?????????????????????????????? 
    // Parameter : function parameters
    // Create : 25/02/2021 Sooksanti(Nont)
    // Return : -
    // Return Type : -
    function JSxADCGetPdtFromDoc(tTable, tDocNo, tPdtCodeDup) {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docADCGetPdtFromDoc",
            data: {
                tTable: tTable,
                tDocNo: tDocNo,
                tPdtCodeDup: tPdtCodeDup,
            },
            async: false,
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aResult = JSON.parse(tResult);
                JSxADCShowTable(aResult)
                JCNxCloseLoading();

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Functionality : ???????????????????????????????????????????????????????????????????????? ImportExcel
    // Parameter : function parameters
    // Create : 25/02/2021 Sooksanti(Nont)
    // Return : -
    // Return Type : -
    function JSxADCGetPdtFromImportExcel(tPdtCodeDup) {
        JCNxOpenLoading();
        var tPdtCodeDup = $('#ohdADCPdtDupCode').val()
        $.ajax({
            type: "POST",
            url: "docADCGetPdtFromImportExcel",
            data: {
                tPdtCodeDup: tPdtCodeDup,
            },
            async: false,
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aResult = JSON.parse(tResult);
                JSxADCShowTable(aResult)
                JCNxCloseLoading();

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
    
    // Functionality : ?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
    // Parameter : function parameters
    // Create : 25/02/2021 Sooksanti(Nont)
    // Return : -
    // Return Type : -
    function JSxADCGetPdtFromFilter(paData) {
        var tPdtCodeFrom = paData[0];
        var tPdtCodeTo = paData[1];
        var tBarCodeFrom = paData[2];
        var tBarCodeCodeTo = paData[3];
        var tPdtCodeDup = $('#ohdADCPdtDupCode').val();
        var tBchCode = $('#ohdADCBchCode').val();
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docADCGetPdtFromFilter",
            data: {
                tPdtCodeFrom: tPdtCodeFrom,
                tPdtCodeTo: tPdtCodeTo,
                tBarCodeFrom: tBarCodeFrom,
                tBarCodeCodeTo: tBarCodeCodeTo,
                tPdtCodeDup: tPdtCodeDup,
                tBchCode: tBchCode,
            },
            async: false,
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aResult = JSON.parse(tResult);
                JSxADCShowTable(aResult)
                $('#odvADCFilterDataCondition').modal('hide');
                JCNxCloseLoading();

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }


    // Functionality : ?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
    // Parameter : function parameters
    // Create : 25/02/2021 Sooksanti(Nont)
    // Return : -
    // Return Type : -
    function JSxADCGetPdtFromDT() {
        var tDocNo = $('#oetADCDocNo').val();
        var tSearchPdtHTML = $('#oetADCSearchPdtHTML').val();
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docADCGetPdtFromDT",
            data: {
                tSearchPdtHTML: tSearchPdtHTML,
                tDocNo: tDocNo,
            },
            async: false,
            cache: false,
            timeout: 0,
            success: function(tResult) {
                var aResult = JSON.parse(tResult);
                JSxADCShowTable(aResult)
                JCNxCloseLoading();

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Functionality : loop ???????????????????????????????????????
    // Parameter : function parameters
    // Create : 25/02/2021 Sooksanti(Nont)
    // Return : -
    // Return Type : -
    function JSxADCShowTable(paResult) {
        var nTypeAdj = $('#ocmADCDocType').val();
        var tbaseUrl = '<?php echo  base_url('application/modules/common/assets/images/icons/delete.png'); ?>';
        var tItemData = '';
        var tFCPdtCost = ''
        var tFTTmpRemark = ''
        if (paResult['nStaEvent'] == '1') {
            var nRemarkCount = 0;
            for (i = 0; i < paResult['aData'].length; i++) {
                if (nTypeAdj == 12) {
                    if (paResult['aData'][i].FCPdtCostStd === null || paResult['aData'][i].FCPdtCostStd === undefined) {
                        tFCPdtCost = (0).toFixed(2);
                    } else {
                        tFCPdtCost = parseFloat(paResult['aData'][i].FCPdtCostStd).toFixed(2);
                    }
                    // console.log(tFCPdtCost);
                }else{
                    if (paResult['aData'][i].FCPdtCostEx === null || paResult['aData'][i].FCPdtCostEx === undefined) {
                        tFCPdtCost = (0).toFixed(2);
                    } else {
                        tFCPdtCost = parseFloat(paResult['aData'][i].FCPdtCostEx).toFixed(2);
                    }
                }

                if(paResult['aData'][i].FCXcdDiff == '' || paResult['aData'][i].FCXcdDiff == null){
                    tFCXcdDiff = (0).toFixed(2);
                }else{
                    tFCXcdDiff = parseFloat(paResult['aData'][i].FCXcdDiff).toFixed(2);
                }

                if(paResult['aData'][i].FCXcdCostNew == ''|| paResult['aData'][i].FCXcdCostNew == null){
                    tFCXcdCostNew = ''
                }else{
                    tFCXcdCostNew = parseFloat(paResult['aData'][i].FCXcdCostNew).toFixed(2);
                }

                if(paResult['aData'][i].FTTmpStatus != '1' && paResult['aData'][i].FTTmpStatus != undefined){
                    nRemarkCount++;
                }

                if(paResult['aData'][i].FTTmpRemark != undefined){
                    tFTTmpRemark = paResult['aData'][i].FTTmpRemark;
                }

                if(paResult['aData'][i].FTPdtName == ''|| paResult['aData'][i].FTPdtName == null){
                    tFTPdtName = ''
                }else{
                    tFTPdtName = paResult['aData'][i].FTPdtName;
                }

                if(paResult['aData'][i].FTPunName == ''|| paResult['aData'][i].FTPunName == null){
                    tFTPunName = ''
                }else{
                    tFTPunName = paResult['aData'][i].FTPunName;
                }

                if(paResult['aData'][i].FTPunCode == ''|| paResult['aData'][i].FTPunCode == null){
                    tFTPunCode = ''
                }else{
                    tFTPunCode = paResult['aData'][i].FTPunCode;
                }

                if(paResult['aData'][i].FCXcdFactor == ''|| paResult['aData'][i].FCXcdFactor == null){
                    tFCXcdFactor = 0
                }else{
                    tFCXcdFactor = paResult['aData'][i].FCXcdFactor;
                }
                
                tItemData += '<tr class="xCNTextDetail" data-pdt='+paResult['aData'][i].FTPdtCode+'>' +
                    '<td class="text-center xCNHideWhenApvOrCancel">' +
                    '<label class="fancy-checkbox">' +
                    '<input type="checkbox" class="ocbListItem" name="ocbListItem[]">' +
                    '<span>&nbsp;</span>' +
                    '</label>' +
                    '</td>' +
                    '<td class="text-center"></td>' +
                    '<td >' + paResult['aData'][i].FTPdtCode + '</td>' +
                    '<td >' + tFTPdtName + '</td>' +
                    '<td class="hidden">' + paResult['aData'][i].FTXcdBarScan + '</td>' +
                    '<td >' + tFTPunName + '</td>' +
                    '<td class="text-right">' + tFCPdtCost + '</td>' +
                    '<td class="text-right">' + tFCXcdDiff + '</td>' +
                    '<td class="otdQty">' +
                    '<div class="xWEditInLine">' +
                    '<input type="text" value ="' + tFCXcdCostNew + '"onblur = "JSxADCCostDiff(this)" class="inputs form-control xControlForm xCNInputNumericWithDecimal xCNPdtEditInLine text-right xWValueEditInLine xWShowInLine" style="background:#F9F9F9;border-top: 0px !important;border-left: 0px !important;border-right: 0px !important;box-shadow: inset 0 0px 0px; min-width:100px;" maxlength="10" value="" autocomplete="off">' +
                    '</div>' +
                    '</td>' +
                    '<td class="text-left xWRemark1" style="color: red !important;">' + tFTTmpRemark + '</td>' +
                    '<td nowrap class="text-center xWASTRemoveOnApv xCNHideWhenApvOrCancel"' +
                    '<lable class="xCNTextLink">' +
                    '<img class="xCNIconTable" src="' + tbaseUrl + '" title="Remove" onclick="JSxADCRemoveDTRow(this)">' +
                    '</lable>' +
                    '</td>' +
                    '<td class= "hidden">' + tFTPunCode + '</td>' +
                    '<td class= "hidden">' + tFCXcdFactor + '</td>' +
                    '</tr>';
            }
            $('#ohdADCCountDocRemark').val(nRemarkCount);
            $("#odvADCTable").prepend(tItemData);
            JSxADCNumberRows($("#odvADCTable"))
            $('.inputs').keydown(function (e) {
                var tCostOld = parseFloat($(this).parents("tr").find("td:eq(6)").text());
                var tCostNew = parseFloat($(this).parents("tr").find(".xCNPdtEditInLine").val());
                var tCostDiff = ''
                    if ($(this).val() == '') { // check if value changed
                    $(this).val('')
                    tCostDiff = 0;
                    }else{
                        tCostDiff = tCostNew - tCostOld;
                    }
                if (e.which === 13) {
                    $(this).closest("tr").nextAll().eq(0).find(".xCNPdtEditInLine").focus().select()
                    $(this).parents("tr").find("td:eq(7)").html((tCostDiff).toFixed(2));
                }
            });
            $('.inputs').click(function (e) {
                $(this).select();
            });

            $('.ocbListItem').unbind().click(function() {
                $('#ocbCheckAll').prop('checked',false);
                var nCountchecked = $("table input[type=checkbox]:checked").length;
                if (nCountchecked > 1) {
                    $("#oliADCBtnDeleteMulti").removeClass("disabled");
                } else {
                    $("#oliADCBtnDeleteMulti").addClass("disabled");
                }
            });
        }
    }


    // Functionality : ??????????????????????????????????????????
    // Parameter : function parameters
    // Create : 25/02/2021 Sooksanti(Nont)
    // Return : -
    // Return Type : -
    function JSxADCCostDiff(tThis) {
        if ($(tThis).parents("tr").find(".xCNPdtEditInLine").val() != '') {
            var tCostOld = parseFloat($(tThis).parents("tr").find("td:eq(6)").text());
            var tCostNew = parseFloat($(tThis).parents("tr").find(".xCNPdtEditInLine").val());
            $(tThis).parents("tr").find("td:eq(7)").html((tCostNew - tCostOld).toFixed(2));
        }else{
            parseFloat($(tThis).parents("tr").find(".xCNPdtEditInLine").val(''));
            $(tThis).parents("tr").find("td:eq(7)").html((0).toFixed(2));
        }
    }


    // Functionality : ????????????????????????????????? Single
    // Parameter : function parameters
    // Create : 25/02/2021 Sooksanti(Nont)
    // Return : -
    // Return Type : -
    function JSxADCRemoveDTRow(tThis) {
        JCNxOpenLoading();
        if($(tThis).parents("tr").find("td:eq(9)").text() != ''){
            var tCountDocRemark = parseInt($("#ohdADCCountDocRemark").val())
            $("#ohdADCCountDocRemark").val(tCountDocRemark-1);
        }
        $(tThis).parents("tr").remove();
        JSxADCNumberRows($("#odvADCTable"))
        JCNxCloseLoading();

        //???????????????????????? ????????????????????????????????? ????????????????????????????????????
        var tTextDup    = $(tThis).parents("tr").find("td:eq(2)").text();
        var nCountDup   = 0;
        $("table tbody tr").each(function() {
            var tText = $(this).find("td:eq(2)").text();
            if(tText == tTextDup){
                nCountDup++;
            }
        });

        if(nCountDup == 1){
            $("table tbody tr").each(function() {
                var tText = $(this).find("td:eq(2)").text();
                if(tText == tTextDup){
                    $(this).find("td:eq(9)").text('');
                }
            });
        }
    }


    // Functionality : ????????????????????????????????? Single
    // Parameter : function parameters
    // Create : 25/02/2021 Sooksanti(Nont)
    // Return : -
    // Return Type : -
    function JSxADCAutoRemoveDTRow(tThis) {
        JCNxOpenLoading();
        if($(tThis).find("td:eq(9)").text() != ''){
            var tCountDocRemark = parseInt($("#ohdADCCountDocRemark").val())
            $("#ohdADCCountDocRemark").val(tCountDocRemark-1);
        }
        $(tThis).remove();
        JSxADCNumberRows($("#odvADCTable"))
        JCNxCloseLoading();

        //???????????????????????? ????????????????????????????????? ????????????????????????????????????
        var tTextDup    = $(tThis).find("td:eq(2)").text();
        var nCountDup   = 0;
        $("table tbody tr").each(function() {
            var tText = $(this).find("td:eq(2)").text();
            if(tText == tTextDup){
                nCountDup++;
            }
        });

        if(nCountDup == 1){
            $("table tbody tr").each(function() {
                var tText = $(this).find("td:eq(2)").text();
                if(tText == tTextDup){
                    $(this).find("td:eq(9)").text('');
                }
            });
        }
    }

    //????????????????????????????????? multi
    $('#oliADCBtnDeleteMulti').unbind().click(function() {
        $("table tbody").find('.ocbListItem').each(function() {
            if ($(this).is(":checked")) {
                $(this).parents("tr").remove();
                JSxADCNumberRows($("#odvADCTable"))
                $("#oliADCBtnDeleteMulti").addClass("disabled");
                $('#ocbCheckAll').prop('checked',false);
            }
        });
    });

    //PDT ?????????????????????
    $('#obtImportPDTInCN').click(function(e) {
        var tDocNo = '';
        var tPdtCodeDup = $('#ohdADCPdtDupCode').val();
        if ($('#ocbADCPurchase').is(':checked')) {
            tDocNo = $('#ohdADCPurchaseCode').val();
            if (tDocNo != '') {
                JSxADCGetPdtFromDoc('TAPTPiDT', tDocNo, tPdtCodeDup)
            }
        } else if ($('#ocbADCAddDoc').is(':checked')) {
            tDocNo = $('#ohdADCAddDocCode').val();
            if (tDocNo != '') {
                JSxADCGetPdtFromDoc('TCNTPdtTwiDT', tDocNo, tPdtCodeDup)
            }
        }
    });


    //Browse ???????????????????????????
    $('#obtADCAddDoc').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oADCAddDocOption = oADCAddDoc({
                'tReturnInputCode': 'ohdADCAddDocCode',
                'tReturnInputName': 'oetADCAddDocName',
                'tAgnCodeWhere': tUsrAgnCode,
            });
            JCNxBrowseData('oADCAddDocOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //Option ?????????????????????
    var oADCAddDoc = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var tAgnCodeWhere = poReturnInput.tAgnCodeWhere;

        var tBchCode = $('#ohdADCBchCode').val();
        if (tBchCode != '') {
            tWhereBCH = " AND TCNTPdtTwiHD.FTBchCode = " + tBchCode + "";
        } else {
            tWhereBCH = '';
        }

        var oOptionReturn = {
            Title: ['document/adjustmentcost/adjustmentcost', 'tWITitle'],
            Table: {
                Master: 'TCNTPdtTwiHD',
                PK: 'FTXthDocNo'
            },
            Join: {
                Table: ['TCNMBranch_L'],
                On: [
                    'TCNMBranch_L.FTBchCode = TCNTPdtTwiHD.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                    // 'TCNMUser_L.FTUsrCode = TAPTPoHD.FTCreateBy AND POHD.FTXphApvCode = TCNMUser_L.FTUsrCode AND TCNMUser_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [tWhereBCH + "AND TCNTPdtTwiHD.FTXthStaApv = '1'"]
            },
            GrideView: {
                ColumnPathLang: 'document/purchaseorder/purchaseorder',
                ColumnKeyLang: ['tPOTBBchCreate', 'tPOTBDocNo', 'tPOTBDocDate'],
                ColumnsSize: ['15%', '75%'],
                DataColumns: ['TCNMBranch_L.FTBchName', 'TCNTPdtTwiHD.FTXthDocNo','TCNTPdtTwiHD.FDXthDocDate'],
                DataColumnsFormat: ['', '', ''],
                DisabledColumns: [2],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNTPdtTwiHD.FDXthDocDate DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNTPdtTwiHD.FTXthDocNo"],
                Text: [tInputReturnName, "TCNTPdtTwiHD.FTXthDocNo"],
            },
            NextFunc: {
                FuncName        : 'FSxNextFuncDoc',
                ArgReturn       : ['FTXthDocNo','FDXthDocDate']
            },
        }
        return oOptionReturn;
    }

    //Clear Data
    $('#obtADCFilterDataCondition').unbind().click(function() {
        $('#odvADCFilterDataCondition').modal('show');
        $('#oetADCFilterPdtCodeFrom').val('');
        $('#oetADCFilterPdtNameFrom').val('');
        $('#oetADCFilterPdtCodeTo').val('');
        $('#oetADCFilterPdtNameTo').val('');

        $('#oetADCFilterBarCodeFrom').val('');
        $('#oetADCFilterBarCodeNameFrom').val('');
        $('#oetADCFilterBarCodeCodeTo').val('');
        $('#oetADCFilterBarCodeNameTo').val('');
    });


    // Browse Event Product
    $('#obtADCBrowseFilterProductFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oProductOption = undefined;
            oProductOption = oADCProductOption({
                'tReturnInputCode': 'oetADCFilterPdtCodeFrom',
                'tReturnInputName': 'oetADCFilterPdtNameFrom',
                'tNextFuncName': 'JSxConsNextFuncBrowsePdt',
                'aArgReturn': ['FTPdtCode', 'FTPdtName']
            });
            JCNxBrowseData('oProductOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });


    // Browse Event Product
    $('#obtADCBrowseFilterProductTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oProductOption = undefined;
            oProductOption = oADCProductOption({
                'tReturnInputCode': 'oetADCFilterPdtCodeTo',
                'tReturnInputName': 'oetADCFilterPdtNameTo',
                'tNextFuncName': 'JSxConsNextFuncBrowsePdt',
                'aArgReturn': ['FTPdtCode', 'FTPdtName']
            });
            JCNxBrowseData('oProductOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Product Option
    var oADCProductOption = function(poReturnInputPdt) {
        let tPdtInputReturnCode = poReturnInputPdt.tReturnInputCode;
        let tPdtInputReturnName = poReturnInputPdt.tReturnInputName;
        let tPdtNextFuncName = poReturnInputPdt.tNextFuncName;
        let aPdtArgReturn = poReturnInputPdt.aArgReturn;
        let tCondition = '';


        let tBchCode = $('#ohdADCBchCode').val();
        let tAgnCode  = "<?= $this->session->userdata("tSesUsrAgnCode") ?>";
        if(tSesUsrLevel != 'HQ'){
            tCondition  +=" AND ((TCNMPdtSpcBch.FTAgnCode = '"+tAgnCode+"')	OR TCNMPdtSpcBch.FTBchCode = "+tBchCode;
            tCondition  +=" OR (ISNULL(TCNMPdtSpcBch.FTBchCode,'') = '' AND TCNMPdtSpcBch.FTAgnCode = '"+tAgnCode+"'	)";
            tCondition  +=" OR ISNULL(TCNMPdtSpcBch.FTAgnCode,'') = '' )";
        }

        let oPdtOptionReturn = {
            Title   : ["product/product/product", "tPDTTitle"],
            Table   : {
                Master  : "TCNMPdt",
                PK      : "FTPdtCode"
            },
            Join    : {
                Table   : ["TCNMPdt_L",'TCNMPdtSpcBch'],
                On      : [
                            'TCNMPdt.FTPdtCode = TCNMPdt_L.FTPdtCode AND TCNMPdt_L.FNLngID = ' + nLangEdits,
                            'TCNMPdtSpcBch.FTPdtCode = TCNMPdt.FTPdtCode' 
                        ]
            },
            Where: {
                Condition   : [tCondition]
            },
            GrideView   : {
                ColumnPathLang  : 'product/product/product',
                ColumnKeyLang   : ['tPDTCode', 'tPDTName'],
                DataColumns     : ['TCNMPdt.FTPdtCode', 'TCNMPdt_L.FTPdtName'],
                DataColumnsFormat: ['', ''],
                ColumnsSize     : ['15%', '75%'],
                Perpage         : 10,
                WidthModal      : 50,
                OrderBy         : ['TCNMPdt.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType      : 'S',
                Value           : [tPdtInputReturnCode, "TCNMPdt.FTPdtCode"],
                Text            : [tPdtInputReturnName, "TCNMPdt_L.FTPdtName"]
            },
            NextFunc: {
                FuncName        : tPdtNextFuncName,
                ArgReturn       : aPdtArgReturn
            },
            RouteAddNew: 'product',
            BrowseLev: 1
        };
        return oPdtOptionReturn;
    }

    // Functionality : Next Function Product And Check Data 
    // Parameter : Event Next Func Modal
    // Create : 25/02/2021
    // Return : Clear Velues Data
    // Return Type : -
    function JSxConsNextFuncBrowsePdt(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tPdtCode = aDataNextFunc[0];
            tPdtName = aDataNextFunc[1];
        } else {
            tPdtCode = '';
            tPdtName = '';
        }

        // ???????????????????????????????????? ??????????????????
        var tPdtCodeFrom, tPdtNameFrom, tPdtCodeTo, tPdtNameTo
        tPdtCodeFrom = $('#oetADCFilterPdtCodeFrom').val();
        tPdtNameFrom = $('#oetADCFilterPdtNameFrom').val();
        tPdtCodeTo = $('#oetADCFilterPdtCodeTo').val();
        tPdtNameTo = $('#oetADCFilterPdtNameTo').val();

        // ?????????????????????????????????????????????????????? Browse ??????????????????????????? ????????? default ?????????????????????????????? ???????????????????????????????????????????????????s??? 
        if ((typeof(tPdtCodeFrom) !== 'undefined' && tPdtCodeFrom != "") && (typeof(tPdtCodeTo) !== 'undefined' && tPdtCodeTo == "")) {
            $('#oetADCFilterPdtCodeTo').val(tPdtCode);
            $('#oetADCFilterPdtNameTo').val(tPdtName);
        }

        // ?????????????????????????????????????????????????????? Browse ?????????????????????????????? default ??????????????????????????? ?????????????????????????????????????????????????????? 
        if ((typeof(tPdtCodeTo) !== 'undefined' && tPdtCodeTo != "") && (typeof(tPdtCodeFrom) !== 'undefined' && tPdtCodeFrom == "")) {
            $('#oetADCFilterPdtCodeFrom').val(tPdtCode);
            $('#oetADCFilterPdtNameFrom').val(tPdtName);
        }

    }


    // Browse Event BarCode
    $('#obtADCBrowseFilterBarCodeFrom').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oBarCodeOption = undefined;
            oBarCodeOption = oADCBarCodetOption({
                'tReturnInputCode': 'oetADCFilterBarCodeFrom',
                'tReturnInputName': 'oetADCFilterBarCodeNameFrom',
                'tNextFuncName': 'JSxConsNextFuncBrowseBarCode',
                'aArgReturn': ['FTBarCode', 'FTBarCode']
            });
            JCNxBrowseData('oBarCodeOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse Event BarCode
    $('#obtADCBrowseFilterBarCodeTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oBarCodeOption = undefined;
            oBarCodeOption = oADCBarCodetOption({
                'tReturnInputCode': 'oetADCFilterBarCodeCodeTo',
                'tReturnInputName': 'oetADCFilterBarCodeNameTo',
                'tNextFuncName': 'JSxConsNextFuncBrowseBarCode',
                'aArgReturn': ['FTBarCode', 'FTBarCode']
            });
            JCNxBrowseData('oBarCodeOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse BarCode Option
    var oADCBarCodetOption = function(poReturnInputBarCode) {
        let tBarCodeInputReturnCode = poReturnInputBarCode.tReturnInputCode;
        let tBarCodeInputReturnName = poReturnInputBarCode.tReturnInputName;
        let tBarCodeNextFuncName = poReturnInputBarCode.tNextFuncName;
        let aBarCodeArgReturn = poReturnInputBarCode.aArgReturn;
        let tCondition = '';


        let tBchCode = $('#ohdADCBchCode').val();
        let tAgnCode  = "<?= $this->session->userdata("tSesUsrAgnCode") ?>";
        if(tSesUsrLevel != 'HQ'){
            tCondition  +=" AND ((TCNMPdtSpcBch.FTAgnCode = '"+tAgnCode+"')	OR TCNMPdtSpcBch.FTBchCode = "+tBchCode;
            tCondition  +=" OR (ISNULL(TCNMPdtSpcBch.FTBchCode,'') = '' AND TCNMPdtSpcBch.FTAgnCode = '"+tAgnCode+"'	)";
            tCondition  +=" OR ISNULL(TCNMPdtSpcBch.FTAgnCode,'') = '' )";
        }
        let oBarCodeOptionReturn = {
            Title: ["product/product/product", "tPDTTitle"],
            Table: {
                Master: "TCNMPdtBar",
                PK: "FTBarCode"
            },
            Join: {
                Table: ['TCNMPdt_L','TCNMPdtSpcBch'],
                On: [
                    'TCNMPdtBar.FTPdtCode = TCNMPdt_L.FTPdtCode AND TCNMPdt_L.FNLngID = ' + nLangEdits,
                    'TCNMPdtSpcBch.FTPdtCode = TCNMPdtBar.FTPdtCode'
                ]
            },
            Where: {
                Condition: [tCondition]
            },
            GrideView: {
                ColumnPathLang: 'product/product/product',
                ColumnKeyLang: ['tPDTCode','tPDTName','tPDTViewPackBarcode'],
                DataColumns: ['TCNMPdtBar.FTPdtCode','TCNMPdt_L.FTPdtName','TCNMPdtBar.FTBarCode'],
                DataColumnsFormat: ['', '',''],
                ColumnsSize: ['15%', '50%'],
                Perpage: 10,
                WidthModal: 50,
                OrderBy: ['TCNMPdtBar.FTBarCode DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tBarCodeInputReturnCode, "TCNMPdtBar.FTBarCode"],
                Text: [tBarCodeInputReturnName, "TCNMPdtBar.FTBarCode"]
            },
            NextFunc: {
                FuncName: tBarCodeNextFuncName,
                ArgReturn: aBarCodeArgReturn
            },

        };
        return oBarCodeOptionReturn;
    }

    // Functionality : Next Function Barcode And Check Data 
    // Parameter : Event Next Func Modal
    // Create : 25/02/2021 Sooksanti
    // Return : Clear Velues Data
    // Return Type : -
    function JSxConsNextFuncBrowseBarCode(poDataNextFunc) {

        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {
            var aDataNextFunc = JSON.parse(poDataNextFunc);
            tBarCode = aDataNextFunc[0];
            tBarCodeName = aDataNextFunc[1];
        } else {
            tBarCode = '';
            tBarCodeName = '';
        }

        // ???????????????????????????????????? ??????????????????
        var tBarCodeFrom, tBarCodeNameFrom, tBarCodeTo, tBarCodeNameTo
        tBarCodeFrom = $('#oetADCFilterBarCodeFrom').val();
        tBarCodeNameFrom = $('#oetADCFilterBarCodeNameFrom').val();
        tBarCodeTo = $('#oetADCFilterBarCodeCodeTo').val();
        tBarCodeNameTo = $('#oetADCFilterBarCodeNameTo').val();

        // ?????????????????????????????????????????????????????? Browse ??????????????????????????? ????????? default ?????????????????????????????? ???????????????????????????????????????????????????s??? 
        if ((typeof(tBarCodeFrom) !== 'undefined' && tBarCodeFrom != "") && (typeof(tBarCodeTo) !== 'undefined' && tBarCodeTo == "")) {
            $('#oetADCFilterBarCodeCodeTo').val(tBarCode);
            $('#oetADCFilterBarCodeNameTo').val(tBarCodeName);
        }

        // ?????????????????????????????????????????????????????? Browse ?????????????????????????????? default ??????????????????????????? ?????????????????????????????????????????????????????? 
        if ((typeof(tBarCodeTo) !== 'undefined' && tBarCodeTo != "") && (typeof(tBarCodeFrom) !== 'undefined' && tBarCodeFrom == "")) {
            $('#oetADCFilterBarCodeFrom').val(tBarCode);
            $('#oetADCFilterBarCodeNameFrom').val(tBarCodeName);
        }

    }

    //?????????????????????????????????????????????
    $('#obtADCConfirmFilter').unbind().click(function() {
        var tPdtCodeFrom = $('#oetADCFilterPdtCodeFrom').val();
        var tPdtCodeTo = $('#oetADCFilterPdtCodeTo').val();
        var tBarCodeFrom = $('#oetADCFilterBarCodeNameFrom').val();
        var tBarCodeCodeTo = $('#oetADCFilterBarCodeNameTo').val();

        if (tPdtCodeFrom == '' && tPdtCodeTo != '') {
            tPdtCodeFrom = tPdtCodeTo;
        }

        if (tPdtCodeTo == '' && tPdtCodeFrom != '') {
            tPdtCodeTo = tPdtCodeFrom;
        }

        if (tBarCodeFrom == '' && tBarCodeCodeTo != '') {
            tBarCodeFrom = tBarCodeCodeTo;
        }

        if (tBarCodeCodeTo == '' && tBarCodeFrom != '') {
            tBarCodeCodeTo = tBarCodeFrom;
        }

        var aDataFilter = [tPdtCodeFrom, tPdtCodeTo, tBarCodeFrom, tBarCodeCodeTo]
        JSxADCGetPdtFromFilter(aDataFilter)
    });

    $('#obtADCPrint').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            JSxADCPrintDoc(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Functionality : ????????????????????????????????? 
    // Parameter : Event Next Func Modal
    // Create : 25/02/2021 Sooksanti
    // Return : Clear Velues Data
    // Return Type : -
    function JSxADCPrintDoc() {
        var aInfor = [
            {"Lang"          : '<?= FCNaHGetLangEdit(); ?>'},
            {"ComCode"       : '<?= FCNtGetCompanyCode(); ?>' },
            {"BranchCode"    : '<?= FCNtGetAddressBranch($tADCBchCode); ?>' },
            {"DocCode"       : '<?= $tADCDocNo; ?>'},
            {"DocBchCode"    : '<?= $tADCBchCode; ?>'}
        ];
        window.open("<?= base_url(); ?>formreport/FRM_SQL_ALLMPdtBillAdjustCost?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

    function JSxADCSearch() {
        var tValue = $('#oetADCSearchPdtHTML').val().toLowerCase();
        $("#odvADCTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(tValue) > -1)
        });
    }

    /*===== Begin Import Excel =========================================================*/
    function JSxOpenImportForm(){

        var tNameModule = 'AdjCost';
        var tTypeModule = 'document';
        var tAfterRoute = 'JSxImportExcelCallback'; // call func
        var tFlagClearTmp = '1' // null = ????????????????????? 1 = ????????????????????????????????????????????????????????? 2 = ??????????????????????????????????????????

        var aPackdata = {
        'tNameModule' : tNameModule,
        'tTypeModule' : tTypeModule,
        'tAfterRoute' : tAfterRoute,
        'tFlagClearTmp' : tFlagClearTmp
        };

        JSxImportPopUp(aPackdata);
    }

    function JSxImportExcelCallback(){
        setTimeout(function(){
            $("#odvADCTable").html('');
            JSxADCGetPdtFromImportExcel();
        }, 50);
    }

    $( "#ocmADCDocType" ).change(function() {
        var type = $('#ocmADCDocType').val();
        var dActDate = $('#oetADCEffectiveDate').val();
        if (type == 10) {
            $('#olbDoctype12').hide();
            $('#olbDoctype10').show();
            $('#ospDoctype10').text(dActDate);
        } else {
            $('#olbDoctype12').show();
            $('#olbDoctype10').hide();
            $('#ospDoctype12').text(dActDate);
        }
        var nChkTrLength = $('.xCNTextDetail').length;
        if (nChkTrLength > 0) {
            $("#odvADCPopupChangeTypeAdj").modal("show");
        }
    });

    function JSxADCClearData(){  
        $("#odvADCTable").empty();
        $("#odvADCPopupChangeTypeAdj").modal("hide");
        JSxADCNumberRows($("#odvADCTable"))
    }

    function JSxADCNotClearData(){  
        var nType = $('#ohdADCDocType').val();
        var nDocType = '';
        if (nType == '') {
            nDocType = $("#ocmADCDocType").val();
            if (nDocType == 10) {
                $("#ocmADCDocType").val("12").selectpicker('refresh');
            } else {
                $("#ocmADCDocType").val("10").selectpicker('refresh');
            }
        } else {
            $("#ocmADCDocType").val(nType).selectpicker('refresh');
        }
        $("#odvADCPopupChangeTypeAdj").modal("hide");
    }

    $("#ocbCheckAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);

        if($(this).is(':checked')==true){
            $('.ocbListItem').prop('checked',true);
            $("#oliADCBtnDeleteMulti").removeClass("disabled");
        }else{
            $('.ocbListItem').prop('checked',false);
            $("#oliADCBtnDeleteMulti").addClass("disabled");
        }
    });
    
</script>