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
        $('#oimJOBBrowseAgn').attr("disabled", true);
    }

    // วันที่ประเมิน
    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard : true,
        autoclose: true
    });

    if($('#oetJOBDocTime').val() == ''){
        $('#oetJOBDocTime').val(tCurrentTime);
    }

    if($('#oetJOBDocTimeBegin').val() == ''){
        $('#oetJOBDocTimeBegin').val(tCurrentTime);
    }

    if($('#oetJOBDocTimeEnd').val() == ''){
        $('#oetJOBDocTimeEnd').val(tTimeEnd);
    }

    if($('#oetJOBDocDate').val() == ''){
        $('#oetJOBDocDate').datepicker("setDate",dCurrentDate); 
    }

    if($('#oetJOBDocDateBegin').val() == ''){
        $('#oetJOBDocDateBegin').datepicker("setDate",dCurrentDate); 
    }

    if($('#oetJOBDocDateEnd').val() == ''){
        $('#oetJOBDocDateEnd').datepicker("setDate",dCurrentDate); 
    }

    $('#obtJOBDocRefExtDate').unbind().click(function(){
        $('#oetJOBDocRefExtDate').datepicker('show');
    });

    $('#obtJOBDocDate').unbind().click(function(){
        $('#oetJOBDocDate').datepicker('show');
    });
    
    $('#obtJOBDocDateBegin').unbind().click(function(){
        $('#oetJOBDocDateBegin').datepicker('show');
    });

    $('#obtJOBDocDateEnd').unbind().click(function(){
        $('#oetJOBDocDateEnd').datepicker('show');
    });

    $('#obtJOBDocTime').unbind().click(function(){
        $('#oetJOBDocTime').datetimepicker('show');
    });

    $('#obtJOBDocTimeBegin').unbind().click(function(){
        $('#oetJOBDocTimeBegin').datetimepicker('show');
    });

    $('#obtJOBDocTimeEnd').unbind().click(function(){
        $('#oetJOBDocTimeEnd').datetimepicker('show');
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    $('#ocbJOBStaAutoGenCode').on('change', function (e) {
        if($('#ocbJOBStaAutoGenCode').is(':checked')){
            $("#oetJOBDocNo").val('');
            $("#oetJOBDocNo").attr("readonly", true);
            $('#oetJOBDocNo').closest(".form-group").css("cursor","not-allowed");
            $('#oetJOBDocNo').css("pointer-events","none");
            $("#oetJOBDocNo").attr("onfocus", "this.blur()");
            $('#ofmJOBAddForm').removeClass('has-error');
            $('#ofmJOBAddForm .form-group').closest('.form-group').removeClass("has-error");
            $('#ofmJOBAddForm em').remove();
        }else{
            $('#oetJOBDocNo').closest(".form-group").css("cursor","");
            $('#oetJOBDocNo').css("pointer-events","");
            $('#oetJOBDocNo').attr('readonly',false);
            $("#oetJOBDocNo").removeAttr("onfocus");
        }
    });

    //end

    
    // Browser Cst
    $('#oimBrowseJOBCst').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oJOBCstBrowse = undefined;
            oJOBCstBrowse        = oJOBCstPriOption({
                'tReturnInputCode'   : 'ohdJOBCstCode',
                'tReturnInputName'   : 'oetJOBCstName',
                'tReturnInputTel'    : 'oetJOBCstTel',
                'tReturnInputEmail'  : 'oetJOBCstMail'
            });
            JCNxBrowseData('oJOBCstBrowse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse ลูกค้า
    var oJOBCstPriOption = function(oJOBCstBrowse){
        let tJOBCstCode    = oJOBCstBrowse.tReturnInputCode;
        let tJOBCstName    = oJOBCstBrowse.tReturnInputName;
        let tJOBCstTel     = oJOBCstBrowse.tReturnInputTel;
        let tJOBCstEmail   = oJOBCstBrowse.tReturnInputEmail;

        var tSQLWhereBch = "AND ISNULL(TCNMCst.FTAgnCode, '') = '"+$("#oetJOBAgnCode").val()+"' ";

        let oOptionReturnJOBCst    = {
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
                Value: ["ohdJOBCstCode", "TCNMCst.FTCstCode"],
                Text: ["oetJOBCstName", "TCNMCst_L.FTCstName"],
            },
            //DebugSQL: true,
            NextFunc:{
                FuncName:'JSxNextFuncJOBCst',
                ArgReturn:['FTCstTel', 'FTCstEmail']
            },
        };
        return oOptionReturnJOBCst;
    };

    function JSxNextFuncJOBCst(paData) {
        $("#oetJOBCstTel").val("");
        $("#oetJOBCstMail").val("");
        var tJOBCstTel = ''
        var tJOBCstEmail = ''
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aJOBCstData = JSON.parse(paData);
            tJOBCstTel = aJOBCstData[0];
            tJOBCstEmail = aJOBCstData[1];
        }
        $("#oetJOBCstTel").val(tJOBCstTel);
        $("#oetJOBCstMail").val(tJOBCstEmail);

        $(".xCNClaerValWhenCstChange").val("");

    }
    // end ลูกค้า

    //Browse ใบอ้างอิงสั่งงาน
    $('#oimBrowseDocRef').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oJOBDocRefBrowse = undefined;
            oJOBDocRefBrowse        = oJOBDocRefOption({
                'tReturnInputCode'   : 'ohdJOBCstCode',
                'tReturnInputName'   : 'oetJOBCstName',
            });
            JCNxBrowseData('oJOBDocRefBrowse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oJOBDocRefOption = function(oJOBDocRefBrowse){
        let tJOBDocCode    = oJOBDocRefBrowse.tReturnInputCode;
        let tJOBDocName    = oJOBDocRefBrowse.tReturnInputName;
        //var tWhereStaAll = "";
        var tWhereStaAll = "AND TSVTJob2OrdHD.FTXshStaDoc = '1' AND TSVTJob2OrdHD.FTXshStaApv = '1' AND TSVTJob2OrdHD.FTXshStaClosed = '1' AND TSVTJob2OrdHD.FNXshStaDocAct = '1'";//AND TSVTJob2OrdHD.FTCstCode = '"+$("#ohdJOBCstCode").val()+"'"
        var tWhereAgn = "";
        var tWhereBch = "";
        var tWhereCst = "";
        if ($("#oetJOBAgnCode").val() != '') {
            var tWhereAgn = "AND TSVTJob2OrdHD.FTAgnCode = '"+$("#oetJOBAgnCode").val()+"'";
        }
        
        if ($("#ohdJOBBchCode").val() != '') {
            var tWhereBch = "AND TSVTJob2OrdHD.FTBchCode = '"+$("#ohdJOBBchCode").val()+"'";
        }
        
        if ($("#ohdJOBSurveyCstCode").val()) {
            var tWhereCst = "AND TSVTJob2OrdHD.FTCstCode = '"+$("#ohdJOBSurveyCstCode").val()+"'";
        }
        
        var tWhereDocRef = "AND ISNULL(JOB4.FTXshRefDocNo, '') = ''"
        
        let oOptionReturnJOBRefDoc    = {
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
                                'TCNMCst.FTCstEmail'
                            ],
                DataColumnsFormat: ['','','',''],
                DisabledColumns: [4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],
                Perpage: 10,
                OrderBy: ['TSVTJob2OrdHD.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["ohdJOBDocRefCode", "TSVTJob2OrdHD.FTXshDocNo"],
                Text: ["oetJOBDocRefCode", "TSVTJob2OrdHD.FTXshDocNo"],
            },
            //DebugSQL: true,
            NextFunc:{
                FuncName:'JSxNextFuncJOBDocRef',
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
        return oOptionReturnJOBRefDoc;
    };

    function JSxNextFuncJOBDocRef(paData) {
        $("#oetJOBDateStaService").val("");
        $("#oetJOBCarNo").val("");
        $("#oetJOBCarEngineCode").val("");
        $("#oetJOBCarPowerCode").val("");
        $("#oetJOBCarType").val("");
        $("#oetJOBCarOwnerType").val("");
        $("#oetJOBCarBrand").val("");
        $("#oetJOBCarModel").val("");
        $("#oetJOBCarColor").val("");
        $("#oetJOBCarGear").val("");
        $("#oetJOBCarEngineOil").val("");
        $("#oetJOBCarCldVol").val("");
        $("#oetJOBCarMileAge").val("");
        $("#oetJOBProvinceName").val("");
        $("#oetJOBAgnName").val("");
        $("#oetJOBBchName").val("");
        $("#oetJOBCstName").val("");
        $("#oetJOBCstTel").val("");
        $("#oetJOBCstMail").val("");
        $("#oetJOBServiceToPos").val("");

        var tJOBDateStaService   = '';
        var tJOBRegCarNo         = '';
        var tJOBCarEngineCode    = '';
        var tJOBCarPowerCode     = '';
        var tJOBCarType          = '';
        var tJOBCarOwnerType     = '';
        var tJOBCarBrand         = '';
        var tJOBCarModel         = '';
        var tJOBCarColor         = '';
        var tJOBCarGear          = '';
        var tJOBCarEngineOil     = '';
        var tJOBCarCldVol        = '';
        var tJOBCarMileAge       = '';
        var tJOBCarProvince      = '';
        var tJOBAgnName          = '';
        var tJOBBchName          = '';
        var tJOBCstName          = '';
        var tJOBCstTel           = '';
        var tJOBCstMail          = '';
        var tJOBServiceToPos     = '';
        
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aJOBCstData = JSON.parse(paData);
            console.log(aJOBCstData);
            tJOBDateStaService   = aJOBCstData[0];
            tJOBRegCarNo         = aJOBCstData[1];
            tJOBCarEngineCode    = aJOBCstData[2];
            tJOBCarPowerCode     = aJOBCstData[3];
            tJOBCarType          = aJOBCstData[12];
            tJOBCarOwnerType     = aJOBCstData[4];
            tJOBCarBrand         = aJOBCstData[5];
            tJOBCarModel         = aJOBCstData[6];
            tJOBCarColor         = aJOBCstData[7];
            tJOBCarGear          = aJOBCstData[8];
            tJOBCarEngineOil     = aJOBCstData[9];
            tJOBCarCldVol        = aJOBCstData[10];
            tJOBCarMileAge       = aJOBCstData[11];
            tJOBCarProvince      = aJOBCstData[13];
            tJOBAgnName          = aJOBCstData[14];
            tJOBBchName          = aJOBCstData[15];
            tJOBCstName          = aJOBCstData[16];
            tJOBCstTel           = aJOBCstData[17];
            tJOBCstMail          = aJOBCstData[18];
            tJOBServiceToPos     = aJOBCstData[19]; 
        }
        $("#oetJOBDateStaService").val(tJOBDateStaService);
        $("#oetJOBCarNo").val(tJOBRegCarNo);
        $("#oetJOBCarEngineCode").val(tJOBCarEngineCode);
        $("#oetJOBCarPowerCode").val(tJOBCarPowerCode);
        $("#oetJOBCarType").val(tJOBCarType);
        $("#oetJOBCarOwnerType").val(tJOBCarOwnerType);
        $("#oetJOBCarBrand").val(tJOBCarBrand);
        $("#oetJOBCarModel").val(tJOBCarModel);
        $("#oetJOBCarColor").val(tJOBCarColor);
        $("#oetJOBCarGear").val(tJOBCarGear);
        $("#oetJOBCarEngineOil").val(tJOBCarEngineOil);
        $("#oetJOBCarCldVol").val(tJOBCarCldVol);
        $("#oetJOBCarMileAge").val(tJOBCarMileAge);
        $("#oetJOBProvinceName").val(tJOBCarProvince);
        $("#oetJOBAgnName").val(tJOBAgnName);
        $("#oetJOBBchName").val(tJOBBchName);
        $("#oetJOBCstName").val(tJOBCstName);
        $("#oetJOBCstTel").val(tJOBCstTel);
        $("#oetJOBCstMail").val(tJOBCstMail);
        $("#oetJOBServiceToPos").val(tJOBServiceToPos);
    }
    //end

    //Browse ชื่อพนักงาน
    $('#oimBrowseUsrBch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oJOBUsrUse = undefined;
            oJOBUsrUse        = oJOBUsrUseOption({
                'tReturnInputCode'   : 'ohdJOBCstCode',
                'tReturnInputName'   : 'oetJOBCstName',
            });
            JCNxBrowseData('oJOBUsrUse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oJOBUsrUseOption = function(oJOBUsrUse){
        let tJOBUsrUseCode    = oJOBUsrUse.tReturnInputCode;
        let tJOBUsrUseName    = oJOBUsrUse.tReturnInputName;
        let oOptionReturnJOBUsrUse    = {
            Title: ['document/satisfactionsurvey/satisfactionsurvey', 'tJOBUsr'],
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
                Value: ["ohdJOBTaskRefUsrCode", "TCNMUser.FTUsrCode"],
                Text: ["oetJOBTaskRefUsrName", "TCNMUser_L.FTUsrName"],
            },
        };
        return oOptionReturnJOBUsrUse;
    };
    //end

    // browse สาขา
    $('#obtJOBBrowseBCH').unbind().click(function(){ 
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetJOBAgnCode').val();
        if (tAgnCode == '') {
            $('#oimJOBBrowseAgn').click();
        }else{
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oJOBBrowseBranchOption  = undefined;
                oJOBBrowseBranchOption         = oBranchOption({
                    'tReturnInputCode'  : 'ohdJOBBchCode',
                    'tReturnInputName'  : 'oetJOBBchName',
                    'tAgnCode'          : tAgnCode
                });
                JCNxBrowseData('oJOBBrowseBranchOption');
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
    $('#oimJOBBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oJOBBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetJOBAgnCode',
                'tReturnInputName': 'oetJOBAgnName',
            });
            JCNxBrowseData('oJOBBrowseAgencyOption');
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
                    FuncName:'JSxNextFuncJOBAgn'
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

    function JSxNextFuncJOBAgn() {
        $("#ohdJOBBchCode").val('');
        $("#oetJOBBchName").val('');
        $("#ohdJOBCstCode").val('');
        $("#oetJOBCstName").val('');
        $("#oetJOBCstTel").val('');
        $("#oetJOBCstMail").val('');
        $("#ohdJOBDocRefCode").val('');
        $("#oetJOBDocRefCode").val('');
        $("#oetJOBDateStaService").val('');
        $("#oetJOBSrvCar").val('');
        $("#oetJOBCarNo").val('');
    }

    //end

    function JSxJOBSetStatusClickSubmit(pnStatus) {
        $("#ohdJOBCheckSubmitByButton").val(pnStatus);
    }
    // Event Click Appove Document
    $('#obtJOBApproveDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxJOBSetStatusClickSubmit(2);
            JSxJOBApproveDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

            // Event Click Cancel Document
    $('#obtJOBCancelDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSnJOBCancelDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Submit From Document
    $('#obtJOBSubmitFromDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxJOBSetStatusClickSubmit(1);
            $('#obtSubmitJOB').click();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    

    // validate and insert
    function JSoAddEditJOB(ptRoute){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            if($("#ohdJOBCheckSubmitByButton").val() == 1) {
                JSxJOBSubmitEventByButton(ptRoute);
            }else{
                JCNxShowMsgSessionExpired();
            }
        }
    }
    //end

    // Function: Validate Success And Send Ajax Add/Update Document
    function JSxJOBSubmitEventByButton(ptRoute){
        var tRmk = $('#otaJOBFrmInfoOthRmk').val();
        var tAgnCode = $('#oetJOBAgnCode').val();
        var tBchCode = $('#ohdJOBBchCode').val();
        var tDocNo = $('#oetJOBDocNo').val();
        var tCstCode = $('#ohdJOBCstCode').val();
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
                    let oDOCallDataTableFile = {
                        ptElementID: 'odvJOBShowDataTable',
                        ptBchCode: aReturn['tBchCode'],
                        ptDocNo: aReturn['tDocNo'],
                        ptDocKey:'TSVTJob2OrdHD'
                    }

                    JCNxUPFInsertDataFile(oDOCallDataTableFile);
                    switch(aReturn['nStaCallBack']) {
                        case '1':
                            JSvJOBCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo'],tCstCode);
                            break;
                        case '2':
                            JSvJOBCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo'],tCstCode);
                            break;
                        case '3':
                            JSvJOBCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo'],tCstCode);
                            break;
                        default:
                            JSvJOBCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo'],tCstCode);
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

</script>