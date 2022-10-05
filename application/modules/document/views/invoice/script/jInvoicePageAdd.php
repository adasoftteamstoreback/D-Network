<script>
    $('.selectpicker').selectpicker('refresh');
    $("#obtIVSubmitFromDoc").removeAttr("disabled");

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

    /** =================== Event Date Function  ====================== */
    $('#obtIVRefIntDate').unbind().click(function(){
            $('#oetIVRefIntDate').datepicker('show');
        });

        $('#obtIVRefSBIntDate').unbind().click(function(){
            $('#oetIVRefSBIntDate').datepicker('show');
        });

        $('#obtIVDocDate').unbind().click(function(){
            $('#oetIVDocDate').datepicker('show');
        });

        $('#obtIVDocTime').unbind().click(function(){
            $('#oetIVDocTime').datetimepicker('show');
        });

        $('#obtIVEffectiveDate').unbind().click(function(){
            $('#oetIVEffectiveDate').datepicker('show');
        });

        $('#obtIVTnfDate').unbind().click(function(){
            $('#oetIVTnfDate').datepicker('show');
        });
    /** =============================================================== */

    //////////////////////////////////////////////////////////////// เลือกผู้จำหน่าย /////////////////////////////////////////////////////////////
    $('#obtIVBrowseSPL').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIVBrowseSPLOption   = undefined;
            oIVBrowseSPLOption          = oSPLOption({
                'tParamsAgnCode'    : '<?=$this->session->userdata("tSesUsrAgnCode")?>',
                'tReturnInputCode'  : 'ohdIVSPLCode',
                'tReturnInputName'  : 'oetIVSPLName',
                'tNextFuncName'     : 'JSxIVSetConditionAfterSelectSPL',
                'aArgReturn'        : ['FNSplCrTerm', 'FCSplCrLimit', 'FTSplStaVATInOrEx', 'FTSplTspPaid', 'FTSplCode', 'FTSplName','FTSplStaLocal']
            });
            JCNxBrowseData('oIVBrowseSPLOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oSPLOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tParamsAgnCode      = poDataFnc.tParamsAgnCode;

        var tWhereAgency = '';
        if( tParamsAgnCode != "" ){
            tWhereAgency = " AND ( TCNMSpl.FTAgnCode = '"+tParamsAgnCode+"' OR ISNULL(TCNMSpl.FTAgnCode,'') = '' ) ";
        }else {
            tWhereAgency ="";
        }

        var oOptionReturn       = {
            Title   : ['supplier/supplier/supplier', 'tSPLTitle'],
            Table   : {Master:'TCNMSpl', PK:'FTSplCode'},
            Join    : {
                Table: ['TCNMSpl_L', 'TCNMSplCredit'],
                On: [
                    'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
                    'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode'
                ]
            },
            Where:{
                Condition : [ " AND TCNMSpl.FTSplStaActive = '1' " + tWhereAgency ]
            },
            GrideView:{
                ColumnPathLang      : 'supplier/supplier/supplier',
                ColumnKeyLang       : ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid' , 'TCNMSpl.FTSplStaLocal'],
                DataColumnsFormat   : ['',''],
                DisabledColumns     : [2, 3, 4, 5 , 6 , 7 , 8],
                Perpage             : 10,
                OrderBy             : ['TCNMSpl.FDCreateOn DESC']
            },
            CallBack:{
                ReturnType          : 'S',
                Value               : ['DEMO',"TCNMSpl.FTSplCode"],
                Text                : ['DEMO',"TCNMSpl_L.FTSplName"]
            },
            NextFunc:{
                FuncName            : tNextFuncName,
                ArgReturn           : aArgReturn
            }
        };
        return oOptionReturn;
    }

    //หลังจากเลือกผู้จำหน่าย
    function JSxIVSetConditionAfterSelectSPL(poDataNextFunc){
        var aData;
        if (poDataNextFunc  != "NULL") {

            //ถ้าเปลี่ยนผู้จำหน่าย แล้วมีตารางสินค้ามากกว่า 1 
            var tCheckClassNotFound = $("#otbIVDocPdtAdvTableList tbody tr td").hasClass('xCNTextNotfoundDataPdtTable');
            if(tCheckClassNotFound == true){

                //รายละเอียดผู้จำหน่าย
                JSxIVAsignValueInSPL(poDataNextFunc);
            }else{ 
                $('#odvIVPopupChangeSPL').modal('show');
                $('#odvIVPopupChangeSPL .xCNIVPopupChangeSPLAgain').unbind().click(function() { //กดยืนยันที่จะเปลี่ยน
                    $.ajax({
                        type    : "POST",
                        url     : "docInvoiceClearTemp",
                        data    : {},
                        cache   : false,
                        Timeout : 0,
                        success: function (oResult) {
                            //โหลดตารางสินค้าอีกครั้ง
                            JSvIVLoadPdtDataTableHtml();
                            FSxIVCallPageHDDocRef();
                            $('#oetIVRefInt').val('');
                            $('#oetIVRefIntDate').val('');
                            $('#oetIVRefIntName').val('');

                            //รายละเอียดผู้จำหน่าย
                            JSxIVAsignValueInSPL(poDataNextFunc);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                });
            }

        }
    }

    //รายละเอียดผู้จำหน่าย
    function JSxIVAsignValueInSPL(poDataNextFunc){
        aData = JSON.parse(poDataNextFunc);

        $('#oetIVRefSBIntDate').val('');
        $('#oetIVEffectiveDate').val('');
        $('#oetIVRefSBInt').val('');
        
        
        //รายละเอียดผู้จำหน่าย
        $('#ohdIVSPLCode').val(aData[4]);
        $('#oetIVSPLName').val(aData[5]);

        //ชื่อผู้จำหน่าย
        $('#oetPanel_SplName').val((aData[5] == '') ? '-' : aData[5]);

        // Vat จาก SPL
        // $('#ohdIVFrmSplVatCode').val(aData[6]);
        // $('#ohdIVFrmSplVatRate').val(aData[7]);

        // ประเภทภาษี
        if(aData[2] === "1"){
            // รวมใน
            $("#ocmIVfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
        }else{
            // แยกนอก
            $("#ocmIVfoVatInOrEx.selectpicker").val("2").selectpicker("refresh");
        }

        // ประเภทชำระเงิน
        if(aData[0] > 0){
            // เงินเชื่อ
            $("#ocmIVPaymentType.selectpicker").val("2").selectpicker("refresh");
            $('.xCNPanel_CreditTerm').show();
        }else{
            // เงินสด
            $("#ocmIVPaymentType.selectpicker").val("1").selectpicker("refresh");
            $('.xCNPanel_CreditTerm').hide();
        }

        // ระยะเครดิต
        $("#oetIVCreditTerm").val(aData[0]);
        var ncreditdate = aData[0];

        // if($("#oetIVRefSBIntDate").val() == ''){
        //     var dDatenow=new Date();
        // }else{
        //     var dDatenow=new Date($("#oetIVRefSBIntDate").val());
        // }
        // var ddatewithterm = new Date(+dDatenow + ncreditdate *86400000);
        // ddatewithterm = ddatewithterm.toISOString().substring(0, 10);
        // $("#oetIVEffectiveDate").val(ddatewithterm);

        // การชำระเงิน
        if(aData[3] === "1"){ // ต้นทาง
            $("#ocmIVDstPaid.selectpicker").val("1").selectpicker("refresh");
        }else{ // ปลายทาง
            $("#ocmIVDstPaid.selectpicker").val("2").selectpicker("refresh");
        }

        //สถานะผู้จำหน่าย local
        $('#ohdIVSPLStaLocal').val(aData[6]);
        $("#ocbIVRefDoc").empty()
        // $("#ocbIVRefDoc option").each(function() {
        //     $(this).remove();
        // });

        if(aData[6] == '1'){
            $("#ocbIVRefDoc").append('<option value="1" selected>ใบรับของ</option>');
            $("#ocbIVRefDoc.selectpicker").selectpicker("refresh");
            $('#ocbIVRefDoc').selectpicker('val', '1');
        }else{
            $("#ocbIVRefDoc").append('<option value="2" >ใบสั่งซื้อ</option>');
            $("#ocbIVRefDoc").append('<option value="3" >ใบขาย</option>');
            $("#ocbIVRefDoc.selectpicker").selectpicker("refresh");
            $('#ocbIVRefDoc').selectpicker('val', '2');
        }
    }

    $("#oetIVRefSBIntDate").change(function () {
        var nCreditTerm = $("#oetIVCreditTerm").val();
        var dDatenow = new Date($("#oetIVRefSBIntDate").val());
        var ddatewithterm = new Date(+dDatenow + nCreditTerm *86400000);
        ddatewithterm = ddatewithterm.toISOString().substring(0, 10);
        if(nCreditTerm != ''){
            $("#oetIVEffectiveDate").val(ddatewithterm);
        }
    })

    // $("#oetIVCreditTerm").change(function () {
    //     var nCreditTerm = $("#oetIVCreditTerm").val();
    //     if($("#oetIVRefSBIntDate").val() == ''){
    //         var dDatenow=new Date();
    //     }else{
    //         var dDatenow=new Date($("#oetIVRefSBIntDate").val());
    //     }
    //     var ddatewithterm = new Date(+dDatenow + nCreditTerm *86400000);
    //     ddatewithterm = ddatewithterm.toISOString().substring(0, 10);
    //     $("#oetIVEffectiveDate").val(ddatewithterm);
    // })
    

    //////////////////////////////////////////////////////////////// เลือกสกุลเงิน ///////////////////////////////////////////////////////////////
    $('#obtIVBrowseRate').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIVBrowseRateOption   = undefined;
            oIVBrowseRateOption          = oRateOption({
                'tReturnInputCode'  : 'ohdIVRateCode',
                'tReturnInputName'  : 'ohdIVRateName'
            });
            JCNxBrowseData('oIVBrowseRateOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oRateOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var oOptionReturn       = {
            Title   : ['payment/rate/rate','tRTETitle'],
            Table   : {Master:'TFNMRate',PK:'FTRteCode'},
            Join    : {
                Table : ['TFNMRate_L'],
                On : ['TFNMRate_L.FTRteCode = TFNMRate.FTRteCode AND TFNMRate_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'payment/rate/rate',
                ColumnKeyLang	: ['tRTETBRteCode','tRTETBRteName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TFNMRate.FTRteCode','TFNMRate_L.FTRteName'],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TFNMRate.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TFNMRate.FTRteCode"],
                Text		: [tInputReturnName,"TFNMRate_L.FTRteName"],
            },
        };
        return oOptionReturn;
    }

    //////////////////////////////////////////////////////////////// เลือกตัวแทนขาย ////////////////////////////////////////////////////////////
    $('#obtIVBrowseAgency').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIVBrowseAgnOption   = undefined;
            oIVBrowseAgnOption          = oAgnOption({
                'tReturnInputCode'  : 'ohdIVADCode',
                'tReturnInputName'  : 'ohdIVADName',
                'tNextFuncName'     : 'JSxIVSetConditionAfterSelectAGN'
            });
            JCNxBrowseData('oIVBrowseAgnOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oAgnOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var tUsrLevSession      = '<?=$this->session->userdata("tSesUsrLevel"); ?>';
        var tWhereAgn           = '';

        var oOptionReturn  = {
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
        };
        return oOptionReturn;
    }

    //หลังจากเลือก
    function JSxIVSetConditionAfterSelectAGN(poDataNextFunc){
        var aData;
        if (poDataNextFunc  != "NULL") {
            $('#ohdIVBchCode , #oetIVBchName').val('');
            $('#ohdIVWahCode , #oetIVWahName').val('');
        }
    }

    //////////////////////////////////////////////////////////////// เลือกสาขา ///////////////////////////////////////////////////////////////// 
    $('#obtIVBrowseBranch').click(function(){ 
        var tChkTaxAdd = $('#ohdIVFrmTaxAdd').val();

        //ถ้ามีที่อยู่เเล้ว จะต้องมี popup ให้แจ้งเตือนว่า ยืนยันจะเปลี่ยนสาขาไหม
        if(tChkTaxAdd != ''){
            $('#odvIVModalAddressRemove').modal('show');
            $('#odvIVModalAddressRemove #osmConfirmRemoveAddress').unbind().click(function(){
                JSxIVClearAddr();
                
                setTimeout(function(){ 
                    JSxIVBrowseBranch();
                }, 700);
            });
        }else{
            JSxIVBrowseBranch();
        }
    });

    //เลือกสาขา
    function JSxIVBrowseBranch(){
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode = $('#ohdIVADCode').val();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIVBrowseBranchOption  = undefined;
            oIVBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'ohdIVBchCode',
                'tReturnInputName'  : 'oetIVBchName',
                'tAgnCode'          : tAgnCode,
                'tNextFuncName'     : 'JSxIVSetConditionAfterSelectBCH'
            });
            JCNxBrowseData('oIVBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //ยืนยันลบที่อยู่
    function JSxIVClearAddr(){

        $('#ohdIVFrmShipAdd').val('');
        $('#ohdIVFrmTaxAdd').val('');

        $('#odvIVModalAddressRemove').modal('hide');

    }  

    var oBranchOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var tAgnCode            = poDataFnc.tAgnCode;
        var tSQLWhere           = "";
        var tSQLWhereAgn        = "";

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";

        if(tUsrLevel != "HQ"){ //แบบสาขา
            tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }else{  //สำนักงานใหญ่
            if($('#ohdIVADCode').val() == '' || $('#ohdIVADCode').val() == null){
                tSQLWhere += "";
            }else{
                tSQLWhere += " AND (TCNMBranch.FTAgnCode = " + $('#ohdIVADCode').val() +" OR ISNULL(TCNMBranch.FTAgnCode,'') = '' )";
            }
        }

        // ปิดเพราะ Error ในหน้าจอใบซื้อสินค้า ตอน Browse เอกสารอ้างอิง แล้วกด Filter เลือกสาขา
        // if(tAgnCode != ""){
        //     tSQLWhereAgn = " AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
        // }
        
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
                Condition : [tSQLWhere,tSQLWhereAgn]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['',''],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode'],
                SourceOrder         : "ASC"
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

    //หลังจากเลือกสาขา
    function JSxIVSetConditionAfterSelectBCH(poDataNextFunc){
        var aData;
        if (poDataNextFunc != "NULL") {
            $('#ohdIVWahCode , #oetIVWahName').val('');
        }
    }

    //////////////////////////////////////////////////////////////// เลือกคลังสินค้า ///////////////////////////////////////////////////////////////
    $('#obtIVBrowseWah').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oIVBrowseWahOption   = undefined;
            oIVBrowseWahOption          = oWahOption({
                'tReturnInputCode'  : 'ohdIVWahCode',
                'tReturnInputName'  : 'oetIVWahName'
            });
            JCNxBrowseData('oIVBrowseWahOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oWahOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tWhereModal         = '';

        if($('#ohdIVBchCode').val() != ""){
            tWhereModal += " AND TCNMWaHouse.FTBchCode='"+$('#ohdIVBchCode').val()+"' ";
        }

        var oOptionReturn   = {
            Title       : ["company/warehouse/warehouse","tWAHTitle"],
            Table       : { Master:"TCNMWaHouse", PK:"FTWahCode"},
            Join        : {
                Table   : ["TCNMWaHouse_L"],
                On      :  [
                    " TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = '"+nLangEdits+"' ",
                ]
            },
            Where: {
                Condition : [tWhereModal]
            },
            GrideView:{
                ColumnPathLang      : 'company/warehouse/warehouse',
                ColumnKeyLang       : ['tWahCode','tWahName'],
                DataColumns         : ['TCNMWaHouse.FTWahCode','TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat   : ['',''],
                ColumnsSize         : ['15%','75%'],
                Perpage             : 10,
                WidthModal          : 50,
                OrderBy             : ['TCNMWaHouse.FTWahCode'],
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TCNMWaHouse.FTWahCode"],
                Text        : [tInputReturnName,"TCNMWaHouse_L.FTWahName"]
            }
        }
        return oOptionReturn;
    }

    //////////////////////////////////////////////////////////////// เลือกที่อยู่ขนส่ง + เลือกที่อยู่จัดภาษี //////////////////////////////////////////////

    var nKeepBrowseAddrOption   = '';
    $('#obtIVFrmBrowseAddrAdd , #obtIVFrmBrowseTaxAdd').click(function(){
        window.oIVBrowseAddrOption   = undefined;
        var nCodeAddr = $(this).attr('data-codebrowse');
        if(nCodeAddr == 1){ //ที่อยู่สำหรับจัดส่ง
            nKeepBrowseAddrOption = 1;
        }else if(nCodeAddr == 2){ //ที่อยู่ใบกำกับภาษี
            nKeepBrowseAddrOption = 2;
        }

        $('#odvIVModalAddress').modal('show');

        //ถ้าเอกสารบึนทึกข้อมูลแล้ว
        if($('#oetIVDocNo').val() != '' || $('#oetIVDocNo').val() != null){
            //เอาที่อยู่ของมีข้อมูลมาเเล้วมาโชว์
            JSxSetAddrInInput(nKeepBrowseAddrOption);
        }
    });

    //เอาที่อยู่ของมีข้อมูลมาเเล้วมาโชว์
    function JSxSetAddrInInput(pnKeepBrowseAddrOption){
        if(pnKeepBrowseAddrOption == 1){
            //ที่อยู่สำหรับจัดส่ง
            var tAddr = 'Ship';
        }else{
            //ที่อยู่ออกใบกำกับภาษี
            var tAddr = 'Tax';
        }

        var tFNAddSeqNo        = $('#ohdIV'+tAddr+'AddSeqNo').val();
        var tFTAddV1No         = $('#ohdIV'+tAddr+'AddV1No').val();
        var tFTAddV1Soi        = $('#ohdIV'+tAddr+'V1Soi').val();
        var tFTAddV1Village    = $('#ohdIV'+tAddr+'V1Village').val();
        var tFTAddV1Road       = $('#ohdIV'+tAddr+'V1Road').val();
        var tFTSudName         = $('#ohdIV'+tAddr+'V1SubDistrict').val();
        var tFTDstName         = $('#ohdIV'+tAddr+'V1District').val();
        var tFTPvnName         = $('#ohdIV'+tAddr+'V1Province').val();
        var tFTAddV1PostCode   = $('#ohdIV'+tAddr+'V1PostCode').val();
        var tFTAddTel          = $('#ohdIV'+tAddr+'Tel').val();
        var tFTAddFax          = $('#ohdIV'+tAddr+'Fax').val();
        var tFTAddTaxNo        = $('#ohdIV'+tAddr+'AddTaxNo').val();

        var tFTAddV2Desc1      = $('#ohdIV'+tAddr+'AddV2Desc1').val();
        var tFTAddV2Desc2      = $('#ohdIV'+tAddr+'AddV2Desc2').val();
        var tFTAddName         = $('#ohdIV'+tAddr+'AddName').val();

        //โชว์ค่า
        $('#ohdIVAddrCode').val(tFNAddSeqNo)
        $('#ohdIVAddrName').val(tFTAddName)
        $('#ohdIVAddrTaxNo').val(tFTAddTaxNo);
        $('#ohdIVAddrNoHouse').val(tFTAddV1No)
        $('#ohdIVAddrVillage').val(tFTAddV1Village)
        $('#ohdIVAddrRoad').val(tFTAddV1Road)
        $('#ohdIVAddrSubDistrict').val(tFTSudName)
        $('#ohdIVAddrDistict').val(tFTDstName)
        $('#ohdIVAddrProvince').val(tFTPvnName)
        $('#ohdIVZipCode').val(tFTAddV1PostCode)
        $('#ohdIVAddrTel').val(tFTAddTel)
        $('#ohdIVAddrFax').val(tFTAddFax)

        $('#ohdIVAddV2Desc1').val(tFTAddV2Desc1);
        $('#ohdIVAddV2Desc2').val(tFTAddV2Desc2);
    }

    //เลือกที่อยู่
    $('#obtIVBrowseAddr').click(function(){ 
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();

            //ปิด modal ที่อยู่
            // setTimeout(function(){ $('#odvIVModalAddress').modal('hide'); }, 1000);
            $('#odvIVModalAddress').modal('hide');

            setTimeout(function(){
                oIVBrowseAddrOption         = oAddrOption({
                        'nStaShwAddress'    : <?=$nStaShwAddress?>,
                        'tReturnInputCode'  : 'ohdIVAddrCode',
                        'tReturnInputName'  : 'ohdIVAddrCode',
                        'nKeepBrowseAddr'   : nKeepBrowseAddrOption,
                        'tNextFuncName'     : 'JSxIVSetConditionAfterSelectAddr',
                        'aArgReturn'        : [ 'FNAddSeqNo'    ,'FTAddV1No'    ,'FTAddV1Soi' ,
                                                'FTAddV1Village','FTAddV1Road'  ,'FTSudName' ,
                                                'FTDstName'     ,'FTPvnName'    ,'FTAddV1PostCode' ,
                                                'FTAddTel'      ,'FTAddFax'     ,'FTAddTaxNo' ,
                                                'FTAddV2Desc1'  ,'FTAddV2Desc2' , 'FTAddName'
                                            ]
                });
                JCNxBrowseData('oIVBrowseAddrOption');
            }, 500);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oAddrOption    = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var nKeepBrowseAddr     = poDataFnc.nKeepBrowseAddr;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var nStaShwAddress      = poDataFnc.nStaShwAddress;

        //เซตชื่อของ title
        if(nKeepBrowseAddr == 1){
            var tLangTitleName = 'tPILabelFrmSplInfoShipAddress';
        }else if(nKeepBrowseAddr == 2){
            var tLangTitleName = 'tPILabelFrmSplInfoTaxAddress';
        }

        if( nStaShwAddress == 1 ){
            var oDisabledColumns = [3,11,12,13,14,15];
            var oColumnKeyLang = ['tSOShipADDBch','ชื่อที่อยู่','เลขที่ใบกำกับภาษี','','tSOShipADDV1No','tSOShipADDV1Soi',
                                  'tSOShipADDV1Village','tSOShipADDV1Road','tSOShipADDV1SubDist','tSOShipADDV1DstCode',
                                  'tSOShipADDV1PvnCode','tSOShipADDV1PostCode'];
        }else{
            var oDisabledColumns = [3,4,5,6,7,8,9,10,13,14,15];
            var oColumnKeyLang = ['tSOShipADDBch','ชื่อที่อยู่','เลขที่ใบกำกับภาษี','','','','','','','','','ที่อยู่ 1','ที่อยู่ 2'];
        }

        var oOptionReturn       = {
            Title   : ['document/purchaseinvoice/purchaseinvoice',tLangTitleName],
            Table   : {Master:'TCNMAddress_L',PK:'FNAddSeqNo'},
            Join    : {
            Table   : ['TCNMProvince_L','TCNMDistrict_L','TCNMSubDistrict_L'],
                On  : [
                    "TCNMAddress_L.FTAddV1PvnCode = TCNMProvince_L.FTPvnCode AND TCNMProvince_L.FNLngID = "+nLangEdits,
                    "TCNMAddress_L.FTAddV1DstCode = TCNMDistrict_L.FTDstCode AND TCNMDistrict_L.FNLngID = "+nLangEdits,
                    "TCNMAddress_L.FTAddV1SubDist = TCNMSubDistrict_L.FTSudCode AND TCNMSubDistrict_L.FNLngID = "+nLangEdits
                ]
            },
            Where : {
                Condition : [" AND FTAddGrpType = 1 AND FTAddRefCode = '"+$("#ohdIVBchCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits+ " AND TCNMAddress_L.FTAddVersion = '"+nStaShwAddress+"' "]
            },
            GrideView:{
                ColumnPathLang	: 'document/saleorder/saleorder',
                ColumnKeyLang	: oColumnKeyLang,
                DataColumns		: [ 'TCNMAddress_L.FTAddRefCode','TCNMAddress_L.FTAddName','TCNMAddress_L.FTAddTaxNo',
                            
                                    'TCNMAddress_L.FNAddSeqNo',
                                    'TCNMAddress_L.FTAddV1No','TCNMAddress_L.FTAddV1Soi','TCNMAddress_L.FTAddV1Village',
                                    'TCNMAddress_L.FTAddV1Road','TCNMSubDistrict_L.FTSudName','TCNMDistrict_L.FTDstName',
                                    'TCNMProvince_L.FTPvnName',
                            
                                    'TCNMAddress_L.FTAddV2Desc1','TCNMAddress_L.FTAddV2Desc2',

                                    'TCNMAddress_L.FTAddV1PostCode','TCNMAddress_L.FTAddTel',
                                    'TCNMAddress_L.FTAddFax',
                                  ],
                DataColumnsFormat   : ['','','','','','','','','','','','','',''],
                DisabledColumns     : oDisabledColumns,
                ColumnsSize         : [''],
                Perpage			    : 10,
                WidthModal          : 50,
                OrderBy			    : ['TCNMAddress_L.FTAddRefCode ASC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMAddress_L.FNAddSeqNo"],
                Text		: [tInputReturnName,"TCNMAddress_L.FNAddSeqNo"],
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            },
            // DebugSQL: true
        }
        return oOptionReturn;
    };

    //หลังจากเลือกที่อยู่
    function JSxIVSetConditionAfterSelectAddr(poDataNextFunc){
        var aData;
        if (poDataNextFunc != "NULL") {
            var aData = JSON.parse(poDataNextFunc);
            console.log(aData);

            //โชว์ค่า
            $('#ohdIVAddrCode').val(aData[0]);
            $('#ohdIVAddrName').val(aData[14]);
            $('#ohdIVAddrTaxNo').val(aData[11]);
            $('#ohdIVAddrNoHouse').val(aData[1]);
            $('#ohdIVAddrVillage').val(aData[3]);
            $('#ohdIVAddrRoad').val(aData[4]);
            $('#ohdIVAddrSubDistrict').val(aData[5]);
            $('#ohdIVAddrDistict').val(aData[6]);
            $('#ohdIVAddrProvince').val(aData[7]);
            $('#ohdIVZipCode').val(aData[8]);
            $('#ohdIVAddrTel').val(aData[9]);
            $('#ohdIVAddrFax').val(aData[10]);

            $('#ohdIVAddV2Desc1').val(aData[12]);
            $('#ohdIVAddV2Desc2').val(aData[13]);

        }else{
            $('#ohdIVAddrCode').val('');
            $('#ohdIVAddrName').val('');
            $('#ohdIVAddrTaxNo').val('');
            $('#ohdIVAddrNoHouse').val('');
            $('#ohdIVAddrVillage').val('');
            $('#ohdIVAddrRoad').val('');
            $('#ohdIVAddrSubDistrict').val('');
            $('#ohdIVAddrDistict').val('');
            $('#ohdIVAddrProvince').val('');
            $('#ohdIVZipCode').val('');
            $('#ohdIVAddrTel').val('');
            $('#ohdIVAddrFax').val('');

            $('#ohdIVAddV2Desc1').val('');
            $('#ohdIVAddV2Desc2').val('');
        }

        setTimeout(function(){ $('#odvIVModalAddress').modal('show'); }, 500);
    }

    //กดยืนยันที่อยู่
    function JSxConfirmAddress(){

        var nAddSeqNo       = $('#ohdIVAddrCode').val();
        var tAddName        = $('#ohdIVAddrName').val();
        var tTaxNo          = $('#ohdIVAddrTaxNo').val();
        var tHouseNumber    = $('#ohdIVAddrNoHouse').val();
        var tVillage        = $('#ohdIVAddrVillage').val();
        var tRoad           = $('#ohdIVAddrRoad').val();
        var tPostCode       = $('#ohdIVZipCode').val();
        var tSubDistrict    = $('#ohdIVAddrSubDistrict').val();
        var tDistict        = $('#ohdIVAddrDistict').val();
        var tProvince       = $('#ohdIVAddrProvince').val();
        var tDesc1          = $('#ohdIVAddV2Desc1').val();
        var tDesc2          = $('#ohdIVAddV2Desc2').val();
        var tTel            = $('#ohdIVAddrTel').val();
        var tFax            = $('#ohdIVAddrFax').val();

        //เซตค่า
        if(nKeepBrowseAddrOption == 1){
            var tType = "Ship";
        }else if(nKeepBrowseAddrOption == 2){
            var tType = "Tax";
        }

        $('#ohdIVFrm'+tType+'Add').val(nAddSeqNo);

        $('#ohdIV'+tType+'AddSeqNo').val(nAddSeqNo);
        $('#ohdIV'+tType+'AddV1No').val(tHouseNumber);
        $('#ohdIV'+tType+'V1Soi').val();
        $('#ohdIV'+tType+'V1Village').val(tVillage);
        $('#ohdIV'+tType+'V1Road').val(tRoad);
        $('#ohdIV'+tType+'V1SubDistrict').val(tSubDistrict);
        $('#ohdIV'+tType+'V1District').val(tDistict);
        $('#ohdIV'+tType+'V1Province').val(tProvince);
        $('#ohdIV'+tType+'V1PostCode').val(tPostCode);
        $('#ohdIV'+tType+'Tel').val(tTel);
        $('#ohdIV'+tType+'Fax').val(tFax);
        $('#ohdIV'+tType+'AddTaxNo').val(tTaxNo);

        $('#ohdIV'+tType+'AddV2Desc1').val(tDesc1);
        $('#ohdIV'+tType+'AddV2Desc2').val(tDesc2);
        $('#ohdIV'+tType+'AddName').val(tAddName);

    }

    //////////////////////////////////////////////////////////////// ค้นหาสินค้าใน ///////////////////////////////////////////////////////////////

    //ค้นหาสินค้าใน temp
    function JSvDOCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbIVDocPdtAdvTableList tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    //////////////////////////////////////////////////////////////// เลือกสินค้า ////////////////////////////////////////////////////////////////// 

    //สแกนบาร์โค๊ด
    function JSxSearchFromBarcode(e,elem){
        var tValue = $(elem).val();
        if($('#ohdIVCustomerCode').val() != ""){
            JSxCheckPinMenuClose();
            if(tValue.length === 0){

            }else{
                $('#oetIVInsertBarcode').attr('readonly',true);
                JCNSearchBarcodePdt(tValue);
                $('#oetIVInsertBarcode').val('');
            }
        }else{
            $('#odvIVModalPleseSelectSPL').modal('show');
            $('#oetIVInsertBarcode').val('');
        }
        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
	function JCNSearchBarcodePdt(ptTextScan){

        if($('#ohdIVSPLCode').val() == "" || $('#ohdIVSPLCode').val() == null){
            $('#oetIVInsertBarcode').attr('readonly',false);
            $('#oetIVInsertBarcode').val('');
            $('#odvIVModalPleseselectSPL').modal('show');
            return;
        }

        var tWhereCondition = "";
        $.ajax({
            type    : "POST",
            url     : "BrowseDataPDTTableCallView",
            data    :  {
                'Qualitysearch'       : [],
                'ReturnType'          : "S",
                'ShowCountRecord'     : 10,
                'aPriceType'          : ["Cost","tCN_Cost","Company","1"],
                'NextFunc'            : "",
                'SelectTier'          : ["PDT"],
                'SPL'                 : $('#ohdIVSPLCode').val(),
                'BCH'                 : $('#ohdIVBchCode').val(),
                'MCH'                 : '',
                'SHP'                 : '',
                // 'Where'               : [tWhereCondition],
                'tTextScan'           : ptTextScan,
                'tTYPEPDT'            : '', 
                'tSNPDT'              : '',
                'tWhere'              : [" AND PPCZ.FTPdtStaAlwPoSPL = 1 "],
                'aPackDataForSearch'  : {
                    'tSearchPDTType' : "T1,T3,T4,T5,T6,S2,S3,S4"
                }
            },
            catch   : false,
            timeout : 0,
            success : function (tResult){
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                console.log(oText);
                if(oText == '800'){
                    $('#oetIVInsertBarcode').attr('readonly',false);
                    $('#odvIVModalPDTNotFound').modal('show');
                    $('#oetIVInsertBarcode').val('');
                }else{
                    // พบสินค้ามีหลายบาร์โค้ด
                    if(oText.length > 1){
                        $('#odvIVModalPDTMoreOne').modal('show');
                        $('#odvIVModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');

                        for(i=0; i<oText.length; i++){
                            var aNewReturn      = JSON.stringify(oText[i]);
                            var tResult         = "["+aNewReturn+"]";
                            var oEncodePackData = window.btoa(unescape(encodeURIComponent(tResult)));
                            var tHTML = "<tr class='xCNColumnPDTMoreOne"+i+" xCNColumnPDTMoreOne' data-information='"+oEncodePackData+"' style='cursor: pointer;'>";
                                tHTML += "<td>"+oText[i].pnPdtCode+"</td>";
                                tHTML += "<td>"+oText[i].packData.PDTName+"</td>";
                                tHTML += "<td>"+oText[i].packData.PUNName+"</td>";
                                tHTML += "<td>"+oText[i].ptBarCode+"</td>";
                                tHTML += "</tr>";
                            $('#odvIVModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick',function(e){
                            $('#odvIVModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            JSxIVEventRenderTemp(tJSON); //Client
                            JSxIVEventInsertToTemp(tJSON); //Server
                        });

                        //คลิกได้เลย
                        $('.xCNColumnPDTMoreOne').on('click',function(e){
                            //เลือกสินค้าแบบตัวเดียว
                            $('.xCNColumnPDTMoreOne').removeClass('xCNActivePDT');
                            $('.xCNColumnPDTMoreOne').children().attr('style', 'background-color:transparent !important; color:#232C3D !important;');
                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important;');
                        });

                    }else{
                        //มีตัวเดียว
                        var aNewReturn  = JSON.stringify(oText);
                        JSxIVEventRenderTemp(aNewReturn); //Client
                        JSxIVEventInsertToTemp(aNewReturn); //Server
                    }
                }
            },
            error: function (jqXHR,textStatus,errorThrown){
               
            }
        });
    }

    //หลังจากค้นหาบาร์โค๊ด กรณีพบมากกว่าหนึ่งตัว
    function JCNxConfirmPDTMoreOne($ptType){
        if($ptType == 1){
            $("#odvIVModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function( index ) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                JSxIVEventRenderTemp(tJSON); //Client
                JSxIVEventInsertToTemp(tJSON); //Server
            });
        }else{
            $('#oetIVInsertBarcode').attr('readonly',false);
            $('#oetIVInsertBarcode').val('');
        }
    }

    //เลือกสินค้า
    $('#obtIVDocBrowsePdt').unbind().click(function(){ 

        if($('#ohdIVSPLCode').val() == "" || $('#ohdIVSPLCode').val() == null){
            $('#odvIVModalPleseselectSPL').modal('show');
            return;
        }

        var dTime               = new Date();
        var dTimelocalStorage   = dTime.getTime();

        var aWhereCondition = [];
        aWhereCondition.push(" AND PPCZ.FTPdtStaAlwPoSPL = 1 ");

        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                'Qualitysearch'   : [],
                'PriceType'       : ["Cost", "tCN_Cost", "Company", "1"],
                'SelectTier'      : ['PDT'],
                'ShowCountRecord' : 10,
                'NextFunc'        : 'JSxAfterChoosePDT',
                'ReturnType'      : 'M',
                'SPL'             : [$('#ohdIVSPLCode').val()],
                'BCH'             : ['',''],
                'SHP'             : ['',''],
                'TimeLocalstorage': dTimelocalStorage,
                // 'tTYPEPDT'        : '1,2,3,4,5',
                'aAlwPdtType'     : ['T1','T3','T4','T5','T6','S2','S3','S4'],
                'Where'           : aWhereCondition
            },
            cache: false,
            timeout: 0,
            success: function(tResult){
                $('#odvModalDOCPDT').modal({backdrop: 'static', keyboard: false})  
                $('#odvModalDOCPDT').modal({ show: true });

                //remove localstorage
                localStorage.removeItem("LocalItemDataPDT");
                $('#odvModalsectionBodyPDT').html(tResult);
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    //หลังจากเลือกสินค้า
    function JSxAfterChoosePDT(ptPdtData){
        var aPackData = JSON.parse(ptPdtData);
        for(var i=0;i<aPackData.length;i++){
            var aNewPackData = JSON.stringify(aPackData[i]);
            var aNewPackData = "["+aNewPackData+"]";
            JSxIVEventRenderTemp(aNewPackData);      // Event Render : client
            JSxIVEventInsertToTemp(aNewPackData);    // Event Insert : server
        }
    }

    //พิมพ์เอกสาร
    function JSxIVPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tIVFTBchCode); ?>'},
            {"DocCode"      : '<?=@$tIVDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tIVFTBchCode;?>'}
        ];
        var tGrandText = $('#odvIVDataTextBath').text();
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillPurInv?infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand="+tGrandText, '_blank');
    }

    //---------------------------------------------------------------------------------------------------------------//

    //กดเพิ่มเอกสารอ้างอิง (ภายใน ภายนอก)
    $('#obtIVAddDocRef').off('click').on('click',function(){
        $('#ofmIVFormAddDocRef').validate().destroy();
        JSxIVEventClearValueInFormHDDocRef();
        var tCountRef = '0';
        var ChkRef = $("#CheckRefDoc tbody").find('tr');
        ChkRef.each(function () { 
            if($(this).hasClass( "xWHaveItem" )){
                tCountRef = '1';
            }
        });
        if(tCountRef == '1'){
            // $('#odvIVModalPleseDelRefCode').modal('show');
            $('#odvIVModalAddDocRef').modal('show');

        }else{
            $('#odvIVModalAddDocRef').modal('show');
        }
    });

    //เคลียร์ค่า
    function JSxIVEventClearValueInFormHDDocRef(){
        $('#oetIVRefDocNo').val('');
        $('#oetIVRefDocDate').val('');
        $('#oetIVDocRefInt').val('');
        $('#oetIVDocRefIntName').val('');
        $('#oetIVRefKey').val('');
    }

    //เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    $('#ocbIVRefType').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        JSxIVEventCheckShowHDDocRef();
    });

    //กดเลือกอ้างอิงเอกสารภายใน (ใบสั่งสินค้าสำนักงานใหญ่)
    $('#obtIVBrowseRefDoc').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tIVRefType = $('#ocbIVRefDoc').val();
            if( tIVRefType == '1' ){ //ใบรับของ
                JSxCallGetIVDORefIntDoc();
            }else if( tIVRefType == '2' ){ //ใบสั่งซื้อ
                JSxCallGetIVDORefIntDoc();
            }else{ //ใบขาย
                JSxCallGetIVDORefIntDoc();
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //Browse => ใบสั่งสินค้าสำนักงานใหญ่
    function JSxCallGetPRBRefIntDoc(){
        JSxCallIVRefIntDoc();
    }

    //Browse => DO
    function JSxCallGetIVDORefIntDoc(){
        if($('#ohdIVSPLCode').val() == "" || $('#ohdIVSPLCode').val() == null){
            $('#odvIVModalPleseselectSPL').modal('show');
            return;
        }
        
        JSxCallPageIVRefIntDoc();
    }


    //Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    JSxIVEventCheckShowHDDocRef();
    function JSxIVEventCheckShowHDDocRef(){
        var tIVRefType = $('#ocbIVRefType').val();
        if( tIVRefType == '1' ){
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();
        }else{
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }

    //โหลด Table อ้างอิงเอกสารทั้งหมด
    FSxIVCallPageHDDocRef();
    function FSxIVCallPageHDDocRef(){
        var tDocNo = $('#oetIVDocNo').val();
        var trefType = $('#ohdIVSPLStaLocal').val();
        var tRefTable = '';
        if(trefType == '1'){
            tRefTable =  'TAPTDoHDDocRef';
        }else{
            tRefTable =  'TAPTPoHDDocRef';
        }
        $.ajax({
            type    : "POST",
            url     : "docIVPageHDDocRef",
            data:{
                'ptDocNo' : tDocNo,
                'ptRef'   : tRefTable
            },
            cache   : false,
            timeout : 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                $('#odvIVTableHDRef').html(aResult['tViewPageHDRef']);
                    JCNxCloseLoading();
                }else{
                    var tMessageError = aResult['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กดยืนยันบันทึกลง Temp
    $('#ofmIVFormAddDocRef').off('click').on('click',function(){
        $('#ofmIVFormAddDocRef').validate().destroy();
        $('#ofmIVFormAddDocRef').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetIVRefDocNo    : {"required" : true}
            },
            messages: {
                oetIVRefDocNo    : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
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
            highlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function (form){
                JCNxOpenLoading();

                if($('#ocbIVRefType').val() == 1){ //อ้างอิงเอกสารภายใน
                    var tDocNoRef = $('#oetIVDocRefInt').val();
                }else{ //อ้างอิงเอกสารภายนอก
                    var tDocNoRef = $('#oetIVRefDocNo').val();
                }

                $.ajax({
                    type    : "POST",
                    url     : "docIVEventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld'     : $('#oetIVRefDocNoOld').val(),
                        'ptIVDocNo'         : $('#oetIVDocNo').val(),
                        'ptRefType'         : $('#ocbIVRefType').val(),
                        'ptRefDocNo'        : tDocNoRef,
                        'pdRefDocDate'      : $('#oetIVRefDocDate').val(),
                        'ptRefKey'          : $('#oetIVRefKey').val()
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        JSxIVEventClearValueInFormHDDocRef();
                        $('#odvIVModalAddDocRef').modal('hide');

                        FSxIVCallPageHDDocRef();
                        JCNxCloseLoading();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    });

    /** ================== Check Box Auto GenCode ===================== */
    $('#ocbIVStaAutoGenCode').on('change', function (e) {
        if($('#ocbIVStaAutoGenCode').is(':checked')){
            $("#oetIVDocNo").val('');
            $("#oetIVDocNo").attr("readonly", true);
            $('#oetIVDocNo').closest(".form-group").css("cursor","not-allowed");
            $('#oetIVDocNo').css("pointer-events","none");
            $("#oetIVDocNo").attr("onfocus", "this.blur()");
            $('#ofmIVFormAdd').removeClass('has-error');
            $('#ofmIVFormAdd .form-group').closest('.form-group').removeClass("has-error");
            $('#ofmIVFormAdd em').remove();
        }else{
            $('#oetIVDocNo').closest(".form-group").css("cursor","");
            $('#oetIVDocNo').css("pointer-events","");
            $('#oetIVDocNo').attr('readonly',false);
            $("#oetIVDocNo").removeAttr("onfocus");
        }
    });
    /** =============================================================== */

</script>