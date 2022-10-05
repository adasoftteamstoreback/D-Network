<script type="text/javascript">
    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit"); ?>;

    $(document).ready(function(){
        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        $("#odvSearchType2").hide();
        $("#obtCRVAdvanceSearch").hide();
        $("#obtCRVSearchReset").hide();

        // Doc Date From
        $('#obtCRVAdvSearchDocDateForm').unbind().click(function(){
            $('#oetCRVAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtCRVAdvSearchDocDateTo').unbind().click(function(){
            $('#oetCRVAdvSearcDocDateTo').datepicker('show');
        });
        JSxCRVControllBrowse();
    });

    $("#ocmCRVSearchTypeChg").change(function () { 
        if($(this).val() == '0'){
            JSxCRVClearAdvSearchData();
            $("#odvSearchType2").hide();
            $("#odvSearchType1").show();
            $("#obtCRVAdvanceSearch").hide();
            $("#obtCRVSearchReset").hide();
            $("#odvCRVAdvanceSearchContainer").hide();
            $("#ohdCRVInCendition").val('');
            $("#odvCRVAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }else{
            JSxCRVClearAdvSearchData();
            $("#odvSearchType1").hide();
            $("#odvSearchType2").show();
            $("#obtCRVAdvanceSearch").hide();
            $("#obtCRVSearchReset").show();
            $("#odvCRVAdvanceSearchContainer").show();
            $("#ohdCRVInCendition").val('');
            $('#odvCRVAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }
    });

    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

    if(nCountBch == 1){
		$('#obtCRVAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtCRVAdvSearchBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}


    // Advance search Display control
    $('#obtCRVAdvanceSearch').unbind().click(function(){
        if($('#odvCRVAdvanceSearchContainer').hasClass('hidden')){
            $('#odvCRVAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvCRVAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    // Option Branch
    var oCRVBrowseBranch = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
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
                OrderBy             : ['TCNMBranch_L.FTBchName ASC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"],
            },
            NextFunc: {
                FuncName: 'JSxCRVControllBrowse',
            },
        }
        return oOptionReturn;
    };

    // Event Browse Branch From
    $('#obtCRVAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oCRVBrowseBranchFromOption  = oCRVBrowseBranch({
                'tReturnInputCode'  : 'oetCRVAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetCRVAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oCRVBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    // $('#obtCRVAdvSearchBrowseBchTo').unbind().click(function(){
    //     var nStaSession = JCNxFuncChkSessionExpired();
    //     if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
    //         JSxCheckPinMenuClose();
    //         window.oCRVBrowseBranchToOption  = oCRVBrowseBranch({
    //             'tReturnInputCode'  : 'oetCRVAdvSearchBchCodeTo',
    //             'tReturnInputName'  : 'oetCRVAdvSearchBchNameTo'
    //         });
    //         JCNxBrowseData('oCRVBrowseBranchToOption');
    //     }else{
    //         JCNxShowMsgSessionExpired();
    //     }
    // });
    
    $('#obtCRVSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCRVClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxCRVClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmCRVFromSerchAdv').find('input').val('');
            $("#oetCRVSearchAllDocument").val('');
            $('#ofmCRVFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvCRVCallPageDataTable();
            JSxCRVControllBrowse();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page  ====================================================
        $('#oetCRVSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    $.ajax({
                    type: "POST",
                    url: "dcmSODataCheckCRV",
                    data: {
                        tDocno  : $("#oetCRVSearchAllDocument").val(),
                    },
                    cache: false,
                    timeout: 0,
                    success: function (oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1'){  
                            var aGetallitems = aReturnData['aItems']['aItems'];
                                var tStrInCondition = '';
                                for(i=0;i<aGetallitems.length;i++){
                                    tStrInCondition += "'";
                                    tStrInCondition += aReturnData['aItems']['aItems'][i]['FTXshRefDocNo'];
                                    tStrInCondition += "',";
                                }
                                tStrInCondition = tStrInCondition.slice(0,-1);
                                $("#ohdCRVInCendition").val(tStrInCondition);
                        }
                        JSvCRVCallPageDataTable();
                        $("#ohdCRVInCendition").val('');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });
        
        $('#obtCRVSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                $.ajax({
                    type: "POST",
                    url: "dcmSODataCheckCRV",
                    data: {
                        tDocno  : $("#oetCRVSearchAllDocument").val(),
                    },
                    cache: false,
                    timeout: 0,
                    success: function (oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1'){  
                            var aGetallitems = aReturnData['aItems']['aItems'];
                                var tStrInCondition = '';
                                for(i=0;i<aGetallitems.length;i++){
                                    tStrInCondition += "'";
                                    tStrInCondition += aReturnData['aItems']['aItems'][i]['FTXshRefDocNo'];
                                    tStrInCondition += "',";
                                }
                                tStrInCondition = tStrInCondition.slice(0,-1);
                                $("#ohdCRVInCendition").val(tStrInCondition);
                        }
                        JSvCRVCallPageDataTable();
                        $("#ohdCRVInCendition").val('');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtCRVAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvCRVCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    // =================================================================================================================================================

        // กดสแกนเพื่อรับของ
        $('#obtCRVSerchForCRV').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== "undefined" && nStaSession == 1) {
                $.ajax({
                    type: "POST",
                    url: "dcmSODataCheckCRV",
                    data: {
                        tDocno  : $("#oetCRVSearchSoSCAN").val(),
                    },
                    cache: false,
                    timeout: 0,
                    success: function (oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1'){  
                            var aGetallitems = aReturnData['aItems']['aItems'];
                            if(aGetallitems.length > 1){
                                var tStrInCondition = '';
                                for(i=0;i<aGetallitems.length;i++){
                                    tStrInCondition += "'";
                                    tStrInCondition += aReturnData['aItems']['aItems'][i]['FTXshRefDocNo'];
                                    tStrInCondition += "',";
                                }
                                tStrInCondition = tStrInCondition.slice(0,-1);
                                $("#ohdCRVInCendition").val(tStrInCondition);
                                JSvCRVCallPageDataTable();
                                $("#ohdCRVInCendition").val('');
                            }else{
                                var tBchCode = aReturnData['aItems']['aItems'][0]['FTBchCode'];
                                var tDocNo = aReturnData['aItems']['aItems'][0]['FTXshRefDocNo'];
                                $("#ohdCRVInCendition").val('');
                                // alert(tDocNo);
                                JSvCRVCallPageEdit(tBchCode,tDocNo)
                            }
                        }else{
                            FSvCMNSetMsgErrorDialog('ไม่พบเลขที่ใบฝากของ');  
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }else{
                JCNxShowMsgSessionExpired();
            }
            $("#oetCRVSearchSoSCAN").val('');
            $("#oetCRVSearchSoSCAN").text('');
        });

        $('#oetCRVSearchSoSCAN').on('keydown',function(){
            if(event.keyCode == 13){
                $.ajax({
                    type: "POST",
                    url: "dcmSODataCheckCRV",
                    data: {
                        tDocno  : $("#oetCRVSearchSoSCAN").val(),
                    },
                    cache: false,
                    timeout: 0,
                    success: function (oResult){
                        var aReturnData = JSON.parse(oResult);
                        if(aReturnData['nStaEvent'] == '1'){  
                            var aGetallitems = aReturnData['aItems']['aItems'];
                            if(aGetallitems.length > 1){
                                var tStrInCondition = '';
                                for(i=0;i<aGetallitems.length;i++){
                                    tStrInCondition += "'";
                                    tStrInCondition += aReturnData['aItems']['aItems'][i]['FTXshRefDocNo'];
                                    tStrInCondition += "',";
                                }
                                tStrInCondition = tStrInCondition.slice(0,-1);
                                $("#ohdCRVInCendition").val(tStrInCondition);
                                JSvCRVCallPageDataTable();
                                $("#ohdCRVInCendition").val('');
                            }else{
                                var tBchCode = aReturnData['aItems']['aItems'][0]['FTBchCode'];
                                var tDocNo = aReturnData['aItems']['aItems'][0]['FTXshRefDocNo'];
                                $("#ohdCRVInCendition").val('');
                                // alert(tDocNo);
                                JSvCRVCallPageEdit(tBchCode,tDocNo)
                            }
                        }else{
                            FSvCMNSetMsgErrorDialog('ไม่พบเลขที่ใบฝากของ');  
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
                $("#oetCRVSearchSoSCAN").val('');
                $("#oetCRVSearchSoSCAN").text('');
            }
        });
    function JSxCRVControllBrowse(){  
        var tBchName = $('#oetCRVAdvSearchBchCodeFrom').val();
        var tShpName = $('#oetCRVAdvSearchShpCode').val();

        if (tBchName != '') {
            $('#obtCRVAdvSearchBrowseShp').attr('disabled', false)
        } else {
            $('#obtCRVAdvSearchBrowseShp').attr('disabled', true)
            $('#obtCRVAdvSearchBrowsePos').attr('disabled', true)
            
        }

        if(tShpName != ''){
            $('#obtCRVAdvSearchBrowsePos').attr('disabled', false)
        }else{
            $('#obtCRVAdvSearchBrowsePos').attr('disabled', true)
        }
    }
    $('#obtCRVAdvSearchBrowseShp').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            var tDataBranch = $('#oetCRVAdvSearchBchCodeFrom').val();

            if (tDataBranch != '') {
                tTextWhereInBranch = ' AND TCNMShop.FTBchCode = '+tDataBranch+'';
            }

            window.oShopBrowseOption = undefined;
            oShopBrowseOption = {
                Title: ['company/shop/shop', 'tSHPTitle_POS'],
                Table: {
                    Master: 'TCNMShop',
                    PK: 'FTShpCode'
                },
                Join: {
                    Table: ['TCNMShop_L'],
                    On: [
                        'TCNMShop.FTBchCode = TCNMShop_L.FTBchCode AND TCNMShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = ' + nLangEdits,
                    ]
                },
                Where: {
                    Condition: [tTextWhereInBranch]
                },
                GrideView: {
                    ColumnPathLang: 'company/shop/shop',
                    ColumnKeyLang: ['tShopCode_POS', 'tShopName_POS'],
                    ColumnsSize: ['40%', '60%'],
                    WidthModal: 50,
                    DataColumns: ['TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName'],
                    DataColumnsFormat: ['', ''],
                    Perpage: 10,
                    OrderBy: ['TCNMShop.FDCreateOn DESC, TCNMShop.FTBchCode ASC,TCNMShop.FTShpCode ASC'],
                },
                CallBack: {
                    ReturnType	: 'S',
                    Value: ['oetCRVAdvSearchShpCode', "TCNMShop.FTShpCode"],
                    Text: ['oetCRVAdvSearchShpName', "TCNMShop_L.FTShpName"]
                },
                NextFunc: {
                    FuncName: 'JSxCRVControllBrowse',
                },
            };
            JCNxBrowseData('oShopBrowseOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });
    $('#obtCRVAdvSearchBrowsePos').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();

            let tDataBranch = $('#oetCRVAdvSearchBchCodeFrom').val();
            let tDataShop = $('#oetCRVAdvSearchShpCode').val();

            // ********** Check Data Branch **********
            let tTextWhereInBranch = '';
            if (tDataBranch != '') {
                tTextWhereInBranch = ' AND TRTMShopPos.FTBchCode = ' + tDataBranch + '';
            }

            // ********** Check Data Shop **********
            let tTextWhereInShop = '';
            if (tDataShop != '') {
                tTextWhereInShop = ' AND TRTMShopPos.FTShpCode = ' + tDataShop + '';
            }

            window.oPSHPosBrowseOption = undefined;
            oPSHPosBrowseOption = {
                Title: ["pos/salemachine/salemachine", "tPOSTitle"],
                Table: {
                    Master: 'TCNMPos',
                    PK: 'FTPosCode'
                },
                Join: {
                    Table: ['TRTMShopPos', 'TCNMBranch_L', 'TCNMShop_L','TCNMPos_L'],
                    On: [
                        'TCNMPos.FTPosCode = TRTMShopPos.FTPosCode AND TCNMPos.FTBchCode = TRTMShopPos.FTBchCode AND TRTMShopPos.FTPshStaUse = 1',
                        'TRTMShopPos.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits,
                        'TRTMShopPos.FTBchCode = TCNMShop_L.FTBchCode AND TRTMShopPos.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = ' + nLangEdits,
                        'TCNMPos_L.FTBchCode = TCNMPos.FTBchCode AND TCNMPos_L.FTPosCode = TCNMPos.FTPosCode AND TCNMPos_L.FNLngID = ' + nLangEdits
                    ]
                },
                Where: {
                    Condition: ['AND (TCNMPos.FTPosType IN (5)) ' + tTextWhereInBranch + tTextWhereInShop]
                },
                GrideView: {
                    ColumnPathLang: 'pos/salemachine/salemachine',
                    ColumnKeyLang: ['tPOSBranchRef', 'tPOSShopRef', 'tPOSCode', 'tPOSName'],
                    ColumnsSize: ['20%', '20%','20%', '20%'],
                    WidthModal: 50,
                    DataColumns: [ 'TCNMBranch_L.FTBchName', 'TCNMShop_L.FTShpName', 'TCNMPos.FTPosCode', 'TCNMPos_L.FTPosName'],
                    DataColumnsFormat: ['', '', '', ''],
                    Perpage: 10,
                    OrderBy: ['TCNMPos.FDCreateOn DESC, TCNMPos.FTPosCode ASC'],
                },
                CallBack: {
                    ReturnType	: 'S',
                    Value: ['oetCRVAdvSearchPosCode', "TCNMPos.FTPosCode"],
                    Text: ['oetCRVAdvSearchPosName', "TCNMPos_L.FTPosName"]
                },
                // DebugSQL: true
            };
            JCNxBrowseData('oPSHPosBrowseOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $("#ocmCRVAdvSearchPshCode").change(function () { 
        if($(this).val() == '2'){
            $("#oetCRVAdvSearchPosName").text('');
            $("#oetCRVAdvSearchPosName").val('');
            $("#oetCRVAdvSearchPosCode").val('');
            $("#obtCRVAdvSearchBrowsePos").attr('disabled',true);
        }else{
            $("#obtCRVAdvSearchBrowsePos").attr('disabled',false);
        }
    });

</script>