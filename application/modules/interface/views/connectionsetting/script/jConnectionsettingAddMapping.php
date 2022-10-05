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

    //BrowseRcv 
    $('#oimBrowseRcv').click(function(e){
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oMPBrowseRcvOption = oBrowseRcv({
                'tReturnInputCode'  : 'oetMPCompar',
                'tReturnInputName'  : 'oetMPComparName',
            });
            JCNxBrowseData('oMPBrowseRcvOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });


    var tStaUsrLevel  = '<?=$this->session->userdata("tSesUsrLevel"); ?>';
    if(tStaUsrLevel == 'BCH' || tStaUsrLevel == 'SHP'){
        $('#oimBrowseAgn').attr("disabled", true);
    }

    //Option Rcv
        var oBrowseRcv =   function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        
        var oOptionReturn       = {
            Title : ['interface/connectionsetting/connectionsetting', 'tRcvTitle'],
            Table:{Master:'TFNMRcv', PK:'FTRcvCode'},
            Join :{
            Table: ['TFNMRcv_L'],
                On: ['TFNMRcv_L.FTRcvCode = TFNMRcv.FTRcvCode AND TFNMRcv_L.FNLngID = '+nLangEdits]
            },
            GrideView:{
                ColumnPathLang	: 'interface/connectionsetting/connectionsetting',
                ColumnKeyLang	: ['tRcvCode', 'tRcvName'],
                ColumnsSize     : ['15%', '85%'],
                WidthModal      : 50,
                DataColumns		: ['TFNMRcv.FTRcvCode', 'TFNMRcv_L.FTRcvName'],
                DataColumnsFormat : ['', ''],
                Perpage			: 10,
                OrderBy			: ['TFNMRcv.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TFNMRcv.FTRcvCode"],
                Text		: [tInputReturnName,"TFNMRcv_L.FTRcvName"],
            },
            RouteAddNew : 'Rcv',
            BrowseLev : 1,
            NextFunc: {
			}
        }
        return oOptionReturn;
    }

</script>