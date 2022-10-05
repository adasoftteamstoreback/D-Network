<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script type="text/javascript">
    var nLangEdits        = '<?php echo $this->session->userdata("tLangEdit");?>';
    var tUsrApvName       = '<?php echo $this->session->userdata("tSesUsername");?>';
    var tSesUsrLevel      = '<?php echo $this->session->userdata('tSesUsrLevel');?>';
    var tUserBchCode      = '<?php echo $this->session->userdata("tSesUsrBchCode");?>';
    var tUserBchName      = '<?php echo $this->session->userdata("tSesUsrBchName");?>';
    var tUserWahCode      = '<?php echo $this->session->userdata("tSesUsrWahCode");?>';
    // var tUserWahName      = '<?php //echo $this->session->userdata("tSesUsrWahName");?>';
    var tRoute                 = $('#ohdTWXRoute').val();
    var tTWXSesSessionID        = $("#ohdSesSessionID").val();
    var nAddressVersion = '<?=FCNaHAddressFormat('TCNMCst')?>';
 
    $(document).ready(function(){
        var nCrTerm = $('#ocmTWXTypePayment').val();
        if (nCrTerm == 2) {
            $('.xCNPanel_CreditTerm').show();
        }else{
            $('.xCNPanel_CreditTerm').hide();
        }
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
        $('.selectpicker').selectpicker('refresh');

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        $("#obtTWXSubmitFromDoc").removeAttr("disabled");

        var dCurrentDate    = new Date();
        if($('#oetTWXDocDate').val() == ''){
            $('#oetTWXDocDate').datepicker("setDate",dCurrentDate); 
        }

        $('.xCNTimePicker').datetimepicker({
            format: 'HH:mm:ss'
        });

        $('.xCNMenuplus').unbind().click(function(){
            if($(this).hasClass('collapsed')){
                $('.xCNMenuplus').removeClass('collapsed').addClass('collapsed');
                $('.xCNMenuPanelData').removeClass('in');
            }
        });

        $('.xWTooltipsBT').tooltip({'placement': 'bottom'});

        $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
    
        $(".xWConditionSearchPdt.disabled").attr("disabled","disabled");


        $('#obtTWXDocBrowsePdt').unbind().click(function(){
            // var nStaSession = JCNxFuncChkSessionExpired();
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){ 
                if($('#oetTWXFrmCstCode').val()!=""){
                JSxCheckPinMenuClose();
                JCNvTWXBrowsePdt();
                }else{
                    $('#odvTWXModalPleseselectCST').modal('show');
                }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // if($('#oetTWXWahBrcCode').val() == ""){
        //     $("#obtTWXFrmBrowseTaxAdd").attr("disabled","disabled");
        // }

        /** =================== Event Search Function ===================== */
            $('#oliTWXMngPdtScan').unbind().click(function(){
                var tTWXSplCode  = $('#oetTWXFrmCstCode').val();
                if(typeof(tTWXSplCode) !== undefined && tTWXSplCode !== ''){
                    //Hide
                    $('#oetTWXFrmFilterPdtHTML').hide();
                    $('#obtTWXMngPdtIconSearch').hide();
                    
                    //Show
                    $('#oetTWXFrmSearchAndAddPdtHTML').show();
                    $('#obtTWXMngPdtIconScan').show();
                }else{
                    var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                }
            });
            $('#oliTWXMngPdtSearch').unbind().click(function(){
                //Hide
                $('#oetTWXFrmSearchAndAddPdtHTML').hide();
                $('#obtTWXMngPdtIconScan').hide();
                //Show
                $('#oetTWXFrmFilterPdtHTML').show();
                $('#obtTWXMngPdtIconSearch').show();
            });
        /** =============================================================== */

        /** ===================== Set Date Autometic Doc ========================  */
            var dCurrentDate    = new Date();
            var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
            var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes() + " " + tAmOrPm;

            if($('#oetTWXDocDate').val() == ''){
                $('#oetTWXDocDate').datepicker("setDate",dCurrentDate); 
            }

            if($('#oetTWXDocTime').val() == ''){
                $('#oetTWXDocTime').val(tCurrentTime);
            }
        /** =============================================================== */

        /** =================== Event Date Function  ====================== */
            $('#obtTWXDocDate').unbind().click(function(){
                $('#oetTWXDocDate').datepicker('show');
            });

            $('#obtTWXDocTime').unbind().click(function(){
                $('#oetTWXDocTime').datetimepicker('show');
            });

            $('#obtTWXBrowseRefIntDocDate').unbind().click(function(){
                $('#oetTWXRefIntDocDate').datepicker('show');
            });

            $('#obtTWXRefDocExtDate').unbind().click(function(){
                $('#oetTWXRefDocExtDate').datepicker('show');
            });

            $('#obtTWXTransDate').unbind().click(function(){
                $('#oetTWXTransDate').datepicker('show');
            });
        /** =============================================================== */

        /** ================== Check Box Auto GenCode ===================== */
            $('#ocbTWXStaAutoGenCode').on('change', function (e) {
                if($('#ocbTWXStaAutoGenCode').is(':checked')){
                    $("#oetTWXDocNo").val('');
                    $("#oetTWXDocNo").attr("readonly", true);
                    $('#oetTWXDocNo').closest(".form-group").css("cursor","not-allowed");
                    $('#oetTWXDocNo').css("pointer-events","none");
                    $("#oetTWXDocNo").attr("onfocus", "this.blur()");
                    $('#ofmTWXFormAdd').removeClass('has-error');
                    $('#ofmTWXFormAdd .form-group').closest('.form-group').removeClass("has-error");
                    $('#ofmTWXFormAdd em').remove();
                }else{
                    $('#oetTWXDocNo').closest(".form-group").css("cursor","");
                    $('#oetTWXDocNo').css("pointer-events","");
                    $('#oetTWXDocNo').attr('readonly',false);
                    $("#oetTWXDocNo").removeAttr("onfocus");
                }
            });
        /** =============================================================== */

        
        $('#ocbPOPurchase').on('change', function(e) {
            $("#ocbTWXSO").prop("checked", false);
            if ($('#ocbPOPurchase').is(':checked')) {
                $("#obtTWXBrowseRefDocInt").prop("disabled", false);
                $("#obtTWXSO").prop("disabled", true);
                $("#ohdTWXRefPOCode").val('');
                $("#oetTWXRefPOName").val('');
            } else {
                $("#ohdTWXRefSOCode").val('');
                $("#oetTWXRefSOName").val('');
                $("#obtTWXBrowseRefDocInt").prop("disabled", true);
            }
        });

        $('#ocbTWXSO').on('change', function(e) {
            $("#ocbPOPurchase").prop("checked", false);
            if ($('#ocbTWXSO').is(':checked')) {
                $("#obtTWXSO").prop("disabled", false);
                $("#obtTWXBrowseRefDocInt").prop("disabled", true);
                $("#ohdTWXRefSOCode").val('');
                $("#oetTWXRefSOName").val('');
            } else {
                $("#ohdTWXRefPOCode").val('');
                $("#oetTWXRefPOName").val('');
                $("#obtTWXSO").prop("disabled", true);
            }
        });
    });
 
    // ========================================== Brows Option Conditon ===========================================
        // ตัวแปร Option Browse Modal สาขา
        var oBranchOptionAgn = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tAgnCode            = poDataFnc.tAgnCode;
            var aArgReturn          = poDataFnc.aArgReturn;

            tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
            tSQLWhereBch = "";
            tSQLWhereAgn = "";

            if(tUsrLevel != "HQ"){
                // tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
                tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+")";
            }

            if(tAgnCode != ""){
                tSQLWhereAgn = "AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
            }
            
            // ตัวแปร ออฟชั่นในการ Return
            var oOptionReturn       = {
                Title: ['authen/user/user', 'tBrowseBCHTitle'],
                Table: {
                    Master  : 'TCNMBranch',
                    PK      : 'FTBchCode'
                },
                Join: {
                    Table   : ['TCNMBranch_L','TCNMWaHouse_L'],
                    On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                             'TCNMBranch.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMBranch.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID ='+nLangEdits,]
                },
                Where : {
                    Condition : [tSQLWhereBch,tSQLWhereAgn]
                },
                GrideView: {
                    ColumnPathLang      : 'authen/user/user',
                    ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                    ColumnsSize         : ['10%', '75%'],
                    DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName','TCNMWaHouse_L.FTWahCode','TCNMWaHouse_L.FTWahName'],
                    DataColumnsFormat   : ['', ''],
                    DisabledColumns   : [2,3],
                    WidthModal          : 50,
                    Perpage             : 10,
                    OrderBy             : ['TCNMBranch.FTBchCode'],
                    SourceOrder         : "ASC"
                },
                NextFunc:{
                    FuncName:'JSxNextFuncTWXBch'
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

        function JSxNextFuncTWXBch() {
            $('#oetTWXFrmWahCode').val('');
            $('#oetTWXFrmWahName').val('');
        }

        //Option Agency
        var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
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
                    FuncName:'JSxNextFuncTWXAgn'
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

    function JSxNextFuncTWXAgn() {
        $('#oetTWXWahBrcCode').val('');
        $('#oetTWXWahBrcName').val('');
        $('#oetTWXFrmWahCode').val('');
        $('#oetTWXFrmWahName').val('');
    }

    // ตัวแปร Option Browse Modal ลูกค้า
    var oCstOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;

        var tWhereAddL = "AND TCNMCst.FTCstStaActive = '1'"

        var oOptionReturn       = {
            Title: ['customer/customer/customer', 'tCSTTitle'],
            Table: {Master:'TCNMCst', PK:'FTCstCode'},
            Join: {
                Table: ['TCNMCst_L'],
                On: [
                    'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = '+nLangEdits,
                ]
            },
            Where:{
                Condition : [tWhereAddL]
            },
            GrideView:{
                ColumnPathLang      : 'customer/customer/customer',
                ColumnKeyLang       : ['tCSTCode', 'tCSTName'],
                ColumnsSize         : ['20%', '80%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName' , 'TCNMCst.FTCstTel' , 'TCNMCst.FTCstEmail'],
                DataColumnsFormat   : ['',''],
                DisabledColumns     : [2,3,4],
                Perpage             : 10,
                OrderBy             : ['TCNMCst.FTCstCode DESC']
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

    // Functionality : Function Behind NextFunc Customer
    // Parameter : Event Next Func Modal
    // Create : 20/09/2021 Off
    // Return : -
    // Return Type : -
    function JSxTWSSetConditionAfterSelectCst(poDataNextFunc){
        var aData;
        if (poDataNextFunc  != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            var poParams = {
                tCstCode     : aData[0],
                tCstName     : aData[1],
                tCstAddL     : aData[4],   
                tCstTel      : aData[2],
                tCstEmail    : aData[3]
            };

            // JSxTWXSetPanelCustomerData(poParams);

            // หาที่อยู่ของลูกค้า
            $.ajax({
                type: "POST",
                url: "docBKOGetAddress",
                data: {
                    'tCstCode'      : aData[0],
                    'tCstName'      : aData[1],
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    var aResultData = JSON.parse(oResult);
                    JCNxCloseLoading();
                    if(aResultData["rtCode"] != '800'){
                        let aDataCst    = aResultData["rtData"];
                        // Set Input Customer Browse Data
                        $('#oetTWXFrmCstCode').val(aDataCst['FTCstCode']);
                        $('#oetTWXFrmCstName').val(aDataCst['FTCstName']);
                        // Set Panel Customer Info
                        $('#oetTWXPanel_CustomerCode').val(aDataCst['FTCstCode']);
                        $('#oetTWXPanel_ADDSeq').val(aDataCst['FNAddSeqNo']);
                        $('#oetTWXPanel_CustomerName').val(aDataCst['FTCstName']);
                        $('#oetTWXPanel_CustomerAddressCode').val(aDataCst['FTCstCode']);
                        // $('#oetTWXPanel_CustomerAddress').val(aDataCst['FTAddV2Desc1']);
                        $('#oetTWXPanel_CustomerTelephone').val(aDataCst['FTCstTel']);
                        $('#oetTWXPanel_CustomerEmail').val(aDataCst['FTCstEmail']);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

            $.ajax({
                type    : "POST",
                url     : "GetAddressCustomer",
                data    :  {
                    'tCSTCode'       : aData[0],
                },
                catch   : false,
                timeout : 0,
                success : function (tResult){
                    aData2 = JSON.parse(tResult);

                    var tAddfull ='';
                    if(nAddressVersion == '1'){
                        tAddfull = aData2[0]['FTAddV1No']+' '+aData2[0]['FTAddV1Soi']+' '+aData2[0]['FTAddV1Village']+' '+aData2[0]['FTAddV1Road']+' '+aData2[0]['FTSudName']+' '+aData2[0]['FTDstName']+' '+aData2[0]['FTPvnName'];
                    }else if(nAddressVersion == '2'){
                        tAddfull = aData2[0]['FTAddV2Desc1']+' '+aData2[0]['FTAddV2Desc2'];
                    }else{
                        tAddfull = "-";
                    }

                    $('#oetTWXPanel_CustomerAddress').val(tAddfull);
                    
                },
                error: function (jqXHR,textStatus,errorThrown){

                }
            });
        }
    }

    function JSxTWXSetPanelCustomerData(poParams){
        $("#oetTWXPanel_CustomerCode").val(poParams.tCstCode);
        $("#oetTWXPanel_CustomerName").val(poParams.tCstName);
        $("#oetTWXPanel_CustomerAddress").val(poParams.tCstAddL);
        $("#oetTWXPanel_CustomerTelephone").val(poParams.tCstTel);
        $("#oetTWXPanel_CustomerEmail").val(poParams.tCstEmail);
    }

    function JSxNextFuncTWXSpl(paData) {
        $("#oetTWXSplName").val("");
        $("#oetTWXFrmSplInfoCrTerm").val("");
        var tTWXSplName = '';
        var tTWXTypePayment = '';
        var tTWXFrmSplInfoCrTerm = '';
        var tTWXFrmSplInfoVatInOrEx = '';
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aTWXSplData = JSON.parse(paData);
            tTWXSplName = aTWXSplData[0];
            tTWXFrmSplInfoVatInOrEx = aTWXSplData[1];
            tTWXTypePayment = aTWXSplData[2]
            tTWXFrmSplInfoCrTerm = aTWXSplData[2]
        }
        $("#oetTWXSplName").val(tTWXSplName);
        $("#oetTWXFrmSplInfoCrTerm").val(tTWXFrmSplInfoCrTerm);

        //ประเภทการชำระเงิน
        if (tTWXTypePayment > 0) {
            $("#ocmTWXTypePayment").val("2").selectpicker('refresh');
        }else{
            $("#ocmTWXTypePayment").val("1").selectpicker('refresh');
        }

        //ประเภทภาษี
        if (tTWXFrmSplInfoVatInOrEx == 1) {
            //รวมใน
            $("#ocmTWXFrmSplInfoVatInOrEx").val("1").selectpicker('refresh');
        }else{
            //แยกนอก
            $("#ocmTWXFrmSplInfoVatInOrEx").val("2").selectpicker('refresh');
        }
    }

    //เลือกสาขา
    $('#obtTWXBrowseBCH').unbind().click(function(){ 
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#oetTWXAgnCode').val();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oTWXBrowseBranchOption  = undefined;
            oTWXBrowseBranchOption         = oBranchOptionAgn({
                'tReturnInputCode'  : 'oetTWXWahBrcCode',
                'tReturnInputName'  : 'oetTWXWahBrcName',
                'tAgnCode'          : tAgnCode,
                'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
            });
            JCNxBrowseData('oTWXBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //เลือกตัวแทนขาย 
    $('#oimTWXBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oTWXBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetTWXAgnCode',
                'tReturnInputName': 'oetTWXAgnName',
            });
            JCNxBrowseData('oTWXBrowseAgencyOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
        
    //เลือกลูกค้า
    $('#obtTWXBrowseCustomer').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oTWXBrowseCstOption   = undefined;
            oTWXBrowseCstOption          = oCstOption({
                'tReturnInputCode'  : 'oetTWXFrmCstCode',
                'tReturnInputName'  : 'oetTWXFrmCstName',
                'tNextFuncName'     : 'JSxTWSSetConditionAfterSelectCst',
                'aArgReturn'        : ['FTCstCode', 'FTCstName','FTCstTel','FTCstEmail']
            });
            JCNxBrowseData('oTWXBrowseCstOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtTWXBrowseRefDocIntMulti').on('click',function(){
        var nCheckRefBrowse = $('#ocmTWXSelectBrowse').val();
        if(nCheckRefBrowse == '0'){
            JSxCallTWXRefIntDoc();
        }else if(nCheckRefBrowse == '1'){
            JSxCallTWXRefIntDocPO();
        }
    });

    $('#obtTWXBrowseRefDocInt').on('click',function(){
        JSxCallTWXRefIntDocPO();
    });

    $('#obtTWXSO').on('click',function(){
        JSxCallTWXRefIntDocPO();
    });

    //Browse เอกสารอ้างอิงภายใน
    function JSxCallTWXRefIntDoc(){
        var tBCHCode = $('#oetTWXWahBrcCode').val()
        var tBCHName = $('#oetTWXWahBrcName').val()
        
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docBKOCallRefIntDoc",
            data: {
                'tBCHCode'      : tBCHCode,
                'tBCHName'      : tBCHName,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                JCNxCloseLoading();
                $('#odvTWXFromRefIntDoc').html(oResult);
                $('#odvTWXModalRefIntDoc').modal({backdrop : 'static' , show : true});
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Browse เอกสารอ้างอิงภายใน PO
    function JSxCallTWXRefIntDocPO(){
        var tBCHCode = $('#oetTWXWahBrcCode').val()
        var tBCHName = $('#oetTWXWahBrcName').val()
        
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "docBKOCallRefIntDocPO",
            data: {
                'tBCHCode'      : tBCHCode,
                'tBCHName'      : tBCHName,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                JCNxCloseLoading();
                $('#odvTWXFromRefIntDoc').html(oResult);
                $('#odvTWXModalRefIntDoc').modal({backdrop : 'static' , show : true});
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    $('#obtConfirmRefDocInt').click(function(){
        var nCountCheck     = $('.ocbRefIntDocDT:checked').length;
        if(nCountCheck == 0){
            alert('กรุณาเลือกรายการสินค้า ก่อนทำเอกสารการจอง');
            return;
        }else{

            $('#odvTWXModalRefIntDoc').modal('hide');

            var tRefIntDocNo        =  $('.xTWXRefInt.active').data('docno');
            var tRefIntDocDate      =  $('.xTWXRefInt.active').data('docdate');
            var tRefIntBchCode      =  $('.xTWXRefInt.active').data('bchcode');
            var aSeqNo              = $('.ocbRefIntDocDT:checked').map(function(elm){
                    return $(this).val();
                }).get();

            var tSplStaVATInOrEx    =  $('.xTWXRefInt.active').data('vatinroex');
            var cSplCrLimit         =  $('.xTWXRefInt.active').data('crtrem');
            var nSplCrTerm          =  $('.xTWXRefInt.active').data('crlimit');
            var tSplName            =  $('#oetTWXFrmCstName').val();
            var tSplCode            =  $('#oetTWXFrmCstCode').val();
            var poParams = {
                FCSplCrLimit        : cSplCrLimit,
                FTSplCode           : tSplCode,
                FTSplName           : tSplName,
                FTSplStaVATInOrEx   : tSplStaVATInOrEx,
                FTRefIntDocNo       : tRefIntDocNo,
                FTRefIntDocDate     : tRefIntDocDate,
            };
            

            $('#oetTWXRefIntDoc').val(tRefIntDocNo);
            $('#oetTWXShowRefInt').val(tRefIntDocNo);

            var nCheckRefBrowse = $('#ocmTWXSelectBrowse').val();
            if(nCheckRefBrowse == '0'){
                var tRount = "docBKOCallRefIntDocInsertDTToTemp";
                JSxTWXSetPanelSupplierData(poParams);
            }else if(nCheckRefBrowse == '1'){
                var tRount = "docBKOCallRefIntDocInsertDTToTempPO";
                JSxTWXSetPanelSupplierDataPO(poParams);
            }

            // ######## ค้นหาข้อมูลลูกค้า ########
            let aDataDocRef = {
                'tRefIntDocNo'      : tRefIntDocNo,
                'tRefIntBchCode'    : tRefIntBchCode,
                'nCheckRefBrowse'   : nCheckRefBrowse
            };
            JSaTWXSetFindCustomerDocRefInfo(aDataDocRef);

            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: tRount,
                data: {
                    'tTWXDocNo'          : $('#oetTWXDocNo').val(),
                    'tTWXFrmBchCode'     : $('#oetTWXWahBrcCode').val(),
                    'tRefIntDocNo'      : tRefIntDocNo,
                    'tRefIntBchCode'    : tRefIntBchCode,
                    'aSeqNo'            : aSeqNo
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    JSvTWXLoadPdtDataTableHtml();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    });

    // ค้นหาข้อมูล ลูกค้าจากเอกสารอ้างอิง Wasin 24112021
    function JSaTWXSetFindCustomerDocRefInfo(aDataDocRef){
        $.ajax({
            type    : "POST",
            url     : 'docBKOFindCstDocRefInfo',
            data    : aDataDocRef,
            cache   : false,
            Timeout : 0,
            success: function (oResult){
                let aDataReturn = JSON.parse(oResult);
                if(aDataReturn['rtCode'] == '1'){
                    let aDataCst    = aDataReturn['raItems'];
                    // Set ค่า Panel Customer
                    $('#oetTWXPanel_CustomerCode').val(aDataCst['FTCstCode']);
                    $('#oetTWXPanel_ADDSeq').val(aDataCst['FNAddSeqNo']);
                    $('#oetTWXPanel_CustomerName').val(aDataCst['FTCstName']);
                    $('#oetTWXPanel_CustomerAddressCode').val(aDataCst['FTCstCode']);
                    $('#oetTWXPanel_CustomerAddress').val(aDataCst['FTAddV2Desc1']);
                    $('#oetTWXPanel_CustomerTelephone').val(aDataCst['FTCstTel']);
                    $('#oetTWXPanel_CustomerEmail').val(aDataCst['FTCstEmail']);
                    // Set ค่า Modal Customer
                    $('#oetTWXFrmCstCode').val(aDataCst['FTCstCode']);
                    $('#oetTWXFrmCstName').val(aDataCst['FTCstName']);
                    // Set ค่าข้อมูลรถ
                    $('#oetTWXCrscarCode').val(aDataCst['FTCarCode']);
                    $('#oetTWXcarCrsName').val(aDataCst['FTCarName']);
                    $('#oetTWXPanel_CustomerCarRegNo').val(aDataCst['FTCarRegNo']);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }



    // Function : ฟังก์ชั่นเซทข้อมูล ผู้จำหน่าย
    function JSxTWXSetPanelSupplierData(poParams){
        // Reset Panel เป็นค่าเริ่มต้น
        $("#ocmTWXFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
        $("#ocmTWXTypePayment.selectpicker").val("2").selectpicker("refresh");
        $("#oetTWXRefDocIntName").val(poParams.FTRefIntDocNo);
        $("#oetTWXRefInAllName").val(poParams.FTRefIntDocNo);
        $("#oetTWXRefIntDocDate").val(poParams.FTRefIntDocDate).datepicker("refresh");

        // ประเภทภาษี
        if(poParams.FTSplStaVATInOrEx === "1"){
            // รวมใน
            $("#ocmTWXFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
        }else{
            // แยกนอก
            $("#ocmTWXFrmSplInfoVatInOrEx.selectpicker").val("2").selectpicker("refresh");
        }

        // ประเภทชำระเงิน
        if(poParams.FCSplCrLimit > 0){
            // เงินเชื่อ
            $("#ocmTWXTypePayment.selectpicker").val("2").selectpicker("refresh");
        }else{
            // เงินสด
            $("#ocmTWXTypePayment.selectpicker").val("1").selectpicker("refresh");
        }
        
        //ผู้ขาย
        $("#oetTWXFrmCstCode").val(poParams.FTSplCode);
        $("#oetTWXFrmCstName").val(poParams.FTSplName);
        $("#oetTWXSplName").val(poParams.FTSplName);
        $("#oetTWXFrmSplInfoCrTerm").val(poParams.FCSplCrLimit);
    }

    // Function : ฟังก์ชั่นเซทข้อมูล ผู้จำหน่าย
    function JSxTWXSetPanelSupplierDataPO(poParams){
        // Reset Panel เป็นค่าเริ่มต้น
        $("#ocmTWXFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
        $("#ocmTWXTypePayment.selectpicker").val("2").selectpicker("refresh");
        $("#oetTWXRefPOName").val(poParams.FTRefIntDocNo);
        $("#oetTWXRefInAllName").val(poParams.FTRefIntDocNo);
        $("#oetTWXRefIntDocDate").val(poParams.FTRefIntDocDate).datepicker("refresh");

        // ประเภทภาษี
        if(poParams.FTSplStaVATInOrEx === "1"){
            // รวมใน
            $("#ocmTWXFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
        }else{
            // แยกนอก
            $("#ocmTWXFrmSplInfoVatInOrEx.selectpicker").val("2").selectpicker("refresh");
        }

        // ประเภทชำระเงิน
        if(poParams.FCSplCrLimit > 0){
            // เงินเชื่อ
            $("#ocmTWXTypePayment.selectpicker").val("2").selectpicker("refresh");
        }else{
            // เงินสด
            $("#ocmTWXTypePayment.selectpicker").val("1").selectpicker("refresh");
        }
        
        //ผู้ขาย
        $("#oetTWXFrmCstCode").val(poParams.FTSplCode);
        $("#oetTWXFrmCstName").val(poParams.FTSplName);
        $("#oetTWXSplName").val(poParams.FTSplName);
        $("#oetTWXFrmSplInfoCrTerm").val(poParams.FCSplCrLimit);
    }

    // Validate From Add Or Update Document
    function JSxTWXValidateFormDocument(){
        if($("#ohdTWXCheckClearValidate").val() != 0){
            $('#ofmTWXFormAdd').validate().destroy();
        }

        $('#ofmTWXFormAdd').validate({
            focusInvalid: true,
            rules: {
                oetTWXDocNo : {
                    "required" : {
                        depends: function (oElement) {
                            if($("#ohdTWXRoute").val()  ==  "docBKOEventAdd"){
                                if($('#ocbTWXStaAutoGenCode').is(':checked')){
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
                oetTWXWahBrcName    : {"required" : true},
                oetTWXFrmCstName : {"required" : true},
                oetTWXToBchName : {"required" : true},
            },
            messages: {
                oetTWXDocNo      : {"required" : $('#oetTWXDocNo').attr('data-validate-required')},
                oetTWXWahBrcName : {"required" : $('#oetTWXWahBrcName').attr('data-validate-required')},
                oetTWXFrmCstName : {"required" : $('#oetTWXFrmCstName').attr('data-validate-required')},
                oetTWXToBchName : {"required" : $('#oetTWXToBchName').attr('data-validate-required')},
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
            highlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function (form){
                if(!$('#ocbTWXStaAutoGenCode').is(':checked')){
                    JSxTWXValidateDocCodeDublicate();
                }else{
                    if($("#ohdTWXCheckSubmitByButton").val() == 1){
                        JSxTWXSubmitEventByButton();
                    }
                }
            },
        });
    }

    // Validate Doc Code (Validate ตรวจสอบรหัสเอกสาร)
    function JSxTWXValidateDocCodeDublicate(){
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: {
                'tTableName'    : 'TCNTPdtTwxHD',
                'tFieldName'    : 'FTXthDocNo',
                'tCode'         : $('#oetTWXDocNo').val()
            },
            success: function (oResult) {
                var aResultData = JSON.parse(oResult);
                $("#ohdTWXCheckDuplicateCode").val(aResultData["rtCode"]);

                if($("#ohdTWXCheckClearValidate").val() != 1) {
                    $('#ofmTWXFormAdd').validate().destroy();
                }

                $.validator.addMethod('dublicateCode', function(value,element){
                    if($("#ohdTWXRoute").val() == "docBKOEventAdd"){
                        if($('#ocbTWXStaAutoGenCode').is(':checked')) {
                            return true;
                        }else{
                            if($("#ohdTWXCheckDuplicateCode").val() == 1) {
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
                $('#ofmTWXFormAdd').validate({
                    focusInvalid: false,
                    onclick: false,
                    onfocusout: false,
                    onkeyup: false,
                    rules: {
                        oetTWXDocNo : {"dublicateCode": {}}
                    },
                    messages: {
                        oetTWXDocNo : {"dublicateCode"  : $('#oetTWXDocNo').attr('data-validate-duplicate')}
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
                        if($("#ohdTWXCheckSubmitByButton").val() == 1) {
                            JSxTWXSubmitEventByButton();
                        }
                    }
                })

                if($("#ohdTWXCheckClearValidate").val() != 1) {
                    $("#ofmTWXFormAdd").submit();
                    $("#ohdTWXCheckClearValidate").val(1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    $('#obtTWXBrowseAgencyTo').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPdtBrowseAgencyOption = oPdtBrowseAgency({
                'tReturnInputCode': 'oetTWXAgnCodeTo',
                'tReturnInputName': 'oetTWXAgnNameTo'
            });
            JCNxBrowseData('oPdtBrowseAgencyOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //เลือกสาขา
    var oPdtBrowseAgency = function(poReturnInput) {
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
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text: [tInputReturnName, "TCNMAgency_L.FTAgnName"],
            },
            BrowseLev: 1
        }
        return oOptionReturn;
    }

    $('#obtTWXBrowseBCHTo').click(function(){ 
        // JCNxBrowseData('oBrowse_BCH'); 
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPOBrowseBranchOption  = undefined;
            oPOBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'oetTWXToBchCode',
                'tReturnInputName'  : 'oetTWXToBchName',
                'tNextFuncName'     : '',
                'tPOAgnCode'        : $('#oetTWXAgnCodeTo').val(),
                'aArgReturn'        : ['FTBchCode','FTBchName','FTWahCode','FTWahName'],
            });
            JCNxBrowseData('oPOBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ตัวแปร Option Browse Modal สาขา
    var oBranchOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tPOAgnCode          = poDataFnc.tPOAgnCode;
        
        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhere = "";
        if(tUsrLevel != "HQ"){
            tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }

        if(tPOAgnCode!=''){
            tSQLWhere = " AND TCNMBranch.FTAgnCode ='"+tPOAgnCode+"' ";
        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn       = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master  : 'TCNMBranch',
                PK      : 'FTBchCode'
            },
            Join: {
                Table   : ['TCNMBranch_L','TCNMWaHouse_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                            'TCNMBranch.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMBranch.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID ='+nLangEdits,]
            },
            Where : {
                Condition : [tSQLWhere]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName','TCNMWaHouse_L.FTWahCode','TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat   : ['', ''],
                DisabledColumns   : [2,3],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode'],
                SourceOrder         : "ASC"
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text        : [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
            NextFunc: {
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            },
            RouteAddNew: 'branch',
            BrowseLev: 1
        };
        return oOptionReturn;
    }

    // Function: Validate Success And Send Ajax Add/Update Document
    function JSxTWXSubmitEventByButton(ptType = ''){
        JCNxOpenLoading();
        var tTWXDocNo = '';

        if($("#ohdTWXRoute").val() !=  "docBKOEventAdd"){
            var tTWXDocNo    = $('#oetTWXDocNo').val();
        }
        $("#obtTWXSubmitFromDoc").attr('disabled','true');
        $.ajax({
            type: "POST",
            url: "docBKOChkHavePdtForDocDTTemp",
            data: {
                'ptTWXDocNo'         : tTWXDocNo,
                'tTWXSesSessionID'   : $('#ohdSesSessionID').val(),
                'tTWXUsrCode'        : $('#ohdTWXUsrCode').val(),
                'tTWXLangEdit'       : $('#ohdTWXLangEdit').val(),
                'tSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
            },
            cache: false,
            timeout: 0,
            success: function (oResult){
                // JCNxCloseLoading();
                var aDataReturnChkTmp   = JSON.parse(oResult);
                $('.xWTWXDisabledOnApv').attr('disabled',false);
                if (aDataReturnChkTmp['nStaReturn'] == '1'){
                    $.ajax({
                        type    : "POST",
                        url     : $("#ohdTWXRoute").val(),
                        data    : $("#ofmTWXFormAdd").serialize(),
                        cache   : false,
                        timeout : 0,
                        success : function(oResult){
                            // JCNxCloseLoading();
                            var aDataReturnEvent    = JSON.parse(oResult);
                            if(aDataReturnEvent['nStaReturn'] == '1'){
                                var nTWXStaCallBack      = aDataReturnEvent['nStaCallBack'];
                                var nTWXDocNoCallBack    = aDataReturnEvent['tCodeReturn'];

                                var oTWXCallDataTableFile = {
                                    ptElementID : 'odvTWXShowDataTable',
                                    ptBchCode   : $('#oetTWXWahBrcCode').val(),
                                    ptDocNo     : nTWXDocNoCallBack,
                                    ptDocKey    :'TCNTPdtTwxHD',
                                }
                                JCNxUPFInsertDataFile(oTWXCallDataTableFile);
                                if(ptType == 'approve'){
                                    var tDocNo = $('#oetTWXDocNo').val();
                                    var tBchCode = $('#ohdTWXBchCode').val();
                                    var tRefInDocNo = $('#oetTWXRefDocIntName').val();
                                    $.ajax({
                                        type: "POST",
                                        url: "docBKOApproveDocument",
                                        data: {
                                            tDocNo: tDocNo,
                                            tBchCode: tBchCode,
                                            tRefInDocNo: tRefInDocNo
                                        },
                                        cache: false,
                                        timeout: 0,
                                        success: function(tResult) {
                                            $("#odvTWXModalAppoveDoc").modal("hide");
                                            $('.modal-backdrop').remove();
                                            var aReturnData = JSON.parse(tResult);
                                            if (aReturnData['nStaEvent'] == '1') {
                                                JSvTWXCallPageEdit(tDocNo);
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
                                    switch(nTWXStaCallBack){
                                        case '1' :
                                            JSvTWXCallPageEdit(nTWXDocNoCallBack);
                                        break;
                                        case '2' :
                                            JSvTWXCallPageAddDoc();
                                        break;
                                        case '3' :
                                            JSvTWXCallPageList();
                                        break;
                                        default :
                                            JSvTWXCallPageEdit(nTWXDocNoCallBack);
                                    }
                                }
                                $("#obtTWXSubmitFromDoc").removeAttr("disabled");
                            }else{
                                var tMessageError = aDataReturnEvent['tStaMessg'];
                                FSvCMNSetMsgErrorDialog(tMessageError);
                                $("#obtTWXSubmitFromDoc").removeAttr("disabled");
                            }
                        },
                        error   : function (jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                }else if(aDataReturnChkTmp['nStaReturn'] == '800'){
                    var tMsgDataTempFound   = aDataReturnChkTmp['tStaMessg'];
                    FSvCMNSetMsgWarningDialog('<p class="text-left">'+tMsgDataTempFound+'</p>');
                }else{
                    var tMsgErrorFunction   = aDataReturnChkTmp['tStaMessg'];
                    FSvCMNSetMsgErrorDialog('<p class="text-left">'+tMsgErrorFunction+'</p>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //นับจำนวนรายการท้ายเอกสาร
    function JSxTWXCountPdtItems(){
        var nPdtItems = $('.xWPdtItem').length;
        $('.xShowQtyFooter').text(accounting.formatNumber(nPdtItems, 0, ','));
    }

    $('#ocmTWXTypePayment').on('change', function() {
        if (this.value == 1) {
            $('.xCNPanel_CreditTerm').hide();
        } else {
            $('.xCNPanel_CreditTerm').show();
        }
    });

    //พิมพ์เอกสาร
    function JSxTWXPrintDoc(){
        var aInfor  = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tTWXBchCode); ?>'},
            {"DocCode"      : '<?=@$tTWXDocNo; ?>'},
            {"DocBchCode"   : '<?=@$tTWXBchCode;?>'}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_ALLReservation?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

    $('#obtTWXBrowseWahBch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oTWXBrowseBranchOption  = undefined;
            oTWXBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'oetTWXWahBrcCode',
                'tReturnInputName'  : 'oetTWXWahBrcName',
                'tNextFuncName'     : 'JSxNextFuncTWXBchClear',
                'aArgReturn'        : ['FTBchCode','FTBchName'],
            });
            JCNxBrowseData('oTWXBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtTWXBrowseWahFrm').unbind().click(function(){
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oTWXBrowseWahOption   = undefined;
            oTWXBrowseWahOption          = oWahOption({
                'tTWXBchCode'        : $('#oetTWXWahBrcCode').val(),
                'tReturnInputCode'  : 'oetTWXWahFrmCode',
                'tReturnInputName'  : 'oetTWXWahFrmName',
                'aArgReturn'        : []
            });
            JCNxBrowseData('oTWXBrowseWahOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtTWXBrowseWahBook').unbind().click(function(){
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oTWXBrowseWahOption   = undefined;
            oTWXBrowseWahOption          = oWahBookOption({
                'tTWXBchCode'        : $('#oetTWXWahBrcCode').val(),
                'tReturnInputCode'  : 'oetTWXWahBookCode',
                'tReturnInputName'  : 'oetTWXWahBookName',
                'aArgReturn'        : []
            });
            JCNxBrowseData('oTWXBrowseWahOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtTWXBrowseCrsCar').unbind().click(function(){
        var nStaSession = 1;
        var tCstCode = $('#oetTWXFrmCstCode').val();
        if(tCstCode == ''){
            $('#odvTWXModalPleseselectCSTCar').modal('show');
            return ;
        }
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oTWXBrowseCarOption   = undefined;
            oTWXBrowseCarOption          = oCarOption({
                'tTWXBchCode'        : $('#oetTWXWahBrcCode').val(),
                'tReturnInputCode'  : 'oetTWXCrscarCode',
                'tCstCode'          : tCstCode,
                'tReturnInputName'  : 'oetTWXcarCrsName',
                'tNextFuncName'     : 'JSxNextFuncTWXCarFill',
                'aArgReturn'        : ['FTCarRegNo']
            });
            JCNxBrowseData('oTWXBrowseCarOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtTWXBrowseAddress').unbind().click(function(){
        var nStaSession = 1;
        var tCstCode = $('#oetTWXFrmCstCode').val();
        if(tCstCode == ''){
            $('#odvTWXModalPleseselectCSTCar').modal('show');
            return ;
        }
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oTWXBrowseAddrOption   = undefined;
            oTWXBrowseAddrOption          = oAddrOption({
                'tTWXBchCode'        : $('#oetTWXWahBrcCode').val(),
                'tReturnInputCode'  : 'oetTWXCrscarCode2',
                'tCstCode'          : tCstCode,
                'tReturnInputName'  : 'oetTWXcarCrsName2',
                'tNextFuncName'     : 'JSxNextFuncTWXAddrFill',
                'aArgReturn'        : ['FTAddV2Desc1']
            });
            JCNxBrowseData('oTWXBrowseAddrOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oAddrOption    = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tCstCode            = poDataFnc.tCstCode;
        // var tPOBchCode          = poDataFnc.tPOBchCode;
        

        var oOptionReturn       = {
            Title   : ['document/bookingorder/bookingorder','tTWXCstAddress'],
            Table   : {Master:'TCNMCstAddress_L',PK:'FNAddSeqNo'},
            Join    : {
            Table   : ['TCNMProvince_L','TCNMDistrict_L','TCNMSubDistrict_L'],
                On  : [
                    "TCNMCstAddress_L.FTAddV1PvnCode = TCNMProvince_L.FTPvnCode AND TCNMProvince_L.FNLngID = "+nLangEdits,
                    "TCNMCstAddress_L.FTAddV1DstCode = TCNMDistrict_L.FTDstCode AND TCNMDistrict_L.FNLngID = "+nLangEdits,
                    "TCNMCstAddress_L.FTAddV1SubDist = TCNMSubDistrict_L.FTSudCode AND TCNMSubDistrict_L.FNLngID = "+nLangEdits
                ]
            },
            Where : {
                Condition : [" AND FTCstCode = '"+tCstCode+"' AND TCNMCstAddress_L.FNLngID = "+nLangEdits]
            },
            GrideView:{
                ColumnPathLang	: 'document/bookingorder/bookingorder',
                ColumnKeyLang	: [
                    'tTWXTable_cstcode',
                    'tTWXSeqno',
                    // 'tSOShipADDV1No',
                    // 'tSOShipADDV1Soi',
                    // 'tSOShipADDV1Village',
                    // 'tSOShipADDV1Road',
                    // 'tSOShipADDV1SubDist',
                    // 'tSOShipADDV1DstCode',
                    // 'tSOShipADDV1PvnCode',
                    'tTWXCstAddress'
                ],
                DataColumns		: [
                    'TCNMCstAddress_L.FTCstCode',
                    'TCNMCstAddress_L.FNAddSeqNo',
                    // 'TCNMCstAddress_L.FTAddV1No',
                    // 'TCNMCstAddress_L.FTAddV1Soi',
                    // 'TCNMCstAddress_L.FTAddV1Village',
                    // 'TCNMCstAddress_L.FTAddV1Road',
                    // 'TCNMSubDistrict_L.FTSudName',
                    // 'TCNMDistrict_L.FTDstName',
                    // 'TCNMProvince_L.FTPvnName',
                    // 'TCNMCstAddress_L.FTAddV1PostCode',
                    // 'TCNMCstAddress_L.FTAddTel',
                    // 'TCNMCstAddress_L.FTAddFax',
                    'TCNMCstAddress_L.FTAddV2Desc1'
                ],
                DataColumnsFormat   : ['','','','','','','','','','','','','',''],
                DisabledColumns     : [],
                ColumnsSize         : [''],
                Perpage			    : 10,
                WidthModal          : 50,
                OrderBy			    : ['TCNMCstAddress_L.FTCstCode ASC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMCstAddress_L.FNAddSeqNo"],
                Text		: [tInputReturnName,"TCNMCstAddress_L.FNAddSeqNo"],
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            }
        }
        return oOptionReturn;
    };

    var oCarOption      = function(poDataFnc){
        var tTWXBchCode         = poDataFnc.tTWXBchCode;
        var tCstCode            = poDataFnc.tCstCode;
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tNextFuncName       = poDataFnc.tNextFuncName;

        var oOptionReturn   = {
            Title: ["document/bookingorder/bookingorder","tTWXCstCar"],
            Table: { Master:"TSVMCar", PK:"FTCarCode"},
            Join: {
                Table: ["TSVMCarInfo_L","TCNMCst_L"],
                On: ["TSVMCar.FTCarBrand = TSVMCarInfo_L.FTCaiCode AND TSVMCarInfo_L.FNLngID = '"+nLangEdits+"'",
                    "TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = "+nLangEdits]
            },
            Where: {
                Condition : ["AND TSVMCar.FTCarOwner = '"+tCstCode+"'"]
            },
            GrideView:{
                ColumnPathLang: 'document/bookingorder/bookingorder',
                ColumnKeyLang: ['tTWXCstCarCode','tTWXCstCarRegNo','tTWXCstCarBrand','tTWXCstCarOwner'],
                DataColumns: ['TSVMCar.FTCarCode','TSVMCar.FTCarRegNo','TSVMCarInfo_L.FTCaiName', 'TCNMCst_L.FTCstName'],
                DataColumnsFormat: ['','','',''],
                ColumnsSize: ['15%','15%','35%','35%'],
                Perpage: 10,
                WidthModal: 50,
                OrderBy: ['TSVMCar.FTCarCode ASC'],
            },
            NextFunc:{
                FuncName    :tNextFuncName,
                ArgReturn   : aArgReturn
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TSVMCar.FTCarModel"],
                Text        : [tInputReturnName,"TSVMCarInfo_L.FTCaiName"]
            },
            RouteAddNew: 'car'
        }
        return oOptionReturn;
    }

    var oWahOption      = function(poDataFnc){
        var tTWXBchCode         = poDataFnc.tTWXBchCode;
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var aArgReturn          = poDataFnc.aArgReturn;

        var oOptionReturn   = {
            Title: ["company/warehouse/warehouse","tWAHTitle"],
            Table: { Master:"TCNMWaHouse", PK:"FTWahCode"},
            Join: {
                Table: ["TCNMWaHouse_L"],
                On: ["TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse.FTBchCode=TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = '"+nLangEdits+"'"]
            },
            Where: {
                Condition : [" AND (TCNMWaHouse.FTWahStaType IN (1,2,3,4,5,6) AND  TCNMWaHouse.FTBchCode='"+tTWXBchCode+"')"]
            },
            GrideView:{
                ColumnPathLang: 'company/warehouse/warehouse',
                ColumnKeyLang: ['tWahCode','tWahName'],
                DataColumns: ['TCNMWaHouse.FTWahCode','TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat: ['',''],
                ColumnsSize: ['15%','75%'],
                Perpage: 5,
                WidthModal: 50,
                OrderBy: ['TCNMWaHouse_L.FTWahName ASC'],
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TCNMWaHouse.FTWahCode"],
                Text        : [tInputReturnName,"TCNMWaHouse_L.FTWahName"]
            },
            RouteAddNew: 'warehouse'
        }
        return oOptionReturn;
    }

    var oWahBookOption      = function(poDataFnc){
        var tTWXBchCode         = poDataFnc.tTWXBchCode;
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var aArgReturn          = poDataFnc.aArgReturn;

        var oOptionReturn   = {
            Title: ["company/warehouse/warehouse","tWAHTitle"],
            Table: { Master:"TCNMWaHouse", PK:"FTWahCode"},
            Join: {
                Table: ["TCNMWaHouse_L"],
                On: ["TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse.FTBchCode=TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = '"+nLangEdits+"'"]
            },
            Where: {
                Condition : [" AND (TCNMWaHouse.FTWahStaType IN (7) AND  TCNMWaHouse.FTBchCode='"+tTWXBchCode+"')"]
            },
            GrideView:{
                ColumnPathLang: 'company/warehouse/warehouse',
                ColumnKeyLang: ['tWahCode','tWahName'],
                DataColumns: ['TCNMWaHouse.FTWahCode','TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat: ['',''],
                ColumnsSize: ['15%','75%'],
                Perpage: 5,
                WidthModal: 50,
                OrderBy: ['TCNMWaHouse_L.FTWahName ASC'],
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TCNMWaHouse.FTWahCode"],
                Text        : [tInputReturnName,"TCNMWaHouse_L.FTWahName"]
            },
            RouteAddNew: 'warehouse'
        }
        return oOptionReturn;
    }

    var oBranchOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName    = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tBchCodeLock          = poDataFnc.tBchCodeLock;

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if(tUsrLevel != "HQ"){
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+")";
        }

        var oOptionReturn       = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master  : 'TCNMBranch',
                PK      : 'FTBchCode'
            },
            Join: {
                Table   : ['TCNMBranch_L','TCNMWaHouse_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                            'TCNMBranch.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMBranch.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID ='+nLangEdits,]
            },
            Where : {
                Condition : [tSQLWhereBch,tSQLWhereAgn]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName','TCNMWaHouse_L.FTWahCode','TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat   : ['', ''],
                DisabledColumns   : [2,3],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode'],
                SourceOrder         : "ASC"
            },
            NextFunc:{
                FuncName:tNextFuncName
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

    function JSxNextFuncTWXBchClear() {
        $('#oetTWXWahFrmCode').val('');
        $('#oetTWXWahFrmName').val('');
        $('#oetTWXWahBookCode').val('');
        $('#oetTWXWahBookName').val('');
    }

    function JSxNextFuncTWXCarFill(poDataNextFunc) {
        var ojson = poDataNextFunc;
        var aDataNextfunc   = JSON.parse(ojson);
        $('#oetTWXPanel_CustomerCarRegNo').val(aDataNextfunc[0]);
    }

    function JSxNextFuncTWXAddrFill(poDataNextFunc) {
        var ojson = poDataNextFunc;
        var aDataNextfunc   = JSON.parse(ojson);
        $('#oetTWXPanel_CustomerAddress').val(aDataNextfunc[0]);
    }


</script>