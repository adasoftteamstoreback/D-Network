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
        $('#obtWhTaxAdvSearchDocDateForm').unbind().click(function(){
            $('#oetWhTaxAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtWhTaxAdvSearchDocDateTo').unbind().click(function(){
            $('#oetWhTaxAdvSearcDocDateTo').datepicker('show');
        });
        
    });

    // Advance search Display control
    $('#obtWhTaxAdvanceSearch').unbind().click(function(){
        if($('#odvWhTaxAdvanceSearchContainer').hasClass('hidden')){
            $('#odvWhTaxAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvWhTaxAdvanceSearchContainer").slideUp(500,function() {
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
		$('#obtWhTaxAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtWhTaxAdvSearchBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

    // Option Branch
    var oWhTaxBrowseBranch = function(poReturnInput){
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
    $('#obtWhTaxAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oWhTaxBrowseBranchFromOption  = oWhTaxBrowseBranch({
                'tReturnInputCode'  : 'oetWhTaxAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetWhTaxAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oWhTaxBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtWhTaxAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oWhTaxBrowseBranchToOption  = oWhTaxBrowseBranch({
                'tReturnInputCode'  : 'oetWhTaxAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetWhTaxAdvSearchBchNameTo'
            });
            JCNxBrowseData('oWhTaxBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    $('#obtWhTaxSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxWhTaxClearAdvSearchData();
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
    function JSxWhTaxClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmWhTaxFromSerchAdv').find('input').val('');
            $('#ofmWhTaxFromSerchAdv').find('select').val(0).selectpicker("refresh");
            $('#oetWhTaxAdvSearcDocDateFrom').datepicker('setDate', null);
            $("#oetWhTaxAdvSearcDocDateTo").datepicker('setDate', null);
            JSvWHTaxDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page Purchase Invioce ====================================================
        $('#oetWhTaxSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvWHTaxDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });
        
        $('#obtWhTaxSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvWHTaxDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtWhTaxAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvWHTaxDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    // =================================================================================================================================================



</script>