<script type="text/javascript">
    var nLangEdits              = '<?=$this->session->userdata("tLangEdit");?>';
    var tUsrApvName             = '<?=$this->session->userdata("tSesUsername");?>';
    var tSesUsrLevel            = '<?=$this->session->userdata('tSesUsrLevel');?>';
    var tUserBchCode            = '<?=$this->session->userdata("tSesUsrBchCode");?>';
    var tUserBchName            = '<?=$this->session->userdata("tSesUsrBchName");?>';
    var tUserWahCode            = '<?=$this->session->userdata("tSesUsrWahCode");?>';
    var tUserWahName            = '<?=$this->session->userdata("tSesUsrWahName");?>';
    var nAddressVersion         = '<?=FCNaHAddressFormat('TCNMCst')?>';
    var tRoute                  = $('#ohdSORoute').val();
    var tSOSesSessionID         = $("#ohdSesSessionID").val();

    $(document).ready(function(){

        $('#odvSOContentHDRef').hide();
        $('#obtSOAddDocRef').hide();
        JSxSOEventCheckShowHDDocRef();

        JSxSOCallPageHDDocRef();

        JSxCheckPinMenuClose();

        if($('#ocmSOFrmSplInfoPaymentType').val() == ''){
            $('.xCNPanel_CreditTerm').hide();
        }
        
        if(tUserBchCode != ''){
            $('#oetSOFrmBchCode').val(tUserBchCode);
            $('#oetSOFrmBchName').val(tUserBchName);
            $('#obtBrowseSOBCH').attr("disabled","disabled");
        }
        if(tUserWahCode != '' && tRoute == 'dcmSOEventAdd'){
            $('#oetSOFrmWahCode').val(tUserWahCode);
            $('#oetSOFrmWahName').val(tUserWahName);
        }

        $('.selectpicker').selectpicker('refresh');

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

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

        $('#obtSODocBrowsePdt').unbind().click(function(){
            if($('#oetSOFrmCstHNNumber').val() != ""){
                JSxCheckPinMenuClose();
                JCNvSOBrowsePdt();
            }else{
                $('#odvSOModalPleseselectCustomer #odvSOPlsChooseCst').html('<p><?php echo language('document/saleorder/saleorder', 'tSoWarningBeforeSelectPdt');?></p>');
                $('#odvSOModalPleseselectCustomer').modal('show');
            }
        });

        if($('#oetSOFrmBchCode').val() == ""){
            $("#obtSOFrmBrowseTaxAdd").attr("disabled","disabled");
        }

        /** =================== Event Search Function ===================== */
        $('#oliSOMngPdtScan').unbind().click(function(){
            var tSOSplCode  = $('#oetSOFrmSplCode').val();
            if(typeof(tSOSplCode) !== undefined && tSOSplCode !== ''){
                //Hide
                $('#oetSOFrmFilterPdtHTML').hide();
                $('#obtSOMngPdtIconSearch').hide();
                
                //Show
                $('#oetSOFrmSearchAndAddPdtHTML').show();
                $('#obtSOMngPdtIconScan').show();
            }else{
                var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
                FSvCMNSetMsgWarningDialog(tWarningMessage);
                return;
            }
        });

        $('#oliSOMngPdtSearch').unbind().click(function(){
            //Hide
            $('#oetSOFrmSearchAndAddPdtHTML').hide();
            $('#obtSOMngPdtIconScan').hide();
            //Show
            $('#oetSOFrmFilterPdtHTML').show();
            $('#obtSOMngPdtIconSearch').show();
        });

        /** ===================== Set Date Autometic Doc ========================  */
        var dCurrentDate    = new Date();
        var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes();

        if($('#oetSODocDate').val() == ''){
            $('#oetSODocDate').datepicker("setDate",dCurrentDate); 
        }

        if($('#oetSOEffectiveDate').val() == ''){
            $('#oetSOEffectiveDate').datepicker("setDate",dCurrentDate); 
        }

        if($('#oetSODocTime').val() == ''){
            $('#oetSODocTime').val(tCurrentTime);
        }

        /** =================== Event Date Function  ====================== */
        $('#obtSODocDate').unbind().click(function(){
            $('#oetSODocDate').datepicker('show');
        });

        $('#obtTQEffectiveDate').unbind().click(function(){
            $('#oetSOEffectiveDate').datepicker('show');
        });

        $('#obtSODocTime').unbind().click(function(){
            $('#oetSODocTime').datetimepicker('show');
        });

        $('#obtSORefDocDate').unbind().click(function(){
            $('#oetSORefDocDate').datepicker('show');
        });

        $('#obtSOBrowseRefExtDocDate').unbind().click(function(){
            $('#oetSORefExtDocDate').datepicker('show');
        });

        $('#obtSOFrmSplInfoDueDate').unbind().click(function(){
            $('#oetSOFrmSplInfoDueDate').datepicker('show');
        });

        $('#obtSOFrmSplInfoBillDue').unbind().click(function(){
            $('#oetSOFrmSplInfoBillDue').datepicker('show');
        });

        $('#obtSOFrmSplInfoTnfDate').unbind().click(function(){
            $('#oetSOFrmSplInfoTnfDate').datepicker('show');
        });

        // Check Box Auto GenCode 
        $('#ocbSOStaAutoGenCode').on('change', function (e) {
            if($('#ocbSOStaAutoGenCode').is(':checked')){
                $("#oetSODocNo").val('');
                $("#oetSODocNo").attr("readonly", true);
                $('#oetSODocNo').closest(".form-group").css("cursor","not-allowed");
                $('#oetSODocNo').css("pointer-events","none");
                $("#oetSODocNo").attr("onfocus", "this.blur()");
                $('#ofmSOFormAdd').removeClass('has-error');
                $('#ofmSOFormAdd .form-group').closest('.form-group').removeClass("has-error");
                $('#ofmSOFormAdd em').remove();
            }else{
                $('#oetSODocNo').closest(".form-group").css("cursor","");
                $('#oetSODocNo').css("pointer-events","");
                $('#oetSODocNo').attr('readonly',false);
                $("#oetSODocNo").removeAttr("onfocus");
            }
        });

        $('#ocmSOFrmSplInfoVatInOrEx').on('change', function (e) {
            JCNxOpenLoading();
            JSvSOLoadPdtDataTableHtml();
        });

        //Control ปุ่ม สร้างใบจัด
        $('#obtSOCreatePCK').attr('disabled',false);
        if('<?=$tSORoute?>' == 'dcmSOEventEdit'){
            if('<?=$nAlwFindWahPCK?>' == 1){
                //ปุ่มสร้างใบจัดจะไม่โชว์
                $('#obtSOCreatePCK').show();
            }else{
                //ปุ่มสร้างใบจัดจะไม่โชว์
                $('#obtSOCreatePCK').hide();
            }
        }else{
            //ปุ่มสร้างใบจัดจะไม่โชว์
            $('#obtSOCreatePCK').hide();
        }

        //Control สถานะเอกสาร
        var  nSOStaApv =  $('#ohdSOStaApv').val();
        if(nSOStaApv == 2 || nSOStaApv == 1){
            $('#obtSODocBrowsePdt').hide();
            $('#obtSOPrintDoc').show();
            $('#obtSOCancelDoc').show();
            $('#obtSOApproveDoc').hide();
            $('#odvSOBtnGrpSave').show();
            $('#oetSOInsertBarcode').hide();

            //ปุ่มสร้างใบจัดจะไม่โชว์
            $('#obtSOCreatePCK').hide();
        }

        var nSOStaDoc = $('#ohdSOStaDoc').val();
        if(nSOStaDoc == 3){
            $('#obtSOCreatePCK').hide();
        }

        //control ปุ่มจัด
        if('<?=$nStaPrcDoc?>' >= 5){
            //ถ้า มีใบจัดเเล้ว ปุ่มใบจัดไม่ต้องโชว์มาอีก
            $('#obtSOCreatePCK').hide();
        }

    });

    //สร้างใบจัด
    $('#obtSOCreatePCK').off('click').on('click',function(){

        //ถ้ายังไม่มีใบจัดจะสร้างใบจัดได้
        if('<?=$nStaPrcDoc?>' == null || '<?=$nStaPrcDoc?>' == 1 || '<?=$nStaPrcDoc?>' == ''){
            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docSOEventGenPCK",
                data    : {
                    'ptSODocNo'         : $('#oetSODocNo').val(),
                    'ptSOBCHCode'       : $('#oetSOFrmBchCode').val(),
                },
                cache: false,
                timeout: 0,
                success: function(oResult){
                    $('#obtSOCreatePCK').attr('disabled',true);

                    $('#odvSOModalCreatePCK').modal('show');

                    JCNxCloseLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#obtSOCreatePCK').attr('disabled',false);
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            var tMessageError = "เอกสารใบนี้ถูกสร้างเป็นใบจัดแล้ว กรุณาตรวจสอบที่หน้าจอใบจัดการสินค้า";
            FSvCMNSetMsgErrorDialog(tMessageError);
        }
    });

    //โหลด Table อ้างอิงเอกสารทั้งหมด
    function JSxSOCallPageHDDocRef(){
        var tDocNo  = "";
        if ($("#ohdSORoute").val() == "dcmSOEventEdit") {
            tDocNo = $('#oetSODocNo').val();
        }

        $.ajax({
            type    : "POST",
            url     : "docSOPageHDDocRef",
            data:{
                'ptDocNo' : tDocNo
            },
            cache   : false,
            timeout : 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                $('#odvSOTableHDRef').html(aResult['tViewPageHDRef']);
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

    //กดเพิ่มเอกสารอ้างอิง (ภายใน ภายนอก)
    $('#obtSOAddDocRef').off('click').on('click',function(){
        $('#ofmSOFormAddDocRef').validate().destroy();
        JSxSOEventClearValueInFormHDDocRef();
        $('#odvSOModalAddDocRef').modal('show');
    });

    //เคลียร์ค่า
    function JSxSOEventClearValueInFormHDDocRef(){
        $('#oetSORefDocNo').val('');
        $('#oetSORefDocDate').val('');
        $('#oetSORefIntDoc').val('');
        $('#oetSODocRefIntName').val('');
        $('#oetSORefKey').val('');
    }

    //Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    function JSxSOEventCheckShowHDDocRef(){
        var tSORefType = $('#ocbSORefType').val();
        if( tSORefType == '1' ){
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();
        }else{
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }

    //เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    $('#ocbSORefType').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        JSxSOEventCheckShowHDDocRef();
    });

    //เมื่อเปลี่ยน อ้างอิงเอกสาร
    $('#ocbSORefDoc').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        $("#oetSORefIntDoc").val('');
        $("#oetSORefDocDate").val('');
    });

    //กดยืนยันบันทึกลง Temp
    $('#ofmSOFormAddDocRef').off('click').on('click',function(){
        $('#ofmSOFormAddDocRef').validate().destroy();
        $('#ofmSOFormAddDocRef').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetSORefDocNo    : {"required" : true}
            },
            messages: {
                oetSORefDocNo    : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
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
                JCNxOpenLoading();

                if($('#ocbSORefType').val() == 1){ //อ้างอิงเอกสารภายใน
                    var tDocNoRef = $('#oetSORefIntDoc').val();
                    if($('#ocbSORefDoc').val() == '1'){ //ใบเสนอราคา
                    var tDocRefType = "QT";
                    var tRefKey   = "QT";
                    }else if($('#ocbSORefDoc').val() == '2'){ //ใบPO
                        var tDocRefType = "PO";
                        var tRefKey   = "PO";
                    }
                }else{ //อ้างอิงเอกสารภายนอก
                    var tDocNoRef = $('#oetSORefDocNo').val();
                    var tRefKey   = $('#oetSORefKey').val();
                }

                if($('#ocbSORefDoc').val() == '1'){ //ใบเสนอราคา
                    var tDocRefType = "QT";
                }else if($('#ocbSORefDoc').val() == '2'){ //ใบPO
                    var tDocRefType = "PO";
                }

                $.ajax({
                    type    : "POST",
                    url     : "docSOEventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld'     : $('#oetSORefDocNoOld').val(),
                        'ptSODocNo'         : $('#oetSODocNo').val(),
                        'ptRefType'         : $('#ocbSORefType').val(),
                        'ptRefDocNo'        : tDocNoRef,
                        'pdRefDocDate'      : $('#oetSORefDocDate').val(),
                        'ptRefKey'          : tRefKey,
                        'tDocRefType'       : tDocRefType
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult){
                        JSxSOEventClearValueInFormHDDocRef();
                        $('#odvSOModalAddDocRef').modal('hide');

                        JSxSOCallPageHDDocRef();
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
 
    // ตัวแปร Option Browse Modal กลุ่มธุรกิจ
    var oMerchantOption = function(poDataFnc){
        var tSOBchCode          = poDataFnc.tSOBchCode;
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tWhereModal         = "";
        
        // สถานะกลุ่มธุรกิจต้องใช้งานเท่านั้น
        tWhereModal += " AND (TCNMMerchant.FTMerStaActive = 1)";

        // เช็คเงื่อนไขแสดงกลุ่มธุรกิจเฉพาะสาขาตัวเอง
        if(typeof(tSOBchCode) != undefined && tSOBchCode != ""){
            tWhereModal += " AND ((SELECT COUNT(FTShpCode) FROM TCNMShop WHERE TCNMShop.FTMerCode = TCNMMerchant.FTMerCode AND TCNMShop.FTBchCode = '"+tSOBchCode+"') != 0)";
        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn       = {
            Title   : ['company/merchant/merchant','tMerchantTitle'],
            Table   : {Master:'TCNMMerchant',PK:'FTMerCode'},
            Join    : {
                Table : ['TCNMMerchant_L'],
                On : ['TCNMMerchant.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits]
            },
            Where : {
                Condition : [tWhereModal]
            },
            GrideView : {
                ColumnPathLang	: 'company/merchant/merchant',
                ColumnKeyLang	: ['tMerCode','tMerName'],
                ColumnsSize     : ['15%','75%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMMerchant.FTMerCode','TCNMMerchant_L.FTMerName'],
                DataColumnsFormat : ['',''],
                Perpage			: 5,
                OrderBy			: ['TCNMMerchant.FTMerCode ASC'],
            },
            CallBack : {
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMMerchant.FTMerCode"],
                Text		: [tInputReturnName,"TCNMMerchant_L.FTMerName"],
            },
            NextFunc : {
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            },
            RouteAddNew: 'merchant',
            BrowseLev: nSOStaSOBrowseType
        };
        return oOptionReturn;
    }
    
    // ตัวแปร Option Browse Modal ร้านค้า
    var oShopOption     = function(poDataFnc){
        var tSOBchCode          = poDataFnc.tSOBchCode;
        var tSOMerCode          = poDataFnc.tSOMerCode;
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tWhereModal         = "";

        // สถานะร้านค้าใช้งาน
        tWhereModal += " AND (TCNMShop.FTShpStaActive = 1)";

        // เช็คเงื่อนไขแสดงร้านค้าในสาขาตัวเอง
        if(typeof(tSOBchCode) != undefined && tSOBchCode != ""){
            tWhereModal += " AND ((TCNMShop.FTBchCode = '"+tSOBchCode+"') AND TCNMShop.FTShpType  != 5)"
        }

        // เช็คเงื่อนไขแสดงร้านค้าในกลุ่มธุรกิจตัวเอง
        if(typeof(tSOMerCode) != undefined && tSOMerCode != ""){
            tWhereModal += " AND ((TCNMShop.FTMerCode = '"+tSOMerCode+"') AND TCNMShop.FTShpType  != 5)";

        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn   = {
            Title: ["company/shop/shop","tSHPTitle"],
            Table: {Master:"TCNMShop",PK:"FTShpCode"},
            Join: {
                Table: ['TCNMShop_L'],
                On: ['TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop.FTBchCode = TCNMShop_L.FTBchCode AND TCNMShop_L.FNLngID = '+nLangEdits,
                    
                ]
            },
            Where: {
                Condition: [tWhereModal]
            },
            GrideView: {
                ColumnPathLang      : 'company/shop/shop',
                ColumnKeyLang       : ['tShopCode','tShopName'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMShop.FTShpCode','TCNMShop_L.FTShpName','TCNMShop.FTShpType','TCNMShop.FTBchCode'],
                DataColumnsFormat   : ['','','',''],
                DisabledColumns     : [2,3,4,5],
                Perpage             : 10,
                OrderBy			    : ['TCNMShop_L.FTShpName ASC'],
            },
            CallBack: {
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMShop.FTShpCode"],
                Text		: [tInputReturnName,"TCNMShop_L.FTShpName"],
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            },
            RouteAddNew: 'shop',
            BrowseLev : nSOStaSOBrowseType
        };
        return oOptionReturn;
    }

    // ตัวแปร Option Browse Modal เครื่องจุดขาย
    var oPosOption      = function(poDataFnc){
        var tSOBchCode          = poDataFnc.tSOBchCode;
        var tSOShpCode          = poDataFnc.tSOShpCode;
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tWhereModal         = "";

        // สถานะเครื่องจุดขายต้องใช้งาน
        tWhereModal +=  " AND (TVDMPosShop.FTPshStaUse  = 1)";

        // เช็คเงื่อนไขแสดงร้านค้าในสาขาตัวเอง
        if(typeof(tSOBchCode) != undefined && tSOBchCode != ""){
            tWhereModal += " AND (TVDMPosShop.FTBchCode = '"+tSOBchCode+"') ";
        }

        // เช็คเงื่อนไขแสดงร้านค้าในร้านค้าตัวเอง
        if(typeof(tSOShpCode) != undefined && tSOShpCode != ""){
            tWhereModal += " AND (TVDMPosShop.FTShpCode = '"+tSOShpCode+"')";
        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn   = {
            Title: ["pos/posshop/posshop","tPshTitle"],
            Table: { Master:'TVDMPosShop', PK:'FTPosCode' },
            Join: {
                Table: ['TCNMPos_L','TCNMWaHouse', 'TCNMWaHouse_L'],
                On: [
                    "TCNMPos_L.FTPosCode = TVDMPosShop.FTPosCode AND TCNMPos_L.FtBchCode = TVDMPosShop.FTBchCode",
                    "TVDMPosShop.FTPosCode = TCNMWaHouse.FTWahRefCode AND TVDMPosShop.FTBchCode = TCNMWaHouse.FTBchCode AND TCNMWaHouse.FTWahStaType = 6",
                    "TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse.FTBchCode=TCNMWaHouse_L.FTBchCode  AND TCNMWaHouse_L.FNLngID = '"+nLangEdits+"'"
                ]
            },
            Where: {
                Condition : [tWhereModal]
            },
            GrideView: {
                ColumnPathLang: 'pos/posshop/posshop',
                ColumnKeyLang: ['tPshBRWShopTBCode', 'tPshBRWPosTBName'],
                ColumnsSize: ['25%', '75%'],
                WidthModal: 50,
                DataColumns: ['TVDMPosShop.FTPosCode','TCNMPos_L.FTPosName','TCNMWaHouse.FTWahCode', 'TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat : ['', ''],
                DisabledColumns: [2,3],
                Perpage: 5,
                OrderBy: ['TVDMPosShop.FTPosCode ASC'],
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TVDMPosShop.FTPosCode"],
                Text        : [tInputReturnName,"TVDMPosShop.FTPosName"]
            },
            NextFunc: {
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            },
            RouteAddNew: 'salemachine',
            BrowseLev : nSOStaSOBrowseType
        };
        return oOptionReturn;
    }

    // ตัวแปร Option Browse Modal คลังสินค้า
    var oWahOption      = function(poDataFnc){
        var tSOBchCode          = poDataFnc.tSOBchCode;
        var tSOShpCode          = poDataFnc.tSOShpCode;
        var tSOPosCode          = poDataFnc.tSOPosCode;
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tWhereModal         = "";

        // Where คลังของ สาขา
        if(tSOShpCode == "" && tSOPosCode == ""){
            tWhereModal += " AND (TCNMWaHouse.FTWahStaType IN (1,2,5) AND  TCNMWaHouse.FTWahRefCode='"+tSOBchCode+"')";
        }

        // Where คลังของ ร้านค้า
        if(tSOShpCode  != "" && tSOPosCode == ""){
            // tWhereModal += " AND (TCNMWaHouse.FTWahStaType IN (4))";
            // tWhereModal += " AND (TCNMWaHouse.FTWahRefCode = '"+tSOShpCode+"')";
        }

        // Where คลังของ เครื่องจุดขาย
        if(tSOShpCode  != "" && tSOPosCode != ""){
            tWhereModal += " AND (TCNMWaHouse.FTWahStaType IN (6))";
            tWhereModal += " AND (TCNMWaHouse.FTWahRefCode = '"+tSOPosCode+"')";
        }

        if(tSOShpCode  != ""){
            var oOptionReturn = {
                Title   : ['company/shop/shop','tSHPWah'],
                Table   : {Master:'TCNMShpWah',PK:'FTWahCode'},
                Join    : {
                    Table   : ['TCNMWaHouse_L' , 'TCNMWaHouse'],
                    On      : [
                                'TCNMWaHouse_L.FTWahCode = TCNMShpWah.FTWahCode AND TCNMWaHouse_L.FTBchCode = TCNMShpWah.FTBchCode  AND TCNMWaHouse_L.FNLngID = '+nLangEdits,
                                'TCNMShpWah.FTWahCode =  TCNMWaHouse.FTWahCode AND  TCNMShpWah.FTBchCode = TCNMWaHouse.FTBchCode '
                                ]
                },
                Where : {
                    Condition : [" AND TCNMWaHouse.FTWahStaType = 4 AND TCNMShpWah.FTShpCode = '" + tSOShpCode + "' AND TCNMShpWah.FTBchCode = '"+ tSOBchCode + "' "]
                },
                GrideView : {
                    ColumnPathLang  : 'company/shop/shop',
                    ColumnKeyLang   : ['tWahCode','tWahName'],
                    ColumnsSize     : ['15%','75%'],
                    WidthModal      : 50,
                    DataColumns     : ['TCNMShpWah.FTWahCode','TCNMWaHouse_L.FTWahName'],
                    DataColumnsFormat : ['',''],
                    Perpage         : 10,
                    OrderBy   : ['TCNMWaHouse_L.FTWahName'],
                    SourceOrder  : "ASC"
                },
                CallBack : {
                    ReturnType : 'S',
                    Value  : ["oetTROutWahFromCode","TCNMShpWah.FTWahCode"],
                    Text  : ["oetTROutWahFromName","TCNMWaHouse_L.FTWahName"],
                }
            }
        }else if(tSOShpCode == ""){
            var oOptionReturn   = {
                Title: ["company/warehouse/warehouse","tWAHTitle"],
                Table: { Master:"TCNMWaHouse", PK:"FTWahCode"},
                Join: {
                    Table: ["TCNMWaHouse_L"],
                    On: ["TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse.FTBchCode=TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = '"+nLangEdits+"'"]
                },
                Where: {
                    Condition : [" AND (TCNMWaHouse.FTWahStaType IN (1,2,5) AND  TCNMWaHouse.FTBchCode ='"+tSOBchCode+"')"]
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
                NextFunc: {
                    FuncName    : tNextFuncName,
                    ArgReturn   : aArgReturn
                },
                RouteAddNew: 'warehouse',
                BrowseLev : nSOStaSOBrowseType
            }
        }
        return oOptionReturn;
    }

    // ตัวแปร Option Browse Modal ตัวแทนจำหน่าย
    var oSplOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var oOptionReturn       = {
            Title: ['supplier/supplier/supplier', 'tSPLTitle'],
            Table: {Master:'TCNMSpl', PK:'FTSplCode'},
            Join: {
                Table: ['TCNMSpl_L', 'TCNMSplCredit'],
                On: [
                    'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
                    'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode'
                ]
            },
            Where:{
                Condition : ["AND TCNMSpl.FTSplStaActive = '1' "]
            },
            GrideView:{
                ColumnPathLang: 'supplier/supplier/supplier',
                ColumnKeyLang: ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid'],
                DataColumnsFormat: ['',''],
                DisabledColumns: [2, 3, 4, 5],
                Perpage: 5,
                OrderBy: ['TCNMSpl_L.FTSplName ASC']
            },
            CallBack:{
                ReturnType: 'S',
                Value   : [tInputReturnCode,"TCNMSpl.FTSplCode"],
                Text    : [tInputReturnName,"TCNMSpl_L.FTSplName"]
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            },
            RouteAddNew: 'supplier',
            BrowseLev: nSOStaSOBrowseType
        };
        return oOptionReturn;
    }

    // ตัวแปร Option Browse Modal ตัวแทนจำหน่าย
    var oCstOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var oOptionReturn       = {
            Title: ['customer/customer/customer', 'tCSTTitle'],
            Table: {Master:'TCNMCst', PK:'FTCstCode'},
            Join: {
                Table: ['TCNMCst_L', 'TCNMCstCard','TCNMCstLev'],
                On: [
                    'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = '+nLangEdits,
                    'TCNMCst.FTCstCode = TCNMCstCard.FTCstCode',
                    'TCNMCst.FTClvCode = TCNMCstLev.FTClvCode'
                ]
            },
            Where:{
                Condition : ["AND TCNMCst.FTCstStaActive = '1' AND ISNULL(TCNMCst.FTCstStaFC,'') = '' "]
            },
            GrideView:{
                ColumnPathLang: 'customer/customer/customer',
                ColumnKeyLang: ['tCSTCode', 'tCSTName','tCSTTel','tCSTTel','tCSTTel','tCSTTel','tCSTTel','tCSTCardNo'],
                ColumnsSize: ['15%', '30%','20%','20%'],
                WidthModal: 50,
                DataColumns: ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName','TCNMCst.FTCstCardID','TCNMCst.FTCstTel','TCNMCstLev.FTPplCode','TCNMCst.FTCstDiscRet','TCNMCst.FTCstStaAlwPosCalSo' , 'TCNMCstCard.FTCstCrdNo'],
                DataColumnsFormat: ['','','','','','','','',''],
                DisabledColumns: [2,4,5,6,8],
                Perpage: 10,
                OrderBy: ['TCNMCst.FDCreateOn DESC']
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

    // ตัวแปร Option Browse Modal รถ
    var oCarOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var tCstCode            = $('#oetSOFrmCstHNNumber').val();
        var tWhereCst           = '';
        if (tCstCode != '') {
            tWhereCst = "AND ISNULL(TSVMCar.FTCarOwner, '') = '"+tCstCode+"' "
        } else {
            tWhereCst = '';
        }
        var oOptionReturn       = {
            Title: ['service/car/car', 'tCARTitle'],
            Table: {Master:'TSVMCar', PK:'FTCarCode'},
            Join: {
                Table: ['TSVMCarInfo_L T1', 'TSVMCarInfo_L T2','TCNMCst_L'],
                On: [
                    'TSVMCar.FTCarBrand = T1.FTCaiCode AND T1.FNLngID = '+nLangEdits,
                    'TSVMCar.FTCarModel = T2.FTCaiCode AND T2.FNLngID = '+nLangEdits,
                    "TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = "+nLangEdits
                ]
            },
            Where:{
                Condition : [tWhereCst]
            },
            GrideView:{
                ColumnPathLang: 'service/car/car',
                ColumnKeyLang: ['tCARCode', 'tCARRegNo','tCAROwner'],
                ColumnsSize: ['15%', '15%', '60%'],
                WidthModal: 20,
                DataColumns: ['TSVMCar.FTCarCode', 'TSVMCar.FTCarRegNo', 'TCNMCst_L.FTCstName','T1.FTCaiName as FTCarBrand','T2.FTCaiName as FTCarModel'],
                DataColumnsFormat: ['','',''],
                DisabledColumns: [3,4],
                Perpage: 10,
                OrderBy: ['TSVMCar.FDCreateOn DESC']
            },
            CallBack:{
                ReturnType: 'S',
                Value   : [tInputReturnCode,"TSVMCar.FTCarCode"],
                Text    : [tInputReturnName,"TSVMCar.FTCarRegNo"]
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : ['FTCarBrand', 'FTCarModel']
            },
        };
        return oOptionReturn;
    }

    // ตัวแปร Option ที่อยู่
    var oAddrOption     = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var nKeepBrowseAddr     = poDataFnc.nKeepBrowseAddr;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;

        //เซตชื่อของ title
        var tLangTitleName = 'tPILabelFrmSplInfoShipAddress';

        var oOptionReturn       = {
            Title   : ['document/purchaseinvoice/purchaseinvoice',tLangTitleName],
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
                Condition : ["AND TCNMCstAddress_L.FTCstCode = '"+$("#oetSOFrmCstHNNumber").val()+"' AND TCNMCstAddress_L.FNLngID = "+nLangEdits]
            },
            GrideView:{
                ColumnPathLang	: 'document/saleorder/saleorder',
                ColumnKeyLang	: [
                    'tSOShipADDBch',
                    'tSOShipADDSeq',
                    'tSOShipADDV1No',
                    'tSOShipADDV1Soi',
                    'tSOShipADDV1Village',
                    'tSOShipADDV1Road',
                    'tSOShipADDV1SubDist',
                    'tSOShipADDV1DstCode',
                    'tSOShipADDV1PvnCode',
                    'tSOShipADDV1PostCode'
                ],
                DataColumns		: [
                    'TCNMCstAddress_L.FNAddSeqNo',
                    'TCNMCstAddress_L.FTAddV1No',
                    'TCNMCstAddress_L.FTAddV1Soi',
                    'TCNMCstAddress_L.FTAddV1Village',
                    'TCNMCstAddress_L.FTAddV1Road',
                    'TCNMSubDistrict_L.FTSudName',
                    'TCNMDistrict_L.FTDstName',
                    'TCNMProvince_L.FTPvnName',
                    'TCNMCstAddress_L.FTAddV1PostCode',
                    'TCNMCstAddress_L.FTAddTel',
                    'TCNMCstAddress_L.FTAddFax'
                ],
                DataColumnsFormat   : ['','','','','','','','','','','',''],
                DisabledColumns     : [8,9,10],
                ColumnsSize         : [''],
                Perpage			    : 10,
                WidthModal          : 50,
                OrderBy			    : ['TCNMCstAddress_L.FDCreateOn ASC'],
            },
            debugSQL : true,
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

    // Event Browse Merchant
    $('#obtSOBrowseMerchant').unbind().click(function(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSOBrowseMerchantOption  = undefined;
            oSOBrowseMerchantOption         = oMerchantOption({
                'tSOBchCode'        : $('#oetSOFrmBchCode').val(),
                'tReturnInputCode'  : 'oetSOFrmMerCode',
                'tReturnInputName'  : 'oetSOFrmMerName',
                'tNextFuncName'     : 'JSxSOSetConditionMerchant',
                'aArgReturn'        : ['FTMerCode','FTMerName'],
            });
            JCNxBrowseData('oSOBrowseMerchantOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    // Event Browse Shop
    $('#obtSOBrowseShop').unbind().click(function(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSOBrowseShopOption  = undefined;
            oSOBrowseShopOption         = oShopOption({
                'tSOBchCode'        : $('#oetSOFrmBchCode').val(),
                'tSOMerCode'        : $('#oetSOFrmMerCode').val(),
                'tReturnInputCode'  : 'oetSOFrmShpCode',
                'tReturnInputName'  : 'oetSOFrmShpName',
                'tNextFuncName'     : 'JSxSOSetConditionShop',
                'aArgReturn'        : ['FTBchCode','FTShpType','FTShpCode','FTShpName']
            });
            JCNxBrowseData('oSOBrowseShopOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Pos
    $('#obtSOBrowsePos').unbind().click(function(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSOBrowsePosOption   = undefined;
            oSOBrowsePosOption          = oPosOption({
                'tSOBchCode'        : $('#oetSOFrmBchCode').val(),
                'tSOShpCode'        : $('#oetSOFrmShpCode').val(),
                'tReturnInputCode'  : 'oetSOFrmPosCode',
                'tReturnInputName'  : 'oetSOFrmPosName',
                'tNextFuncName'     : 'JSxSOSetConditionPos',
                'aArgReturn'        : ['FTPosCode','FTWahCode','FTWahName']
            });
            JCNxBrowseData('oSOBrowsePosOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Warehouse
    $('#obtSOBrowseWahouse').unbind().click(function(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSOBrowseWahOption   = undefined;
            oSOBrowseWahOption          = oWahOption({
                'tSOBchCode'        : $('#oetSOFrmBchCode').val(),
                'tSOShpCode'        : '',
                'tSOPosCode'        : $('#oetSOFrmWahCode').val(),
                'tReturnInputCode'  : 'oetSOFrmWahCode',
                'tReturnInputName'  : 'oetSOFrmWahName',
                'tNextFuncName'     : 'JSxSOSetConditionWahouse',
                'aArgReturn'        : []
            });
            JCNxBrowseData('oSOBrowseWahOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Supplier
    $('#obtSOBrowseSupplier').unbind().click(function(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSOBrowseSplOption   = undefined;
            oSOBrowseSplOption          = oSplOption({
                'tReturnInputCode'  : 'oetSOFrmSplCode',
                'tReturnInputName'  : 'oetSOFrmSplName',
                'tNextFuncName'     : 'JSxSOSetConditionAfterSelectSpl',
                'aArgReturn'        : ['FNSplCrTerm', 'FCSplCrLimit', 'FTSplStaVATInOrEx', 'FTSplTspPaid', 'FTSplCode', 'FTSplName']
            });
            JCNxBrowseData('oSOBrowseSplOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Customer
    $('#obtSOBrowseCustomer').unbind().click(function(){
        var tDocRef     = $('#oetSORefIntDoc').val();
        var tShipAddr   = $('#ohdSOFrmShipAdd').val();

        //แจ้งเตือนเมื่อเปลี่ยนลูกค้าขณะมีที่อยู่จัดส่งแล้ว
        if (tShipAddr != '') {
            $('#odvSOModalChangeBCH #odvWarningTxt').html('<p><?php echo language('document/saleorder/saleorder', 'tSoWarningChangeCstAddr');?></p>');
            $('#odvSOModalChangeBCH').modal('show');
        }else if (tDocRef != '') {
            $('#odvSOModalChangeBCH #odvWarningTxt').html('<p><?php echo language('document/saleorder/saleorder', 'tSoWarningChangeCst');?></p>');
            $('#odvSOModalChangeBCH').modal('show');
        }else{
            JSxCheckPinMenuClose();
            window.oSOBrowseSplOption   = undefined;
            oSOBrowseCstOption          = oCstOption({
                'tReturnInputCode'  : 'oetSOFrmCstCode',
                'tReturnInputName'  : 'oetSOFrmCstName',
                'tNextFuncName'     : 'JSxSOSetConditionAfterSelectCst',
                'aArgReturn'        : ['FTCstCode', 'FTCstName','FTCstCardID','FTCstTel','FTPplCode','FTCstDiscRet','FTCstStaAlwPosCalSo']
            });
            JCNxBrowseData('oSOBrowseCstOption');
        }

        //แจ้งเตือนเมื่อเปลี่ยนสาขา
        $('#obtChangeBCH').on("click",function() {
            if (tShipAddr != '') {
                $('.xWClearDataWhenChangeCst').val("");
            }else{
                $('#oetSORefIntDoc').val("");
                $('#oetSORefIntDocDate').val("");
            }
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oSOBrowseSplOption   = undefined;
                oSOBrowseCstOption          = oCstOption({
                    'tReturnInputCode'  : 'oetSOFrmCstCode',
                    'tReturnInputName'  : 'oetSOFrmCstName',
                    'tNextFuncName'     : 'JSxSOSetConditionAfterSelectCst',
                    'aArgReturn'        : ['FTCstCode', 'FTCstName','FTCstCardID','FTCstTel','FTPplCode','FTCstDiscRet','FTCstStaAlwPosCalSo']
                });
                JCNxBrowseData('oSOBrowseCstOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    });
    
    // Event Browse Customer
    $('#obtSOBrowseCarCode').unbind().click(function(){
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSOBrowseSplOption   = undefined;
            oSOBrowseCarOption          = oCarOption({
                'tReturnInputCode'  : 'oetSOFrmCarCode',
                'tReturnInputName'  : 'oetSOFrmCarRegNo',
                'tNextFuncName'     : 'JSxSOSetConditionAfterSelectCar',
            });
            JCNxBrowseData('oSOBrowseCarOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    function JSxSOSetConditionAfterSelectCar(paData) {
        var tSOCarBrand   = '';
        var tSOCarModel   = '';
        
        if (typeof(paData) != 'undefined' && paData != "NULL") {
            var aSOCarData = JSON.parse(paData);
            tSOCarBrand   = aSOCarData[0];
            tSOCarModel   = aSOCarData[1];
        }
        var tCarName = tSOCarBrand+ " " +tSOCarModel;
        $("#oetSOFrmCarName").val(tCarName);
    }
        
    //เลือกที่อยู่
    $('#obtSOBrowseAddr').click(function(){ 
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();

            //ปิด modal ที่อยู่
            setTimeout(function(){ $('#odvSOModalAddress').modal('hide'); }, 500);

            oSOBrowseAddrOption         = oAddrOption({
                    'tReturnInputCode'  : 'ohdSOAddrCode',
                    'tReturnInputName'  : 'ohdSOAddrCode',
                    'nKeepBrowseAddr'   : nKeepBrowseAddrOption,
                    'tNextFuncName'     : 'JSxSOSetConditionAfterSelectAddr',
                    'aArgReturn'        : [ 'FNAddSeqNo'    ,'FTAddV1No'    ,'FTAddV1Soi' ,
                                            'FTAddV1Village','FTAddV1Road'  ,'FTSudName' ,
                                            'FTDstName'     ,'FTPvnName'    ,'FTAddV1PostCode' ,
                                            'FTAddTel'      ,'FTAddFax'     
                                        ]
            });
            JCNxBrowseData('oSOBrowseAddrOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    // ====================================== Function NextFunc Browse Modal =====================================

    //หลังจากเลือกที่อยู่
    function JSxSOSetConditionAfterSelectAddr(poDataNextFunc){
        var aData;
        if (poDataNextFunc != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            
            $('#odvSOModalAddress').modal({backdrop : 'static' , show : true});

            //เก็บค่า
            $('#ohdSOAddrCode').val(aData[0])
            $('#ohdSOAddrName').val(aData[1])
            $('#ohdSOAddrNoHouse').val(aData[1])
            $('#ohdSOAddrVillage').val(aData[3])
            $('#ohdSOAddrRoad').val(aData[4])
            $('#ohdSOAddrSubDistrict').val(aData[5])
            $('#ohdSOAddrDistict').val(aData[6])
            $('#ohdSOAddrProvince').val(aData[7])
            $('#ohdSOZipCode').val(aData[8])
            $('#ohdSOAddrTel').val(aData[9])
            $('#ohdSOAddrFax').val(aData[10])

            //โชว์ค่า
            $('#oetSOAddrCode').val(aData[0])
            $('#oetSOAddrName').val(aData[1])
            $('#oetSOAddrNoHouse').val(aData[1])
            $('#oetSOAddrVillage').val(aData[3])
            $('#oetSOAddrRoad').val(aData[4])
            $('#oetSOAddrSubDistrict').val(aData[5])
            $('#oetSOAddrDistict').val(aData[6])
            $('#oetSOAddrProvince').val(aData[7])
            $('#oetSOZipCode').val(aData[8])
            $('#oetSOAddrTel').val(aData[9])
            $('#oetSOAddrFax').val(aData[10])
        }
    }

    //กดยืนยันที่อยู่
    function JSxConfirmAddress(){
        //เซตค่า
        $('#ohdSOFrmShipAdd').val($('#ohdSOAddrCode').val())
    }

    // Functionality : Function Behind NextFunc กลุ่มธุรกิจ
    function JSxSOSetConditionMerchant(poDataNextFunc){
        var aDataNextFunc,tSOMerCode,tSOMerName;
        if(typeof(poDataNextFunc) != undefined && poDataNextFunc != "NULL"){
            aDataNextFunc   = JSON.parse(poDataNextFunc);
            tSOMerCode      = aDataNextFunc[0];
            tSOMerName      = aDataNextFunc[1];
        }
        
        let tSOBchCode  = $('#oetSOFrmBchCode').val();
        let tSOMchCode  = $('#oetSOFrmMerCode').val();
        let tSOMchName  = $('#oetSOFrmMerName').val();
        let tSOShopCode = $('#oetSOFrmShpCode').val();
        let tSOShopName = $('#oetSOFrmShpName').val();
        let tSOPosCode  = $('#oetSOFrmPosCode').val();
        let tSOPosName  = $('#oetSOFrmPosName').val();
        let tSOWahCode  = $('#oetSOFrmWahCode').val();
        let tSOWahName  = $('#oetSOFrmWahName').val();

        let nCountDataInTable = $('#otbSODocPdtAdvTableList tbody .xWPdtItem').length;
        
        if(nCountDataInTable > 0 && tSOMchCode != "" && tSOShopCode != "" && tSOWahCode != ""){
            // รายการสินค้าที่ท่านเพิ่มไปแล้วจะถูกล้างค่าทิ้ง เมื่อท่านเปลี่ยนกลุ่มธุรกิจ
            var tTextMssage    = '<?php echo language('document/purchaseinvoice/purchaseinvoice','tSOMsgNotiChangeMerchantClearDocTemp');?>';
            FSvCMNSetMsgWarningDialog("<p>"+tTextMssage+"</p>");
            
            // Event CLick Close Massage And Delete Temp
            $('#odvModalWanning .xWBtnOK').click(function(evn){
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "dcmSOClearDataDocTemp",
                    data: {
                        'ptSODocNo' : $("#oetSODocNo").val()
                    },
                    cache: false,
                    success: function (oResult){
                        var aDataReturn     = JSON.parse(oResult);
                        var tMessageError   = aDataReturn['tStaMessg'];
                        switch(aDataReturn['nStaReturn']){
                            case 1:
                                JSvSOLoadPdtDataTableHtml();
                                JCNxCloseLoading();
                            break;
                            case 800:
                                FSvCMNSetMsgErrorDialog(tMessageError);
                            break;
                            case 500:
                                FSvCMNSetMsgErrorDialog(tMessageError);
                            break;
                        }
                        $('#odvModalWanning .xWBtnOK').unbind();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        }

        $('#obtSOBrowseShop').attr('disabled', true);
        $('#obtSOBrowsePos').attr('disabled', true);
        // $('#obtSOBrowseWahouse').attr('disabled', true);
        
        if(tSesUsrLevel == 'HQ' || tSesUsrLevel == 'BCH'){
            if((tSOMchCode == "" && tSOMchName == "") && (tSOShopCode == "" && tSOShopName == "") && (tSOPosCode == "" && tSOPosName == "" )) {
                $('#obtSOBrowseWahouse').attr('disabled', false).removeClass('disabled');

            }else{
                $('#obtSOBrowseShop').attr('disabled',false).removeClass('disabled');
                // $('#obtSOBrowseWahouse').attr('disabled', true).addClass('disabled');
            }

            $('#oetSOFrmShpCode,#oetSOFrmShpName').val('');
            $('#oetSOFrmPosCode,#oetSOFrmPosName').val('');
            $('#oetSOFrmWahCode,#oetSOFrmWahName').val('');
        }
    }

    // Functionality : Function Behind NextFunc ร้านค้า
    function JSxSOSetConditionShop(poDataNextFunc){
        var aDataNextFunc,tSOBchCode,tSOShpType,tSOShpCode,tSOShpName,tSOWahCode,tSOWahName;
        if(typeof(poDataNextFunc) != undefined && poDataNextFunc != "NULL"){
            aDataNextFunc   = JSON.parse(poDataNextFunc);
            tSOBchCode      = aDataNextFunc[0];
            tSOShpType      = aDataNextFunc[1];
            tSOShpCode      = aDataNextFunc[2];
            tSOShpName      = aDataNextFunc[3];
    
        }else{
            $('#oetSOFrmWahCode,#oetSOFrmWahName').val('');
        }

        let tSODataBchCode  = $('#oetSOFrmBchCode').val();
        let tSODataMchCode  = $('#oetSOFrmMerCode').val();
        let tSODataMchName  = $('#oetSOFrmMerName').val();
        let tSODataShopCode = $('#oetSOFrmShpCode').val();
        let tSODataShopName = $('#oetSOFrmShpName').val();
        let tSODataPosCode  = $('#oetSOFrmPosCode').val();
        let tSODataPosName  = $('#oetSOFrmPosName').val();
    

        let nCountDataInTable = $('#otbSODocPdtAdvTableList tbody .xWPdtItem').length;
        if(nCountDataInTable > 0 && tSODataMchCode != "" && tSODataShopCode != "" && tSODataWahCode != ""){
            // Show Modal Notification Found Data In Table Doctemp Behide Change Shop 
            FSvCMNSetMsgWarningDialog("<p>รายการสินค้าที่ท่านเพิ่มไปแล้วจะถูกล้างค่าทิ้ง เมื่อท่านเปลี่ยนร้านค้าใหม่</p>");
            
            // Event CLick Close Massage And Delete Temp
            $('#odvModalWanning .xWBtnOK').click(function(evn){
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "dcmSOClearDataDocTemp",
                    data: {
                        'ptSODocNo' : $("#oetSODocNo").val()
                    },
                    cache: false,
                    success: function (oResult){
                        var aDataReturn     = JSON.parse(oResult);
                        var tMessageError   = aDataReturn['tStaMessg'];
                        switch(aDataReturn['nStaReturn']){
                            case 1:
                                JSvSOLoadPdtDataTableHtml();
                                JCNxCloseLoading();
                            break;
                            case 800:
                                FSvCMNSetMsgErrorDialog(tMessageError);
                            break;
                            case 500:
                                FSvCMNSetMsgErrorDialog(tMessageError);
                            break;
                        }
                        $('#odvModalWanning .xWBtnOK').unbind();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            });
        }

        if(tSesUsrLevel == 'HQ' || tSesUsrLevel == 'BCH'){
            if(typeof(tSOShpName) != undefined && tSOShpName != ''){
                // if(tSOShpType == 4){
                    $('#obtSOBrowsePos').attr('disabled',false).removeClass('disabled');
                    // $('#obtSOBrowseWahouse').attr('disabled',true).addClass('disabled');
                    // $('#oetSOFrmWahCode').val(tSOWahCode);
                    // $('#oetSOFrmWahName').val(tSOWahName);
                // }else{
                    // $('#oetSOFrmWahCode').val(tSOWahCode);
                    // $('#oetSOFrmWahName').val(tSOWahName);
                    // $('#obtSOBrowsePos').attr('disabled',true).addClass('disabled');
                    // $('#obtSOBrowseWahouse').attr('disabled',true).addClass('disabled');
                // }
            }else{
                $('#obtSOBrowsePos').attr('disabled',true).addClass('disabled');
                $('#oetSOFrmWahCode,#oetSOFrmWahName').val('');
            }
            $('#oetSOFrmPosCode,#oetSOFrmPosName').val('');
        }

    }

    // Functionality : Function Behind NextFunc เครื่องจุดขาย
    function JSxSOSetConditionPos(poDataNextFunc){

        var aDataNextFunc,tSOBchCode,tSOShpCode,tSOPosCode,tSOWahCode,tSOWahName;
        if(typeof(poDataNextFunc) != undefined && poDataNextFunc != "NULL"){
            aDataNextFunc   = JSON.parse(poDataNextFunc);
            // tSOBchCode      = aDataNextFunc[0];
            // tSOShpCode      = aDataNextFunc[1];
            tSOPosCode      = aDataNextFunc[0];
            tSOWahCode      = aDataNextFunc[1];
            tSOWahName      = aDataNextFunc[2];
            $('#oetSOFrmWahCode').val(tSOWahCode);
            $('#oetSOFrmWahName').val(tSOWahName);
            $('#obtSOBrowsePos').attr('disabled',false).removeClass('disabled');
            $('#obtSOBrowseWahouse').attr('disabled',true).addClass('disabled');
        }else{
            $('#oetSOFrmPosCode,#oetSOFrmPosCode').val('');
            // $('#oetSOFrmWahCode').val('');
            // $('#oetSOFrmWahName').val('');
            return;
        }
        // $('#obtSOBrowseWahouse').attr('disabled',true).addClass('disabled');
        // $('#obtSOBrowseWahouse').attr('disabled',false).removeClass('disabled');
    
    }

    // Functionality : Function Behind NextFunc Supllier
    function JSxSOSetConditionAfterSelectSpl(poDataNextFunc){
        var aData;
        if (poDataNextFunc  != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            var poParams = {
                FNSplCrTerm         : aData[0],
                FCSplCrLimit        : aData[1],
                FTSplStaVATInOrEx   : aData[2],
                FTSplTspPaid        : aData[3],
                FTSplCode           : aData[4],
                FTSplName           : aData[5]
            };
            JSxSOSetPanelSupplierData(poParams);
        }
    }

    function JSxSOSetConditionAfterSelectCst(poDataNextFunc){
        aData = JSON.parse(poDataNextFunc);
        if (poDataNextFunc  != "NULL") {
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

                    var poParams = {
                        FTCstCode     : aData[0],
                        FTCstName     : aData[1],
                        FTCstCtzID    : aData[2],
                        FTCstTel      : aData[3],
                        FTPplCode     : aData[4],
                        FTCstDiscRet  : aData[5],
                        FTCstStaAlwPosCalSo : aData[6],
                        FTAddV2Desc1  : tAddfull
                    };
                    JSxSOSetPanelCustomerData(poParams);
                    JSxSOClearDTTmp(aData[5]);
                    
                },
                error: function (jqXHR,textStatus,errorThrown){

                }
            });
        }
    }

    // Functionality : Posecc AddDisTmpCst
    function JSxSOPocessAddDisTmpCst(rtCstDiscRet){
        $.ajax({
            type: "POST",
            url: "dcmSOPocessAddDisTmpCst",
            
            data : {
                tCstDiscRet : rtCstDiscRet,
                tBchCode    : $('#oetSOFrmBchCode').val(),
                tDocNo      : $('#oetSODocNo').val(),
                tVatInOrEx  : $('#ocmSOFrmSplInfoVatInOrEx').val(), // 1: รวมใน, 2: แยกนอก
                },
            cache: false,
            Timeout: 0,
            success: function (oResult) {
                var aDataReturn = JSON.parse(oResult);
                if(aDataReturn['nStaEvent'] == '1'){
                    JSvSOLoadPdtDataTableHtml();
                }else{
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    function JSxSOSetPanelCustomerData(poParams){
        $("#oetSOFrmCstHNNumber").val(poParams.FTCstCode);
        $("#oetSOFrmCstCtzID").val(poParams.FTCstCtzID);
        $("#oetSOFrmCustomerName").val(poParams.FTCstName);
        $("#oetSOFrmCstTel").val(poParams.FTCstTel);
        $('#ohdSOPplCodeCst').val(poParams.FTPplCode);
        $('#otaSOCustomerAddress').val(poParams.FTAddV2Desc1);
        if(poParams.FTCstStaAlwPosCalSo==1){
            $('#ocbSOStaAlwPosCalSo').prop('checked',true);
        }else{
            $('#ocbSOStaAlwPosCalSo').prop('checked',false);
        }
    }

    // Functionality : ฟังก์ชั่นเซทข้อมูล ผู้จำหน่าย
    function JSxSOSetPanelSupplierData(poParams){
        // Reset Panel เป็นค่าเริ่มต้น
        $("#ocmSOFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
        $("#ocmSOFrmSplInfoPaymentType.selectpicker").val("2").selectpicker("refresh");
        $("#ocmSOFrmSplInfoDstPaid.selectpicker").val("1").selectpicker("refresh");
        $("#oetSOFrmSplInfoCrTerm").val("");

        // ประเภทภาษี
        if(poParams.FTSplStaVATInOrEx === "1"){
            // รวมใน
            $("#ocmSOFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
        }else{
            // แยกนอก
            $("#ocmSOFrmSplInfoVatInOrEx.selectpicker").val("2").selectpicker("refresh");
        }

        // ประเภทชำระเงิน
        if(poParams.FCSplCrLimit > 0){
            // เงินเชื่อ
            $("#ocmSOFrmSplInfoPaymentType.selectpicker").val("2").selectpicker("refresh");
        }else{
            // เงินสด
            $("#ocmSOFrmSplInfoPaymentType.selectpicker").val("1").selectpicker("refresh");
        }

        // การชำระเงิน
        if(poParams.FTSplTspPaid === "1"){ // ต้นทาง
            $("#ocmSOFrmSplInfoDstPaid.selectpicker").val("1").selectpicker("refresh");
        }else{ // ปลายทาง
            $("#ocmSOFrmSplInfoDstPaid.selectpicker").val("2").selectpicker("refresh");
        }
        
        // ระยะเครดิต
        $("#oetSOFrmSplInfoCrTerm").val(poParams.FCSplCrLimit);
    }

    $('#obtSOAdvTablePdtDTTemp').unbind().click(function(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxSOOpenColumnFormSet();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#odvSOOrderAdvTblColumns #obtSOSaveAdvTableColums').unbind().click(function(){
        JSxSOSaveColumnShow();
    });

    // Functionality : Call Advnced Table 
    function JSxSOOpenColumnFormSet(){
        $.ajax({
            type: "POST",
            url: "dcmSOAdvanceTableShowColList",
            cache: false,
            Timeout: 0,
            success: function (oResult) {
                var aDataReturn = JSON.parse(oResult);
                if(aDataReturn['nStaEvent'] == '1'){
                    var tViewTableShowCollist   = aDataReturn['tViewTableShowCollist'];
                    $('#odvSOOrderAdvTblColumns .modal-body').html(tViewTableShowCollist);
                    $('#odvSOOrderAdvTblColumns').modal({backdrop: 'static', keyboard: false})  
                    $("#odvSOOrderAdvTblColumns").modal({ show: true });
                }else{
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Functionality : Save Columns Show Advanced Table
    function JSxSOSaveColumnShow(){
        // คอลัมน์ที่เลือกให้แสดง
        var aSOColShowSet = [];
        $("#odvSOOrderAdvTblColumns .xWPIInputColStaShow:checked").each(function(){
            aSOColShowSet.push($(this).data("id"));
        });

        // คอลัมน์ทั้งหมด
        var aSOColShowAllList = [];
        $("#odvSOOrderAdvTblColumns .xWPIInputColStaShow").each(function () {
            aSOColShowAllList.push($(this).data("id"));
        });

        // ชื่อคอลัมน์ทั้งหมดในกรณีมีการแก้ไขชื่อคอลัมน์ที่แสดง
        var aSOColumnLabelName = [];
        $("#odvSOOrderAdvTblColumns .xWPILabelColumnName").each(function () {
            aSOColumnLabelName.push($(this).text());
        });

        // สถานะย้อนกลับค่าเริ่มต้น
        var nSOStaSetDef;
        if($("#odvSOOrderAdvTblColumns #ocbSOSetDefAdvTable").is(":checked")) {
            nSOStaSetDef   = 1;
        } else {
            nSOStaSetDef   = 0;
        }

        $.ajax({
            type: "POST",
            url: "dcmSOAdvanceTableShowColSave",
            data: {
                'pnSOStaSetDef'         : nSOStaSetDef,
                'paSOColShowSet'        : aSOColShowSet,
                'paSOColShowAllList'    : aSOColShowAllList,
                'paSOColumnLabelName'   : aSOColumnLabelName
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                $("#odvSOOrderAdvTblColumns").modal("hide");
                $(".modal-backdrop").remove();
                JSvSOLoadPdtDataTableHtml();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    $('#obtSOFrmBrowseShipAdd').unbind().click(function(){
        $('#odvSOBrowseShipAdd').modal({backdrop: 'static', keyboard: false})  
        $('#odvSOBrowseShipAdd').modal('show');
    });

    // Option Browse Shipping Address
    var oSOBrowseShipAddress    = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tSOWhereCons        = poDataFnc.tSOWhereCons;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var oOptionReturn       = {
            Title : ['document/purchaseinvoice/purchaseinvoice','tSOShipAddress'],
            Table : {Master:'TCNMAddress_L',PK:'FNAddSeqNo'},
            Join : {
            Table : ['TCNMProvince_L','TCNMDistrict_L','TCNMSubDistrict_L'],
                On : [
                    "TCNMAddress_L.FTAddV1PvnCode = TCNMProvince_L.FTPvnCode AND TCNMProvince_L.FNLngID = "+nLangEdits,
                    "TCNMAddress_L.FTAddV1DstCode = TCNMDistrict_L.FTDstCode AND TCNMDistrict_L.FNLngID = "+nLangEdits,
                    "TCNMAddress_L.FTAddV1SubDist = TCNMSubDistrict_L.FTSudCode AND TCNMSubDistrict_L.FNLngID = "+nLangEdits
                ]
            },
            Where : {
                Condition : [tSOWhereCons]
            },
            GrideView:{
                ColumnPathLang	: 'document/purchaseinvoice/purchaseinvoice',
                ColumnKeyLang	: [
                    'tSOShipADDBch',
                    'tSOShipADDSeq',
                    'tSOShipADDV1No',
                    'tSOShipADDV1Soi',
                    'tSOShipADDV1Village',
                    'tSOShipADDV1Road',
                    'tSOShipADDV1SubDist',
                    'tSOShipADDV1DstCode',
                    'tSOShipADDV1PvnCode',
                    'tSOShipADDV1PostCode'
                ],
                DataColumns		: [
                    'TCNMAddress_L.FTAddRefCode',
                    'TCNMAddress_L.FNAddSeqNo',
                    'TCNMAddress_L.FTAddV1No',
                    'TCNMAddress_L.FTAddV1Soi',
                    'TCNMAddress_L.FTAddV1Village',
                    'TCNMAddress_L.FTAddV1Road',
                    'TCNMAddress_L.FTAddV1SubDist',
                    'TCNMAddress_L.FTAddV1DstCode',
                    'TCNMAddress_L.FTAddV1PvnCode',
                    'TCNMAddress_L.FTAddV1PostCode',
                    'TCNMSubDistrict_L.FTSudName',
                    'TCNMDistrict_L.FTDstName',
                    'TCNMProvince_L.FTPvnName',
                    'TCNMAddress_L.FTAddV2Desc1',
                    'TCNMAddress_L.FTAddV2Desc2'
                ],
                DataColumnsFormat : ['','','','','','','','','','','','','','',''],
                ColumnsSize     : [''],
                DisabledColumns	:[10,11,12,13,14],
                Perpage			: 10,
                WidthModal      : 50,
                OrderBy			: ['TCNMAddress_L.FTAddRefCode ASC'],
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
            BrowseLev : 1
        };
        return oOptionReturn;
    };

    // Event Browse Shipping Address
    $('#odvSOBrowseShipAdd #oliSOEditShipAddress').unbind().click(function(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tSOWhereCons    = "";
            if($("#oetSOFrmBchCode").val() != ""){
                if($("#oetSOFrmMerCode").val() != ""){
                    if($("#oetSOFrmShpCode").val() != ""){
                        if($("#oetSOFrmPosCode").val() != ""){
                            // Address Ref POS
                            tSOWhereCons    +=  "AND FTAddGrpType = 6 AND FTAddRefCode = '"+$("#oetSOFrmPosCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits;
                        }else{
                            // Address Ref SHOP
                            tSOWhereCons    +=  "AND FTAddGrpType = 4 AND FTAddRefCode = '"+$("#oetSOFrmShpCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits;
                        }
                    }else{
                        // Address Ref BCH
                        tSOWhereCons        +=  "AND FTAddGrpType = 1 AND FTAddRefCode = '"+$("#oetSOFrmBchCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits;
                    }
                }else{
                    // Address Ref BCH
                    tSOWhereCons            +=  "AND FTAddGrpType = 1 AND FTAddRefCode = '"+$("#oetSOFrmBchCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits;
                }
            }
            // Call Option Modal
            window.oSOBrowseShipAddressOption   = undefined;
            oSOBrowseShipAddressOption          = oSOBrowseShipAddress({
                'tReturnInputCode'  : 'ohdSOShipAddSeqNo',
                'tReturnInputName'  : 'ohdSOShipAddSeqNo',
                'tSOWhereCons'     : tSOWhereCons,
                'tNextFuncName'     : 'JSvSOGetShipAddrData',
                'aArgReturn'        : [
                    'FNAddSeqNo',
                    'FTAddV1No',
                    'FTAddV1Soi',
                    'FTAddV1Village',
                    'FTAddV1Road',
                    'FTSudName',
                    'FTDstName',
                    'FTPvnName',
                    'FTAddV1PostCode',
                    'FTAddV2Desc1',
                    'FTAddV2Desc2']
            });
            $("#odvSOBrowseShipAdd").modal("hide");
            $('.modal-backdrop').remove();
            JCNxBrowseData('oSOBrowseShipAddressOption');
        }else{
            $("#odvSOBrowseShipAdd").modal("hide");
            $('.modal-backdrop').remove();
            JCNxShowMsgSessionExpired();
        }
    });

    //Functionality : Behind NextFunc Browse Shippinh Address
    function JSvSOGetShipAddrData(paInForCon){
        if(paInForCon !== "NULL") {
            var aDataReturn = JSON.parse(paInForCon);
            $("#ospSOShipAddAddV1No").text((aDataReturn[1] != "")      ? aDataReturn[1]    : '-');
            $("#ospSOShipAddV1Soi").text((aDataReturn[2] != "")        ? aDataReturn[2]    : '-');
            $("#ospSOShipAddV1Village").text((aDataReturn[3] != "")    ? aDataReturn[3]    : '-');
            $("#ospSOShipAddV1Road").text((aDataReturn[4] != "")       ? aDataReturn[4]    : '-');
            $("#ospSOShipAddV1SubDist").text((aDataReturn[5] != "")    ? aDataReturn[5]    : '-');
            $("#ospSOShipAddV1DstCode").text((aDataReturn[6] != "")    ? aDataReturn[6]    : '-');
            $("#ospSOShipAddV1PvnCode").text((aDataReturn[7] != "")    ? aDataReturn[7]    : '-');
            $("#ospSOShipAddV1PostCode").text((aDataReturn[8] != "")   ? aDataReturn[8]    : '-');
            $("#ospSOShipAddV2Desc1").text((aDataReturn[9] != "")      ? aDataReturn[9]    : '-');
            $("#ospSOShipAddV2Desc2").text((aDataReturn[10] != "")     ? aDataReturn[10]   : '-');
        }else{
            $("#ospSOShipAddAddV1No").text("-");
            $("#ospSOShipAddV1Soi").text("-");
            $("#ospSOShipAddV1Village").text("-");
            $("#ospSOShipAddV1Road").text("-");
            $("#ospSOShipAddV1SubDist").text("-");
            $("#ospSOShipAddV1DstCode").text("-");
            $("#ospSOShipAddV1PvnCode").text("-");
            $("#ospSOShipAddV1PostCode").text("-");
            $("#ospSOShipAddV2Desc1").text("-");
            $("#ospSOShipAddV2Desc2").text("-");
        }
        $("#odvSOBrowseShipAdd").modal("show");
    }

    //Functionality : Add Shiping Add To Input
    function JSnSOShipAddData(){
        var tSOShipAddSeqNoSelect   = $('#ohdSOShipAddSeqNo').val();
        $('#ohdSOFrmShipAdd').val(tSOShipAddSeqNoSelect);
        $("#odvSOBrowseShipAdd").modal("hide");
        $('.modal-backdrop').remove();
    }

    $('#obtSOFrmBrowseTaxAdd').unbind().click(function(){
        $('#odvSOBrowseTexAdd').modal({backdrop: 'static', keyboard: false})  
        $('#odvSOBrowseTexAdd').modal('show');
    });

    // Option Browse Shipping Address
    var oSOBrowseTexAddress     = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tSOWhereCons        = poDataFnc.tSOWhereCons;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var oOptionReturn       = {
            Title   : ['document/purchaseinvoice/purchaseinvoice','tSOTexAddress'],
            Table   : {Master:'TCNMAddress_L',PK:'FNAddSeqNo'},
            Join    : {
                Table   : ['TCNMProvince_L','TCNMDistrict_L','TCNMSubDistrict_L'],
                On      : [
                    "TCNMAddress_L.FTAddV1PvnCode = TCNMProvince_L.FTPvnCode AND TCNMProvince_L.FNLngID = "+nLangEdits,
                    "TCNMAddress_L.FTAddV1DstCode = TCNMDistrict_L.FTDstCode AND TCNMDistrict_L.FNLngID = "+nLangEdits,
                    "TCNMAddress_L.FTAddV1SubDist = TCNMSubDistrict_L.FTSudCode AND TCNMSubDistrict_L.FNLngID = "+nLangEdits
                ]
            },
            Where : {
                Condition : [tSOWhereCons]
            },
            GrideView:{
                ColumnPathLang	: 'document/purchaseinvoice/purchaseinvoice',
                ColumnKeyLang	: [
                    'tSOTexADDBch',
                    'tSOTexADDSeq',
                    'tSOTexADDV1No',
                    'tSOTexADDV1Soi',
                    'tSOTexADDV1Village',
                    'tSOTexADDV1Road',
                    'tSOTexADDV1SubDist',
                    'tSOTexADDV1DstCode',
                    'tSOTexADDV1PvnCode',
                    'tSOTexADDV1PostCode'
                ],
                DataColumns		: [
                    'TCNMAddress_L.FTAddRefCode',
                    'TCNMAddress_L.FNAddSeqNo',
                    'TCNMAddress_L.FTAddV1No',
                    'TCNMAddress_L.FTAddV1Soi',
                    'TCNMAddress_L.FTAddV1Village',
                    'TCNMAddress_L.FTAddV1Road',
                    'TCNMAddress_L.FTAddV1SubDist',
                    'TCNMAddress_L.FTAddV1DstCode',
                    'TCNMAddress_L.FTAddV1PvnCode',
                    'TCNMAddress_L.FTAddV1PostCode',
                    'TCNMSubDistrict_L.FTSudName',
                    'TCNMDistrict_L.FTDstName',
                    'TCNMProvince_L.FTPvnName',
                    'TCNMAddress_L.FTAddV2Desc1',
                    'TCNMAddress_L.FTAddV2Desc2'
                ],
                DataColumnsFormat : ['','','','','','','','','','','','','','',''],
                ColumnsSize     : [''],
                DisabledColumns	:[10,11,12,13,14],
                Perpage			: 10,
                WidthModal      : 50,
                OrderBy			: ['TCNMAddress_L.FTAddRefCode ASC'],
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
            BrowseLev : 1
        };
        return oOptionReturn;
    };

    // Event Browse Shipping Address
    $('#odvSOBrowseTexAdd #oliSOEditTexAddress').unbind().click(function(){
        // var nStaSession = JCNxFuncChkSessionExpired();
        var nStaSession = 1;
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tSOWhereCons    = "AND FTAddGrpType = 1 AND FTAddRefCode = '"+$("#oetSOFrmBchCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits;
            // Call Option Modal
            window.oSOBrowseTexAddressOption    = undefined;
            oSOBrowseTexAddressOption           = oSOBrowseTexAddress({
                'tReturnInputCode'  : 'ohdSOTexAddSeqNo',
                'tReturnInputName'  : 'ohdSOTexAddSeqNo',
                'tSOWhereCons'     : tSOWhereCons,
                'tNextFuncName'     : 'JSvSOGetTexAddrData',
                'aArgReturn'        : [
                    'FNAddSeqNo',
                    'FTAddV1No',
                    'FTAddV1Soi',
                    'FTAddV1Village',
                    'FTAddV1Road',
                    'FTSudName',
                    'FTDstName',
                    'FTPvnName',
                    'FTAddV1PostCode',
                    'FTAddV2Desc1',
                    'FTAddV2Desc2']
            });
            $("#odvSOBrowseTexAdd").modal("hide");
            $('.modal-backdrop').remove();
            JCNxBrowseData('oSOBrowseTexAddressOption');
        }else{
            $("#odvSOBrowseTexAdd").modal("hide");
            $('.modal-backdrop').remove();
            JCNxShowMsgSessionExpired();
        }
    });
    
    //Functionality : Behind NextFunc Browse Shippinh Address
    function JSvSOGetTexAddrData(paInForCon){
        if(paInForCon !== "NULL") {
            var aDataReturn = JSON.parse(paInForCon);
            $("#ospSOTexAddAddV1No").text((aDataReturn[1] != "")      ? aDataReturn[1]    : '-');
            $("#ospSOTexAddV1Soi").text((aDataReturn[2] != "")        ? aDataReturn[2]    : '-');
            $("#ospSOTexAddV1Village").text((aDataReturn[3] != "")    ? aDataReturn[3]    : '-');
            $("#ospSOTexAddV1Road").text((aDataReturn[4] != "")       ? aDataReturn[4]    : '-');
            $("#ospSOTexAddV1SubDist").text((aDataReturn[5] != "")    ? aDataReturn[5]    : '-');
            $("#ospSOTexAddV1DstCode").text((aDataReturn[6] != "")    ? aDataReturn[6]    : '-');
            $("#ospSOTexAddV1PvnCode").text((aDataReturn[7] != "")    ? aDataReturn[7]    : '-');
            $("#ospSOTexAddV1PostCode").text((aDataReturn[8] != "")   ? aDataReturn[8]    : '-');
            $("#ospSOTexAddV2Desc1").text((aDataReturn[9] != "")      ? aDataReturn[9]    : '-');
            $("#ospSOTexAddV2Desc2").text((aDataReturn[10] != "")     ? aDataReturn[10]   : '-');
        }else{
            $("#ospSOTexAddAddV1No").text("-");
            $("#ospSOTexAddV1Soi").text("-");
            $("#ospSOTexAddV1Village").text("-");
            $("#ospSOTexAddV1Road").text("-");
            $("#ospSOTexAddV1SubDist").text("-");
            $("#ospSOTexAddV1DstCode").text("-");
            $("#ospSOTexAddV1PvnCode").text("-");
            $("#ospSOTexAddV1PostCode").text("-");
            $("#ospSOTexAddV2Desc1").text("-");
            $("#ospSOTexAddV2Desc2").text("-");
        }
        $("#odvSOBrowseTexAdd").modal("show");
    }

    //Functionality : Add Shiping Add To Input
    function JSnSOTexAddData(){
        var tSOTexAddSeqNoSelect    = $('#ohdSOTexAddSeqNo').val();
        $('#ohdSOFrmTaxAdd').val(tSOTexAddSeqNoSelect);
        $("#odvSOBrowseTexAdd").modal("hide");
        $('.modal-backdrop').remove();
    }
    
    // Functionality: Check Status Document Process EQ And Call Back MQ
    function JSxSOChkStaDocCallModalMQ(){
        var nSOLangEdits        = nLangEdits;
        var tSOFrmBchCode       = $("#oetSOFrmBchCode").val();
        var tSOUsrApv           = $("#ohdSOApvCodeUsrLogin").val();
        var tSODocNo            = $("#oetSODocNo").val();
        var tSOPrefix           = "RESPPI";
        var tSOStaApv           = $("#ohdSOStaApv").val();
        var tSOStaPrcStk        = $("#ohdSOStaPrcStk").val();
        var tSOStaDelMQ         = $("#ohdSOStaDelMQ").val();
        var tSOQName            = tSOPrefix + "_" + tSODocNo + "_" + tSOUsrApv;
        var tSOTableName        = "TARTSoHD";
        var tSOFieldDocNo       = "FTXphDocNo";
        var tSOFieldStaApv      = "FTXphStaPrcStk";
        var tSOFieldStaDelMQ    = "FTXphStaDelMQ";

        // MQ Message Config
        var poDocConfig = {
            tLangCode     : nSOLangEdits,
            tUsrBchCode   : tSOFrmBchCode,
            tUsrApv       : tSOUsrApv,
            tDocNo        : tSODocNo,
            tPrefix       : tSOPrefix,
            tStaDelMQ     : tSOStaDelMQ,
            tStaApv       : tSOStaApv,
            tQName        : tSOQName
        };

       // RabbitMQ STOMP Config
        var poMqConfig = {
            host: "ws://" + oSTOMMQConfig.host + ":15674/ws",
            username: oSTOMMQConfig.user,
            password: oSTOMMQConfig.password,
            vHost: oSTOMMQConfig.vhost
        };
        
        // Update Status For Delete Qname Parameter
        var poUpdateStaDelQnameParams   = {
            ptDocTableName      : tSOTableName,
            ptDocFieldDocNo     : tSOFieldDocNo,
            ptDocFieldStaApv    : tSOFieldStaApv,
            ptDocFieldStaDelMQ  : tSOFieldStaDelMQ,
            ptDocStaDelMQ       : tSOStaDelMQ,
            ptDocNo             : tSODocNo
        };

        // Callback Page Control(function)
        var poCallback = {
            tCallPageEdit: "JSvSOCallPageEditDoc",
            tCallPageList: "JSvSOCallPageList"
        };
        
        // Check Show Progress %
        if(tSODocNo != '' && (tSOStaApv == 2 || tSOStaPrcStk == 2)){
            FSxCMNRabbitMQMessage(poDocConfig,poMqConfig,poUpdateStaDelQnameParams,poCallback);
        }

        // Check Delete MQ SubScrib
        if(tSOStaApv == 1 && tSOStaPrcStk == 1 && tSOStaDelMQ == ""){
            var poDelQnameParams    = {
                ptPrefixQueueName   : tSOPrefix,
                ptBchCode           : tSOFrmBchCode,
                ptDocNo             : tSODocNo,
                ptUsrCode           : tSOUsrApv
            };
            FSxCMNRabbitMQDeleteQname(poDelQnameParams);
            FSxCMNRabbitMQUpdateStaDeleteQname(poUpdateStaDelQnameParams);
        }
    }
        
    //พิมพ์
    function JSxSOPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'}, // Lang ID
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'}, // Company Code
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tSOBchCode); ?>'},
            {"DocCode"      : '<?=$tSODocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tSOBchCode;?>'},
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillSO?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }        

    function JSxSOClearDTTmp(ptDataDisTmp){
        JCNxOpenLoading();
                        $.ajax({
                            type: "POST",
                            url: "dcmSOClearDataDocTemp",
                            data: {
                                'ptSODocNo' : $("#oetSODocNo").val()
                            },
                            cache: false,
                            success: function (oResult){
                                var aDataReturn     = JSON.parse(oResult);
                                var tMessageError   = aDataReturn['tStaMessg'];
                                switch(aDataReturn['nStaReturn']){
                                    case 1:
                                        if(ptDataDisTmp!='' && ptDataDisTmp!=null && ptDataDisTmp!=' '){
                                            
                                                JSxSOPocessAddDisTmpCst(ptDataDisTmp);
                                            }else{
                                        JSvSOLoadPdtDataTableHtml();
                                        JCNxCloseLoading();
                                            }
                            
                                    break;
                                    case 800:
                                        FSvCMNSetMsgErrorDialog(tMessageError);
                                    break;
                                    case 500:
                                        FSvCMNSetMsgErrorDialog(tMessageError);
                                    break;
                                }
                                $('#odvModalWanning .xWBtnOK').unbind();
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                JCNxResponseError(jqXHR, textStatus, errorThrown);
                            }
                        });
    }

    $('#obtBrowseSOBCH').click(function(){ 
        JCNxBrowseData('oBrowse_BCH'); 
    });

    tUsrLevel   = "<?=$this->session->userdata('tSesUsrLevel')?>";
    tBchMulti   = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
    tSQLWhereBch = "";

    if(tUsrLevel != "HQ"){
        tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
    }
    
    var oBrowse_BCH = {
        Title   : ['company/branch/branch','tBCHTitle'],
        Table   : {Master:'TCNMBranch',PK:'FTBchCode',PKName:'FTBchName'},
        Join    : {
            Table   : ['TCNMBranch_L','TCNMWaHouse_L'],
            On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID ='+nLangEdits,
                        'TCNMBranch.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMBranch.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID ='+nLangEdits,
            ]
        },
        Where : {
            Condition : [tSQLWhereBch]
        },
        GrideView:{
            ColumnPathLang : 'company/branch/branch',
            ColumnKeyLang : ['tBCHCode','tBCHName',''],
            ColumnsSize     : ['15%','75%',''],
            WidthModal      : 50,
            DataColumns  : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName','TCNMWaHouse_L.FTWahCode','TCNMWaHouse_L.FTWahName'],
            DataColumnsFormat : ['',''],
            DisabledColumns   : [2,3],
            Perpage   : 5,
            OrderBy   : ['TCNMBranch_L.FTBchName'],
            SourceOrder  : "ASC"
        },
        CallBack:{
            ReturnType : 'S',
            Value  : ["demo","TCNMBranch.FTBchCode"],
            Text  : ["demo","TCNMBranch_L.FTBchName"],
        },
        NextFunc    :   {
            FuncName    :   'JSxSetDefauleWahouse',
            ArgReturn   :   ['FTBchCode','FTBchName','FTWahCode','FTWahName']
        }
    }
    
    function JSxSetDefauleWahouse(ptData){
        if(ptData == '' || ptData == 'NULL'){
            $('#oetSOFrmWahCode').val('');
            $('#oetSOFrmWahName').val('');
        }else{
            var tResult = JSON.parse(ptData);
            //เช็คค่าเก่ากับค่าใหม่ ก่อนจะเเจ้งเตือนให้ล้างค่า
            if($('#oetSOFrmBchCode').val() == tResult[0]){
                
            }else{
                nRowCount = $('#otbSODocPdtAdvTableList >tbody >tr').length;
                if(nRowCount >= 1){
                    if($('#otbSODocPdtAdvTableList >tbody >tr > td').hasClass('xWPITextNotfoundDataPdtTable') == true){
                        $('#oetSOFrmBchCode').val(tResult[0]);
                        $('#oetSOFrmBchName').val(tResult[1]);
                    }else{
                        //แจ้งเตือนว่ามีการเปลี่ยนค่า
                        $('#odvSOModalChangeBCH #odvWarningTxt').html('<p><?php echo language('document/saleorder/saleorder', 'tSoWarningChangeBch');?></p>');
                        $('#odvSOModalChangeBCH').modal('show');
                            $('#obtChangeBCH').on("click",function() {

                            $('.xWClearDataWhenChangeCst').val("");
                            $('#oetSORefIntDoc').val("");
                            $('#oetSORefIntDocDate').val("");
                            $('#oetSOFrmWahCode').val("");
                            $('#oetSOFrmWahName').val("");
                            $('#oetSOFrmCarCode').val("");
                            $('#oetSOFrmCarName').val("");
                            $('#oetSOFrmCarRegNo').val("");

                            $.ajax({
                                type: "POST",
                                url: "dcmSOClearDataDocTemp",
                                data: {
                                    'ptSODocNo' : $("#oetSODocNo").val()
                                },
                                cache: false,
                                Timeout: 0,
                                success: function (oResult){
                                    JSvSOLoadPdtDataTableHtml();
                                    $('#oetSOFrmBchCode').val(tResult[0]);
                                    $('#oetSOFrmBchName').val(tResult[1]);
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                                }
                            });
                        });
                    }
                }
            }

            $('#oetSOFrmWahCode').val(tResult[2]);
            $('#oetSOFrmWahName').val(tResult[3]);
        }
    }

    function JSxSOSetConditionFromGenSO(ptCstcode,ptBchCodeRef,ptDocref,pdDocDate){
        JCNxOpenLoading();
        if (ptCstcode  != "NULL") {
            $.ajax({
                type    : "POST",
                url     : "dcmSODataTableGenPOGetCst",
                data    :  {
                    'tCSTCode'       : ptCstcode,
                },
                catch   : false,
                timeout : 0,
                success : function (tResult){
                    aDataCstData = JSON.parse(tResult);
                    
                    $("#oetSOFrmCstCode").val(aDataCstData.FTCstCode);
                    $("#oetSOFrmCstName").val(aDataCstData.FTCstName);
                    $("#ohdSODocRefJump").val(ptDocref);
                    $("#ohdSODocRefBchJump").val(ptBchCodeRef);
                    
                    var poParams = {
                        FTCstCode     : aDataCstData.FTCstCode,
                        FTCstName     : aDataCstData.FTCstName,
                        FTCstCtzID    : aDataCstData.FTCstCardID,
                        FTCstTel      : aDataCstData.FTCstTel,
                        FTPplCode     : aDataCstData.FTPplCode,
                        FTCstDiscRet  : aDataCstData.FTCstDiscRet,
                        FTCstStaAlwPosCalSo : aDataCstData.FTCstStaAlwPosCalSo
                    };
                    JSxSOSetPanelCustomerData(poParams);
                    JSxSOClearDTTmp(aDataCstData.FTCstDiscRet);
                    JSxSOSetConditionFromGenSOAddress(aDataCstData.FTCstCode)
                    JSxSOSetConditionFromGenSOGetProduct(ptDocref,ptBchCodeRef,pdDocDate);
                },
                error: function (jqXHR,textStatus,errorThrown){

                }
            });
        }
    }

    function JSxSOSetConditionFromGenSOAddress(FTCstCode){
            $.ajax({
                type    : "POST",
                url     : "GetAddressCustomer",
                data    :  {
                    'tCSTCode'       : FTCstCode,
                },
                catch   : false,
                timeout : 0,
                async:false,
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
                    $("#otaSOCustomerAddress").val(tAddfull);
                },
                error: function (jqXHR,textStatus,errorThrown){

                }
            });
        
    }

    function JSxSOSetConditionFromGenSOGetProduct(ptDocref,ptBchCode,pdDocDate){
            $.ajax({
                type    : "POST",
                url     : "dcmSODataTableGenPOGetProduct",
                data    :  {
                    'tSODocNo': $('#oetSODocNo').val(),
                    'tSOFrmBchCode': $('#oetSOFrmBchCode').val(),
                    'tRefIntDocNo': ptDocref,
                    'tRefIntBchCode': ptBchCode,
                },
                catch   : false,
                timeout : 0,
                async:false,
                success : function (tResult){
                    JCNxCloseLoading();
                },
                error: function (jqXHR,textStatus,errorThrown){

                }
            });

            var spite = pdDocDate.split('/');
            var Fulltext = spite['2']+'-'+spite['1']+'-'+spite['0']

            $.ajax({
                    type    : "POST",
                    url     : "docSOEventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld'     : $('#oetSORefDocNoOld').val(),
                        'ptSODocNo'         : $('#oetSODocNo').val(),
                        'ptRefType'         : $('#ocbSORefType').val(),
                        'ptRefDocNo'        : ptDocref,
                        // 'pdRefDocDate'      : $('#oetSORefDocDate').val(),
                        'pdRefDocDate'      : Fulltext,
                        'ptRefKey'          : "PO",
                        'tDocRefType'       : "PO"
                    },
                    cache: false,
                    timeout: 0,
                    async:false,
                    success: function(oResult){
                        JSxSOEventClearValueInFormHDDocRef();

                        JSxSOCallPageHDDocRef();
                        JCNxCloseLoading();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
    }


    
</script>