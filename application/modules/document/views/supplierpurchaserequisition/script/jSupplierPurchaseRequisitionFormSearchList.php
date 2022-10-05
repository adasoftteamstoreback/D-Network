<script type="text/javascript">
    var nLangEdits  = <?=$this->session->userdata("tLangEdit"); ?>;

    $(document).ready(function(){

        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format                  : 'yyyy-mm-dd',
            enableOnReadonly        : false,
            disableTouchKeyboard    : true,
            autoclose               : true
        });

        // Doc Date From
        $('#obtPRSAdvSearchDocDateForm').unbind().click(function(){
            $('#oetPRSAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtPRSAdvSearchDocDateTo').unbind().click(function(){
            $('#oetPRSAdvSearcDocDateTo').datepicker('show');
        });
        
    });

    var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    if(nCountBch == 1){
        $('#obtPRSAdvSearchBrowseBchTo').attr('disabled',true);
        $('#obtPRSAdvSearchBrowseBchFrom').attr('disabled',true);
    }

    // ค้นหาขั้นสูง
    $('#obtPRSAdvanceSearch , #obtPRS_FN_AdvanceSearch').unbind().click(function(){
        if($('#odvPRSAdvanceSearchContainer , #odvPRS_FN_AdvanceSearchContainer').hasClass('hidden')){
            $('#odvPRSAdvanceSearchContainer , #odvPRS_FN_AdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvPRSAdvanceSearchContainer , #odvPRS_FN_AdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    // Option Branch
    var oPRSBrowseBranch = function(poReturnInput){

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tAgnCode  = "<?=$this->session->userdata('tSesUsrAgnCode');?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if(tUsrLevel != "HQ"){
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }

        if(tAgnCode != ""){
            tSQLWhereAgn = "AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
        }

        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var oOptionReturn       = {
            Title : ['company/branch/branch','tBCHTitle'],
            Table : {Master:'TCNMBranch',PK:'FTBchCode'},
            Join :{
                Table : ['TCNMBranch_L'],
                On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
            },
            Where : {
                Condition : [tSQLWhereBch,tSQLWhereAgn]
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
    $('#obtPRSAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPRSBrowseBranchFromOption  = oPRSBrowseBranch({
                'tReturnInputCode'  : 'oetPRSAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetPRSAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oPRSBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtPRSAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPRSBrowseBranchToOption  = oPRSBrowseBranch({
                'tReturnInputCode'  : 'oetPRSAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetPRSAdvSearchBchNameTo'
            });
            JCNxBrowseData('oPRSBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    $('#obtPRSSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxPRSClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างค่า Input ทั้งหมดใน Advance Search
    function JSxPRSClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmPRSFromSerchAdv').find('input').val('');
            $('#ofmPRSFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvPRSCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    $('#oetPRSSearchAllDocument').keyup(function(event){
        var nCodeKey    = event.which;
        if(nCodeKey == 13){
            event.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSvPRSCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        }
    });
    
    $('#obtPRSSerchAllDocument').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvPRSCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $("#obtPRSAdvSearchSubmitForm").unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){

            if($('#ohdPRSTypeDocument').val() == 1){ //ใบขอซื้อแบบสำนักงานใหญ่
                JSvPRSCallPageDataTable();
            }else{ //ใบขอซื้อแบบแฟรนไชส์
                JSvPRSCallPageDataTable_FN();
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

</script>