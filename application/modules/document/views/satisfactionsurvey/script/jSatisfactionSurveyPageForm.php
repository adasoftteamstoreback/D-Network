<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script>
    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit");?>';
    var dCurrentDate    = new Date();
    var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes();
    var tUsrLevel = '<?=$this->session->userdata('tSesUsrLevel')?>';
    if( tUsrLevel != "HQ" ){
        $('#oimSatSvBrowseAgn').attr("disabled", true);
    }

    // วันที่ประเมิน
    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard : true,
        autoclose: true
    });
    if($('#oetSatDocTime').val() == ''){
        $('#oetSatDocTime').val(tCurrentTime);
    }

    if($('#oetSatDocDate').val() == ''){
        $('#oetSatDocDate').datepicker("setDate",dCurrentDate); 
    }

    if($('#oetSatSvDate').val() == ''){
        $('#oetSatSvDate').datepicker("setDate",dCurrentDate); 
    }
    
    $('#obtSatDocDate').unbind().click(function(){
        $('#oetSatDocDate').datepicker('show');
    });

    $('#obtSatSvDate').unbind().click(function(){
        $('#oetSatSvDate').datepicker('show');
    });

    $('#obtSatDocTime').unbind().click(function(){
        $('#oetSatDocTime').datetimepicker('show');
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    $('#ocbSatStaAutoGenCode').on('change', function (e) {
        if($('#ocbSatStaAutoGenCode').is(':checked')){
            $("#oetSatDocNo").val('');
            $("#oetSatDocNo").attr("readonly", true);
            $('#oetSatDocNo').closest(".form-group").css("cursor","not-allowed");
            $('#oetSatDocNo').css("pointer-events","none");
            $("#oetSatDocNo").attr("onfocus", "this.blur()");
            $('#ofmSatSurveyAddForm').removeClass('has-error');
            $('#ofmSatSurveyAddForm .form-group').closest('.form-group').removeClass("has-error");
            $('#ofmSatSurveyAddForm em').remove();
        }else{
            $('#oetSatDocNo').closest(".form-group").css("cursor","");
            $('#oetSatDocNo').css("pointer-events","");
            $('#oetSatDocNo').attr('readonly',false);
            $("#oetSatDocNo").removeAttr("onfocus");
        }
    });

    //end

    
    // Browser Cst
    $('#oimBrowseSatSurveyCst').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSatSurveyCstBrowse = undefined;
            oSatSurveyCstBrowse        = oSatCstPriOption({
                'tReturnInputCode'   : 'ohdSatSurveyCstCode',
                'tReturnInputName'   : 'oetSatSurveyCstName',
                'tReturnInputTel'    : 'oetSatSurveyCstTel',
                // 'tReturnInputEmail'  : 'oetSatSurveyCstMail'
            });
            JCNxBrowseData('oSatSurveyCstBrowse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Browse ลูกค้า
    var oSatCstPriOption = function(oSatSurveyCstBrowse){
        let tSatCstCode    = oSatSurveyCstBrowse.tReturnInputCode;
        let tSatCstName    = oSatSurveyCstBrowse.tReturnInputName;
        let tSatCstTel     = oSatSurveyCstBrowse.tReturnInputTel;
        let tSatCstEmail   = oSatSurveyCstBrowse.tReturnInputEmail;

        var tSQLWhereBch = "AND ISNULL(TCNMCst.FTAgnCode, '') = '"+$("#oetSatAgnCode").val()+"' ";

        let oOptionReturnSatCst    = {
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
                Value: ["ohdSatSurveyCstCode", "TCNMCst.FTCstCode"],
                Text: ["oetSatSurveyCstName", "TCNMCst_L.FTCstName"],
            },
            //DebugSQL: true,
            NextFunc:{
                FuncName:'JSxNextFuncSatCst',
                ArgReturn:['FTCstTel', 'FTCstEmail']
            },
        };
        return oOptionReturnSatCst;
    };

    function JSxNextFuncSatCst(paData) {
        $("#oetSatSurveyCstTel").val("");
        $("#oetSatSurveyCstMail").val("");
        var tSatCstTel = ''
        var tSatCstEmail = ''
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aSatCstData = JSON.parse(paData);
            tSatCstTel = aSatCstData[0];
            tSatCstEmail = aSatCstData[1];
        }
        $("#oetSatSurveyCstTel").val(tSatCstTel);
        $("#oetSatSurveyCstMail").val(tSatCstEmail);

    }
    // end ลูกค้า

    //Browse ใบอ้างอิงสั่งงาน
    $('#oimBrowseDocRef').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetSatAgnCode').val();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSatDocRefBrowse = undefined;
            oSatDocRefBrowse        = oSatDocRefOption({
                'tReturnInputCode'   : 'ohdSatSurveyCstCode',
                'tReturnInputName'   : 'oetSatSurveyCstName',
            });
            JCNxBrowseData('oSatDocRefBrowse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oSatDocRefOption = function(oSatDocRefBrowse){
        let tSatDocCode    = oSatDocRefBrowse.tReturnInputCode;
        let tSatDocName    = oSatDocRefBrowse.tReturnInputName;
        //var tWhereStaAll = "";
        // var tWhereStaAll = "AND TSVTJob2OrdHD.FTXshStaDoc = '1' AND TSVTJob2OrdHD.FTXshStaApv = '1' AND TSVTJob2OrdHD.FTXshStaClosed = '1' AND TSVTJob2OrdHD.FNXshStaDocAct = '1'";//AND TSVTJob2OrdHD.FTCstCode = '"+$("#ohdSatSurveyCstCode").val()+"'"
        
        // Last Update By : Napat(Jame) 15/11/2564
        // ค้นหาใบสั่งงานที่ยังไม่จบ job
        var tWhereStaAll = " AND TSVTJob2OrdHD.FTXshStaDoc = '1' AND TSVTJob2OrdHD.FTXshStaClosed = '1' ";
        
        var tWhereAgn = "";
        var tWhereBch = "";
        var tWhereCst = "";
        if ($("#oetSatAgnCode").val() != '') {
            var tWhereAgn = "AND TSVTJob2OrdHD.FTAgnCode = '"+$("#oetSatAgnCode").val()+"'";
        }
        
        if ($("#ohdSatBchCode").val() != '') {
            var tWhereBch = "AND TSVTJob2OrdHD.FTBchCode = '"+$("#ohdSatBchCode").val()+"'";
        }
        
        if ($("#ohdSatSurveyCstCode").val()) {
            var tWhereCst = "AND TSVTJob2OrdHD.FTCstCode = '"+$("#ohdSatSurveyCstCode").val()+"'";
        }
        
        var tWhereDocRef = "AND ISNULL(JOB5.FTXshRefDocNo, '') = ''"
         
        let oOptionReturnSatRefDoc    = {
            Title: ['document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyDocRef'],
            Table: {
                Master: 'TSVTJob2OrdHD',
                PK: 'FTXshDocNo'
            },
            Join :{
                Table:	['TCNMCst', 'TCNMCst_L', 'TCNMAgency_L', 'TSVTJob2OrdHDCst J2HDCst', 'TSVMCar', 'TSVMCarInfo_L T1', 'TSVMCarInfo_L T2', 'TCNMBranch_L', 'TSVTJob5ScoreHDDocRef JOB5'],
                On:[
                        'TSVTJob2OrdHD.FTCstCode = TCNMCst.FTCstCode',
                        'TSVTJob2OrdHD.FTCstCode = TCNMCst_L.FTCstCode AND TCNMCst_L.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTAgnCode = TCNMAgency_L.FTAgnCode AND TCNMAgency_L.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTXshDocNo = J2HDCst.FTXshDocNo',
                        'J2HDCst.FTCarCode = TSVMCar.FTCarCode',
                        'TSVMCar.FTCarBrand  = T1.FTCaiCode AND T1.FNLngID ='+nLangEdits,
                        'TSVMCar.FTCarModel  = T2.FTCaiCode AND T2.FNLngID ='+nLangEdits,
                        'TSVTJob2OrdHD.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID ='+nLangEdits,
                        'JOB5.FTXshRefDocNo = TSVTJob2OrdHD.FTXshDocNo AND JOB5.FTXshRefType = 1'
                   ]    
            },
            Where: {
                Condition: [tWhereStaAll,tWhereAgn,tWhereBch,tWhereCst,tWhereDocRef]
            },
            GrideView: {
                ColumnPathLang: 'document/satisfactionsurvey/satisfactionsurvey',
                ColumnKeyLang: ['tSatSurveyBch', 'tSatSurveyDocNo', 'tSatSurveyDocDate', 'tSatSurveyCst'],
                ColumnsSize: ['20%', '20%' ,'20%', '20%', '20%'],
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
                                'TCNMBranch_L.FTBchCode'
                            ],
                DataColumnsFormat: ['','','',''],
                DisabledColumns: [4,5,6,7,8,9,10,11],
                Perpage: 10,
                OrderBy: ['TSVTJob2OrdHD.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["ohdSatDocRefCode", "TSVTJob2OrdHD.FTXshDocNo"],
                Text: ["oetSatDocRefCode", "TSVTJob2OrdHD.FTXshDocNo"],
            },
            //DebugSQL: true,
            NextFunc:{
                FuncName:'JSxNextFuncSatDocRef',
                ArgReturn:['FDXshDocDate', 'FTCarBrand', 'FTCarModel', 'FTCarRegNo','FTCstTel','FTCstEmail','FTAgnName','FTBchName','FTCstName','FTAgnCode','FTBchCode']
            },
        };
        return oOptionReturnSatRefDoc;
    };

    function JSxNextFuncSatDocRef(paData) {
        $("#oetSatSurveyDateStaService").val("");
        $("#oetSatSurveySrvCar").val("");
        $("#oetSatSurveyCarNo").val("");
        $("#oetSatAgnCode").val("");
        $("#oetSatAgnName").val("");
        $("#oetSatBchName").val("");
        $("#ohdSatBchCode").val("");
        $("#oetSatSurveyCstName").val("");
        $("#oetSatSurveyCstTel").val("");
        $("#oetSatSurveyCstMail").val("");
        var tSatDateStaService  = '';
        var tSatSrvCarBrand     = '';
        var tSatSrvCarModel     = '';
        var tSatRegCarNo        = '';
        var tSatAgnCode         = '';
        var tSatAgnName         = '';
        var tSatBchCode         = '';
        var tSatBchName         = '';
        var tSatCstName         = '';
        var tSatCstTel          = '';
        var tSatCstMail         = '';
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aSatCstData = JSON.parse(paData);
            console.log(aSatCstData);
            tSatDateStaService  = aSatCstData[0];
            tSatSrvCarBrand     = aSatCstData[1];
            tSatSrvCarModel     = aSatCstData[2];
            tSatRegCarNo        = aSatCstData[3];
            tSatAgnName         = aSatCstData[6];
            tSatBchName         = aSatCstData[7];
            tSatCstName         = aSatCstData[8];
            tSatCstTel          = aSatCstData[4];
            tSatCstMail         = aSatCstData[5];
            tSatAgnCode         = aSatCstData[9];
            tSatBchCode         = aSatCstData[10];
        }
        $("#oetSatSurveyDateStaService").val(tSatDateStaService);
        $("#oetSatSurveySrvCar").val(tSatSrvCarBrand + " " + tSatSrvCarModel);
        $("#oetSatSurveyCarNo").val(tSatRegCarNo);
        $("#oetSatAgnCode").val(tSatAgnCode);
        $("#oetSatAgnName").val(tSatAgnName);
        $("#ohdSatBchCode").val(tSatBchCode);
        $("#oetSatBchName").val(tSatBchName);
        $("#oetSatSurveyCstName").val(tSatCstName);
        $("#oetSatSurveyCstTel").val(tSatCstTel);
        $("#oetSatSurveyCstMail").val(tSatCstMail);
    }
    //end

    //Browse ชื่อพนักงาน
    $('#oimBrowseUsrBch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSatUsrUse = undefined;
            oSatUsrUse        = oSatUsrUseOption({
                'tReturnInputCode'   : 'ohdSatSurveyCstCode',
                'tReturnInputName'   : 'oetSatSurveyCstName',
            });
            JCNxBrowseData('oSatUsrUse');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oSatUsrUseOption = function(oSatUsrUse){
        let tSatUsrUseCode    = oSatUsrUse.tReturnInputCode;
        let tSatUsrUseName    = oSatUsrUse.tReturnInputName;
        let oOptionReturnSatUsrUse    = {
            Title: ['document/satisfactionsurvey/satisfactionsurvey', 'tSatSurveyUsr'],
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
                Value: ["ohdSatTaskRefUsrCode", "TCNMUser.FTUsrCode"],
                Text: ["oetSatTaskRefUsrName", "TCNMUser_L.FTUsrName"],
            },
        };
        return oOptionReturnSatUsrUse;
    };
    //end

    // browse สาขา
    $('#obtSatSvBrowseBCH').unbind().click(function(){ 
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetSatAgnCode').val();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSatBrowseBranchOption  = undefined;
            oSatBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'ohdSatBchCode',
                'tReturnInputName'  : 'oetSatBchName',
                'tAgnCode'          : tAgnCode
            });
            JCNxBrowseData('oSatBrowseBranchOption');
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
    $('#oimSatSvBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oSatBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetSatAgnCode',
                'tReturnInputName': 'oetSatAgnName',
            });
            JCNxBrowseData('oSatBrowseAgencyOption');
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
                    FuncName:'JSxNextFuncSatAgn'
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

    function JSxNextFuncSatAgn() {
        $("#ohdSatBchCode").val('');
        $("#oetSatBchName").val('');
        $("#ohdSatSurveyCstCode").val('');
        $("#oetSatSurveyCstName").val('');
        $("#oetSatSurveyCstTel").val('');
        $("#oetSatSurveyCstMail").val('');
        $("#ohdSatDocRefCode").val('');
        $("#oetSatDocRefCode").val('');
        $("#oetSatSurveyDateStaService").val('');
        $("#oetSatSurveySrvCar").val('');
        $("#oetSatSurveyCarNo").val('');
    }

    //end

    function JSxSATSetStatusClickSubmit(pnStatus) {
        $("#ohdSatSvCheckSubmitByButton").val(pnStatus);
    }
    // Event Click Appove Document
    $('#obtSatSvApproveDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxSATSetStatusClickSubmit(2);
            JSxSatApproveDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

            // Event Click Cancel Document
    $('#obtSatSvCancelDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSnSATCancelDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Click Submit From Document
    $('#obtSatSvSubmitFromDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxSATSetStatusClickSubmit(1);
            $('#obtSubmitSat').click();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    

    // validate and insert
    function JSoAddEditSat(ptRoute){
        var nStaSession = JCNxFuncChkSessionExpired();
        var nStaDoc = $('#ohdSatStaDoc').val();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1 && nStaDoc != 3){
            $('#ofmSatSurveyAddForm').validate({
                rules: {
                    oetSatDocNo : {
                        "required" : {
                            depends: function (oElement) {
                                if(ptRoute == "docSatisfactionSurveyEventAdd"){
                                    if($('#ocbSatStaAutoGenCode').is(':checked')){
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
                    oetSatBchName           : {"required" : true},
                    oetSatSurveyCstName     : {"required" : true},
                    oetSatDocRefCode        : {"required" : true}
                },
                messages: {
                    oetSatDocNo             : {"required" : $('#oetSatDocNo').attr('data-validate-required')},
                    oetSatSurveyCstName     : {"required" : $('#oetSatSurveyCstName').attr('data-validate-required')},
                    oetSatBchName           : {"required" : $('#oetSatBchName').attr('data-validate-required')},
                    oetSatDocRefCode        : {"required" : $('#oetSatDocRefCode').attr('data-validate-required')}
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
                    if(!$('#ocbSatStaAutoGenCode').is(':checked')){
                        JSxSATValidateDocCodeDublicate(ptRoute);
                    }else{
                        if($("#ohdSatSvCheckSubmitByButton").val() == 1){
                            JSxSatSubmitEventByButton(ptRoute);
                        }
                    }
                },
            });
        }else if(typeof(nStaSession) !== 'undefined' && nStaSession == 1 && nStaDoc == 3){
            if(!$('#ocbSatStaAutoGenCode').is(':checked')){
                JSxSATValidateDocCodeDublicate(ptRoute);
            }else{
                if($("#ohdSatSvCheckSubmitByButton").val() == 1){
                    JSxSatSubmitEventByButton(ptRoute);
                }
            }
        }else{
            JCNxShowMsgSessionExpired();
        }

    }
    //end

    // Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
    function JSxSATValidateDocCodeDublicate(ptRoute){
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName'    : 'TSVTJob5ScoreHD',
                'tFieldName'    : 'FTXshDocNo',
                'tCode'         : $('#oetSatDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                $("#ohdSatCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdSatSvCheckClearValidate").val() != 1) {
                    $('#ofmSatSurveyAddForm').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if(ptRoute == "docSatisfactionSurveyEventAdd"){
                        if($('#ocbSatStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdSatCheckDuplicateCode").val() == 1) {
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
                        oetSatDocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetSatDocNo : {"dublicateCode"  : $('#oetSatDocNo').attr('data-validate-duplicate')}
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
                        if($("#ohdSatSvCheckSubmitByButton").val() == 1) {
                            JSxSatSubmitEventByButton(ptRoute);
                        }
                    }
                })
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function: Validate Success And Send Ajax Add/Update Document
    function JSxSatSubmitEventByButton(ptRoute,ptType = ''){
        var nTrLength = $('#otbDataTable tbody tr').length;
        var aSatAns = [];
        var aSatQue = [];

        $("#ocbSatStaAutoGenCode" ).prop( "disabled", false );
        $("#ocbSatStaDocAct" ).prop( "disabled", false);
        $(".xWRadioRate" ).prop( "disabled", false);
        $(".xCNSatAns" ).prop( "disabled", false);
        
        $('.xCNSatAns:checked').each(function(){
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
            
            aSatAns.push(aResult);
        });

        $('.xCNSatAns[type=text]').each(function() {
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
                aSatAns.push(aResult); 
            }
        });

        $('textarea.xCNSatAns').each(function() {
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
                aSatAns.push(aResult); 
            }
        });

        // $('tr.xWQuestion').each(function() {
        //     const aResult = {
        //                         tDocNo:$(this).attr('data-docno'),
        //                         nSeqDt:$(this).attr('data-seqdt'), 
        //                         nQueType:$(this).attr('data-quetype')
        //                     };
            
        //     aSatQue.push(aResult); 
        // });

        $('#otbDataTable tbody tr').each(function(){
            $(this).find('.xWQuestion').each(function(){
                const aResult = {
                                tDocNo:$(this).attr('data-docno'),
                                nSeqDt:$(this).attr('data-seqdt'), 
                                nQueType:$(this).attr('data-quetype')
                            };
            
                aSatQue.push(aResult); 
            })
        })

        if(aSatAns.length < nTrLength){
            $('#odvSatModalvalidate').modal('show');
            return;
        }

        $.ajax({
            type: "POST",
            url: ptRoute,
            data: $('#ofmSatSurveyAddForm').serialize() + '&aSatAns=' + JSON.stringify(aSatAns) + '&aSatQue=' + JSON.stringify(aSatQue),
            success: function(oResult){
                var aReturn = JSON.parse(oResult);

                if(aReturn['nStaEvent'] == 1){
                    var oDOCallDataTableFile = {
                        ptElementID : 'odvSatSvShowDataTable',
                        ptBchCode   : aReturn['tBchCode'],
                        ptDocNo     : aReturn['tDocNo'],
                        ptDocKey    :'TSVTJob5ScoreHD'
                    }
                    JCNxUPFInsertDataFile(oDOCallDataTableFile);
                    if(ptType == 'approve'){
                        var tAgnCode = $('#oetSatAgnCode').val();
                        var tBchCode = $('#ohdSatBchCode').val();
                        var tDocNo = $('#oetSatDocNo').val();
                        $.ajax({
                            type: "POST",
                            url: "docSatisfactionSurveyApproveDocument",
                            data: {
                                'tAgnCode': tAgnCode,
                                'tBchCode': tBchCode,
                                'tDocNo': tDocNo
                            },
                            cache: false,
                            timeout: 0,
                            success: function(tResult) {
                                var aReturnData = JSON.parse(tResult);
                                if (aReturnData['nStaEvent'] == '1') {
                                    $("#odvSatModalAppoveDoc").modal("hide");
                                    JCNxCloseLoading();
                                    JSvSatSvCallPageEdit(tAgnCode,tBchCode,tDocNo);
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
                                JSvSatSvCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo']);
                                break;
                            case '2':
                                JSvSatSvCallPageAdd();
                                break;
                            case '3':
                                JSvSatSvCallPageList()
                                break;
                            default:
                                JSvSatSvCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo']);
                        }
                    }
                }else{
                    FSvCMNSetMsgErrorDialog(aReturn['tStaMessg']);
                    JCNxCloseLoading();
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    function JSxSATCallBackControll() {  
        alert('sssss');
    }


    // ############################### พิมพ์เอกสาร - ใบประเมินความพึงพอใจลูกค้า ###############################
    function JSxSatSvPrintDoc(){
        var aInfor  = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tSatBchCode); ?>'},
            {"DocCode"      : '<?=@$tSatDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tSatBchCode;?>'},
            {"DocDate"      : '<?=@$dSatDocDate;?>'},
            {"DocTime"      : '<?=@$dSatDocTime;?>'},
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillCstEvaluate?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

</script>