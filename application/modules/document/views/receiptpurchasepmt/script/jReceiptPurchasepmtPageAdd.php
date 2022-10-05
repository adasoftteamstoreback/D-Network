<script type="text/javascript">
    var tStatusDoc      = $('#ohdRPPStaDoc').val();
    var tChkrount       = $('#ohdRPPRoute').val();
    var nAddressVersion = '<?=FCNaHAddressFormat('TCNMSpl')?>';
    var tStatusDoc      = $('#ohdRPPStaDoc').val();
    var tStatusApv      = $('#ohdRPPStaApv').val();
    
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

    //  ============================================================================  Document Date Button Control ============================================================================
    $('#obtRPPDocDate').unbind().click(function(){
        $('#oetRPPDocDate').datepicker('show');
    });
    $('#obtRPPDocTime').unbind().click(function(){
        $('#oetRPPDocTime').datetimepicker('show');
    });
    $('#obtRPPSearchBillDateFrom').unbind().click(function(){
        $('#oetRPPSearchBillDateFrom').datepicker('show');
    });
    $('#obtRPPSearchBillDateTo').unbind().click(function(){
        $('#oetRPPSearchBillDateTo').datepicker('show');
    });
    //  =======================================================================================================================================================================================

    // ================================================================================ Event Click Step Document =============================================================================
    // Function : กดถัดไป (ที่ปุ่ม) [TAB LEVEL 1]
    $('.xCNRPPNextStep').on('click', function() {
        var tStepNow = $('#CheckStep .xWPointStep.active').data('step');
        // เช็ค Case Step ใบเสร็จจ่ายชำระ 
        switch(tStepNow){
            case 1:
                var aPdtLent = $("#ohdConfirmRPPInsertPDT").val();
                if(aPdtLent != '') {
                    $('.xCNRPPStep1Point2').trigger('click');
                }else{
                    FSvCMNSetMsgErrorDialog("กรุณาเลือกเอกสารที่จะนำมาชำระ");
                    return;
                }
            break;
            case 2:
                var aPdtLent = $("#ohdConfirmRPPInsertPDT").val();
                if(aPdtLent != '') {
                    $('.xCNRPPStep1Point3').trigger('click');
                }else{
                    FSvCMNSetMsgErrorDialog("กรุณาเลือกเอกสารที่จะนำมาชำระ");
                    return;
                }
            break;
        }
    });

    // Function : กดย้อนกลับ [TAB LEVEL 2]
    $('.xCNRPPBackStep').on('click', function() {
        var tStepNow    = $('#CheckStep .xWPointStep.active').data('step');
        if (tStepNow == 2) {
            $('.xCNRPPStep1Point1').trigger('click');
            var tStatusDoc = $('#ohdRPPStaDoc').val();
            var tStatusApv = $('#ohdRPPStaApv').val();
            if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                $('.xCNRPPBackStep').attr('disabled', true);
                $('.xCNRPPNextStep').attr('disabled', false);
            }else{
                $('.xCNRPPBackStep').attr('disabled', true);
                $('.xCNRPPNextStep').attr('disabled', false);
                $('#odvStep1Search').removeClass('xCNHide');
            }
            $('.xCNRPPBackStep').attr('disabled', true);
            $('.xCNRPPNextStep').attr('disabled', false);
        }else if(tStepNow == 3){
            $('.xCNRPPStep1Point2').trigger('click');
            $('.xCNRPPBackStep').attr('disabled', false);
            $('.xCNRPPNextStep').attr('disabled', false);
            $('#odvStep1Search').addClass('xCNHide');
            $('#odvRPPStep1Point2').removeClass('xCNHide');
            
        }
    });

    // Function : Click Point Step
    $('.xWPointStep').on('click', function() {
        var tStepNow    = $('#CheckStep .xWPointStep.active').data('step');
        var tStepNext   = $(this).data('step');
        if (tStepNow >= 1) {
            var aPdtLent    = $("#ohdConfirmRPPInsertPDT").val();
            if(tStepNext == 2){
                // Step 2 ตรวจสอบเอกสาร
                if (aPdtLent != '') {
                    $('.xCNRPPBackStep').attr('disabled', false);
                    $('.xCNRPPNextStep').attr('disabled', false);
                    $('#odvStep1Search').addClass('xCNHide');
                    $('#odvRPPStep1Point2').removeClass('xCNHide');
                } else {
                    FSvCMNSetMsgErrorDialog("กรุณาเลือกเอกสารที่จะนำมาชำระ");
                    return;
                }
            } else if(tStepNext == 3) {
                // Step 3 ชำระเงิน
                var tRppInpay   = $('#oetRPPPriceInvPay').val();
                if (tRppInpay != '') {
                    $('.xCNRPPBackStep').attr('disabled', false);
                    $('.xCNRPPNextStep').attr('disabled', true);
                    $('#odvStep1Search').addClass('xCNHide');
                    $('#odvRPPStep1Point2').addClass('xCNHide');
                }else {
                    FSvCMNSetMsgErrorDialog("โปรดกรอกจำนวนยอดเงินที่ต้องการชำระ");
                    $('#oetRPPPriceInvPay').focus();
                    return;
                }
            }else{
                // Step 1 เลือกเอกสาร
                var tStatusDoc  = $('#ohdRPPStaDoc').val();
                var tStatusApv  = $('#ohdRPPStaApv').val();
                if(tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)){
                    $('.xCNRPPBackStep').attr('disabled', true);
                    $('.xCNRPPNextStep').attr('disabled', false);
                }else{
                    $('.xCNRPPBackStep').attr('disabled', true);
                    $('.xCNRPPNextStep').attr('disabled', false);
                    $('#odvStep1Search').removeClass('xCNHide');
                }
            }
        }else{
            $('.xCNRPPBackStep').attr('disabled', true);
            $('.xCNRPPNextStep').attr('disabled', false);
            $('#odvStep1Search').removeClass('xCNHide');
        }
    });

    //  =======================================================================================================================================================================================

    
    // Function : Browse ข้อมูลตัวแทนขาย / แฟรนไชด์
    // Creator  : 28/03/2022 Wasin
    var oAgnOption  = function(poDataFnc){
        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tNextFuncName       = poDataFnc.tNextFuncName;
        let tUsrLevSession      = '<?=$this->session->userdata("tSesUsrLevel"); ?>';
        let tWhereAgn           = '';
        let oOptionReturn       = {
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
        }
        return oOptionReturn;
    }
    $('#obtRPPBrowseAgency').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRPPAgencyOption = undefined;
            oRPPAgencyOption        = oAgnOption({
                'tReturnInputCode'  : 'oetRPPAgnCode',
                'tReturnInputName'  : 'oetRPPAgnName',
                'tNextFuncName'     : 'JSxRPPWhenSeletedAgency'
            });
            JCNxBrowseData('oRPPAgencyOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    function JSxRPPWhenSeletedAgency(poDataNextFunc){
        if (poDataNextFunc  != "NULL") {
            $('#oetRPPBchCode,#oetRPPBchName').val('');
        }
    }

    // Function : Browse สาขา
    // Creator  : 28/03/2022 Wasin
    var oBchOption  = function(poDataFnc){
        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tNextFuncName       = poDataFnc.tNextFuncName;
        let nLangEdits          = <?php echo $this->session->userdata("tLangEdit")?>;
        let tAgnCode            = $('#oetRPPAgnCode').val();
        let tSQLWhere           = "";
        if(tAgnCode != ""){
            tSQLWhere   += " AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
        }
        if(tUsrLevel != "HQ"){
            tSQLWhere   += " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }
        let oOptionReturn   = {
            Title   : ['company/branch/branch', 'tBCHTitle'],
            Table   : {Master:'TCNMBranch', PK:'FTBchCode'},
            Join    : {
                Table: ['TCNMBranch_L'],
                On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
            },
            Where   : {
                Condition: [tSQLWhere]
            },
            GrideView   : {
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['tBCHCode', 'tBCHName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['', ''],
                DisabledColumns     : [],
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack:{
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text        : [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
        };
        return oOptionReturn;
    }
    $('#obtRPPBrowseBranch').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRPPBranchOption = undefined;
            oRPPBranchOption        = oBchOption({
                'tReturnInputCode'  : 'oetRPPBchCode',
                'tReturnInputName'  : 'oetRPPBchName',
                'tNextFuncName'     : 'JSxRPPWhenSeletedBranch'
            });
            JCNxBrowseData('oRPPBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    function JSxRPPWhenSeletedBranch(poDataNextFunc){
    }


    // =========================================================================== เลือกผู้จำหน่าย ===========================================================================
    
    var oRPPSPLOption   = function(poDataFnc) {
        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tNextFuncName       = poDataFnc.tNextFuncName;
        let aArgReturn          = poDataFnc.aArgReturn;
        let tParamsAgnCode      = poDataFnc.tParamsAgnCode;
        let tWhereAgency        = '';
        if (tParamsAgnCode != "") {
            tWhereAgency    = " AND ( TCNMSpl.FTAgnCode = '" + tParamsAgnCode + "' OR ISNULL(TCNMSpl.FTAgnCode,'') = '' ) ";
        } else {
            tWhereAgency    = "";
        }
        let oOptionReturn   = {
            Title   : ['supplier/supplier/supplier', 'tSPLTitle'],
            Table   : {
                Master  : 'TCNMSpl',
                PK      : 'FTSplCode'
            },
            Join    : {
                Table   : ['TCNMSpl_L', 'TCNMSplCredit'],
                On      : [
                    'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = ' + nLangEdits,
                    'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode'
                ]
            },
            Where   : {
                Condition   : [" AND TCNMSpl.FTSplStaActive = '1'  " + tWhereAgency]
            },
            GrideView   : {
                ColumnPathLang      : 'supplier/supplier/supplier',
                ColumnKeyLang       : ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid', 'TCNMSpl.FTSplStaLocal'],
                DataColumnsFormat   : ['', ''],
                DisabledColumns     : [2, 3, 4, 5, 6, 7, 8],
                Perpage             : 10,
                OrderBy             : ['TCNMSpl.FDCreateOn DESC']
            },
            CallBack    : {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMSpl.FTSplCode"],
                Text        : [tInputReturnName, "TCNMSpl_L.FTSplName"]
            },
            NextFunc    : {
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            },
            // DebugSQL: true,
        }
        return oOptionReturn;
    };
    $('#obtRPPBrowseSPL').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRPPBrowseSPLOption  = undefined;
            let tUsrAgnCode     = $('#oetRPPAgnCode').val();
            oRPPBrowseSPLOption = oRPPSPLOption({
                'tParamsAgnCode'    : tUsrAgnCode,
                'tReturnInputCode'  : 'ohdRPPSPLCode',
                'tReturnInputName'  : 'oetRPPSPLName',
                'tNextFuncName'     : 'JSxRPPSetConditionAfterSelectSPL',
                'aArgReturn'        : ['FNSplCrTerm', 'FCSplCrLimit', 'FTSplStaVATInOrEx', 'FTSplTspPaid', 'FTSplCode', 'FTSplName', 'FTSplStaLocal']
            });
            JCNxBrowseData('oRPPBrowseSPLOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Function : หลังจากเลือกผู้จำหน่าย
    // Creator  : 28/03/2022 Wasin
    function JSxRPPSetConditionAfterSelectSPL(poDataNextFunc ){
        let aData;
        var tSearchDocType      = $("#ocmRPPBillType").val();
        var tSearchDateFrm      = $("#oetRPPSearchBillDateFrom").val();
        var tSearchDateTo       = $("#oetRPPSearchBillDateTo").val();
        var tSearchDocno        = $("#oetRPPSearchDocno").val();
        var tSearchDocRef       = $("#oetRPPSearchDocRef").val();
        var tAgnCode            = $("#oetRPPAgnCode").val();
        var tBchCode            = $("#oetRPPBchCode").val();

        $('#ohdConfirmRPPInsertPDT').val('');
        aData   = JSON.parse(poDataNextFunc);
        $('#oetRPPSplName').val(aData[5]);

        if(poDataNextFunc != "NULL") {
            // ค้นหาที่อยู่ Supplier
            $.ajax({
                type    : "POST",
                url     : "docRPPFindingSplAddress",
                data    : {tSPLCode : aData[4]},
                cache   : false,
                Timeout : 0,
                success : function(oResult) {
                    var aData2      = JSON.parse(oResult);
                    var tAddfull    ='';
                    if(nAddressVersion == '1'){
                        tAddfull = aData2[0]['FTAddV1No']+' '+aData2[0]['FTAddV1Soi']+' '+aData2[0]['FTAddV1Village']+' '+aData2[0]['FTAddV1Road']+' '+aData2[0]['FTSudName']+' '+aData2[0]['FTDstName']+' '+aData2[0]['FTPvnName'];
                    }else if(nAddressVersion == '2'){
                        tAddfull = aData2[0]['FTAddV2Desc1']+' '+aData2[0]['FTAddV2Desc2'];
                    }else{
                        tAddfull = "-";
                    }
                    $('#otaRPPAdress').val(tAddfull);
                    $('#oetRPPSplAddrSeq').val(aData2[0]['FNAddSeqNo']);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

            // ค้นหาเอกสารที่จะชำระที่เกี่ยวข้องกับ Supplier
            $.ajax({
                type    : "POST",
                url     : "docRPPFindingPoint1",
                data    : {
                    tSPLCode        : aData[4],
                    tDocno          : $("#ohdRPPDocNo").val(),
                    tSearchDocType  : tSearchDocType,
                    tSearchDateFrm  : tSearchDateFrm,
                    tSearchDateTo   : tSearchDateTo,
                    tSearchDocno    : tSearchDocno,
                    tSearchDocRef   : tSearchDocRef,
                    tAgnCode        : tAgnCode,
                    tBchCode        : tBchCode,
                    tTypeIn         : '1'
                },
                cache: false,
                Timeout: 0,
                success: function(oResult) {
                    let aResult = JSON.parse(oResult);
                    if(aResult.aContactSPL.rtCode == 1){
                        var aDataContact    = aResult.aContactSPL.rtResult;
                        $('#oetRPPCtrCode').val(aDataContact.FNCtrSeq);
                        $('#oetRPPCtrName').val(aDataContact.FTCtrName);
                        $('#oetRPPCtrPhone').val(aDataContact.FTCtrTel);
                    }else{
                        $('#oetRPPCtrCode').val('');
                        $('#oetRPPCtrName').val('');
                        $('#oetRPPCtrPhone').val('');
                    }
                    JSxRPPStep1Point1LoadDatatable();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        }
        
    }   

    // Function : Browse ผู้ติดต่อ
    // Creator  : 28/03/2022 Wasin
    var oRPPSPLContactOption = function(poDataFnc) {
        let tsplCode            = $("#ohdRPPSPLCode").val();
        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tNextFuncName       = poDataFnc.tNextFuncName;
        let aArgReturn          = poDataFnc.aArgReturn;
        let tParamsAgnCode      = poDataFnc.tParamsAgnCode;
        let oOptionReturn       = {
            Title   : ['supplier/supplier/supplier', 'tTitleSplContr'],
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
    };
    $('#oimRPPBrowseCtr').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        $('#oetRPPCtrCode').val('');
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oRPPBrowseSPLOption = undefined;
            oRPPBrowseSPLOption = oRPPSPLContactOption({
                'tParamsAgnCode'    : '<?= $this->session->userdata("tSesUsrAgnCode") ?>',
                'tReturnInputCode'  : 'oetRPPCtrCode',
                'tReturnInputName'  : 'oetRPPCtrName',
                'tNextFuncName'     : 'JSxRPPSetConditionAfterSelectCtr',
                'aArgReturn'        : ['FTSplCode', 'FTCtrName','FTCtrTel']
            });
            JCNxBrowseData('oRPPBrowseSPLOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    function JSxRPPSetConditionAfterSelectCtr(poDataNextFunc){
        if(poDataNextFunc != "NULL") {
            let aData   = JSON.parse(poDataNextFunc);
            $('#oetRPPCtrPhone').val(aData[2]);
        }
    }

    // Function : ล้างข้อมูลการค้นหา
    // Creator  : 28/03/2022 Wasin
    function JSxRPPResetFilter(){
        $("#oetRPPSearchBillDateFrom").val('');
        $("#oetRPPSearchBillDateTo").val('');
        $("#oetRPPSearchDocno").val('');
        $("#oetRPPSearchDocRef").val('');
    }

    // Function : ค้นหาเอกสารที่จะมาชำระจากฟอร์มกรองข้อมูลเอกสาร
    // Creator  : 28/03/2022 Wasin
    function JSxRPPShowBillOnEditEventSearch (){
        let tSPLCode    = $("#ohdRPPSPLCode").val();
        if(tSPLCode == ''){
            FSvCMNSetMsgErrorDialog('กรุณาเลือกผู้จำหน่าย');
            return;
        }
        var tSearchDocType  = $("#ocmRPPBillType").val();
        var tSearchDateFrm  = $("#oetRPPSearchBillDateFrom").val();
        var tSearchDateTo   = $("#oetRPPSearchBillDateTo").val();
        var tSearchDocno    = $("#oetRPPSearchDocno").val();
        var tSearchDocRef   = $("#oetRPPSearchDocRef").val();
        var tAgnCode        = $("#oetRPPAgnCode").val();
        var tBchCode        = $("#oetRPPBchCode").val();
        $.ajax({
            type    : "POST",
            url     : "docRPPFindingPoint1",
            data    : {
                tSPLCode        : $("#ohdRPPSPLCode").val(),
                tDocno          : $("#ohdRPPDocNo").val(),
                tSearchDocType  : tSearchDocType,
                tSearchDateFrm  : tSearchDateFrm,
                tSearchDateTo   : tSearchDateTo,
                tSearchDocno    : tSearchDocno,
                tSearchDocRef   : tSearchDocRef,
                tAgnCode        : tAgnCode,
                tBchCode        : tBchCode,
                tTypeIn         : '1'
            },
            cache   : false,
            Timeout : 0,
            success: function(oResult) {
                JSxRPPStep1Point1LoadDatatableSearch();
                localStorage.removeItem("RPP_LocalItemDataInsertDtTemp");
                $('.ocbListItem').prop('checked', false);
                $('#ohdConfirmRPPInsertPDT').val('');
                $('.xCNRPPStep1Point2').attr('data-toggle','false');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // ===================================================================================================================================================================

    // Function : เลือกคอลัมน์ในฐานข้อมูล เก็บไว้ใน localstorage [หลายรายการ]
    // Creator  : 28/03/2022 Wasin
    function FSxRPPSelectMulInsert(ptElm) {
        let oDataObj    = [];
        let tDocNo      = $('#ohdRPPDocNo').val();
        let tPdtCode    = $(ptElm).parents('.xWPdtItemPoint1').data('pdtcode');
        $(ptElm).prop('checked', true);
        let oLocalItemDTTemp    = localStorage.getItem("RPP_LocalItemDataInsertDtTemp");
        if (oLocalItemDTTemp) {
           oDataObj = JSON.parse(oLocalItemDTTemp);
        }
        let aArrayConvert       = [JSON.parse(localStorage.getItem("RPP_LocalItemDataInsertDtTemp"))];
        if (aArrayConvert == '' || aArrayConvert == null) {
            oDataObj.push({
               'tDocNo'     : tDocNo,
               'tPdtCode'   : tPdtCode,
           });
           localStorage.setItem("RPP_LocalItemDataInsertDtTemp", JSON.stringify(oDataObj));
           JSxRPPTextInModalInsertPdtDtTemp();
        } else {
            let aReturnRepeat   = JStRPPFindObjectByKey(aArrayConvert[0], 'tPdtCode', tPdtCode);
            if (aReturnRepeat == 'None') {
               //ยังไม่ถูกเลือก
               oDataObj.push({
                   'tDocNo': tDocNo,
                   'tPdtCode': tPdtCode,
               });
               localStorage.setItem("RPP_LocalItemDataInsertDtTemp", JSON.stringify(oDataObj));
               JSxRPPTextInModalInsertPdtDtTemp();
           } else if (aReturnRepeat == 'Dupilcate') {
               // มีการเลือกข้อมูลไว้ก่อนแล้ว
               localStorage.removeItem("RPP_LocalItemDataInsertDtTemp");
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
               localStorage.setItem("RPP_LocalItemDataInsertDtTemp", JSON.stringify(aNewarraydata));
               JSxRPPTextInModalInsertPdtDtTemp();
           }
        }
    }

    // Function : ลบคอลัมน์ในฐานข้อมูล เก็บค่าใน Modal [หลายรายการ]
    // Creator  : 28/03/2022 Wasin
    function JSxRPPTextInModalInsertPdtDtTemp() {
        let aArrayConvert   = [JSON.parse(localStorage.getItem("RPP_LocalItemDataInsertDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
            $('#ohdConfirmRPPInsertPDT').val('');
            $('.xCNRPPStep1Point2').attr('data-toggle','false');
        }else{
            $('.xCNRPPStep1Point2').attr('data-toggle','tab');
            let tRPPTextDocNo   = "";
            let tRPPTextPdtCode = "";
            $.each(aArrayConvert[0], function(nKey, aValue) {
                tRPPTextDocNo   += aValue.tDocNo;
                tRPPTextDocNo   += " , ";
                tRPPTextPdtCode += aValue.tPdtCode;
                tRPPTextPdtCode += " , ";
            });
            $('#ohdConfirmRPPInsertPDT').val(tRPPTextPdtCode);
        }
    }

    // ลบคอลัมน์ในฐานข้อมูล เช็คค่าใน array [หลายรายการ]
    function JStRPPFindObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    // Function : พิมพ์เอกสาร
    // Creator  : 28/03/2022 Wasin
    function JSxRPPPrintDoc(){
    }
    

    // ================================================================== Event Appove And Save Document ==============================================================
    $('#ocbRPPStaAutoGenCode').on('change', function (e) {
        if($('#ocbRPPStaAutoGenCode').is(':checked')){
            $("#oetRPPDocNo").val('');
            $("#oetRPPDocNo").attr("readonly", true);
            $('#oetRPPDocNo').closest(".form-group").css("cursor","not-allowed");
            $('#oetRPPDocNo').css("pointer-events","none");
            $("#oetRPPDocNo").attr("onfocus", "this.blur()");
            $('#ofmRPPFormAdd').removeClass('has-error');
            $('#ofmRPPFormAdd .form-group').closest('.form-group').removeClass("has-error");
            $('#ofmRPPFormAdd em').remove();
        }else{
            $('#oetRPPDocNo').closest(".form-group").css("cursor","");
            $('#oetRPPDocNo').css("pointer-events","");
            $('#oetRPPDocNo').attr('readonly',false);
            $("#oetRPPDocNo").removeAttr("onfocus");
        }
    });

    
    $('#obtRPPSaveAndApvDoc').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#obtRPPSubmitDocument').click();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Function : Add Edit Document
    // Creator  : 19/04/2022 Wasin
    function JSxRPPAddEditDocument(){
        if($("#ohdRPPCheckClearValidate").val() != 0){
            $('#ofmRPPFormAdd').validate().destroy();
        }
        $('#ofmRPPFormAdd').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetRPPDocNo     : {
                    "required"  : {
                        depends : function (oElement) {
                            if($("#ohdRPPRoute").val()  ==  "docRPPEventAdd"){
                                if($('#ocbRPPStaAutoGenCode').is(':checked')){
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
                oetRPPDocDate   : {"required" : true},
                oetRPPDocTime   : {"required" : true},
                oetRPPBchName   : {"required" : true},
            },
            messages        : {
                oetRPPDocNo     : {"required" : $('#oetRPPDocNo').attr('data-validate-required')},
                oetRPPDocDate   : {"required" : $('#oetRPPDocDate').attr('data-validate-required')},
                oetRPPDocTime   : {"required" : $('#oetRPPDocTime').attr('data-validate-required')},
                oetRPPBchName   : "กรุณาเลือกสาขา"
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
            highlight       : function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight     : function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler   : function (form){
                if(!$('#ocbRPPStaAutoGenCode').is(':checked')){
                    if($("#ohdRPPRoute").val() ==  "docRPPEventAdd"){
                        JSxRPPValidateDocCodeDublicate();
                    }else{
                        JSxRPPSubmitEventByButton();
                    }
                }else{
                    JSxRPPSubmitEventByButton();
                }
            }
        });
    }

    // Function : ถ้าปล่อยให้คีย์เองต้อง ตรวจสอบรหัสเอกสารว่าซ้ำไหม
    // Creator  : 28/03/2022 Wasin
    function JSxRPPValidateDocCodeDublicate(){
        $.ajax({
            type    : "POST",
            url     : "CheckInputGenCode",
            data    : {
                'tTableName'    : 'TACTPpHD',
                'tFieldName'    : 'FTXshDocNo',
                'tCode'         : $('#oetRPPDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);

                $("#ohdRPPCheckDuplicateCode").val(aResultData["rtCode"]);
                if($("#ohdRPPCheckClearValidate").val() != 1) {
                    $('#ofmRPPFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdRPPRoute").val() == "docRPPEventAdd"){
                        if($('#ocbRPPStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdRPPCheckDuplicateCode").val() == 1) {
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
                $('#ofmRPPFormAdd').validate({
                    focusInvalid    : false,
                    onclick         : false,
                    onfocusout      : false,
                    onkeyup         : false,
                    rules           : {
                        oetRPPDocNo : {"dublicateCode": {}}
                    },
                    messages        : {
                        oetRPPDocNo : {"dublicateCode"  : $('#oetRPPDocNo').attr('data-validate-duplicate')}
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
                        JSxRPPSubmitEventByButton();
                    }
                });

                if($("#ohdRPPCheckClearValidate").val() != 1) {
                    $("#ofmRPPFormAdd").submit();
                    $("#ohdRPPCheckClearValidate").val(1);
                }

                if($("#ohdRPPCheckDuplicateCode").val() == 1) {
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    var tLogFunction    = 'ERROR';
                    var tDisplayEvent   = 'เพิ่ม/แก้ไข ใบเสร็จจ่ายชำระ';
                    var tErrorStatus    = 900
                    var tHtmlError      = 'Data Duplicate'
                    var tLogDocNo       = $('#oetRPPDocNo').val();
                    JCNxPackDataToMQLog(tHtmlError,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
                let tDocNo = $('#oetRPPDocNo').val();
                //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                if (jqXHR.status != 404){
                    let tLogFunction    = 'ERROR';
                    let tDisplayEvent   = 'Checking Data Duplicate';
                    let tErrorStatus    = 900
                    let tHtmlError      = $(jqXHR.responseText);
                    let tMsgErrorBody   = tHtmlError.find('p:nth-child(3)').text();
                    let tLogDocNo       = tDocNo;
                    JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                }
            }
        });
    }

    // Function : บันทึกเอกสาร
    // Creator  : 28/03/2022 Wasin
    function JSxRPPSubmitEventByButton(ptType = ''){
        $('#odvRPPPopupApv').modal('show');
    }

    // Function : Appove And Save Document
    // Creator  : 28/03/2022 Wasin
    function JSxRPPDocumentSaveAndAppoveDoc(pbIsConfirm){
        if(pbIsConfirm){
            var tRPPDocNo   = "";
            if($("#ohdRPPRoute").val() !=  "docRPPEventAdd"){
                tRPPDocNo   = $('#oetRPPDocNo').val();
            }
            $.ajax({
                type    : "POST",
                url     : "docRPPChkHavePdtForDocDTTemp",
                data    : {'ptRPPDocNo' : tRPPDocNo},
                async   : false,
                cache   : false,
                timeout : 0,
                success : function (oResult){

                    // let aDataReturnChkTmp   = JSON.parse(oResult);
                    // if (aDataReturnChkTmp['nStaReturn'] == '1'){
                    // }else if(aDataReturnChkTmp['nStaReturn'] == '800'){
                    //     var tMsgDataTempFound   = aDataReturnChkTmp['tStaMessg'];
                    //     FSvCMNSetMsgWarningDialog('<p class="text-left">'+tMsgDataTempFound+'</p>');
                    // }else{
                    //     var tMsgErrorFunction   = aDataReturnChkTmp['tStaMessg'];
                    //     FSvCMNSetMsgErrorDialog('<p class="text-left">'+tMsgErrorFunction+'</p>');
                    // }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    //ส่งขอมูลไปรวบรวมที่ Center ก่อนส่ง MQ เพื่อเก็บ LOG (TYPE:ERROR)
                    var tDocNo = $('#oetIVDocNo').val();
                    if (jqXHR.status != 404){
                        var tLogFunction    = 'ERROR';
                        var tDisplayEvent   = 'บันทึก/แก้ไข ใบเสร็จจ่ายชำระ';
                        var tErrorStatus    = 500;
                        var tHtmlError      = $(jqXHR.responseText);
                        var tMsgErrorBody   = tHtmlError.find('p:nth-child(3)').text();
                        var tLogDocNo       = tDocNo;
                        JCNxPackDataToMQLog(tMsgErrorBody,tErrorStatus,tDisplayEvent,tLogFunction,tLogDocNo);
                    }
                }
            });
        }else{
            $("#odvRPPPopupApv").modal('show');
        }
    }


    // ================================================================================================================================================================

</script>