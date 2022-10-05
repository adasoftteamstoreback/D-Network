<script type="text/javascript">
    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit"); ?>;

    $(document).ready(function(){
        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        });

        // Doc Date From
        $('#obtSOAdvSearchDocDateForm').unbind().click(function(){
            $('#oetDPSAdvSearcDocDateFrom').datepicker('show');
        });
    });

    // Advance search Display control
    $('#obtDPSAdvanceSearch').unbind().click(function(){
        if($('#odvDPSAdvanceSearchContainer').hasClass('hidden')){
            $('#odvDPSAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvDPSAdvanceSearchContainer").slideUp(500,function() {
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
		$('#obtDPSAdvSearchBrowseBchFrom').attr('disabled',true);
		$('#obtDPSAdvSearchBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

    // Option Branch
    var oDPSBrowseBranch = function(poReturnInput){
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
    $('#obtDPSAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDPSBrowseBranchFromOption  = oDPSBrowseBranch({
                'tReturnInputCode'  : 'oetDPSAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetDPSAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oDPSBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtDPSAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oDPSBrowseBranchToOption  = oDPSBrowseBranch({
                'tReturnInputCode'  : 'oetDPSAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetDPSAdvSearchBchNameTo'
            });
            JCNxBrowseData('oDPSBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    $('#obtDPSSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxDPSClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //ฟังก์ชั่นล้างค่า Input Advance Search
    function JSxDPSClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmDPSFromSerchAdv').find('input').val('');
            $('#ofmDPSFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvDPSCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    $('#oetDPSSearchAllDocument').keyup(function(event){
        var nCodeKey    = event.which;
        if(nCodeKey == 13){
            event.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSvDPSCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        }
    });
    
    $('#obtDPSSerchAllDocument').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvDPSCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $("#obtDPSAdvSearchSubmitForm").unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvDPSCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

</script>