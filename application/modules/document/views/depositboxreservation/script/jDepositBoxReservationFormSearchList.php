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
        $('#obtDBRAdvSearchDocDateForm').unbind().click(function(){
            $('#oetDBRAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtDBRAdvSearchDocDateTo').unbind().click(function(){
            $('#oetDBRAdvSearcDocDateTo').datepicker('show');
        });
        
    });

    var tUsrLevel 	  	= "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

    if(nCountBch == 1){
		$('#obtDBRAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtDBRAdvSearchBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}


    // Advance search Display control
    $('#obtDBRAdvanceSearch').unbind().click(function(){
        if($('#odvDBRAdvanceSearchContainer').hasClass('hidden')){
            $('#odvDBRAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvDBRAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    // Option Branch
    var oDBRBrowseBranch = function(poReturnInput){
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

    // Event Browse Branch From
    $('#obtDBRAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDBRBrowseBranchFromOption  = oDBRBrowseBranch({
                'tReturnInputCode'  : 'oetDBRAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetDBRAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oDBRBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtDBRAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDBRBrowseBranchToOption  = oDBRBrowseBranch({
                'tReturnInputCode'  : 'oetDBRAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetDBRAdvSearchBchNameTo'
            });
            JCNxBrowseData('oDBRBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    $('#obtDBRSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxDBRClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxDBRClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmDBRFromSerchAdv').find('input').val('');
            $('#ofmDBRFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvDBRCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page  ====================================================
        $('#oetDBRSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvDBRCallPageDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });
        
        $('#obtDBRSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvDBRCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtDBRAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvDBRCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    // =================================================================================================================================================



</script>