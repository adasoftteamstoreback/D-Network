<script type="text/javascript">
    var nLangEdits          = '<?=$this->session->userdata("tLangEdit");?>';
    var tUsrApvName         = '<?=$this->session->userdata("tSesUsername");?>';
    var tSesUsrLevel        = '<?=$this->session->userdata('tSesUsrLevel');?>';
    var tUserBchCode        = '<?=$this->session->userdata("tSesUsrBchCode");?>';
    var tUserBchName        = '<?=$this->session->userdata("tSesUsrBchName");?>';
    var tUserWahCode        = '<?=$this->session->userdata("tSesUsrWahCode");?>';
    var tRoute              = $('#ohdPAMRoute').val();
    var tPAMSesSessionID    = $("#ohdSesSessionID").val();
    var tJumpType           = $("#oetCheckJumpType").val();
    var tJumpBackto         = $("#oetCheckBackTo").val();

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
        $('#obtPAMDocBrowsePdt').unbind().click(function(){
            JSvPAMPDTBrowseList();
        });

        $('#obtPAMDocDate').unbind().click(function(){
            $('#oetPAMDocDate').datepicker('show');
        });

        $('#obtPAMDocTime').unbind().click(function(){
            $('#oetPAMDocTime').datetimepicker('show');
        });

        //Autogen
        $('#ocbPAMStaAutoGenCode').on('change', function (e) {
            if($('#ocbPAMStaAutoGenCode').is(':checked')){
                $("#oetPAMDocNo").val('');
                $("#oetPAMDocNo").attr("readonly", true);
                $('#oetPAMDocNo').closest(".form-group").css("cursor","not-allowed");
                $('#oetPAMDocNo').css("pointer-events","none");
                $("#oetPAMDocNo").attr("onfocus", "this.blur()");
                $('#ofmPAMFormAdd').removeClass('has-error');
                $('#ofmPAMFormAdd .form-group').closest('.form-group').removeClass("has-error");
                $('#ofmPAMFormAdd em').remove();
            }else{
                $('#oetPAMDocNo').closest(".form-group").css("cursor","");
                $('#oetPAMDocNo').css("pointer-events","");
                $('#oetPAMDocNo').attr('readonly',false);
                $("#oetPAMDocNo").removeAttr("onfocus");
            }
        });

        //control ปุ่ม [อนุมัติแล้ว หรือยกเลิก]
        if('<?=$tPAMStaApv;?>' == 1 || '<?=$tPAMStaDoc?>' == 3){
            // ปุ่มอนุมัติ
            $('#obtPAMApproveDoc').hide();

            // ปุ่มยกเลิก
            $('#obtPAMCancelDoc').hide();

            // อินพุต
            $(".form-control").attr("disabled", true);

            // ปุ่มเลือก
            $('.xCNBtnBrowseAddOn').addClass('disabled');
            $('.xCNBtnBrowseAddOn').attr('disabled', true);

            // ปุ่มเวลา
            $('.xCNBtnDateTime').addClass('disabled');
            $('.xCNBtnDateTime').attr('disabled', true);

            // หมายเหตุ
            $('#otaPAMFrmInfoOthRmk').attr("disabled", false);

            // ช่องค้นหา
            $('#oetSearchPdtHTML').attr("disabled", false);

            // เพิ่มข้อมูลสินค้า
            $('.xCNHideWhenCancelOrApprove').hide();
        }

    });

    // [ปุ่ม] บันทึกข้อมูล
    $('#obtPAMSubmitFromDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            var tCheckIteminTable = $('#otbPAMDocPdtAdvTableList .xWPdtItem').length;
            var tChkFlag = 0;
            $(".xWPdtItem").each(function () { 
                 var tSeqno = $(this).data('seqno');
                 var tQty = $("#ohdQty"+tSeqno).val();
                 var tQtyOld = $(this).data('qtyold');
                 if(tQty == 0){
                    tChkFlag = 1;
                    return ;
                 }
            });
            if(tChkFlag == 1){
                $("#odvPAMModalQtyOld").modal('show');
            }else{
                if (tCheckIteminTable > 0) {
                    $('#obtPAMSubmitDocument').click();
                } else {
                    FSvCMNSetMsgWarningDialog($('#ohdPAMValidatePdt').val());
                }
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ยืนยันเอกสารกรณีที่เป็น 0
    function JSxPAMSubmitChangeQTY() { 
        var nStaSession = 1;
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            try {
                
                $("#odvPAMModalQtyOld").modal('hide');
                $(".xWPdtItem").each(function () { 
                    var tSeqno                  = $(this).data('seqno');
                    var tQty                    = $("#ohdQty"+tSeqno).val();
                    var tQtyOld                 = $(this).data('qtyold');
                    var nSeq                    = $(this).attr('data-seq');
                    var nQty                    = parseFloat($('#ohdQty'+nSeq).val());
                    var cFactor                 = $(this).attr('data-factor');
                    var cQtyOrd                 = parseFloat($(this).attr('data-qtyord'));
                    if(tQty == 0){
                        // $("#ohdQty"+tSeqno).val(tQtyOld);
                        JSxPAMPdtEditInlineSubmit('Qty',tSeqno,tQtyOld,cFactor);
                    }
            });
                $('#obtPAMSubmitDocument').click();
            } catch (err) {
                console.log("JSxPAMApproveDocument Error: ", err);
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //เเก้ไขจำนวน และ ราคา
    function JSxPAMPdtEditInlineSubmit(ptType,pnSeq,ptValue,pcFactor){
    if(pnSeq != undefined){
        $.ajax({
            type    : "POST",
            url     : "docPAMEditPdtInDTDocTemp",
            data    : {
                'tPAMBchCode'   : $("#ohdPAMBchCode").val(),
                'tPAMDocNo'     : $("#oetPAMDocNo").val(),
                'tPAMType'      : ptType,
                'nPAMSeqNo'     : pnSeq,
                'tPAMValue'     : ptValue,
                'cPAMFactor'    : pcFactor
            },
            catch   : false,
            timeout : 0,
            success : function (oResult){
            },
            error   : function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
    }

    // [ปุ่ม] ยกเลิกเอกสาร
    $('#obtPAMCancelDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSnPAMCancelDocument(false);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // [ปุ่ม] ย้อนกลับ
    $('#obtPAMCallBackPage').unbind().click(function() {
        if($('#oetCheckJumpStatus').val() != '1'){
            JSvPAMCallPageList();
        }else{
            if(tJumpType == 'SO'){
                        $.ajax({
                            type    : "GET",
                            url     : "dcmSO/0/0",
                            data    : {'ptTypeJump' : '1',
                                       'tDocNo' : tJumpBackto },
                            cache   : false,
                            timeout : 5000,
                            success : function (tResult) {
                                $(window).scrollTop(0);
                                $('.odvMainContent').html(tResult);
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                JCNxResponseError(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }else{
                $.ajax({
                    type    : "GET",
                    url     : "dcmSOData/0/0",
                    cache   : false,
                    timeout : 5000,
                    success : function (tResult) {
                        $(window).scrollTop(0);
                        $('.odvMainContent').html(tResult);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        }
        
    });

    // [ปุ่ม] อนุมัติ
    $('#obtPAMApproveDoc').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        var tCheckIteminTable = $('#otbPAMDocPdtAdvTableList .xWPdtItem').length;
        var tChkFlag = 0;
        $(".xWPdtItem").each(function () { 
                var tSeqno = $(this).data('seqno');
                var tQty = $("#ohdQty"+tSeqno).val();
                var tQtyOld = $(this).data('qtyold');
                if(tQty == 0){
                tChkFlag = 1;
                return ;
                }
        });
        if(tChkFlag == 1){
            $("#odvPAMModalQtyOld2").modal('show');
        }else{
            if (tCheckIteminTable > 0) {
                JSxPAMSubmitEventByButton('approve');
            } else {
                FSvCMNSetMsgWarningDialog($('#ohdPAMValidatePdt').val());
            }
        }
    });

    // ยืนยันเอกสารกรณีที่เป็น 0
    function JSxPAMSubmitChangeQTY2() { 
        var nStaSession = 1;
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            try {
                
                $("#odvPAMModalQtyOld2").modal('hide');
                $(".xWPdtItem").each(function () { 
                    var tSeqno                  = $(this).data('seqno');
                    var tQty                    = $("#ohdQty"+tSeqno).val();
                    var tQtyOld                 = $(this).data('qtyold');
                    var nSeq                    = $(this).attr('data-seq');
                    var nQty                    = parseFloat($('#ohdQty'+nSeq).val());
                    var cFactor                 = $(this).attr('data-factor');
                    var cQtyOrd                 = parseFloat($(this).attr('data-qtyord'));
                    if(tQty == 0){
                        // $("#ohdQty"+tSeqno).val(tQtyOld);
                        JSxPAMPdtEditInlineSubmit('Qty',tSeqno,tQtyOld,cFactor);
                    }
            });
                JSxPAMSubmitEventByButton('approve');
            } catch (err) {
                console.log("JSxPAMApproveDocument Error: ", err);
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //ค้นหาสินค้าใน temp
    function JSvPAMCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbPAMDocPdtAdvTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    //กดเลือกบาร์โค๊ด
    function JSxSearchFromBarcode(e, elem) {
        var tValue = $(elem).val();
        $('#oetPAMInsertBarcode').attr('readonly', true);
        JCNSearchBarcodePdt(tValue);
        $('#oetPAMInsertBarcode').val('');
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
                BCH                 : $("#oetPAMBchCode").val(),
                tInpSesSessionID    : $('#ohdSesSessionID').val(),
                tInpUsrCode         : $('#ohdPAMUsrCode').val(),
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
                    $('#oetPAMInsertBarcode').attr('readonly', false);
                    $('#odvPAMModalPDTNotFound').modal('show');
                    $('#oetPAMInsertBarcode').val('');
                } else {
                    if (oText.length > 1) {

                        // พบสินค้ามีหลายบาร์โค้ด
                        $('#odvPAMModalPDTMoreOne').modal('show');
                        $('#odvPAMModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');
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
                            $('#odvPAMModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick', function(e) {
                            $('#odvPAMModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            FSvPAMAddPdtIntoDocDTTemp(tJSON); //Client
                            JSxPAMEventInsertToTemp(tJSON); //Serve
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
                        FSvPAMAddPdtIntoDocDTTemp(aNewReturn); //Client
                        JSxPAMEventInsertToTemp(aNewReturn); //Server
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR,textStatus,errorThrown);
            }
        });
    }

    //เลือกสินค้า กรณีพบมากกว่าหนึ่งตัว
    function JCNxConfirmPDTMoreOne($ptType) {
        if ($ptType == 1) {
            $("#odvPAMModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function(index) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                FSvPAMAddPdtIntoDocDTTemp(tJSON);
                JSxPAMEventInsertToTemp(tJSON);
            });
        } else {
            $('#oetPAMInsertBarcode').attr('readonly', false);
            $('#oetPAMInsertBarcode').val('');
        }
    }

    // Event Browse Category 1
    $('#obtPAMBrowseCat1').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPAMBrowseCategoryOption  = oPAMBrowseCategory({
                'tReturnInputCode'  : 'oetPAMCat1Code',
                'tReturnInputName'  : 'oetPAMCat1Name',
                'nCatLevel'         :  1
            });
            JCNxBrowseData('oPAMBrowseCategoryOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Category 2
    $('#obtPAMBrowseCat2').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPAMBrowseCategoryOption  = oPAMBrowseCategory({
                'tReturnInputCode'  : 'oetPAMCat2Code',
                'tReturnInputName'  : 'oetPAMCat2Name',
                'nCatLevel'         :  2
            });
            JCNxBrowseData('oPAMBrowseCategoryOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oPAMBrowseCategory = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var nCatLevel           = poReturnInput.nCatLevel;
        var tWhere              = " AND TCNMPdtCatInfo.FTCatStaUse = '1' ";

        if( nCatLevel != "" ){
            tWhere += " AND TCNMPdtCatInfo.FNCatLevel = "+nCatLevel+" ";
            var tCat1Code = $("#oetPAMCat1Code").val();
            if (nCatLevel=='2' && tCat1Code !='') {
                tWhere += " AND TCNMPdtCatInfo.FTCatParent = '"+tCat1Code+"' ";
            }
        }

        var oOptionReturn       = {
            Title : ['product/pdtcat/pdtcat','tCATTitle'],
            Table : {Master:'TCNMPdtCatInfo',PK:'FTCatCode'},
            Join :{
                Table : ['TCNMPdtCatInfo_L'],
                On : ['TCNMPdtCatInfo_L.FTCatCode = TCNMPdtCatInfo.FTCatCode AND TCNMPdtCatInfo_L.FNCatLevel = TCNMPdtCatInfo.FNCatLevel AND TCNMPdtCatInfo_L.FNLngID = '+nLangEdits,]
            },
            Where   : {
                Condition : [tWhere]
            },
            GrideView:{
                ColumnPathLang      : 'product/pdtcat/pdtcat',
                ColumnKeyLang       : ['tCATTBCode','tCATTBName'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMPdtCatInfo.FTCatCode','TCNMPdtCatInfo_L.FTCatName'],
                DataColumnsFormat   : ['',''],
                Perpage             : 20,
                OrderBy             : ['TCNMPdtCatInfo.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMPdtCatInfo.FTCatCode"],
                Text		: [tInputReturnName,"TCNMPdtCatInfo_L.FTCatName"],
            },
        }
        return oOptionReturn;
    };

    // เลือกสาขา
    $('#obtPAMBrowseBch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPAMBrowseBranchFromOption  = oPAMBrowseBranch({
                'tReturnInputCode'  : 'oetPAMBchCode',
                'tReturnInputName'  : 'oetPAMBchName'
            });
            JCNxBrowseData('oPAMBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oPAMBrowseBranch = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tUsrLevel 	  	    = "<?=$this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti 	    = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var nCountBch 		    = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
        var nLangEdits          = "<?=$this->session->userdata("tLangEdit")?>";
        var tWhere 			    = "";

        if(nCountBch == 1){
            $('#obtPAMBrowseBch').attr('disabled',true);
        }

        if(tUsrLevel != "HQ"){
            tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
        }else{
            tWhere = "";
        }

        var oOptionReturn       = {
            Title : ['company/branch/branch','tBCHTitle'],
            Table : {Master:'TCNMBranch',PK:'FTBchCode'},
            Join :{
                Table : ['TCNMBranch_L'],
                On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
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

    // เลือกที่เก็บ
    $('#obtPAMBrowsePlc').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPAMBrowseLocationOption  = oPAMBrowseLocation({
                'tReturnInputCode'  : 'oetPAMPlcCode',
                'tReturnInputName'  : 'oetPAMPlcName'
            });
            JCNxBrowseData('oPAMBrowseLocationOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oPAMBrowseLocation = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tUsrLevel 	  	    = "<?=$this->session->userdata("tSesUsrLevel"); ?>";
        var tBchCodeMulti 	    = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        var nCountBch 		    = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
        var nLangEdits          = "<?=$this->session->userdata("tLangEdit")?>";
        var tAgnCode            = '<?=$this->session->userdata("tSesUsrAgnCode"); ?>';
        var tWhere 			    = "";

        if(nCountBch == 1){
            $('#obtPAMBrowseBch').attr('disabled',true);
        }

        if(tUsrLevel != "HQ"){
            tWhere = " AND TCNMPdtLoc.FTAgnCode = '"+tAgnCode+"' ";
        }else{
            tWhere = "";
        }
        var oOptionReturn       = {
            Title : ['product/pdtlocation/pdtlocation','tLOCTitle'],
            Table : {Master:'TCNMPdtLoc',PK:'FTPlcCode'},
            Join :{
                Table : ['TCNMPdtLoc_L'],
                On : ['TCNMPdtLoc_L.FTPlcCode = TCNMPdtLoc.FTPlcCode AND TCNMPdtLoc_L.FNLngID = '+nLangEdits,]
            },
            Where   : {
                Condition : [tWhere]
            },
            GrideView:{
                ColumnPathLang      : 'product/pdtlocation/pdtlocation',
                ColumnKeyLang       : ['tLOCFrmLocCode','tLOCFrmLocName'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMPdtLoc.FTPlcCode','TCNMPdtLoc_L.FTPlcName'],
                DataColumnsFormat   : ['',''],
                Perpage             : 20,
                OrderBy             : ['TCNMPdtLoc.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMPdtLoc.FTPlcCode"],
                Text		: [tInputReturnName,"TCNMPdtLoc_L.FTPlcName"],
            },
        }
        return oOptionReturn;
    };

    //เลือกสินค้า
    function JSvPAMPDTBrowseList() {

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
                NextFunc        : "FSvPAMNextFuncB4SelPDT",
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
    function JSxPAMEventInsertToTemp(ptPdtData){
        var ptPAMDocNo = "";
        if ($("#ohdPAMRoute").val() == "docPAMEventEdit") {
            ptPAMDocNo = $("#oetPAMDocNo").val();
        }

        var tPAMOptionAddPdt    = $('#ocmPAMFrmInfoOthReAddPdt').val();
        var nKey                = parseInt($('#otbPAMDocPdtAdvTableList tr:last').attr('data-seqno'));

        $.ajax({
            type    : "POST",
            url     : "docPAMAddPdtIntoDTDocTemp",
            data    : {
                'tSelectBCH'        : $('#oetPAMBchCode').val(),
                'tPAMDocNo'         : ptPAMDocNo,
                'tPAMOptionAddPdt'  : tPAMOptionAddPdt,
                'tPAMPdtData'       : ptPdtData,
                'tSeqNo'            : nKey
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aResult = JSON.parse(oResult);
                if (aResult['nStaEvent'] == 1) {
                    JCNxCloseLoading();
                    $('#oetPAMInsertBarcode').attr('readonly', false);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //---------------- เอกสารอ้างอิง ----------------//

    // [เอกสารอ้างอิง] โหลด Table อ้างอิงเอกสารทั้งหมด
    function JSxPAMCallPageHDDocRef(){
        $.ajax({
            type    : "POST",
            url     : "docPAMPageHDDocRefList",
            data:{
                'ptDocNo'       : $('#oetPAMDocNo').val()
            },
            cache   : false,
            timeout : 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                $('#ohdDocSoRef').val(aResult['tSODocRef']);
                var nStaApv = $('#ohdPAMStaApv').val();
                if (aResult['aDataStaBooking']['nStaEvent'] == 1 && nStaApv == 1) {
                    $("#obtPAMBooking").show();
                } else {
                    $("#obtPAMBooking").hide();
                }
                if( aResult['nStaEvent'] == 1 ){
                    var tCheckIteminTable = $('#otbPAMDocRefTable .xWDocRefItem').length; 
                    if (tCheckIteminTable > 0) 
                    { 
                        $('#obtPAMAddDocRef').hide(); 
                    } else { 
                        $('#obtPAMAddDocRef').show(); ; 
                    }
                    $('#odvPAMTableHDRef').html(aResult['tViewPageHDRef']);
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
    $('#obtPAMAddDocRef').off('click').on('click',function(){
        JSxPAMEventClearValueInFormHDDocRef();
        $('#odvPAMModalAddDocRef').modal('show');
        JSxPAMEventCheckShowHDDocRef();
    });

    // [เอกสารอ้างอิง] เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    $('#ocbPAMRefType').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        JSxPAMEventCheckShowHDDocRef();
    });

    // [เอกสารอ้างอิง] กดเลือกอ้างอิงเอกสารภายใน (ใบจ่ายโอน-สาขา หรือ ใบสั่งขาย)
    $('#obtPAMBrowseRefDoc').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCallPAMRefIntDoc();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // [เอกสารอ้างอิง] Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    function JSxPAMEventCheckShowHDDocRef(){
        var tPAMRefType = $('#ocbPAMRefType').val();
        if( tPAMRefType == '1' ){
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();

            var nTypeDoc = $('#ocmPAMPackType').val();
            $('#ocbPAMRefDoc option[value=1]').show();
            $('#ocbPAMRefDoc option[value=2]').show();
            if(nTypeDoc == 11){
                $('#ocbPAMRefDoc option[value=1]').attr('selected','selected');
                $('#ocbPAMRefDoc option[value=2]').hide();
            }else{
                $('#ocbPAMRefDoc option[value=1]').hide();
                $('#ocbPAMRefDoc option[value=2]').attr('selected','selected');
            }
            $('.selectpicker').selectpicker('refresh');
        }else{
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }

    // [เอกสารอ้างอิง] เคลียร์ค่า
    function JSxPAMEventClearValueInFormHDDocRef(){
        $('#oetPAMRefDocNo').val('');
        $('#oetPAMRefDocDate').val('');
        $('#oetPAMDocRefInt').val('');
        $('#oetPAMDocRefIntName').val('');
        $('#oetPAMRefKey').val('');
    }

    // [เอกสารอ้างอิง] Browse เอกสารอ้างอิงภายใน (ใบจ่ายโอน-สาขา หรือ ใบสั่งขาย)
    function JSxCallPAMRefIntDoc(){
        var tBCHCode    = $('#oetPAMBchCode').val();
        var tBCHName    = $('#oetPAMBchName').val();
        var tRefDoc     = $('#ocbPAMRefDoc').val();
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docPAMCallRefIntDoc",
            data    : {
                'tBCHCode'      : tBCHCode,
                'tBCHName'      : tBCHName,
                'tRefDoc'       : tRefDoc
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                JCNxCloseLoading();
                $('#odvPAMFromRefIntDoc').html(oResult);
                $('#odvPAMModalRefIntDoc').modal({backdrop : 'static' , show : true});

                if (tRefDoc == 1) {
                    var tTextRefPanel = "อ้างอิงเอกสารใบจ่ายโอน - สาขา";
                }else{
                    var tTextRefPanel = "อ้างอิงเอกสารใบสั่งขาย";
                }
                $('.olbPAMModalRefIntDoc').text(tTextRefPanel);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // [เอกสารอ้างอิง] กดยืนยัน (ระดับสินค้าลง Temp)
    $('#obtConfirmRefDocInt').click(function(){

        var tRefIntDocNo    =  $('.xPAMRefInt.active').data('docno');
        var tRefIntDocDate  =  $('.xPAMRefInt.active').data('docdate');
        var tRefIntBchCode  =  $('.xPAMRefInt.active').data('bchcode');
        var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm){
            return $(this).val();
        }).get();

        $('#oetPAMRefDocDate').val(tRefIntDocDate);
        $('#oetPAMDocRefIntName').val(tRefIntDocNo);

        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "docPAMCallRefIntDocInsertDTToTemp",
            data    : {
                'tPAMDocNo'          : $('#oetPAMDocNo').val(),
                'tPAMFrmBchCode'     : $('#oetPAMBchCode').val(),
                'tRefIntDocNo'       : tRefIntDocNo,
                'tRefIntBchCode'     : tRefIntBchCode,
                'aSeqNo'             : aSeqNo,
                'tRefDoc'            : $('#ocbPAMRefDoc').val(),
                'tInsertOrUpdateRow' : $('#ocmPAMFrmInfoOthReAddPdt').val()
            },
            cache   : false,
            Timeout : 0,
            success : function (oResult){
                console.log(oResult);
                JSvPAMLoadPdtDataTableHtml();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    });

    // [เอกสารอ้างอิง] กดยืนยัน (ระดับเอกสารลง TCNTDocHDRefTmp)
    $('#ofmPAMFormAddDocRef').off('click').on('click',function(){
        $('#ofmPAMFormAddDocRef').validate().destroy();
        $('#ofmPAMFormAddDocRef').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetPAMRefDocNo    : {"required" : true}
            },
            messages: {
                oetPAMRefDocNo    : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
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

                if($('#ocbPAMRefType').val() == 1){         
                    //อ้างอิงเอกสารภายใน
                    if($('#ocbPAMRefDoc').val() == 1){
                        var tDocNoRef   = $('#oetPAMDocRefIntName').val();
                        var tDocRefKey  = 'TBO';
                    }else{
                        var tDocNoRef   = $('#oetPAMDocRefIntName').val();
                        var tDocRefKey  = 'SO';
                    }
                }else{                                     
                     //อ้างอิงเอกสารภายนอก
                    var tDocNoRef       = $('#oetPAMRefDocNo').val();
                    var tDocRefKey      = $('#oetPAMRefKey').val();
                }


                $.ajax({
                    type    : "POST",
                    url     : "docPAMEventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld'     : $('#oetPAMRefDocNoOld').val(),
                        'ptDocNo'           : $('#oetPAMDocNo').val(),
                        'ptRefType'         : $('#ocbPAMRefType').val(),
                        'ptRefDocNo'        : tDocNoRef,
                        'pdRefDocDate'      : $('#oetPAMRefDocDate').val(),
                        'ptRefKey'          : tDocRefKey
                    },
                    cache   : false,
                    timeout : 0,
                    success : function(oResult){
                        JSxPAMEventClearValueInFormHDDocRef();
                        $('#odvPAMModalAddDocRef').modal('hide');

                        JSxPAMCallPageHDDocRef();
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
    function JSxPAMPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tPAMBchCode); ?>'},
            {"DocCode"      : '<?=@$tPAMDocNo; ?>'},
            {"DocBchCode"   : '<?=@$tPAMBchCode;?>'}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_SQL_ALLMPdtBillPick?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

    // ยกเลิกเอกสาร
    function JSnPAMCancelDocument(pbIsConfirm) {
        var tPAMDocNo = $("#oetPAMDocNo").val();
        if (pbIsConfirm) {
            $.ajax({
                type: "POST",
                url: "docPAMCancelDocument",
                data: {
                    'ptPAMDocNo'    : tPAMDocNo,
                    'ptPAMDocType'  : $('#ohdPAMDocType').val()
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $("#odvPAMPopupCancel").modal("hide");
                    $('.modal-backdrop').remove();
                    var aReturnData = JSON.parse(tResult);
                    if (aReturnData['nStaEvent'] == '1') {
                        JSvPAMCallPageEdit(tPAMDocNo);
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
            $('#odvPAMPopupCancel').modal({ backdrop: 'static', keyboard: false });
            $("#odvPAMPopupCancel").modal("show");
        }
    }

    // =========================================== ลบข้อมูล =========================================== //

    // [ลบทั้งหมด] รายการสินค้า
    $('#odvPAMModalDelPdtInDTTempMultiple #osmConfirmDelMultiple').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSnPAMRemovePdtDTTempMultiple();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // [ลบทั้งหมด] เลือกทั้งหมด
    $('#ocbCheckAll').click(function(){
        if($(this).is(':checked')==true){
            $('.ocbListItem').prop('checked',true);
            $("#odvPAMMngDelPdtInTableDT #oliPAMBtnDeleteMulti").removeClass("disabled");
        }else{
            $('.ocbListItem').prop('checked',false);
            $("#odvPAMMngDelPdtInTableDT #oliPAMBtnDeleteMulti").addClass("disabled");
        }
    });

    // [ลบทั้งหมด] ลบ
    function FSxPAMSelectMulDel(ptElm){
        var tPAMDocNo           = $('#oetPAMDocNo').val();
        var tPAMSeqNo           = $(ptElm).parents('.xWPdtItem').data('key');
        var tPAMPdtCode         = $(ptElm).parents('.xWPdtItem').data('pdtcode');
        var tPAMBarCode         = $(ptElm).parents('.xWPdtItem').data('barcode');
        $(ptElm).prop('checked', true);

        var oLocalItemDTTemp    = localStorage.getItem("PAM_LocalItemDataDelDtTemp");
        var oDataObj            = [];
        if(oLocalItemDTTemp){
            oDataObj    = JSON.parse(oLocalItemDTTemp);
        }
        var aArrayConvert   = [JSON.parse(localStorage.getItem("PAM_LocalItemDataDelDtTemp"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            oDataObj.push({
                'tDocNo'    : tPAMDocNo,
                'tSeqNo'    : tPAMSeqNo,
                'tPdtCode'  : tPAMPdtCode,
                'tBarCode'  : tPAMBarCode,
            });
            localStorage.setItem("PAM_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
            JSxPAMTextInModalDelPdtDtTemp();
        }else{
            var aReturnRepeat   = JStPAMFindObjectByKey(aArrayConvert[0],'tSeqNo',tPAMSeqNo);
            if(aReturnRepeat == 'None' ){
                //ยังไม่ถูกเลือก
                oDataObj.push({
                    'tDocNo'    : tPAMDocNo,
                    'tSeqNo'    : tPAMSeqNo,
                    'tPdtCode'  : tPAMPdtCode,
                    'tBarCode'  : tPAMBarCode,
                });
                localStorage.setItem("PAM_LocalItemDataDelDtTemp",JSON.stringify(oDataObj));
                JSxPAMTextInModalDelPdtDtTemp();
            }else if(aReturnRepeat == 'Dupilcate'){
                localStorage.removeItem("PAM_LocalItemDataDelDtTemp");
                $(ptElm).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeqNo == tPAMSeqNo){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata   = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("PAM_LocalItemDataDelDtTemp",JSON.stringify(aNewarraydata));
                JSxPAMTextInModalDelPdtDtTemp();
            }
        }
        JSxPAMShowButtonDelMutiDtTemp();
    }

    // [ลบทั้งหมด] Pase Text Product Item In Modal Delete
    function JSxPAMTextInModalDelPdtDtTemp(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("PAM_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
        }else{
            var tPAMTextDocNo   = "";
            var tPAMTextSeqNo   = "";
            var tPAMTextPdtCode = "";
            $.each(aArrayConvert[0],function(nKey,aValue){
                tPAMTextDocNo    += aValue.tDocNo;
                tPAMTextDocNo    += " , ";

                tPAMTextSeqNo    += aValue.tSeqNo;
                tPAMTextSeqNo    += " , ";

                tPAMTextPdtCode  += aValue.tPdtCode;
                tPAMTextPdtCode  += " , ";
            });
            $('#odvPAMModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').text($('#oetTextComfirmDeleteMulti').val());
            $('#odvPAMModalDelPdtInDTTempMultiple #ohdConfirmPAMDocNoDelete').val(tPAMTextDocNo);
            $('#odvPAMModalDelPdtInDTTempMultiple #ohdConfirmPAMSeqNoDelete').val(tPAMTextSeqNo);
            $('#odvPAMModalDelPdtInDTTempMultiple #ohdConfirmPAMPdtCodeDelete').val(tPAMTextPdtCode);
        }
    }

    // [ลบทั้งหมด] ความคุมปุ่มตัวเลือก -> ลบทั้งหมด
    function JSxPAMShowButtonDelMutiDtTemp(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("PAM_LocalItemDataDelDtTemp"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == ""){
            $("#odvPAMMngDelPdtInTableDT #oliPAMBtnDeleteMulti").addClass("disabled");
        }else{
            var nNumOfArr   = aArrayConvert[0].length;
            if(nNumOfArr > 1) {
                $("#odvPAMMngDelPdtInTableDT #oliPAMBtnDeleteMulti").removeClass("disabled");
            }else{
                $("#odvPAMMngDelPdtInTableDT #oliPAMBtnDeleteMulti").addClass("disabled");
            }
        }
    }

    // [ลบทั้งหมด] Chack Value LocalStorage
    function JStPAMFindObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    // [ลบทั้งหมด] Fucntion Call Delete Multiple Doc DT Temp
    function JSnPAMRemovePdtDTTempMultiple(){
        var tPAMDocNo        = $("#oetPAMDocNo").val();
        var tPAMBchCode      = $('#oetPAMBchCode').val();
        var aDataPdtCode    = JSoPAMRemoveCommaData($('#odvPAMModalDelPdtInDTTempMultiple #ohdConfirmPAMPdtCodeDelete').val().trim());
        var aDataSeqNo      = JSoPAMRemoveCommaData($('#odvPAMModalDelPdtInDTTempMultiple #ohdConfirmPAMSeqNoDelete').val().trim());

        for(var i=0;i<aDataSeqNo.length;i++){
            $('.xWPdtItemList'+aDataSeqNo[i]).remove();
        }

        $('#odvPAMModalDelPdtInDTTempMultiple').modal('hide');
        $('#odvPAMModalDelPdtInDTTempMultiple #ospTextConfirmDelMultiple').empty();
        localStorage.removeItem('PAM_LocalItemDataDelDtTemp');
        $('#odvPAMModalDelPdtInDTTempMultiple #ohdConfirmPAMDocNoDelete').val('');
        $('#odvPAMModalDelPdtInDTTempMultiple #ohdConfirmPAMSeqNoDelete').val('');
        $('#odvPAMModalDelPdtInDTTempMultiple #ohdConfirmPAMPdtCodeDelete').val('');
        $('#odvPAMModalDelPdtInDTTempMultiple #ohdConfirmPAMBarCodeDelete').val('');
        JCNxCloseLoading();

        $.ajax({
            type    : "POST",
            url     : "docPAMRemovePdtInDTTmp",
            data    : {
                'tBchCode'      : tPAMBchCode,
                'tDocNo'        : tPAMDocNo,
                'nSeqNo'        : aDataSeqNo,
                'tPdtCode'      : aDataPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var tCheckIteminTable = $('#otbPAMDocPdtAdvTableList tbody tr').length;
                if(tCheckIteminTable==0){
                    $('#otbPAMDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">ไม่พบข้อมูล</td></tr>');
                }
                JSxPAMCountPdtItems();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResPAMnseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // [ลบทั้งหมด] Remove Comma
    function JSoPAMRemoveCommaData(paData){
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
    function JSnPAMDelPdtInDTTempSingle(elem) {
        var tPdtCode = $(elem).parents("tr.xWPdtItem").attr("data-pdtcode");
        var tSeqno   = $(elem).parents("tr.xWPdtItem").attr("data-key");
        $(elem).parents("tr.xWPdtItem").remove();
        JSnPAMRemovePdtDTTempSingle(tSeqno, tPdtCode);
    }

    // [ลบข้อมูล] ลบรายการสินค้าในตาราง DT Temp
    function JSnPAMRemovePdtDTTempSingle(ptSeqNo,ptPdtCode){
        var tPAMDocNo        = $("#oetPAMDocNo").val();
        var tPAMBchCode      = $('#oetPAMBchCode').val();
        JCNxCloseLoading();

        $.ajax({
            type    : "POST",
            url     : "docPAMRemovePdtInDTTmp",
            data    : {
                'tBchCode'      : tPAMBchCode,
                'tDocNo'        : tPAMDocNo,
                'nSeqNo'        : ptSeqNo,
                'tPdtCode'      : ptPdtCode
            },
            cache: false,
            timeout: 0,
            success: function (tResult) {
                var aReturnData = JSON.parse(tResult);
                if(aReturnData['nStaEvent'] == '1'){
                    JCNxLayoutControll();
                    JSxPAMCountPdtItems();
                    var tCheckIteminTable = $('#otbPAMDocPdtAdvTableList tbody tr').length;
                    if(tCheckIteminTable == 0){
                        $('#otbPAMDocPdtAdvTableList').append('<tr style="background-color: rgb(255, 255, 255);"><td class="text-center xCNTextDetail2 xCNTextNotfoundDataPdtTable" colspan="100%">กรุณาเลือกเอกสารอ้างอิงเพื่อทำการจัดสินค้า</td></tr>');
                    }
                }else{
                    var tMessageError   = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResPAMnseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Jump ไปจองช่องฝาก
    function JSvSODJumptoCreateBook(ptSODocNo, ptSOCstCode ,ptType){
        JCNxOpenLoading();
        var nStaSession = JCNxFuncChkSessionExpired();
        var tSODocNo = $('#ohdDocSoRef').val();
        var tBchCode = $('#oetPAMBchCode').val();
        var tType = 'PL';
        if(typeof nStaSession !== "undefined" && nStaSession == 1) {
            JStCMMGetPanalLangSystemHTML("JSvDBDCallPageEditDoc",tSODocNo);
            $.ajax({
                type    : "POST",
                url     : "docDBR/0/0",
                data    : {'ptTypeJump' : '1',
                            'tDocNo' : tSODocNo,
                            'tBchCode' : tBchCode,
                            'tType' : tType,
                            'tBackTo' : $('#oetPAMDocNo').val()
                        },
                cache   : false,
                timeout : 5000,
                success : function (tResult) {
                    $(window).scrollTop(0);
                    $('.odvMainContent').html(tResult);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

</script>
