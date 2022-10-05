<script>
    var tStatusDoc = $('#ohdIVCStaDoc').val();
    var tChkrount = $('#ohdIVCRoute').val();
    var nAddressVersion = '<?=FCNaHAddressFormat('TCNMCst')?>';
    var tStatusDoc = $('#ohdIVCStaDoc').val();
    var tStatusApv = $('#ohdIVCStaApv').val();

    /** ================== Check Box Auto GenCode ===================== */
    $('#ocbIVCStaAutoGenCode').on('change', function (e) {
        if($('#ocbIVCStaAutoGenCode').is(':checked')){
            $("#oetIVCDocNo").val('');
            $("#oetIVCDocNo").attr("readonly", true);
            $('#oetIVCDocNo').closest(".form-group").css("cursor","not-allowed");
            $('#oetIVCDocNo').css("pointer-events","none");
            $("#oetIVCDocNo").attr("onfocus", "this.blur()");
            $('#ofmIVCFormAdd').removeClass('has-error');
            $('#ofmIVCFormAdd .form-group').closest('.form-group').removeClass("has-error");
            $('#ofmIVCFormAdd em').remove();
        }else{
            $('#oetIVCDocNo').closest(".form-group").css("cursor","");
            $('#oetIVCDocNo').css("pointer-events","");
            $('#oetIVCDocNo').attr('readonly',false);
            $("#oetIVCDocNo").removeAttr("onfocus");
        }
    });
    /** =============================================================== */

    if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
        $('#CheckStep').addClass('xCNHide');
        $('#odvStp1Div').addClass('xCNHide');
        $('#odvTableStep').css('margin-top','0px');
    }
    if(tChkrount == 'docInvoiceCustomerBillEventEdit'){
        JSxIVCShowBillOnEditEvent();
    }

    if (tStatusDoc == 2) { //ถ้าเป็นเอกสารยกเลิก
        $('.xCNIVCNextStep').hide();
        $('#obtIVCPrintDocStep1').hide();
    }

    $('.selectpicker').selectpicker('refresh');

    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard: true,
        autoclose: true
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    // Doc Date Button Control
    $('#obtIVCRefPanelPaidDate').unbind().click(function(){
        $('#oetIVCPaidDate').datepicker('show');
    });
    $('#obtIVCDocDate').unbind().click(function(){
        $('#oetIVCDocDate').datepicker('show');
    });
    $('#obtIVCDocTime').unbind().click(function(){
        $('#oetIVCDocTime').datetimepicker('show');
    });
    $('#obtSearchBillDateTo').unbind().click(function(){
        $('#oetSearchBillDateTo').datepicker('show');
    });
    $('#obtSearchBillDateFrom').unbind().click(function(){
        $('#oetSearchBillDateFrom').datepicker('show');
    });

    //กดถัดไป (ที่กลมๆ) [TAB LEVEL 1]
    $('.xCNClaimCircle').on('click', function() {
        if (tStatusDoc == 2) { //เอกสารยกเลิก
            return;
        }

        var tTab = $(this).data('tab');

        //เช็คว่ามีข้อมูลหรือยัง
        if (tTab == 'odvInvoiceBillStep2') {
            if ($('#ohdIVCStaPrc').val() == '' || $('#ohdIVCStaPrc').val() == null || $('#ohdIVCStaPrc').val() < 2) {
                $('#odvIVCModalStepNotClear #ospIVCModalStepNotClear').text('กรุณากด "ยืนยันการเคลม" ให้ครบทุกขั้นตอน ก่อนทำรายการถัดไป');
                $('#odvIVCModalStepNotClear').modal('show');
                return;
            }
        }

        //เช็คว่ามีข้อมูลหรือยัง ก่อนจะไป step3
        if (tTab == 'odvInvoiceBillStep3' || tTab == 'odvInvoiceBillStep4') {
            if ($('#ohdIVCStaPrc').val() < 3) {
                $('#odvIVCModalStepNotClear #ospIVCModalStepNotClear').text('กรุณาทำการส่งสินค้าไปยังผู้จำหน่ายให้ครบถ้วน ก่อนทำรายการถัดไป');
                $('#odvIVCModalStepNotClear').modal('show');
                return;
            }
        }

        switch (tTab) {
            case "odvInvoiceBillStep1": {
                $('.xCNInvoiceBillStep1').css("background", "#fff");
                $('.xCNInvoiceBillStep2').css("background", "#d6d6d6");
                $('.xCNInvoiceBillStep3').css("background", "#d6d6d6");
                $('.xCNInvoiceBillStep4').css("background", "#d6d6d6");

                $('.xCNInvoiceBillStep1').css('border', '2px solid #000');
                $('.xCNInvoiceBillStep2').css('border', '2px solid #d6d6d6');
                $('.xCNInvoiceBillStep3').css('border', '2px solid #d6d6d6');
                $('.xCNInvoiceBillStep4').css('border', '2px solid #d6d6d6');

                $('#odvClaimLine').css("background", "#d6d6d6");

                //ปุ่มถัดไป + ย้อนกลับ
                $('.xCNIVCBackStep').css('display', 'none');
                $('.xCNIVCNextStep').css('display', 'inline-block');
                break;
            }
            case "odvInvoiceBillStep2": {
                $('.xCNInvoiceBillStep1').css("background", "#000");
                $('.xCNInvoiceBillStep2').css("background", "#fff");
                $('.xCNInvoiceBillStep3').css("background", "#d6d6d6");
                $('.xCNInvoiceBillStep4').css("background", "#d6d6d6");

                $('.xCNInvoiceBillStep1').css('border', '2px solid #000');
                $('.xCNInvoiceBillStep2').css('border', '2px solid #000');
                $('.xCNInvoiceBillStep3').css('border', '2px solid #d6d6d6');
                $('.xCNInvoiceBillStep4').css('border', '2px solid #d6d6d6');

                $('#odvClaimLine').css("background", "linear-gradient(to right, black 34%, #d6d6d6 20% 40%)");

                //ปุ่มถัดไป + ย้อนกลับ
                $('.xCNIVCBackStep').attr('disabled', false);
                $('.xCNIVCBackStep').css('display', 'inline-block');
                $('.xCNIVCNextStep').css('display', 'inline-block');
                break;
            }
            case "odvInvoiceBillStep3": {
                $('.xCNInvoiceBillStep1').css("background", "#000");
                $('.xCNInvoiceBillStep2').css("background", "#000");
                $('.xCNInvoiceBillStep3').css("background", "#fff");
                $('.xCNInvoiceBillStep4').css("background", "#d6d6d6");

                $('.xCNInvoiceBillStep1').css('border', '2px solid #000');
                $('.xCNInvoiceBillStep2').css('border', '2px solid #000');
                $('.xCNInvoiceBillStep3').css('border', '2px solid #000');
                $('.xCNInvoiceBillStep4').css('border', '2px solid #d6d6d6');

                $('#odvClaimLine').css("background", "linear-gradient(to right, black 66%, #d6d6d6 20% 40%)");

                //ปุ่มถัดไป + ย้อนกลับ
                $('.xCNIVCBackStep').attr('disabled', false);
                $('.xCNIVCBackStep').css('display', 'inline-block');
                $('.xCNIVCNextStep').css('display', 'inline-block');
                break;
            }
            case "odvInvoiceBillStep4": {
                $('.xCNInvoiceBillStep1').css("background", "#000");
                $('.xCNInvoiceBillStep2').css("background", "#000");
                $('.xCNInvoiceBillStep3').css("background", "#000");
                $('.xCNInvoiceBillStep4').css("background", "#fff");

                $('.xCNInvoiceBillStep1').css('border', '2px solid #000');
                $('.xCNInvoiceBillStep2').css('border', '2px solid #000');
                $('.xCNInvoiceBillStep3').css('border', '2px solid #000');
                $('.xCNInvoiceBillStep4').css('border', '2px solid #d6d6d6');

                $('#odvClaimLine').css("background", "linear-gradient(to right, black 100%, #d6d6d6 20% 40%)");

                //ปุ่มถัดไป + ย้อนกลับ
                $('.xCNIVCBackStep').attr('disabled', false);
                $('.xCNIVCBackStep').css('display', 'inline-block');
                $('.xCNIVCNextStep').css('display', 'none');

                //โหลดตารางใหม่
                JSxIVCStep4ResultLoadDatatable();
                break;
            }
            default: {}
        }

        $('.xCNClaimCircle').removeClass('active');
        $(this).addClass('active');
        $(".xCNClaimTabContent .tab-pane").removeClass('active').removeClass('in');
        $(".xCNClaimTabContent #" + tTab).addClass("active").addClass("in");
    });

    //กดถัดไป (ที่ปุ่ม) [TAB LEVEL 1]
    $('.xCNIVCNextStep').on('click', function() {
        var tStepNow = $('#CheckStep .xWPointStep.active').data('step');
        //เช็คว่ามีข้อมูลหรือยัง
        if (tStepNow == 1) {
            var aPdtLent = $("#ohdConfirmIVCInsertPDT").val();
            if (aPdtLent != '') {
                $('.xCNInvoiceBillStep1Point2').trigger('click');
            } else {
                FSvCMNSetMsgErrorDialog("กรุณาเลือกเอกสารที่จะนำมาวางบิล");
                return;
            }
        }

        if (tStepNow >= 1) {
            $('.xCNIVCBackStep').css('display', 'inline-block');
            $('.xCNIVCBackStep').attr('disabled', false);
            
            $('.xCNIVCNextStep').attr('disabled', true);
            $('#odvStep1Search').addClass('xCNHide');
        }

    });


    //กดย้อนกลับ [TAB LEVEL 1]
    $('.xCNIVCBackStep').on('click', function() {
        var tStepNow = $('#CheckStep .xWPointStep.active').data('step');

        if (tStepNow >= 1) {
            $('.xCNInvoiceBillStep1Point1').trigger('click');
            var tStatusDoc = $('#ohdIVCStaDoc').val();
            var tStatusApv = $('#ohdIVCStaApv').val();
            if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                $('.xCNIVCBackStep').attr('disabled', true);
                $('.xCNIVCNextStep').attr('disabled', false);
            }else{
                $('.xCNIVCBackStep').attr('disabled', true);
                $('.xCNIVCNextStep').attr('disabled', false);
                $('#odvStep1Search').removeClass('xCNHide');
            }
            $('.xCNIVCBackStep').attr('disabled', true);
            $('.xCNIVCNextStep').attr('disabled', false);
        }
    });

    $('.xWPointStep').on('click', function() {
        var tStepNow = $('#CheckStep .xWPointStep.active').data('step');
        var tStepNext = $(this).data('step');
        if (tStepNow >= 1) {
            var aPdtLent = $("#ohdConfirmIVCInsertPDT").val();
            if(tStepNext == 2){
                if (aPdtLent != '') {
                    $('.xCNIVCBackStep').attr('disabled', false);
                    $('.xCNIVCNextStep').attr('disabled', true);
                    $('#odvStep1Search').addClass('xCNHide');
                } else {
                    FSvCMNSetMsgErrorDialog("กรุณาเลือกเอกสารที่จะนำมาวางบิล");
                    return;
                }
            }else{
                var tStatusDoc = $('#ohdIVCStaDoc').val();
                var tStatusApv = $('#ohdIVCStaApv').val();
                if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                    $('.xCNIVCBackStep').attr('disabled', true);
                    $('.xCNIVCNextStep').attr('disabled', false);
                }else{
                    $('.xCNIVCBackStep').attr('disabled', true);
                    $('.xCNIVCNextStep').attr('disabled', false);
                    $('#odvStep1Search').removeClass('xCNHide');
                }
            }
        }else{
            $('.xCNIVCBackStep').attr('disabled', true);
            $('.xCNIVCNextStep').attr('disabled', false);
            $('#odvStep1Search').removeClass('xCNHide');
        }
    });

    //เลือกสาขา
    var oBranchOption = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tNextFuncName = poDataFnc.tNextFuncName;
        var tSQLWhere = "";

        tUsrLevel = "<?= $this->session->userdata('tSesUsrLevel') ?>";
        tBchMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        if (tUsrLevel != "HQ") { //แบบสาขา
            tSQLWhere = " AND TCNMBranch.FTBchCode IN (" + tBchMulti + ") ";
        } else { //สำนักงานใหญ่
            // if($('#ohdIVADCode').val() == '' || $('#ohdIVADCode').val() == null){
            //     tSQLWhere += "";
            // }else{
            //     tSQLWhere += " AND (TCNMBranch.FTAgnCode = " + $('#ohdIVADCode').val() +" OR ISNULL(TCNMBranch.FTAgnCode,'') = '' )";
            // }
        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L'],
                On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [tSQLWhere]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode DESC']
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: ['FTBchCode']
            }
        };
        return oOptionReturn;
    }
    $('#obtIVCBrowseBranch').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIVCCustomerOption = undefined;
            oIVCCustomerOption = oBranchOption({
                'tReturnInputCode': 'ohdIVCBchCode',
                'tReturnInputName': 'oetIVCBchName',
                'tNextFuncName': 'JSxWhenSeletedBranch'
            });
            JCNxBrowseData('oIVCCustomerOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    function JSxWhenSeletedBranch() {
        $('#ohdConfirmIVCInsertPDT').val('');
        $('#ohdIVCCSTCode').val('');
        $('#oetIVCCSTName').val('');
        $('#otaIVCAdress').val('');
        $('#oetPanel_CstName').val('');
        $('#oetIVCreditTerm').val('');
        
        $.ajax({
                type: "POST",
                url: "docInvoiceCustomerBillFinding",
                data: {
                    tSPLCode            : '',
                    tCstBchCode         : '',
                    tDocno              : $("#ohdIVCDocNo").val(),
                    tSearchDocType      : '',
                    tSearchDateFrm      : '',
                    tSearchDateTo       : '',
                    tSearchDocNo        : '',
                    tSearchDocRef       : '',
                    tBchCode            : '',
                    tSearchBchFrm       : '',
                    tSearchBchTo        : '',
                    tTypeIn             : '1'
                },
                cache: false,
                Timeout: 0,
                success: function(oResult) {
                    JSxIVCStep1Point1LoadDatatable();
                    JSxIVCStep1Point2LoadDatatable()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
    }

    //เลือกลูกค้า 
    var oIVCCustomer = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tNextFuncName = poDataFnc.tNextFuncName;
        var aArgReturn = poDataFnc.aArgReturn;
        var oOptionReturn = {
            Title: ['customer/customer/customer', 'tCSTTitle'],
            Table: {
                Master: 'TCNMCst',
                PK: 'FTCstCode'
            },
            Join: {
                Table: ['TCNMCst_L', 'TCNMCstAddress_L'],
                On: [
                    'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = ' + nLangEdits,
                    'TCNMCst_L.FTCstCode = TCNMCstAddress_L.FTCstCode'
                ]
            },
            Where: {
                Condition: ["AND TCNMCst.FTCstStaActive = '1' AND (ISNULL(TCNMCstAddress_L.FTAddGrpType,'') = '1' OR ISNULL(TCNMCstAddress_L.FTAddGrpType,'') = '') "]
            },
            GrideView: {
                ColumnPathLang: 'customer/customer/customer',
                ColumnKeyLang: ['tCSTCode', 'tCSTName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName', 'TCNMCst.FTCstTaxNo', 'TCNMCst.FTCstTel', 'TCNMCst.FTCstEmail', 'TCNMCstAddress_L.FTAddV2Desc1'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [2, 3, 4, 5],
                Perpage: 10,
                OrderBy: ['TCNMCst_L.FTCstCode ASC']
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMCst.FTCstCode"],
                Text: [tInputReturnName, "TCNMCst_L.FTCstName"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            }
        };
        return oOptionReturn;
    }
    $('#oimIVCBrowseCustomer').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIVCCustomerOption = undefined;
            oIVCCustomerOption = oIVCCustomer({
                'tReturnInputCode': 'oetIVCFrmCstCode',
                'tReturnInputName': 'oetIVCFrmCstName',
                'tNextFuncName': 'JSxWhenSeletedCustomer',
                'aArgReturn': ['FTCstName', 'FTCstTaxNo', 'FTCstTel', 'FTCstEmail', 'FTAddV2Desc1']
            });
            JCNxBrowseData('oIVCCustomerOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    function JSxWhenSeletedCustomer(poDataNextFunc) {
        var aData;
        if (poDataNextFunc != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            $('#oetIVCFrmCstTel').val((aData[2] == '') ? '-' : aData[2]);
            $('#oetIVCFrmCstEmail').val((aData[3] == '') ? '-' : aData[3]);
            $('#oetIVCFrmCstAddr').val((aData[4] == '') ? '-' : aData[4]);
        }
    }

    $('#oimIVCBrowseCtr').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        $('#oetIVCCtrCode').val('');
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIVCBrowseSPLOption = undefined;
            oIVCBrowseSPLOption = oIVCSPLContactOption({
                'tParamsAgnCode': '<?= $this->session->userdata("tSesUsrAgnCode") ?>',
                'tReturnInputCode': 'oetIVCCtrCode',
                'tReturnInputName': 'oetIVCCtrName',
                'aArgReturn': ['FTSplCode', 'FTCtrName',]
            });
            JCNxBrowseData('oIVCBrowseSPLOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // สาขาลูกค้า
    $('#oimIVCBrowseCstBch').unbind().click(function() {
        var tSPLCode = $("#ohdIVCCSTCode").val();
        if(tSPLCode == ''){
            FSvCMNSetMsgErrorDialog('กรุณาเลือกลูกค้า');
            return;
        }

        let nStaSession = JCNxFuncChkSessionExpired();
        $('#oetIVCCtrCode').val('');
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIVCBrowseSPLOption = undefined;
            oIVCBrowseSPLOption = oIVCCstBchOption({
                'tParamsAgnCode': '<?= $this->session->userdata("tSesUsrAgnCode") ?>',
                'tReturnInputCode': 'oetIVCCstBchFrm',
                'tReturnInputName': 'oetIVCCstBchFrmName',
                'tNextFuncName': 'JSxIVCSetConditionAfterSelectCSTBch',
                'aArgReturn': ['FTCbrBchCode', 'FTCbrBchName']
            });
            JCNxBrowseData('oIVCBrowseSPLOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // สาขาที่ขาย
    $('#oimIVCBrowseCarBchFrm').unbind().click(function() {
        var tSPLCode = $("#ohdIVCCSTCode").val();
        var tCstBchCode = $("#oetIVCCstBchFrm").val();
        if(tSPLCode == ''){
            FSvCMNSetMsgErrorDialog('กรุณาเลือกลูกค้าที่ต้องการวางบิล');
            return;
        }
        if(tCstBchCode == ''){
            FSvCMNSetMsgErrorDialog('กรุณาเลือกสาขาของลูกค้าที่ต้องการวางบิล');
            return;
        }

        let nStaSession = JCNxFuncChkSessionExpired();
        $('#oetIVCCtrCode').val('');
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIVCBrowseSPLOption = undefined;
            oIVCBrowseSPLOption = oIVCCstBchtOption({
                'tParamsAgnCode': '<?= $this->session->userdata("tSesUsrAgnCode") ?>',
                'tReturnInputCode': 'oetIVCCarBchFrm',
                'tReturnInputName': 'oetIVCCarBchFrmName',
            });
            JCNxBrowseData('oIVCBrowseSPLOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    

    $('#oimIVCBrowseCarBchTo').unbind().click(function() {
        var tSPLCode = $("#ohdIVCCSTCode").val();
        var tCstBchCode = $("#oetIVCCstBchFrm").val();
        if(tSPLCode == ''){
            FSvCMNSetMsgErrorDialog('กรุณาเลือกลูกค้า');
            return;
        }
        if(tCstBchCode == ''){
            FSvCMNSetMsgErrorDialog('กรุณาเลือกสาขาของลูกค้าที่ต้องการวางบิล');
            return;
        }

        let nStaSession = JCNxFuncChkSessionExpired();
        $('#oetIVCCtrCode').val('');
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIVCBrowseSPLOption = undefined;
            oIVCBrowseSPLOption = oIVCCstBchtOption({
                'tParamsAgnCode': '<?= $this->session->userdata("tSesUsrAgnCode") ?>',
                'tReturnInputCode': 'oetIVCCarBchTo',
                'tReturnInputName': 'oetIVCCarBchToName',
            });
            JCNxBrowseData('oIVCBrowseSPLOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });


    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสินค้า-รายการสินค้ารับเคลม ##############################
    $('#obtIVCPrintDocStep1').unbind().click(function() {
        let aInfor = [{
                "Lang": '<?= FCNaHGetLangEdit(); ?>'
            },
            {
                "ComCode": '<?= FCNtGetCompanyCode(); ?>'
            },
            {
                "BranchCode": '<?= FCNtGetAddressBranch(@$tIVCBchCode); ?>'
            },
            {
                "DocCode": '<?= @$tIVCDocNo; ?>'
            }, // เลขที่เอกสาร
            {
                "DocBchCode": '<?= @$tIVCBchCode; ?>'
            },
        ];
        window.open("<?= base_url(); ?>formreport/Frm_SQL_SMPdtIVCPdtDetail?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });

    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสินค้า-ส่งรายการสินค้าเครมไปยังผู้จำหน่าย ##############################
    $('#obtIVCPrintDocStep2').unbind().click(function() {
        let aInfor = [{
                "Lang": '<?= FCNaHGetLangEdit(); ?>'
            },
            {
                "ComCode": '<?= FCNtGetCompanyCode(); ?>'
            },
            {
                "BranchCode": '<?= FCNtGetAddressBranch(@$tIVCBchCode); ?>'
            },
            {
                "DocCode": '<?= @$tIVCDocNo; ?>'
            }, // เลขที่เอกสาร
            {
                "DocBchCode": '<?= @$tIVCBchCode; ?>'
            },
        ];
        window.open("<?= base_url(); ?>formreport/Frm_SQL_SMPdtIVCToVendor?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });

    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสืนค้า-รับสินค้ากลับจากผู้จำหน่าย ##############################
    $('#obtIVCPrintDocStep3').unbind().click(function() {
        let aInfor = [{
                "Lang": '<?= FCNaHGetLangEdit(); ?>'
            },
            {
                "ComCode": '<?= FCNtGetCompanyCode(); ?>'
            },
            {
                "BranchCode": '<?= FCNtGetAddressBranch(@$tIVCBchCode); ?>'
            },
            {
                "DocCode": '<?= @$tIVCDocNo; ?>'
            }, // เลขที่เอกสาร
            {
                "DocBchCode": '<?= @$tIVCBchCode; ?>'
            },
        ];
        window.open("<?= base_url(); ?>formreport/Frm_SQL_SMPdtIVCFrmVendor?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });

    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสินค้า-ใบส่งรถลูกค้า ##############################
    $('#obtIVCPrintDocStep4').unbind().click(function() {
        let aInfor = [{
                "Lang": '<?= FCNaHGetLangEdit(); ?>'
            },
            {
                "ComCode": '<?= FCNtGetCompanyCode(); ?>'
            },
            {
                "BranchCode": '<?= FCNtGetAddressBranch(@$tIVCBchCode); ?>'
            },
            {
                "DocCode": '<?= @$tIVCDocNo; ?>'
            }, // เลขที่เอกสาร
            {
                "DocBchCode": '<?= @$tIVCBchCode; ?>'
            },
        ];
        window.open("<?= base_url(); ?>formreport/Frm_SQL_SMPdtIVCCstCarDelivery?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });


    //////////////////////////////////////////////////////////////// เลือกผู้จำหน่าย /////////////////////////////////////////////////////////////

    $('#obtIVCBrowseCST').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        var tCstName = $('#oetPanel_CstName').val();
        if (tCstName != '') {
            $('#tWarningText').text('<?=language('document/invoicebill/invoicebill', 'tIVCWarningText') ?>');
            $('#tWarningTextCfm').text('<?=language('document/invoicebill/invoicebill', 'tIVCWarningTextCfm')?>');
            $('.xCNBtnControllHide').show();
            $('#odvIVCPopupWarning').modal('show');
        } else {
            if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
                JSxCheckPinMenuClose();
                window.oIVCBrowseSPLOption = undefined;
                oIVCBrowseSPLOption = oIVCCSTOption({
                    'tParamsAgnCode': '<?= $this->session->userdata("tSesUsrAgnCode") ?>',
                    'tReturnInputCode': 'ohdIVCCSTCode',
                    'tReturnInputName': 'oetIVCCSTName',
                    'tNextFuncName': 'JSxIVCSetConditionAfterSelectCST',
                    'aArgReturn': ['FTCstCode', 'FTCstName']
                });
                JCNxBrowseData('oIVCBrowseSPLOption');
            } else {
                JCNxShowMsgSessionExpired();
            }
        }
    });

    var oIVCCSTOption = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var aArgReturn = poDataFnc.aArgReturn;
        var tParamsAgnCode = poDataFnc.tParamsAgnCode;
        var tNextFuncName = poDataFnc.tNextFuncName;
        var aArgReturn = poDataFnc.aArgReturn;

        var tWhereAgency = '';
        if (tParamsAgnCode != "") {
            tWhereAgency = " AND ( TCNMCst.FTAgnCode = '" + tParamsAgnCode + "' OR ISNULL(TCNMCst.FTAgnCode,'') = '' ) ";
        } else {
            tWhereAgency = "";
        }

        var oOptionReturn = {
            Title: ['customer/customer/customer', 'tCSTTitle'],
            Table: {
                Master: 'TCNMCst',
                PK: 'FTCstCode'
            },
            Join: {
                Table: ['TCNMCst_L', 'TCNMCstCredit'],
                On: [
                    'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = ' + nLangEdits,
                    'TCNMCst_L.FTCstCode = TCNMCstCredit.FTCstCode'
                ]
            },
            Where: {
                Condition: [" AND TCNMCst.FTCstStaActive = '1' AND TCNMCst.FTClvCode = '00002' " + tWhereAgency]
            },
            GrideView: {
                ColumnPathLang: 'customer/customer/customer',
                ColumnKeyLang: ['tCSTCode', 'tCSTName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName', 'TCNMCstCredit.FNCstCrTerm', 'TCNMCstCredit.FCCstCrLimit', 'TCNMCstCredit.FTCstTspPaid'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [2, 3, 4, 5, 6],
                Perpage: 10,
                OrderBy: ['TCNMCst.FDCreateOn DESC']
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMCst.FTCstCode"],
                Text: [tInputReturnName, "TCNMCst_L.FTCstName"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            }
        };
        return oOptionReturn;
    }

    function JSxIVCSetConditionAfterSelectCST(poDataNextFunc) {
        aDataNF = JSON.parse(poDataNextFunc);
        tCstCode = aDataNF[0];
        tCstName = aDataNF[1];
        var tDataNextFunc = "NextFunc";
        $.ajax({
            type: "POST",
            url: "docInvoiceCustomerBillFindingCstBch",
            data: {
                tCSTCode            : tCstCode
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                aData = JSON.parse(oResult);
                if (aData['aChkData']['row'] == 1) {
                    $.ajax({
                        type: "POST",
                        url: "docInvoiceCustomerBillFindingCstAddress",
                        data: {
                            tCSTCode            : tCstCode,
                            tCSTBchCode         : aData['aChkData']['rtResult'][0]['FTCbrBchCode']
                        },
                        cache: false,
                        Timeout: 0,
                        success: function(oResult) {
                            aDataAddress = JSON.parse(oResult);
                            if (aDataAddress['rtCode'] == 999) {
                                $('#tWarningText').text('<?=language('document/invoicebill/invoicebill', 'ลูกค้าที่คุณเลือกไม่มีที่อยู่ กรุณาเลือกลูกค้าใหม่') ?>');
                                $('#tWarningTextCfm').text('');
                                $('.xCNBtnControllHide').hide();
                                $('#odvIVCPopupWarning').modal('show');
                            }else{
                                $('#oetIVCCstBchFrm').val(aData['aChkData']['rtResult'][0]['FTCbrBchCode']);
                                $('#oetIVCCstBchFrmName').val(aData['aChkData']['rtResult'][0]['FTCbrBchName']);
                                $('#oimIVCBrowseCstBch').addClass('disabled');

                                $('#oetPanel_CstName').val(tCstName);
                                $('#oetIVCreditTerm').val(aDataAddress[0]['FNCstCrTerm']);
                                if (aDataAddress[0]['FNCstCrTerm'] != '' && aDataAddress[0]['FNCstCrTerm'] > 0) {
                                    $("#ocmIVPaymentType.selectpicker").val("2").selectpicker("refresh");
                                }
                                // FTAddVersion
                                var AddressfromCst = aDataAddress[0]['FTAddVersion']
                                
                                var tAddfull ='';
                                if(AddressfromCst == '1'){
                                    tAddfull = aDataAddress[0]['FTAddV1No']+' '+aDataAddress[0]['FTAddV1Soi']+' '+aDataAddress[0]['FTAddV1Village']+' '+aDataAddress[0]['FTAddV1Road']+' '+aDataAddress[0]['FTSudName']+' '+aDataAddress[0]['FTDstName']+' '+aDataAddress[0]['FTPvnName'];
                                }else if(AddressfromCst == '2'){
                                    tAddfull = aDataAddress[0]['FTAddV2Desc1']+' '+aDataAddress[0]['FTAddV2Desc2'];
                                }else{
                                    tAddfull = "-";
                                }
                                $('#otaIVCAdress').val(tAddfull);
                                $('#oetPanel_CstTell').val(aDataAddress[0]['FTCstTel']);
                                $('#oetPanel_CstMail').val(aDataAddress[0]['FTCstEmail']);
                                JSxIVCFindingBill(tDataNextFunc);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }else{
                    $.ajax({
                        type: "POST",
                        url: "docInvoiceCustomerBillFindingCstAddress",
                        data: {
                            tCSTCode            : tCstCode,
                            tCSTBchCode         : ''
                        },
                        cache: false,
                        Timeout: 0,
                        success: function(oResult) {
                            aDataAddress = JSON.parse(oResult);
                            if (aDataAddress['rtCode'] == 999) {
                                $('#tWarningText').text('<?=language('document/invoicebill/invoicebill', 'ลูกค้าที่คุณเลือกไม่มีที่อยู่ กรุณาเลือกลูกค้าใหม่') ?>');
                                $('#tWarningTextCfm').text('');
                                $('.xCNBtnControllHide').hide();
                                $('#odvIVCPopupWarning').modal('show');
                            }else{
                                if (aData['aChkData']['row'] == 0) {
                                    $('#oimIVCBrowseCstBch').addClass('disabled');
                                    $('#oimIVCBrowseCstBch').attr('disabled',true);
                                } else {
                                    $('#oimIVCBrowseCstBch').removeClass('disabled');
                                    $('#oimIVCBrowseCstBch').removeAttr('disabled');
                                }

                                $('#oetPanel_CstName').val(tCstName);
                                $('#oetIVCreditTerm').val(aDataAddress[0]['FNCstCrTerm']);
                                if (aDataAddress[0]['FNCstCrTerm'] != '' && aDataAddress[0]['FNCstCrTerm'] > 0) {
                                    $("#ocmIVPaymentType.selectpicker").val("2").selectpicker("refresh");
                                }
                                // FTAddVersion
                                var AddressfromCst = aDataAddress[0]['FTAddVersion']
                                var tAddfull ='';
                                if(AddressfromCst == '1'){
                                    tAddfull = aDataAddress[0]['FTAddV1No']+' '+aDataAddress[0]['FTAddV1Soi']+' '+aDataAddress[0]['FTAddV1Village']+' '+aDataAddress[0]['FTAddV1Road']+' '+aDataAddress[0]['FTSudName']+' '+aDataAddress[0]['FTDstName']+' '+aDataAddress[0]['FTPvnName'];
                                }else if(AddressfromCst == '2'){
                                    tAddfull = aDataAddress[0]['FTAddV2Desc1']+' '+aDataAddress[0]['FTAddV2Desc2'];
                                }else{
                                    tAddfull = "-";
                                }
                                $('#otaIVCAdress').val(tAddfull);
                                $('#oetPanel_CstTell').val(aDataAddress[0]['FTCstTel']);
                                $('#oetPanel_CstMail').val(aDataAddress[0]['FTCstEmail']);
                                JSxIVCFindingBill(tDataNextFunc);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    var oIVCSPLContactOption = function(poDataFnc) {
        var tsplCode = $("#ohdIVCCSTCode").val();
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tNextFuncName = poDataFnc.tNextFuncName;
        var aArgReturn = poDataFnc.aArgReturn;
        var tParamsAgnCode = poDataFnc.tParamsAgnCode;

        var oOptionReturn = {
            Title: ['supplier/supplier/supplier', 'tTitleSplContr'],
            Table: {
                Master: 'TCNMSplContact_L',
                PK: 'FNCtrSeq'
            },
            Where: {
                Condition: [" AND TCNMSplContact_L.FTSplCode = '" + tsplCode + "'"]
            },
            GrideView: {
                ColumnPathLang: 'supplier/supplier/supplier',
                ColumnKeyLang: ['tSPLTBCode', 'tSPLTBConName', 'tSPLTBConMail', 'tSPLTBConTel', 'tSPLTBConFax'],
                ColumnsSize: [],
                WidthModal: 50,
                DataColumns: ['TCNMSplContact_L.FTSplCode', 'TCNMSplContact_L.FTCtrName', 'TCNMSplContact_L.FTCtrEmail', 'TCNMSplContact_L.FTCtrTel', 'TCNMSplContact_L.FTCtrFax', 'TCNMSplContact_L.FNCtrSeq'],
                DataColumnsFormat: ['', '' , '' , '' , ''],
                DisabledColumns: [5],
                Perpage: 10,
                OrderBy: ['TCNMSplContact_L.FDCreateOn DESC']
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMSplContact_L.FNCtrSeq"],
                Text: [tInputReturnName, "TCNMSplContact_L.FTCtrName"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            }
        };
        return oOptionReturn;
    }

    var oIVCCstBchOption = function(poDataFnc) {
        var tCstCode = $("#ohdIVCCSTCode").val();
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tNextFuncName = poDataFnc.tNextFuncName;
        var aArgReturn = poDataFnc.aArgReturn;
        var tParamsAgnCode = poDataFnc.tParamsAgnCode;

        var oOptionReturn = {
            Title   : ['company/branch/branch','tBCHTitle'],
            Table: {
                Master: 'TCNMCstBch',
                PK: 'FTCbrBchCode'
            },
            Where: {
                Condition: [" AND TCNMCstBch.FTCSTCode = '" + tCstCode + "'"]
            },
            GrideView: {
                ColumnPathLang : 'company/branch/branch',
                ColumnKeyLang : ['tBCHCode','tBCHName','tBCHCode'],
                ColumnsSize     : ['15%','75%',''],
                WidthModal: 50,
                DataColumns: ['TCNMCstBch.FTCbrBchCode', 'TCNMCstBch.FTCbrBchName','TCNMCstBch.FNCbrSeq'],
                DataColumnsFormat: ['','',''],
                DisabledColumns   : [2],
                Perpage: 10,
                OrderBy: ['TCNMCstBch.FDCreateOn DESC']
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMCstBch.FTCbrBchCode"],
                Text: [tInputReturnName, "TCNMCstBch.FTCbrBchName"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            }
        };
        return oOptionReturn;
    }

    var oIVCCstBchtOption = function(poDataFnc) {
        var tCstCode = $("#ohdIVCCSTCode").val();
        var tCstBchCode = $("#oetIVCCstBchFrm").val();
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tParamsAgnCode = poDataFnc.tParamsAgnCode;

        var oOptionReturn = {
            Title   : ['company/branch/branch','tBCHTitle'],
            Table: {
                Master: 'TPSTSalHD',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TPSTSalHDCst', 'TCNMBranch_L'],
                On: [
                    'TPSTSalHD.FTBchCode = TPSTSalHDCst.FTBchCode',
                    'TPSTSalHD.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID =  ' + nLangEdits
                ]
            },
            Where: {
                Condition: [" AND TPSTSalHD.FTCstCode = '" + tCstCode + "' AND TPSTSalHDCst.FTXshCstRef = '" + tCstBchCode + "'"]
            },
            GrideView: {
                ColumnPathLang : 'company/branch/branch',
                ColumnKeyLang : ['tBCHCode','tBCHName'],
                ColumnsSize     : ['15%','75%'],
                WidthModal: 50,
                DataColumns: ['TCNMBranch_L.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DistinctField: [0],
                DataColumnsFormat: ['',''],
                Perpage: 10,
                OrderBy: ['TCNMBranch_L.FTBchCode DESC']
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch_L.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
        };
        return oOptionReturn;
    }

    //หลังจากเลือกผู้จำหน่าย
    function JSxIVCSetConditionAfterSelectCSTBch(poDataNextFunc) {
        var aData;
        var tSearchDocType = $("#ocmIVCBillType").val();
        var tSearchDateFrm = $("#oetSearchBillDateFrom").val();
        var tSearchDateTo  = $("#oetSearchBillDateTo").val();
        var tSearchDocNo   = $("#oetIVSearchDocno").val();
        var tSearchDocRef  = $("#oetIVSearchDocRef").val();
        var tBchCode       = $("#ohdIVCBchCode").val();
        var tSearchBchFrm = $("#oetIVCCarBchFrm").val();
        var tSearchBchTo  = $("#oetIVCCarBchTo").val();
        var tCstCode      = $("#ohdIVCCSTCode").val();
        var tCstName      = $("#oetIVCCSTName").val();
        var tCstBchCode     = $("#oetIVCCstBchFrm").val();
        $('#ohdConfirmIVCInsertPDT').val('');

        aData = JSON.parse(poDataNextFunc);
        $.ajax({
            type: "POST",
            url: "docInvoiceCustomerBillFindingCstAddress",
            data: {
                tCSTCode            : tCstCode,
                tCSTBchCode         : aData[0]
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                aData2 = JSON.parse(oResult);
                $('#oetPanel_CstName').val(tCstName);
                $('#oetIVCreditTerm').val(aData[0]['FNCstCrTerm']);
                if (aData[0]['FNCstCrTerm'] != '' && aData[0]['FNCstCrTerm'] > 0) {
                    $("#ocmIVPaymentType.selectpicker").val("2").selectpicker("refresh");
                }
                // FTAddVersion
                var AddressfromCst = aData2[0]['FTAddVersion']
                var tAddfull ='';
                if(AddressfromCst == '1'){
                    tAddfull = aData2[0]['FTAddV1No']+' '+aData2[0]['FTAddV1Soi']+' '+aData2[0]['FTAddV1Village']+' '+aData2[0]['FTAddV1Road']+' '+aData2[0]['FTSudName']+' '+aData2[0]['FTDstName']+' '+aData2[0]['FTPvnName'];
                }else if(AddressfromCst == '2'){
                    tAddfull = aData2[0]['FTAddV2Desc1']+' '+aData2[0]['FTAddV2Desc2'];
                }else{
                    tAddfull = "-";
                }
                $('#otaIVCAdress').val(tAddfull);
                $('#oetPanel_CstTell').val(aData2[0]['FTCstTel']);
                $('#oetPanel_CstMail').val(aData2[0]['FTCstEmail']);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
        JSxIVCFindingBill(poDataNextFunc)
    }
    
    function JSxIVCFindingBill(poDataNextFunc) {

        var tSearchDocType = $("#ocmIVCBillType").val();
        var tSearchDateFrm = $("#oetSearchBillDateFrom").val();
        var tSearchDateTo  = $("#oetSearchBillDateTo").val();
        var tSearchDocNo   = $("#oetIVSearchDocno").val();
        var tSearchDocRef  = $("#oetIVSearchDocRef").val();
        var tBchCode       = $("#ohdIVCBchCode").val();
        var tSearchBchFrm = $("#oetIVCCarBchFrm").val();
        var tSearchBchTo  = $("#oetIVCCarBchTo").val();
        var tCstCode      = $("#ohdIVCCSTCode").val();
        var tCstName      = $("#oetIVCCSTName").val();
        var tCstBchCode     = $("#oetIVCCstBchFrm").val();

        if (poDataNextFunc != "NULL") {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docInvoiceCustomerBillFinding",
                data: {
                    tSPLCode            : tCstCode,
                    tCstBchCode         : tCstBchCode,
                    tDocno              : $("#ohdIVCDocNo").val(),
                    tSearchDocType      : tSearchDocType,
                    tSearchDateFrm      : tSearchDateFrm,
                    tSearchDateTo       : tSearchDateTo,
                    tSearchDocNo        : tSearchDocNo,
                    tSearchDocRef       : tSearchDocRef,
                    tBchCode            : tBchCode,
                    tSearchBchFrm       : tSearchBchFrm,
                    tSearchBchTo        : tSearchBchTo,
                    tTypeIn             : '1'
                },
                cache: false,
                Timeout: 0,
                success: function(oResult) {
                    var aResult = JSON.parse(oResult);
                    if(aResult.aContactSPL.rtCode == 1){
                        var aResultContact = aResult.aContactSPL;
                        $('#oetIVCCtrCode').val(aResultContact.rtResult.FNCtrSeq);
                        $('#oetIVCCtrName').val(aResultContact.rtResult.FTCtrName);
                    }else{
                        $('#oetIVCCtrCode').val('');
                        $('#oetIVCCtrName').val('');
                    }
                    JCNxCloseLoading();
                    JSxIVCStep1Point1LoadDatatable();
                    JSxIVCStep1Point2LoadDatatable()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //ล้างข้อมูลการค้นหา
    function JSxIVCResetFilter() {

        $("#oetSearchBillDateFrom").val('');
        $("#oetSearchBillDateTo").val('');
        $("#oetIVSearchDocno").val('');
        $("#oetIVSearchDocRef").val('');
        $("#oetIVSearchDocRef").val('');
        $("#oetIVCCarBchFrm").val('');
        $("#oetIVCCarBchFrmName").val('');
        $("#oetIVCCarBchTo").val('');
        $("#oetIVCCarBchToName").val('');
        JSxIVCShowBillOnEditEventSearch();

    }

    //แสดงข้อมูลหลังจากเข้าหน้า Edit
    function JSxIVCShowBillOnEditEvent() {
        var tSPLCode = $("#ohdIVCCSTCode").val();
        var tCstBchCode = $("#oetIVCCstBchFrm").val();
        
        if(tSPLCode == ''){
            FSvCMNSetMsgErrorDialog('กรุณาเลือกลูกค้า');
            return;
        }

        var tSearchDocType = $("#ocmIVCBillType").val();
        var tSearchDateFrm = $("#oetSearchBillDateFrom").val();
        var tSearchDateTo  = $("#oetSearchBillDateTo").val();
        var tSearchDocNo   = $("#oetIVSearchDocno").val();
        var tSearchDocRef  = $("#oetIVSearchDocRef").val();
        var tBchCode       = $("#ohdIVCBchCode").val();
        var tSearchBchFrm = $("#oetIVCCarBchFrm").val();
        var tSearchBchTo  = $("#oetIVCCarBchTo").val();

        if((tStatusDoc == 1 && tStatusApv == 1)){
            var tType = '2';
        }else{
            var tType = '1';
        }

        $.ajax({
            type: "POST",
            url: "docInvoiceCustomerBillFinding",
            data: {
                tSPLCode            : $("#ohdIVCCSTCode").val(),
                tCstBchCode         : tCstBchCode,
                tDocno              : $("#ohdIVCDocNo").val(),
                tSearchDocType      : tSearchDocType,
                tSearchDateFrm      : tSearchDateFrm,
                tSearchDateTo       : tSearchDateTo,
                tSearchDocNo        : tSearchDocNo,
                tSearchDocRef       : tSearchDocRef,
                tBchCode            : tBchCode,
                tSearchBchFrm       : tSearchBchFrm,
                tSearchBchTo        : tSearchBchTo,
                tTypeIn             : tType
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                JSxIVCStep1Point1LoadDatatable();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    }

    function JSxIVCShowBillOnEditEventSearch() {
        JCNxOpenLoading();
        var tSPLCode = $("#ohdIVCCSTCode").val();
        var tCstBchCode = $("#oetIVCCstBchFrm").val();
        if(tSPLCode == ''){
            FSvCMNSetMsgErrorDialog('กรุณาเลือกลูกค้า');
            return;
        }

        var tSearchDocType = $("#ocmIVCBillType").val();
        var tSearchDateFrm = $("#oetSearchBillDateFrom").val();
        var tSearchDateTo  = $("#oetSearchBillDateTo").val();
        var tSearchDocNo   = $("#oetIVSearchDocno").val();
        var tSearchDocRef  = $("#oetIVSearchDocRef").val();
        var tBchCode       = $("#ohdIVCBchCode").val();
        var tSearchBchFrm = $("#oetIVCCarBchFrm").val();
        var tSearchBchTo  = $("#oetIVCCarBchTo").val();

        $.ajax({
            type: "POST",
            url: "docInvoiceCustomerBillFinding",
            data: {
                tSPLCode            : $("#ohdIVCCSTCode").val(),
                tCstBchCode         : tCstBchCode,
                tDocno              : $("#ohdIVCDocNo").val(),
                tSearchDocType      : tSearchDocType,
                tSearchDateFrm      : tSearchDateFrm,
                tSearchDateTo       : tSearchDateTo,
                tSearchDocNo        : tSearchDocNo,
                tSearchDocRef       : tSearchDocRef,
                tSearchBchFrm       : tSearchBchFrm,
                tSearchBchTo        : tSearchBchTo,
                tBchCode            : tBchCode,
                tTypeIn             : '1'
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                JSxIVCStep1Point1LoadDatatableSearch();
                localStorage.removeItem("IVC_LocalItemDataInsertDtTemp");
                $('.ocbListItem').prop('checked', false);
                $('#ohdConfirmIVCInsertPDT').val('');
                $('.xCNInvoiceBillStep1Point2').attr('data-toggle','false');
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    }

    
    //เลือกคอลัมน์ในฐานข้อมูล เก็บไว้ใน localstorage [หลายรายการ]
    function FSxQTSelectMulInsert(ptElm) {
       
       let tDocNo = $('#ohdIVCDocNo').val();
       let tPdtCode = $(ptElm).parents('.xWPdtItemPoint1').data('pdtcode');
       $(ptElm).prop('checked', true);
       let oLocalItemDTTemp = localStorage.getItem("IVC_LocalItemDataInsertDtTemp");
       let oDataObj = [];
       if (oLocalItemDTTemp) {
           oDataObj = JSON.parse(oLocalItemDTTemp);
       }
       let aArrayConvert = [JSON.parse(localStorage.getItem("IVC_LocalItemDataInsertDtTemp"))];
       if (aArrayConvert == '' || aArrayConvert == null) {
           oDataObj.push({
               'tDocNo': tDocNo,
               'tPdtCode': tPdtCode,
           });
           localStorage.setItem("IVC_LocalItemDataInsertDtTemp", JSON.stringify(oDataObj));
           JSxIVCextInModalInsertPdtDtTemp();
       } else {
           var aReturnRepeat = JStIVCFindObjectByKey(aArrayConvert[0], 'tPdtCode', tPdtCode);
           if (aReturnRepeat == 'None') {
               //ยังไม่ถูกเลือก
               oDataObj.push({
                   'tDocNo': tDocNo,
                   'tPdtCode': tPdtCode,
               });
               localStorage.setItem("IVC_LocalItemDataInsertDtTemp", JSON.stringify(oDataObj));
               JSxIVCextInModalInsertPdtDtTemp();
           } else if (aReturnRepeat == 'Dupilcate') {
               localStorage.removeItem("IVC_LocalItemDataInsertDtTemp");
               $(ptElm).prop('checked', false);
               var nLength = aArrayConvert[0].length;
               for ($i = 0; $i < nLength; $i++) {
                   if (aArrayConvert[0][$i].tPdtCode == tPdtCode) {
                       delete aArrayConvert[0][$i];
                   }
               }
               var aNewarraydata = [];
               for ($i = 0; $i < nLength; $i++) {
                   if (aArrayConvert[0][$i] != undefined) {
                       aNewarraydata.push(aArrayConvert[0][$i]);
                   }
               }
               localStorage.setItem("IVC_LocalItemDataInsertDtTemp", JSON.stringify(aNewarraydata));
               JSxIVCextInModalInsertPdtDtTemp();
           }
       }
   }

   
    //ลบคอลัมน์ในฐานข้อมูล เก็บค่าใน Modal [หลายรายการ]
    function JSxIVCextInModalInsertPdtDtTemp() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("IVC_LocalItemDataInsertDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
            $('#ohdConfirmIVCInsertPDT').val('');
            $('.xCNInvoiceBillStep1Point2').attr('data-toggle','false');
        } else {
            $('.xCNInvoiceBillStep1Point2').attr('data-toggle','tab');
            var tIVCextDocNo = "";
            var tIVCextPdtCode = "";
            $.each(aArrayConvert[0], function(nKey, aValue) {
                tIVCextDocNo += aValue.tDocNo;
                tIVCextDocNo += " , ";

                tIVCextPdtCode += aValue.tPdtCode;
                tIVCextPdtCode += " , ";

            });
            $('#ohdConfirmIVCInsertPDT').val(tIVCextPdtCode);
            // alert($("#ohdConfirmIVCInsertPDT").val());
        }
    }

    //พิมพ์เอกสาร
    function JSxIVPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tIVCBchCode); ?>'},
            {"DocCode"      : '<?=@$tIVCDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tIVCBchCode;?>'}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMSBillStateMent?infor=" + JCNtEnCodeUrlParameter(aInfor) , '_blank');
    }

    //เคลียค่า
    function JSxIVCPopupWarning(pbIsConfirm){
        var tDocNo = $('#oetIVCDocNo').val();
        $.ajax({
            type: "POST",
            url: "docInvoiceCustomerBillClearTemp",
            data: {
                tDocNo : tDocNo
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                var tDocNo = $('#ohdIVCDocNo').val();
                var tDocDate = $('#ohdIVCDocDate').val();
                var tDocTime = $('#ohdIVCDocTime').val();
                
                $(".xCNInvoiceBillStep1Point1").click();
                $('.ocbListItem').prop('checked', false);
                $('.form-control').val('');
                $('#oetIVCDocNo').val(tDocNo);
                $('#oetIVCDocDate').val(tDocDate);
                $('#oetIVCDocTime').val(tDocTime);
                $("#ocmIVPaymentType.selectpicker").val("1").selectpicker("refresh");
                $("#ocmIVDstPaid.selectpicker").val("1").selectpicker("refresh");
                $('#ohdConfirmIVCInsertPDT').val('');
                $('.xCNInvoiceBillStep1Point2').attr('data-toggle','false');
                $('#odvIVCPopupWarning').modal('hide');
                $('#obtIVCBrowseCST').click();
                JSxIVCStep1Point1LoadDatatable();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    }

</script>