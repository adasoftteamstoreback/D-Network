<script type="text/javascript">
    var tUsrLevel       = "<?=$this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?=$this->session->userdata("tLangEdit")?>";
    var tWhere 			= "";
    if(nCountBch == 1){
        $('#obtJR1AdvSearchBrowseBchFrom').attr('disabled',true);
        $('#obtJR1AdvSearchBrowseBchTo').attr('disabled',true);
    }
    if(tUsrLevel != "HQ"){
        tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
    }else{
        tWhere = "";
    }
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });
        // Advance search Display control
        $('#obtJR1AdvanceSearch').unbind().click(function(){
            if($('#odvJR1AdvanceSearchContainer').hasClass('hidden')){
                $('#odvJR1AdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
            }else{
                $("#odvJR1AdvanceSearchContainer").slideUp(500,function() {
                    $(this).addClass('hidden');
                });
            }
        });
        // Clear Search Reset Filter
        $('#obtJR1SearchReset').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxJR1ClearAdvSearchData();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        // Option Branch Advance Filter 
        var oJR1BrowseBranch = function(poReturnInput){
            var tInputReturnCode    = poReturnInput.tReturnInputCode;
            var tInputReturnName    = poReturnInput.tReturnInputName;
            var oOptionReturn       = {
                Title   : ['company/branch/branch','tBCHTitle'],
                Table   : {Master:'TCNMBranch',PK:'FTBchCode'},
                Join    : {
                    Table : ['TCNMBranch_L'],
                    On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
                },
                GrideView   : {
                    ColumnPathLang      : 'company/branch/branch',
                    ColumnKeyLang       : ['tBCHCode','tBCHName'],
                    ColumnsSize         : ['15%','75%'],
                    WidthModal          : 50,
                    DataColumns         : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                    DataColumnsFormat   : ['',''],
                    Perpage             : 10,
                    OrderBy             : ['TCNMBranch_L.FTBchName ASC'],
                },
                CallBack    : {
                    ReturnType	: 'S',
                    Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                    Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"],
                }
            };
            return oOptionReturn;
        };
        // Event Browse Branch From
        $('#obtJR1AdvSearchBrowseBchFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oJR1BrowseBranchFromOption  = oJR1BrowseBranch({
                    'tReturnInputCode'  : 'oetJR1AdvSearchBchCodeFrom',
                    'tReturnInputName'  : 'oetJR1AdvSearchBchNameFrom'
                });
                JCNxBrowseData('oJR1BrowseBranchFromOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        // Event Browse Branch To
        $('#obtJR1AdvSearchBrowseBchTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                window.oJR1BrowseBranchToOption  = oJR1BrowseBranch({
                    'tReturnInputCode'  : 'oetJR1AdvSearchBchCodeTo',
                    'tReturnInputName'  : 'oetJR1AdvSearchBchNameTo'
                });
                JCNxBrowseData('oJR1BrowseBranchToOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        // Event Click Document Date Form
        $('#obtJR1AdvSearchDocDateForm').unbind().click(function(){
            $('#oetJR1AdvSearcDocDateFrom').datepicker('show');
        });
        // Event Click Document Date To
        $('#obtJR1AdvSearchDocDateTo').unbind().click(function(){
            $('#oetJR1AdvSearcDocDateTo').datepicker('show');
        });
        // Event Key Up Input Text Search
        $('#oetJR1SearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvJR1CallPageDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });
        // Event Click Button Search All Document
        $('#obtJR1SerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvJR1CallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        // Event Click Submit Form Search Advance
        $("#obtJR1AdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvJR1CallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    });
    // Function: ล้างค่า Input ทั้งหมดใน Advance Search
    // Create By: Wasin (Yoshi)
    // Create Date: 21/09/2021
    function JSxJR1ClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmJR1FromSerchAdv').find('input').val('');
            $('#oetJR1SearchAllDocument').val('');
            $('#ofmJR1FromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvJR1CallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }
    // Function: รวม Values ต่างๆของการค้นหาขั้นสูง
    // Create By: Wasin (Yoshi)
    // Create Date: 21/09/2021
    function JSoJR1GetAdvanceSearchData() {
        var oAdvanceSearchData = {
            tSearchAll: $("#oetJR1SearchAllDocument").val(),
            tSearchBchCodeFrom: $("#oetJR1AdvSearchBchCodeFrom").val(),
            tSearchBchCodeTo: $("#oetJR1AdvSearchBchCodeTo").val(),
            tSearchDocDateFrom: $("#oetJR1AdvSearcDocDateFrom").val(),
            tSearchDocDateTo: $("#oetJR1AdvSearcDocDateTo").val(),
            tSearchStaDoc: $("#ocmJR1AdvSearchStaDoc").val(),
            tSearchStaDocAct: $("#ocmJR1StaDocAct").val()
        };
        return oAdvanceSearchData;
    }
</script>