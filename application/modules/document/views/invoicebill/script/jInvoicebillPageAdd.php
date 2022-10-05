<script>
    var tStatusDoc = $('#ohdIVBStaDoc').val();
    var tChkrount = $('#ohdIVBRoute').val();
    var nAddressVersion = '<?=FCNaHAddressFormat('TCNMSpl')?>';
    var tStatusDoc = $('#ohdIVBStaDoc').val();
    var tStatusApv = $('#ohdIVBStaApv').val();
    if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
        $('#CheckStep').addClass('xCNHide');
        $('#odvStp1Div').addClass('xCNHide');
        $('#odvTableStep').css('margin-top','0px');
    }
    if(tChkrount == 'docInvoiceBillEventEdit'){
        JSxIVBShowBillOnEditEvent();
    }

    if (tStatusDoc == 2) { //ถ้าเป็นเอกสารยกเลิก
        $('.xCNIVBNextStep').hide();
        $('#obtIVBPrintDocStep1').hide();
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
    $('#obtIVBRefPanelPaidDate').unbind().click(function(){
        $('#oetIVBPaidDate').datepicker('show');
    });
    $('#obtIVBDocDate').unbind().click(function(){
        $('#oetIVBDocDate').datepicker('show');
    });
    $('#obtIVBDocTime').unbind().click(function(){
        $('#oetIVBDocTime').datetimepicker('show');
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
            if ($('#ohdIVBStaPrc').val() == '' || $('#ohdIVBStaPrc').val() == null || $('#ohdIVBStaPrc').val() < 2) {
                $('#odvIVBModalStepNotClear #ospIVBModalStepNotClear').text('กรุณากด "ยืนยันการเคลม" ให้ครบทุกขั้นตอน ก่อนทำรายการถัดไป');
                $('#odvIVBModalStepNotClear').modal('show');
                return;
            }
        }

        //เช็คว่ามีข้อมูลหรือยัง ก่อนจะไป step3
        if (tTab == 'odvInvoiceBillStep3' || tTab == 'odvInvoiceBillStep4') {
            if ($('#ohdIVBStaPrc').val() < 3) {
                $('#odvIVBModalStepNotClear #ospIVBModalStepNotClear').text('กรุณาทำการส่งสินค้าไปยังผู้จำหน่ายให้ครบถ้วน ก่อนทำรายการถัดไป');
                $('#odvIVBModalStepNotClear').modal('show');
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
                $('.xCNIVBBackStep').css('display', 'none');
                $('.xCNIVBNextStep').css('display', 'inline-block');
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
                $('.xCNIVBBackStep').attr('disabled', false);
                $('.xCNIVBBackStep').css('display', 'inline-block');
                $('.xCNIVBNextStep').css('display', 'inline-block');
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
                $('.xCNIVBBackStep').attr('disabled', false);
                $('.xCNIVBBackStep').css('display', 'inline-block');
                $('.xCNIVBNextStep').css('display', 'inline-block');
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
                $('.xCNIVBBackStep').attr('disabled', false);
                $('.xCNIVBBackStep').css('display', 'inline-block');
                $('.xCNIVBNextStep').css('display', 'none');

                //โหลดตารางใหม่
                JSxIVBStep4ResultLoadDatatable();
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
    $('.xCNIVBNextStep').on('click', function() {
        var tStepNow = $('#CheckStep .xWPointStep.active').data('step');

        //เช็คว่ามีข้อมูลหรือยัง
        if (tStepNow == 1) {
            var aPdtLent = $("#ohdConfirmIVBInsertPDT").val();
            if (aPdtLent != '') {
                $('.xCNInvoiceBillStep1Point2').trigger('click');
            } else {
                FSvCMNSetMsgErrorDialog("กรุณาเลือกเอกสารที่จะนำมาวางบิล");
                return;
            }
        }

        if (tStepNow >= 1) {
            $('.xCNIVBBackStep').css('display', 'inline-block');
            $('.xCNIVBBackStep').attr('disabled', false);
            
            $('.xCNIVBNextStep').attr('disabled', true);
            $('#odvStep1Search').addClass('xCNHide');
        }

    });


    //กดย้อนกลับ [TAB LEVEL 1]
    $('.xCNIVBBackStep').on('click', function() {
        var tStepNow = $('#CheckStep .xWPointStep.active').data('step');

        if (tStepNow >= 1) {
            $('.xCNInvoiceBillStep1Point1').trigger('click');
            var tStatusDoc = $('#ohdIVBStaDoc').val();
            var tStatusApv = $('#ohdIVBStaApv').val();
            if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                $('.xCNIVBBackStep').attr('disabled', true);
                $('.xCNIVBNextStep').attr('disabled', false);
            }else{
                $('.xCNIVBBackStep').attr('disabled', true);
                $('.xCNIVBNextStep').attr('disabled', false);
                $('#odvStep1Search').removeClass('xCNHide');
            }
            $('.xCNIVBBackStep').attr('disabled', true);
            $('.xCNIVBNextStep').attr('disabled', false);
        }
    });

    $('.xWPointStep').on('click', function() {
        var tStepNow = $('#CheckStep .xWPointStep.active').data('step');
        var tStepNext = $(this).data('step');
        if (tStepNow >= 1) {
            var aPdtLent = $("#ohdConfirmIVBInsertPDT").val();
            if(tStepNext == 2){
                if (aPdtLent != '') {
                    $('.xCNIVBBackStep').attr('disabled', false);
                    $('.xCNIVBNextStep').attr('disabled', true);
                    $('#odvStep1Search').addClass('xCNHide');
                } else {
                    FSvCMNSetMsgErrorDialog("กรุณาเลือกเอกสารที่จะนำมาวางบิล");
                    return;
                }
            }else{
                var tStatusDoc = $('#ohdIVBStaDoc').val();
                var tStatusApv = $('#ohdIVBStaApv').val();
                if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                    $('.xCNIVBBackStep').attr('disabled', true);
                    $('.xCNIVBNextStep').attr('disabled', false);
                }else{
                    $('.xCNIVBBackStep').attr('disabled', true);
                    $('.xCNIVBNextStep').attr('disabled', false);
                    $('#odvStep1Search').removeClass('xCNHide');
                }
            }
        }else{
            $('.xCNIVBBackStep').attr('disabled', true);
            $('.xCNIVBNextStep').attr('disabled', false);
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
    $('#obtIVBBrowseBranch').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIVBCustomerOption = undefined;
            oIVBCustomerOption = oBranchOption({
                'tReturnInputCode': 'ohdIVBBchCode',
                'tReturnInputName': 'oetIVBBchName',
                'tNextFuncName': 'JSxWhenSeletedBranch'
            });
            JCNxBrowseData('oIVBCustomerOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    function JSxWhenSeletedBranch() {
        $('#ohdConfirmIVBInsertPDT').val('');
        $('#ohdIVBSPLCode').val('');
        $('#oetIVBSPLName').val('');
        $('#otaIVBAdress').val('');
        $('#oetPanel_SplName').val('');
        $('#oetIVCreditTerm').val('');
        
        $.ajax({
                type: "POST",
                url: "docInvoiceBillFinding",
                data: {
                    tSPLCode            : '',
                    tDocno              : $("#ohdIVBDocNo").val(),
                    tSearchDocType      : '',
                    tSearchDateFrm      : '',
                    tSearchDateTo       : '',
                    tSearchDocNo        : '',
                    tSearchDocRef       : '',
                    tBchCode            : '',
                    tTypeIn             : '1'
                },
                cache: false,
                Timeout: 0,
                success: function(oResult) {
                    JSxIVBStep1Point1LoadDatatable();
                    JSxIVBStep1Point2LoadDatatable()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
    }

    //เลือกลูกค้า 
    var oIVBCustomer = function(poDataFnc) {
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
    $('#oimIVBBrowseCustomer').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIVBCustomerOption = undefined;
            oIVBCustomerOption = oIVBCustomer({
                'tReturnInputCode': 'oetIVBFrmCstCode',
                'tReturnInputName': 'oetIVBFrmCstName',
                'tNextFuncName': 'JSxWhenSeletedCustomer',
                'aArgReturn': ['FTCstName', 'FTCstTaxNo', 'FTCstTel', 'FTCstEmail', 'FTAddV2Desc1']
            });
            JCNxBrowseData('oIVBCustomerOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    function JSxWhenSeletedCustomer(poDataNextFunc) {
        var aData;
        if (poDataNextFunc != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            $('#oetIVBFrmCstTel').val((aData[2] == '') ? '-' : aData[2]);
            $('#oetIVBFrmCstEmail').val((aData[3] == '') ? '-' : aData[3]);
            $('#oetIVBFrmCstAddr').val((aData[4] == '') ? '-' : aData[4]);
        }
    }

    $('#oimIVBBrowseCtr').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        $('#oetIVBCtrCode').val('');
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIVBBrowseSPLOption = undefined;
            oIVBBrowseSPLOption = oIVBSPLContactOption({
                'tParamsAgnCode': '<?= $this->session->userdata("tSesUsrAgnCode") ?>',
                'tReturnInputCode': 'oetIVBCtrCode',
                'tReturnInputName': 'oetIVBCtrName',
                'aArgReturn': ['FTSplCode', 'FTCtrName',]
            });
            JCNxBrowseData('oIVBBrowseSPLOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });


    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสินค้า-รายการสินค้ารับเคลม ##############################
    $('#obtIVBPrintDocStep1').unbind().click(function() {
        let aInfor = [{
                "Lang": '<?= FCNaHGetLangEdit(); ?>'
            },
            {
                "ComCode": '<?= FCNtGetCompanyCode(); ?>'
            },
            {
                "BranchCode": '<?= FCNtGetAddressBranch(@$tIVBBchCode); ?>'
            },
            {
                "DocCode": '<?= @$tIVBDocNo; ?>'
            }, // เลขที่เอกสาร
            {
                "DocBchCode": '<?= @$tIVBBchCode; ?>'
            },
        ];
        window.open("<?= base_url(); ?>formreport/Frm_SQL_SMPdtIVBPdtDetail?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });

    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสินค้า-ส่งรายการสินค้าเครมไปยังผู้จำหน่าย ##############################
    $('#obtIVBPrintDocStep2').unbind().click(function() {
        let aInfor = [{
                "Lang": '<?= FCNaHGetLangEdit(); ?>'
            },
            {
                "ComCode": '<?= FCNtGetCompanyCode(); ?>'
            },
            {
                "BranchCode": '<?= FCNtGetAddressBranch(@$tIVBBchCode); ?>'
            },
            {
                "DocCode": '<?= @$tIVBDocNo; ?>'
            }, // เลขที่เอกสาร
            {
                "DocBchCode": '<?= @$tIVBBchCode; ?>'
            },
        ];
        window.open("<?= base_url(); ?>formreport/Frm_SQL_SMPdtIVBToVendor?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });

    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสืนค้า-รับสินค้ากลับจากผู้จำหน่าย ##############################
    $('#obtIVBPrintDocStep3').unbind().click(function() {
        let aInfor = [{
                "Lang": '<?= FCNaHGetLangEdit(); ?>'
            },
            {
                "ComCode": '<?= FCNtGetCompanyCode(); ?>'
            },
            {
                "BranchCode": '<?= FCNtGetAddressBranch(@$tIVBBchCode); ?>'
            },
            {
                "DocCode": '<?= @$tIVBDocNo; ?>'
            }, // เลขที่เอกสาร
            {
                "DocBchCode": '<?= @$tIVBBchCode; ?>'
            },
        ];
        window.open("<?= base_url(); ?>formreport/Frm_SQL_SMPdtIVBFrmVendor?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });

    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสินค้า-ใบส่งรถลูกค้า ##############################
    $('#obtIVBPrintDocStep4').unbind().click(function() {
        let aInfor = [{
                "Lang": '<?= FCNaHGetLangEdit(); ?>'
            },
            {
                "ComCode": '<?= FCNtGetCompanyCode(); ?>'
            },
            {
                "BranchCode": '<?= FCNtGetAddressBranch(@$tIVBBchCode); ?>'
            },
            {
                "DocCode": '<?= @$tIVBDocNo; ?>'
            }, // เลขที่เอกสาร
            {
                "DocBchCode": '<?= @$tIVBBchCode; ?>'
            },
        ];
        window.open("<?= base_url(); ?>formreport/Frm_SQL_SMPdtIVBCstCarDelivery?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });


    //////////////////////////////////////////////////////////////// เลือกผู้จำหน่าย /////////////////////////////////////////////////////////////

    $('#obtIVBBrowseSPL').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIVBBrowseSPLOption = undefined;
            oIVBBrowseSPLOption = oIVBSPLOption({
                'tParamsAgnCode': '<?= $this->session->userdata("tSesUsrAgnCode") ?>',
                'tReturnInputCode': 'ohdIVBSPLCode',
                'tReturnInputName': 'oetIVBSPLName',
                'tNextFuncName': 'JSxIVBSetConditionAfterSelectSPL',
                'aArgReturn': ['FNSplCrTerm', 'FCSplCrLimit', 'FTSplStaVATInOrEx', 'FTSplTspPaid', 'FTSplCode', 'FTSplName', 'FTSplStaLocal']
            });
            JCNxBrowseData('oIVBBrowseSPLOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var oIVBSPLOption = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;
        var tNextFuncName = poDataFnc.tNextFuncName;
        var aArgReturn = poDataFnc.aArgReturn;
        var tParamsAgnCode = poDataFnc.tParamsAgnCode;

        var tWhereAgency = '';
        if (tParamsAgnCode != "") {
            tWhereAgency = " AND ( TCNMSpl.FTAgnCode = '" + tParamsAgnCode + "' OR ISNULL(TCNMSpl.FTAgnCode,'') = '' ) ";
        } else {
            tWhereAgency = "";
        }

        var oOptionReturn = {
            Title: ['supplier/supplier/supplier', 'tSPLTitle'],
            Table: {
                Master: 'TCNMSpl',
                PK: 'FTSplCode'
            },
            Join: {
                Table: ['TCNMSpl_L', 'TCNMSplCredit'],
                On: [
                    'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = ' + nLangEdits,
                    'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode'
                ]
            },
            Where: {
                Condition: [" AND TCNMSpl.FTSplStaActive = '1'  " + tWhereAgency]
            },
            GrideView: {
                ColumnPathLang: 'supplier/supplier/supplier',
                ColumnKeyLang: ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid', 'TCNMSpl.FTSplStaLocal'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [2, 3, 4, 5, 6, 7, 8],
                Perpage: 10,
                OrderBy: ['TCNMSpl.FDCreateOn DESC']
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMSpl.FTSplCode"],
                Text: [tInputReturnName, "TCNMSpl_L.FTSplName"]
            },
            NextFunc: {
                FuncName: tNextFuncName,
                ArgReturn: aArgReturn
            }
        };
        return oOptionReturn;
    }

    var oIVBSPLContactOption = function(poDataFnc) {
        var tsplCode = $("#ohdIVBSPLCode").val();
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

    //หลังจากเลือกผู้จำหน่าย
    function JSxIVBSetConditionAfterSelectSPL(poDataNextFunc) {
        var aData;
        var tSearchDocType = $("#ocmIVBillType").val();
        var tSearchDateFrm = $("#oetSearchBillDateFrom").val();
        var tSearchDateTo  = $("#oetSearchBillDateTo").val();
        var tSearchDocNo   = $("#oetIVSearchDocno").val();
        var tSearchDocRef  = $("#oetIVSearchDocRef").val();
        var tBchCode       = $("#ohdIVBBchCode").val();
        $('#ohdConfirmIVBInsertPDT').val('');

        aData = JSON.parse(poDataNextFunc);
        $('#oetPanel_SplName').val(aData[5]);
        $('#oetIVCreditTerm').val(aData[0]);
        $.ajax({
            type: "POST",
            url: "docInvoiceBillFindingSplAddress",
            data: {
                tSPLCode            : aData[4],
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                aData2 = JSON.parse(oResult);
                var tAddfull ='';
                // console.log(aData2);
                if(nAddressVersion == '1'){
                    tAddfull = aData2[0]['FTAddV1No']+' '+aData2[0]['FTAddV1Soi']+' '+aData2[0]['FTAddV1Village']+' '+aData2[0]['FTAddV1Road']+' '+aData2[0]['FTSudName']+' '+aData2[0]['FTDstName']+' '+aData2[0]['FTPvnName'];
                }else if(nAddressVersion == '2'){
                    tAddfull = aData2[0]['FTAddV2Desc1']+' '+aData2[0]['FTAddV2Desc2'];
                }else{
                    tAddfull = "-";
                }
                $('#otaIVBAdress').val(tAddfull);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

        if (poDataNextFunc != "NULL") {
            $.ajax({
                type: "POST",
                url: "docInvoiceBillFinding",
                data: {
                    tSPLCode            : aData[4],
                    tDocno              : $("#ohdIVBDocNo").val(),
                    tSearchDocType      : tSearchDocType,
                    tSearchDateFrm      : tSearchDateFrm,
                    tSearchDateTo       : tSearchDateTo,
                    tSearchDocNo        : tSearchDocNo,
                    tSearchDocRef       : tSearchDocRef,
                    tBchCode            : tBchCode,
                    tTypeIn             : '1'
                },
                cache: false,
                Timeout: 0,
                success: function(oResult) {
                    var aResult = JSON.parse(oResult);
                    if(aResult.aContactSPL.rtCode == 1){
                        var aResultContact = aResult.aContactSPL;
                        $('#oetIVBCtrCode').val(aResultContact.rtResult.FNCtrSeq);
                        $('#oetIVBCtrName').val(aResultContact.rtResult.FTCtrName);
                    }else{
                        $('#oetIVBCtrCode').val('');
                        $('#oetIVBCtrName').val('');
                    }

                    JSxIVBStep1Point1LoadDatatable();
                    JSxIVBStep1Point2LoadDatatable()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //ล้างข้อมูลการค้นหา
    function JSxIVBResetFilter() {

        $("#oetSearchBillDateFrom").val('');
        $("#oetSearchBillDateTo").val('');
        $("#oetIVSearchDocno").val('');
        $("#oetIVSearchDocRef").val('');

    }

    //แสดงข้อมูลหลังจากเข้าหน้า Edit
    function JSxIVBShowBillOnEditEvent() {
        var tSPLCode = $("#ohdIVBSPLCode").val();
        if(tSPLCode == ''){
            FSvCMNSetMsgErrorDialog('กรุณาเลือกผู้จำหน่าย');
            return;
        }

        var tSearchDocType = $("#ocmIVBillType").val();
        var tSearchDateFrm = $("#oetSearchBillDateFrom").val();
        var tSearchDateTo  = $("#oetSearchBillDateTo").val();
        var tSearchDocNo   = $("#oetIVSearchDocno").val();
        var tSearchDocRef  = $("#oetIVSearchDocRef").val();
        var tBchCode       = $("#ohdIVBBchCode").val();

        if((tStatusDoc == 1 && tStatusApv == 1)){
            var tType = '2';
        }else{
            var tType = '1';
        }

        $.ajax({
            type: "POST",
            url: "docInvoiceBillFinding",
            data: {
                tSPLCode            : $("#ohdIVBSPLCode").val(),
                tDocno              : $("#ohdIVBDocNo").val(),
                tSearchDocType      : tSearchDocType,
                tSearchDateFrm      : tSearchDateFrm,
                tSearchDateTo       : tSearchDateTo,
                tSearchDocNo        : tSearchDocNo,
                tSearchDocRef       : tSearchDocRef,
                tBchCode            : tBchCode,
                tTypeIn             : tType
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                JSxIVBStep1Point1LoadDatatable();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    }

    function JSxIVBShowBillOnEditEventSearch() {
        var tSPLCode = $("#ohdIVBSPLCode").val();
        if(tSPLCode == ''){
            FSvCMNSetMsgErrorDialog('กรุณาเลือกผู้จำหน่าย');
            return;
        }

        var tSearchDocType = $("#ocmIVBillType").val();
        var tSearchDateFrm = $("#oetSearchBillDateFrom").val();
        var tSearchDateTo  = $("#oetSearchBillDateTo").val();
        var tSearchDocNo   = $("#oetIVSearchDocno").val();
        var tSearchDocRef  = $("#oetIVSearchDocRef").val();
        var tBchCode       = $("#ohdIVBBchCode").val();
        $.ajax({
            type: "POST",
            url: "docInvoiceBillFinding",
            data: {
                tSPLCode            : $("#ohdIVBSPLCode").val(),
                tDocno              : $("#ohdIVBDocNo").val(),
                tSearchDocType      : tSearchDocType,
                tSearchDateFrm      : tSearchDateFrm,
                tSearchDateTo       : tSearchDateTo,
                tSearchDocNo        : tSearchDocNo,
                tSearchDocRef       : tSearchDocRef,
                tBchCode            : tBchCode,
                tTypeIn             : '1'
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                JSxIVBStep1Point1LoadDatatableSearch();
                localStorage.removeItem("IVB_LocalItemDataInsertDtTemp");
                $('.ocbListItem').prop('checked', false);
                $('#ohdConfirmIVBInsertPDT').val('');
                $('.xCNInvoiceBillStep1Point2').attr('data-toggle','false');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
        
    }

    
    //เลือกคอลัมน์ในฐานข้อมูล เก็บไว้ใน localstorage [หลายรายการ]
    function FSxQTSelectMulInsert(ptElm) {
       
       let tDocNo = $('#ohdIVBDocNo').val();
       let tPdtCode = $(ptElm).parents('.xWPdtItemPoint1').data('pdtcode');
       $(ptElm).prop('checked', true);
       let oLocalItemDTTemp = localStorage.getItem("IVB_LocalItemDataInsertDtTemp");
       console.log(oLocalItemDTTemp);
       let oDataObj = [];
       if (oLocalItemDTTemp) {
           oDataObj = JSON.parse(oLocalItemDTTemp);
       }
       let aArrayConvert = [JSON.parse(localStorage.getItem("IVB_LocalItemDataInsertDtTemp"))];
       if (aArrayConvert == '' || aArrayConvert == null) {
           oDataObj.push({
               'tDocNo': tDocNo,
               'tPdtCode': tPdtCode,
           });
           localStorage.setItem("IVB_LocalItemDataInsertDtTemp", JSON.stringify(oDataObj));
           JSxIVBextInModalInsertPdtDtTemp();
       } else {
           var aReturnRepeat = JStIVBFindObjectByKey(aArrayConvert[0], 'tPdtCode', tPdtCode);
           if (aReturnRepeat == 'None') {
               //ยังไม่ถูกเลือก
               oDataObj.push({
                   'tDocNo': tDocNo,
                   'tPdtCode': tPdtCode,
               });
               localStorage.setItem("IVB_LocalItemDataInsertDtTemp", JSON.stringify(oDataObj));
               JSxIVBextInModalInsertPdtDtTemp();
           } else if (aReturnRepeat == 'Dupilcate') {
               localStorage.removeItem("IVB_LocalItemDataInsertDtTemp");
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
               localStorage.setItem("IVB_LocalItemDataInsertDtTemp", JSON.stringify(aNewarraydata));
               JSxIVBextInModalInsertPdtDtTemp();
           }
       }
   }

   
    //ลบคอลัมน์ในฐานข้อมูล เก็บค่าใน Modal [หลายรายการ]
    function JSxIVBextInModalInsertPdtDtTemp() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("IVB_LocalItemDataInsertDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
            $('#ohdConfirmIVBInsertPDT').val('');
            $('.xCNInvoiceBillStep1Point2').attr('data-toggle','false');
        } else {
            $('.xCNInvoiceBillStep1Point2').attr('data-toggle','tab');
            var tIVBextDocNo = "";
            var tIVBextPdtCode = "";
            $.each(aArrayConvert[0], function(nKey, aValue) {
                tIVBextDocNo += aValue.tDocNo;
                tIVBextDocNo += " , ";

                tIVBextPdtCode += aValue.tPdtCode;
                tIVBextPdtCode += " , ";

            });
            $('#ohdConfirmIVBInsertPDT').val(tIVBextPdtCode);
            // alert($("#ohdConfirmIVBInsertPDT").val());
        }
    }

    //พิมพ์เอกสาร
    function JSxIVPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tIVBBchCode); ?>'},
            {"DocCode"      : '<?=@$tIVBDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tIVBBchCode;?>'}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMPBillStateMent?infor=" + JCNtEnCodeUrlParameter(aInfor) , '_blank');
    }
</script>