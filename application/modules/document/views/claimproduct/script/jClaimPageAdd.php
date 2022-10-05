<script>

    var tStatusDoc = $('#ohdCLMStaDoc').val();
    if(tStatusDoc == 2){ //ถ้าเป็นเอกสารยกเลิก
        $('.xCNClaimNextStep').hide();
        $('#obtCLMPrintDocStep1').hide();
    }

    $('.selectpicker').selectpicker('refresh');

    $('.xCNDatePicker').datepicker({
        format                  : "yyyy-mm-dd",
        todayHighlight          : true,
        enableOnReadonly        : false,
        disableTouchKeyboard    : true,
        autoclose               : true
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    //กดถัดไป (ที่กลมๆ) [TAB LEVEL 1]
    $('.xCNClaimCircle').on('click', function(){
        if(tStatusDoc == 2){ //เอกสารยกเลิก
            return;
        }

        var tTab = $(this).data('tab');

        //เช็คว่ามีข้อมูลหรือยัง
        if(tTab == 'odvClaimStep2'){
            if($('#ohdCLMStaPrc').val() == '' || $('#ohdCLMStaPrc').val() == null || $('#ohdCLMStaPrc').val() < 2){
                $('#odvCLMModalStepNotClear #ospCLMModalStepNotClear').text('กรุณากด "ยืนยันการเคลม" ให้ครบทุกขั้นตอน ก่อนทำรายการถัดไป');
                $('#odvCLMModalStepNotClear').modal('show');
                return;
            }
        }

        //เช็คว่ามีข้อมูลหรือยัง ก่อนจะไป step3
        if(tTab == 'odvClaimStep3' || tTab == 'odvClaimStep4'){
            if($('#ohdCLMStaPrc').val() < 3){
                $('#odvCLMModalStepNotClear #ospCLMModalStepNotClear').text('กรุณาทำการส่งสินค้าไปยังผู้จำหน่ายให้ครบถ้วน ก่อนทำรายการถัดไป');
                $('#odvCLMModalStepNotClear').modal('show');
                return;
            }
        }

        switch(tTab){
            case "odvClaimStep1" : {
                $('.xCNClaimStep1').css( "background","#fff" );
                $('.xCNClaimStep2').css( "background","#d6d6d6" );
                $('.xCNClaimStep3').css( "background","#d6d6d6" );
                $('.xCNClaimStep4').css( "background","#d6d6d6" );

                $('.xCNClaimStep1').css('border', '2px solid #000');
                $('.xCNClaimStep2').css('border', '2px solid #d6d6d6');
                $('.xCNClaimStep3').css('border', '2px solid #d6d6d6');
                $('.xCNClaimStep4').css('border', '2px solid #d6d6d6');

                $('#odvClaimLine').css( "background","#d6d6d6" );

                //ปุ่มถัดไป + ย้อนกลับ
                $('.xCNClaimBackStep').css('display','none');
                $('.xCNClaimNextStep').css('display','inline-block');
                break;
            }
            case "odvClaimStep2" : {
                $('.xCNClaimStep1').css( "background","#000" );
                $('.xCNClaimStep2').css( "background","#fff" );
                $('.xCNClaimStep3').css( "background","#d6d6d6" );
                $('.xCNClaimStep4').css( "background","#d6d6d6" );

                $('.xCNClaimStep1').css('border', '2px solid #000');
                $('.xCNClaimStep2').css('border', '2px solid #000');
                $('.xCNClaimStep3').css('border', '2px solid #d6d6d6');
                $('.xCNClaimStep4').css('border', '2px solid #d6d6d6');

                $('#odvClaimLine').css( "background","linear-gradient(to right, black 34%, #d6d6d6 20% 40%)" );

                //ปุ่มถัดไป + ย้อนกลับ
                $('.xCNClaimBackStep').attr('disabled',false);
                $('.xCNClaimBackStep').css('display','inline-block');
                $('.xCNClaimNextStep').css('display','inline-block');
                break;
            }
            case "odvClaimStep3" : {
                $('.xCNClaimStep1').css( "background","#000" );
                $('.xCNClaimStep2').css( "background","#000" );
                $('.xCNClaimStep3').css( "background","#fff" );
                $('.xCNClaimStep4').css( "background","#d6d6d6" );

                $('.xCNClaimStep1').css('border', '2px solid #000');
                $('.xCNClaimStep2').css('border', '2px solid #000');
                $('.xCNClaimStep3').css('border', '2px solid #000');
                $('.xCNClaimStep4').css('border', '2px solid #d6d6d6');

                $('#odvClaimLine').css( "background","linear-gradient(to right, black 66%, #d6d6d6 20% 40%)" );

                //ปุ่มถัดไป + ย้อนกลับ
                $('.xCNClaimBackStep').attr('disabled',false);
                $('.xCNClaimBackStep').css('display','inline-block');
                $('.xCNClaimNextStep').css('display','inline-block');
                break;
            }
            case "odvClaimStep4" : {
                $('.xCNClaimStep1').css( "background","#000" );
                $('.xCNClaimStep2').css( "background","#000" );
                $('.xCNClaimStep3').css( "background","#000" );
                $('.xCNClaimStep4').css( "background","#fff" );

                $('.xCNClaimStep1').css('border', '2px solid #000');
                $('.xCNClaimStep2').css('border', '2px solid #000');
                $('.xCNClaimStep3').css('border', '2px solid #000');
                $('.xCNClaimStep4').css('border', '2px solid #d6d6d6');

                $('#odvClaimLine').css( "background","linear-gradient(to right, black 100%, #d6d6d6 20% 40%)" );

                //ปุ่มถัดไป + ย้อนกลับ
                $('.xCNClaimBackStep').attr('disabled',false);
                $('.xCNClaimBackStep').css('display','inline-block');
                $('.xCNClaimNextStep').css('display','none');

                //โหลดตารางใหม่
                JSxCLMStep4ResultLoadDatatable();
                break;
            }
            default : {
            }
        }

        $('.xCNClaimCircle').removeClass('active');
        $(this).addClass('active');
        $(".xCNClaimTabContent .tab-pane").removeClass('active').removeClass('in');
        $(".xCNClaimTabContent #"+tTab).addClass("active").addClass("in");
    });
    
    //กดถัดไป (ที่ปุ่ม) [TAB LEVEL 1]
    $('.xCNClaimNextStep').on('click', function(){
        var tStepNow = $('#odvClaimLine .xCNClaimCircle.active').data('step');

        //เช็คว่ามีข้อมูลหรือยัง
        if(tStepNow == 1){
            if($('#ohdCLMStaPrc').val() == '' || $('#ohdCLMStaPrc').val() == null || $('#ohdCLMStaPrc').val() < 2){
                $('#odvCLMModalStepNotClear #ospCLMModalStepNotClear').text('กรุณากด "ยืนยันการเคลม" ให้ครบทุกขั้นตอน ก่อนทำรายการถัดไป');
                $('#odvCLMModalStepNotClear').modal('show');
                return;
            }
        }

        //เช็คว่ามีข้อมูลหรือยัง ก่อนจะไป step3
        if(tStepNow == 2){
            if($('#ohdCLMStaPrc').val() < 3){
                $('#odvCLMModalStepNotClear #ospCLMModalStepNotClear').text('กรุณาทำการส่งสินค้าไปยังผู้จำหน่ายให้ครบถ้วน ก่อนทำรายการถัดไป');
                $('#odvCLMModalStepNotClear').modal('show');
                return;
            }
        }

        if(tStepNow >= 1){
            $('.xCNClaimBackStep').css('display','inline-block');
            $('.xCNClaimBackStep').attr('disabled',false);
        }

        if(tStepNow < 4){
            $(".xCNClaimTabContent .tab-pane").removeClass('active').removeClass('in');
            setTimeout(function(){
                $('.xCNClaimCircle.xCNClaimStep'+(tStepNow+1)).trigger('click');
            },100);
        }
    });

    //กดย้อนกลับ [TAB LEVEL 1]
    $('.xCNClaimBackStep').on('click', function(){
        var tStepNow = $('#odvClaimLine .xCNClaimCircle.active').data('step');
        if(tStepNow >= 1){
            $(".xCNClaimTabContent .tab-pane").removeClass('active').removeClass('in');
            setTimeout(function(){
                $('.xCNClaimCircle.xCNClaimStep'+(tStepNow-1)).trigger('click');
            },100);
        }
    });

    //เลือกสาขา
    var oBranchOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var tSQLWhere           = "";

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        if(tUsrLevel != "HQ"){ //แบบสาขา
            tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }else{  //สำนักงานใหญ่
            // if($('#ohdIVADCode').val() == '' || $('#ohdIVADCode').val() == null){
            //     tSQLWhere += "";
            // }else{
            //     tSQLWhere += " AND (TCNMBranch.FTAgnCode = " + $('#ohdIVADCode').val() +" OR ISNULL(TCNMBranch.FTAgnCode,'') = '' )";
            // }
        }
        
        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn       = {
            Title       : ['authen/user/user', 'tBrowseBCHTitle'],
            Table       : {
                Master  : 'TCNMBranch',
                PK      : 'FTBchCode'
            },
            Join: {
                Table   : ['TCNMBranch_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where : {
                Condition : [tSQLWhere]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['',''],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode DESC']
            },
            CallBack: {
                ReturnType          : 'S',
                Value               : [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text                : [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
            NextFunc:{
                FuncName            : tNextFuncName,
                ArgReturn           : ['FTBchCode']
            }
        };
        return oOptionReturn;
    }
    $('#obtCLMBrowseBranch').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oCLMCustomerOption   = undefined;
            oCLMCustomerOption          = oBranchOption({
                'tReturnInputCode'  : 'ohdCLMBchCode',
                'tReturnInputName'  : 'oetCLMBchName',
                'tNextFuncName'     : ''
            });
            JCNxBrowseData('oCLMCustomerOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //เลือกลูกค้า 
    var oCLMCustomer    = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var oOptionReturn       = {
            Title   : ['customer/customer/customer', 'tCSTTitle'],
            Table   : {Master:'TCNMCst', PK:'FTCstCode'},
            Join    : {
                Table: ['TCNMCst_L','TCNMCstAddress_L'],
                On: [
                    'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = '+nLangEdits,
                    'TCNMCst_L.FTCstCode = TCNMCstAddress_L.FTCstCode' 
                ]
            },
            Where:{
                Condition           : ["AND TCNMCst.FTCstStaActive = '1' AND (ISNULL(TCNMCstAddress_L.FTAddGrpType,'') = '1' OR ISNULL(TCNMCstAddress_L.FTAddGrpType,'') = '') AND ( ISNULL( TCNMCstAddress_L.FTAddRefNo, '' ) = '1' OR ISNULL( TCNMCstAddress_L.FTAddRefNo, '' ) = '' )"]
            },
            GrideView:{
                ColumnPathLang      : 'customer/customer/customer',
                ColumnKeyLang       : ['tCSTCode', 'tCSTName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName','TCNMCst.FTCstTaxNo','TCNMCst.FTCstTel','TCNMCst.FTCstEmail','TCNMCstAddress_L.FTAddV2Desc1'],
                DataColumnsFormat   : ['',''],
                DisabledColumns     : [2 , 3 , 4 , 5],
                Perpage             : 10,
                OrderBy             : ['TCNMCst_L.FTCstCode ASC']
            },
            CallBack:{
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TCNMCst.FTCstCode"],
                Text        : [tInputReturnName,"TCNMCst_L.FTCstName"]
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            }
        };
        return oOptionReturn;
    }
    $('#oimCLMBrowseCustomer').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oCLMCustomerOption   = undefined;
            oCLMCustomerOption          = oCLMCustomer({
                'tReturnInputCode'  : 'oetCLMFrmCstCode',
                'tReturnInputName'  : 'oetCLMFrmCstName',
                'tNextFuncName'     : 'JSxWhenSeletedCustomer',
                'aArgReturn'        : ['FTCstName', 'FTCstTaxNo', 'FTCstTel', 'FTCstEmail', 'FTAddV2Desc1','FTCstCode']
            });
            JCNxBrowseData('oCLMCustomerOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    function JSxWhenSeletedCustomer(poDataNextFunc){
        var aData;

        if (poDataNextFunc  != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            $.ajax({
                type: "POST",
                url: "docClaimResultFindCstAddress",
                data: {
                    "poItem": poDataNextFunc
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    let aReturn = JSON.parse(tResult);
                    $('#oetCLMFrmCstAddr').val('');
                    if (aReturn != "") {
                        // Check Data Customer Address
                        let aDataCstAddr = aReturn.aDataCstAddr;
                        if (aDataCstAddr.rtCode == 1) {
                            // Check Type Vession Address
                            let nAddrVer = aDataCstAddr.raItems.FTAddVersion;
                            $("#oetJPreFrmCstTelFax").val(aDataCstAddr.raItems.FTAddFax);

                            if (nAddrVer == 1) {
                                let tFTAddV1No       = aDataCstAddr.raItems.FTAddV1No;
                                let tFTAddV1Soi      = aDataCstAddr.raItems.FTAddV1Soi;
                                let tFTAddV1Road     = aDataCstAddr.raItems.FTAddV1Road;
                                let tFTSudName       = aDataCstAddr.raItems.FTAddV1SubDistName;
                                let tFTDstName       = aDataCstAddr.raItems.FTAddV1DstName;
                                let tFTPvnName       = aDataCstAddr.raItems.FTAddV1PvnName;
                                let tFTAddV1PostCode = aDataCstAddr.raItems.FTAddV1PostCode;
                                $('#oetCLMFrmCstAddr').val(tFTAddV1No + ' ' + tFTAddV1Soi + ' ' + tFTAddV1Road + ' ' + tFTSudName + ' ' + tFTDstName + ' ' + tFTPvnName + ' ' + tFTAddV1PostCode );
                            } else if (nAddrVer == 2) {
                                // ทีอยู่เวอร์ชั่น 2
                                let tAddV2Desc1 = aDataCstAddr.raItems.FTAddV2Desc1;
                                let tAddV2Desc2 = aDataCstAddr.raItems.FTAddV2Desc2;
                                $('#oetCLMFrmCstAddr').val(tAddV2Desc1 + ' ' + tAddV2Desc2);
                            } else {
                                let tFTAddV1No       = aDataCstAddr.raItems.FTAddV1No;
                                let tFTAddV1Soi      = aDataCstAddr.raItems.FTAddV1Soi;
                                let tFTAddV1Road     = aDataCstAddr.raItems.FTAddV1Road;
                                let tFTSudName       = aDataCstAddr.raItems.FTAddV1SubDistName;
                                let tFTDstName       = aDataCstAddr.raItems.FTAddV1DstName;
                                let tFTPvnName       = aDataCstAddr.raItems.FTAddV1PvnName;
                                let tFTAddV1PostCode = aDataCstAddr.raItems.FTAddV1PostCode;
                                $('#oetCLMFrmCstAddr').val(tFTAddV1No + ' ' + tFTAddV1Soi + ' ' + tFTAddV1Road + ' ' + tFTSudName + ' ' + tFTDstName + ' ' + tFTPvnName + ' ' + tFTAddV1PostCode );
                            }
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

            $('#oetCLMFrmCstTel').val((aData[2] == '') ? '-' : aData[2]);
            $('#oetCLMFrmCstEmail').val((aData[3] == '') ? '-' : aData[3]);
            // $('#oetCLMFrmCstAddr').val((aData[4] == '') ? '-' : aData[4]);
        }
    }

    //เลือกรถ 
    var oCLMCarCst  = function(poDataFnc){
        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tNextFuncName       = poDataFnc.tNextFuncName;
        let aArgReturn          = poDataFnc.aArgReturn;
        let tParamsCstCode      = poDataFnc.tParamsCstCode;
        let oOptionReturn       = {
            Title   : ['document/jobrequest1/jobrequest1', 'tJR1CarCst'],
            Table   : {Master:'TSVMCar', PK:'FTCarCode'},
            Join    : {
                Table   : ['TCNMImgObj','TCNMCst_L'],
                On      : ["TCNMImgObj.FTImgRefID = TSVMCar.FTCarCode AND TCNMImgObj.FTImgTable = 'TSVMCar' ","TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = "+nLangEdits ]
            },
            Where   : {
                Condition : [tParamsCstCode]
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
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TSVMCar.FTCarOwner"],
                Text        : [tInputReturnName,"TSVMCar.FTCarRegNo"]
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            }
        };
        return oOptionReturn;
    };
    $('#oimCLMBrowseCar').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();

            if($('#oetCLMFrmCstCode').val() == ''){
				var tParamsCstCode = "";
			}else{
				var tParamsCstCode = "AND TSVMCar.FTCarOwner = '" + $('#oetCLMFrmCstCode').val() + "'";
			}

            window.oCLMCarCstOption = undefined;
            oCLMCarCstOption        = oCLMCarCst({
                'tReturnInputCode'  : 'oetCLMFrmCarCode',
                'tReturnInputName'  : 'oetCLMFrmCarName',
                'tNextFuncName'     : 'JSxWhenSeletedCstCar',
                'aArgReturn'        : ['FTCarCode','FTCarRegNo','FTImgObj'],
                'tParamsCstCode'    : tParamsCstCode
            });
            JCNxBrowseData('oCLMCarCstOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    function JSxWhenSeletedCstCar(poDataNextFunc){
        var aData;
        if (poDataNextFunc  != "NULL") {
            aData = JSON.parse(poDataNextFunc);
        }
    }

    //ยืนยันการเคลม (STEP1 - POINT4)
    $('.xCNClaimConfirm').unbind().click(function(){

        JCNxOpenLoading();

        if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
            var tCLMDocNo    = "DUMMY";
        }else{
            var tCLMDocNo    = $("#ohdCLMDocNo").val();
        }

        //ต้องหาว่ามีคลังเคลมรับ + คลังเคลมเปลี่ยน + สินค้าที่จะส่งมี SPL ครบไหม หรือยัง
        $.ajax({
            type    : "POST",
            url     : 'docClaimEventCheckWahAndSPL',
            data    : {
                'tBCHCode'              : $('#ohdCLMBchCode').val(),
                'ptCLMDocNo'            : tCLMDocNo,
            },
            cache   : false,
            timeout : 0,
            success : function(oResult){
                var aReturnData = JSON.parse(oResult);
                JCNxCloseLoading();
                if(aReturnData['nStaReturn'] == 800 && aReturnData['nTypeReturn'] == 1){
                    //fail
                    $('#odvCLMModalPleseDataInFill #ospCLMModalPleseDataInFill').text('ไม่พบคลังเคลมเปลี่ยน หรือคลังเคลมรับ กรุณาไปตั้งค่าที่หน้าจอคลังสินค้า');
                    $('#odvCLMModalPleseDataInFill').modal('show');  
                }else if(aReturnData['nStaReturn'] == 800 && aReturnData['nTypeReturn'] == 2){
                    //fail
                    $('#odvCLMModalPleseDataInFill #ospCLMModalPleseDataInFill').text('กรุณาระบุผู้จำหน่าย ให้ครบถ้วน ก่อนยืนยันการเคลม');
                    $('#odvCLMModalPleseDataInFill').modal('show');  
                }else if(aReturnData['nStaReturn'] == 800 && aReturnData['nTypeReturn'] == 5){
                    //fail
                    $('#odvCLMModalPleseDataInFill #ospCLMModalPleseDataInFill').text('สินค้าที่เปลี่ยน / เบิกไม่เพียงพอ กรุณาเปลี่ยนสินค้า หรือกดบันทึก และดำเนินการทำเอกสารรับเข้า');
                    $('#odvCLMModalPleseDataInFill').modal('show');  
                }else if(aReturnData['nStaReturn'] == 800 && aReturnData['nTypeReturn'] == 3){
                    //fail
                    $('#odvCLMModalPleseDataInFill #ospCLMModalPleseDataInFill').text('กรุณาระบุข้อมูลตั้งค่า');
                    $('#odvCLMModalPleseDataInFill').modal('show');  
                }else{
                    //pass
                    $('#ohdCLMStaSaveOrSaveClaim').val(2);

                    //กดบันทึกเอกสาร
                    if($("#ohdCLMRoute").val() == "docClaimEventAdd"){
                        $('#obtCLMSubmitFromDoc').trigger("click");
                    }else{
                        $('#obtCLMSubmitDocument').click();
                    }
                }
            },
            error   : function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });

    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสินค้า-รายการสินค้ารับเคลม ##############################
    $('#obtCLMPrintDocStep1').unbind().click(function(){
        let aInfor  = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tCLMBchCode); ?>'},
            {"DocCode"      : '<?=@$tCLMDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tCLMBchCode;?>'},
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMPdtClmPdtDetail?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });

    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสินค้า-ส่งรายการสินค้าเครมไปยังผู้จำหน่าย ##############################
    $('#obtCLMPrintDocStep2').unbind().click(function(){
        let aInfor  = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tCLMBchCode); ?>'},
            {"DocCode"      : '<?=@$tCLMDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tCLMBchCode;?>'},
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMPdtClmToVendor?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });

    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสืนค้า-รับสินค้ากลับจากผู้จำหน่าย ##############################
    $('#obtCLMPrintDocStep3').unbind().click(function(){
        let aInfor  = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tCLMBchCode); ?>'},
            {"DocCode"      : '<?=@$tCLMDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tCLMBchCode;?>'},
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMPdtClmFrmVendor?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });

    // ############################## พิมพ์ฟอร์มเอกสาร - ใบเคลมสินค้า-ใบส่งรถลูกค้า ##############################
    $('#obtCLMPrintDocStep4').unbind().click(function(){
        let aInfor  = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tCLMBchCode); ?>'},
            {"DocCode"      : '<?=@$tCLMDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tCLMBchCode;?>'},
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMPdtClmCstCarDelivery?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    });

</script>