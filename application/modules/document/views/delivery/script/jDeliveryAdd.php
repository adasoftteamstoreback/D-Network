<script type="text/javascript">
    var nLangEdits          = '<?=$this->session->userdata("tLangEdit");?>';
    var tUsrApvName         = '<?=$this->session->userdata("tSesUsername");?>';
    var tSesUsrLevel        = '<?=$this->session->userdata('tSesUsrLevel');?>';
    var tUserBchCode        = '<?=$this->session->userdata("tSesUsrBchCode");?>';
    var tUserBchName        = '<?=$this->session->userdata("tSesUsrBchName");?>';
    var tUserWahCode        = '<?=$this->session->userdata("tSesUsrWahCode");?>';
    var tRoute              = $('#ohdDLVRoute').val();
    var tDLVSesSessionID    = $("#ohdSesSessionID").val();

    $(document).ready(function(){
        JSxCheckPinMenuClose(); 

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

        //เพิ่มสินค้า
        $('#obtDLVDocBrowsePdt').unbind().click(function(){
            JSvDLVPDTBrowseList();
        });

        $('#obtDLVDocDate').unbind().click(function(){
            $('#oetDLVDocDate').datepicker('show');
        });

        $('#obtDLVDocTime').unbind().click(function(){
            $('#oetDLVDocTime').datetimepicker('show');
        });

        //Autogen
        $('#ocbDLVStaAutoGenCode').on('change', function (e) {
            if($('#ocbDLVStaAutoGenCode').is(':checked')){
                $("#oetDLVDocNo").val('');
                $("#oetDLVDocNo").attr("readonly", true);
                $('#oetDLVDocNo').closest(".form-group").css("cursor","not-allowed");
                $('#oetDLVDocNo').css("pointer-events","none");
                $("#oetDLVDocNo").attr("onfocus", "this.blur()");
                $('#ofmDLVFormAdd').removeClass('has-error');
                $('#ofmDLVFormAdd .form-group').closest('.form-group').removeClass("has-error");
                $('#ofmDLVFormAdd em').remove();
            }else{
                $('#oetDLVDocNo').closest(".form-group").css("cursor","");
                $('#oetDLVDocNo').css("pointer-events","");
                $('#oetDLVDocNo').attr('readonly',false);
                $("#oetDLVDocNo").removeAttr("onfocus");
            }
        });

        //control ปุ่ม [อนุมัติแล้ว หรือยกเลิก]
        if('<?=$tDLVStaApv;?>' == 1 || '<?=$tDLVStaDoc?>' == 3){
            // ปุ่มอนุมัติ
            $('#obtDLVApproveDoc').hide();

            // ปุ่มยกเลิก
            $('#obtDLVCancelDoc').hide();

            // อินพุต
            $(".form-control").attr("disabled", true);

            // ปุ่มเลือก
            $('.xCNBtnBrowseAddOn').addClass('disabled');
            $('.xCNBtnBrowseAddOn').attr('disabled', true);

            // ปุ่มเวลา
            $('.xCNBtnDateTime').addClass('disabled');
            $('.xCNBtnDateTime').attr('disabled', true);

            // หมายเหตุ
            $('#otaDLVFrmInfoOthRmk').attr("disabled", false);

            // ช่องค้นหา
            $('#oetSearchPdtHTML').attr("disabled", false);

            // เพิ่มข้อมูลสินค้า
            $('.xCNHideWhenCancelOrApprove').hide();
        }

    });

    // [ปุ่ม] บันทึกข้อมูล
    $('#obtDLVSubmitFromDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            var tCheckIteminTable = $('#otbDLVDocPdtAdvTableList .xWPdtItem').length;
            if (tCheckIteminTable > 0) {
                $('#obtDLVSubmitDocument').click();
            } else {
                FSvCMNSetMsgWarningDialog($('#ohdDLVValidatePdt').val());
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // [ปุ่ม] ยกเลิกเอกสาร
    $('#obtDLVCancelDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSnDLVCancelDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // [ปุ่ม] ย้อนกลับ
    $('#obtDLVCallBackPage').unbind().click(function() {
        JSvDLVCallPageList();
    });

    // [ปุ่ม] อนุมัติ
    $('#obtDLVApproveDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxDLVSubmitEventByButton('approve');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //ค้นหาสินค้าใน temp
    function JSvDLVSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbDLVDocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    //กดเลือกบาร์โค๊ด
    function JSxSearchFromBarcode(e, elem) {
        var tValue = $(elem).val();
        $('#oetDLVInsertBarcode').attr('readonly', true);
        JCNSearchBarcodePdt(tValue);
        $('#oetDLVInsertBarcode').val('');
        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
    function JCNSearchBarcodePdt(ptTextScan) {

        var tWhereCondition = "";
        var aMulti          = [];

        $.ajax({
            type: "POST",
            url: "BrowseDataPDTTableCallView",
            data: {
                aPriceType          : ["Cost", "tCN_Cost", "Company", "1"],
                NextFunc            : "",
                SPL                 : "",
                BCH                 : $("#oetDLVBchCode").val(),
                tInpSesSessionID    : $('#ohdSesSessionID').val(),
                tInpUsrCode         : $('#ohdDLVUsrCode').val(),
                tInpLangEdit        : '',
                tInpSesUsrLevel     : '',
                tInpSesUsrBchCom    : '',
                Where               : [tWhereCondition],
                tTextScan           : ptTextScan
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                if (oText == '800') {
                    $('#oetDLVInsertBarcode').attr('readonly', false);
                    $('#odvDLVModalPDTNotFound').modal('show');
                    $('#oetDLVInsertBarcode').val('');
                } else {
                    if (oText.length > 1) {

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvDLVModalPDTMoreOne').modal('show');
                        $('#odvDLVModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
                        for (i = 0; i < oText.length; i++) {
                            var aNewReturn = JSON.stringify(oText[i]);
                            var tTest = "[" + aNewReturn + "]";
                            var oEncodePackData = window.btoa(unescape(encodeURIComponent(tTest)));
                            var tHTML = "<tr class='xCNColumnPDTMoreOne" + i + " xCNColumnPDTMoreOne' data-information='" + oEncodePackData + "' style='cursor: pointer;'>";
                            tHTML += "<td>" + oText[i].pnPdtCode + "</td>";
                            tHTML += "<td>" + oText[i].packData.PDTName + "</td>";
                            tHTML += "<td>" + oText[i].packData.PUNName + "</td>";
                            tHTML += "<td>" + oText[i].ptBarCode + "</td>";
                            tHTML += "</tr>";
                            $('#odvDLVModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick', function(e) {
                            $('#odvDLVModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            FSvDLVAddPdtIntoDocDTTemp(tJSON); //Client
                            JSxDLVEventInsertToTemp(tJSON); //Serve
                        });

                        //คลิกได้เลย
                        $('.xCNColumnPDTMoreOne').on('click', function(e) {
                            //เลือกสินค้าแบบตัวเดียว
                            $('.xCNColumnPDTMoreOne').removeClass('xCNActivePDT');
                            $('.xCNColumnPDTMoreOne').children().attr('style', 'background-color:transparent !important; color:#232C3D !important;');
                            $('.xCNColumnPDTMoreOne').children(':last-child').css('text-align', 'right');

                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important;');
                            $(this).children().last().css('text-align', 'right');
                        });
                    } else {
                        //มีตัวเดียว
                        var aNewReturn = JSON.stringify(oText);
                        FSvDLVAddPdtIntoDocDTTemp(aNewReturn); //Client
                        JSxDLVEventInsertToTemp(aNewReturn); //Server
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR,textStatus,errorThrown);
            }
        });
    }

    // เลือกสินค้า กรณีพบมากกว่าหนึ่งตัว
    function JCNxConfirmPDTMoreOne($ptType) {
        if ($ptType == 1) {
            $("#odvDLVModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function(index) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                FSvDLVAddPdtIntoDocDTTemp(tJSON);
                JSxDLVEventInsertToTemp(tJSON);
            });
        } else {
            $('#oetDLVInsertBarcode').attr('readonly', false);
            $('#oetDLVInsertBarcode').val('');
        }
    }

    //เลือกตัวแทนขาย ส่งจากตัวเเทนขาย
    $('#obtDLVBrowseFrmAgn').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDLVBrowseAgnOption   = undefined;
            oDLVBrowseAgnOption          = oDLVBrowseAgn({
                'tReturnInputCode'  : 'oetDLVFrmAgnCode',
                'tReturnInputName'  : 'oetDLVFrmAgnName',
                'tNextFuncName'     : 'JSxDLVSetConditionAfterSelectAGN'
            });
            JCNxBrowseData('oDLVBrowseAgnOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //เลือกตัวแทนขาย ตัวเเทนขายปลายทาง
    $('#obtDLVBrowseToAgn').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDLVBrowseAgnOption   = undefined;
            oDLVBrowseAgnOption          = oDLVBrowseAgn({
                'tReturnInputCode'  : 'oetDLVToAgnCode',
                'tReturnInputName'  : 'oetDLVToAgnName',
                'tNextFuncName'     : 'JSxDLVSetConditionAfterSelectAGN'
            });
            JCNxBrowseData('oDLVBrowseAgnOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // เลือกสาขา ที่สร้าง
    $('#obtDLVBrowseBch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDLVBrowseBranchFromOption  = oDLVBrowseBranch({
                'tReturnInputCode'  : 'oetDLVBchCode',
                'tReturnInputName'  : 'oetDLVBchName'
            });
            JCNxBrowseData('oDLVBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // เลือกสาขา ต้นทาง
    $('#obtDLVBrowseFrmBch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDLVBrowseBranchFromOption  = oDLVBrowseBranch({
                'tReturnInputCode'  : 'oetDLVFrmBchCode',
                'tReturnInputName'  : 'oetDLVFrmBchName'
            });
            JCNxBrowseData('oDLVBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // เลือกสาขา ปลายทาง
    $('#obtDLVBrowseToBch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDLVBrowseBranchFromOption  = oDLVBrowseBranch({
                'tReturnInputCode'  : 'oetDLVToBchCode',
                'tReturnInputName'  : 'oetDLVToBchName'
            });
            JCNxBrowseData('oDLVBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oDLVBrowseBranch = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tUsrLevel 	  	    = "<?=$this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti 	    = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var nCountBch 		    = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
        var nLangEdits          = "<?=$this->session->userdata("tLangEdit")?>";
        var tWhere 			    = "";

        if(nCountBch == 1){
            $('#obtDLVBrowseBch').attr('disabled',true);
        }

        // if(tUsrLevel != "HQ"){
        //     tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
        // }else{
        //     tWhere = "";
        // }

        //เลือกส่งจากสาขา
        if(tInputReturnCode == 'oetDLVFrmBchCode'){
            if($('#oetDLVFrmAgnCode').val() == ''){ //ถ้าไม่มีตัวแทนขาย
                tWhere += ''
            }else{
                tWhere +=  " AND (TCNMBranch.FTAgnCode = " + $('#oetDLVFrmAgnCode').val() +" )";
            }
        }else if(tInputReturnCode == 'oetDLVToBchCode'){ //เลือกสาขาปลายทาง
            if($('#oetDLVToAgnCode').val() == ''){ //ถ้าไม่มีตัวแทนขาย
                tWhere += ''
            }else{
                tWhere +=  " AND (TCNMBranch.FTAgnCode = " + $('#oetDLVToAgnCode').val() +" )";
            }
        }

        var oOptionReturn       = {
            Title   :   ['company/branch/branch','tBCHTitle'],
            Table   :   {Master:'TCNMBranch',PK:'FTBchCode'},
            Join    :   {
                Table   : ['TCNMBranch_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
            },
            Where   : {
                Condition : [tWhere]
            },
            GrideView:{
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['tBCHCode','tBCHName'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['',''],
                Perpage             : 20,
                OrderBy             : ['TCNMBranch.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"],
            },
        }
        return oOptionReturn;
    };

    var tKeepAgn           = '';
    var oDLVBrowseAgn      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var tUsrLevSession      = '<?=$this->session->userdata("tSesUsrLevel"); ?>';
        var tWhereAgn           = '';

        //เก็บค่าไว้เอาไปใช้สำหรับ nextfunc
        if(tInputReturnCode == 'oetDLVFrmAgnCode'){
            tKeepAgn = 'FRM';
        }else{
            tKeepAgn = 'TO';
        }

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
                FuncName        : tNextFuncName,
                ArgReturn       : ['FTAgnCode']
            }
        };
        return oOptionReturn;
    }

    //หลังจากเลือกตัวแทนขาย
    function JSxDLVSetConditionAfterSelectAGN(ptPdtData){
        if(tKeepAgn == 'FRM'){ //ล้างค่าสาขาต้นทาง
            $('#oetDLVFrmBchCode').val('');
            $('#oetDLVFrmBchName').val('');
        }else{ //ล้างค่าสาขาปลายทาง
            $('#oetDLVToBchCode').val('');
            $('#oetDLVToBchName').val('');
        }
    }

    // เลือกชือผู้รับ/ลูกค้า
    $('#obtDLVBrowseCustomers').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDLVCustomerOption   = undefined;
            oDLVCustomerOption          = oDLVCustomer({
                'tReturnInputCode'  : 'oetDLVCstCode',
                'tReturnInputName'  : 'oetDLVCstName',
                'tNextFuncName'     : 'JSxWhenSeletedCustomer',
                'aArgReturn'        : ['FTCstName', 'FTCstTel', 'FTCstEmail' ,'FTCstCode']
            });
            JCNxBrowseData('oDLVCustomerOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // เลือกชือผู้รับ/ลูกค้า
    var oDLVCustomer    = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var oOptionReturn       = {
            Title   : ['customer/customer/customer', 'tCSTTitle'],
            Table   : {Master:'TCNMCst', PK:'FTCstCode'},
            Join    : {
                Table: ['TCNMCst_L'],
                On: [
                    'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = '+nLangEdits
                ]
            },
            Where:{
                Condition           : ["AND TCNMCst.FTCstStaActive = '1'"]
            },
            GrideView:{
                ColumnPathLang      : 'customer/customer/customer',
                ColumnKeyLang       : ['tCSTCode', 'tCSTName','เบอร์โทร','อีเมล'],
                ColumnsSize         : ['15%', '50%', '15%', '15%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName','TCNMCst.FTCstTel','TCNMCst.FTCstEmail'],
                DataColumnsFormat   : ['','','',''],
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

    //หลังจากเลือกลูกค้า
    function JSxWhenSeletedCustomer(poDataNextFunc){
        aData = '';
        if (poDataNextFunc != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            $('#oetDLVCstEmail').val((aData[2] == '') ? '-' : aData[2]);
            $('#oetDLVCstTel').val((aData[1] == '') ? '-' : aData[1]);
        }
    }

    // เลือกพนักงานส่งของ 
    $('#obtDLVBrowseCstDeliverly').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDLVCstDeliverlyOption   = undefined;
            oDLVCstDeliverlyOption          = oDLVCstDeliverly({
                'tReturnInputCode'  : 'oetDLVCstDeliverlyCode',
                'tReturnInputName'  : 'oetDLVCstDeliverlyName'
            });
            JCNxBrowseData('oDLVCstDeliverlyOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // เลือกพนักงานส่งของ
    var oDLVCstDeliverly    = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var aArgReturn          = poDataFnc.aArgReturn;

        
        var oOptionReturn = {
            Title : ['authen/user/user','พนักงานส่งของ'],
            Table:{Master:'TCNMUser',PK:'FTUsrCode',PKName:'FTUsrName'},
            Join : {
                Table:	['TCNMUser_L'],
                On:['TCNMUser_L.FTUsrCode = TCNMUser.FTUsrCode AND TCNMUser_L.FNLngID ='+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'authen/user/user',
                ColumnKeyLang	: ['tUSRCode','tUSRName'],
                ColumnsSize		: ['15%','75%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMUser.FTUsrCode','TCNMUser_L.FTUsrName'],
                DataColumnsFormat : ['',''],
                Perpage			: 5,
                OrderBy			: ['TCNMUser_L.FTUsrName'],
                SourceOrder		: "ASC"
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMUser.FTUsrCode"],
                Text		: [tInputReturnName,"TCNMUser_L.FTUsrName"],
            }
        }
        return oOptionReturn;
    }

    //เลือกสินค้า
    function JSvDLVPDTBrowseList() {

        var dTime = new Date();
        var dTimelocalStorage = dTime.getTime();

        $.ajax({
            type: "POST",
            url : "BrowseDataPDT",
            data: {
                Qualitysearch   : [],
                PriceType       : [ "Pricesell"],
                SelectTier      : ["Barcode"],
                ShowCountRecord : 10,
                NextFunc        : "FSvDLVNextFuncB4SelPDT",
                ReturnType      : "M",
                TimeLocalstorage: dTimelocalStorage,
                aAlwPdtType     : ['T1','T3','T4','T5','T6','S2','S3','S4']
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $("#odvModalDOCPDT").modal({ backdrop: "static", keyboard: false });
                $("#odvModalDOCPDT").modal({ show: true });
                localStorage.removeItem("LocalItemDataPDT");
                $("#odvModalsectionBodyPDT").html(tResult);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    //หลังจากเลือกสินค้า
    function JSxDLVEventInsertToTemp(ptPdtData){
        var ptDLVDocNo = "";
        if ($("#ohdDLVRoute").val() == "docDLVEventEdit") {
            ptDLVDocNo = $("#oetDLVDocNo").val();
        }

        var tDLVOptionAddPdt    = $('#ocmDLVFrmInfoOthReAddPdt').val();
        var nKey                = parseInt($('#otbDLVDocPdtAdvTableList tr:last').attr('data-seqno'));

        $.ajax({
            type    : "POST",
            url     : "docDLVAddPdtIntoDTDocTemp",
            data    : {
                'tSelectBCH'        : $('#oetDLVBchCode').val(),
                'tDLVDocNo'         : ptDLVDocNo,
                'tDLVOptionAddPdt'  : tDLVOptionAddPdt,
                'tDLVPdtData'       : ptPdtData,
                'tSeqNo'            : nKey
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aResult = JSON.parse(oResult);
                if (aResult['nStaEvent'] == 1) {
                    JCNxCloseLoading();
                    $('#oetDLVInsertBarcode').attr('readonly', false);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //---------------- เอกสารอ้างอิง ----------------//

    // [เอกสารอ้างอิง] โหลด Table อ้างอิงเอกสารทั้งหมด
    function JSxDLVCallPageHDDocRef(){
        $.ajax({
            type    : "POST",
            url     : "docDLVPageHDDocRefList",
            data:{
                'ptDocNo'       : $('#oetDLVDocNo').val()
            },
            cache   : false,
            timeout : 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                    $('#odvDLVTableHDRef').html(aResult['tViewPageHDRef']);
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

    // [เอกสารอ้างอิง] กดเพิ่มเอกสารอ้างอิง (ภายใน ภายนอก)
    $('#obtDLVAddDocRef').off('click').on('click',function(){
        JSxDLVEventClearValueInFormHDDocRef();
        $('#odvDLVModalAddDocRef').modal('show');
        JSxDLVEventCheckShowHDDocRef();
    });

    // [เอกสารอ้างอิง] เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    $('#ocbDLVRefType').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        JSxDLVEventCheckShowHDDocRef();
    });

    // [เอกสารอ้างอิง] กดเลือกอ้างอิงเอกสารภายใน (ใบขาย)
    $('#obtDLVBrowseRefDoc').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){

            //กรุณาเลือกลูกค้าก่อน
            if($('#oetDLVCstCode').val() == "" || $('#oetDLVCstCode').val() == null){
                $('#odvDLVModalPleseselectCST').modal('show');
                return;
            }

            JSxCallDLVRefIntDoc();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // [เอกสารอ้างอิง] Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    function JSxDLVEventCheckShowHDDocRef(){
        var tDLVRefType = $('#ocbDLVRefType').val();
        if( tDLVRefType == '1' ){
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();
        }else{
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }

    // [เอกสารอ้างอิง] เคลียร์ค่า
    function JSxDLVEventClearValueInFormHDDocRef(){
        $('#oetDLVRefDocNo').val('');
        $('#oetDLVRefDocDate').val('');
        $('#oetDLVDocRefInt').val('');
        $('#oetDLVDocRefIntName').val('');
        $('#oetDLVRefKey').val('');
    }

    // [เอกสารอ้างอิง] Browse เอกสารอ้างอิงภายใน (ใบจ่ายโอน-สาขา หรือ ใบสั่งขาย)
    function JSxCallDLVRefIntDoc(){
        var tBCHCode    = $('#oetDLVBchCode').val();
        var tBCHName    = $('#oetDLVBchName').val();
        var tRefDoc     = $('#ocbDLVRefDoc').val();
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docDLVCallRefIntDoc",
            data    : {
                'tBCHCode'      : tBCHCode,
                'tBCHName'      : tBCHName,
                'tRefDoc'       : tRefDoc
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                JCNxCloseLoading();
                $('#odvDLVFromRefIntDoc').html(oResult);
                $('#odvDLVModalRefIntDoc').modal({backdrop : 'static' , show : true});

                var tTextRefPanel = "อ้างอิงเอกสารใบขาย";
                $('.olbDLVModalRefIntDoc').text(tTextRefPanel);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // [เอกสารอ้างอิง] กดยืนยัน (ระดับสินค้าลง Temp)
    $('#obtConfirmRefDocInt').click(function(){

        var tRefIntDocNo    =  $('.xDLVRefInt.active').data('docno');
        var tRefIntDocDate  =  $('.xDLVRefInt.active').data('docdate');
        var tRefIntBchCode  =  $('.xDLVRefInt.active').data('bchcode');
        var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm){
            return $(this).val();
        }).get();

        $('#oetDLVRefDocDate').val(tRefIntDocDate);
        $('#oetDLVDocRefIntName').val(tRefIntDocNo);

        var tBchcodeto    =  $('.xDLVRefInt.active').data('bchcodeto');
        var tBchnameto    =  $('.xDLVRefInt.active').data('bchnameto');
        var tAgncodeto    =  $('.xDLVRefInt.active').data('agncodeto');
        var tAgnnameto    =  $('.xDLVRefInt.active').data('agnnameto');

        if(tAgncodeto != '' || tAgncodeto != null){
            $('#oetDLVToAgnCode').val(tAgncodeto);
            $('#oetDLVToAgnName').val(tAgnnameto);
        }

        if(tBchcodeto != '' || tBchcodeto != null){
            $('#oetDLVToBchCode').val(tBchcodeto);
            $('#oetDLVToBchName').val(tBchnameto);
        }

        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docDLVCallRefIntDocInsertDTToTemp",
            data    : {
                'tDLVDocNo'          : $('#oetDLVDocNo').val(),
                'tDLVFrmBchCode'     : $('#oetDLVBchCode').val(),
                'tRefIntDocNo'       : tRefIntDocNo,
                'tRefIntBchCode'     : tRefIntBchCode,
                'aSeqNo'             : aSeqNo,
                'tRefDoc'            : $('#ocbDLVRefDoc').val(),
                'tInsertOrUpdateRow' : $('#ocmDLVFrmInfoOthReAddPdt').val()
            },
            cache   : false,
            Timeout : 0,
            success : function (oResult){
                JSvDLVLoadPdtDataTableHtml();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });

    // [เอกสารอ้างอิง] กดยืนยัน (ระดับเอกสารลง TCNTDocHDRefTmp)
    $('#ofmDLVFormAddDocRef').off('click').on('click',function(){
        $('#ofmDLVFormAddDocRef').validate().destroy();
        $('#ofmDLVFormAddDocRef').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetDLVRefDocNo    : {"required" : true}
            },
            messages: {
                oetDLVRefDocNo    : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
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

                if($('#ocbDLVRefType').val() == 1){         
                    //อ้างอิงเอกสารภายใน
                    var tDocNoRef       = $('#oetDLVDocRefIntName').val();
                    var tDocRefKey      = 'ABB';
                }else{                                     
                     //อ้างอิงเอกสารภายนอก
                    var tDocNoRef       = $('#oetDLVRefDocNo').val();
                    var tDocRefKey      = $('#oetDLVRefKey').val();
                }


                $.ajax({
                    type    : "POST",
                    url     : "docDLVEventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld'     : $('#oetDLVRefDocNoOld').val(),
                        'ptDocNo'           : $('#oetDLVDocNo').val(),
                        'ptRefType'         : $('#ocbDLVRefType').val(),
                        'ptRefDocNo'        : tDocNoRef,
                        'pdRefDocDate'      : $('#oetDLVRefDocDate').val(),
                        'ptRefKey'          : tDocRefKey
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        JSxDLVEventClearValueInFormHDDocRef();
                        $('#odvDLVModalAddDocRef').modal('hide');

                        JSxDLVCallPageHDDocRef();
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

    // พิมพ์เอกสาร
    function JSxDLVPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tDLVBchCode); ?>'},
            {"DocCode"      : '<?=@$tDLVDocNo; ?>'},
            {"DocBchCode"   : '<?=@$tDLVBchCode;?>'}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMSBillDo?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

    // ยกเลิกเอกสาร
    function JSnDLVCancelDocument(pbIsConfirm) {
        var tDLVDocNo = $("#oetDLVDocNo").val();
        if (pbIsConfirm) {
            $.ajax({
                type: "POST",
                url: "docDLVCancelDocument",
                data: {
                    'ptDLVDocNo'    : tDLVDocNo
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $("#odvDLVPopupCancel").modal("hide");
                    $('.modal-backdrop').remove();
                    var aReturnData = JSON.parse(tResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSvDLVCallPageEdit(tDLVDocNo);
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
        } else {
            $('#odvDLVPopupCancel').modal({ backdrop: 'static', keyboard: false });
            $("#odvDLVPopupCancel").modal("show");
        }
    }

    // =========================================== ลบข้อมูล =========================================== //

    // [ลบทั้งหมด] รายการสินค้า
    $('#odvDLVModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSnDLVRemovePdtDTTempMultiple();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // [ลบทั้งหมด] เลือกทั้งหมด
    $('#ocbCheckAll').click(function(){
        if($(this).is(':checked')==true){
            $('.ocbListItem').prop('checked',true);
            $("#odvDLVMngDelPdtInTableDT #oliDLVBtnDeleteMulti").removeClass("disabled");
        }else{
            $('.ocbListItem').prop('checked',false);
            $("#odvDLVMngDelPdtInTableDT #oliDLVBtnDeleteMulti").addClass("disabled");
        }
    });

    // [ลบทั้งหมด] ลบ
    function FSxDLVSelectMulDel(ptElm){
        var tDLVDocNo           = $('#oetDLVDocNo').val();
        var tDLVSeqNo           = $(ptElm).parents('.xWPdtItem').data('key');
        var tDLVPdtCode         = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        var tDLVBarCode         = $(ptElm).parents('.xWPdtItem').data('barcode');
        $(ptElm).prop('checked', true);

        var oLocalItemDTTemp    = localStorage.getItem("DLV_LocalItemDataDelDtTemp");
        var oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        var aArrayConvert   = [JSON.parse(localStorage.getItem("DLV_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tDLVDocNo,
                'tSeqNo'    : tDLVSeqNo,
                'tPdtCode'  : tDLVPdtCode,
                'tBarCode'  : tDLVBarCode,
            });
            localStorage.setItem("DLV_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxDLVTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStDLVFindObjectByKey(aArrayConvert[0],'tSeqNo',tDLVSeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo'    : tDLVDocNo,
                    'tSeqNo'    : tDLVSeqNo,
                    'tPdtCode'  : tDLVPdtCode,
                    'tBarCode'  : tDLVBarCode,
                });
                localStorage.setItem("DLV_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxDLVTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("DLV_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tDLVSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("DLV_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxDLVTextInModalDelPdtDtTemp();
            }
        }
        JSxDLVShowButtonDelMutiDtTemp();
    }

    // [ลบทั้งหมด] Pase Text Product Item In Modal Delete
    function JSxDLVTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("DLV_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tDLVTextDocNo   = "";
            var tDLVTextSeqNo   = "";
            var tDLVTextPdtCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tDLVTextDocNo    += aValue.tDocNo;
                tDLVTextDocNo    += " , ";

                tDLVTextSeqNo    += aValue.tSeqNo;
                tDLVTextSeqNo    += " , ";

                tDLVTextPdtCode  += aValue.tPdtCode;
                tDLVTextPdtCode  += " , ";
            });
            $('#odvDLVModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvDLVModalDelPdtInDTTempMultiple #ohdConfirmDLVDocNoDelete').val(tDLVTextDocNo);
            $('#odvDLVModalDelPdtInDTTempMultiple #ohdConfirmDLVSeqNoDelete').val(tDLVTextSeqNo);
            $('#odvDLVModalDelPdtInDTTempMultiple #ohdConfirmDLVPdtCodeDelete').val(tDLVTextPdtCode);
        }
    }

    // [ลบทั้งหมด] ความคุมปุ่มตัวเลือก -> ลบทั้งหมด
    function JSxDLVShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("DLV_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#odvDLVMngDelPdtInTableDT #oliDLVBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#odvDLVMngDelPdtInTableDT #oliDLVBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#odvDLVMngDelPdtInTableDT #oliDLVBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    // [ลบทั้งหมด] Chack Value LocalStorage
    function JStDLVFindObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    // [ลบทั้งหมด] Fucntion Call Delete Multiple Doc DT Temp
    function JSnDLVRemovePdtDTTempMultiple(){
        var tDLVDocNo        = $("#oetDLVDocNo").val();
        var tDLVBchCode      = $('#oetDLVBchCode').val();
        var aDataPdtCode    = JSoDLVRemoveCommaData($('#odvDLVModalDelPdtInDTTempMultiple #ohdConfirmDLVPdtCodeDelete').val().trim());
        var aDataSeqNo      = JSoDLVRemoveCommaData($('#odvDLVModalDelPdtInDTTempMultiple #ohdConfirmDLVSeqNoDelete').val().trim());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvDLVModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvDLVModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('DLV_LocalItemDataDelDtTemp');
        $('#odvDLVModalDelPdtInDTTempMultiple #ohdConfirmDLVDocNoDelete').val('');
        $('#odvDLVModalDelPdtInDTTempMultiple #ohdConfirmDLVSeqNoDelete').val('');
        $('#odvDLVModalDelPdtInDTTempMultiple #ohdConfirmDLVPdtCodeDelete').val('');
        $('#odvDLVModalDelPdtInDTTempMultiple #ohdConfirmDLVBarCodeDelete').val('');
        JCNxCloseLoading();

        $.ajax({
            type    : "POST",
            url     : "docDLVRemovePdtInDTTmp",
            data    : {
                'tBchCode'      : tDLVBchCode,
                'tDocNo'        : tDLVDocNo,
                'nSeqNo'        : aDataSeqNo,
                'tPdtCode'      : aDataPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var tCheckIteminTable = $('#otbDLVDocPdtAdvTableList tbody tr').length;
                if(tCheckIteminTable==0){
                    $('#otbDLVDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                JSxDLVCountPdtItems();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResDLVnseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // [ลบทั้งหมด] Remove Comma
    function JSoDLVRemoveCommaData(paData){
        var aTexts              = paData.substring(0, paData.length - 2);
        var aDataSplit          = aTexts.split(" , ");
        var aDataSplitlength    = aDataSplit.length;
        var aNewDataDeleteComma = [];

        for ($i = 0; $i < aDataSplitlength; $i++) {
            aNewDataDeleteComma.push(aDataSplit[$i]);
        }
        return aNewDataDeleteComma;
    }

    // [ลบตัวเดียว] ลบรายการสินค้าในตาราง DT Temp
    function JSnDLVDelPdtInDTTempSingle(elem) {
        var tPdtCode = $(elem).parents("tr.xWPdtItem").attr("data-pdtcode");
        var tSeqno   = $(elem).parents("tr.xWPdtItem").attr("data-key");
        $(elem).parents("tr.xWPdtItem").remove();
        JSnDLVRemovePdtDTTempSingle(tSeqno, tPdtCode);
    }

    // [ลบข้อมูล] ลบรายการสินค้าในตาราง DT Temp
    function JSnDLVRemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tDLVDocNo        = $("#oetDLVDocNo").val();
        var tDLVBchCode      = $('#oetDLVBchCode').val();
        JCNxCloseLoading();

        $.ajax({
            type    : "POST",
            url     : "docDLVRemovePdtInDTTmp",
            data    : {
                'tBchCode'      : tDLVBchCode,
                'tDocNo'        : tDLVDocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JCNxLayoutControll();
                    JSxDLVCountPdtItems();
                    var tCheckIteminTable = $('#otbDLVDocPdtAdvTableList tbody tr').length;
                    if(tCheckIteminTable == 0){
                        $('#otbDLVDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                    }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResDLVnseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // =========================================== ที่อยู่จัดส่ง =========================================== //

    //ประเภทที่อยู่
    var nStaShwAddress = <?=$nStaShwAddress?>;
    if( nStaShwAddress == 1 ){
        $('.xWDLVAddress1').show();
        $('.xWDLVAddress2').hide();
    }else{
        $('.xWDLVAddress1').hide();
        $('.xWDLVAddress2').show();
    }

    $('#obtDLVBrowseShip').click(function(){
        //ถ้าเอกสารบึนทึกข้อมูลแล้ว
        if($('#oetDLVDocNo').val() != '' || $('#oetDLVDocNo').val() != null){
            //เอาที่อยู่ของมีข้อมูลมาเเล้วมาโชว์
            JSxSetAddrInInput();
        }

        $('#odvDLVModalAddress').modal('show');
    });

    //เอาที่อยู่ของมีข้อมูลมาเเล้วมาโชว์
    function JSxSetAddrInInput(){
        var tVariableType      = 'Ship';
        var tFTAddV1No         = $('#ohdDLV'+tVariableType+'AddV1No').val();
        var tFTAddV1Soi        = $('#ohdDLV'+tVariableType+'V1Soi').val();
        var tFTAddV1Village    = $('#ohdDLV'+tVariableType+'V1Village').val();
        var tFTAddV1Road       = $('#ohdDLV'+tVariableType+'V1Road').val();
        var tFTSudName         = $('#ohdDLV'+tVariableType+'V1SubDistrict').val();
        var tFTDstName         = $('#ohdDLV'+tVariableType+'V1District').val();
        var tFTPvnName         = $('#ohdDLV'+tVariableType+'V1Province').val();
        var tFTAddV1PostCode   = $('#ohdDLV'+tVariableType+'V1PostCode').val();

        var tFNAddSeqNo        = $('#ohdDLV'+tVariableType+'AddSeqNo').val();
        var tFTAddTaxNo        = $('#ohdDLV'+tVariableType+'AddTaxNo').val();
        var tFTAddName         = $('#ohdDLV'+tVariableType+'AddName').val();
        var tFTAddTel          = $('#ohdDLV'+tVariableType+'Tel').val();
        var tFTAddFax          = $('#ohdDLV'+tVariableType+'Fax').val();
        
        var tFTAddV2Desc1      = $('#ohdDLV'+tVariableType+'AddV2Desc1').val();
        var tFTAddV2Desc2      = $('#ohdDLV'+tVariableType+'AddV2Desc2').val();
        
        //โชว์ค่า
        $('#ohdShipAddrCode').val(tFNAddSeqNo);
        $('#ohdShipAddrName').val(tFTAddName);

        $('#ohdShipAddrNoHouse').val(tFTAddV1No);
        $('#ohdShipV1Soi').val(tFTAddV1Soi);
        $('#ohdShipAddrVillage').val(tFTAddV1Village);
        $('#ohdShipAddrRoad').val(tFTAddV1Road);
        $('#ohdShipAddrSoi').val(tFTAddV1Soi);
        $('#ohdShipAddrSubDistrict').val(tFTSudName);
        $('#ohdShipAddrDistict').val(tFTDstName);
        $('#ohdShipAddrProvince').val(tFTPvnName);
        $('#ohdShipZipCode').val(tFTAddV1PostCode);
        
        $('#ohdShipAddrTaxNo').val(tFTAddTaxNo);
        $('#ohdShipAddName').val(tFTAddName);
        $('#ohdShipAddrTel').val(tFTAddTel);
        $('#ohdShipAddrFax').val(tFTAddFax);

        $('#ohdShipAddV2Desc1').val(tFTAddV2Desc1);
        $('#ohdShipAddV2Desc2').val(tFTAddV2Desc2);
    }

    //เลือกที่อยู่
    $('#obtShipBrowseAddr').click(function(){ 
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();

            //ปิด modal ที่อยู่
            $('#odvDLVModalAddress').modal('hide');

            setTimeout(function(){
                oShipBrowseAddrOption         = oAddrOption({
                        'nStaShwAddress'    : <?=$nStaShwAddress?>,
                        'tReturnInputCode'  : 'ohdShipAddrCode',
                        'tReturnInputName'  : 'ohdShipAddrName',
                        'tNextFuncName'     : 'JSxShipSetConditionAfterSelectAddr',
                        'aArgReturn'        : [ 'FNAddSeqNo'    ,'FTAddV1No'    ,'FTAddV1Soi' ,
                                                'FTAddV1Village','FTAddV1Road'  ,'FTSudName' ,
                                                'FTDstName'     ,'FTPvnName'    ,'FTAddV1PostCode' ,
                                                'FTAddTel'      ,'FTAddFax'     ,'FTAddTaxNo' ,
                                                'FTAddV2Desc1'  ,'FTAddV2Desc2' , 'FTAddName'
                                            ]
                });
                JCNxBrowseData('oShipBrowseAddrOption');
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
            Title   : ['document/purchaseinvoice/purchaseinvoice','tPILabelFrmSplInfoTaxAddress'],
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
                Condition : [" AND FTAddGrpType = 1 AND FTAddRefCode = '"+$("#oetDLVToBchCode").val()+"' AND TCNMAddress_L.FNLngID = "+nLangEdits+ " AND TCNMAddress_L.FTAddVersion = '"+nStaShwAddress+"' "]
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
            }
        }
        return oOptionReturn;
    };

    //หลังจากเลือกที่อยู่
    function JSxShipSetConditionAfterSelectAddr(poDataNextFunc){
        var aData;
        if (poDataNextFunc != "NULL") {
            var aData = JSON.parse(poDataNextFunc);
            
            console.log(aData);
            //โชว์ค่า
            $('#ohdShipAddrCode').val(aData[0]);
            $('#ohdShipAddrName').val(aData[14]);
            $('#ohdShipAddrTaxNo').val(aData[11]);
            $('#ohdShipAddrNoHouse').val(aData[1]);
            $('#ohdShipAddrVillage').val(aData[3]);
            $('#ohdShipAddrRoad').val(aData[4]);
            $('#ohdShipAddrSoi').val(aData[2]);
            $('#ohdShipAddrSubDistrict').val(aData[5]);
            $('#ohdShipAddrDistict').val(aData[6]);
            $('#ohdShipAddrProvince').val(aData[7]);
            $('#ohdShipZipCode').val(aData[8]);
            $('#ohdShipAddrTel').val(aData[9]);
            $('#ohdShipAddrFax').val(aData[10]);
            $('#ohdShipAddV2Desc1').val(aData[12]);
            $('#ohdShipAddV2Desc2').val(aData[13]);
        }else{
            $('#ohdShipAddrCode').val('');
            $('#ohdShipAddrName').val('');
            $('#ohdShipAddrTaxNo').val('');
            $('#ohdShipAddrNoHouse').val('');
            $('#ohdShipAddrVillage').val('');
            $('#ohdShipAddrRoad').val('');
            $('#ohdShipAddrSoi').val('');
            $('#ohdShipAddrSubDistrict').val('');
            $('#ohdShipAddrDistict').val('');
            $('#ohdShipAddrProvince').val('');
            $('#ohdShipZipCode').val('');
            $('#ohdShipAddrTel').val('');
            $('#ohdShipAddrFax').val('');
            $('#ohdShipAddV2Desc1').val('');
            $('#ohdShipAddV2Desc2').val('');
        }

        setTimeout(function(){ $('#odvDLVModalAddress').modal('show'); }, 500);
    }

    //กดยืนยันที่อยู่
    function JSxConfirmAddress(){

        var nAddSeqNo       = $('#ohdShipAddrCode').val();
        var tAddName        = $('#ohdShipAddrName').val();
        var tTaxNo          = $('#ohdShipAddrTaxNo').val();
        var tHouseNumber    = $('#ohdShipAddrNoHouse').val();
        var tVillage        = $('#ohdShipAddrVillage').val();
        var tRoad           = $('#ohdShipAddrRoad').val();
        var tSoi            = $('#ohdShipAddrSoi').val();
        var tPostCode       = $('#ohdShipZipCode').val();
        var tSubDistrict    = $('#ohdShipAddrSubDistrict').val();
        var tDistict        = $('#ohdShipAddrDistict').val();
        var tProvince       = $('#ohdShipAddrProvince').val();
        var tDesc1          = $('#ohdShipAddV2Desc1').val();
        var tDesc2          = $('#ohdShipAddV2Desc2').val();
        var tTel            = $('#ohdShipAddrTel').val();
        var tFax            = $('#ohdShipAddrFax').val();
        var tVariableType   = 'Ship';

        $('#ohdDLV'+tVariableType+'AddV1No').val(tHouseNumber);
        $('#ohdDLV'+tVariableType+'V1Soi').val(tSoi);
        $('#ohdDLV'+tVariableType+'V1Village').val(tVillage);
        $('#ohdDLV'+tVariableType+'V1Road').val(tRoad);
        $('#ohdDLV'+tVariableType+'V1SubDistrict').val(tSubDistrict);
        $('#ohdDLV'+tVariableType+'V1District').val(tDistict);
        $('#ohdDLV'+tVariableType+'V1Province').val(tProvince);
        $('#ohdDLV'+tVariableType+'V1PostCode').val(tPostCode);
        $('#ohdDLV'+tVariableType+'AddSeqNo').val(nAddSeqNo);
        $('#ohdDLV'+tVariableType+'AddTaxNo').val(tTaxNo);
        $('#ohdDLV'+tVariableType+'AddName').val(tAddName);
        $('#ohdDLV'+tVariableType+'Tel').val(tTel);
        $('#ohdDLV'+tVariableType+'Fax').val(tFax);
        $('#ohdDLV'+tVariableType+'AddV2Desc1').val(tDesc1);
        $('#ohdDLV'+tVariableType+'AddV2Desc2').val(tDesc2);
        
    }

</script>
