<script type="text/javascript">

    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit")?>;

    $(document).ready(function(){
      
        // event
        $("#oetCssBchCode").change(function(){
            JSxSltBrowseDisabled(); 
        });

        $('.selectpicker').selectpicker();

        $("#oetCssBchCode").change(function(){
            JSxSltBrowseDisabled(); 
        });

    });


    //ถ้า ไม่มีการเลือก สาขา Browse คลังจะถูกปิด
    function JSxSltBrowseDisabled(){
        var tBchCode = $('#oetCssBchCode').val();
        if(tBchCode == ''){
            $('#oimBrowseWah').attr('disabled', true);
            $('#oetCssWahCode').val('');
            $('#oetCssWahName').val('');
        }else{
            $('#oimBrowseWah').attr('disabled', false);
        }
    }

    //BrowseBch
    $('#oimBrowseBch').click(function(e){
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oBrowseBranchOption = oBrowseBch({
                'tReturnInputCode'  : 'oetUsrShopBchCode',
                'tReturnInputName'  : 'oetBchName',
                'tReturnInputPlant'  : 'oetCssPlant',
                'tAgnCodeWhere'     : $('#oetCssAgnCode').val(),
            });
            JCNxBrowseData('oBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var tStaUsrLevel  = '<?=$this->session->userdata("tSesUsrLevel"); ?>';
    if(tStaUsrLevel == 'BCH' || tStaUsrLevel == 'SHP'){
        $('#oimBrowseAgn').attr("disabled", true);
    }


    //Option Branch
    var oBrowseBch       = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tInputReturnPlant    = poReturnInput.tReturnInputPlant;
        var tAgnCodeWhere       = poReturnInput.tAgnCodeWhere;
        var nUsedBrc = $( ".xWUsedBrc" ).length;
        var tWhereBCH = "";
        if(nUsedBrc > 0){
            tWhereBCH += "AND TCNMBranch.FTBchCode NOT IN (";
            $( ".xWUsedBrc" ).each(function( index ) {
                tWhereBCH += "'"+$(this).val()+"'";
                if(index != (nUsedBrc-1)){
                    tWhereBCH += ",";
                }
            });
            tWhereBCH += ")";
        }
        $nCountBCH = '<?=$this->session->userdata('nSesUsrBchCount')?>';

        var oOptionReturn       = {
            Title   : ['company/branch/branch','tBCHTitle'],
            Table   :{Master:'TCNMBranch',PK:'FTBchCode'},
            Join :{
                Table   :	['TCNMBranch_L'],
                On      :   [
                    'TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+ nLangEdits,
            ]
            },
            Where:{
                Condition : [tWhereBCH]
            },
            GrideView:{
                ColumnPathLang	: 'company/branch/branch',
                ColumnKeyLang	: ['tBCHCode','tBCHName',],
                ColumnsSize     : ['15%','75%'],
                DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName','TCNMBranch.FTAgnCode','TCNMBranch.FTBchRefID'],
                DataColumnsFormat : ['','','',''],
                DisabledColumns: [2, 3],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TCNMBranch.FTBchCode DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"],
            },
            RouteAddNew : 'branch',
            BrowseLev : 1,
            NextFunc: {
				FuncName: 'JSxPlantInput',
				ArgReturn: ['FTBchRefID']
			}
        }
        return oOptionReturn;
    }

    function JSxPlantInput(ptData){
        aData = JSON.parse(ptData);
        $('#oetCssPlant').val(aData); 
        $('#ohdCssPlant').val(aData); 
    }

</script>