<script type="text/javascript">
    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit"); ?>;

    $(document).ready(function(){
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        });

        // Doc Date From
        $('#obtSOAdvSearchDocDateForm').unbind().click(function(){
            $('#oetSOAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtSOAdvSearchDocDateTo').unbind().click(function(){
            $('#oetSOAdvSearcDocDateTo').datepicker('show');
        });

        // Doc Date From
        $('#obtSOGenAdvSearchDocDateForm').unbind().click(function(){
            $('#oetSOGenAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtSOGenAdvSearchDocDateTo').unbind().click(function(){
            $('#oetSOGenAdvSearcDocDateTo').datepicker('show');
        });
        
    });

    // Advance search Display control
    $('#obtSOAdvanceSearch').unbind().click(function(){
        if($('#odvSOAdvanceSearchContainer').hasClass('hidden')){
            $('#odvSOAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvSOAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    // Advance search2 Display control
    $('#obtSOGenAdvanceSearch').unbind().click(function(){
        if($('#odvSOGenAdvanceSearchContainer').hasClass('hidden')){
            $('#odvSOGenAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvSOGenAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

    if(nCountBch == 1){
		$('#obtSOAdvSearchBrowseBchTo').attr('disabled',true);
		$('#obtSOGenAdvSearchBrowseBchTo').attr('disabled',true);
		$('#obtSOAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtSOGenAdvSearchBrowseBchFrom').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

    // Option Branch
    var oSOBrowseBranch = function(poReturnInput){
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
                Perpage             : 20,
                OrderBy             : ['TCNMBranch_L.FTBchName ASC'],
            },
            Where   : {
                Condition : [tWhere]
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
    $('#obtSOAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSOBrowseBranchFromOption  = oSOBrowseBranch({
                'tReturnInputCode'  : 'oetSOAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetSOAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oSOBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch From
    $('#obtSOGenAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSOBrowseBranchFromOption  = oSOBrowseBranch({
                'tReturnInputCode'  : 'oetSOGenAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetSOGenAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oSOBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtSOAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSOBrowseBranchToOption  = oSOBrowseBranch({
                'tReturnInputCode'  : 'oetSOAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetSOAdvSearchBchNameTo'
            });
            JCNxBrowseData('oSOBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtSOGenAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oSOBrowseBranchToOption  = oSOBrowseBranch({
                'tReturnInputCode'  : 'oetSOGenAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetSOGenAdvSearchBchNameTo'
            });
            JCNxBrowseData('oSOBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    $('#obtSOSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxSOClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtSOGenSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxSOGenClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Functionality: ฟังก์ชั่นล้างค่า Input Advance Search
    // Parameters: Button Event Click
    // Creator: 19/06/2019 Wasin(Yoshi)
    // Last Update: -
    // Return: Clear Value In Input Advance Search
    // ReturnType: -
    function JSxSOClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmSOFromSerchAdv').find('input').val('');
            $('#ocmSOAdvSearchStaDoc').val(1).selectpicker("refresh");
            $('#ocmSOAdvSearchStaApprove').val(0).selectpicker("refresh");
            JSvSOCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Functionality: ฟังก์ชั่นล้างค่า Input Advance Search
    // Parameters: Button Event Click
    // Creator: 18/05/2022 Off
    // Last Update: -
    // Return: Clear Value In Input Advance Search
    // ReturnType: -
    function JSxSOGenClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmSOFromSerchAdv').find('input').val('');
            $('#ocmSOGenAdvSearchStaDoc').val(1).selectpicker("refresh");
            $('#ocmSOGenAdvSearchStaApprove').val(0).selectpicker("refresh");
            JSvSOCallPageGenPODataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page Purchase Invioce ====================================================
        $('#oetSOSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvSOCallPageDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });

        $('#oetSOGenSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvSOCallPageGenPODataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });
        
        $('#obtSOSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvSOCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $('#obtSOGenSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvSOCallPageGenPODataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtSOAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvSOCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtSOGenAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvSOCallPageGenPODataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    // =================================================================================================================================================

    $('#oliSOBtnToGenPo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvSOCallPageGenPODataTable('','4');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

</script>