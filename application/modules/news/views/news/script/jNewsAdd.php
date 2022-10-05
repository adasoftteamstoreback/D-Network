<script>

        // Lang Edit In Browse
        var nLangEdits = <?php echo $this->session->userdata("tLangEdit")?>;


        $('#ocmNewToType').change(function(e){
            JSxNEWSetElementType();
        })

        function JSxNEWSetElementType(){
            var nNewSendType = $('#ocmNewToType').val();
            if(nNewSendType==1){
                $('.odvToBranch').show();
                $('.odvToAgency').hide();
            }else{
                $('.odvToBranch').hide();
                $('.odvToAgency').show();
            }
        }


        // Create By Witsarut 23/04/2020
        // Last Updated By Napat 07/05/2020 โปรเจค Kubota ไม่ใช้ Bch Multi
        // กำหนด 1 ผู้ใช้มีได้หลาย สาขา (Multi-select boxes)
        $('#oimBrowseBranchIn').unbind().click(function(){
            var nStaSession  = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oBchOption       =  undefined;
                oBchOption              = oBrowseBranch({
                    'tReturnInputBranchCode'    : 'oetBranchCodeIn',
                    'tReturnInputBranchName'    : 'oetBranchNameIn',
                    'tNextFuncName'             : 'JSxConsNextFuncBrowseUsrBranchIn',
                    'aArgReturn'                : ['FTBchCode','FTBchName'] //,'FTMerCode','FTMerName'
                });
                JCNxBrowseMultiSelect('oBchOption');
                // JCNxBrowseData('oBchOption');

            }else{
                JCNxShowMsgSessionExpired();
            }
        });


        // Create By Witsarut 23/04/2020
        // Last Updated By Napat 07/05/2020 โปรเจค Kubota ไม่ใช้ Bch Multi
        // กำหนด 1 ผู้ใช้มีได้หลาย สาขา (Multi-select boxes)
        $('#oimBrowseBranchEx').unbind().click(function(){
            var nStaSession  = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oBchOption       =  undefined;
                oBchOption              = oBrowseBranch({
                    'tReturnInputBranchCode'    : 'oetBranchCodeEx',
                    'tReturnInputBranchName'    : 'oetBranchNameEx',
                    'tNextFuncName'             : 'JSxConsNextFuncBrowseUsrBranchEx',
                    'aArgReturn'                : ['FTBchCode','FTBchName'] //,'FTMerCode','FTMerName'
                });
                JCNxBrowseMultiSelect('oBchOption');
                // JCNxBrowseData('oBchOption');

            }else{
                JCNxShowMsgSessionExpired();
            }
        });



                // Create By Witsarut 23/04/2020
        // Last Updated By Napat 07/05/2020 โปรเจค Kubota ไม่ใช้ Bch Multi
        // กำหนด 1 ผู้ใช้มีได้หลาย สาขา (Multi-select boxes)
        $('#oimBrowseAgencyIn').unbind().click(function(){
            var nStaSession  = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oAgnOption       =  undefined;
                oAgnOption              = oBrowseAgency({
                    'tReturnInputAgencyCode'    : 'oetAgencyCodeIn',
                    'tReturnInputAgencyName'    : 'oetAgencyNameIn',
                    'tNextFuncName'             : 'JSxConsNextFuncBrowseUsrAgencyIn',
                    'aArgReturn'                : ['FTAgnCode','FTAgnName'] //,'FTMerCode','FTMerName'
                });
                JCNxBrowseMultiSelect('oAgnOption');
                // JCNxBrowseData('oAgnOption');

            }else{
                JCNxShowMsgSessionExpired();
            }
        });


        // Create By Witsarut 23/04/2020
        // Last Updated By Napat 07/05/2020 โปรเจค Kubota ไม่ใช้ Agn Multi
        // กำหนด 1 ผู้ใช้มีได้หลาย สาขา (Multi-select boxes)
        $('#oimBrowseAgencyEx').unbind().click(function(){
            var nStaSession  = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oAgnOption       =  undefined;
                oAgnOption              = oBrowseAgency({
                    'tReturnInputAgencyCode'    : 'oetAgencyCodeEx',
                    'tReturnInputAgencyName'    : 'oetAgencyNameEx',
                    'tNextFuncName'             : 'JSxConsNextFuncBrowseUsrAgencyEx',
                    'aArgReturn'                : ['FTAgnCode','FTAgnName'] //,'FTMerCode','FTMerName'
                });
                JCNxBrowseMultiSelect('oAgnOption');
                // JCNxBrowseData('oAgnOption');

            }else{
                JCNxShowMsgSessionExpired();
            }
        });

       // Option Browse Branch
       var oBrowseBranch = function(poReturnInputBranch){
            let tInputReturnBranchCode   = poReturnInputBranch.tReturnInputBranchCode;
            let tInputReturnBranchName   = poReturnInputBranch.tReturnInputBranchName;
            let tBranchNextFunc          = poReturnInputBranch.tNextFuncName;
            let aBranchArgReturn         = poReturnInputBranch.aArgReturn;

            let tSesUsrBchCodeMulti     =  "<?=$this->session->userdata('tSesUsrBchCodeMulti')?>";
            let tSesUsrLevel            =  "<?=$this->session->userdata('tSesUsrLevel')?>";
            let tWhereCondiotion        = "";


            let oBranchOptionReturn      = {
                Title : ['authen/user/user','tBrowseBCHTitle'],
                Table :{Master:'TCNMBranch',PK:'FTBchCode'},
                Join :{
                    Table       : ['TCNMBranch_L'], //,'TCNMMerchant_L'
                    On          : [
                        'TCNMBranch.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits
                        // 'TCNMBranch.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits
                    ]
                },
                Where:{
                    Condition: [ tWhereCondiotion ]
                },
                Filter:{
                    Selector    : 'oetUsrAgnCode',
                    Table       : 'TCNMBranch',
                    Key         : 'FTAgnCode'
                },
                GrideView:{
                    ColumnPathLang	: 'authen/user/user',
                    ColumnKeyLang	: ['tBrowseBCHCode','tBrowseBCHName'],
                    ColumnsSize     : ['10%','75%'],
                    DataColumns	    : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'], //,'TCNMBranch.FTMerCode','TCNMMerchant_L.FTMerName'
                    DataColumnsFormat : ['',''],
                    // DisabledColumns	: [2,3],
                    WidthModal      : 50,
                    Perpage			: 10,
                    OrderBy			: ['TCNMBranch.FTBchCode DESC'],
                },
                NextFunc : {
                    FuncName  : tBranchNextFunc,
                    ArgReturn : aBranchArgReturn
                },
                CallBack:{
                    ReturnType	: 'S',
                    Value		: [tInputReturnBranchCode,"TCNMBranch.FTBchCode"],
                    Text		: [tInputReturnBranchName,"TCNMBranch_L.FTBchName"]
                },
            };
            return oBranchOptionReturn;
        }

       // Option Browse Agency
       var oBrowseAgency = function(poReturnInputAgency){
            let tInputReturnAgencyCode   = poReturnInputAgency.tReturnInputAgencyCode;
            let tInputReturnAgencyName   = poReturnInputAgency.tReturnInputAgencyName;
            let tAgencyNextFunc          = poReturnInputAgency.tNextFuncName;
            let aAgencyArgReturn         = poReturnInputAgency.aArgReturn;

            let oAgencyOptionReturn      = {
                Title : ['authen/user/user','tBrowseAgnTitle'],
                Table :{Master:'TCNMAgency',PK:'FTAgnCode'},
                Join :{
                    Table       : ['TCNMAgency_L'],
                    On          : [
                        'TCNMAgency.FTAgnCode = TCNMAgency_L.FTAgnCode AND TCNMAgency_L.FNLngID = '+nLangEdits
                    ]
                },
                GrideView:{
                    ColumnPathLang	: 'authen/user/user',
                    ColumnKeyLang	: ['tBrowseAgnCode','tBrowseAgnName'],
                    ColumnsSize     : ['10%','75%'],
                    DataColumns	    : ['TCNMAgency.FTAgnCode','TCNMAgency_L.FTAgnName'], //,'TCNMAgency.FTMerCode','TCNMMerchant_L.FTMerName'
                    DataColumnsFormat : ['',''],
                    // DisabledColumns	: [2,3],
                    WidthModal      : 50,
                    Perpage			: 10,
                    OrderBy			: ['TCNMAgency.FDCreateOn DESC'],
                },
                NextFunc : {
                    FuncName  : tAgencyNextFunc,
                    ArgReturn : aAgencyArgReturn
                },
                CallBack:{
                    ReturnType	: 'S',
                    Value		: [tInputReturnAgencyCode,"TCNMAgency.FTAgnCode"],
                    Text		: [tInputReturnAgencyName,"TCNMAgency_L.FTAgnName"]
                },
            };
            return oAgencyOptionReturn;
        }

        function JSxConsNextFuncBrowseUsrBranchIn(poDataNextFunc){

            $('#odvBranchShowIn').html('');
            if(typeof(poDataNextFunc[0])!= 'undefined' && poDataNextFunc[0] != null){ //poDataNextFunc[0] != "NULL"
                var tHtml = '';
                var tBchCodeStr = '';
                for($i=0; $i < poDataNextFunc.length; $i++ ){
                    var aText   = JSON.parse(poDataNextFunc[$i]);
                    tHtml       += '<span class="label label-info m-r-5">'+aText[1]+'</span>';

                }

                $('#odvBranchShowIn').html(tHtml);

            }

        }

        function JSxConsNextFuncBrowseUsrBranchEx(poDataNextFunc){

            $('#odvBranchShowEx').html('');
            if(typeof(poDataNextFunc[0])!= 'undefined' && poDataNextFunc[0] != null){ //poDataNextFunc[0] != "NULL"
                var tHtml = '';
                var tBchCodeStr = '';
                for($i=0; $i < poDataNextFunc.length; $i++ ){
                    var aText   = JSON.parse(poDataNextFunc[$i]);
                    tHtml       += '<span class="label label-info m-r-5">'+aText[1]+'</span>';

                }

                $('#odvBranchShowEx').html(tHtml);

            }

        }


        function JSxConsNextFuncBrowseUsrAgencyIn(poDataNextFunc){

            $('#odvAgencyShowIn').html('');
            if(typeof(poDataNextFunc[0])!= 'undefined' && poDataNextFunc[0] != null){ //poDataNextFunc[0] != "NULL"
                var tHtml = '';
                var tBchCodeStr = '';
                for($i=0; $i < poDataNextFunc.length; $i++ ){
                    var aText   = JSON.parse(poDataNextFunc[$i]);
                    tHtml       += '<span class="label label-info m-r-5">'+aText[1]+'</span>';

                }

                $('#odvAgencyShowIn').html(tHtml);

            }

        }

        function JSxConsNextFuncBrowseUsrAgencyEx(poDataNextFunc){

            $('#odvAgencyShowEx').html('');
            if(typeof(poDataNextFunc[0])!= 'undefined' && poDataNextFunc[0] != null){ //poDataNextFunc[0] != "NULL"
                var tHtml = '';
                var tBchCodeStr = '';
                for($i=0; $i < poDataNextFunc.length; $i++ ){
                    var aText   = JSON.parse(poDataNextFunc[$i]);
                    tHtml       += '<span class="label label-info m-r-5">'+aText[1]+'</span>';

                }

                $('#odvAgencyShowEx').html(tHtml);

            }

        }
</script>