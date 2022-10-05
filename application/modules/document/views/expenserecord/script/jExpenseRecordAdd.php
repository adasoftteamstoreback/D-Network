<script type="text/javascript">

    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit");?>';
    var tUsrApvName     = '<?php echo $this->session->userdata("tSesUsername");?>';
    var tSesUsrLevel    = '<?php echo $this->session->userdata('tSesUsrLevel');?>';
    var tUserBchCode    = '<?php echo $this->session->userdata("tSesUsrBchCodeDefault");?>';
    var tUserBchName    = '<?php echo $this->session->userdata("tSesUsrBchNameDefault");?>';
    var tUserWahCode    = '<?php echo $this->session->userdata("tSesUsrWahCode");?>';
    var tUserWahName    = '<?php echo $this->session->userdata("tSesUsrWahName");?>';
    var tRoute          = $('#ohdPXRoute').val();
    var tStaApv         = $('#ohdPXStaApv').val();
    var tStaDoc         = $('#ohdPXStaDoc').val();

    $(document).ready(function(){

        FSxPXCallPageHDDocRef();

        $('.selectpicker').selectpicker('refresh');

        if( tStaApv == '1' || tStaDoc == '3' ){
            $(".xWPXDisabledOnApv").attr("readonly",true);
        }

        if(tUserWahCode != '' && tRoute == 'docPXEventAdd'){
            $('#oetPXFrmWahCode').val(tUserWahCode);
            $('#oetPXFrmWahName').val(tUserWahName);
        }

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        $('.xCNTimePicker').datetimepicker({
            format: 'HH:mm:ss',
        });

        // $('.xCNMenuplus').unbind().click(function(){
        //     if($(this).hasClass('collapsed')){
        //         $('.xCNMenuplus').removeClass('collapsed').addClass('collapsed');
        //         $('.xCNMenuPanelData').removeClass('in');
        //     }
        // });

        $('.xWTooltipsBT').tooltip({'placement': 'bottom'});

        $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});

        $(".xWConditionSearchPdt.disabled").attr("disabled","disabled");


        $('#obtPXDocBrowsePdt').unbind().click(function(){
            var nStaSession = 1;
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                if($('#oetPXFrmSplCode').val()!=""){
                    JSxCheckPinMenuClose();
                    JCNvPXBrowsePdt();
                }else{
                    $('#odvPXModalPleseselectCustomer').modal('show');
                }
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        if($('#oetPXFrmBchCode').val() == ""){
            $("#obtPXFrmBrowseTaxAdd").attr("disabled","disabled");
        }

        /** =================== Event Search Function ===================== */
            $('#oliPXMngPdtScan').unbind().click(function(){
                var tPXSplCode  = $('#oetPXFrmSplCode').val();
                if(typeof(tPXSplCode) !== undefined && tPXSplCode !== ''){
                    //Hide
                    $('#oetPXFrmFilterPdtHTML').hide();
                    $('#obtPXMngPdtIconSearch').hide();

                    //Show
                    $('#oetPXFrmSearchAndAddPdtHTML').show();
                    $('#obtPXMngPdtIconScan').show();
                }else{
                    var tWarningMessage = 'โปรดเลือกผู้จำหน่ายก่อนทำรายการ';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                }
            });
            $('#oliPXMngPdtSearch').unbind().click(function(){
                //Hide
                $('#oetPXFrmSearchAndAddPdtHTML').hide();
                $('#obtPXMngPdtIconScan').hide();
                //Show
                $('#oetPXFrmFilterPdtHTML').show();
                $('#obtPXMngPdtIconSearch').show();
            });
        /** =============================================================== */

        /** ===================== Set Date Autometic Doc ========================  */
            var dCurrentDate    = new Date();
            var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
            var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes() + " " + tAmOrPm;

            if($('#oetPXDocDate').val() == ''){
                $('#oetPXDocDate').datepicker("setDate",dCurrentDate);
            }

            if($('#oetPXDocTime').val() == ''){
                $('#oetPXDocTime').val(tCurrentTime);
            }
        /** =============================================================== */

        /** =================== Event Date Function  ====================== */
            $('#obtPXDocDate').unbind().click(function(){
                $('#oetPXDocDate').datepicker('show');
            });

            $('#obtPXDocTime').unbind().click(function(){
                $('#oetPXDocTime').datetimepicker('show');
            });

            $('#obtPXBrowseRefIntDocDate').unbind().click(function(){
                $('#oetPXRefIntDocDate').datepicker('show');
            });

            $('#obtPXRefDocDate').unbind().click(function(){
                $('#oetPXRefDocDate').datepicker('show');
            });

            $('#obtPXBrowseRefExtDocDate').unbind().click(function(){
                $('#oetPXRefExtDocDate').datepicker('show');
            });

            $('#obtPXFrmSplInfoDueDate').unbind().click(function(){
                $('#oetPXFrmSplInfoDueDate').datepicker('show');
            });

            $('#obtPXFrmSplInfoBillDue').unbind().click(function(){
                $('#oetPXFrmSplInfoBillDue').datepicker('show');
            });

            $('#obtPXFrmSplInfoTnfDate').unbind().click(function(){
                $('#oetPXFrmSplInfoTnfDate').datepicker('show');
            });
        /** =============================================================== */

        /** ================== Check Box Auto GenCode ===================== */
            $('#ocbPXStaAutoGenCode').on('change', function (e) {
                if($('#ocbPXStaAutoGenCode').is(':checked')){
                    $("#oetPXDocNo").val('');
                    $("#oetPXDocNo").attr("readonly", true);
                    $('#oetPXDocNo').closest(".form-group").css("cursor","not-allowed");
                    $('#oetPXDocNo').css("pointer-events","none");
                    $("#oetPXDocNo").attr("onfocus", "this.blur()");
                    $('#ofmPXFormAdd').removeClass('has-error');
                    $('#ofmPXFormAdd .form-group').closest('.form-group').removeClass("has-error");
                    $('#ofmPXFormAdd em').remove();
                }else{
                    $('#oetPXDocNo').closest(".form-group").css("cursor","");
                    $('#oetPXDocNo').css("pointer-events","");
                    $('#oetPXDocNo').attr('readonly',false);
                    $("#oetPXDocNo").removeAttr("onfocus");
                }
            });
        /** =============================================================== */

        // $('#ocmPXFrmSplInfoVatInOrEx').on('change', function (e) {
        //     var nStaSession = JCNxFuncChkSessionExpired();
        //     if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        //         JCNxOpenLoading();
        //         JSvPXLoadPdtDataTableHtml();
        //     }else{
        //         JCNxShowMsgSessionExpired();
        //     }
        // });

        JSxPXChkStaDocCallModalMQ();

    });

    // ========================================== Brows Option Conditon ===========================================
        // ตัวแปร Option Browse Modal กลุ่มธุรกิจ
        var oMerchantOption = function(poDataFnc){
            var tPXBchCode          = poDataFnc.tPXBchCode;
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tWhereModal         = "";

            // สถานะกลุ่มธุรกิจต้องใช้งานเท่านั้น
            tWhereModal += " AND (TCNMMerchant.FTMerStaActive = 1)";

            // เช็คเงื่อนไขแสดงกลุ่มธุรกิจเฉพาะสาขาตัวเอง
            // if(typeof(tPXBchCode) != undefined && tPXBchCode != ""){
            //     tWhereModal += " AND ((SELECT COUNT(FTShpCode) FROM TCNMShop WHERE TCNMShop.FTMerCode = TCNMMerchant.FTMerCode AND TCNMShop.FTBchCode = '"+tPXBchCode+"') != 0) ";
            // }

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
                    Perpage			: 10,
                    OrderBy			: ['TCNMMerchant.FDCreateOn DESC'],
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
                BrowseLev: nPXStaPXBrowseType,
                // DebugSQL: true
            };
            return oOptionReturn;
        }

        // ตัวแปร Option Browse Modal ร้านค้า
        var oShopOption     = function(poDataFnc){
            var tPXBchCode          = poDataFnc.tPXBchCode;
            var tPXMerCode          = poDataFnc.tPXMerCode;
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tWhereModal         = "";

            // สถานะร้านค้าใช้งาน
            tWhereModal += " AND (TCNMShop.FTShpStaActive = 1)";

            // เช็คเงื่อนไขแสดงร้านค้าในสาขาตัวเอง
            if(typeof(tPXBchCode) != undefined && tPXBchCode != ""){
                tWhereModal += " AND ((TCNMShop.FTBchCode = '"+tPXBchCode+"') AND TCNMShop.FTShpType  != 5)"
            }

            // เช็คเงื่อนไขแสดงร้านค้าในกลุ่มธุรกิจตัวเอง
            if(typeof(tPXMerCode) != undefined && tPXMerCode != ""){
                tWhereModal += " AND ((TCNMShop.FTMerCode = '"+tPXMerCode+"') AND TCNMShop.FTShpType  != 5)";
            }

            tSHP        = "<?=$this->session->userdata("tSesUsrShpCodeMulti");?>";
            tSHPCount   = '<?=$this->session->userdata("nSesUsrShpCount");?>';
            if(tSHPCount < 2){
                tWhereModal += " ";
            }else{
                tWhereModal += " AND TCNMShop.FTShpCode IN("+tSHP+")";
            }

            // ตัวแปร ออฟชั่นในการ Return
            var oOptionReturn   = {
                Title: ["company/shop/shop","tSHPTitle_POS"],
                Table: {Master:"TCNMShop",PK:"FTShpCode"},
                Join: {
                    Table: ['TCNMShop_L','TCNMWaHouse_L'],
                    On: ['TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop.FTBchCode = TCNMShop_L.FTBchCode AND TCNMShop_L.FNLngID = '+nLangEdits,
                        'TCNMShop.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse_L.FTBchCode = TCNMShop.FTBchCode AND TCNMWaHouse_L.FNLngID= '+nLangEdits
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
                    DataColumns         : ['TCNMShop.FTShpCode','TCNMShop_L.FTShpName','TCNMShop.FTWahCode','TCNMWaHouse_L.FTWahName','TCNMShop.FTShpType','TCNMShop.FTBchCode'],
                    DataColumnsFormat   : ['','','','','',''],
                    DisabledColumns     : [2,3,4,5],
                    Perpage             : 10,
                    OrderBy			    : ['TCNMShop.FDCreateOn DESC'],
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
                BrowseLev : nPXStaPXBrowseType,
                // DebugSQL: true
            };
            return oOptionReturn;
        }

        // ตัวแปร Option Browse Modal เครื่องจุดขาย
        var oPosOption      = function(poDataFnc){
            var tPXBchCode          = poDataFnc.tPXBchCode;
            var tPXShpCode          = poDataFnc.tPXShpCode;
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tWhereModal         = "";

            // สถานะเครื่องจุดขายต้องใช้งาน
            tWhereModal +=  " AND (TCNMPos.FTPosStaUse  = 1)";

            // เช็คเงื่อนไขแสดงร้านค้าในสาขาตัวเอง
            if(typeof(tPXBchCode) != undefined && tPXBchCode != ""){
                tWhereModal += " AND ((TVDMPosShop.FTBchCode = '"+tPXBchCode+"') AND TVDMPosShop.FTPshStaUse = 1)";
            }

            // เช็คเงื่อนไขแสดงร้านค้าในร้านค้าตัวเอง
            if(typeof(tPXShpCode) != undefined && tPXShpCode != ""){
                tWhereModal += " AND ((TVDMPosShop.FTShpCode = '"+tPXShpCode+"') AND TVDMPosShop.FTPshStaUse = 1)";
            }

            // ตัวแปร ออฟชั่นในการ Return
            var oOptionReturn   = {
                Title: ["pos/posshop/posshop","tPshTitle"],
                Table: { Master:'TVDMPosShop', PK:'FTPosCode' },
                Join: {
                    Table: ['TCNMPos', 'TCNMPosLastNo', 'TCNMWaHouse', 'TCNMWaHouse_L'],
                    On: [
                        "TVDMPosShop.FTPosCode = TCNMPos.FTPosCode",
                        "TVDMPosShop.FTPosCode = TCNMPosLastNo.FTPosCode",
                        "TVDMPosShop.FTPosCode = TCNMWaHouse.FTWahRefCode AND TCNMWaHouse.FTWahStaType = 6",
                        "TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse_L.FNLngID = '"+nLangEdits+"'"
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
                    DataColumns: ['TVDMPosShop.FTPosCode', 'TCNMPosLastNo.FTPosComName', 'TVDMPosShop.FTShpCode', 'TVDMPosShop.FTBchCode', 'TCNMWaHouse.FTWahCode', 'TCNMWaHouse_L.FTWahName','TVDMPosShop.FTPshStaUse'],
                    DataColumnsFormat : ['', '', '', '', '', ''],
                    DisabledColumns: [2, 3, 4, 5, 6],
                    Perpage: 10,
                    OrderBy: ['TVDMPosShop.FDCreateOn DESC'],
                },
                CallBack: {
                    ReturnType  : 'S',
                    Value       : [tInputReturnCode,"TVDMPosShop.FTPosCode"],
                    Text        : [tInputReturnName,"TCNMPosLastNo.FTPosComName"]
                },
                NextFunc: {
                    FuncName    : tNextFuncName,
                    ArgReturn   : aArgReturn
                },
                RouteAddNew: 'salemachine',
                BrowseLev : nPXStaPXBrowseType
            };
            return oOptionReturn;
        }

        // ตัวแปร Option Browse Modal คลังสินค้า
        var oWahOption      = function(poDataFnc){
            var tPXShpCode          = poDataFnc.tPXShpCode;
            var tPXPosCode          = poDataFnc.tPXPosCode;
            var tPXBchCode          = poDataFnc.tPXBchCode;
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tWhereModal         = "";

            if(tPXBchCode != ""){
                tWhereModal += " AND TCNMWaHouse.FTBchCode='"+tPXBchCode+"' ";
                tWhereModal += " AND TCNMShpWah.FTWahCode IS NULL ";
            }

            //ไม่เอาคลังเครื่องจุดขาย
            tWhereModal += " AND (TCNMWaHouse.FTWahStaType NOT IN (6))";

            // Where คลังของ สาขา
            /*if(tPXShpCode == "" && tPXPosCode == ""){
                tWhereModal += " AND (TCNMWaHouse.FTWahStaType IN (1,2,5))";
            }*/

            // Where คลังของ ร้านค้า
            /*if(tPXShpCode  != "" && tPXPosCode == ""){
                tWhereModal += " AND (TCNMWaHouse.FTWahStaType IN (4))";
                tWhereModal += " AND (TCNMWaHouse.FTWahRefCode = '"+tPXShpCode+"')";
            }*/

            // Where คลังของ เครื่องจุดขาย
            /*if(tPXShpCode  != "" && tPXPosCode != ""){
                tWhereModal += " AND (TCNMWaHouse.FTWahStaType IN (6))";
                tWhereModal += " AND (TCNMWaHouse.FTWahRefCode = '"+tPXPosCode+"')";
            }*/

            var oOptionReturn   = {
                Title: ["company/warehouse/warehouse","tWAHTitle"],
                Table: { Master:"TCNMWaHouse", PK:"FTWahCode"},
                Join: {
                    Table: ["TCNMWaHouse_L","TCNMShpWah"],
                    On: [
                        " TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = '"+nLangEdits+"' ",
                        " TCNMWaHouse.FTBchCode = TCNMShpWah.FTBchCode AND TCNMWaHouse.FTWahCode = TCNMShpWah.FTWahCode "
                    ]
                },
                Where: {
                    Condition : [tWhereModal]
                },
                GrideView:{
                    ColumnPathLang: 'company/warehouse/warehouse',
                    ColumnKeyLang: ['tWahCode','tWahName'],
                    DataColumns: ['TCNMWaHouse.FTWahCode','TCNMWaHouse_L.FTWahName'],
                    DataColumnsFormat: ['',''],
                    ColumnsSize: ['15%','75%'],
                    Perpage: 10,
                    WidthModal: 50,
                    OrderBy: ['TCNMWaHouse.FDCreateOn DESC'],
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
                BrowseLev : nPXStaPXBrowseType,
                // DebugSQL : true
            }
            return oOptionReturn;
        }

        // ตัวแปร Option Browse Modal คลังสินค้า
        var oShopWahOption      = function(poDataFnc){
            var tPXShpCode          = poDataFnc.tPXShpCode;
            var tPXPosCode          = poDataFnc.tPXPosCode;
            var tPXBchCode          = poDataFnc.tPXBchCode;
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var tWhereModal         = "";

            if(tPXBchCode!=""){
                tWhereModal += " AND TCNMShpWah.FTBchCode='"+tPXBchCode+" '";
            }

            // Where คลังของ สาขา
            if(tPXShpCode == "" && tPXPosCode == ""){
                tWhereModal += " AND (TCNMWaHouse.FTWahStaType IN (1,2,5))";
            }

            // Where คลังของ ร้านค้า
            // if(tPXShpCode  != "" && tPXPosCode == ""){
            //     tWhereModal += " AND (TCNMWaHouse.FTWahStaType IN (4))";
            //     tWhereModal += " AND (TCNMShpWah.FTShpCode = '"+tPXShpCode+"')";
            // }

            // Where คลังของ เครื่องจุดขาย
            if(tPXShpCode  != ""){
                // tWhereModal += " AND (TCNMWaHouse.FTWahStaType IN (4))";
                tWhereModal += " AND (TCNMShpWah.FTShpCode = '"+tPXShpCode+"')";
            }


            var oOptionReturn = {
                Title   : ['company/shop/shop','tSHPWah'],
                Table   : {Master:'TCNMShpWah',PK:'FTWahCode'},
                Join    : {
                    Table   : ['TCNMWaHouse','TCNMWaHouse_L'],
                    On      : [
                    'TCNMShpWah.FTWahCode = TCNMWaHouse.FTWahCode AND TCNMShpWah.FTBchCode = TCNMWaHouse.FTBchCode ',
                    'TCNMWaHouse_L.FTWahCode = TCNMShpWah.FTWahCode AND TCNMWaHouse_L.FTBchCode = TCNMWaHouse.FTBchCode  AND TCNMWaHouse_L.FNLngID = '+nLangEdits,]
                },
                Where : {
                    Condition : [tWhereModal]
                },
                GrideView : {
                    ColumnPathLang  : 'company/shop/shop',
                    ColumnKeyLang   : ['tWahCode','tWahName'],
                    ColumnsSize     : ['15%','75%'],
                    WidthModal      : 50,
                    DataColumns     : ['TCNMShpWah.FTWahCode','TCNMWaHouse_L.FTWahName'],
                    DataColumnsFormat : ['',''],
                    Perpage         : 10,
                    OrderBy   : ['TCNMWaHouse.FDCreateOn DESC'],
                },
                CallBack : {
                    ReturnType : 'S',
                    Value  : [tInputReturnCode,"TCNMShpWah.FTWahCode"],
                    Text  : [tInputReturnName,"TCNMWaHouse_L.FTWahName"],
                },
                // DebugSQL : true
            }

            return oOptionReturn;
        }

        // ตัวแปร Option Browse Modal ตัวแทนจำหน่าย
        var oSplOption      = function(poDataFnc){
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
                Title: ['supplier/supplier/supplier', 'tSPLTitle'],
                Table: {Master:'TCNMSpl', PK:'FTSplCode'},
                Join: {
                    Table: ['TCNMSpl_L', 'TCNMSplCredit' , 'VCN_VatActive'],
                    On: [
                        'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
                        'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode',
                        'TCNMSpl.FTVatCode = VCN_VatActive.FTVatCode'
                    ]
                },
                Where:{
                    Condition : [ " AND TCNMSpl.FTSplStaActive = '1' " + tWhereAgency ]
                },
                GrideView:{
                    ColumnPathLang: 'supplier/supplier/supplier',
                    ColumnKeyLang: ['tSPLTBCode', 'tSPLTBName'],
                    ColumnsSize: ['15%', '75%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid','VCN_VatActive.FTVatCode','VCN_VatActive.FCVatRate'],
                    DataColumnsFormat: ['',''],
                    DisabledColumns: [2, 3, 4, 5 , 6 , 7],
                    Perpage: 10,
                    OrderBy: ['TCNMSpl.FDCreateOn DESC']
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
                BrowseLev: nPXStaPXBrowseType,
                // DebugSQL: true
            };
            return oOptionReturn;
        }

    // ========================================== Brows Event Conditon ===========================================
        // Event Browse Merchant
        $('#obtPXBrowseMerchant').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oPXBrowseMerchantOption  = undefined;
                oPXBrowseMerchantOption         = oMerchantOption({
                    'tPXBchCode'        : $('#oetPXFrmBchCode').val(),
                    'tReturnInputCode'  : 'oetPXFrmMerCode',
                    'tReturnInputName'  : 'oetPXFrmMerName',
                    'tNextFuncName'     : 'JSxPXSetConditionMerchant',
                    'aArgReturn'        : ['FTMerCode','FTMerName'],
                });
                JCNxBrowseData('oPXBrowseMerchantOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse Shop
        $('#obtPXBrowseShop').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oPXBrowseShopOption  = undefined;
                oPXBrowseShopOption         = oShopOption({
                    'tPXBchCode'        : $('#oetPXFrmBchCode').val(),
                    'tPXMerCode'        : $('#oetPXFrmMerCode').val(),
                    'tReturnInputCode'  : 'oetPXFrmShpCode',
                    'tReturnInputName'  : 'oetPXFrmShpName',
                    'tNextFuncName'     : 'JSxPXSetConditionShop',
                    'aArgReturn'        : ['FTBchCode','FTShpType','FTShpCode','FTShpName','FTWahCode','FTWahName']
                });
                JCNxBrowseData('oPXBrowseShopOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse Pos
        $('#obtPXBrowsePos').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oPXBrowsePosOption   = undefined;
                oPXBrowsePosOption          = oPosOption({
                    'tPXBchCode'        : $('#oetPXFrmBchCode').val(),
                    'tPXShpCode'        : $('#oetPXFrmShpCode').val(),
                    'tReturnInputCode'  : 'oetPXFrmPosCode',
                    'tReturnInputName'  : 'oetPXFrmPosName',
                    'tNextFuncName'     : 'JSxPXSetConditionPos',
                    'aArgReturn'        : ['FTBchCode','FTShpCode','FTPosCode','FTWahCode','FTWahName']
                });
                JCNxBrowseData('oPXBrowsePosOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse Warehouse
        $('#obtPXBrowseWahouse').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oPXBrowseWahOption   = undefined;
                if($('#oetPXFrmBchCode').val()!='' && $('#oetPXFrmShpCode').val()!=''){
                    oPXBrowseWahOption          = oShopWahOption({
                        'tPXShpCode'        : $('#oetPXFrmShpCode').val(),
                        'tPXPosCode'        : $('#oetPXFrmWahCode').val(),
                        'tPXBchCode'        : $('#oetPXFrmBchCode').val(),
                        'tReturnInputCode'  : 'oetPXFrmWahCode',
                        'tReturnInputName'  : 'oetPXFrmWahName',
                        'tNextFuncName'     : 'JSxPXSetConditionWahouse',
                        'aArgReturn'        : []
                    });
                }else if($('#oetPXFrmBchCode').val()!=''){
                    oPXBrowseWahOption          = oWahOption({
                        'tPXShpCode'        : $('#oetPXFrmShpCode').val(),
                        'tPXPosCode'        : $('#oetPXFrmWahCode').val(),
                        'tPXBchCode'        : $('#oetPXFrmBchCode').val(),
                        'tReturnInputCode'  : 'oetPXFrmWahCode',
                        'tReturnInputName'  : 'oetPXFrmWahName',
                        'tNextFuncName'     : 'JSxPXSetConditionWahouse',
                        'aArgReturn'        : []
                    });
                }

                JCNxBrowseData('oPXBrowseWahOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Event Browse Supplier
        $('#obtPXBrowseSupplier').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oPXBrowseSplOption   = undefined;
                oPXBrowseSplOption          = oSplOption({
                    'tParamsAgnCode'    : $('#oetPXAgnCode').val()/*'<?=$this->session->userdata("tSesUsrAgnCode")?>'*/,
                    'tReturnInputCode'  : 'oetPXFrmSplCode',
                    'tReturnInputName'  : 'oetPXFrmSplName',
                    'tNextFuncName'     : 'JSxPXSetConditionAfterSelectSpl',
                    'aArgReturn'        : ['FNSplCrTerm', 'FCSplCrLimit', 'FTSplStaVATInOrEx', 'FTSplTspPaid', 'FTSplCode', 'FTSplName', 'FTVatCode', 'FCVatRate']
                });
                JCNxBrowseData('oPXBrowseSplOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });


    // ====================================== Function NextFunc Browse Modal =====================================
        // Functionality : Function Behind NextFunc กลุ่มธุรกิจ
        // Parameter : Event Next Func Modal
        // Create : 26/06/2019 Wasin(Yoshi)
        // Return : Set value And Control Input
        // Return Type : -
        function JSxPXSetConditionMerchant(poDataNextFunc){
            var aDataNextFunc,tPXMerCode,tPXMerName;
            if(typeof(poDataNextFunc) != undefined && poDataNextFunc != "NULL"){
                aDataNextFunc   = JSON.parse(poDataNextFunc);
                tPXMerCode      = aDataNextFunc[0];
                tPXMerName      = aDataNextFunc[1];
            }

            let tPXBchCode  = $('#oetPXFrmBchCode').val();
            let tPXMchCode  = $('#oetPXFrmMerCode').val();
            let tPXMchName  = $('#oetPXFrmMerName').val();
            let tPXShopCode = $('#oetPXFrmShpCode').val();
            let tPXShopName = $('#oetPXFrmShpName').val();
            let tPXPosCode  = $('#oetPXFrmPosCode').val();
            let tPXPosName  = $('#oetPXFrmPosName').val();
            let tPXWahCode  = $('#oetPXFrmWahCode').val();
            let tPXWahName  = $('#oetPXFrmWahName').val();

            let nCountDataInTable = $('#otbPXDocPdtAdvTableList tbody .xWPdtItem').length;

            if(nCountDataInTable > 0 && tPXMchCode != "" && tPXShopCode != "" && tPXWahCode != ""){
                // รายการสินค้าที่ท่านเพิ่มไปแล้วจะถูกล้างค่าทิ้ง เมื่อท่านเปลี่ยนกลุ่มธุรกิจ
                var tTextMssage    = '<?php echo language('document/expenserecord/expenserecord','tPXMsgNotiChangeMerchantClearDocTemp');?>';
                FSvCMNSetMsgWarningDialog("<p>"+tTextMssage+"</p>");

                // Event CLick Close Massage And Delete Temp
                $('#odvModalWanning .xWBtnOK').click(function(evn){
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docPXClearDataDocTemp",
                        data: {
                            'ptPXDocNo' : $("#oetPXDocNo").val()
                        },
                        cache: false,
                        success: function (oResult){
                            var aDataReturn     = JSON.parse(oResult);
                            var tMessageError   = aDataReturn['tStaMessg'];
                            switch(aDataReturn['nStaReturn']){
                                case 1:
                                    JSvPXLoadPdtDataTableHtml();
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

            $('#obtPXBrowseShop').attr('disabled', true);
            $('#obtPXBrowsePos').attr('disabled', true);
            // $('#obtPXBrowseWahouse').attr('disabled', true);

            if(tSesUsrLevel == 'HQ' || tSesUsrLevel == 'BCH'){
                if((tPXMchCode == "" && tPXMchName == "") && (tPXShopCode == "" && tPXShopName == "") && (tPXPosCode == "" && tPXPosName == "" )) {
                    $('#obtPXBrowseWahouse').attr('disabled', false).removeClass('disabled');

                }else{
                    $('#obtPXBrowseShop').attr('disabled',false).removeClass('disabled');
                    // $('#obtPXBrowseWahouse').attr('disabled', true).addClass('disabled');
                }

                $('#oetPXFrmShpCode,#oetPXFrmShpName').val('');
                $('#oetPXFrmPosCode,#oetPXFrmPosName').val('');
                $('#oetPXFrmWahCode,#oetPXFrmWahName').val('');
            }
        }

        // Functionality : Function Behind NextFunc ร้านค้า
        // Parameter : Event Next Func Modal
        // Create : 26/06/2019 Wasin(Yoshi)
        // Return : Set value And Control Input
        // Return Type : -
        function JSxPXSetConditionShop(poDataNextFunc){
            var aDataNextFunc,tPXBchCode,tPXShpType,tPXShpCode,tPXShpName,tPXWahCode,tPXWahName;
            if(typeof(poDataNextFunc) != undefined && poDataNextFunc != "NULL"){
                aDataNextFunc   = JSON.parse(poDataNextFunc);
                tPXBchCode      = aDataNextFunc[0];
                tPXShpType      = aDataNextFunc[1];
                tPXShpCode      = aDataNextFunc[2];
                tPXShpName      = aDataNextFunc[3];
                tPXWahCode      = aDataNextFunc[4];
                tPXWahName      = aDataNextFunc[5];
            }else{
                $('#oetPXFrmWahCode,#oetPXFrmWahName').val('');
            }

            let tPXDataBchCode  = $('#oetPXFrmBchCode').val();
            let tPXDataMchCode  = $('#oetPXFrmMerCode').val();
            let tPXDataMchName  = $('#oetPXFrmMerName').val();
            let tPXDataShopCode = $('#oetPXFrmShpCode').val();
            let tPXDataShopName = $('#oetPXFrmShpName').val();
            let tPXDataPosCode  = $('#oetPXFrmPosCode').val();
            let tPXDataPosName  = $('#oetPXFrmPosName').val();
            let tPXDataWahCode  = $('#oetPXFrmWahCode').val();
            let tPXDataWahName  = $('#oetPXFrmWahName').val();

            let nCountDataInTable = $('#otbPXDocPdtAdvTableList tbody .xWPdtItem').length;
            if(nCountDataInTable > 0 && tPXDataMchCode != "" && tPXDataShopCode != "" && tPXDataWahCode != ""){
                // Show Modal Notification Found Data In Table Doctemp Behide Change Shop
                FSvCMNSetMsgWarningDialog("<p>รายการสินค้าที่ท่านเพิ่มไปแล้วจะถูกล้างค่าทิ้ง เมื่อท่านเปลี่ยนร้านค้าใหม่</p>");

                // Event CLick Close Massage And Delete Temp
                $('#odvModalWanning .xWBtnOK').click(function(evn){
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docPXClearDataDocTemp",
                        data: {
                            'ptPXDocNo' : $("#oetPXDocNo").val()
                        },
                        cache: false,
                        success: function (oResult){
                            var aDataReturn     = JSON.parse(oResult);
                            var tMessageError   = aDataReturn['tStaMessg'];
                            switch(aDataReturn['nStaReturn']){
                                case 1:
                                    JSvPXLoadPdtDataTableHtml();
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
                if(typeof(tPXShpName) != undefined && tPXShpName != ''){
                    if(tPXShpType == 4){
                        $('#obtPXBrowsePos').attr('disabled',false).removeClass('disabled');
                        // $('#obtPXBrowseWahouse').attr('disabled',true).addClass('disabled');
                        // $('#oetPXFrmWahCode').val(tPXWahCode);
                        // $('#oetPXFrmWahName').val(tPXWahName);
                    }else{
                        // $('#oetPXFrmWahCode').val(tPXWahCode);
                        // $('#oetPXFrmWahName').val(tPXWahName);
                        $('#obtPXBrowsePos').attr('disabled',true).addClass('disabled');
                        // $('#obtPXBrowseWahouse').attr('disabled',true).addClass('disabled');
                    }
                }else{
                    $('#oetPXFrmWahCode,#oetPXFrmWahName').val('');
                }
                $('#oetPXFrmPosCode,#oetPXFrmPosName').val('');
            }

            $('#oetPXFrmWahName,#oetPXFrmWahCode').val('');
        }

        // Functionality : Function Behind NextFunc เครื่องจุดขาย
        // Parameter : Event Next Func Modal
        // Create : 26/06/2019 Wasin(Yoshi)
        // Return : Set value And Control Input
        // Return Type : -
        function JSxPXSetConditionPos(poDataNextFunc){
            var aDataNextFunc,tPXBchCode,tPXShpCode,tPXPosCode,tPXWahCode,tPXWahName;
            if(typeof(poDataNextFunc) != undefined && poDataNextFunc != "NULL"){
                aDataNextFunc   = JSON.parse(poDataNextFunc);
                tPXBchCode      = aDataNextFunc[0];
                tPXShpCode      = aDataNextFunc[1];
                tPXPosCode      = aDataNextFunc[2];
                tPXWahCode      = aDataNextFunc[3];
                tPXWahName      = aDataNextFunc[4];
                $('#oetPXFrmWahCode').val(tPXWahCode);
                $('#oetPXFrmWahName').val(tPXWahName);
                $('#obtPXBrowsePos').attr('disabled',false).removeClass('disabled');
            }else{
                $('#oetPXFrmPosCode,#oetPXFrmPosCode').val('');
                $('#oetPXFrmWahCode').val('');
                $('#oetPXFrmWahName').val('');
                return;
            }

        }

        // Functionality : Function Behind NextFunc Supllier
        // Parameter : Event Next Func Modal
        // Create : 01/07/2019 Wasin(Yoshi)
        // Return : -
        // Return Type : -
        function JSxPXSetConditionAfterSelectSpl(poDataNextFunc){
            var aData;
            if (poDataNextFunc  != "NULL") {
                aData = JSON.parse(poDataNextFunc);
                var poParams = {
                    FNSplCrTerm         : aData[0],
                    FCSplCrLimit        : aData[1],
                    FTSplStaVATInOrEx   : aData[2],
                    FTSplTspPaid        : aData[3],
                    FTSplCode           : aData[4],
                    FTSplName           : aData[5],
                    FTVatCode           : aData[6],
                    FCVatRate           : aData[7]
                };
                JSxPXSetPanelSupplierData(poParams);
            }
        }

        // Functionality : ฟังก์ชั่นเซทข้อมูล ผู้จำหน่าย
        // Parameter : Event Next Func Modal
        // Create : 01/07/2019 Wasin(Yoshi)
        // Return : -
        // Return Type : -
        function JSxPXSetPanelSupplierData(poParams){
            // Reset Panel เป็นค่าเริ่มต้น
            $("#ocmPXFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
            $("#ocmPXFrmSplInfoPaymentType.selectpicker").val("2").selectpicker("refresh");
            $("#ocmPXFrmSplInfoDstPaid.selectpicker").val("1").selectpicker("refresh");
            $("#oetPXFrmSplInfoCrTerm").val("");

            // ประเภทภาษี
            if(poParams.FTSplStaVATInOrEx === "1"){
                // รวมใน
                $("#ocmPXFrmSplInfoVatInOrEx.selectpicker").val("1").selectpicker("refresh");
            }else{
                // แยกนอก
                $("#ocmPXFrmSplInfoVatInOrEx.selectpicker").val("2").selectpicker("refresh");
            }

            // ประเภทชำระเงิน
            if(poParams.FCSplCrLimit > 0){
                // เงินเชื่อ
                $("#ocmPXFrmSplInfoPaymentType.selectpicker").val("2").selectpicker("refresh");
            }else{
                // เงินสด
                $("#ocmPXFrmSplInfoPaymentType.selectpicker").val("1").selectpicker("refresh");
            }

            // การชำระเงิน
            if(poParams.FTSplTspPaid === "1"){ // ต้นทาง
                $("#ocmPXFrmSplInfoDstPaid.selectpicker").val("1").selectpicker("refresh");
            }else{ // ปลายทาง
                $("#ocmPXFrmSplInfoDstPaid.selectpicker").val("2").selectpicker("refresh");
            }

            // ระยะเครดิต
            $("#oetPXFrmSplInfoCrTerm").val(poParams.FNSplCrTerm);

            // Vat จาก SPL
            $('#ohdPXFrmSplVatCode').val(poParams.FTVatCode);
            $('#ohdPXFrmSplVatRate').val(poParams.FCVatRate);

            //เปลี่ยน VAT
            var tVatCode = poParams.FTVatCode;
            var tVatRate = poParams.FCVatRate;
            JSxChangeVatBySPL(tVatCode,tVatRate);
        }

        //ทุกครั้งที่เปลี่ยน SPL ต้องเกิดการคำนวณ VAT ใหม่ที่อยู่ในสินค้า
        function JSxChangeVatBySPL(tVatCode,tVatRate){
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "docPXChangeSPLAffectNewVAT",
                data: {
                    'tBCHCode'      : $('#oetPXFrmBchCode').val(),
                    'tPXDocNo'      : $("#oetPXDocNo").val(),
                    'tVatCode'      : tVatCode,
                    'tVatRate'      : tVatRate
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    JSvPXLoadPdtDataTableHtml(1)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }


    /** ================================== Manage Product Advance Table Colums  ================================== */
        // Event Call Modal Show Option Advance Product Doc DT Tabel
        $('#obtPXAdvTablePdtDTTemp').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxPXOpenColumnFormSet();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });


        $('#odvPXOrderAdvTblColumns #obtPXSaveAdvTableColums').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxPXSaveColumnShow();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Functionality : Call Advnced Table
        // Parameters : Event Next Func Modal
        // Creator : 01/07/2019 Wasin(Yoshi)
        // Return : Open Modal Manage Colums Show
        // Return Type : -
        function JSxPXOpenColumnFormSet(){
            $.ajax({
                type: "POST",
                url: "docPXAdvanceTableShowColList",
                cache: false,
                Timeout: 0,
                success: function (oResult) {
                    var aDataReturn = JSON.parse(oResult);
                    if(aDataReturn['nStaEvent'] == '1'){
                        var tViewTableShowCollist   = aDataReturn['tViewTableShowCollist'];
                        $('#odvPXOrderAdvTblColumns .modal-body').html(tViewTableShowCollist);
                        $('#odvPXOrderAdvTblColumns').modal({backdrop: 'static', keyboard: false})
                        $("#odvPXOrderAdvTblColumns").modal({ show: true });
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
        //Parameters : Event Next Func Modal
        //Creator : 02/07/2019 Wasin(Yoshi)
        //Return : Open Modal Manage Colums Show
        //Return Type : -
        function JSxPXSaveColumnShow(){
            // คอลัมน์ที่เลือกให้แสดง
            var aPXColShowSet = [];
            $("#odvPXOrderAdvTblColumns .xWPXInputColStaShow:checked").each(function(){
                aPXColShowSet.push($(this).data("id"));
            });

            // คอลัมน์ทั้งหมด
            var aPXColShowAllList = [];
            $("#odvPXOrderAdvTblColumns .xWPXInputColStaShow").each(function () {
                aPXColShowAllList.push($(this).data("id"));
            });

            // ชื่อคอลัมน์ทั้งหมดในกรณีมีการแก้ไขชื่อคอลัมน์ที่แสดง
            var aPXColumnLabelName = [];
            $("#odvPXOrderAdvTblColumns .xWPXLabelColumnName").each(function () {
                aPXColumnLabelName.push($(this).text());
            });

            // สถานะย้อนกลับค่าเริ่มต้น
            var nPXStaSetDef;
            if($("#odvPXOrderAdvTblColumns #ocbPXSetDefAdvTable").is(":checked")) {
                nPXStaSetDef   = 1;
            } else {
                nPXStaSetDef   = 0;
            }

            $.ajax({
                type: "POST",
                url: "docPXAdvanceTableShowColSave",
                data: {
                    'pnPXStaSetDef'         : nPXStaSetDef,
                    'paPXColShowSet'        : aPXColShowSet,
                    'paPXColShowAllList'    : aPXColShowAllList,
                    'paPXColumnLabelName'   : aPXColumnLabelName
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    $("#odvPXOrderAdvTblColumns").modal("hide");
                    $(".modal-backdrop").remove();
                    JSvPXLoadPdtDataTableHtml();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    // ===========================================================================================================

    /** ========================================= Set Shipping Address =========================================== */
        $('#obtPXFrmBrowseShipAdd').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                $('#odvPXBrowseShipAdd').modal({backdrop: 'static', keyboard: false})
                $('#odvPXBrowseShipAdd').modal('show');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Option Browse Shipping Address
        var oPXBrowseShipAddress    = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tPXWhereCons        = poDataFnc.tPXWhereCons;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var oOptionReturn       = {
                Title : ['document/expenserecord/expenserecord','tPXShipAddress'],
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
                    Condition : [tPXWhereCons]
                },
                GrideView:{
                    ColumnPathLang	: 'document/expenserecord/expenserecord',
                    ColumnKeyLang	: [
                        'tPXShipADDBch',
                        'tPXShipADDSeq',
                        'tPXShipADDV1No',
                        'tPXShipADDV1Soi',
                        'tPXShipADDV1Village',
                        'tPXShipADDV1Road',
                        'tPXShipADDV1SubDist',
                        'tPXShipADDV1DstCode',
                        'tPXShipADDV1PvnCode',
                        'tPXShipADDV1PostCode'
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
                        'TCNMAddress_L.FTAddV2Desc2',
                        'TCNMAddress_L.FTAddVersion'
                    ],
                    DataColumnsFormat : ['','','','','','','','','','','','','','',''],
                    ColumnsSize     : [''],
                    DisabledColumns	:[10,11,12,13,14,15],
                    Perpage			: 10,
                    WidthModal      : 50,
                    OrderBy			: ['TCNMAddress_L.FDCreateOn DESC'],
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
        $('#odvPXBrowseShipAdd #oliPXEditShipAddress').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                var tPXWhereCons    = "";
                console.log( $("#oetPXFrmBchCode").val() );
                if( $("#oetPXFrmBchCode").val() != "" ){
                    // if($("#oetPXFrmMerCode").val() != ""){
                    //     if($("#oetPXFrmShpCode").val() != ""){
                    //         if($("#oetPXFrmPosCode").val() != ""){
                    //             // Address Ref POS
                    //             tPXWhereCons    +=  "AND FTAddGrpType = 6 AND FTAddRefCode = '"+$("#oetPXFrmPosCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits;
                    //         }else{
                    //             // Address Ref SHOP
                    //             tPXWhereCons    +=  "AND FTAddGrpType = 4 AND FTAddRefCode = '"+$("#oetPXFrmShpCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits;
                    //         }
                    //     }else{
                    //         // Address Ref BCH
                    //         tPXWhereCons        +=  "AND FTAddGrpType = 1 AND FTAddRefCode = '"+$("#oetPXFrmBchCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits;
                    //     }
                    // }else{
                        // Address Ref BCH
                        tPXWhereCons            +=  "AND FTAddGrpType = 1 AND FTAddRefCode = '"+$("#oetPXFrmBchCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits;
                    // }
                }
                // Call Option Modal
                window.oPXBrowseShipAddressOption   = undefined;
                oPXBrowseShipAddressOption          = oPXBrowseShipAddress({
                    'tReturnInputCode'  : 'ohdPXShipAddSeqNo',
                    'tReturnInputName'  : 'ohdPXShipAddSeqNo',
                    'tPXWhereCons'     : tPXWhereCons,
                    'tNextFuncName'     : 'JSvPXGetShipAddrData',
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
                        'FTAddV2Desc2',
                        'FTAddVersion'
                    ]
                });
                $("#odvPXBrowseShipAdd").modal("hide");
                $('.modal-backdrop').remove();
                JCNxBrowseData('oPXBrowseShipAddressOption');
            }else{
                $("#odvPXBrowseShipAdd").modal("hide");
                $('.modal-backdrop').remove();
                JCNxShowMsgSessionExpired();
            }
        });

        //Functionality : Behind NextFunc Browse Shippinh Address
        //Parameters : Event Next Func Modal
        //Creator : 04/07/2019 Wasin(Yoshi)
        //Return : Set Value And Controll Input
        //Return Type : -
        function JSvPXGetShipAddrData(paInForCon){
            // console.log(paInForCon);
            if(paInForCon !== "NULL") {
                var aDataReturn = JSON.parse(paInForCon);
                // console.log(aDataReturn);
                var tAddVersion = aDataReturn[11];
                $('.xWPXShipContentAddVersion'+tAddVersion).show();
                $("#ospPXShipAddAddV1No").text((aDataReturn[1] != "")      ? aDataReturn[1]    : '-');
                $("#ospPXShipAddV1Soi").text((aDataReturn[2] != "")        ? aDataReturn[2]    : '-');
                $("#ospPXShipAddV1Village").text((aDataReturn[3] != "")    ? aDataReturn[3]    : '-');
                $("#ospPXShipAddV1Road").text((aDataReturn[4] != "")       ? aDataReturn[4]    : '-');
                $("#ospPXShipAddV1SubDist").text((aDataReturn[5] != "")    ? aDataReturn[5]    : '-');
                $("#ospPXShipAddV1DstCode").text((aDataReturn[6] != "")    ? aDataReturn[6]    : '-');
                $("#ospPXShipAddV1PvnCode").text((aDataReturn[7] != "")    ? aDataReturn[7]    : '-');
                $("#ospPXShipAddV1PostCode").text((aDataReturn[8] != "")   ? aDataReturn[8]    : '-');
                $("#ospPXShipAddV2Desc1").text((aDataReturn[9] != "")      ? aDataReturn[9]    : '-');
                $("#ospPXShipAddV2Desc2").text((aDataReturn[10] != "")     ? aDataReturn[10]   : '-');
            }else{
                $("#ospPXShipAddAddV1No").text("-");
                $("#ospPXShipAddV1Soi").text("-");
                $("#ospPXShipAddV1Village").text("-");
                $("#ospPXShipAddV1Road").text("-");
                $("#ospPXShipAddV1SubDist").text("-");
                $("#ospPXShipAddV1DstCode").text("-");
                $("#ospPXShipAddV1PvnCode").text("-");
                $("#ospPXShipAddV1PostCode").text("-");
                $("#ospPXShipAddV2Desc1").text("-");
                $("#ospPXShipAddV2Desc2").text("-");
            }
            $("#odvPXBrowseShipAdd").modal("show");
        }

        //Functionality : Add Shiping Add To Input
        //Parameters : Event Next Func Modal
        //Creator : 04/07/2019 Wasin(Yoshi)
        //Return : Set Value And Controll Input
        //Return Type : -
        function JSnPXShipAddData(){
            var tPXShipAddSeqNoSelect   = $('#ohdPXShipAddSeqNo').val();
            $('#ohdPXFrmShipAdd').val(tPXShipAddSeqNoSelect);
            $("#odvPXBrowseShipAdd").modal("hide");
            $('.modal-backdrop').remove();
        }

    // ===========================================================================================================

    /** ============================================ Set Tex Address ============================================= */
        $('#obtPXFrmBrowseTaxAdd').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                $('#odvPXBrowseTexAdd').modal({backdrop: 'static', keyboard: false})
                $('#odvPXBrowseTexAdd').modal('show');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Option Browse Shipping Address
        var oPXBrowseTexAddress     = function(poDataFnc){
            var tInputReturnCode    = poDataFnc.tReturnInputCode;
            var tInputReturnName    = poDataFnc.tReturnInputName;
            var tPXWhereCons        = poDataFnc.tPXWhereCons;
            var tNextFuncName       = poDataFnc.tNextFuncName;
            var aArgReturn          = poDataFnc.aArgReturn;
            var oOptionReturn       = {
                Title   : ['document/expenserecord/expenserecord','tPXTexAddress'],
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
                    Condition : [tPXWhereCons]
                },
                GrideView:{
                    ColumnPathLang	: 'document/expenserecord/expenserecord',
                    ColumnKeyLang	: [
                        'tPXTexADDBch',
                        'tPXTexADDSeq',
                        'tPXTexADDV1No',
                        'tPXTexADDV1Soi',
                        'tPXTexADDV1Village',
                        'tPXTexADDV1Road',
                        'tPXTexADDV1SubDist',
                        'tPXTexADDV1DstCode',
                        'tPXTexADDV1PvnCode',
                        'tPXTexADDV1PostCode'
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
                        'TCNMAddress_L.FTAddV2Desc2',
                        'TCNMAddress_L.FTAddVersion'
                    ],
                    DataColumnsFormat : ['','','','','','','','','','','','','','',''],
                    ColumnsSize     : [''],
                    DisabledColumns	:[10,11,12,13,14],
                    Perpage			: 10,
                    WidthModal      : 50,
                    OrderBy			: ['TCNMAddress_L.FDCreateOn DESC'],
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
        $('#odvPXBrowseTexAdd #oliPXEditTexAddress').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                var tPXWhereCons    = "AND FTAddGrpType = 1 AND FTAddRefCode = '"+$("#oetPXFrmBchCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits;
                // Call Option Modal
                window.oPXBrowseTexAddressOption    = undefined;
                oPXBrowseTexAddressOption           = oPXBrowseTexAddress({
                    'tReturnInputCode'  : 'ohdPXTexAddSeqNo',
                    'tReturnInputName'  : 'ohdPXTexAddSeqNo',
                    'tPXWhereCons'     : tPXWhereCons,
                    'tNextFuncName'     : 'JSvPXGetTexAddrData',
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
                        'FTAddV2Desc2',
                        'FTAddVersion'
                    ]
                });
                $("#odvPXBrowseTexAdd").modal("hide");
                $('.modal-backdrop').remove();
                JCNxBrowseData('oPXBrowseTexAddressOption');
            }else{
                $("#odvPXBrowseTexAdd").modal("hide");
                $('.modal-backdrop').remove();
                JCNxShowMsgSessionExpired();
            }
        });

        //Functionality : Behind NextFunc Browse Shippinh Address
        //Parameters : Event Next Func Modal
        //Creator : 04/07/2019 Wasin(Yoshi)
        //Return : Set Value And Controll Input
        //Return Type : -
        function JSvPXGetTexAddrData(paInForCon){
            if(paInForCon !== "NULL") {
                var aDataReturn = JSON.parse(paInForCon);
                var tAddVersion = aDataReturn[11];
                $('.xWPXTaxContentAddVersion'+tAddVersion).show();
                $("#ospPXTexAddAddV1No").text((aDataReturn[1] != "")      ? aDataReturn[1]    : '-');
                $("#ospPXTexAddV1Soi").text((aDataReturn[2] != "")        ? aDataReturn[2]    : '-');
                $("#ospPXTexAddV1Village").text((aDataReturn[3] != "")    ? aDataReturn[3]    : '-');
                $("#ospPXTexAddV1Road").text((aDataReturn[4] != "")       ? aDataReturn[4]    : '-');
                $("#ospPXTexAddV1SubDist").text((aDataReturn[5] != "")    ? aDataReturn[5]    : '-');
                $("#ospPXTexAddV1DstCode").text((aDataReturn[6] != "")    ? aDataReturn[6]    : '-');
                $("#ospPXTexAddV1PvnCode").text((aDataReturn[7] != "")    ? aDataReturn[7]    : '-');
                $("#ospPXTexAddV1PostCode").text((aDataReturn[8] != "")   ? aDataReturn[8]    : '-');
                $("#ospPXTexAddV2Desc1").text((aDataReturn[9] != "")      ? aDataReturn[9]    : '-');
                $("#ospPXTexAddV2Desc2").text((aDataReturn[10] != "")     ? aDataReturn[10]   : '-');
            }else{
                $("#ospPXTexAddAddV1No").text("-");
                $("#ospPXTexAddV1Soi").text("-");
                $("#ospPXTexAddV1Village").text("-");
                $("#ospPXTexAddV1Road").text("-");
                $("#ospPXTexAddV1SubDist").text("-");
                $("#ospPXTexAddV1DstCode").text("-");
                $("#ospPXTexAddV1PvnCode").text("-");
                $("#ospPXTexAddV1PostCode").text("-");
                $("#ospPXTexAddV2Desc1").text("-");
                $("#ospPXTexAddV2Desc2").text("-");
            }
            $("#odvPXBrowseTexAdd").modal("show");
        }

        //Functionality : Add Shiping Add To Input
        //Parameters : Event Next Func Modal
        //Creator : 04/07/2019 Wasin(Yoshi)
        //Return : Set Value And Controll Input
        //Return Type : -
        function JSnPXTexAddData(){
            var tPXTexAddSeqNoSelect    = $('#ohdPXTexAddSeqNo').val();
            $('#ohdPXFrmTaxAdd').val(tPXTexAddSeqNoSelect);
            $("#odvPXBrowseTexAdd").modal("hide");
            $('.modal-backdrop').remove();
        }
    // ===========================================================================================================
    // Functionality: Check Status Document Process EQ And Call Back MQ
    // Parameters: Event Document Ready Load Page
    // Creator: 11/07/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: -
    // ReturnType: -
    function JSxPXChkStaDocCallModalMQ(){
        var nPXLangEdits        = nLangEdits;
        var tPXFrmBchCode       = $("#oetPXFrmBchCode").val();
        var tPXUsrApv           = $("#ohdPXApvCodeUsrLogin").val();
        var tPXDocNo            = $("#oetPXDocNo").val();
        var tPXPrefix           = "RESPPX";
        var tPXStaApv           = $("#ohdPXStaApv").val();
        // var tPXStaPrcStk        = $("#ohdPXStaPrcStk").val();
        var tPXStaDelMQ         = $("#ohdPXStaDelMQ").val();
        var tPXQName            = tPXPrefix + "_" + tPXDocNo + "_" + tPXUsrApv;
        var tPXTableName        = "TAPTPXHD";
        var tPXFieldDocNo       = "FTXphDocNo";
        var tPXFieldStaApv      = "FTXphStaPrcStk";
        var tPXFieldStaDelMQ    = "FTXphStaDelMQ";

        // MQ Message Config
        var poDocConfig = {
            tLangCode     : nPXLangEdits,
            tUsrBchCode   : tPXFrmBchCode,
            tUsrApv       : tPXUsrApv,
            tDocNo        : tPXDocNo,
            tPrefix       : tPXPrefix,
            tStaDelMQ     : tPXStaDelMQ,
            tStaApv       : tPXStaApv,
            tQName        : tPXQName
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
            ptDocTableName      : tPXTableName,
            ptDocFieldDocNo     : tPXFieldDocNo,
            ptDocFieldStaApv    : tPXFieldStaApv,
            ptDocFieldStaDelMQ  : tPXFieldStaDelMQ,
            ptDocStaDelMQ       : tPXStaDelMQ,
            ptDocNo             : tPXDocNo
        };

        // Callback Page Control(function)
        var poCallback = {
            tCallPageEdit: "JSvPXCallPageEditDoc",
            tCallPageList: "JSvPXCallPageList"
        };

        // Check Show Progress %
        if(tPXDocNo != '' && (tPXStaApv == 2)){
            FSxCMNRabbitMQMessage(poDocConfig,poMqConfig,poUpdateStaDelQnameParams,poCallback);
        }

        // Check Delete MQ SubScrib
        if(tPXStaApv == 1 && tPXStaDelMQ == ""){
            var poDelQnameParams    = {
                ptPrefixQueueName   : tPXPrefix,
                ptBchCode           : tPXFrmBchCode,
                ptDocNo             : tPXDocNo,
                ptUsrCode           : tPXUsrApv
            };
            FSxCMNRabbitMQDeleteQname(poDelQnameParams);
            FSxCMNRabbitMQUpdateStaDeleteQname(poUpdateStaDelQnameParams);
        }
    }

    //Call Report
    function JSxPXPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch($tPXBchCode); ?>'},
            {"DocCode"      : '<?=$tPXDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=$tPXBchCode;?>'}
        ];
        var tGrandText = $('#odvPXDataTextBath').text();
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillPx?infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand="+tGrandText, '_blank');
    }

    

    // $('#obtBrowseTWOBCH').click(function(){ JCNxBrowseData('oBrowse_BCH'); });

    $('#obtBrowseTWOBCH').click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) != 'undefined' && nStaSession == 1) {
            window.oPXBrowseBranch = oPXBrowseBranchOption({
                'tReturnInputCode': 'oetPXFrmBchCode',
                'tReturnInputName': 'oetPXFrmBchName',
                'tConditionAgnCode' : $('#oetPXAgnCode').val()
            });
            JCNxBrowseData('oPXBrowseBranch');

        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var oPXBrowseBranchOption = function(poReturnInput) {
        var tInputReturnCode  = poReturnInput.tReturnInputCode;
        var tInputReturnName  = poReturnInput.tReturnInputName;
        var tConditionAgnCode = poReturnInput.tConditionAgnCode;
        var tSQLWhere         = "";

        if( tConditionAgnCode == "" ){
            var tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            var tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
            if(tUsrLevel != "HQ"){
                tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
            }
        }else{
            tSQLWhere = " AND TCNMBranch.FTAgnCode = '"+tConditionAgnCode+"' ";
        }

        var oBrowseOptionReturn = {
            Title   : ['company/branch/branch','tBCHTitle'],
            Table   : {Master:'TCNMBranch',PK:'FTBchCode',PKName:'FTBchName'},
            Join    : {
                Table   : ['TCNMBranch_L'],
                On      : [
                    'TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [ tSQLWhere ]
            },
            GrideView:{
                ColumnPathLang : 'company/branch/branch',
                ColumnKeyLang : ['tBCHCode','tBCHName',''],
                ColumnsSize     : ['15%','75%',''],
                WidthModal      : 50,
                DataColumns  : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                DataColumnsFormat : ['',''],
                // DisabledColumns   : [2,3],
                Perpage   : 10,
                OrderBy   : ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack:{
                ReturnType : 'S',
                Value  : [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text   : [tInputReturnName,"TCNMBranch_L.FTBchName"],
            },
            // NextFunc    :   {
            //     FuncName    :   'JSxSetDefauleWahouse',
            //     ArgReturn   :   ['FTWahCode','FTWahName']
            // }
        }
        return oBrowseOptionReturn;
    }

    $('#obtPXBrowseAgn').click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) != 'undefined' && nStaSession == 1) {
            window.oPXBrowseAgency = oPXBrowseAgencyOption({
                'tReturnInputCode': 'oetPXAgnCode',
                'tReturnInputName': 'oetPXAgnName'
            });
            JCNxBrowseData('oPXBrowseAgency');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var oPXBrowseAgencyOption = function(poReturnInput) {
        var tInputReturnCode  = poReturnInput.tReturnInputCode;
        var tInputReturnName  = poReturnInput.tReturnInputName;
        // var tConditionAgnCode = poReturnInput.tConditionAgnCode;
        var tSQLWhere         = "";

        // if( tConditionAgnCode == "" ){
            // var tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
            // var tAgncCode = "<?=$this->session->userdata("tSesUsrAgnCode"); ?>";
            // var tAgnName  = "<?=$this->session->userdata("tSesUsrAgnName"); ?>";

            // if( tUsrLevel != "HQ" ){
            //     tSQLWhere = " AND TCNMAgency.FTAgnCode = "+tAgncCode+" ";
            // }
        // }else{
        //     tSQLWhere = " AND TCNMBranch.FTAgnCode = '"+tConditionAgnCode+"' ";
        // }

        var oBrowseOptionReturn = {
            Title   : ['ticket/agency/agency','tAggTitle'],
            Table   : { Master:'TCNMAgency',PK:'FTAgnCode',PKName:'FTAgnName' },
            Join    : {
                Table   : ['TCNMAgency_L'],
                On      : [
                    'TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [ tSQLWhere ]
            },
            GrideView:{
                ColumnPathLang : 'ticket/agency/agency',
                ColumnKeyLang : ['tAggCode','tAggName'],
                ColumnsSize     : ['15%','75%'],
                WidthModal      : 50,
                DataColumns  : ['TCNMAgency.FTAgnCode','TCNMAgency_L.FTAgnName'],
                DataColumnsFormat : ['',''],
                // DisabledColumns   : [2,3],
                Perpage   : 10,
                OrderBy   : ['TCNMAgency.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType : 'S',
                Value  : [tInputReturnCode,"TCNMAgency.FTAgnCode"],
                Text   : [tInputReturnName,"TCNMAgency_L.FTAgnName"],
            },
            NextFunc    :   {
                FuncName    :   'JSxPXNextFuncAgency',
                ArgReturn   :   ['FTAgnCode','FTAgnName']
            }
        }
        return oBrowseOptionReturn;
    }

    function JSxPXNextFuncAgency(ptData){
        if( ptData != '' || ptData != 'NULL'){
            $('#oetPXFrmBchCode').val('');
            $('#oetPXFrmBchName').val('');
        }
    }

    function JSxSetDefauleWahouse(ptData){

        $('#oetPXFrmWahCode').val('');
        $('#oetPXFrmWahName').val('');

        $('#oetPXFrmMerCode').val('');
        $('#oetPXFrmMerName').val('');

        $('#oetPXFrmShpCode').val('');
        $('#oetPXFrmShpName').val('');

        if(ptData == '' || ptData == 'NULL'){
            $('#obtPXBrowseShop').attr("disabled",true);
            $('#obtPXBrowseWahouse').attr("disabled",true);
        }else{
            var tResult = JSON.parse(ptData);
            $('#oetPXFrmWahCode').val(tResult[0]);
            $('#oetPXFrmWahName').val(tResult[1]);

            // $('#obtPXBrowseShop').attr("disabled",false);
            $('#obtPXBrowseWahouse').attr("disabled",false);
        }
    }

    /*===== Begin Import Excel =========================================================*/
    function JSxOpenImportForm(){
        if($('#oetPXFrmSplCode').val()!=""){
            var tNameModule     = 'purchaseinvoice';
            var tTypeModule     = 'document';
            var tAfterRoute     = 'JSxImportExcelCallback'; // call func
            var tFlagClearTmp   = '1' // null = ไม่สนใจ 1 = ลบหมดเเล้วเพิ่มใหม่ 2 = เพิ่มต่อเนื่อง

            var aPackdata = {
                'tNameModule'   : tNameModule,
                'tTypeModule'   : tTypeModule,
                'tAfterRoute'   : tAfterRoute,
                'tFlagClearTmp' : tFlagClearTmp
            };

                JSxImportPopUp(aPackdata);
        }else{
            $('#odvPXModalPleseselectCustomer').modal('show');
        }
    }

    function JSxImportExcelCallback(){
        JCNxOpenLoading();
        JSvPXCallEndOfBill();
        $('#ohdPXStaImport').val(1);
    }


//open Browse PO Doc
$('#obtPXBrowsePODoc').click(function() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) != 'undefined' && nStaSession == 1) {
        window.oSMPBrowsePODoc = oBrowsePODoc({
            'tReturnInputCode': 'oetPXRefIntDoc',
            'tReturnInputName': 'oetPXRefIntDocName',
        });
        JCNxBrowseData('oSMPBrowsePODoc');

    } else {
        JCNxShowMsgSessionExpired();
    }
});

//Browse WhTax
var oBrowsePODoc = function(poReturnInput) {
    var tInputReturnCode = poReturnInput.tReturnInputCode;
    var tInputReturnName = poReturnInput.tReturnInputName;
    var tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
    var tWhereCondition = '';
    if (tSesUsrLevel != "HQ") { // ไม่ใช่ผู้ใช้ระดับ HQ ดูได้แค่สาขาที่ login
        tWhereCondition += " AND TPSTWhTaxHD.FTBchCode IN ("+tBchMulti+") ";
    }

    tWhereCondition += " AND ( TPSTWhTaxHDDocRef.FTXshDocNo IS NULL OR TPSTWhTaxHDDocRef.FTXshRefType <> 2 ) ";

    var oSMPBrowsePODoc = {
        Title: ['document/purchaseorder/purchaseorder','ใบหักภาษี ณ.ที่จ่าย'],
        Table: {
            Master: 'TPSTWhTaxHD',
            PK: 'FTXshDocNo'
        },
        Join: {
            Table: ['TCNMBranch_L','TPSTWhTaxHDDocRef'/*,'TCNMUser_L','TCNMSpl_L'*/],
            On: [
                'TPSTWhTaxHD.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID =' + nLangEdits,
                'TPSTWhTaxHD.FTBchCode = TPSTWhTaxHDDocRef.FTBchCode AND TPSTWhTaxHD.FTXshDocNo = TPSTWhTaxHDDocRef.FTXshDocNo '
                // 'TPSTWhTaxHD.FTCreateBy = TCNMUser_L.FTUsrCode AND TPSTWhTaxHD.FTXphApvCode = TCNMUser_L.FTUsrCode AND TCNMUser_L.FNLngID = ' + nLangEdits,
                // 'TPSTWhTaxHD.FTSplCode = TCNMSpl_L.FTSplCode AND TCNMSpl_L.FNLngID = ' + nLangEdits
            ]
        },
        Where: {
            Condition: [ 
                " AND TPSTWhTaxHD.FTXshStaDoc = '1' AND TPSTWhTaxHD.FTXshStaApv = '1' AND TPSTWhTaxHD.FNXshStaDocAct = 1 ",
                // " AND TPSTWhTaxHD.FTXphDocNo NOT IN (SELECT FTXphRefInt FROM TAPTPIHD WITH(NOLOCK) WHERE ISNULL(FTXphRefInt,'') != '') ", //ไม่เอาใบ PO ที่เคยถูก Ref แล้ว
                tWhereCondition 
            ]
        },
        GrideView: {
            ColumnPathLang: 'document/purchaseorder/purchaseorder',
            ColumnKeyLang: ['สาขา','เลขที่เอกสาร','วันที่เอกสาร'],
            ColumnsSize  : ['20%','65%','15%'],
            WidthModal: 50,
            DataColumns: ['TCNMBranch_L.FTBchName', 'TPSTWhTaxHD.FTXshDocNo' /*,'TCNMUser_L.FTUsrName'*/,'TPSTWhTaxHD.FDXshDocDate'/*,'TPSTWhTaxHD.FTSplCode','TCNMSpl_L.FTSplName'*/],
            DataColumnsFormat: ['', '','Date:0'],
            // DisabledColumns: [4,5],
            Perpage: 10,
            OrderBy: ['TPSTWhTaxHD.FDCreateOn DESC'],
        },
        CallBack: {
            ReturnType: 'S',
            Value: [tInputReturnCode, "TPSTWhTaxHD.FTXshDocNo"],
            Text: [tInputReturnName, "TPSTWhTaxHD.FTXshDocNo"]
        },
        NextFunc: {
            FuncName: 'FSxPXNextFuncPODoc',
            ArgReturn: ['FDXshDocDate'/*,'FTSplCode','FTSplName'*/]
        },
    };
    return oSMPBrowsePODoc;
}


//Functionality: NextFunc
//Creator: 26/03/2021 Sooksanti(Non)
//Last Update: 05/04/2021 Napat(Jame) เพิ่มการดึง SplCode และ ดึงสินค้าจาก PO
//Return: -
function FSxPXNextFuncPODoc(poDataNextFunc) {
    if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {

        // var aDataNextFunc = JSON.parse(poDataNextFunc);
        var dDocDate = $.datepicker.formatDate('yy-mm-dd', new Date())
        $('#oetPXRefIntDocDate').datepicker('setDate', dDocDate);
        // $("#oetPXRefIntDocDate").datepicker("refresh");


        // let nChkPdtInTable = $('.xWPdtItem').length;
        // if( nChkPdtInTable > 0 ){
        //     let tMsgWarning = '<?=language('document/expenserecord/expenserecord', 'tPXMsgClearPdt');?>';
        //     FSvCMNSetMsgWarningDialog(tMsgWarning,'FSxPXMovePODTToDocTmp',poDataNextFunc);
        //     $("#odvModalWanning button.xCNBTNDefult2Btn.xWBtnCancel").show();
        //     $("#odvModalWanning button.xCNBTNDefult2Btn.xWBtnCancel").off('click').on('click',function(){
        //         let tRefIntDo = $('#oetPXRefIntDoc').val();
        //         $('#oetPXRefIntDocOld').val(tRefIntDo);
        //     });
        // }else{
        //     FSxPXMovePODTToDocTmp(poDataNextFunc);
        // }
    }
}

// Create By : Napat(Jame) 07/04/2021
//ReturnType: 0:DocDate, 1:DocNo, 2:SplCode, 3:SplName
function FSxPXMovePODTToDocTmp(poDataNextFunc){
    JCNxOpenLoading();
    var aDataNextFunc = JSON.parse(poDataNextFunc);
    $('#oetPXRefIntDocDate').datepicker("setDate", new Date(aDataNextFunc[0]));
    $('#oetPXRefIntDocDate').datepicker('update');

    $('#oetPXRefIntDoc').val(aDataNextFunc[1]);
    $('#oetPXRefIntDocName').val(aDataNextFunc[1]);

    $('#oetPXFrmSplCode').val(aDataNextFunc[2]);
    $('#oetPXFrmSplName').val(aDataNextFunc[3]);

    var ptXthDocNoSend  = "";
    if ($("#ohdPXRoute").val() == "docPXEventEdit") {
        ptXthDocNoSend = $('#oetPXDocNo').val();
    }

    var tPXVATInOrEx    = $('#ocmPXFrmSplInfoVatInOrEx').val();
    var tPXOptionAddPdt = $('#ocmPXFrmInfoOthReAddPdt').val();

    $('#oetPXInsertBarcode').attr('readonly',false);
    $('#oetPXInsertBarcode').val('');

    $.ajax({
        type : "POST",
        url: "docPXMovePODTToDocTmp",
        data:{
            'tPODocNo'            : aDataNextFunc[1],
            'tBCHCode'            : $('#oetPXFrmBchCode').val(),
            'tPXDocNo'            : ptXthDocNoSend,
            'tPXVATInOrEx'        : tPXVATInOrEx,
            'tPXOptionAddPdt'     : tPXOptionAddPdt,
            'ohdSesSessionID'     : $('#ohdSesSessionID').val(),
            'ohdPXUsrCode'        : $('#ohdPXUsrCode').val(),
            'ohdPXLangEdit'       : $('#ohdPXLangEdit').val(),
            'ohdSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
            'ohdPXSesUsrBchCode'  : $('#ohdPXSesUsrBchCode').val(),
            'nVatRate'            : $('#ohdPXFrmSplVatRate').val(),
            'nVatCode'            : $('#ohdPXFrmSplVatCode').val()
        },
        cache: false,
        timeout: 0,
        success: function(oResult){
            var aResult = JSON.parse(oResult);
            if(aResult['nStaEvent'] == 1){
                JSvPXLoadPdtDataTableHtml();
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

//บังคับให้เลือกผู้จำหน่าย
function JSxFocusInputCustomer(){
    $('#oetPXFrmSplName').focus();
}

function JSxNotFoundClose(){
    $('#oetPXInsertBarcode').focus();
}

//กดเลือกบาร์โค๊ด
function  JSxSearchFromBarcode(e,elem){
    var tValue = $(elem).val();
    if($('#oetPXFrmSplCode').val() != ""){
        JSxCheckPinMenuClose();
        if(tValue.length === 0){
        }else{
            $('#oetPXInsertBarcode').attr('readonly',true);
            JCNSearchBarcodePdt(tValue);
            $('#oetPXInsertBarcode').val('');
        }
    }else{
        $('#odvPXModalPleseselectCustomer').modal('show');
        $('#oetPXInsertBarcode').val('');
    }
    e.preventDefault();
}

//ค้นหาบาร์โค๊ด
function JCNSearchBarcodePdt(ptTextScan){
    var tPXSplCode = $('#oetPXFrmSplCode').val();

    var tWhereCondition = "";
    if( tPXSplCode != "" ){
        tWhereCondition = " AND FTPdtSetOrSN IN('1','2') ";
    }

    $.ajax({
        type : "POST",
        url : "BrowseDataPDTTableCallView",
        data :{
            Qualitysearch   : [],
            ReturnType  : "M",
            aPriceType  : ["Cost","tCN_Cost","Company","1"],
            // aPriceType  : ['Price4Cst',tPXSplCode],
            NextFunc    : "",
            SelectTier  : ["Barcode"],
            SPL         : $("#oetPXFrmSplCode").val(),
            BCH         : $("#oetPXFrmBchCode").val(),
            MCH         : $("#oetPXFrmMerCode").val(),
            SHP         : $("#oetPXFrmShpCode").val(),
            tInpSesSessionID : $('#ohdSesSessionID').val(),
            tInpSesUsrLevel  : $('#ohdSesUsrLevel').val(),
            tInpSesUsrBchCom : $('#ohdSesUsrBchCom').val(),
            tInpLangEdit     : $('#ohdPXLangEdit').val(),
            Where       : [tWhereCondition],
            tTextScan   : ptTextScan,
        },
        catch : false,
        timeout : 0,
        success : function (tResult){
            JCNxCloseLoading();
            var oText = JSON.parse(tResult);
            if(oText == '800'){
                $('#oetPXInsertBarcode').attr('readonly',false);
                $('#odvPXModalPDTNotFound').modal('show');
                $('#oetPXInsertBarcode').val('');
            }else{
                // พบสินค้ามีหลายบาร์โค้ด
                if(oText.length > 1){
                    $('#odvPXModalPDTMoreOne').modal('show');
                    $('#odvPXModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');

                    for(i=0; i<oText.length; i++){
                        var aNewReturn      = JSON.stringify(oText[i]);
                        var tTest = "["+aNewReturn+"]";
                        var oEncodePackData = window.btoa(unescape(encodeURIComponent(tTest)));
                        var tHTML = "<tr class='xCNColumnPDTMoreOne"+i+" xCNColumnPDTMoreOne' data-information='"+oEncodePackData+"' style='cursor: pointer;'>";
                            tHTML += "<td>"+oText[i].pnPdtCode+"</td>";
                            tHTML += "<td>"+oText[i].packData.PDTName+"</td>";
                            tHTML += "<td>"+oText[i].packData.PUNName+"</td>";
                            tHTML += "<td>"+oText[i].ptBarCode+"</td>";
                            tHTML += "</tr>";
                        $('#odvPXModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                    }

                    //เลือกสินค้า
                    $('.xCNColumnPDTMoreOne').off();

                    //ดับเบิ้ลคลิก
                    $('.xCNColumnPDTMoreOne').on('dblclick',function(e){
                        $('#odvPXModalPDTMoreOne').modal('hide');
                        var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                        FSvPXAddPdtIntoDocDTTempScan(tJSON); //Client
                        FSvPXAddBarcodeIntoDocDTTemp(aNewReturn); //Server
                    });

                    //คลิกได้เลย
                    $('.xCNColumnPDTMoreOne').on('click',function(e){
                        //เลือกสินค้าแบบตัวเดียว
                        $('.xCNColumnPDTMoreOne').removeClass('xCNActivePDT');
                        $('.xCNColumnPDTMoreOne').children().attr('style', 'background-color:transparent !important; color:#232C3D !important;');
                        $('.xCNColumnPDTMoreOne').children(':last-child').css('text-align','right');
                        $(this).addClass('xCNActivePDT');
                        $(this).children().attr('style', 'background-color:#FFFFFF !important; color:#FFF !important;');
                        $(this).children().last().css('text-align','right');
                    });

                }else{
                    //มีตัวเดียว
                    var aNewReturn  = JSON.stringify(oText);
                    console.log('aNewReturn: '+aNewReturn);
                    FSvPXAddPdtIntoDocDTTempScan(aNewReturn); //Client
                    FSvPXAddBarcodeIntoDocDTTemp(aNewReturn); //Server
                }
            }
        },
        error: function (jqXHR,textStatus,errorThrown){
            JCNSearchBarcodePdt(ptTextScan);
        }
    });
}

//เลือกสินค้า กรณีพบมากกว่าหนึ่งตัว
function JCNxConfirmPDTMoreOne($ptType){
    if($ptType == 1){
        $("#odvPXModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function( index ) {
            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
            FSvPXAddPdtIntoDocDTTempScan(tJSON);
            FSvPXAddBarcodeIntoDocDTTemp(tJSON);
        });
    }else{
        $('#oetPXInsertBarcode').attr('readonly',false);
        $('#oetPXInsertBarcode').val('');
    }
}

//หลังจากค้นหาเสร็จแล้ว
function FSvPXAddBarcodeIntoDocDTTemp(ptPdtData){
    var nStaSession = 1;
    if (typeof nStaSession !== "undefined" && nStaSession == 1){
        var ptXthDocNoSend  = "";
        if ($("#ohdPXRoute").val() == "docPXEventEdit") {
            ptXthDocNoSend = $('#oetPXDocNo').val();
        }

        var tPXVATInOrEx    = $('#ocmPXFrmSplInfoVatInOrEx').val();
        var tPXOptionAddPdt = $('#ocmPXFrmInfoOthReAddPdt').val();
        var nKey            = parseInt($('#otbPXDocPdtAdvTableList tr:last').attr('data-seqno'));


        $('#oetPXInsertBarcode').attr('readonly',false);
        $('#oetPXInsertBarcode').val('');

        $.ajax({
            type : "POST",
            url: "docPXAddPdtIntoDTDocTemp",
            data:{
                'tBCHCode'          : $('#oetPXFrmBchCode').val(),
                'tPXDocNo'          : ptXthDocNoSend,
                'tPXVATInOrEx'      : tPXVATInOrEx,
                'tPXOptionAddPdt'   : tPXOptionAddPdt,
                'tPXPdtData'        : ptPdtData,
                'tSeqNo'              : nKey,
                'ohdSesSessionID'     : $('#ohdSesSessionID').val(),
                'ohdPXUsrCode'        : $('#ohdPXUsrCode').val(),
                'ohdPXLangEdit'       : $('#ohdPXLangEdit').val(),
                'ohdSesUsrLevel'      : $('#ohdSesUsrLevel').val(),
                'ohdPXSesUsrBchCode'  : $('#ohdPXSesUsrBchCode').val(),
                'nVatRate'            : $('#ohdPXFrmSplVatRate').val(),
                'nVatCode'            : $('#ohdPXFrmSplVatCode').val()
            },
            cache: false,
            timeout: 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if(aResult['nStaEvent']==1){
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

function FSxPXCallPageHDDocRef(){

    var tDocNo  = "";
    if ($("#ohdPXRoute").val() == "docPXEventEdit") {
        tDocNo = $('#oetPXDocNo').val();
    }

    $.ajax({
        type : "POST",
        url: "docPXPageHDDocRef",
        data:{
            'ptPXDocNo'          : tDocNo
        },
        cache: false,
        timeout: 0,
        success: function(oResult){
            var aResult = JSON.parse(oResult);
            if( aResult['nStaEvent'] == 1 ){
            $('#odvPXTableHDRef').html(aResult['tPXViewPageHDRef']);
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

$(document).ready(function(){
    JSxPXEventCheckShowHDDocRef();
});

function JSxPXEventCheckShowHDDocRef(){
    var tPXRefType = $('#ocbPXRefType').val();
    if( tPXRefType == '1' ){
        $('.xWShowRefExt').hide();
        $('.xWShowRefInt').show();
    }else{
        $('.xWShowRefInt').hide();
        $('.xWShowRefExt').show();
    }
}

$('#ocbPXRefType').off('change').on('change',function(){
    $(this).selectpicker('refresh');
    JSxPXEventCheckShowHDDocRef();
});


//Function : Search Pdt
function JSvDOCSearchPdtHTML() {
  var value = $("#oetPXFrmFilterPdtHTML")
    .val()
    .toLowerCase();
  $("#otbPXDocPdtAdvTableList tbody tr ").filter(function () {
    tText = $(this).toggle(
      $(this)
        .text()
        .toLowerCase()
        .indexOf(value) > -1
    );
  });
}

function JSxPXEventClearValueInFormHDDocRef(){
    $('#oetPXRefDocNo').val('');
    $('#oetPXRefDocDate').val('');
    $('#oetPXRefKey').val('');
}

$('#obtPXAddDocRef').off('click').on('click',function(){
    $('#ofmPXFormAddDocRef').validate().destroy();
    JSxPXEventClearValueInFormHDDocRef();
    $('#odvPXModalAddDocRef').modal('show');
});

$('#obtPXConfirmAddDocRef').off('click').on('click',function(){

    $('#ofmPXFormAddDocRef').validate().destroy();

    $('#ofmPXFormAddDocRef').validate({
        focusInvalid: false,
        onclick: false,
        onfocusout: false,
        onkeyup: false,
        rules: {
            oetPXRefDocNo    : {"required" : true}
        },
        messages: {
            oetPXRefDocNo    : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
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
            $.ajax({
                type : "POST",
                url: "docPXEventAddEditHDDocRef",
                data:{
                    'ptRefDocNoOld'     : $('#oetPXRefDocNoOld').val(),
                    'ptPXDocNo'         : $('#oetPXDocNo').val(),
                    'ptRefType'         : $('#ocbPXRefType').val(),
                    'ptRefDocNo'        : $('#oetPXRefDocNo').val(),
                    'pdRefDocDate'      : $('#oetPXRefDocDate').val(),
                    'ptRefKey'          : $('#oetPXRefKey').val()
                },
                cache: false,
                timeout: 0,
                success: function(oResult){

                    JSxPXEventClearValueInFormHDDocRef();
                    $('#odvPXModalAddDocRef').modal('hide');

                    console.log(oResult);
                    FSxPXCallPageHDDocRef();
                    JCNxCloseLoading();
                    // var aResult = JSON.parse(oResult);
                    // if( aResult['nStaEvent'] == 1 ){
                    // $('#odvPXTableHDRef').html(aResult['tPXViewPageHDRef']);
                    //     JCNxCloseLoading();
                    // }else{
                    //     var tMessageError = aResult['tStaMessg'];
                    //     FSvCMNSetMsgErrorDialog(tMessageError);
                    //     JCNxCloseLoading();
                    // }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        },
    });

});



</script>
