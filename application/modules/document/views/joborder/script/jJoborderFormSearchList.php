<script type="text/javascript">
    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

	if(nCountBch == 1){
		$('#obtJOBAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtJOBAdvSearchBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

	    // Option Branch
        var oJOBBrowseBranch = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var oOptionReturn       = {
            Title : ['company/branch/branch','tBCHTitle'],
            Table : {Master:'TCNMBranch',PK:'FTBchCode'},
            Join :{
                Table : ['TCNMBranch_L'],
                On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['tBCHCode','tBCHName'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['',''],
                Perpage             : 10,
                OrderBy             : ['TCNMBranch_L.FTBchName ASC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"],
            },
        }
        return oOptionReturn;
    };

    // Event Browse Branch From
    $('#obtJOBAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oJOBBrowseBranchFromOption  = oJOBBrowseBranch({
                'tReturnInputCode'  : 'oetJOBAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetJOBAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oJOBBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtJOBAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oJOBBrowseBranchToOption  = oJOBBrowseBranch({
                'tReturnInputCode'  : 'oetJOBAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetJOBAdvSearchBchNameTo'
            });
            JCNxBrowseData('oJOBBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $(document).ready(function(){
        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        // Doc Date From
        $('#obtJOBAdvSearchDocDateForm').unbind().click(function(){
            $('#oetJOBAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtJOBAdvSearchDocDateTo').unbind().click(function(){
            $('#oetJOBAdvSearcDocDateTo').datepicker('show');
        });
        
    });

    // Advance search Display control
    $('#obtJOBAdvanceSearch').unbind().click(function(){
        if($('#odvJOBAdvanceSearchContainer').hasClass('hidden')){
            $('#odvJOBAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvJOBAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });
    
    $('#obtJOBSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxJOBClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxJOBClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmJOBFromSerchAdv').find('input').val('');
            $('#oetJOBSearchAllDocument').val('');
            $('#ofmJOBFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvJOBCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page  ====================================================
        $('#oetJOBSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvJOBCallPageDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });
        
        $('#obtJOBSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvJOBCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtJOBAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvJOBCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    // =================================================================================================================================================

    // Function: Call Page DataTable
    function JSvJOBCallPageDataTable(pnPage) {
        var oAdvanceSearch = JSoJOBGetAdvanceSearchData();
        var nPageCurrent = pnPage;
        if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        $.ajax({
            type: "POST",
            url: "docJOBDataTable",
            data: {
                nPageCurrent: nPageCurrent,
                oAdvanceSearch: oAdvanceSearch
            },
            cache: false,
            timeout: 0,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#ostJOBDataTableDocument').html(aReturnData['tViewDataTable']);
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                }
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
    // รวม Values ต่างๆของการค้นหาขั้นสูง
    function JSoJOBGetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchAll: $("#oetJOBSearchAllDocument").val(),
            tSearchBchCodeFrom: $("#oetJOBAdvSearchBchCodeFrom").val(),
            tSearchBchCodeTo: $("#oetJOBAdvSearchBchCodeTo").val(),
            tSearchDocDateFrom: $("#oetJOBAdvSearcDocDateFrom").val(),
            tSearchDocDateTo: $("#oetJOBAdvSearcDocDateTo").val(),
            tSearchStaDoc: $("#ocmJOBAdvSearchStaDoc").val(),
            tSearchStaDocAct: $("#ocmStaDocAct").val()
        };
        return oAdvanceSearchData;
    }

</script>