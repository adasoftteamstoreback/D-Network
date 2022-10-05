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

        // Doc Date From
        $('#obtPRBAdvSearchDocDateForm').unbind().click(function(){
            $('#oetPRBAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtPRBAdvSearchDocDateTo').unbind().click(function(){
            $('#oetPRBAdvSearcDocDateTo').datepicker('show');
        });
        
    });

    // Advance search Display control
    $('#obtPRBAdvanceSearch').unbind().click(function(){
        if($('#odvPRBAdvanceSearchContainer').hasClass('hidden')){
            $('#odvPRBAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvPRBAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    // Option Branch
    var oPRBBrowseBranch = function(poReturnInput){
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
        }
        return oOptionReturn;
    };

    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

    if(nCountBch == 1){
		$('#obtPRBAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtPRBAdvSearchBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

    // Event Browse Branch From
    $('#obtPRBAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPRBBrowseBranchFromOption  = oPRBBrowseBranch({
                'tReturnInputCode'  : 'oetPRBAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetPRBAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oPRBBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtPRBAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPRBBrowseBranchToOption  = oPRBBrowseBranch({
                'tReturnInputCode'  : 'oetPRBAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetPRBAdvSearchBchNameTo'
            });
            JCNxBrowseData('oPRBBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    $('#obtPRBSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxPRBClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxPRBClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmPRBFromSerchAdv').find('input').val('');
            $('#ofmPRBFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvPRBCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page  ====================================================
        $('#oetPRBSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvPRBCallPageDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });
        
        $('#obtPRBSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvPRBCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtPRBAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvPRBCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    // =================================================================================================================================================



</script>