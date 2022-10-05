<script type="text/javascript">
    $(document).ready(function(){
        JSxCMNVisibleComponent('#obtAPDCancel', true);
        JSxCMNVisibleComponent('#obtAPDApprove', true);
        JSxCMNVisibleComponent('#odvBtnAddEdit .btn-group', true);
        JSxCheckPinMenuClose();

        $('.selectpicker').selectpicker();

        $('#obtXphDocDateFrom').click(function(){
            event.preventDefault();
            $('#oetXphDocDateFrom').datepicker('show');
        });

        $('#obtXphDocDateTo').click(function(){
            event.preventDefault();
            $('#oetXphDocDateTo').datepicker('show');
        });

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true,
        });

        $(".selection-2").select2({
            dropdownParent: $('#dropDownSelect1')
        });

    });

    // Advance search display control
    $('#oahAPDAdvanceSearch').on('click', function() {
        if($('#odvAPDAdvanceSearchContainer').hasClass('hidden')){
            JSxCMNVisibleComponent('#odvAPDAdvanceSearchContainer', true, 'slideUD');
        }else{
            JSxCMNVisibleComponent('#odvAPDAdvanceSearchContainer', false, 'slideUD');
        }
    });

    var tUsrLevel       = "<?php echo $this->session->userdata("tSesUsrLevel"); ?>";
    var tBchCodeMulti 	= "<?php echo $this->session->userdata("tSesUsrBchCodeMulti"); ?>";
    var nCountBch 		= "<?php echo $this->session->userdata("nSesUsrBchCount"); ?>";
    var tWhere 			= "";

    if(nCountBch == 1){
        $('#obtAPDBrowseBchFrom').attr('disabled',true);
        $('#obtAPDBrowseBchTo').attr('disabled',true);
    }

    if(tUsrLevel != "HQ"){
        tWhere = " AND TCNMBranch.FTBchCode IN ("+tBchCodeMulti+") ";
    }else{
        tWhere = "";
    }

    // Option Branch
    var oPmhBrowseBranch = function(poReturnInput){
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
    $('#obtAPDBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oPmhBrowseBranchFromOption  = oPmhBrowseBranch({
                'tReturnInputCode'  : 'oetBchCodeFrom',
                'tReturnInputName'  : 'oetBchNameFrom'
            });
            JCNxBrowseData('oPmhBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtAPDBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oPmhBrowseBchToOption    = oPmhBrowseBranch({
                'tReturnInputCode'  : 'oetBchCodeTo',
                'tReturnInputName'  : 'oetBchNameTo'
            });
            JCNxBrowseData('oPmhBrowseBchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });


    // Option Spl
    var tAPDAgnCode = '<?=$this->session->userdata('tSesUsrAgnCode')?>';
    var tWhereSpl   = '';
    if(tAPDAgnCode != ''){
        tWhereSpl += " AND ( TCNMSpl.FTAgnCode = '"+tAPDAgnCode+"' OR  ISNULL(TCNMSpl.FTAgnCode,'')=''  )  ";
    }
    var oAPDBrowseSpl   = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var oOptionReturn       = {
            Title: ['supplier/supplier/supplier', 'tSPLTitle'],
            Table: {Master:'TCNMSpl', PK:'FTSplCode'},
            Join: {
                Table: ['TCNMSpl_L', 'TCNMSplCredit' , 'VCN_VatActive'],
                On: [
                    'TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
                    'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode',
                    'TCNMSpl.FTVatCode = VCN_VatActive.FTVatCode'
                ]
            },
            Where:{
                Condition : ["AND TCNMSpl.FTSplStaActive = '1' "+tWhereSpl]
            },
            GrideView:{
                ColumnPathLang: 'supplier/supplier/supplier',
                ColumnKeyLang: ['tSPLTBCode', 'tSPLTBName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid', 'TCNMSpl.FTVatCode','VCN_VatActive.FCVatRate'],
                DataColumnsFormat: ['',''],
                DisabledColumns: [2, 3, 4, 5, 6 , 7],
                Perpage: 10,
                OrderBy: ['TCNMSpl.FDCreateOn DESC']
            },
            CallBack:{
                ReturnType	: 'S',
                Value       : [tInputReturnCode,"TCNMSpl.FTSplCode"],
                Text        : [tInputReturnName,"TCNMSpl_L.FTSplName"]
            },
        };
        return oOptionReturn;
    }

    // Event Browse Supplier From
    $('#obtAPDBrowseSplFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oAPDBrowseSplFromOption  = oAPDBrowseSpl({
                'tReturnInputCode'  : 'oetSplCodeFrom',
                'tReturnInputName'  : 'oetSplNameFrom'
            });
            JCNxBrowseData('oAPDBrowseSplFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Supplier To
    $('#obtAPDBrowseSplTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oAPDBrowseSplToOption    = oAPDBrowseSpl({
                'tReturnInputCode'  : 'oetSplCodeTo',
                'tReturnInputName'  : 'oetSplNameTo'
            });
            JCNxBrowseData('oAPDBrowseSplToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });




    /**
     * Functionality : Clear search data
     * Parameters : -
     * Creator : 03/03/2022 Wasin
     * Last Modified : -
     * Return : -
     * Return Type : -
    */
    function JSxAPDClearSearchData() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            try {
                var nCountBch   = "<?=$this->session->userdata("nSesUsrBchCount"); ?>";
                if(nCountBch != 1){ //ถ้ามีมากกว่า 1 สาขาต้อง reset 
                    $("#oetBchCodeFrom").val("");
                    $("#oetBchNameFrom").val("");
                    $("#oetBchCodeTo").val("");
                    $("#oetBchNameTo").val("");
                }

                $("#oetSearchAll").val("");
                $("#oetSearchDocDateFrom").val("");
                $("#oetSearchDocDateTo").val("");
                $(".xCNDatePicker").datepicker("setDate", null);
                $(".selectpicker").val("0").selectpicker("refresh");
                JSvCallPageAPDPdtDataTable();
            }catch (err) {
                console.log("JSxAPDClearSearchData Error: ", err);
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

</script>