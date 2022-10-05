<script type="text/javascript">

    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit"); ?>;

    $(document).ready(function(){

        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format                  : 'yyyy-mm-dd',
            enableOnReadonly        : false,
            disableTouchKeyboard    : true,
            autoclose               : true,
            todayHighlight          : true
        });

        // Doc Date From
        $('#obtDLVDocDateFrm').unbind().click(function(){
            $('#oetDLVDocDateFrm').datepicker('show');
        });

        // Doc Date To
        $('#obtDLVDocDateTo').unbind().click(function(){
            $('#oetDLVDocDateTo').datepicker('show');
        });

    });

    // ค้นหาขั้นสูง
    $('#oahDLVAdvanceSearch').unbind().click(function(){
        if($('#odvDLVAdvanceSearchContainer').hasClass('hidden')){
            $('#odvDLVAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvDLVAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    // From Search Data Page 
    $("#obtDLVConfirmSearch").unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvDLVCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var tUsrLevel 	  	= "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
	var tBchCodeMulti 	= "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
	var nCountBch 		= "<?php echo $this->session->userdata("nSesUsrBchCount"); ?>";
    var nLangEdits      = "<?php echo $this->session->userdata("tLangEdit")?>";
	var tWhere 			= "";

	if(nCountBch == 1){
		$('#obtTQBrowseBchFrom').attr('disabled',true);
		$('#obtTQBrowseBchTo').attr('disabled',true);
	}
	if(tUsrLevel != "HQ"){
		tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
	}else{
		tWhere = "";
	}

   // Option Branch From
	var oDLVBrowseBchFrom = {

        Title   : ['company/branch/branch', 'tBCHTitle'],
        Table   : {
            Master  : 'TCNMBranch',
            PK      : 'FTBchCode'
        },
        Join    : {
            Table   : ['TCNMBranch_L'],
            On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits, ]
        },
        Where   : {
            Condition : [tWhere]
        },
        GrideView : {
            ColumnPathLang   : 'company/branch/branch',
            ColumnKeyLang    : ['tBCHCode', 'tBCHName'],
            ColumnsSize      : ['15%', '75%'],
            WidthModal       : 50,
            DataColumns      : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
            DataColumnsFormat: ['', ''],
            Perpage          : 10,
            OrderBy          : ['TCNMBranch.FTBchCode ASC'],
        },
        CallBack: {
            ReturnType      : 'S',
            Value           : ["oetDLVFrmBchCode", "TCNMBranch.FTBchCode"],
            Text            : ["oetDLVFrmBchName", "TCNMBranch_L.FTBchName"],
        },
    }

    // Option Branch To
    var oDLVBrowseBchTo = {

        Title   : ['company/branch/branch', 'tBCHTitle'],
        Table   : {
            Master  : 'TCNMBranch',
            PK      : 'FTBchCode'
        },
        Join    : {
            Table   : ['TCNMBranch_L'],
            On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits, ]
        },
        Where   :{
            Condition : [tWhere]
        },
        GrideView   : {
            ColumnPathLang      : 'company/branch/branch',
            ColumnKeyLang       : ['tBCHCode', 'tBCHName'],
            ColumnsSize         : ['15%', '75%'],
            WidthModal          : 50,
            DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
            DataColumnsFormat   : ['', ''],
            Perpage             : 10,
            OrderBy             : ['TCNMBranch.FTBchCode DESC'],
        },
        CallBack: {
            ReturnType  : 'S',
            Value       : ["oetDLVToBchCode", "TCNMBranch.FTBchCode"],
            Text        : ["oetDLVToBchName", "TCNMBranch_L.FTBchName"],
        },
    }

    // Event Browse
    $('#obtDLVBrowseBchFrm').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            JCNxBrowseData('oDLVBrowseBchFrom');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtDLVBrowseBchTo').unbind().click(function() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            JCNxBrowseData('oDLVBrowseBchTo');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    // ล้างข้อมูล clear ค่า
    function JSxDLVClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){

            var nCountBch = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
            if(nCountBch != 1){ //ถ้ามีมากกว่า 1 สาขาต้อง reset 
                $("#oetDLVFrmBchName").val("");
                $("#oetDLVFrmBchCode").val(""); 
                $("#oetDLVToBchName").val("");
                $("#oetDLVToBchCode").val("");
            }

            $('#oetDLVDocDateFrm').val("");
            $('#oetDLVDocDateTo').val("");
            $('#ofmDLVSearchAdv').find('select').val(0).selectpicker("refresh");
            JSvDLVCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }


</script>
