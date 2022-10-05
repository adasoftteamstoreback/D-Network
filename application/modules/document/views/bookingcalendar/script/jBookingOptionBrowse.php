<script>

    $(document).on('hidden.bs.modal', '#myModal', function () {
        if(nKeepBrowseMain != 1){ //ถ้าเป็น browse option ทั่วไป
            // $('#odvModalPopupBookingCalendar').modal('show');
            //$('body').append('<div class="modal-backdrop fade in"></div>').fadeIn(2000);
            nKeepBrowseMain = 0;
        }
    });

    //ภาษา
    var nLangEdits  = '<?=$this->session->userdata("tLangEdit")?>';
    var nLngID      = '<?=$this->session->userdata("tLangEdit")?>';

    ///////////////////////////////////////////////////////////////////////// เลือกตัวแทนขาย

    //เลือกตัวแทนขาย 
    $('#oetBookLastBrowseAgn').click(function(e) {
        nKeepBrowseMain = 0;
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

            //ซ่อนบราว์หลัก
            // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

            JSxCheckPinMenuClose();
            window.oBKBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetBookLastAgnCode',
                'tReturnInputName': 'oetBookLastAgnName',
            });
            setTimeout(function(){ 
                JCNxBrowseData('oBKBrowseAgencyOption');
            }, 500);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var oBrowseAgn = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;

        var oOptionReturn = {
            Title   : ['ticket/agency/agency', 'tAggTitle'],
            Table   : {
                Master  : 'TCNMAgency',
                PK      : 'FTAgnCode'
            },
            Join: {
                Table   : ['TCNMAgency_L'],
                On      : ['TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang  : 'ticket/agency/agency',
                ColumnKeyLang   : ['tAggCode', 'tAggName'],
                ColumnsSize     : ['15%', '85%'],
                WidthModal      : 50,
                DataColumns     : ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                Perpage         : 10,
                OrderBy         : ['TCNMAgency.FDCreateOn DESC'],
            },
            NextFunc:{
                FuncName        :'JSxNextFuncWhenSeletedAD'
            },
            CallBack    : {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text        : [tInputReturnName, "TCNMAgency_L.FTAgnName"],
            }
        }
        return oOptionReturn;
    }

    //หลังจากเลือกตัวแทนขาย
    function JSxNextFuncWhenSeletedAD(aReturn){
        $('#ohdBKFindBchCode').val('');
        $('#oetBKFindBchName').val('');

        $('#oetBookLastBchCode').val('');
        $('#oetBookLastBchName').val('');

        $('#oetBookBayCode').val('');
        $('#oetBookBayName').val('');

        $('#ohdBKWahouseFrom').val('');
        $('#oetBKWahouseFromName').val('');

        $('#ohdBKWahouseTo').val('');
        $('#oetBKWahouseToName').val('');
    }

    ///////////////////////////////////////////////////////////////////////// เลือกเหตุผล

    //เลือกเหตุผล
    $('#oetBookBrowseReason').unbind().click(function(){ 
        nKeepBrowseMain = 0;
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){

            //ซ่อนบราว์หลัก
            // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();
        
            JSxCheckPinMenuClose();
            window.oBKBrowseReasonOption  = oReasonOption({
                'tReturnInputCode'  : 'oetBookReasonCode',
                'tReturnInputName'  : 'oetBookReasonName'
            });
            setTimeout(function(){ 
                JCNxBrowseData('oBKBrowseReasonOption');
            }, 500);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //ตัวแปร Option Browse Modal เหตุผล
    var oReasonOption       = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var oOptionReturn       = {
            Title   : ["other/reason/reason","tRSNTitle"],
            Table   : {Master:"TCNMRsn",PK:"FTRsnCode"},
            Join    : {
                Table: ["TCNMRsn_L"],
                On: ["TCNMRsn.FTRsnCode = TCNMRsn_L.FTRsnCode AND TCNMRsn_L.FNLngID = '"+nLangEdits+"'"]
            },
            Where   : {
                Condition : ["  AND TCNMRsn.FTRsgCode = '020' "]
            },
            GrideView: {
                ColumnPathLang  : 'other/reason/reason',
                ColumnKeyLang   : ['tRSNTBCode','tRSNTBName'],
                ColumnsSize     : ['15%','75%'],
                WidthModal      : 50,
                DataColumns     : ['TCNMRsn.FTRsnCode','TCNMRsn_L.FTRsnName'],
                DataColumnsFormat: ['',''],
                Perpage         : 10,
                OrderBy         : ['TCNMRsn.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TCNMRsn.FTRsnCode"],
                Text        : [tInputReturnName,"TCNMRsn_L.FTRsnName"],
            },
            RouteAddNew     : 'reason',
            BrowseLev       : 0,
        };
        return oOptionReturn;
    }

    //เลือกเหตุผลในการยกเลิกติดตาม
    $('#oetBookBrowseReasonFlw').unbind().click(function(){ 
        nKeepBrowseMain = 1;
        JSxCheckPinMenuClose();
        
        setTimeout(function(){ 
            $('#odvBKModalCloseFollowPDT').modal("toggle")
        }, 500);

        window.oBKBrowseReasonFlwOption  = oReasonOptionFlw({
            'tReturnInputCode'  : 'oetBookReasonFlwCode',
            'tReturnInputName'  : 'oetBookReasonFlwName'
        });
        setTimeout(function(){ 
            JCNxBrowseData('oBKBrowseReasonFlwOption');
        }, 500);
    });

    //ตัวแปร Option Browse Modal เหตุผล
    var oReasonOptionFlw       = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var oOptionReturn       = {
            Title   : ["other/reason/reason","tRSNTitle"],
            Table   : {Master:"TCNMRsn",PK:"FTRsnCode"},
            Join    : {
                Table: ["TCNMRsn_L"],
                On: ["TCNMRsn.FTRsnCode = TCNMRsn_L.FTRsnCode AND TCNMRsn_L.FNLngID = '"+nLangEdits+"'"]
            },
            Where   : {
                Condition : ["  AND TCNMRsn.FTRsgCode = '020' "]
            },
            GrideView: {
                ColumnPathLang  : 'other/reason/reason',
                ColumnKeyLang   : ['tRSNTBCode','tRSNTBName'],
                ColumnsSize     : ['15%','75%'],
                WidthModal      : 50,
                DataColumns     : ['TCNMRsn.FTRsnCode','TCNMRsn_L.FTRsnName'],
                DataColumnsFormat: ['',''],
                Perpage         : 10,
                OrderBy         : ['TCNMRsn.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TCNMRsn.FTRsnCode"],
                Text        : [tInputReturnName,"TCNMRsn_L.FTRsnName"],
            },
            NextFunc:{
                FuncName    :'JSxNextFuncWhenSeleteReason',
                ArgReturn   : ['FTRsnCode']
            },
            RouteAddNew     : 'reason',
            BrowseLev       : 0,
        };
        return oOptionReturn;
    }

    //หลังจากเลือกเหตุผล
    function JSxNextFuncWhenSeleteReason(aReturn){
        $('#odvBKModalCloseFollowPDT').modal("toggle")
    }

    ///////////////////////////////////////////////////////////////////////// เลือกสาขา

    //เลือกสาขา
    $('#oetBookLastBrowseBch').unbind().click(function(){ 
        nKeepBrowseMain = 0;
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode    = $('#oetBookLastAgnCode').val();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){

            //ซ่อนบราว์หลัก
            // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();
        
            JSxCheckPinMenuClose();
            window.oBKBrowseBranchOption  = oBranchOption({
                'tReturnInputCode'  : 'oetBookLastBchCode',
                'tReturnInputName'  : 'oetBookLastBchName',
                'tAgnCode'          : tAgnCode,
                'aArgReturn'        : ['FTBchCode','FTBchName'],
            });
            setTimeout(function(){ 
                JCNxBrowseData('oBKBrowseBranchOption');
            }, 500);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //ตัวแปร Option Browse Modal สาขา
    var oBranchOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tAgnCode            = poDataFnc.tAgnCode;
        var aArgReturn          = poDataFnc.aArgReturn;

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if(tUsrLevel != "HQ"){
            tSQLWhereBch = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
        }

        if(tAgnCode != ""){
            tSQLWhereAgn = " AND TCNMBranch.FTAgnCode IN ("+tAgnCode+")";
        }
        
        var oOptionReturn       = {
            Title   : ['authen/user/user', 'tBrowseBCHTitle'],
            Table   : {
                Master  : 'TCNMBranch',
                PK      : 'FTBchCode'
            },
            Join: {
                Table   : ['TCNMBranch_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where : {
                Condition : [tSQLWhereBch,tSQLWhereAgn]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['', ''],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode'],
                SourceOrder         : "ASC"
            },
            NextFunc:{
                FuncName    :'JSxNextFuncWhenSeleteBCH',
                ArgReturn   : ['FTBchCode']
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text        : [tInputReturnName, "TCNMBranch_L.FTBchName"]
            },
        };
        return oOptionReturn;
    }

    //หลังจากเลือกสาขา
    function JSxNextFuncWhenSeleteBCH(aReturn){
        if(aReturn == '' || aReturn == 'NULL'){
            
        }else{

            if($('#otbBKPdtTemp tbody tr td').hasClass('xCNTextNotfoundDataPdtTable') != true){ //มีสินค้าในตาราง

                //ซ่อนบราว์หลัก
                $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

                //เปิด modal
                $('#odvBKModalChangeBCH').modal('show');

                //กดยืนยัน
                $('#odvBKModalChangeBCH .xCNBKModalChangeBCHConfirm').unbind().click(function(){

                    //ลบข้อมูล
                    JSxRemoveProductInTableTemp(0,'ALL');

                    setTimeout(function(){ 
                        JSxLoadTablePDTBookingCalendar();
                    }, 500);

                    //เปิด Modal หลัก
                    // $('#odvModalPopupBookingCalendar').modal('show');

                    //เคีลยร์ค่า
                    $('#oetBookBayCode').val('');
                    $('#oetBookBayName').val('');

                    $('#ohdBKWahouseFrom').val('');
                    $('#oetBKWahouseFromName').val('');

                    $('#ohdBKWahouseTo').val('');
                    $('#oetBKWahouseToName').val('');
                });

                //กดยกเลิก
                $('#odvBKModalChangeBCH .xCNBKModalChangeBCHClose').unbind().click(function(){
                    //เปิด Modal หลัก
                    // $('#odvModalPopupBookingCalendar').modal('show');
                });
            }else{
                //เคีลยร์ค่า
                $('#oetBookBayCode').val('');
                $('#oetBookBayName').val('');

                $('#ohdBKWahouseFrom').val('');
                $('#oetBKWahouseFromName').val('');

                $('#ohdBKWahouseTo').val('');
                $('#oetBKWahouseToName').val('');
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////// เลือกช่องให้บริการ

    //เลือกช่องให้บริการ
    $('#oetBookLastBrowseBay').unbind().click(function(){ 
        nKeepBrowseMain = 0;
        var nStaSession = JCNxFuncChkSessionExpired();
        var tAgnCode    = $('#oetBookLastAgnCode').val();
        var tBchCode    = $('#oetBookLastBchCode').val();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){

            //ซ่อนบราว์หลัก
            // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();
        
            JSxCheckPinMenuClose();
            window.oBKBrowsePosOption  = oPosOption({
                'tReturnInputCode'  : 'oetBookBayCode',
                'tReturnInputName'  : 'oetBookBayName',
                'tAgnCode'          : tAgnCode,
                'tBchCode'          : tBchCode,
                'aArgReturn'        : ['FTSpsCode','FTSpsName'],
            });
            setTimeout(function(){ 
                JCNxBrowseData('oBKBrowsePosOption');
            }, 500);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //ตัวแปร Option Browse Modal จุดให้บริการ
    var oPosOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tAgnCode            = poDataFnc.tAgnCode;
        var tBchCode            = poDataFnc.tBchCode;
        var aArgReturn          = poDataFnc.aArgReturn;

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhereBch = "";
        tSQLWhereAgn = "";

        if(tAgnCode != ""){
            tSQLWhereAgn = " AND TSVMPos.FTAgnCode IN ("+tAgnCode+")";
        }

        if(tBchCode != ""){
            tSQLWhereBch += " AND TSVMPos.FTBchCode = '"+tBchCode+"' ";
        }

        var oOptionReturn       = {
            Title   : ['service/calendar/calendar', 'tCLDTitle'],
            Table   : {
                Master  : 'TSVMPos',
                PK      : 'FTSpsCode'
            },
            Join: {
                Table   : ['TSVMPos_L'],
                On      : ['TSVMPos_L.FTBchCode = TSVMPos.FTBchCode AND TSVMPos_L.FTSpsCode = TSVMPos.FTSpsCode AND TSVMPos.FTAgnCode = TSVMPos_L.FTAgnCode AND TSVMPos_L.FNLngID = ' + nLangEdits]
            },
            Where : {
                Condition : [tSQLWhereBch,tSQLWhereAgn]
            },
            GrideView: {
                ColumnPathLang      : 'service/calendar/calendar',
                ColumnKeyLang       : ['tCLDCode', 'tCLDName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TSVMPos.FTSpsCode', 'TSVMPos_L.FTSpsName'],
                DataColumnsFormat   : ['', ''],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TSVMPos.FTSpsCode'],
                SourceOrder         : "ASC"
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TSVMPos.FTSpsCode"],
                Text        : [tInputReturnName, "TSVMPos_L.FTSpsName"]
            },
            //DebugSQL : true
        };
        return oOptionReturn;
    }

    ///////////////////////////////////////////////////////////////////////// เลือกคลังขาย

    //เลือกคลังขาย
    $('#obtBKWahouseFrm').click(function(e) {
        nKeepBrowseMain = 0;
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

            //ซ่อนบราว์หลัก
            $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

            JSxCheckPinMenuClose();
            window.oBKBrowseWahouseFrmOption = oBrowseWahouseFrmAndTo({
                'tReturnInputCode': 'ohdBKWahouseFrom',
                'tReturnInputName': 'oetBKWahouseFromName',
                'tCondition'      : 'AND TCNMWaHouse.FTWahStaType != 7'
            });
            setTimeout(function(){ 
                JCNxBrowseData('oBKBrowseWahouseFrmOption');
            }, 500);
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    ///////////////////////////////////////////////////////////////////////// เลือกคลังจอง

    //เลือกคลังจอง
    $('#obtBKWahouseTo').click(function(e) {
        nKeepBrowseMain = 0;
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

            //ซ่อนบราว์หลัก
            $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

            JSxCheckPinMenuClose();

            window.oBKBrowseWahouseToOption = oBrowseWahouseFrmAndTo({
                'tReturnInputCode': 'ohdBKWahouseTo',
                'tReturnInputName': 'oetBKWahouseToName',
                'tCondition'      : 'AND TCNMWaHouse.FTWahStaType = 7'
            });
            setTimeout(function(){ 
                JCNxBrowseData('oBKBrowseWahouseToOption');
            }, 500); 

        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //คลังขาย + คลังจอง
    var oBrowseWahouseFrmAndTo = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;
        var tCondition       = poReturnInput.tCondition;
        var tWhere           = " AND TCNMWaHouse.FTBchCode = '" + $('#oetBookLastBchCode').val() + "' ";

        //ถ้าเป็นคลังจอง ต้องเอาแต่ Type 7
        if(tCondition != ''){
            tWhere += tCondition;
        }

        var oOptionReturn = {
            Title   : ['company/warehouse/warehouse', 'tWAHTitle'],
            Table   : {
                Master: 'TCNMWaHouse',
                PK: 'FTWahCode'
            },
            Join: {
                Table   : ['TCNMWaHouse_L'],
                On      : ['TCNMWaHouse_L.FTWahCode = TCNMWaHouse.FTWahCode AND TCNMWaHouse_L.FTBchCode = TCNMWaHouse.FTBchCode AND TCNMWaHouse_L.FNLngID = ' + nLangEdits, ]
            },
            Where: {
                Condition: [tWhere]
            },
            GrideView: {
                ColumnPathLang  : 'company/warehouse/warehouse',
                ColumnKeyLang   : ['tWahCode', 'tWahName'],
                ColumnsSize     : ['15%', '75%'],
                WidthModal      : 50,
                DataColumns     : ['TCNMWaHouse.FTWahCode', 'TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat: ['', ''],
                Perpage         : 10,
                OrderBy         : ['TCNMWaHouse.FTWahCode'],
                SourceOrder     : "ASC"
            },
            CallBack: {
                ReturnType  : 'S',
                Value       : [tInputReturnCode, "TCNMWaHouse.FTWahCode"],
                Text        : [tInputReturnName, "TCNMWaHouse_L.FTWahName"],
            } 
        }
        return oOptionReturn;
    }
    ///////////////////////////////////////////////////////////////////////// เลือกลูกค้า

    //เลือกลูกค้า
    $('#obtBKBrowseCustomer').unbind().click(function(){

        //ซ่อนบราว์หลัก
        // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            nKeepBrowseMain = 0;
            JSxCheckPinMenuClose();
            window.oSOBrowseSplOption   = undefined;
            oSOBrowseCstOption          = oCstOption({
                'tReturnInputCode'  : 'oetBKCusCode',
                'tReturnInputName'  : 'oetBKCusName',
                'tNextFuncName'     : 'JSxWhenSeletedCustomer',
                'aArgReturn'        : ['FTCstCode', 'FTCstName','FTCstCardID','FTCstTel','FTCstEmail']
            });
            setTimeout(function(){ 
                JCNxBrowseData('oSOBrowseCstOption');
            }, 500);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oCstOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var oOptionReturn       = {
            Title   : ['customer/customer/customer', 'tCSTTitle'],
            Table   : {Master:'TCNMCst', PK:'FTCstCode'},
            Join    : {
                Table: ['TCNMCst_L', 'TCNMCstCredit'],
                On: [
                    'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = '+nLngID,
                    'TCNMCst_L.FTCstCode = TCNMCstCredit.FTCstCode'
                ]
            },
            Where:{
                Condition : ["AND TCNMCst.FTCstStaActive = '1' "]
            },
            GrideView:{
                ColumnPathLang      : 'customer/customer/customer',
                ColumnKeyLang       : ['tCSTCode', 'tCSTName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName','TCNMCst.FTCstCardID','TCNMCst.FTCstTel' , 'TCNMCst.FTCstEmail'],
                DataColumnsFormat   : ['',''],
                DisabledColumns     : [2, 3, 4, 5],
                Perpage             : 10,
                OrderBy             : ['TCNMCst_L.FTCstCode DESC']
            },
            CallBack:{
                ReturnType: 'S',
                Value   : [tInputReturnCode,"TCNMCst.FTCstCode"],
                Text    : [tInputReturnName,"TCNMCst_L.FTCstName"]
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            }
        };
        return oOptionReturn;
    }
    
    //หลังจากเลือกลูกค้า
    function JSxWhenSeletedCustomer(aReturn){
        //เปิดบราว์หลัก
        // $('#odvModalPopupBookingCalendar').modal('show');
        // $('body').append('<div class="modal-backdrop fade in"></div>').fadeIn(2000);
        // setTimeout(function(){ 
        //     $('body').append('<div class="modal-backdrop fade in"></div>').fadeIn(2000);
        // }, 500);

        //รูปภาพที่เป็นสีซ่อนไปก่อน
        $('.xCNImageCarTypeColor').hide();
        $('.xCNImageCar').show();

        $('#oetBKCarCode , #oetBKCarName').val('');
        //remove image
        $('.xCNImageCar').attr('src','<?=base_url().'/application/modules/common/assets/images/logo/fitauto.jpg'?>');
        $('.xCNImageCar').css('opacity','0.2');

        if(aReturn == '' || aReturn == 'NULL'){
            $('#obtBKBrowseCar').attr('disabled',true);
        }else{
            $('#obtBKBrowseCar').attr('disabled',false);
            aDataNextFunc   = JSON.parse(aReturn);
            var tCstTel     = aDataNextFunc[3];
            var tCstEmail   = aDataNextFunc[4];
            $('#oetBKTelephone').val(tCstTel);
            $('#oetBKEmail').val(tCstEmail);

            //หาว่า ลูกค้าคนนี้ มีรถอะไร
            JSxBKFindCarOnlyByCstCode(aDataNextFunc[0])
        }
    }   

    //หาว่าลูกค้าคนนี้มีรถอะไร ควรเอามา default
    function JSxBKFindCarOnlyByCstCode(tCstCode){
        $.ajax({
            type    : "POST",
            url     : "docBookingFindCar",
            data    : {'tCstCode' : tCstCode },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                var aResult = JSON.parse(tResult);
                if(aResult.tRetrunStatus == 1){
                    $('#oetBKCarName').val(aResult.aResult[0].FTCarRegNo);
                    $('#oetBKCarCode').val(aResult.aResult[0].FTCarCode);

                    //ข้อมูลหลังเลือกรถ
                    var aInformationCar = [aResult.aResult[0].FTCarCode , aResult.aResult[0].FTCarRegNo , aResult.aResult[0].FTImgObj];
                    var aInformationCar = JSON.stringify(aInformationCar);
                    JSxWhenSeletedCar(aInformationCar);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    ///////////////////////////////////////////////////////////////////////// เลือกรถ

    //เลือกรถ
    $('#obtBKBrowseCar').unbind().click(function(){

        //ซ่อนบราว์หลัก
        // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            nKeepBrowseMain = 0;
            JSxCheckPinMenuClose();
            window.oBrowseCarOption   = undefined;
            oBrowseCarOption          = oCarOption({
                'tReturnInputCode'  : 'oetBKCarCode',
                'tReturnInputName'  : 'oetBKCarName',
                'tNextFuncName'     : 'JSxWhenSeletedCar',
                'aArgReturn'        : ['FTCarCode', 'FTCarRegNo','FTImgObj']
            });
            setTimeout(function(){ 
                JCNxBrowseData('oBrowseCarOption');
            }, 500);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oCarOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tNextFuncName       = poDataFnc.tNextFuncName;
        var aArgReturn          = poDataFnc.aArgReturn;
        var oOptionReturn       = {
            Title   : ['document/bookingcalendar/bookingcalendar', 'tBKTitleCar'],
            Table   : {Master:'TSVMCar', PK:'FTCarCode'},
            Join    : {
                Table   : ['TCNMImgObj','TCNMCst_L'],
                On      : ["TCNMImgObj.FTImgRefID = TSVMCar.FTCarCode AND TCNMImgObj.FTImgTable = 'TSVMCar' ","TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = "+nLangEdits ]
            },
            Where   : {
                Condition : ["AND TSVMCar.FTCarOwner = '" + $('#oetBKCusCode').val() + "'"]
            },
            GrideView:{
                ColumnPathLang      : 'document/bookingcalendar/bookingcalendar',
                ColumnKeyLang       : ['tBKTitleCarCode', 'tBKTitleCarID', 'tBKOwnerCstName'],
                ColumnsSize         : ['15%', '15%', '60%'],
                WidthModal          : 50,
                DataColumns         : ['TSVMCar.FTCarCode', 'TSVMCar.FTCarRegNo', 'TCNMCst_L.FTCstName','TCNMImgObj.FTImgObj'],
                DataColumnsFormat   : ['','',''],
                DisabledColumns     : [3],
                Perpage             : 10,
                OrderBy             : ['TSVMCar.FTCarCode ASC']
            },
            CallBack:{
                ReturnType: 'S',
                Value   : [tInputReturnCode,"TSVMCar.FTCarOwner"],
                Text    : [tInputReturnName,"TSVMCar.FTCarRegNo"]
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            },
            //DebugSQL : true
        };
        return oOptionReturn;
    }

    //หลังจากเลือกรถ
    function JSxWhenSeletedCar(aReturn){
        //เปิดบราว์หลัก
        // $('#odvModalPopupBookingCalendar').modal('show');

        if(typeof(aReturn) != undefined && aReturn != "NULL"){
            aDataNextFunc   = JSON.parse(aReturn);
            var tImageCar   = aDataNextFunc[2];
            $('.xCNImageCar').show();
            if(aReturn == '' || aReturn == 'NULL'){
                $('.xCNImageCar').attr('src','<?=base_url().'/application/modules/common/assets/images/logo/fitauto.jpg'?>');
                $('.xCNImageCar').css('opacity','0.2');
            }else{
                if(tImageCar == '' || tImageCar == null || tImageCar == 'NULL'){
                    $('.xCNImageCar').attr('src','<?=base_url().'/application/modules/common/assets/images/logo/fitauto.jpg'?>');
                    $('.xCNImageCar').css('opacity','0.2');
                }else{
                    if(tImageCar.substring(0, 1) == '#'){ //ถ้าเป็นสี
                        $('.xCNImageCar').hide();
                        $('.xCNImageCarInCalendar').html('');
                        $('.xCNImageCarInCalendar').append('<div class="text-center xCNImageCarTypeColor"><span style="margin-top: 8px;height:170px;width:400px;background-color:'+aDataNextFunc[2]+';display:inline-block;line-height:2.3;"></span></div>');
                    }else{ //ถ้าเป็นรูปภาพ
                        $('.xCNImageCarInCalendar').html('');
                        $('.xCNImageCarInCalendar').append('<img class="xCNImageCar"></img>');
                        $('.xCNImageCar').attr('src',aDataNextFunc[2]);
                        $('.xCNImageCar').css('opacity','1');
                    }
                }
            }

            //โหลดข้อมูล follow
            JSxLoadTableHistoryService();
        }
    }

    ///////////////////////////////////////////////////////////////////////// เลือกสินค้า 

    //เลือกสินค้า
    $('#obtBKEventAddPDTToTemp').unbind().click(function(){

        //ต้องเลือกคลังปลายทางเสมอ
        if($('#oetBKWahouseToName').val() == '' || $('#oetBKWahouseToName').val() == null){
            // $('#oetBKWahouseToName').focus();
            alert('ไม่พบประเภทคลังจอง กรุณาตั้งค่าที่หน้าจอคลังสินค้า');
            return;
        }

        // $('#odvModalPopupBookingCalendar .xCNCloseModal').click();

        setTimeout(function(){ 

            var aDataNotINItem = [];
            var tPDTNotIN      = '';
            $("#odvContentPDTBookingCalendar tbody tr").each(function( index ) {
                bCheckPDT = $(this).find( "td:eq(1)").hasClass('xCNClassPDTCode');
                if(bCheckPDT == true){
                    var tPdtCodeSet = [$(this).find( "td:eq(1)").text(),""];
                    aDataNotINItem.push(tPdtCodeSet);
                }
            });

            //อนุญาต "ซื้อ" ที่หน่วย และ อนุญาต "ซื้อ" ที่บาร์โค๊ด
            var aWhereItem      = [];
            tPDTAlwSale         = ' AND (PPCZ.FTPdtStaAlwSale = 1 ';
            aWhereItem.push(tPDTAlwSale);

            tPDTAlwSale         = " OR ISNULL(PPCZ.FTPdtStaAlwSale,null) = null ) ";
            aWhereItem.push(tPDTAlwSale);

            tPDTAlwSale         = ' AND (PBAR.FTBarStaAlwSale = 1 ';
            aWhereItem.push(tPDTAlwSale);

            tPDTAlwSale         = " OR ISNULL(PBAR.FTBarStaAlwSale,null) = null ) ";
            aWhereItem.push(tPDTAlwSale);

            $.ajax({
                type    : "POST",
                url     : "BrowseDataPDT",
                data    : {
                    Qualitysearch   : [],
                    PriceType       : ["Cost","tCN_Cost","Company","1"],
                    SelectTier      : ["Barcode"],
                    ShowCountRecord : 10,
                    NextFunc        : "FSvBKNextFuncB4ToTemp",
                    ReturnType      : "M",
                    SPL             : ['',''],
                    BCH             : [$('#oetBookLastBchCode').val(),$('#oetBookLastBchCode').val()],
                    MCH             : ['',''],
                    SHP             : ['',''],
                    Where           : aWhereItem,
                    PDTService      : true,
                    aAlwPdtType     : ['T1','T2','T3','S2','S5'],
                    NOTINITEM       : aDataNotINItem
                },
                cache   : false,
                timeout : 0,
                success : function(tResult){
                    $("#odvModalDOCPDT").modal({backdrop: "static", keyboard: false});
                    $("#odvModalDOCPDT").modal({show: true});

                    //remove localstorage
                    localStorage.removeItem("LocalItemDataPDT");
                    $("#odvModalsectionBodyPDT").html(tResult);
                    $("#odvModalDOCPDT #oliBrowsePDTSupply").css('display','none');
                },
                error: function (jqXHR,textStatus,errorThrown){
                    JCNxResponseError(jqXHR,textStatus,errorThrown);
                }
            });
        }, 500);
    });

    //หลังจากเลือกสินค้า
    function FSvBKNextFuncB4ToTemp(poParam){
        if(poParam == '' || poParam == 'NULL'){
        }else{
            $.ajax({
                type    : "POST",
                url     : "docBookingCalendarEventInsertToDT",
                data    : {
                    "poItem"            : poParam , 
                    "tBchCode"          : $('#ohdBKFindBchCode').val() , 
                    "tAgnCode"          : $('#ohdBKFindADCode').val() , 
                    "tDocumentNumber"   : $('#ohdNameDocumentBooking').val() ,
                    "tWahFrm"           : $('#ohdBKWahouseFrom').val() ,
                    "tWahTo"            : $('#ohdBKWahouseTo').val()
                },
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    var aReturn = JSON.parse(tResult);

                    if(aReturn.nStatusRender == 1){//คือมีสินค้าชุด รอ insert
                        //จะวิ่งเข้าไปเช็คว่าสินค้าตัวไหนบ้างที่เป็น สินค้าชุด + สินค้าบำรุงรักษา
                        var oOptionForSet = {
                            'tKeyInTemp'        : 'TSVTBookDT',
                            "tBchCode"          : $('#ohdBKFindBchCode').val(),
                            "tAgnCode"          : $('#ohdBKFindADCode').val() , 
                            "tDocumentNumber"   : ($('#ohdNameDocumentBooking').val() == '' ) ? 'DUMMY' : $('#ohdNameDocumentBooking').val()
                        }
                        JSxCheckProductSetOrSVSet(oOptionForSet).then(function(res) {
                            var oReturn = JSON.parse(res);
                            // if(oReturn.nStatus > 0){
                            //     JSxLoadTablePDTBookingCalendar();
                            // }

                            JSxLoadTablePDTBookingCalendar();
                        });

                    }else{ //ไม่มีสินค้าชุด
                        JSxLoadTablePDTBookingCalendar();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //ถ้ากดปิด แต่ ขี้นว่าสต๊อกไม่พอ ต้องรีเฟรช
    $('#odvModalPopupBookingCalendar .xCNCloseModal').unbind().click(function(){ 
        var bCNStockIsNotNull = $('.xCNTextBookingFail').hasClass('xCNSaveButStockIsNotNull');
        if(bCNStockIsNotNull == true){
            JSvBKCallPageList();
        }
    });
</script>