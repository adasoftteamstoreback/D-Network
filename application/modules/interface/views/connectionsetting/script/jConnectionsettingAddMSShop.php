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

        var nBlueCard = $('#ohdBlueCard').val();
        $( ".xWSelectBlueCard" ).each(function( index ) {
            if($(this).val() == nBlueCard){
                $(this).attr("selected","selected");
            }
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

    //BrowseBch
        $('#oimBrowsePos').click(function(e){
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oBrowsePosOption = oBrowsePos({
                'tReturnInputCode'  : 'oetMSShopPosCode',
                'tReturnInputName'  : 'oetMSShopPosName',
                'tReturnInputPlant'  : 'oetCssPlant',
                'tAgnCodeWhere'     : $('#oetCssAgnCode').val(),
            });
            JCNxBrowseData('oBrowsePosOption');
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
                Condition : []
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

        //Option Pos
        var oBrowsePos       = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tBranCode = $('#oetUsrShopBchCode').val();
        var nUsedBrc = $( ".xWUsedBrc" ).length;
        var tCondition = ' AND TCNMPos.FTBchCode = ' + tBranCode;
        var tWhereBCH = "";
        var ncount = 0;
        var ncount2 = 0;
        var tposcon = "";

        $( ".xWUsedBrc" ).each(function( index ) {
            if($(this).val() == tBranCode){
                ncount++;
            }
        });
        if(ncount > 0){
            tCondition += " AND TCNMPos.FTPosCode NOT IN (";
            $( ".xWUsedBrc" ).each(function( index ) {
                if($(this).val() == tBranCode){
                    ncount2++;
                    tposcon = $("#odhUsedPos"+index).val();
                    tCondition += "'"+tposcon+"'";
                    if(ncount2 != (ncount)){
                        tCondition += ",";
                    }
                }
            });
            tCondition += ")";
        }
        // odhUsedPos
        var oOptionReturn       = {
            Title   : ['company/branch/branch','tBCHTitle'],
            Table   :{Master:'TCNMPos',PK:'FTPosCode'},
            Join :{
                Table   :	['TCNMPos_L'],
                On      :   [
                    'TCNMPos_L.FTPosCode = TCNMPos.FTPosCode AND TCNMPos_L.FTBchCode = TCNMPos.FTBchCode AND TCNMPos_L.FNLngID = '+ nLangEdits,
            ]
            },
            Where:{
                Condition : [tCondition]
            },
            GrideView:{
                ColumnPathLang	: 'company/branch/branch',
                ColumnKeyLang	: ['tBCHCode','tBCHName',],
                ColumnsSize     : ['15%','75%'],
                DataColumns		: ['TCNMPos.FTPosCode','TCNMPos_L.FTPosName','TCNMPos.FTBchCode'],
                DataColumnsFormat : ['','','',''],
                DisabledColumns: [2],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TCNMPos.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMPos.FTPosCode"],
                Text		: [tInputReturnName,"TCNMPos_L.FTPosName"],
            },
            RouteAddNew : 'pos',
            BrowseLev : 1
        }
        return oOptionReturn;
    }

    function JSxPlantInput(ptData){
        aData = JSON.parse(ptData);
        $('#oetCssPlant').val(aData); 
        $('#ohdCssPlant').val(aData); 
        $('#oimBrowsePos').prop( "disabled", false );
        $('#oetMSShopPosCode').val('');
        $('#oetMSShopPosName').val('');
    }

</script>