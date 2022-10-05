<!-- <script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script> -->

<script>
    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit");?>';
    var dCurrentDate    = new Date();
    var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
    var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes() + " " + tAmOrPm;
    var oDate           = new Date();
    oDate.setSeconds(1800);
    var tTimeEnd        = oDate.toTimeString().substr(0, 9);

    var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
    if( tUsrLevel != "HQ" ){
        $('#oimIASBrowseAgn').attr("disabled", true);
    }

    // วันที่ประเมิน
    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard : true,
        autoclose: true
    });

    if($('#oetIASDocTime').val() == ''){
        $('#oetIASDocTime').val(tCurrentTime);
    }

    if($('#oetIASDocTimeBegin').val() == ''){
        $('#oetIASDocTimeBegin').val(tCurrentTime);
    }

    if($('#oetIASDocTimeEnd').val() == ''){
        $('#oetIASDocTimeEnd').val(tTimeEnd);
    }

    if($('#oetIASDocDate').val() == ''){
        $('#oetIASDocDate').datepicker("setDate",dCurrentDate);
    }

    if($('#oetIASDocDateBegin').val() == ''){
        $('#oetIASDocDateBegin').datepicker("setDate",dCurrentDate);
    }

    if($('#oetIASDocDateEnd').val() == ''){
        $('#oetIASDocDateEnd').datepicker("setDate",dCurrentDate);
    }

    $('#obtIASDocRefExtDate').unbind().click(function(){
        $('#oetIASDocRefExtDate').datepicker('show');
    });

    $('#obtIASDocDate').unbind().click(function(){
        $('#oetIASDocDate').datepicker('show');
    });

    $('#obtIASDocDateBegin').unbind().click(function(){
        $('#oetIASDocDateBegin').datepicker('show');
    });

    $('#obtIASDocDateEnd').unbind().click(function(){
        $('#oetIASDocDateEnd').datepicker('show');
    });

    $('#obtIASDocTime').unbind().click(function(){
        $('#oetIASDocTime').datetimepicker('show');
    });

    $('#obtIASDocTimeBegin').unbind().click(function(){
        $('#oetIASDocTimeBegin').datetimepicker('show');
    });

    $('#obtIASDocTimeEnd').unbind().click(function(){
        $('#oetIASDocTimeEnd').datetimepicker('show');
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    $('#ocbIASStaAutoGenCode').on('change', function (e) {
        if($('#ocbIASStaAutoGenCode').is(':checked')){
            $("#oetIASDocNo").val('');
            $("#oetIASDocNo").attr("readonly", true);
            $('#oetIASDocNo').closest(".form-group").css("cursor","not-allowed");
            $('#oetIASDocNo').css("pointer-events","none");
            $("#oetIASDocNo").attr("onfocus", "this.blur()");
            $('#ofmIASAddForm').removeClass('has-error');
            $('#ofmIASAddForm .form-group').closest('.form-group').removeClass("has-error");
            $('#ofmIASAddForm em').remove();
        }else{
            $('#oetIASDocNo').closest(".form-group").css("cursor","");
            $('#oetIASDocNo').css("pointer-events","");
            $('#oetIASDocNo').attr('readonly',false);
            $("#oetIASDocNo").removeAttr("onfocus");
        }
    });

    //Browser ทะเบียนรถ
    $('#oimInsBrowseCarRegNo').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oInsCarCstOption = undefined;
            oInsCarCstOption        = oInsCarCst({
                'tReturnInputCode'  : 'oetIASCarNo',
                'tReturnInputName'  : 'oetIASCarNoName',
                'tNextFuncName'     : 'JSxWhenSeletedCstCarIns',
                'aArgReturn'        : ['FTCarCode','FTCarRegNo'],
                'tParamsCstCode'    : $('#ohdIASCstCode').val()
            });
            JCNxBrowseData('oInsCarCstOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //Browser ทะเบียนรถ
    var oInsCarCst  = function(poDataFnc){
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
                Table   : ['TCNMCst_L'],
                On      : ["TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = "+nLangEdits ]
            },
            Where   : {
                Condition : [tWhereCondition]
            },
            GrideView:{
                ColumnPathLang      : 'document/jobrequest1/jobrequest1',
                ColumnKeyLang       : ['tJR1CarCstCode', 'tJR1CarCstName', 'tJR1OwnerCstName'],
                ColumnsSize         : ['15%', '15%', '60%'],
                WidthModal          : 50,
                DataColumns         : ['TSVMCar.FTCarCode', 'TSVMCar.FTCarRegNo', 'TCNMCst_L.FTCstName'],
                DataColumnsFormat   : ['','',''],
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

    // ฟังก์ชั่นทำงานหลังจากทำการเลือกข้อมูลทะเบียนรถ
    function JSxWhenSeletedCstCarIns(aReturn, paDataDoc){
        if(aReturn != '' || aReturn != 'NULL'){
            $.ajax({
                type    : "POST",
                url     : "docIASResultFindCar",
                data    : {"poItem" : aReturn},
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    let aReturn = JSON.parse(tResult);
                    //ล้างค่าเอกสารอ้างอิง
                    $("#oetIASDocRefCode").val('');
                    $("#ohdIASDocRefCode").val('');
                    $('#oetIASDateStaService').val('');

                    if(aReturn != ""){

                        let aResultItem = aReturn.aDataCarCst;

                        //รายละเอียดข้อมูลรถ
                        if(aResultItem.raItems !== null){
                            $('#ohdIASCstCode').val(aResultItem.raItems.FTCarOwnerCode);
                            $('#oetIASCstName').val(aResultItem.raItems.FTCarOwnerName);            //ชื่อลูกค้า + ชื่อผู้ครอบครอง
                            $("#oetIASCstTel").val(aResultItem.raItems.FTCstTel);                   //เบอร์โทร
                            $("#oetIASCstMail").val(aResultItem.raItems.FTCstEmail);                //อีเมล์
                            $("#oetIASProvinceName").val(aResultItem.raItems.FTCarRegPvnName);      //จังหวัด
                            $("#oetIASCarEngineCode").val(aResultItem.raItems.FTCarEngineNo);       //เลขเครื่องยนต์
                            $("#oetIASCarPowerCode").val(aResultItem.raItems.FTCarVIDRef);          //เลขตัวถัง
                            $("#oetIASCarType").val(aResultItem.raItems.FTCarTypeName);             //ประเภทลักษณะ
                            $("#oetIASCarOwnerType").val(aResultItem.raItems.FTCarCategoryName);    //ประเภทเจ้าของ
                            $("#oetIASCarBrand").val(aResultItem.raItems.FTCarBrandName);           //ยี่ห้อ
                            $("#oetIASCarModel").val(aResultItem.raItems.FTCarModelName);           //รุ่น
                            $("#oetIASCarColor").val(aResultItem.raItems.FTCarColorName);           //สี
                            $("#oetIASCarGear").val(aResultItem.raItems.FTCarGearName);             //เกียร์
                            $("#oetIASCarEngineOil").val(aResultItem.raItems.FTCarPowerTypeName);   //เครื่องยนต์
                            $("#oetIASCarCldVol").val(aResultItem.raItems.FTCarEngineSizeName);     //ขนาดเครื่องยนต์ (ซีซี.)
                            $("#oetIASCarMileAge").val(); //เลขไมล์
                            $("#oetIASCarNo").val(aResultItem.raItems.FTCarCode);
                            $("#oetIASCarNoName").val(aResultItem.raItems.FTCarRegNo);
                        }

                        if(typeof(paDataDoc) != 'undefined' && paDataDoc != "NULL"){
                            $('#ohdIASDocRefCode').val(paDataDoc[0].ptDocNo);
                            $('#oetIASDocRefCode').val(paDataDoc[0].ptDocNo);
                            $('#oetIASDateStaService').val(paDataDoc[0].pdDocRefDate).datepicker("refresh");
                            $('#oetIASCarMileAge').val(paDataDoc[0].ptCarMile);
                            $('#ohdIASBchCode').val(paDataDoc[0].ptBchCode);
                            $('#oetIASBchName').val(paDataDoc[0].ptBchName);
                            $('#oetIASAgnCode').val(paDataDoc[0].ptAgnCode);
                            $('#oetIASAgnName').val(paDataDoc[0].ptAgnName);
                        }

                        //รายละเอียดข้อมูลที่อยู่ของลูกค้า
                        if(aResultItem.raItems2 !== null){
                            //เอกสารนีไม่ได้โชว์ข้อมูลที่อยู่
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    // Browser ลูกค้า
    $('#oimBrowseIASCst').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIASCstBrowse = undefined;
            oIASCstBrowse        = oIASCstPriOption({
                'tReturnInputCode'   : 'ohdIASCstCode',
                'tReturnInputName'   : 'oetIASCstName',
                'tReturnInputTel'    : 'oetIASCstTel',
                'tReturnInputEmail'  : 'oetIASCstMail'
            });
            JCNxBrowseData('oIASCstBrowse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse ลูกค้า
    var oIASCstPriOption = function(oIASCstBrowse){
        let tIASCstCode    = oIASCstBrowse.tReturnInputCode;
        let tIASCstName    = oIASCstBrowse.tReturnInputName;
        let tIASCstTel     = oIASCstBrowse.tReturnInputTel;
        let tIASCstEmail   = oIASCstBrowse.tReturnInputEmail;

        var tSQLWhereBch = "AND ISNULL(TCNMCst.FTAgnCode, '') = '"+$("#oetIASAgnCode").val()+"' ";

        let oOptionReturnIASCst    = {
            Title: ['customer/customer/customer','tCSTTitle'],
            Table: {
                Master: 'TCNMCst',
                PK: 'FTCstCode'
            },
            Join :{
                Table:	['TCNMCst_L'],
                On:['TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID ='+nLangEdits,]
            },
            Where : {
                Condition : [tSQLWhereBch]
            },
            GrideView: {
                ColumnPathLang: 'customer/customer/customer',
                ColumnKeyLang: ['tCSTCode', 'tCSTName'],
                ColumnsSize: ['15%', '75%'],
                DataColumns: ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName', 'TCNMCst.FTCstTel', 'TCNMCst.FTCstEmail'],
                DataColumnsFormat: ['', ''],
                DisabledColumns: [2,3],
                Perpage: 10,
                OrderBy: ['TCNMCst.FDCreateOn DESC'],
            },
            CallBack: {
                StaSingItem: '1',
                ReturnType: 'S',
                Value: ["ohdIASCstCode", "TCNMCst.FTCstCode"],
                Text: ["oetIASCstName", "TCNMCst_L.FTCstName"],
            },
            //DebugSQL: true,
            NextFunc:{
                FuncName:'JSxNextFuncIASCst',
                ArgReturn:['FTCstTel', 'FTCstEmail']
            },
        };
        return oOptionReturnIASCst;
    };

    function JSxNextFuncIASCst(paData) {
        $("#oetIASCstTel").val("");
        $("#oetIASCstMail").val("");
        var tIASCstTel = ''
        var tIASCstEmail = ''
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aIASCstData = JSON.parse(paData);
            tIASCstTel = aIASCstData[0];
            tIASCstEmail = aIASCstData[1];
        }
        $("#oetIASCstTel").val(tIASCstTel);
        $("#oetIASCstMail").val(tIASCstEmail);

        $(".xCNClaerValWhenCstChange").val("");

    }

    //Browse ใบอ้างอิงสั่งงาน
    $('#oimBrowseDocRef').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIASDocRefBrowse = undefined;
            oIASDocRefBrowse        = oIASDocRefOption({
                'tReturnInputCode'   : 'ohdIASCstCode',
                'tReturnInputName'   : 'oetIASCstName',
            });
            JCNxBrowseData('oIASDocRefBrowse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oIASDocRefOption = function(oIASDocRefBrowse){
        let tIASDocCode     = oIASDocRefBrowse.tReturnInputCode;
        let tIASDocName     = oIASDocRefBrowse.tReturnInputName;
        var tWhereStaAll    = "AND TSVTJob2OrdHD.FTXshStaDoc = '1' AND (TSVTJob2OrdHD.FTXshStaClosed != '1' OR ISNULL(TSVTJob2OrdHD.FTXshStaClosed,'') = '') ";
        var tWhereAgn       = "";
        var tWhereBch       = "";
        var tWhereCst       = "";

        if ($("#oetIASAgnCode").val() != '') {
            var tWhereAgn = "AND TSVTJob2OrdHD.FTAgnCode = '"+$("#oetIASAgnCode").val()+"'";
        }

        if ($("#ohdIASBchCode").val() != '') {
            var tWhereBch = "AND TSVTJob2OrdHD.FTBchCode = '"+$("#ohdIASBchCode").val()+"'";
        }

        if ($("#ohdIASCstCode").val() != '') {
            var tWhereCst = "AND TSVTJob2OrdHD.FTCstCode = '"+$("#ohdIASCstCode").val()+"'";
        }

        var tWhereDocRef = "AND ISNULL(JOB4.FTXshRefDocNo, '') = ''"

        let oOptionReturnIASRefDoc    = {
            Title: ['document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyDocRef'],
            Table: {
                Master  : 'TSVTJob2OrdHD',
                PK      : 'FTXshDocNo'
            },
            Join :{
                Table:	[
                            'TCNMCst_L',
                            'TCNMAgency_L',
                            'TSVTJob2OrdHDCst J2HDCst',
                            'TSVMCar',
                            'TSVMCarInfo_L T1',
                            'TSVMCarInfo_L T2',
                            'TSVMCarInfo_L T3',
                            'TSVMCarInfo_L T4',
                            'TSVMCarInfo_L T5',
                            'TSVMCarInfo_L T6',
                            'TSVMCarInfo_L T7',
                            'TSVMCarInfo_L T8',
                            'TCNMBranch_L',
                            'TSVTJob4ApvHDDocRef JOB4',
                            'TSVTJob1ReqHDDocRef J1REQ',
                            'TSVTJob1ReqHD J1HD',
                            'TCNMProvince_L PVNL',
                            'TSVMPos_L',
                            'TCNMCst'
                        ],
                On:[
                        'TSVTJob2OrdHD.FTCstCode = TCNMCst_L.FTCstCode AND TCNMCst_L.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTAgnCode = TCNMAgency_L.FTAgnCode AND TCNMAgency_L.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTXshDocNo = J2HDCst.FTXshDocNo',
                        'J2HDCst.FTCarCode = TSVMCar.FTCarCode',
                        'TSVMCar.FTCarType   = T1.FTCaiCode AND T1.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarBrand  = T2.FTCaiCode AND T2.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarModel  = T3.FTCaiCode AND T3.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarColor  = T4.FTCaiCode AND T4.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarGear  = T5.FTCaiCode AND T5.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarPowerType  = T6.FTCaiCode AND T6.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarEngineSize  = T7.FTCaiCode AND T7.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarCategory   = T8.FTCaiCode AND T8.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID ='+nLangEdits,
                        'JOB4.FTXshRefDocNo = TSVTJob2OrdHD.FTXshDocNo AND JOB4.FTXshRefType = 1',
                        'J1REQ.FTXshRefDocNo =  TSVTJob2OrdHD.FTXshDocNo  AND TSVTJob2OrdHD.FTAgnCode = J1REQ.FTAgnCode AND TSVTJob2OrdHD.FTBchCode = J1REQ.FTBchCode AND J1REQ.FTXshRefType = 2',
                        'J1REQ.FTXshDocNo = J1HD.FTXshDocNo AND J1REQ.FTAgnCode  = J1HD.FTAgnCode AND J1REQ.FTBchCode = J1HD.FTBchCode',
                        'PVNL.FTPvnCode = TSVMCar.FTCarRegProvince AND PVNL.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTXshToPos = TSVMPos_L.FTSpsCode AND TSVMPos_L.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTCstCode = TCNMCst.FTCstCode'
                   ]
            },
            Where: {
                Condition: [tWhereStaAll,tWhereAgn,tWhereBch,tWhereCst,tWhereDocRef]
            },
            GrideView: {
                ColumnPathLang: 'document/satisfactionsurvey/satisfactionsurvey',
                ColumnKeyLang: ['tSatSurveyBch', 'tSatSurveyDocNo', 'tSatSurveyDocDate', 'tSatSurveyCst'],
                ColumnsSize: ['20%', '20%' ,'20%', '20%'],
                DataColumns: [
                                'TCNMBranch_L.FTBchName',
                                'TSVTJob2OrdHD.FTXshDocNo',
                                'TSVTJob2OrdHD.FDXshDocDate',
                                'TCNMCst_L.FTCstName',
                                'TSVMCar.FTCarRegNo',
                                'TSVMCar.FTCarEngineNo',
                                'TSVMCar.FTCarVIDRef',
                                'T1.FTCaiName as FTCarType',
                                'T2.FTCaiName as FTCarBrand',
                                'T3.FTCaiName as FTCarModel',
                                'T4.FTCaiName as FTCarColor',
                                'T5.FTCaiName as FTCarGear',
                                'T6.FTCaiName as FTCarPowerType',
                                'T7.FTCaiName as FTCarEngineSize',
                                'T8.FTCaiName as FTCarCategory',
                                'J1HD.FCXshCarMileage',
                                'PVNL.FTPvnName',
                                'TCNMAgency_L.FTAgnName',
                                'TSVMPos_L.FTSpsName',
                                'TCNMCst.FTCstTel',
                                'TCNMCst.FTCstEmail',
                                'TSVMCar.FTCarCode'
                            ],
                DataColumnsFormat: ['','','',''],
                DisabledColumns: [4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21],
                Perpage: 10,
                OrderBy: ['TSVTJob2OrdHD.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["ohdIASDocRefCode", "TSVTJob2OrdHD.FTXshDocNo"],
                Text: ["oetIASDocRefCode", "TSVTJob2OrdHD.FTXshDocNo"],
            },
            NextFunc:{
                FuncName:'JSxNextFuncIASDocRef',
                ArgReturn:[
                            'FDXshDocDate',
                            'FTCarRegNo',
                            'FTCarEngineNo',
                            'FTCarVIDRef',
                            'FTCarType',
                            'FTCarBrand',
                            'FTCarModel',
                            'FTCarColor',
                            'FTCarGear',
                            'FTCarPowerType',
                            'FTCarEngineSize',
                            'FCXshCarMileage',
                            'FTCarCategory',
                            'FTPvnName',
                            'FTAgnName',
                            'FTBchName',
                            'FTCstName',
                            'FTCstTel',
                            'FTCstEmail',
                            'FTSpsName',
                            'FTCarCode'
                        ]
            }
        };
        return oOptionReturnIASRefDoc;
    };

    function JSxNextFuncIASDocRef(paData) {
        $("#oetIASDateStaService").val("");
        $("#oetIASCarNo").val("");
        $("#oetIASCarEngineCode").val("");
        $("#oetIASCarPowerCode").val("");
        $("#oetIASCarType").val("");
        $("#oetIASCarOwnerType").val("");
        $("#oetIASCarBrand").val("");
        $("#oetIASCarModel").val("");
        $("#oetIASCarColor").val("");
        $("#oetIASCarGear").val("");
        $("#oetIASCarEngineOil").val("");
        $("#oetIASCarCldVol").val("");
        $("#oetIASCarMileAge").val("");
        $("#oetIASProvinceName").val("");
        $("#oetIASAgnName").val("");
        $("#oetIASBchName").val("");
        $("#oetIASCstName").val("");
        $("#oetIASCstTel").val("");
        $("#oetIASCstMail").val("");
        $("#oetIASServiceToPos").val("");

        var tIASDateStaService   = '';
        var tIASRegCarNo         = '';
        var tIASCarEngineCode    = '';
        var tIASCarPowerCode     = '';
        var tIASCarType          = '';
        var tIASCarOwnerType     = '';
        var tIASCarBrand         = '';
        var tIASCarModel         = '';
        var tIASCarColor         = '';
        var tIASCarGear          = '';
        var tIASCarEngineOil     = '';
        var tIASCarCldVol        = '';
        var tIASCarMileAge       = '';
        var tIASCarProvince      = '';
        var tIASAgnName          = '';
        var tIASBchName          = '';
        var tIASCstName          = '';
        var tIASCstTel           = '';
        var tIASCstMail          = '';
        var tIASServiceToPos     = '';
        var tIASCarCode          = '';

        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aIASCstData = JSON.parse(paData);
            // console.log(aIASCstData);
            tIASDateStaService   = aIASCstData[0];
            tIASRegCarNo         = aIASCstData[1];
            tIASCarEngineCode    = aIASCstData[2];
            tIASCarPowerCode     = aIASCstData[3];
            tIASCarType          = aIASCstData[12];
            tIASCarOwnerType     = aIASCstData[4];
            tIASCarBrand         = aIASCstData[5];
            tIASCarModel         = aIASCstData[6];
            tIASCarColor         = aIASCstData[7];
            tIASCarGear          = aIASCstData[8];
            tIASCarEngineOil     = aIASCstData[9];
            tIASCarCldVol        = aIASCstData[10];
            tIASCarMileAge       = aIASCstData[11];
            tIASCarProvince      = aIASCstData[13];
            tIASAgnName          = aIASCstData[14];
            tIASBchName          = aIASCstData[15];
            tIASCstName          = aIASCstData[16];
            tIASCstTel           = aIASCstData[17];
            tIASCstMail          = aIASCstData[18];
            tIASServiceToPos     = aIASCstData[19];
            tIASCarCode          = aIASCstData[20];
        }
        $("#oetIASDateStaService").val(tIASDateStaService.substring(0, 10));
        $("#oetIASCarNo").val(tIASCarCode);
        $("#oetIASCarNoName").val(tIASRegCarNo);
        $("#oetIASCarEngineCode").val(tIASCarEngineCode);
        $("#oetIASCarPowerCode").val(tIASCarPowerCode);
        $("#oetIASCarType").val(tIASCarType);
        $("#oetIASCarOwnerType").val(tIASCarOwnerType);
        $("#oetIASCarBrand").val(tIASCarBrand);
        $("#oetIASCarModel").val(tIASCarModel);
        $("#oetIASCarColor").val(tIASCarColor);
        $("#oetIASCarGear").val(tIASCarGear);
        $("#oetIASCarEngineOil").val(tIASCarEngineOil);
        $("#oetIASCarCldVol").val(tIASCarCldVol);
        $("#oetIASCarMileAge").val(tIASCarMileAge);
        $("#oetIASProvinceName").val(tIASCarProvince);
        $("#oetIASAgnName").val(tIASAgnName);
        $("#oetIASBchName").val(tIASBchName);
        $("#oetIASCstName").val(tIASCstName);
        $("#oetIASCstTel").val(tIASCstTel);
        $("#oetIASCstMail").val(tIASCstMail);
        $("#oetIASServiceToPos").val(tIASServiceToPos);
    }

    //Browse ชื่อพนักงาน
    $('#oimBrowseUsrBch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIASUsrUse = undefined;
            oIASUsrUse        = oIASUsrUseOption({
                'tReturnInputCode'   : 'ohdIASCstCode',
                'tReturnInputName'   : 'oetIASCstName',
            });
            JCNxBrowseData('oIASUsrUse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oIASUsrUseOption = function(oIASUsrUse){
        let tIASUsrUseCode    = oIASUsrUse.tReturnInputCode;
        let tIASUsrUseName    = oIASUsrUse.tReturnInputName;
        let oOptionReturnIASUsrUse    = {
            Title: ['document/satisfactionsurvey/satisfactionsurvey', 'tIASUsr'],
            Table: {
                Master: 'TCNMUser',
                PK: 'FTUsrCode'
            },
            Join :{
                Table:	['TCNMUser_L'],
                On:['TCNMUser_L.FTUsrCode = TCNMUser.FTUsrCode AND TCNMUser_L.FNLngID ='+nLangEdits,]
            },
            GrideView: {
                ColumnPathLang: 'company/warehouse/warehouse',
                ColumnKeyLang: ['tBrowseSHPCode', 'tBrowseSHPName'],
                ColumnsSize: ['15%', '75%'],
                DataColumns: ['TCNMUser.FTUsrCode', 'TCNMUser_L.FTUsrName'],
                DataColumnsFormat: ['',''],
                Perpage: 10,
                OrderBy: ['TCNMUser.FTUsrCode ASC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["ohdIASTaskRefUsrCode", "TCNMUser.FTUsrCode"],
                Text: ["oetIASTaskRefUsrName", "TCNMUser_L.FTUsrName"],
            },
        };
        return oOptionReturnIASUsrUse;
    };

    // browse สาขา
    $('#obtIASBrowseBCH').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();

        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIASBrowseBranchOption  = undefined;
            oIASBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'ohdIASBchCode',
                'tReturnInputName'  : 'oetIASBchName',
                'tAgnCode'          : $('#oetIASAgnCode').val()
            });
            JCNxBrowseData('oIASBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ตัวแปร Option Browse Modal สาขา
    var oBranchOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tAgnCode            = poDataFnc.tAgnCode;

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if(tUsrLevel != "HQ"){
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+")";
        }else{
            tSQLWhereBch = "";
        }

        if(tAgnCode != ""){
            tSQLWhereAgn = "AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
        }else{
            tSQLWhereAgn = "";
        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn       = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master  : 'TCNMBranch',
                PK      : 'FTBchCode'
            },
            Join: {
                Table   : ['TCNMBranch_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where : {
                Condition : [tSQLWhereBch,tSQLWhereAgn]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['', ''],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode DESC']
            },
            //DebugSQL : true,
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text        : [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
        };
        return oOptionReturn;
    }

    //BrowseAgn
    $('#oimIASBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oIASBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetIASAgnCode',
                'tReturnInputName': 'oetIASAgnName',
            });
            JCNxBrowseData('oIASBrowseAgencyOption');
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
            NextFunc:{
                    FuncName:'JSxNextFuncIASAgn'
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

    function JSxNextFuncIASAgn() {
        $("#ohdIASBchCode").val('');
        $("#oetIASBchName").val('');
        $("#ohdIASCstCode").val('');
        $("#oetIASCstName").val('');
        $("#oetIASCstTel").val('');
        $("#oetIASCstMail").val('');
        $("#ohdIASDocRefCode").val('');
        $("#oetIASDocRefCode").val('');
        $("#oetIASDateStaService").val('');
        $("#oetIASSrvCar").val('');
        $("#oetIASCarNo").val('');
    }

    //กดปุ่มบันทึก
    function JSxIASSetStatusClickSubmit(pnStatus) {
        $("#ohdIASCheckSubmitByButton").val(pnStatus);
    }

    // Event Click Appove Document
    $('#obtIASApproveDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxIASSetStatusClickSubmit(2);
            JSxIASApproveDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Cancel Document
    $('#obtIASCancelDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSnIASCancelDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Submit From Document
    $('#obtIASSubmitFromDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxIASSetStatusClickSubmit(1);
            $('#obtSubmitIAS').click();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // validate and insert
    function JSoAddEditIAS(ptRoute){
        var nStaSession = JCNxFuncChkSessionExpired();
        var nStaDoc = $('#ohdIASStaDoc').val();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1 && nStaDoc != 3){
            $('#ofmIASAddForm').validate({
                rules: {
                    oetIASDocNo : {
                        "required" : {
                            depends: function (oElement) {
                                if(ptRoute == "docIASisfactionEventAdd"){
                                    if($('#ocbIASStaAutoGenCode').is(':checked')){
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
                    // oetIASAgnName           : {"required" : true},
                    oetIASBchName           : {"required" : true},
                    oetIASCstName           : {"required" : true},
                    oetIASDocRefCode        : {"required" : true}
                },
                messages: {
                    oetIASDocNo             : {"required" : $('#oetIASDocNo').attr('data-validate-required')},
                    oetIASCstName           : {"required" : $('#oetIASCstName').attr('data-validate-required')},
                    oetIASBchName           : {"required" : $('#oetIASBchName').attr('data-validate-required')},
                    // oetIASAgnName           : {"required" : $('#oetIASAgnName').attr('data-validate-required')},
                    oetIASDocRefCode        : {"required" : $('#oetIASDocRefCode').attr('data-validate-required')}
                },
                errorElement: "em",
                errorPlacement: function (error, element) {
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
                highlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass("has-error").removeClass("has-success");
                },
                unhighlight: function(element, errorClass, validClass) {
                    var nStaCheckValid = $(element).parents('.form-group').find('.help-block').length
                    if (nStaCheckValid != 0) {
                        $(element).closest('.form-group').addClass("has-success").removeClass("has-error");
                    }
                },
                submitHandler: function (form){
                    if(!$('#ocbIASStaAutoGenCode').is(':checked')){
                        if ($("#ocbIASStaAutoGenCode").prop("checked")==undefined) {
                            JSxIASSubmitEventByButton(ptRoute);
                        }else {
                            JSxIASValidateDocCodeDublicate(ptRoute);
                        }
                    }else{
                        if($("#ohdIASCheckSubmitByButton").val() == 1){
                            JSxIASSubmitEventByButton(ptRoute);
                        }
                    }
                },
            });
        }else if(typeof(nStaSession) !== 'undefined' && nStaSession == 1 && nStaDoc == 3){
            if(!$('#ocbIASStaAutoGenCode').is(':checked')){
                JSxIASValidateDocCodeDublicate(ptRoute);
            }else{
                if($("#ohdIASCheckSubmitByButton").val() == 1){
                    JSxIASSubmitEventByButton(ptRoute);
                }
            }
        }else{
            JCNxShowMsgSessionExpired();
        }

    }

    // Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
    function JSxIASValidateDocCodeDublicate(ptRoute){
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName'    : 'TSVTJob4ApvHD',
                'tFieldName'    : 'FTXshDocNo',
                'tCode'         : $('#oetIASDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                if (aResultData["rtCode"]=="1") {
                  FSvCMNSetMsgErrorDialog(aResultData['rtDesc']);
                  JCNxCloseLoading();
                }else {
                  $("#ohdIASCheckDuplicateCode").val(aResultData["rtCode"]);

                  if($("#ohdIASCheckClearValidate").val() != 1) {
                      $('#ofmIASAddForm').validate().destroy();
                  }

                  $.validator.addMethod('dublicateCode', function(value,element){
                      if(ptRoute == "docIASisfactionEventAdd"){
                          if($('#ocbIASStaAutoGenCode').is(':checked')) {
                              return true;
                          }else{
                              if($("#ohdIASCheckDuplicateCode").val() == 1) {
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
                  $('#ofmDOFormAdd').validate({
                      focusInvalid: false,
                      onclick: false,
                      onfocusout: false,
                      onkeyup: false,
                      rules: {
                          oetIASDocNo : {"dublicateCode": {}}
                      },
                      messages: {
                          oetIASDocNo : {"dublicateCode"  : $('#oetIASDocNo').attr('data-validate-duplicate')}
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
                          if($("#ohdIASCheckSubmitByButton").val() == 1) {
                              JSxIASSubmitEventByButton(ptRoute);
                          }
                      }
                  })
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function: Validate Success And Send Ajax Add/Update Document
    function JSxIASSubmitEventByButton(ptRoute,ptType = ''){
        var nTrLength = $('#otbDataTable tbody tr').length;
        var aIASAns = [];
        var aIASQue = [];

        $("#ocbIASStaAutoGenCode" ).prop( "disabled", false );
        $("#ocbIASStaDocAct" ).prop( "disabled", false);
        $(".xWRadioRate" ).prop( "disabled", false);
        $(".xCNIASAns" ).prop( "disabled", false);

        $('.xCNIASAns:checked').each(function(){
            const aResult = {
                                tDocNo:$(this).attr('data-docno'),
                                tSgName:$(this).attr('data-sgname'),
                                nSeqDt:$(this).attr('data-seqdt'),
                                nSeqAs:$(this).attr('data-seqas'),
                                tQueName:$(this).attr('data-quename'),
                                tResName:$(this).attr('data-resname'),
                                tResVal:$(this).attr('data-resval'),
                                nQueType:$(this).attr('data-quetype')
                            };
            aIASAns.push(aResult);
        });

        $('.xCNIASAns[type=text]').each(function() {
            const aResult = {
                                tDocNo:$(this).attr('data-docno'),
                                tSgName:$(this).attr('data-sgname'),
                                nSeqDt:$(this).attr('data-seqdt'),
                                nSeqAs:$(this).attr('data-seqas'),
                                tQueName:$(this).attr('data-quename'),
                                tResName:$(this).val(),
                                tResVal:'',
                                nQueType:$(this).attr('data-quetype')
                            };
            if ($(this).val() != '') {
                aIASAns.push(aResult);
            }
        });

        $('textarea.xCNIASAns').each(function() {
            const aResult = {
                                tDocNo:$(this).attr('data-docno'),
                                tSgName:$(this).attr('data-sgname'),
                                nSeqDt:$(this).attr('data-seqdt'),
                                nSeqAs:$(this).attr('data-seqas'),
                                tQueName:$(this).attr('data-quename'),
                                tResName:$(this).val(),
                                tResVal:'',
                                nQueType:$(this).attr('data-quetype')
                            };

            if ($(this).val() != '') {
                aIASAns.push(aResult);
            }
        });

        $('#otbDataTable tbody tr').each(function(){
            $(this).find('.xWQuestion').each(function(){
                const aResult = {
                                tDocNo:$(this).attr('data-docno'),
                                nSeqDt:$(this).attr('data-seqdt'),
                                nQueType:$(this).attr('data-quetype')
                            };
                aIASQue.push(aResult);
            })
        })

        if(aIASAns.length < nTrLength){
            $('#odvIASModalvalidate').modal('show');
            return;
        }

        //ปุ่มบันทึกทำงานได้แค่ครั้งเดียว
        $('#obtIASSubmitFromDoc').attr('disabled',true);

        $.ajax({
            type: "POST",
            url: ptRoute,
            data: $('#ofmIASAddForm').serialize() + '&aIASAns=' + JSON.stringify(aIASAns) + '&aIASQue=' + JSON.stringify(aIASQue),
            success: function(oResult){
                var aReturn = JSON.parse(oResult);

                if(aReturn['nStaEvent'] == 1){
                    var oDOCallDataTableFile = {
                        ptElementID : 'odvIASShowDataTable',
                        ptBchCode   : aReturn['tBchCode'],
                        ptDocNo     : aReturn['tDocNo'],
                        ptDocKey    :'TSVTJob4ApvHD'
                    }
                    JCNxUPFInsertDataFile(oDOCallDataTableFile);

                    //ปลดปุ่ม
                    $('#obtIASSubmitFromDoc').attr('disabled',false);

                    if(ptType == 'approve'){
                        var tAgnCode = $('#oetIASAgnCode').val();
                        var tBchCode = $('#ohdIASBchCode').val();
                        var tDocNo = $('#oetIASDocNo').val();
                         $.ajax({
                            type: "POST",
                            url: "docIASApproveDocument",
                            data: {
                                'tAgnCode'  : tAgnCode,
                                'tBchCode'  : tBchCode,
                                'tDocNo'    : tDocNo ,
                                'tDocJOB2'  : $('#oetIASDocRefCode').val()
                            },
                            cache: false,
                            timeout: 0,
                            success: function(tResult) {
                                var aReturnData = JSON.parse(tResult);
                                if (aReturnData['nStaEvent'] == '1') {
                                    $("#odvIASModalAppoveDoc").modal("hide");
                                    JCNxCloseLoading();
                                    JSvIASCallPageEdit(tAgnCode,tBchCode,tDocNo);
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
                    }else{
                        switch(aReturn['nStaCallBack']) {
                            case '1':
                                JSvIASCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo']);
                                break;
                            case '2':
                                JSvIASCallPageAdd();
                                break;
                            case '3':
                                JSvIASCallPageList()
                                break;
                            default:
                                JSvIASCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo']);
                        }
                    }
                }else{
                    FCMNSetMsgErrorDialog(aReturn['tStaMessg']);
                    JCNxCloseLoading();
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //พิมพ์เอกสาร
    function JSxIASPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tIASBchCode); ?>'},
            {"DocCode"      : '<?=@$tIASDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tIASBchCode;?>'}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillIspAfRep?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }
</script>
