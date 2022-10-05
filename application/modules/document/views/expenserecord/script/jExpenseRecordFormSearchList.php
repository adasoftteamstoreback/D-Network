<script type="text/javascript">
    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit"); ?>;

    $(document).ready(function(){
        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        });

        // Doc Date From
        $('#obtPXAdvSearchDocDateForm').unbind().click(function(){
            $('#oetPXAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtPXAdvSearchDocDateTo').unbind().click(function(){
            $('#oetPXAdvSearcDocDateTo').datepicker('show');
        });
        
    });

    // Advance search Display control
    $('#obtPXAdvanceSearch').unbind().click(function(){
        if($('#odvPXAdvanceSearchContainer').hasClass('hidden')){
            $('#odvPXAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvPXAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    var nCountBch 		= "<?php echo $this->session->userdata("nSesUsrBchCount"); ?>";
    if(nCountBch == 1){
        $('#obtPXAdvSearchBrowseBchFrom').attr('disabled',true);
        $('#obtPXAdvSearchBrowseBchTo').attr('disabled',true);
    }
    
    // Option Branch
    var oPXBrowseBranch = function(poReturnInput){

        var tWhereModal = "";
        var tUsrLevel   = "<?php echo $this->session->userdata("tSesUsrLevel");?>";
        var tBchMulti 	= "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        if(tUsrLevel != "HQ"){
            tWhereModal 	+= " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
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
                Condition : [tWhereModal]
            },
            GrideView:{
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['tBCHCode','tBCHName'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['',''],
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"],
            },
            NextFunc: {
                FuncName: 'JSxPXNextFuncAdvSearchBch',
                ArgReturn: ['FTBchCode','FTBchName']
            },
        }
        return oOptionReturn;
    };

    function JSxPXNextFuncAdvSearchBch(poDataNextFunc) {
        if (typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL") {

            var aDataNextFunc = JSON.parse(poDataNextFunc);
            if( $('#oetPXAdvSearchBchCodeFrom').val() == "" ){
                $('#oetPXAdvSearchBchCodeFrom').val(aDataNextFunc[0]);
                $('#oetPXAdvSearchBchNameFrom').val(aDataNextFunc[1]);
            }

            if( $('#oetPXAdvSearchBchCodeTo').val() == "" ){
                $('#oetPXAdvSearchBchCodeTo').val(aDataNextFunc[0]);
                $('#oetPXAdvSearchBchNameTo').val(aDataNextFunc[1]);
            }
        }
    }

    // Event Browse Branch From
    $('#obtPXAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPXBrowseBranchFromOption  = oPXBrowseBranch({
                'tReturnInputCode'  : 'oetPXAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetPXAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oPXBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtPXAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPXBrowseBranchToOption  = oPXBrowseBranch({
                'tReturnInputCode'  : 'oetPXAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetPXAdvSearchBchNameTo'
            });
            JCNxBrowseData('oPXBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    $('#obtPXSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxPXClearAdvSearchData();
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
    function JSxPXClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){

            var nCountBch = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
            if(nCountBch != 1){ //ถ้ามีมากกว่า 1 สาขาต้อง reset 
                $('#oetPXAdvSearchBchNameFrom').val("");
                $('#oetPXAdvSearchBchCodeFrom').val("");
                $('#oetPXAdvSearchBchCodeTo').val("");
                $('#oetPXAdvSearchBchNameTo').val("");
            }

            $('#oetPXAdvSearcDocDateFrom').val("");
            $('#oetPXAdvSearcDocDateTo').val("");

            $('#ofmPXFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvPXCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page Purchase Invioce ====================================================
        $('#oetPXSearchAllDocument').keyup(function(event){
            var nCodeKey    = event.which;
            if(nCodeKey == 13){
                event.preventDefault();
                var nStaSession = JCNxFuncChkSessionExpired();
                if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                    JSvPXCallPageDataTable();
                }else{
                    JCNxShowMsgSessionExpired();
                }
            }
        });
        
        $('#obtPXSerchAllDocument').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvPXCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        $("#obtPXAdvSearchSubmitForm").unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof nStaSession !== "undefined" && nStaSession == 1){
                JSvPXCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    // =================================================================================================================================================



</script>