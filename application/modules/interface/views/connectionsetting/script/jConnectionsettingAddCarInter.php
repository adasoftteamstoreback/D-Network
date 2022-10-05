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

        var nTaxSta = $('#ohdStaTax').val();
        $( ".xWSelectVat" ).each(function( index ) {
            if($(this).val() == nTaxSta){
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
    $('#oimBrowseCar').click(function(e){
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oBrowseCarOption = oBrowseCar({
                'tReturnInputCode'  : 'oetCssCarID',
                'tReturnInputName'  : 'oetCssCarName',
                'tReturnInputPlant'  : 'oetCssPlant',
                'tAgnCodeWhere'     : $('#oetCssAgnCode').val(),
            });
            JCNxBrowseData('oBrowseCarOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var tStaUsrLevel  = '<?=$this->session->userdata("tSesUsrLevel"); ?>';
    if(tStaUsrLevel == 'BCH' || tStaUsrLevel == 'SHP'){
        $('#oimBrowseAgn').attr("disabled", true);
    }


    //Option Car
    var oBrowseCar       = function(poReturnInput){
        var tInputReturnCode     = poReturnInput.tReturnInputCode;
        var tInputReturnName     = poReturnInput.tReturnInputName;
        var tInputReturnPlant    = poReturnInput.tReturnInputPlant;
        var tAgnCodeWhere        = poReturnInput.tAgnCodeWhere;
        var nUsedCar = $( ".xWUsedcar" ).length;
        var tWhereCAR = "";
        if(nUsedCar > 0){
            tWhereCAR += "AND TSVMCar.FTCarRegNo NOT IN (";
            $( ".xWUsedcar" ).each(function( index ) {
                tWhereCAR += "'"+$(this).val()+"'";
                if(index != (nUsedCar-1)){
                    tWhereCAR += ",";
                }
            });
            tWhereCAR += ")";
        }
        $nCountBCH = '<?=$this->session->userdata('nSesUsrBchCount')?>';

        var oOptionReturn       = {
            Title   : ['interface/connectionsetting/connectionsetting','tTABAgcCar'],
            Table   :{Master:'TSVMCar',PK:'FTCarCode'},
            Join :{
                Table   :	['TCNMCst_L'],
                On      :   [
                    'TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = '+ nLangEdits,
            ]
            },
            Where:{
                Condition : [tWhereCAR]
            },
            GrideView:{
                ColumnPathLang	: 'interface/connectionsetting/connectionsetting',
                ColumnKeyLang	: ['tBrowseInterCode','tBrowseInterreq','tBrowseInterName'],
                ColumnsSize     : ['15%','15%','70'],
                DataColumns		: ['TSVMCar.FTCarCode','TSVMCar.FTCarRegNo','TCNMCst_L.FTCstName','TSVMCar.FTCarOwner'],
                DataColumnsFormat : ['','','',''],
                DisabledColumns: [3,4],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TSVMCar.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TSVMCar.FTCarRegNo"],
                Text		: [tInputReturnName,"TCNMCst_L.FTCarRegNo"],
            },
            RouteAddNew : 'car',
            BrowseLev : 1,
            NextFunc: {
				FuncName: 'JSxCarOwnerInput',
				ArgReturn: ['FTCstName']
			}
        }
        return oOptionReturn;
    }

    function JSxCarOwnerInput(ptData){
        aData = JSON.parse(ptData);
        $('#oetCssCarOwner').val(aData); 
    }

</script>