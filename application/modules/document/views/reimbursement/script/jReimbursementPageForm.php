<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

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
        $('#oimSALBrowseAgn').attr("disabled", true);
    }

    // วันที่ประเมิน
    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard : true,
        autoclose: true
    });

    if($('#oetSALDocTime').val() == ''){
        $('#oetSALDocTime').val(tCurrentTime);
    }

    if($('#oetSALDocTimeBegin').val() == ''){
        $('#oetSALDocTimeBegin').val(tCurrentTime);
    }

    if($('#oetSALDocTimeEnd').val() == ''){
        $('#oetSALDocTimeEnd').val(tTimeEnd);
    }

    if($('#oetSALDocDate').val() == ''){
        $('#oetSALDocDate').datepicker("setDate",dCurrentDate); 
    }

    if($('#oetSALDocDateBegin').val() == ''){
        $('#oetSALDocDateBegin').datepicker("setDate",dCurrentDate); 
    }

    if($('#oetSALDocDateEnd').val() == ''){
        $('#oetSALDocDateEnd').datepicker("setDate",dCurrentDate); 
    }

    $('#obtSALDocRefExtDate').unbind().click(function(){
        $('#oetSALDocRefExtDate').datepicker('show');
    });

    $('#obtSALDocDate').unbind().click(function(){
        $('#oetSALDocDate').datepicker('show');
    });
    
    $('#obtSALDocDateBegin').unbind().click(function(){
        $('#oetSALDocDateBegin').datepicker('show');
    });

    $('#obtSALDocDateEnd').unbind().click(function(){
        $('#oetSALDocDateEnd').datepicker('show');
    });

    $('#obtSALDocTime').unbind().click(function(){
        $('#oetSALDocTime').datetimepicker('show');
    });

    $('#obtSALDocTimeBegin').unbind().click(function(){
        $('#oetSALDocTimeBegin').datetimepicker('show');
    });

    $('#obtSALDocTimeEnd').unbind().click(function(){
        $('#oetSALDocTimeEnd').datetimepicker('show');
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    $('#ocbSALStaAutoGenCode').on('change', function (e) {
        if($('#ocbSALStaAutoGenCode').is(':checked')){
            $("#oetSALDocNo").val('');
            $("#oetSALDocNo").attr("readonly", true);
            $('#oetSALDocNo').closest(".form-group").css("cursor","not-allowed");
            $('#oetSALDocNo').css("pointer-events","none");
            $("#oetSALDocNo").attr("onfocus", "this.blur()");
            $('#ofmSALAddForm').removeClass('has-error');
            $('#ofmSALAddForm .form-group').closest('.form-group').removeClass("has-error");
            $('#ofmSALAddForm em').remove();
        }else{
            $('#oetSALDocNo').closest(".form-group").css("cursor","");
            $('#oetSALDocNo').css("pointer-events","");
            $('#oetSALDocNo').attr('readonly',false);
            $("#oetSALDocNo").removeAttr("onfocus");
        }
    });

    //end

    
    // Browser Cst
    $('#oimBrowseSALCst').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSALCstBrowse = undefined;
            oSALCstBrowse        = oSALCstPriOption({
                'tReturnInputCode'   : 'ohdSALCstCode',
                'tReturnInputName'   : 'oetSALCstName',
                'tReturnInputTel'    : 'oetSALCstTel',
                'tReturnInputEmail'  : 'oetSALCstMail'
            });
            JCNxBrowseData('oSALCstBrowse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse ลูกค้า
    var oSALCstPriOption = function(oSALCstBrowse){
        let tSALCstCode    = oSALCstBrowse.tReturnInputCode;
        let tSALCstName    = oSALCstBrowse.tReturnInputName;
        let tSALCstTel     = oSALCstBrowse.tReturnInputTel;
        let tSALCstEmail   = oSALCstBrowse.tReturnInputEmail;

        var tSQLWhereBch = "AND ISNULL(TCNMCst.FTAgnCode, '') = '"+$("#oetSALAgnCode").val()+"' ";

        let oOptionReturnSALCst    = {
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
                Value: ["ohdSALCstCode", "TCNMCst.FTCstCode"],
                Text: ["oetSALCstName", "TCNMCst_L.FTCstName"],
            },
            //DebugSQL: true,
            NextFunc:{
                FuncName:'JSxNextFuncSALCst',
                ArgReturn:['FTCstTel', 'FTCstEmail']
            },
        };
        return oOptionReturnSALCst;
    };

    function JSxNextFuncSALCst(paData) {
        $("#oetSALCstTel").val("");
        $("#oetSALCstMail").val("");
        var tSALCstTel = ''
        var tSALCstEmail = ''
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aSALCstData = JSON.parse(paData);
            tSALCstTel = aSALCstData[0];
            tSALCstEmail = aSALCstData[1];
        }
        $("#oetSALCstTel").val(tSALCstTel);
        $("#oetSALCstMail").val(tSALCstEmail);

        $(".xCNClaerValWhenCstChange").val("");

    }
    // end ลูกค้า

    //Browse ใบอ้างอิงสั่งงาน
    $('#oimBrowseDocRef').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSALDocRefBrowse = undefined;
            oSALDocRefBrowse        = oSALDocRefOption({
                'tReturnInputCode'   : 'ohdSALCstCode',
                'tReturnInputName'   : 'oetSALCstName',
            });
            JCNxBrowseData('oSALDocRefBrowse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oSALDocRefOption = function(oSALDocRefBrowse){
        let tSALDocCode    = oSALDocRefBrowse.tReturnInputCode;
        let tSALDocName    = oSALDocRefBrowse.tReturnInputName;
        //var tWhereStaAll = "";
        var tWhereStaAll = "AND TSVTJob2OrdHD.FTXshStaDoc = '1' AND TSVTJob2OrdHD.FTXshStaApv = '1' AND TSVTJob2OrdHD.FTXshStaClosed = '1' AND TSVTJob2OrdHD.FNXshStaDocAct = '1'";//AND TSVTJob2OrdHD.FTCstCode = '"+$("#ohdSALCstCode").val()+"'"
        var tWhereAgn = "";
        var tWhereBch = "";
        var tWhereCst = "";
        if ($("#oetSALAgnCode").val() != '') {
            var tWhereAgn = "AND TSVTJob2OrdHD.FTAgnCode = '"+$("#oetSALAgnCode").val()+"'";
        }
        
        if ($("#ohdSALBchCode").val() != '') {
            var tWhereBch = "AND TSVTJob2OrdHD.FTBchCode = '"+$("#ohdSALBchCode").val()+"'";
        }
        
        if ($("#ohdSALSurveyCstCode").val()) {
            var tWhereCst = "AND TSVTJob2OrdHD.FTCstCode = '"+$("#ohdSALSurveyCstCode").val()+"'";
        }
        
        var tWhereDocRef = "AND ISNULL(SAL4.FTXshRefDocNo, '') = ''"
        
        let oOptionReturnSALRefDoc    = {
            Title: ['document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyDocRef'],
            Table: {
                Master: 'TSVTJob2OrdHD',
                PK: 'FTXshDocNo'
            },
            Join :{
                Table:	[   
                            'TCNMCst_L', 
                            'TCNMAgency_L', 
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
                            'TSVTJob4ApvHDDocRef SAL4',
                            'TSVTJob1ReqHDDocRef J1REQ',
                            'TSVTJob1ReqHD J1HD',
                            'TCNMProvince_L PVNL',
                            'TSVMPos_L',
                            'TCNMCst'
                        ],
                On:[
                        'TSVTJob2OrdHD.FTCstCode = TCNMCst_L.FTCstCode AND TCNMCst_L.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTAgnCode = TCNMAgency_L.FTAgnCode AND TCNMAgency_L.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTCarCode = TSVMCar.FTCarCode',
                        'TSVMCar.FTCarType   = T1.FTCaiCode AND T1.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarBrand  = T2.FTCaiCode AND T2.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarModel  = T3.FTCaiCode AND T3.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarColor  = T4.FTCaiCode AND T4.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarGear  = T5.FTCaiCode AND T5.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarPowerType  = T6.FTCaiCode AND T6.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarEngineSize  = T7.FTCaiCode AND T7.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarCategory   = T8.FTCaiCode AND T8.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID ='+nLangEdits,
                        'SAL4.FTXshRefDocNo = TSVTJob2OrdHD.FTXshDocNo AND SAL4.FTXshRefType = 1',
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
                                'TCNMCst.FTCstEmail'
                            ],
                DataColumnsFormat: ['','','',''],
                DisabledColumns: [4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],
                Perpage: 10,
                OrderBy: ['TSVTJob2OrdHD.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["ohdSALDocRefCode", "TSVTJob2OrdHD.FTXshDocNo"],
                Text: ["oetSALDocRefCode", "TSVTJob2OrdHD.FTXshDocNo"],
            },
            //DebugSQL: true,
            NextFunc:{
                FuncName:'JSxNextFuncSALDocRef',
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
                            'FTSpsName'
                        ]
            },
        };
        return oOptionReturnSALRefDoc;
    };

    function JSxNextFuncSALDocRef(paData) {
        $("#oetSALDateStaService").val("");
        $("#oetSALCarNo").val("");
        $("#oetSALCarEngineCode").val("");
        $("#oetSALCarPowerCode").val("");
        $("#oetSALCarType").val("");
        $("#oetSALCarOwnerType").val("");
        $("#oetSALCarBrand").val("");
        $("#oetSALCarModel").val("");
        $("#oetSALCarColor").val("");
        $("#oetSALCarGear").val("");
        $("#oetSALCarEngineOil").val("");
        $("#oetSALCarCldVol").val("");
        $("#oetSALCarMileAge").val("");
        $("#oetSALProvinceName").val("");
        $("#oetSALAgnName").val("");
        $("#oetSALBchName").val("");
        $("#oetSALCstName").val("");
        $("#oetSALCstTel").val("");
        $("#oetSALCstMail").val("");
        $("#oetSALServiceToPos").val("");

        var tSALDateStaService   = '';
        var tSALRegCarNo         = '';
        var tSALCarEngineCode    = '';
        var tSALCarPowerCode     = '';
        var tSALCarType          = '';
        var tSALCarOwnerType     = '';
        var tSALCarBrand         = '';
        var tSALCarModel         = '';
        var tSALCarColor         = '';
        var tSALCarGear          = '';
        var tSALCarEngineOil     = '';
        var tSALCarCldVol        = '';
        var tSALCarMileAge       = '';
        var tSALCarProvince      = '';
        var tSALAgnName          = '';
        var tSALBchName          = '';
        var tSALCstName          = '';
        var tSALCstTel           = '';
        var tSALCstMail          = '';
        var tSALServiceToPos     = '';
        
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aSALCstData = JSON.parse(paData);
            console.log(aSALCstData);
            tSALDateStaService   = aSALCstData[0];
            tSALRegCarNo         = aSALCstData[1];
            tSALCarEngineCode    = aSALCstData[2];
            tSALCarPowerCode     = aSALCstData[3];
            tSALCarType          = aSALCstData[12];
            tSALCarOwnerType     = aSALCstData[4];
            tSALCarBrand         = aSALCstData[5];
            tSALCarModel         = aSALCstData[6];
            tSALCarColor         = aSALCstData[7];
            tSALCarGear          = aSALCstData[8];
            tSALCarEngineOil     = aSALCstData[9];
            tSALCarCldVol        = aSALCstData[10];
            tSALCarMileAge       = aSALCstData[11];
            tSALCarProvince      = aSALCstData[13];
            tSALAgnName          = aSALCstData[14];
            tSALBchName          = aSALCstData[15];
            tSALCstName          = aSALCstData[16];
            tSALCstTel           = aSALCstData[17];
            tSALCstMail          = aSALCstData[18];
            tSALServiceToPos     = aSALCstData[19]; 
        }
        $("#oetSALDateStaService").val(tSALDateStaService);
        $("#oetSALCarNo").val(tSALRegCarNo);
        $("#oetSALCarEngineCode").val(tSALCarEngineCode);
        $("#oetSALCarPowerCode").val(tSALCarPowerCode);
        $("#oetSALCarType").val(tSALCarType);
        $("#oetSALCarOwnerType").val(tSALCarOwnerType);
        $("#oetSALCarBrand").val(tSALCarBrand);
        $("#oetSALCarModel").val(tSALCarModel);
        $("#oetSALCarColor").val(tSALCarColor);
        $("#oetSALCarGear").val(tSALCarGear);
        $("#oetSALCarEngineOil").val(tSALCarEngineOil);
        $("#oetSALCarCldVol").val(tSALCarCldVol);
        $("#oetSALCarMileAge").val(tSALCarMileAge);
        $("#oetSALProvinceName").val(tSALCarProvince);
        $("#oetSALAgnName").val(tSALAgnName);
        $("#oetSALBchName").val(tSALBchName);
        $("#oetSALCstName").val(tSALCstName);
        $("#oetSALCstTel").val(tSALCstTel);
        $("#oetSALCstMail").val(tSALCstMail);
        $("#oetSALServiceToPos").val(tSALServiceToPos);
    }
    //end

    //Browse ชื่อพนักงาน
    $('#oimBrowseUsrBch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSALUsrUse = undefined;
            oSALUsrUse        = oSALUsrUseOption({
                'tReturnInputCode'   : 'ohdSALCstCode',
                'tReturnInputName'   : 'oetSALCstName',
            });
            JCNxBrowseData('oSALUsrUse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oSALUsrUseOption = function(oSALUsrUse){
        let tSALUsrUseCode    = oSALUsrUse.tReturnInputCode;
        let tSALUsrUseName    = oSALUsrUse.tReturnInputName;
        let oOptionReturnSALUsrUse    = {
            Title: ['document/satisfactionsurvey/satisfactionsurvey', 'tSALUsr'],
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
                Value: ["ohdSALTaskRefUsrCode", "TCNMUser.FTUsrCode"],
                Text: ["oetSALTaskRefUsrName", "TCNMUser_L.FTUsrName"],
            },
        };
        return oOptionReturnSALUsrUse;
    };
    //end

    // browse สาขา
    $('#obtSALBrowseBCH').unbind().click(function(){ 
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetSALAgnCode').val();
        if (tAgnCode == '') {
            $('#oimSALBrowseAgn').click();
        }else{
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oSALBrowseBranchOption  = undefined;
                oSALBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'ohdSALBchCode',
                    'tReturnInputName'  : 'oetSALBchName',
                    'tAgnCode'          : tAgnCode
                });
                JCNxBrowseData('oSALBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }

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
                OrderBy             : ['TCNMBranch.FTBchCode'],
                SourceOrder         : "ASC"
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
    $('#oimSALBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oSALBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetSALAgnCode',
                'tReturnInputName': 'oetSALAgnName',
            });
            JCNxBrowseData('oSALBrowseAgencyOption');
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
                    FuncName:'JSxNextFuncSALAgn'
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

    function JSxNextFuncSALAgn() {
        $("#ohdSALBchCode").val('');
        $("#oetSALBchName").val('');
        $("#ohdSALCstCode").val('');
        $("#oetSALCstName").val('');
        $("#oetSALCstTel").val('');
        $("#oetSALCstMail").val('');
        $("#ohdSALDocRefCode").val('');
        $("#oetSALDocRefCode").val('');
        $("#oetSALDateStaService").val('');
        $("#oetSALSrvCar").val('');
        $("#oetSALCarNo").val('');
    }

    //end

    function JSxSALSetStatusClickSubmit(pnStatus) {
        $("#ohdSALCheckSubmitByButton").val(pnStatus);
    }
    // Event Click Appove Document
    $('#obtSALApproveDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxSALSetStatusClickSubmit(2);
            JSxSALApproveDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

            // Event Click Cancel Document
    $('#obtSALCancelDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSnSALCancelDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Submit From Document
    $('#obtSALSubmitFromDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxSALSetStatusClickSubmit(1);
            $('#obtSubmitSAL').click();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    

    // validate and insert
    function JSoAddEditSAL(ptRoute){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            if($("#ohdSALCheckSubmitByButton").val() == 1) {
                JSxSALSubmitEventByButton(ptRoute);
            }else{
                JCNxShowMsgSessionExpired();
            }
        }
    }
    //end

    // Function: Validate Success And Send Ajax Add/Update Document
    function JSxSALSubmitEventByButton(ptRoute){
        var tRmk = $('#otaSALFrmInfoOthRmk').val();
        var tAgnCode = $('#oetSALAgnCode').val();
        var tBchCode = $('#ohdSALBchCode').val();
        var tDocNo = $('#oetSALDocNo').val();
        var tCstCode = $('#ohdSALCstCode').val();
        $.ajax({
            type: "POST",
            url: ptRoute,
            data: {
                tRmk: tRmk,
                tAgnCode: tAgnCode,
                tBchCode: tBchCode,
                tDocNo: tDocNo
            },
            success: function(oResult){
                var aReturn = JSON.parse(oResult);

                if(aReturn['nStaEvent'] == 1){
                    var oDOCallDataTableFile = {
                        ptElementID : 'odvSALShowDataTable',
                        ptBchCode   : aReturn['tBchCode'],
                        ptDocNo     : aReturn['tDocNo'],
                        ptDocKey    :'TSVTSalTwoHD'
                    }
                    JCNxUPFInsertDataFile(oDOCallDataTableFile);

                    switch(aReturn['nStaCallBack']) {
                        case '1':
                            JSvSALCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo'],tCstCode);
                            break;
                        case '2':
                            JSvSALCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo'],tCstCode);
                            break;
                        case '3':
                            JSvSALCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo'],tCstCode);
                            break;
                        default:
                            JSvSALCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo'],tCstCode);
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



    // ##################################### พิมพ์เอกสาร - ใบเบิกจ่าย (หน่วยงาน) #####################################
    function JSxSALPrintDocType1(){
        var tGrandText  = $('#odvSALDataTextBath').text();
        var aInfor      = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tSALBchCode); ?>'},
            {"DocCode"      : '<?=@$tSALDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tSALBchCode;?>'},
        ];
        window.open("<?php echo base_url(); ?>formreport/Frm_SQL_SMReimbursement?infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand="+tGrandText, '_blank');
    }

    // ##################################### พิมพ์เอกสาร - ใบแลกของพรีเมี่ยม #####################################
    function JSxSALPrintDocType2(){
        var tGrandText  = $('#odvSALDataTextBath').text();
        var aInfor      = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tSALBchCode); ?>'},
            {"DocCode"      : '<?=@$tSALDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tSALBchCode;?>'},
        ];
        window.open("<?php echo base_url(); ?>formreport/Frm_SQL_SMRdmPremium?infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand="+tGrandText, '_blank');


    }
</script>