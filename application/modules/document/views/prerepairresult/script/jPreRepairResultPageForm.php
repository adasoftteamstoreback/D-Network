<script>
    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit"); ?>';
    var dCurrentDate    = new Date();
    var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
    var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes() + " " + tAmOrPm;
    var nChkRedlabel    = $("#ohdPreRedLaber").val();
    var nChkFuelStatus  = $("#ohdPreFuelStatus").val();
    var nChkTimeBook    = $("#ohdPreTimeBook").val();
    var nChkCstCode     = $("#oetPreFrmCstCode").val();
    if(nChkCstCode != ''){
        $('#oimPreBrowseCarRegNo').attr('disabled',false);
    }

    //ป้ายเเดง
    if(nChkRedlabel == '1'){
        $("#oetPreCarRedLabel").prop( "checked", true)
    }else if(nChkRedlabel == '2'){
        $("#oetPreCarRedLabel").prop( "checked", false)
    }

    //แกนน้ำมัน
    $('#ohdPreFrmCarFuel').val(nChkFuelStatus);
    $('.xCNClickMile').each( function () {
        if($(this).data('hiddenval') == nChkFuelStatus){
            $(this).addClass( "xCNActiveMile" );
        }else{
            $(this).removeClass( "xCNActiveMile" );
        }
    });

    if(nChkTimeBook != ''){
        $("#oetPreBookUse").prop( "checked", true)
    }else{
        $("#oetPreBookUse").prop( "checked", false)
    }

    var tUsrLevel = '<?= $this->session->userdata('tSesUsrLevel') ?>';
    if (tUsrLevel != "HQ") {
        $('#oimPreSvBrowseAgn').attr("disabled", true);
    }

    // วันที่ประเมิน
    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard: true,
        autoclose: true
    });
    if ($('#oetPreDocTime').val() == '') {
        $('#oetPreDocTime').val(tCurrentTime);
    }

    if ($('#oetPreDocDate').val() == '') {
        $('#oetPreDocDate').datepicker("setDate", dCurrentDate);
    }

    $('#obtPreDocDate').unbind().click(function() {
        $('#oetPreDocDate').datepicker('show');
    });

    $('#obtJPreBrowseBookingDate').unbind().click(function() {
        $('#oetJPreDocRefBookDate').datepicker('show');
    });

    $('#obtJPreBrowseRefExtDocDate').unbind().click(function() {
        $('#oetJPreDocRefExtDocDate').datepicker('show');
    });

    $('#obtPreDocTime').unbind().click(function() {
        $('#oetPreDocTime').datetimepicker('show');
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    $('#ocbPreStaAutoGenCode').on('change', function(e) {
        if ($('#ocbPreStaAutoGenCode').is(':checked')) {
            $("#oetPreDocNo").val('');
            $("#oetPreDocNo").attr("readonly", true);
            $('#oetPreDocNo').closest(".form-group").css("cursor", "not-allowed");
            $('#oetPreDocNo').css("pointer-events", "none");
            $("#oetPreDocNo").attr("onfocus", "this.blur()");
            $('#ofmPreSurveyAddForm').removeClass('has-error');
            $('#ofmPreSurveyAddForm .form-group').closest('.form-group').removeClass("has-error");
            $('#ofmPreSurveyAddForm em').remove();
        } else {
            $('#oetPreDocNo').closest(".form-group").css("cursor", "");
            $('#oetPreDocNo').css("pointer-events", "");
            $('#oetPreDocNo').attr('readonly', false);
            $("#oetPreDocNo").removeAttr("onfocus");
        }
    });
    
    // Browser Cst
    $('#oimBrowsePreSurveyCst').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPreSurveyCstBrowse = undefined;
            oPreSurveyCstBrowse = oPreCstPriOption({
                'tReturnInputCode': 'ohdPreSurveyCstCode',
                'tReturnInputName': 'oetPreSurveyCstName',
                'tReturnInputTel': 'oetPreSurveyCstTel',
                'tReturnInputEmail': 'oetPreSurveyCstMail'
            });
            JCNxBrowseData('oPreSurveyCstBrowse');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse ลูกค้า
    var oPreCstPriOption = function(oPreSurveyCstBrowse) {
        let tPreCstCode = oPreSurveyCstBrowse.tReturnInputCode;
        let tPreCstName = oPreSurveyCstBrowse.tReturnInputName;
        let tPreCstTel = oPreSurveyCstBrowse.tReturnInputTel;
        let tPreCstEmail = oPreSurveyCstBrowse.tReturnInputEmail;

        var tSQLWhereBch = "AND ISNULL(TCNMCst.FTAgnCode, '') = '" + $("#oetPreAgnCode").val() + "' ";

        let oOptionReturnPreCst = {
            Title: ['customer/customer/customer', 'tCSTTitle'],
            Table: {
                Master: 'TCNMCst',
                PK: 'FTCstCode'
            },
            Join: {
                Table: ['TCNMCst_L'],
                On: ['TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID =' + nLangEdits, ]
            },
            Where: {
                Condition: [tSQLWhereBch]
            },
            GrideView: {
                ColumnPathLang: 'customer/customer/customer',
                ColumnKeyLang: ['tCSTCode', 'tCSTName'],
                ColumnsSize: ['15%', '75%'],
                DataColumns: ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName', 'TCNMCst.FTCstTel', 'TCNMCst.FTCstEmail'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [2, 3],
                Perpage: 10,
                OrderBy: ['TCNMCst.FDCreateOn DESC'],
            },
            CallBack: {
                StaSingItem: '1',
                ReturnType: 'S',
                Value: ["ohdPreSurveyCstCode", "TCNMCst.FTCstCode"],
                Text: ["oetPreSurveyCstName", "TCNMCst_L.FTCstName"],
            },
            //DebugSQL: true,
            NextFunc: {
                FuncName: 'JSxNextFuncPreCst',
                ArgReturn: ['FTCstTel', 'FTCstEmail']
            },
        };
        return oOptionReturnPreCst;
    };

    function JSxNextFuncPreCst(paData) {
        $("#oetPreSurveyCstTel").val("");
        $("#oetPreSurveyCstMail").val("");
        var tPreCstTel = ''
        var tPreCstEmail = ''
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aPreCstData = JSON.parse(paData);
            tPreCstTel = aPreCstData[0];
            tPreCstEmail = aPreCstData[1];
        }
        $("#oetPreSurveyCstTel").val(tPreCstTel);
        $("#oetPreSurveyCstMail").val(tPreCstEmail);
    }

    //Browse ใบอ้างอิงสั่งงาน
    $('#oimPreBrowseDocRef').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetPreAgnCode').val();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPreDocRefBrowse = undefined;
            oPreDocRefBrowse = oPreDocRefOption({
                'tReturnInputCode': 'ohdPreSurveyCstCode',
                'tReturnInputName': 'oetPreSurveyCstName',
            });
            JCNxBrowseData('oPreDocRefBrowse');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //Browse อ้างอิงลูกค้า
    $('#oimPreBrowseCustomer').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPreCustomerOption   = undefined;
            oPreCustomerOption          = oPreCustomer({
                'tReturnInputCode'  : 'oetPreFrmCstCode',
                'tReturnInputName'  : 'oetJPreFrmCstName',
                'tNextFuncName'     : 'JSxWhenSeletedCustomer',
                'aArgReturn'        : ['FTCstCode', 'FTCstName','FTCstCardID','FTCstTel','FTCstEmail']
            });
            JCNxBrowseData('oPreCustomerOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ============================================ Start Browse ลูกค้า ================================================
    var oPreCustomer    = function(poDataFnc){
        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tNextFuncName       = poDataFnc.tNextFuncName;
        let aArgReturn          = poDataFnc.aArgReturn;
        let oOptionReturn       = {
            Title   : ['document/jobrequest1/jobrequest1', 'tJR1Cst'],
            Table   : {Master:'TCNMCst', PK:'FTCstCode'},
            Join    : {
                Table: ['TCNMCst_L', 'TCNMCstCredit'],
                On: [
                    'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = '+nLangEdits,
                    'TCNMCst_L.FTCstCode = TCNMCstCredit.FTCstCode'
                ]
            },
            Where:{
                Condition : ["AND TCNMCst.FTCstStaActive = '1' "]
            },
            GrideView:{
                ColumnPathLang      : 'document/jobrequest1/jobrequest1',
                ColumnKeyLang       : ['tJR1CstCode', 'tJR1CstName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMCst.FTCstCode','TCNMCst_L.FTCstName','TCNMCst.FTCstCardID','TCNMCst.FTCstTel','TCNMCst.FTCstEmail'],
                DataColumnsFormat   : ['',''],
                DisabledColumns     : [2, 3, 4, 5, 6],
                Perpage             : 10,
                OrderBy             : ['TCNMCst_L.FTCstCode DESC']
            },
            CallBack:{
                ReturnType: 'S',
                Value   : [tInputReturnCode,"TCNMCst.FTCstCode"],
                Text    : [tInputReturnName,"TCNMCst_L.FTCstName"]
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            }
        };
        return oOptionReturn;
    }
    
    // ฟังก์ชั่นทำงานหลังจากทำการเลือกข้อมูลไปค้นหาข้อมูลลูกค้า
    function JSxWhenSeletedCustomer(aReturn){
        var aPreCstData = JSON.parse(aReturn);
        $("#oetJPreFrmCstTel").val(aPreCstData[3]);
        $("#oetJPreFrmCstEmail").val(aPreCstData[4]);
        $('#oetJPreFrmCstAddr').val('');
        $.ajax({
            type: "POST",
            url: "docPreRepairResultFindCstAddress",
            data: {
                "poItem": aReturn
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                let aReturn = JSON.parse(tResult);
                if (aReturn != "") {
                    // Check Data Customer Address
                    let aDataCstAddr = aReturn.aDataCstAddr;
                    if (aDataCstAddr.rtCode == 1) {
                        // Check Type Vession Address
                        let nAddrVer = aDataCstAddr.raItems.FTAddVersion;
                        $("#oetJPreFrmCstTelFax").val(aDataCstAddr.raItems.FTAddFax);

                        if (nAddrVer == 1) {

                        } else if (nAddrVer == 2) {
                            // ทีอยู่เวอร์ชั่น 2
                            let tAddV2Desc1 = aDataCstAddr.raItems.FTAddV2Desc1;
                            let tAddV2Desc2 = aDataCstAddr.raItems.FTAddV2Desc2;
                            // if (tAddV2Desc1 != "" && tAddV2Desc2 == "") {
                            //     $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                            // } else if (tAddV2Desc1 == "" && tAddV2Desc2 != "") {
                            //     $('#oetJPreFrmCstAddr').val(tAddV2Desc2);
                            // } else {
                            //     $('#oetJPreFrmCstAddr').val(tAddV2Desc1 + ' ' + tAddV2Desc2);
                            // }
                            $('#oetJPreFrmCstAddr').val(tAddV2Desc1 + ' ' + tAddV2Desc2);
                        } else {
                            // ทีอยู่เวอร์ชั่น 2
                            // let tAddV2Desc1 = aDataCstAddr.raItems.FTAddV2Desc1;
                            // let tAddV2Desc2 = aDataCstAddr.raItems.FTAddV2Desc2;
                            // if (tAddV2Desc1 != "" && tAddV2Desc2 == "") {
                            //     $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                            // } else if (tAddV2Desc1 == "" && tAddV2Desc2 != "") {
                            //     $('#oetJPreFrmCstAddr').val(tAddV2Desc2);
                            // } else {
                            //     $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                            // }    

                            let tFTAddV1No       = aDataCstAddr.raItems.FTAddV1No;
                            let tFTAddV1Soi      = aDataCstAddr.raItems.FTAddV1Soi;
                            let tFTAddV1Road     = aDataCstAddr.raItems.FTAddV1Road;
                            let tFTSudName       = aDataCstAddr.raItems.FTAddV1SubDistName;
                            let tFTDstName       = aDataCstAddr.raItems.FTAddV1DstName;
                            let tFTPvnName       = aDataCstAddr.raItems.FTAddV1PvnName;
                            let tFTAddV1PostCode = aDataCstAddr.raItems.FTAddV1PostCode;
                            $('#oetJPreFrmCstAddr').val(tFTAddV1No + ' ' + tFTAddV1Soi + ' ' + tFTAddV1Road + ' ' + tFTSudName + ' ' + tFTDstName + ' ' + tFTPvnName + ' ' + tFTAddV1PostCode );
                        }
                    }
                }
                // $("#oetPreDocRefCode").val('');
                // $("#oetJPreDocRefBookDate").val('');
                $("#odvPreCarDetailInfo :input").val('');
                $("#odvPreCarDetailInfo :input").attr('checked',false);
                $(".xCNClickMile").removeClass('xCNActiveMile');
                $('#oimPreBrowseCarRegNo').attr('disabled',false);

                // JSvPreCallPageDataTableAnwser('2');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
    // ============================================ End Browse ลูกค้า ==================================================

    $('#oimPreBrowseCarRegNo').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPreCarCstOption = undefined;
            oPreCarCstOption        = oPreCarCst({
                'tReturnInputCode'  : 'oetPreCarRegCode',
                'tReturnInputName'  : 'oetPreCarRegName',
                'tNextFuncName'     : 'JSxWhenSeletedCstCar',
                'aArgReturn'        : ['FTCarCode','FTCarRegNo','FTImgObj'],
                'tParamsCstCode'    : $('#oetPreFrmCstCode').val()
            });
            JCNxBrowseData('oPreCarCstOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // อ้างอิงทะเบียนรถ
    var oPreCarCst  = function(poDataFnc){
        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tNextFuncName       = poDataFnc.tNextFuncName;
        let aArgReturn          = poDataFnc.aArgReturn;
        let tParamsCstCode      = poDataFnc.tParamsCstCode;
        let tWhereCondition     = "";
        if(tParamsCstCode != ""){
            tWhereCondition     = "AND TSVMCar.FTCarOwner = '" + tParamsCstCode + "'";
        }
        let oOptionReturn       = {
            Title   : ['document/jobrequest1/jobrequest1', 'tJR1CarCst'],
            Table   : {Master:'TSVMCar', PK:'FTCarCode'},
            Join    : {
                Table   : ['TCNMImgObj','TCNMCst_L'],
                On      : ["TCNMImgObj.FTImgRefID = TSVMCar.FTCarCode AND TCNMImgObj.FTImgTable = 'TSVMCar' ","TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = "+nLangEdits ]
            },
            Where   : {
                Condition : [tWhereCondition]
            },
            GrideView:{
                ColumnPathLang      : 'document/jobrequest1/jobrequest1',
                ColumnKeyLang       : ['tJR1CarCstCode', 'tJR1CarCstName', 'tJR1OwnerCstName'],
                ColumnsSize         : ['15%', '15%', '60%'],
                WidthModal          : 50,
                DataColumns         : ['TSVMCar.FTCarCode', 'TSVMCar.FTCarRegNo', 'TCNMCst_L.FTCstName','TCNMImgObj.FTImgObj'],
                DataColumnsFormat   : ['','',''],
                DisabledColumns     : [3],
                Perpage             : 10,
                OrderBy             : ['TSVMCar.FTCarCode ASC']
            },
            CallBack:{
                ReturnType: 'S',
                Value   : [tInputReturnCode,"TSVMCar.FTCarOwner"],
                Text    : [tInputReturnName,"TSVMCar.FTCarRegNo"]
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            },
            //DebugSQL : true
        };
        return oOptionReturn;
    };

    // ฟังก์ชั่นทำงานหลังจากทำการเลือกข้อมูลทะเบียนรถ
    function JSxWhenSeletedCstCar(aReturn, paDataDoc){
        if(aReturn != '' || aReturn != 'NULL'){
            // Find Data Car
            $.ajax({
                type    : "POST",
                url     : "docPreRepairResultFindCar",
                data    : {"poItem" : aReturn },
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    let aReturn = JSON.parse(tResult);
                    // $("#oetPreDocRefCode").val('');
                    // $("#oetJPreDocRefBookDate").val('');
                    if(aReturn != ""){

                        let aDataCarCst = aReturn.aDataCarCst;
                        $('#oetPreFrmCstCode').val('');
                        $('#oetJPreFrmCstName').val('');
                        $('#oetPreFrmCstCode').val(aDataCarCst.raItems.FTCarOwnerCode);
                        $('#oetJPreFrmCstName').val(aDataCarCst.raItems.FTCarOwnerName);
                        if($("#oetJPreFrmCstName").val() == ""){
                            if(aDataCarCst.raItems2 !== null){
                            // Check Type Vession Address
                                $('#oetJPreFrmCstAddr').val('');
                                $("#oetJPreFrmCstTel").val('');
                                $("#oetJPreFrmCstEmail").val('');
                                let nAddrVer = aDataCarCst.raItems2.FTAddVersion;
                                $("#oetJPreFrmCstTelFax").val(aDataCarCst.raItems2.FTAddFax);

                                if (nAddrVer == 1) {    

                                } else if (nAddrVer == 2) {
                                    // ทีอยู่เวอร์ชั่น 2
                                    let tAddV2Desc1     = aDataCstAddr.raItems.FTAddV2Desc1;
                                    let tAddV2Desc2     = aDataCstAddr.raItems.FTAddV2Desc2;
                                    $('#oetJPreFrmCstAddr').val(tAddV2Desc1 + ' ' + tAddV2Desc2);
                                } else {
                                    // ทีอยู่เวอร์ชั่น 2
                                    let tFTAddV1No       = aDataCstAddr.raItems.FTAddV1No;
                                    let tFTAddV1Soi      = aDataCstAddr.raItems.FTAddV1Soi;
                                    let tFTAddV1Road     = aDataCstAddr.raItems.FTAddV1Road;
                                    let tFTSudName       = aDataCstAddr.raItems.FTAddV1SubDistName;
                                    let tFTDstName       = aDataCstAddr.raItems.FTAddV1DstName;
                                    let tFTPvnName       = aDataCstAddr.raItems.FTAddV1PvnName;
                                    let tFTAddV1PostCode = aDataCstAddr.raItems.FTAddV1PostCode;
                                    $('#oetJPreFrmCstAddr').val(tFTAddV1No + ' ' + tFTAddV1Soi + ' ' + tFTAddV1Road + ' ' + tFTSudName + ' ' + tFTDstName + ' ' + tFTPvnName + ' ' + tFTAddV1PostCode );
                                }


                                // if (nAddrVer == 1) {

                                // } else if (nAddrVer == 2) {
                                //     // ทีอยู่เวอร์ชั่น 2 
                                //     let tAddV2Desc1 = aDataCarCst.raItems2.FTAddV2Desc1;
                                //     let tAddV2Desc2 = aDataCarCst.raItems2.FTAddV2Desc2;
                                //     if (tAddV2Desc1 != "" && tAddV2Desc2 == "") {
                                //         $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                                //     } else if (tAddV2Desc1 == "" && tAddV2Desc2 != "") {
                                //         $('#oetJPreFrmCstAddr').val(tAddV2Desc2);
                                //     } else {
                                //         $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                                //     }
                                // } else {
                                //     // ทีอยู่เวอร์ชั่น 2 
                                //     let tAddV2Desc1 = aDataCarCst.raItems2.FTAddV2Desc1;
                                //     let tAddV2Desc2 = aDataCarCst.raItems2.FTAddV2Desc2;
                                //     if (tAddV2Desc1 != "" && tAddV2Desc2 == "") {
                                //         $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                                //     } else if (tAddV2Desc1 == "" && tAddV2Desc2 != "") {
                                //         $('#oetJPreFrmCstAddr').val(tAddV2Desc2);
                                //     } else {
                                //         $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                                //     }
                                // }
                                // $('#oetJPreFrmCstAddr').val(aDataCarCst.raItems2.FTAddV2Desc1);
                                $("#oetJPreFrmCstTel").val(aDataCarCst.raItems2.FTCstTel);
                                $("#oetJPreFrmCstEmail").val(aDataCarCst.raItems2.FTCstEmail);
                            }
                        }

                        // Start Clear Input Car Info
                        var nChkRedlabel = aDataCarCst.raItems.FTCarStaRedLabel
                        $("#oetPreCarRegName").val(aDataCarCst.raItems.FTCarRegNo);
                        $("#oetPrePvnName").val(aDataCarCst.raItems.FTCarRegPvnName);
                        $("#oetPreCarTypeName").val(aDataCarCst.raItems.FTCarTypeName);
                        $("#oetPreCarBrandName").val(aDataCarCst.raItems.FTCarBrandName);
                        $("#oetPreCarModelName").val(aDataCarCst.raItems.FTCarModelName);
                        $("#oetPreCarColorName").val(aDataCarCst.raItems.FTCarColorName);
                        $("#oetPreCarOwnerName").val(aDataCarCst.raItems.FTCarCategoryName);
                        $("#oetPreCarGearName").val(aDataCarCst.raItems.FTCarGearName);
                        $("#oetPreCarVIDRef").val(aDataCarCst.raItems.FTCarVIDRef);

                        if(typeof(paDataDoc) != 'undefined' && paDataDoc != "NULL"){
                            $('#ohdPreDocRefCode').val(paDataDoc[0].ptDocNo);
                            $('#oetPreDocRefCode').val(paDataDoc[0].ptDocNo);
                            var aDataRef = aDataDoc[0].pdDocRefDate.split(" ");

                            $('#oetJPreDocRefBookDate').val(aDataRef[0]).datepicker("refresh");
                            $('#oetPreCarMiter').val(paDataDoc[0].ptCarMile);
                            $('#ohdPreSvOldBchCode').val(paDataDoc[0].ptBchCode);
                        }
                        
                        if(nChkRedlabel == '1'){
                            $("#oetPreCarRedLabel").prop( "checked", true)
                        }else if(nChkRedlabel == '2'){
                            $("#oetPreCarRedLabel").prop( "checked", false)
                        }
                    }
                    JSvPreCallPageDataTableAnwser('2');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{

        }
    }

    //อ้างอิงเอกสารภายใน
    var oPreDocRefOption = function(oPreDocRefBrowse) {
        let tPreDocCode = oPreDocRefBrowse.tReturnInputCode;
        let tPreDocName = oPreDocRefBrowse.tReturnInputName;
        //var tWhereStaAll = "";
        var tWhereStaAll = " AND TSVTJob2OrdHD.FTXshStaDoc = '1' AND ( TSVTJob2OrdHD.FTXshStaClosed != '1' OR ISNULL(TSVTJob2OrdHD.FTXshStaClosed,'') = '' ) ";
        var tWhereAgn = "";
        var tWhereBch = "";
        var tWhereCst = "";
        var tWhereCar = "";

        /*if ($("#oetPreAgnCode").val() != '') {
            var tWhereAgn = "AND TSVTJob2OrdHD.FTAgnCode = '" + $("#oetPreAgnCode").val() + "'";
        }*/

        if ($("#oetPreFrmBchCode").val() != '') {
            var tWhereBch = "AND TSVTJob2OrdHD.FTBchCode = '" + $("#oetPreFrmBchCode").val() + "'";
        }

        if ($("#oetPreFrmCstCode").val() != '') {
            var tWhereCst = "AND TSVTJob2OrdHD.FTCstCode = '" + $("#oetPreFrmCstCode").val() + "'";
        }

        if ($("#oetPreCarRegCode").val() != '') {
            var tWhereCar = "AND J2HDCst.FTCarCode = '" + $("#oetPreCarRegCode").val() + "'";
        }

        var tWhereDocRef = "AND ISNULL(JOB3.FTXshRefDocNo, '') = ''";

        let oOptionReturnPreRefDoc = {
            Title: ['document/prerepairresult/prerepairresult', 'tPreSurveyDocRef'],
            Table: {
                Master: 'TSVTJob2OrdHD',
                PK: 'FTXshDocNo'
            },
            Join: {
                Table: ['TCNMCst', 'TCNMCst_L', 'TCNMAgency_L', 'TSVTJob2OrdHDCst J2HDCst', 'TSVMCar', 'TSVMCarInfo_L T1', 'TSVMCarInfo_L T2', 'TCNMBranch_L', 'TSVTJob3ChkHDDocRef JOB3','TCNMUser_L USRL'],
                On: [
                    'TSVTJob2OrdHD.FTCstCode = TCNMCst.FTCstCode',
                    'TSVTJob2OrdHD.FTCstCode = TCNMCst_L.FTCstCode AND TCNMCst_L.FNLngID =' + nLangEdits,
                    'TSVTJob2OrdHD.FTAgnCode = TCNMAgency_L.FTAgnCode AND TCNMAgency_L.FNLngID =' + nLangEdits,
                    'TSVTJob2OrdHD.FTXshDocNo = J2HDCst.FTXshDocNo',
                    'J2HDCst.FTCarCode = TSVMCar.FTCarCode',
                    'TSVMCar.FTCarBrand  = T1.FTCaiCode AND T1.FNLngID =' + nLangEdits,
                    'TSVMCar.FTCarModel  = T2.FTCaiCode AND T2.FNLngID =' + nLangEdits,
                    'TSVTJob2OrdHD.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID =' + nLangEdits,
                    'JOB3.FTXshRefDocNo = TSVTJob2OrdHD.FTXshDocNo AND JOB3.FTXshRefType = 1',
                    'USRL.FTUsrCode = TSVTJob2OrdHD.FTUsrCode AND USRL.FNLngID = 1'
                ]
            },
            Where: {
                Condition: [tWhereStaAll, tWhereBch , tWhereCst, tWhereDocRef, tWhereCar]
            },
            GrideView: {
                ColumnPathLang: 'document/prerepairresult/prerepairresult',
                ColumnKeyLang: ['tPreSurveyBch', 'tPreSurveyDocNo', 'tPreSurveyDocDate', 'tPreSurveyCst', 'tPreLabelCarRegNo'],
                ColumnsSize: ['20%', '20%', '20%', '20%', '20%'],
                DataColumns: [
                    'TCNMBranch_L.FTBchName',
                    'TSVTJob2OrdHD.FTXshDocNo',
                    'TSVTJob2OrdHD.FDXshDocDate',
                    'TCNMCst_L.FTCstName',
                    'TSVMCar.FTCarRegNo',
                    'T1.FTCaiName as FTCarBrand',
                    'T2.FTCaiName as FTCarModel',
                    'TCNMCst.FTCstTel',
                    'TCNMCst.FTCstEmail',
                    'TCNMAgency_L.FTAgnName',
                    'TCNMAgency_L.FTAgnCode',
                    'TCNMBranch_L.FTBchCode',
                    'TSVTJob2OrdHD.FTCstCode',
                    'TSVMCar.FTCarCode',
                    'USRL.FTUsrName',
                    'TSVTJob2OrdHD.FDXshTimeStart',
                    'TSVTJob2OrdHD.FTUsrCode',
                    'TSVTJob2OrdHD.FCXshCarMileAge'
                ],
                DataColumnsFormat: ['', '', '', '', ''],
                DisabledColumns: [5, 6, 7, 8, 9, 10, 11, 12,13 ,14, 15, 16, 17],
                Perpage: 10,
                OrderBy: ['TSVTJob2OrdHD.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["ohdPreDocRefCode", "TSVTJob2OrdHD.FTXshDocNo"],
                Text: ["oetPreDocRefCode", "TSVTJob2OrdHD.FTXshDocNo"],
            },
            NextFunc: {
                FuncName: 'JSxNextFuncPreDocRef',
                ArgReturn: ['FDXshDocDate', 'FTCarBrand', 'FTCarModel', 'FTCarRegNo', 'FTCstTel', 'FTCstEmail', 'FTAgnName', 'FTBchName', 'FTCstName', 'FTAgnCode', 'FTBchCode', 'FTCstCode', 'FTCarCode','FTUsrName','FDXshTimeStart','FTUsrCode' , 'FCXshCarMileAge']
            }
        };
        return oOptionReturnPreRefDoc;
    };

    function JSxNextFuncPreDocRef(paData) {
        var tPreDateStaService = '';
        var tPreSrvCarBrand = '';
        var tPreSrvCarModel = '';
        var tPreRegCarNo = '';
        var tPreAgnCode = '';
        var tPreAgnName = '';
        var tPreBchCode = '';
        var tPreBchName = '';
        var tPreCstName = '';
        var tPreCstTel = '';
        var tPreCstMail = '';
        var tPreUsrName = '';
        var tPreCstCode = '';
        var tPreCarMile = '';
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aPreCstData = JSON.parse(paData);
            tPreDateStaService = aPreCstData[0];
            tPreSrvCarBrand = aPreCstData[1];
            tPreSrvCarModel = aPreCstData[2];
            tPreRegCarNo = aPreCstData[3];
            tPreAgnName = aPreCstData[6];
            tPreBchName = aPreCstData[7];
            tPreCstName = aPreCstData[8];
            tPreCstTel = aPreCstData[4];
            tPreCstMail = aPreCstData[5];
            tPreAgnCode = aPreCstData[9];
            tPreBchCode = aPreCstData[10];
            tPreCstCode = aPreCstData[11];
            tPreUsrName = aPreCstData[13];
            tPreUsrCode = aPreCstData[15];
            tPreCarMile = aPreCstData[16];
            var tPreDate = aPreCstData[0].split(' ');
            if(aPreCstData[14] != 'undefined'  && aPreCstData[14] != null){
                var tPreUsrDate = aPreCstData[14].split(' ');
                var tPreUsrDateUse = tPreUsrDate[0];
            }else{
                var tPreUsrDateUse = '';
            }
        }
        $("#oetJPreDocRefBookDate").datepicker("setDate", tPreDate[0]);
        $("#oetJPreFrmCstName").val(tPreCstName);
        $("#oetJPreFrmCstTel").val(tPreCstTel);
        $("#oetJPreFrmCstEmail").val(tPreCstMail);
        $("#oetPreUsrValetName").val(tPreUsrName);
        $("#ohdPreSvOldAgnCode").val(aPreCstData[9]);
        $("#ohdPreSvOldBchCode").val(aPreCstData[10]);
        $("#ohdPreTaskRefUsrCode").val(aPreCstData[15]);
        $("#oetPreBookDate").datepicker("setDate", tPreUsrDateUse);
        $('#oimPreBrowseCarRegNo').attr('disabled',false);
        $('#oetPreFrmCstCode').val(aPreCstData[11]);
        
        $.ajax({
            type: "POST",
            url: "docPreRepairResultFindCst",
            data: {
                "poItem": paData,
                "tDocNo" : $("#ohdPreDocRefCode").val(),
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                let aReturn = JSON.parse(tResult);
                if (aReturn != "") {
                    // Check Data Customer Address
                    let aDataCstAddr = aReturn.aDataCstAddr;
                    if (aDataCstAddr.rtCode == 1) {
                        // Check Type Vession Address
                        let nAddrVer = aDataCstAddr.raItems.FTAddVersion;
                        $("#oetJPreFrmCstTelFax").val(aDataCstAddr.raItems.FTAddFax);
                        if (nAddrVer == 1) {

                        } else if (nAddrVer == 2) {
                            // ทีอยู่เวอร์ชั่น 2
                            let tAddV2Desc1 = aDataCstAddr.raItems.FTAddV2Desc1;
                            let tAddV2Desc2 = aDataCstAddr.raItems.FTAddV2Desc2;
                            if (tAddV2Desc1 != "" && tAddV2Desc2 == "") {
                                $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                            } else if (tAddV2Desc1 == "" && tAddV2Desc2 != "") {
                                $('#oetJPreFrmCstAddr').val(tAddV2Desc2);
                            } else {
                                $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                            }
                        } else {
                            // ทีอยู่เวอร์ชั่น 2
                            let tAddV2Desc1 = aDataCstAddr.raItems.FTAddV2Desc1;
                            let tAddV2Desc2 = aDataCstAddr.raItems.FTAddV2Desc2;
                            if (tAddV2Desc1 != "" && tAddV2Desc2 == "") {
                                $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                            } else if (tAddV2Desc1 == "" && tAddV2Desc2 != "") {
                                $('#oetJPreFrmCstAddr').val(tAddV2Desc2);
                            } else {
                                $('#oetJPreFrmCstAddr').val(tAddV2Desc1);
                            }
                        }
                    }
                    let aDataCarCst = aReturn.aDataCarCst;
                    if (aDataCarCst.rtCode == 1) {
                        var nChkRedlabel = aDataCarCst.raItems.FTCarStaRedLabel
                        $("#oetPreCarRegName").val(aDataCarCst.raItems.FTCarRegNo);
                        $("#oetPrePvnName").val(aDataCarCst.raItems.FTCarRegPvnName);
                        $("#oetPreCarTypeName").val(aDataCarCst.raItems.FTCarTypeName);
                        $("#oetPreCarBrandName").val(aDataCarCst.raItems.FTCarBrandName);
                        $("#oetPreCarModelName").val(aDataCarCst.raItems.FTCarModelName);
                        $("#oetPreCarColorName").val(aDataCarCst.raItems.FTCarColorName);
                        $("#oetPreCarOwnerName").val(aDataCarCst.raItems.FTCarCategoryName);
                        $("#oetPreCarGearName").val(aDataCarCst.raItems.FTCarGearName);
                        $("#oetPreCarVIDRef").val(aDataCarCst.raItems.FTCarVIDRef);

                        // oetPreCarRedLabel
                        if(nChkRedlabel == '1'){
                            $("#oetPreCarRedLabel").prop( "checked", true)
                        }else if(nChkRedlabel == '2'){
                            $("#oetPreCarRedLabel").prop( "checked", false)
                        }
                    }
                    let aDataJob1 = aReturn.aDataJob1HD;

                    if (aDataJob1.rtCode == 1) {
                        //$("#oetPreCarMiter").val(aDataJob1.raItems.FCXshCarMileage);
                        $("#oetPreTimeBook").val(aDataJob1.raItems.fdxshrefdocdate);
                        $("#oetPreBookUse").prop( "checked", true);
                        $('.xCNClickMile').each( function () {
                            if($(this).data('hiddenval') == aDataJob1.raItems.ftxshcarfuel){
                                $(this).addClass( "xCNActiveMile" );
                            }else{
                                $(this).removeClass( "xCNActiveMile" );
                            }
                        });
                    }else{
                        //$("#oetPreCarMiter").val('');
                        $("#oetPreTimeBook").val('');
                        $("#oetPreBookUse").prop( "checked", false)
                        $('.xCNClickMile').each( function () {
                            $(this).removeClass( "xCNActiveMile" );
                        });
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

        JSvPreCallPageDataTableAnwser('2');

        //เลขไมล์
        if(tPreCarMile == '' || tPreCarMile == null || tPreCarMile == .0000){
            nPreCarMile = 0;
        }else{
            nPreCarMile = parseInt(tPreCarMile);
        }
        $('#oetPreCarMiter').val(nPreCarMile);
    }

    // เลื่อกชื่อพนักงาน
    $('#oimBrowseUsrBch').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPreUsrUse = undefined;
            oPreUsrUse = oPreUsrUseOption({
                'tReturnInputCode': 'ohdPreSurveyCstCode',
                'tReturnInputName': 'oetPreSurveyCstName',
            });
            JCNxBrowseData('oPreUsrUse');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var oPreUsrUseOption = function(oPreUsrUse) {
        let tPreUsrUseCode = oPreUsrUse.tReturnInputCode;
        let tPreUsrUseName = oPreUsrUse.tReturnInputName;
        let oOptionReturnPreUsrUse = {
            Title: ['document/prerepairresult/prerepairresult', 'tPreSurveyUsr'],
            Table: {
                Master: 'TCNMUser',
                PK: 'FTUsrCode'
            },
            Join: {
                Table: ['TCNMUser_L'],
                On: ['TCNMUser_L.FTUsrCode = TCNMUser.FTUsrCode AND TCNMUser_L.FNLngID =' + nLangEdits, ]
            },
            GrideView: {
                ColumnPathLang: 'company/warehouse/warehouse',
                ColumnKeyLang: ['tBrowseSHPCode', 'tBrowseSHPName'],
                ColumnsSize: ['15%', '75%'],
                DataColumns: ['TCNMUser.FTUsrCode', 'TCNMUser_L.FTUsrName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMUser.FTUsrCode ASC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["ohdPreTaskRefUsrCode", "TCNMUser.FTUsrCode"],
                Text: ["oetPreTaskRefUsrName", "TCNMUser_L.FTUsrName"],
            },
        };
        return oOptionReturnPreUsrUse;
    };

    // เลื่อกสาขา
    $('#obtPreSvBrowseBCH').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPreBrowseBranchOption = undefined;
            oPreBrowseBranchOption = oBranchOption({
                'tReturnInputCode': 'oetPreFrmBchCode',
                'tReturnInputName': 'oetPreFrmBchName'
            });
            JCNxBrowseData('oPreBrowseBranchOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ตัวแปร Option Browse Modal สาขา
    var oBranchOption = function(poDataFnc) {
        var tInputReturnCode = poDataFnc.tReturnInputCode;
        var tInputReturnName = poDataFnc.tReturnInputName;

        tUsrLevel = "<?= $this->session->userdata('tSesUsrLevel') ?>";
        tBchMulti = "<?= $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if (tUsrLevel != "HQ") {
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN (" + tBchMulti + ")";
        } else {
            tSQLWhereBch = "";
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
                Condition: [tSQLWhereBch, tSQLWhereAgn]
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
            //DebugSQL : true,
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text: [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
        };
        return oOptionReturn;
    }

    // เลือกตัวแทนขาย
    $('#oimPreSvBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPreBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetPreAgnCode',
                'tReturnInputName': 'oetPreAgnName',
            });
            JCNxBrowseData('oPreBrowseAgencyOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

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
                FuncName: 'JSxNextFuncPreAgn'
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

    function JSxNextFuncPreAgn() {
        $("#ohdPreBchCode").val('');
        $("#oetPreBchName").val('');
        $("#ohdPreSurveyCstCode").val('');
        $("#oetPreSurveyCstName").val('');
        $("#oetPreSurveyCstTel").val('');
        $("#oetPreSurveyCstMail").val('');
        $("#ohdPreDocRefCode").val('');
        $("#oetPreDocRefCode").val('');
        $("#oetPreSurveyDateStaService").val('');
        $("#oetPreSurveySrvCar").val('');
        $("#oetPreSurveyCarNo").val('');
    }

    function JSxPreSetStatusClickSubmit(pnStatus) {
        $("#ohdPreSvCheckSubmitByButton").val(pnStatus);
    }

    // Event Click Appove Document
    $('#obtPreSvApproveDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxPreSetStatusClickSubmit(2);
            JSxPreApproveDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Cancel Document
    $('#obtPreSvCancelDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSnPreCancelDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Submit From Document
    $('#obtPreSvSubmitFromDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {

            if($('#oetPreCarMiter').val() == '' || $('#oetPreCarMiter').val() == null ){
                FSvCMNSetMsgWarningDialog('กรุณาระบุเลขไมล์');
                return false;
            }

            JSxPreSetStatusClickSubmit(1);
            $('#obtSubmitPre').click();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // validate and insert
    function JSoAddEditPre(ptRoute) {
        var nStaSession = JCNxFuncChkSessionExpired();
        var nStaDoc = $('#ohdPreStaDoc').val();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1 && nStaDoc != 3) {
            $('#ofmPreSurveyAddForm').validate({
                rules: {
                    oetPreDocNo: {
                        "required": {
                            depends: function(oElement) {
                                if (ptRoute == "docPreRepairResultEventAdd") {
                                    if ($('#ocbPreStaAutoGenCode').is(':checked')) {
                                        return false;
                                    } else {
                                        return true;
                                    }
                                } else {
                                    return false;
                                }
                            }
                        }
                    },
                    oetPreBchName: {
                        "required": true
                    },
                    oetPreSurveyCstName: {
                        "required": true
                    },
                    oetPreDocRefCode: {
                        "required": true
                    }
                },
                messages: {
                    oetPreDocNo: {
                        "required": $('#oetPreDocNo').attr('data-validate-required')
                    },
                    oetPreSurveyCstName: {
                        "required": $('#oetPreSurveyCstName').attr('data-validate-required')
                    },
                    oetPreBchName: {
                        "required": $('#oetPreBchName').attr('data-validate-required')
                    },
                    oetPreDocRefCode: {
                        "required": $('#oetPreDocRefCode').attr('data-validate-required')
                    }
                },
                errorElement: "em",
                errorPlacement: function(error, element) {
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
                highlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass("has-error").removeClass("has-success");
                },
                unhighlight: function(element, errorClass, validClass) {
                    var nStaCheckValid = $(element).parents('.form-group').find('.help-block').length
                    if (nStaCheckValid != 0) {
                        $(element).closest('.form-group').addClass("has-success").removeClass("has-error");
                    }
                },
                submitHandler: function(form) {
                    if (!$('#ocbPreStaAutoGenCode').is(':checked') && ptRoute == "docPreRepairResultEventAdd") {
                        JSxPreValidateDocCodeDublicate(ptRoute);
                    } else {
                        if ($("#ohdPreSvCheckSubmitByButton").val() == 1) {
                            JSxPreSubmitEventByButton(ptRoute);
                        }
                    }
                },
            });
        } else if (typeof(nStaSession) !== 'undefined' && nStaSession == 1 && nStaDoc == 3) {
            if (!$('#ocbPreStaAutoGenCode').is(':checked')) {
                JSxPreValidateDocCodeDublicate(ptRoute);
            } else {
                if ($("#ohdPreSvCheckSubmitByButton").val() == 1) {
                    JSxPreSubmitEventByButton(ptRoute);
                }
            }
        } else {
            JCNxShowMsgSessionExpired();
        }

    }

    // Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
    function JSxPreValidateDocCodeDublicate(ptRoute) {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName': 'TSVTJob3ChkHD',
                'tFieldName': 'FTXshDocNo',
                'tCode': $('#oetPreDocNo').val()
            },
            success: function(oResult) {
                var aResultData = JSON.parse(oResult);
                $("#ohdPreCheckDuplicateCode").val(aResultData["rtCode"]);
                if ($("#ohdPreSvCheckClearValidate").val() != 1) {
                    $('#ofmPreSurveyAddForm').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value, element) {
                    if (ptRoute == "docPreRepairResultEventAdd") {
                        if ($('#ocbPreStaAutoGenCode').is(':checked')) {
                            return true;
                        } else {
                            if ($("#ohdPreCheckDuplicateCode").val() == 1) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    } else {
                        return true;
                    }
                });

                // Set Form Validate From Add Document
                $('#ofmDOFormAdd').validate({
                    focusInvalid: false,
                    onclick: false,
                    onfocusout: false,
                    onkeyup: false,
                    rules: {
                        oetPreDocNo: {
                            "dublicateCode": {}
                        }
                    },
                    messages: {
                        oetPreDocNo: {
                            "dublicateCode": $('#oetPreDocNo').attr('data-validate-duplicate')
                        }
                    },
                    errorElement: "em",
                    errorPlacement: function(error, element) {
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
                    highlight: function(element, errorClass, validClass) {
                        $(element).closest('.form-group').addClass("has-error");
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).closest('.form-group').removeClass("has-error");
                    },
                    submitHandler: function(form) {
                        if ($("#ohdPreSvCheckSubmitByButton").val() == 1) {
                            JSxPreSubmitEventByButton(ptRoute);
                        }
                    }
                })
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function: Validate Success And Send Ajax Add/Update Document
    function JSxPreSubmitEventByButton(ptRoute) {
        var nTrLength = $('#otbDataTable tbody tr').length;
        var aPreAns = [];
        var aPreQue = [];

        $("#ocbPreStaAutoGenCode").prop("disabled", false);
        $("#ocbPreStaDocAct").prop("disabled", false);
        $(".xWRadioRate").prop("disabled", false);
        $(".xCNPreAns").prop("disabled", false);

        $('.xCNPreAns:checked').each(function() {
            const aResult = {
                tDocNo: $(this).attr('data-docno'),
                tSgName: $(this).attr('data-sgname'),
                nSeqDt: $(this).attr('data-seqdt'),
                nSeqAs: $(this).attr('data-seqas'),
                tQueName: $(this).attr('data-quename'),
                tResName: $(this).attr('data-resname'),
                tResVal: $(this).attr('data-resval'),
                nQueType: $(this).attr('data-quetype')
            };

            aPreAns.push(aResult);
        });

        $('.xCNPreAns[type=text]').each(function() {
            const aResult = {
                tDocNo: $(this).attr('data-docno'),
                tSgName: $(this).attr('data-sgname'),
                nSeqDt: $(this).attr('data-seqdt'),
                nSeqAs: $(this).attr('data-seqas'),
                tQueName: $(this).attr('data-quename'),
                tResName: $(this).val(),
                tResVal: '',
                nQueType: $(this).attr('data-quetype')
            };
            if ($(this).val() != '') {
                aPreAns.push(aResult);
            }
        });

        $('textarea.xCNPreAns').each(function() {
            const aResult = {
                tDocNo: $(this).attr('data-docno'),
                tSgName: $(this).attr('data-sgname'),
                nSeqDt: $(this).attr('data-seqdt'),
                nSeqAs: $(this).attr('data-seqas'),
                tQueName: $(this).attr('data-quename'),
                tResName: $(this).val(),
                tResVal: '',
                nQueType: $(this).attr('data-quetype')
            };

            if ($(this).val() != '') {
                aPreAns.push(aResult);
            }
        });

        $('#otbDataTable tbody tr').each(function() {
            $(this).find('.xWQuestion').each(function() {
                const aResult = {
                    tDocNo: $(this).attr('data-docno'),
                    nSeqDt: $(this).attr('data-seqdt'),
                    nQueType: $(this).attr('data-quetype')
                };

                aPreQue.push(aResult);
            })
        })


        // if (aPreAns.length < nTrLength) {
        //     $('#odvPreModalvalidate').modal('show');
        //     return;
        // }

        $.ajax({
            type: "POST",
            url: ptRoute,
            data: $('#ofmPreSurveyAddForm').serialize() + '&aPreAns=' + JSON.stringify(aPreAns) + '&aPreQue=' + JSON.stringify(aPreQue),
            success: function(oResult) {
                var aReturn = JSON.parse(oResult);

                if (aReturn['nStaEvent'] == 1) {
                    var oPreCallDataTableFile = {
                        ptElementID : 'odvPreSvShowDataTable',
                        ptBchCode   : aReturn['tBchCode'],
                        ptDocNo     : aReturn['tDocNo'],
                        ptDocKey    : 'TSVTJob3ChkHD'
                    }
                    JCNxUPFInsertDataFile(oPreCallDataTableFile);

                    switch (aReturn['nStaCallBack']) {
                        case '1':
                            JSvPreSvCallPageEdit(aReturn['tAgnCode'], aReturn['tBchCode'], aReturn['tDocNo']);
                            break;
                        case '2':
                            JSvPreSvCallPageAdd();
                            break;
                        case '3':
                            JSvPreSvCallPageList()
                            break;
                        default:
                            JSvPreSvCallPageEdit(aReturn['tAgnCode'], aReturn['tBchCode'], aReturn['tDocNo']);
                    }
                } else {
                    FSvCMNSetMsgErrorDialog(aReturn['tStaMessg']);
                    JCNxCloseLoading();
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //พิมพ์เอกสาร
    function JSxPrePrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tDOBchCode); ?>'},
            {"DocCode"      : $("#oetPreDocNo").val()}, // เลขที่เอกสาร
            {"DocBchCode"   : $("#ohdPreSvOldBchCode").val()}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMVehicleChk?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

</script>
