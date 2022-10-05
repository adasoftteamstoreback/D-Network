<script type="text/javascript">
    var nAddressVersion = '<?=FCNaHAddressFormat('TCNMCst')?>';
    $('.selectpicker').selectpicker('refresh');
    //$("#oimPreBrowseCarRegNo").prop("disabled",true);
    $('.xCNDatePicker').datepicker({
        format                  : "yyyy-mm-dd",
        todayHighlight          : true,
        enableOnReadonly        : false,
        disableTouchKeyboard    : true,
        autoclose               : true
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    //////////////////////////////////////////////////////////////// อ้างอิงเอกสาร //////////////////////////////////////////////////////////////////

    $(document).ready(function(){
        JSxQTEventCheckShowHDDocRef();

        //อ้างอิงเอกสาร
        FSxQTCallPageHDDocRef();
    });

    //Default โชว์ panel ตามประเภท (ภายใน หรือ ภายนอก)
    function JSxQTEventCheckShowHDDocRef(){
        var tQTRefType = $('#ocbQTRefType').val();
        if( tQTRefType == '1' ){
            $('.xWShowRefExt').hide();
            $('.xWShowRefInt').show();
        }else{
            $('.xWShowRefInt').hide();
            $('.xWShowRefExt').show();
        }
    }

    //เมื่อเปลี่ยน ประเภท (ภายใน หรือ ภายนอก)
    $('#ocbQTRefType').off('change').on('change',function(){
        $(this).selectpicker('refresh');
        JSxQTEventCheckShowHDDocRef();
    });

    //โหลด Table อ้างอิงเอกสารทั้งหมด
    function FSxQTCallPageHDDocRef(){
        var tDocNo  = "";
        if ($("#ohdTQRoute").val() == "docQuotationEventEdit") {
            tDocNo = $('#ohdTQDocNo').val();
        }

        $.ajax({
            type    : "POST",
            url     : "docQuotationPageHDDocRef",
            data:{
                'ptDocNo' : tDocNo
            },
            cache   : false,
            timeout : 0,
            success: function(oResult){
                var aResult = JSON.parse(oResult);
                if( aResult['nStaEvent'] == 1 ){
                $('#odvQTTableHDRef').html(aResult['tViewPageHDRef']);
                    JCNxCloseLoading();
                }else{
                    var tMessageError = aResult['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กดเพิ่มเอกสารอ้างอิง (ภายใน ภายนอก)
    $('#obtQUTAddDocRef').off('click').on('click',function(){
        $('#ofmQTFormAddDocRef').validate().destroy();
        JSxQTEventClearValueInFormHDDocRef();
        $('#odvQTModalAddDocRef').modal('show');
    });

    //กดเลือกอ้างอิงเอกสารภายใน (ใบรับรถ , ใบสั่งาน)
    $('#obtQTBrowseRefDoc').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tQTRefType = $('#ocbQTRefDoc').val();
            if( tQTRefType == '1' ){ //ใบรับรถ ต้องเอาสินค้า
                JSxCallGetCarRefIntDoc();
            }else{
                let tBchCode    = $('#ohdTQBchCode').val();
                let tCstCode    = $('#ohdTQCustomerCode').val();
                let tCarCode    = $('#oetPreCarRegCode').val();
                // //ใบสั่งงาน ไม่ต้องเอาสินค้า
                window.oQTBrowseJOB1Option  = undefined;
                oQTBrowseJOB1Option         = oQTJOB1Option({
                    'tBchCode'          : tBchCode,
                    'tCstCode'          : tCstCode,
                    'tCarCode'          : tCarCode,
                    'tReturnInputCode'  : 'oetQTDocRefInt',
                    'tReturnInputName'  : 'oetQTDocRefIntName',
                    'tNextFuncName'     : 'JSxTQSelectDocumentRef',
                    'aArgReturn'        : [ 'FTXshDocNo','FDXshDocDate','FTCarCode',
                                            'FTCstCode', 'FTCstName', 'FTCstTel' , 
                                            'FTCstEmail' , 'FTCstTaxNo','FTAddV2Desc1' ]
                });
                JCNxBrowseData('oQTBrowseJOB1Option');
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //Browse => เอกสารใบรับรถ
    function JSxCallGetCarRefIntDoc(){
        var tBCHCode    = $('#ohdTQBchCode').val();
        var tBCHName    = $('#oetTQBchName').val();
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url : "docQuotationRefIntDoc",
            data: {
                'tBCHCode'      : tBCHCode,
                'tBCHName'      : tBCHName,
            },
            cache: false,
            Timeout: 0,
            success: function (oResult){
                JCNxCloseLoading();
                $('#odvQTFromRefIntDoc').html(oResult);
                $('#odvQTModalRefIntDoc').modal({backdrop : 'static' , show : true});
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Browse => เอกสารใบรับรถ เอาสินค้าใบรับรถลง Temp
    $('#obtConfirmRefDocInt').click(function(){
        var tRefIntDocNo    =  $('.xDocuemntRefInt.active').data('docno');
        var tRefIntDocDate  =  $('.xDocuemntRefInt.active').data('docdate');
        var tRefIntBchCode  =  $('.xDocuemntRefInt.active').data('bchcode');


        var nCountCheck     = $('.ocbRefIntDocDT:checked').length;
        if(nCountCheck == 0){
            alert('กรุณาเลือกรายการสินค้า ก่อนทำใบเสนอราคา');
            return;
        }else{
            var aSeqNo = $('.ocbRefIntDocDT:checked').map(function(elm){
                return $(this).val();
            }).get();

            $('#odvQTModalRefIntDoc').modal('hide');
            
            var tStaVATInOrEx   =  $('.xDocuemntRefInt.active').data('vatinroex');
            var tCstcode        =  $('.xDocuemntRefInt.active').data('cstcode');
            var tCstname        =  $('.xDocuemntRefInt.active').data('cstname');

            var poParams = {
                tCstcode           : tCstcode,
                tCstname           : tCstname,
                tCstTaxNo          : $('.xDocuemntRefInt.active').data('csttaxno'),
                tCstTel            : $('.xDocuemntRefInt.active').data('csttel'),
                tCstEmail          : $('.xDocuemntRefInt.active').data('cstemail'),
                tAddV2Desc1        : $('.xDocuemntRefInt.active').data('cstaddl'),
                tTypeRef           : 'Job1Req' 
            };
            JSxQTSetPanelCustomerAfterJOB1Data(poParams);
            
            $('#oetQTDocRefInt').val(tRefIntDocNo);
            $('#oetQTDocRefIntName').val(tRefIntDocNo);
            $('#oetQTRefDocDate').val(tRefIntDocDate);

            var poParamsCar = {
                'ptQTRefType'       : 1, // Type การอ้างอิงเอกสารภายใน ใบรับรถ
                'ptRefIntDocNo'     : tRefIntDocNo,
                'ptRefIntBchCode'   : tRefIntBchCode,
                'ptCstcode'         : tCstcode
            };
            JSxQTSetPanelCarDocRefAfterJOB1Data(poParamsCar);

            JCNxOpenLoading();
            $.ajax({
                type    : "POST",
                url     : "docQuotationRefIntDocInsertDTToTemp",
                data    : {
                    'tDocNo'            : $('#oetTQDocNo').val(),
                    'tFrmBchCode'       : $('#ohdTQBchCode').val(),
                    'tRefIntDocNo'      : tRefIntDocNo,
                    'tRefIntBchCode'    : tRefIntBchCode,
                    'aSeqNo'            : aSeqNo
                },
                cache: false,
                Timeout: 0,
                success: function (oResult){
                    
                    JSvTQLoadPdtDataTableHtml();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    });

    //Browse => หลังจากเลือกใบรับรถ เเล้วเอาอ้างอิงเอกสารมาใส่
    function JSxQTSetPanelCustomerAfterJOB1Data(paData){
        $('#ohdTQCustomerCode').val(paData.tCstcode);
        $('#oetTQCustomerName').val(paData.tCstname);
        $('#oetPanel_CustomerName').val(paData.tCstname);
        $('#oetPanel_CustomerTaxID').val((paData.tCstTaxNo == '') ? '-' : paData.tCstTaxNo);
        $('#oetPanel_CustomerTelephone').val((paData.tCstTel == '') ? '-' : paData.tCstTel);
        $('#oetPanel_CustomerEmail').val((paData.tCstEmail == '') ? '-' : paData.tCstEmail);
        $('#oetPanel_CustomerAddress').val((paData.tAddV2Desc1 == '') ? '-' : paData.tAddV2Desc1);
        $('#oetQTRefKey').val(paData.tTypeRef);
    }

    // ค้นหาข้อมูลรถจากเอกสารอ้างอิงใบรับรถ
    function JSxQTSetPanelCarDocRefAfterJOB1Data(poParamsCar){
        $.ajax({
            type    : "POST",
            url     : "docQuotationRefIntDocFindDocCarInfo",
            data    : poParamsCar,
            cache   : false,
            success: function (oResult){
                let aReturn = JSON.parse(oResult);
                if(aReturn['rtCode'] == '1'){
                    let aDataCarInfo    = aReturn['raItems'];
                    // Set Input Car Info Show
                    $('#oetPreCarRegCode').val(aDataCarInfo['FTCarCode']);
                    $('#oetPreCarRegName').val(aDataCarInfo['FTCarRegNo']);
                    $('#oetPreCarTypeName').val(aDataCarInfo['FTCarTypeName']);
                    $('#oetPreCarBrandName').val(aDataCarInfo['FTCarBrandName']);
                    $('#oetPreCarModelName').val(aDataCarInfo['FTCarModelName']);
                    $('#oetPreCarColorName').val(aDataCarInfo['FTCarColorName']);
                    $('#oetPreCarGearName').val(aDataCarInfo['FTCarGearName']);
                    // Set Input Car Info Hidden
                    $('#oetPreCarMiter').val(aDataCarInfo['FCXshCarMileage']);
                    $('#oetPreCarVIDRef').val(aDataCarInfo['FTCarVIDRef']);
                }else{
                    var tTextMsg    = aReturn['rtDesc'];
                    FSvCMNSetMsgErrorDialog('<p class="text-left">'+tTextMsg+'</p>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Browse => เลือกใบสั่งงาน
    var oQTJOB1Option   = function(poDataFnc){

        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tNextFuncName       = poDataFnc.tNextFuncName;
        let aArgReturn          = poDataFnc.aArgReturn;
        let tBchCode            = poDataFnc.tBchCode;
        let tCstCode            = poDataFnc.tCstCode;
        let tCarCode            = poDataFnc.tCarCode;

        var tWhereStaAll = " AND TSVTJob2OrdHD.FTXshStaDoc = '1' AND ( TSVTJob2OrdHD.FTXshStaClosed != '1' OR ISNULL(TSVTJob2OrdHD.FTXshStaClosed,'') = '' ) ";

        var tWhereBCH = '';
        if($('#ohdTQBchCode').val() != ''){
            var tWhereBCH = " AND TSVTJob2OrdHD.FTBchCode = '"+tBchCode+"' "
        }

        var tWhereCar = '';
        if($('#oetPreCarRegCode').val() != ''){
            var tWhereCar = "AND TSVMCar.FTCarCode = '" + $("#oetPreCarRegCode").val() + "'";
        }

        if(tCstCode != ''){
            var tWhereBCH = " AND TSVTJob2OrdHD.FTCstCode = '"+tCstCode+"' "
        }

        var tWhereAddL = "AND TCNMCst.FTCstStaActive = '1' AND (ISNULL(TCNMCstAddress_L.FTAddGrpType,'') = '' OR TCNMCstAddress_L.FTAddGrpType = '1' "

        if( '<?=@$nStaShwAddress?>' == 1 ){ //ที่อยู่แบบรวม
            tWhereAddL += "  AND TCNMCstAddress_L.FTAddVersion = 1 "
        }else{ //ที่อยู่แบบแยก
            tWhereAddL += "  AND TCNMCstAddress_L.FTAddVersion = 2 "
        }

        tWhereAddL += " ) ";

        var oOptionReturn       = {
            Title   : ['document/purchaseorder/purchaseorder', 'อ้างอิงใบสั่งงาน'],
            Table   : {Master:'TSVTJob2OrdHD', PK:'FTXshDocNo'},
            Join: {
                Table: ['TSVMCar','TCNMCst','TCNMCst_L','TCNMCstAddress_L'],
                On: [
                    'TSVTJob2OrdHD.FTCstCode = TSVMCar.FTCarOwner',
                    'TSVTJob2OrdHD.FTCstCode = TCNMCst.FTCstCode',
                    'TSVTJob2OrdHD.FTCstCode = TCNMCst_L.FTCstCode AND TCNMCst_L.FNLngID =' + nLangEdits,
                    'TCNMCst_L.FTCstCode = TCNMCstAddress_L.FTCstCode AND TCNMCstAddress_L.FNLngID = '+nLangEdits
                ]
            },
            Where:{
                Condition       : [tWhereBCH,tWhereCar,tWhereStaAll,tWhereAddL]
            },
            GrideView:{
                ColumnPathLang      : 'document/purchaseorder/purchaseorder',
                ColumnKeyLang       : ['tPOTBDocNo', 'tPOTBDocDate'],
                ColumnsSize         : ['30%', '70%'],
                WidthModal          : 50,
                DataColumns         : [
                    'TSVTJob2OrdHD.FTXshDocNo', 
                    'TSVTJob2OrdHD.FDXshDocDate' , 
                    'TSVMCar.FTCarCode' , 
                    'TCNMCst_L.FTCstCode', 
                    'TCNMCst_L.FTCstName', 
                    'TCNMCst.FTCstTel' , 
                    'TCNMCst.FTCstEmail' , 
                    'TCNMCst.FTCstTaxNo',
                    'TCNMCstAddress_L.FTAddV2Desc1' ],
                DataColumnsFormat   : ['',''],
                DisabledColumns     : [2,3,4,5,6,7,8],
                Perpage             : 10,
                OrderBy             : ['TSVTJob2OrdHD.FTXshDocNo DESC']
            },
            CallBack:{
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TSVTJob2OrdHD.FTXshDocNo"],
                Text        : [tInputReturnName,"TSVTJob2OrdHD.FTXshDocNo"]
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            }
        };
        return oOptionReturn;
    }

    //Browse => หลังจากเลือกเอกสารใบสั่งงาน
    function JSxTQSelectDocumentRef(poDataNextFunc){
        var aData;
        if (poDataNextFunc  != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            var dDataJOBDoc = aData[1].split(" ");
            $('#oetQTRefDocDate').val(dDataJOBDoc[0]);
            $('#oetQTRefKey').val('Job2Ord');

            //เอาข้อมูลลูกค้าไปใส่
            var poParams = {
                tCstcode           : aData[3],
                tCstname           : aData[4],
                tCstTaxNo          : aData[7],
                tCstTel            : aData[5],
                tCstEmail          : aData[6],
                tAddV2Desc1        : aData[8],
                tTypeRef           : 'Job2Ord'
            };
            JSxQTSetPanelCustomerAfterJOB1Data(poParams);

            //เอาข้อมูลรถมาใส่
            JSxQTSetPanelCarWhenSelectJob2(aData[2]);
        }
    }

    // ค้นหาข้อมูลรถ
    function JSxQTSetPanelCarWhenSelectJob2(poParamsCar){
        $.ajax({
            type    : "POST",
            url     : "docQuotationFindCarInfo",
            data    : { 'tCarCode' : poParamsCar },
            cache   : false,
            success: function (oResult){
                let aReturn = JSON.parse(oResult);
                if(aReturn['rtCode'] == '1'){
                    let aDataCarInfo    = aReturn['raItems'];
                    // Set Input Car Info Show
                    $('#oetPreCarRegCode').val(aDataCarInfo['FTCarCode']);
                    $('#oetPreCarRegName').val(aDataCarInfo['FTCarRegNo']);
                    $('#oetPreCarTypeName').val(aDataCarInfo['FTCarTypeName']);
                    $('#oetPreCarBrandName').val(aDataCarInfo['FTCarBrandName']);
                    $('#oetPreCarModelName').val(aDataCarInfo['FTCarModelName']);
                    $('#oetPreCarColorName').val(aDataCarInfo['FTCarColorName']);
                    $('#oetPreCarGearName').val(aDataCarInfo['FTCarGearName']);
                    // Set Input Car Info Hidden
                    $('#oetPreCarMiter').val(aDataCarInfo['FCXshCarMileage']);
                    $('#oetPreCarVIDRef').val(aDataCarInfo['FTCarVIDRef']);
                }else{
                    var tTextMsg    = aReturn['rtDesc'];
                    FSvCMNSetMsgErrorDialog('<p class="text-left">'+tTextMsg+'</p>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //กดยืนยันบันทึกลง Temp
    $('#ofmQTFormAddDocRef').off('click').on('click',function(){
        $('#ofmQTFormAddDocRef').validate().destroy();
        $('#ofmQTFormAddDocRef').validate({
            focusInvalid    : false,
            onclick         : false,
            onfocusout      : false,
            onkeyup         : false,
            rules           : {
                oetQTRefDocNo    : {"required" : true}
            },
            messages: {
                oetQTRefDocNo    : {"required" : 'กรุณากรอกเลขที่เอกสารอ้างอิง'}
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                error.addClass("help-block");
                if(element.prop("type") === "checkbox") {
                    error.appendTo(element.parent("label"));
                }else{
                    var tCheck  = $(element.closest('.form-group')).find('.help-block').length;
                    if(tCheck == 0) {
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function (form){
                JCNxOpenLoading();

                if($('#ocbQTRefType').val() == 1){ //อ้างอิงเอกสารภายใน
                    var tDocNoRef = $('#oetQTDocRefInt').val();
                }else{ //อ้างอิงเอกสารภายนอก
                    var tDocNoRef = $('#oetQTRefDocNo').val();
                }

                $.ajax({
                    type    : "POST",
                    url     : "docQuotationEventAddEditHDDocRef",
                    data    : {
                        'ptRefDocNoOld'     : $('#oetQTRefDocNoOld').val(),
                        'ptQTDocNo'         : $('#oetTQDocNo').val(),
                        'ptRefType'         : $('#ocbQTRefType').val(),
                        'ptRefDocNo'        : tDocNoRef,
                        'pdRefDocDate'      : $('#oetQTRefDocDate').val(),
                        'ptRefKey'          : $('#oetQTRefKey').val()
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult){
                        JSxQTEventClearValueInFormHDDocRef();
                        $('#odvQTModalAddDocRef').modal('hide');

                        FSxQTCallPageHDDocRef();
                        JCNxCloseLoading();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    });

    //เคลียร์ค่า
    function JSxQTEventClearValueInFormHDDocRef(){
        $('#oetQTRefDocNo').val('');
        $('#oetQTRefDocDate').val('');
        $('#oetQTDocRefInt').val('');
        $('#oetQTDocRefIntName').val('');
        $('#oetQTRefKey').val('');
    }

    //////////////////////////////////////////////////////////////// เลือกลูกค้า //////////////////////////////////////////////////////////////////
    $('#obtTQBrowseCustomer').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oQTBrowseCstOption   = undefined;
            oQTBrowseCstOption          = oCstOption({
                'tReturnInputCode'  : 'ohdTQCustomerCode',
                'tReturnInputName'  : 'oetTQCustomerName',
                'tNextFuncName'     : 'JSxTQSetConditionAfterSelectCustomer',
                'aArgReturn'        : ['FTCstCode','FTCstName', 'FTCstTaxNo', 'FTCstTel', 'FTCstEmail','FTPplCode']
            });
            JCNxBrowseData('oQTBrowseCstOption');
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
                Table: ['TCNMCst_L','TCNMCstLev'],
                On: [
                    'TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = '+nLangEdits,
                    'TCNMCst.FTClvCode = TCNMCstLev.FTClvCode'
                ]
            },
            Where:{
                Condition           : ["AND TCNMCst.FTCstStaActive = '1'"]
            },
            GrideView:{
                ColumnPathLang      : 'customer/customer/customer',
                ColumnKeyLang       : ['tCSTCode', 'tCSTName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName','TCNMCst.FTCstTaxNo','TCNMCst.FTCstTel','TCNMCst.FTCstEmail' , 'TCNMCstLev.FTPplCode' ],
                DataColumnsFormat   : ['',''],
                DisabledColumns     : [2 , 3 , 4 , 5],
                Perpage             : 10,
                OrderBy             : ['TCNMCst_L.FTCstCode DESC']
            },
            CallBack:{
                ReturnType  : 'S',
                Value       : [tInputReturnCode,"TCNMCst.FTCstCode"],
                Text        : [tInputReturnName,"TCNMCst_L.FTCstName"]
            },
            NextFunc:{
                FuncName    : tNextFuncName,
                ArgReturn   : aArgReturn
            }
        };
        return oOptionReturn;
    }

    //หลังจากเลือกลูกค้า
    function JSxTQSetConditionAfterSelectCustomer(poDataNextFunc){
        var aData;
        if (poDataNextFunc  != "NULL") {
            aData = JSON.parse(poDataNextFunc);
            $.ajax({
                type    : "POST",
                url     : "GetAddressCustomer",
                data    :  {
                    'tCSTCode'       : aData[0],
                },
                catch   : false,
                timeout : 0,
                success : function (tResult){
                    aData2 = JSON.parse(tResult);

                    var tAddfull ='';
                    if(nAddressVersion == '1'){
                        tAddfull = aData2[0]['FTAddV1No']+' '+aData2[0]['FTAddV1Soi']+' '+aData2[0]['FTAddV1Village']+' '+aData2[0]['FTAddV1Road']+' '+aData2[0]['FTSudName']+' '+aData2[0]['FTDstName']+' '+aData2[0]['FTPvnName'];
                    }else if(nAddressVersion == '2'){
                        tAddfull = aData2[0]['FTAddV2Desc1']+' '+aData2[0]['FTAddV2Desc2'];
                    }else{
                        tAddfull = "-";
                    }

                    $('#oetPanel_CustomerName').val((aData[1] == '') ? '-' : aData[1]);
                    $('#oetPanel_CustomerTaxID').val((aData[2] == '') ? '-' : aData[2]);
                    $('#oetPanel_CustomerTelephone').val((aData[3] == '') ? '-' : aData[3]);
                    $('#oetPanel_CustomerEmail').val((aData[4] == '') ? '-' : aData[4]);
                    $('#oetPanel_CustomerAddress').val(tAddfull);

                    //ล้างค่าข้อมูลรถ
                    $("#oetPreCarRegName").val('');
                    $("#oetPrePvnName").val('');
                    $("#oetPreCarTypeName").val('');
                    $("#oetPreCarBrandName").val('');
                    $("#oetPreCarModelName").val('');
                    $("#oetPreCarColorName").val('');
                    $("#oetPreCarGearName").val('');
                    $("#oetPreCarVIDRef").val('');

                    //ชื่อลูกค้า
                    $("#ohdTQCustomerCode").val(aData[0]);
                    $("#oetTQCustomerName").val(aData[1]);

                    //กลุ่มราคา
                    $("#ohdTQCustomerPPLCode").val(aData[5]);
                },
                error: function (jqXHR,textStatus,errorThrown){

                }
            });
        }
    }

    //////////////////////////////////////////////////////////////// เลือกสกุลเงิน ////////////////////////////////////////////////////////////////
    $('#obtQTBrowseRate').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oQTBrowseRateOption   = undefined;
            oQTBrowseRateOption          = oRateOption({
                'tReturnInputCode'  : 'ohdQTRateCode',
                'tReturnInputName'  : 'oetQTRateName'
            });
            JCNxBrowseData('oQTBrowseRateOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oRateOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var oOptionReturn       = {
            Title   : ['payment/rate/rate','tRTETitle'],
            Table   : {Master:'TFNMRate',PK:'FTRteCode'},
            Join    : {
                Table : ['TFNMRate_L'],
                On : ['TFNMRate_L.FTRteCode = TFNMRate.FTRteCode AND TFNMRate_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'payment/rate/rate',
                ColumnKeyLang	: ['tRTETBRteCode','tRTETBRteName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TFNMRate.FTRteCode','TFNMRate_L.FTRteName'],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TFNMRate.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TFNMRate.FTRteCode"],
                Text		: [tInputReturnName,"TFNMRate_L.FTRteName"],
            },
        };
        return oOptionReturn;
    }

    //////////////////////////////////////////////////////////////// เลือกสาขา /////////////////////////////////////////////////////////////////
    $('#obtTQBrowseBranch').click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oTQBrowseBranchOption  = undefined;
            oTQBrowseBranchOption         = oBranchOption({
                'tReturnInputCode'  : 'ohdTQBchCode',
                'tReturnInputName'  : 'oetTQBchName'
            });
            JCNxBrowseData('oTQBrowseBranchOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oBranchOption = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;

        tUsrLevel = "<?=$this->session->userdata('tSesUsrLevel')?>";
        tBchMulti = "<?=$this->session->userdata("tSesUsrBchCodeMulti"); ?>";
        tSQLWhere = "";
        if(tUsrLevel != "HQ"){
            tSQLWhere = " AND TCNMBranch.FTBchCode IN ("+tBchMulti+") ";
        }

        // ตัวแปร ออฟชั่นในการ Return
        var oOptionReturn       = {
            Title       : ['authen/user/user', 'tBrowseBCHTitle'],
            Table       : {
                Master  : 'TCNMBranch',
                PK      : 'FTBchCode'
            },
            Join: {
                Table   : ['TCNMBranch_L'],
                On      : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where : {
                Condition : [tSQLWhere]
            },
            GrideView: {
                ColumnPathLang      : 'authen/user/user',
                ColumnKeyLang       : ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize         : ['10%', '75%'],
                DataColumns         : ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['',''],
                WidthModal          : 50,
                Perpage             : 10,
                OrderBy             : ['TCNMBranch.FTBchCode DESC']
            },
            CallBack: {
                ReturnType          : 'S',
                Value               : [tInputReturnCode, "TCNMBranch.FTBchCode"],
                Text                : [tInputReturnName, "TCNMBranch_L.FTBchName"]
            }
        };
        return oOptionReturn;
    }

    //////////////////////////////////////////////////////////////// ค้นหาสินค้าใน ///////////////////////////////////////////////////////////////

    //ค้นหาสินค้าใน temp
    function JSvDOCSearchPdtHTML() {
        var value = $("#oetSearchPdtHTML").val().toLowerCase();
        $("#otbTQDocPdtAdvTableList tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    //////////////////////////////////////////////////////////////// เลือกสินค้า //////////////////////////////////////////////////////////////////

    //สแกนบาร์โค๊ด
    function JSxSearchFromBarcode(e,elem){
        var tValue = $(elem).val();
        if($('#ohdTQCustomerCode').val() != ""){
            JSxCheckPinMenuClose();
            if(tValue.length === 0){

            }else{
                $('#oetTQInsertBarcode').attr('readonly',true);
                JCNSearchBarcodePdt(tValue);
                $('#oetTQInsertBarcode').val('');
            }
        }else{
            $('#odvTQModalPleseselectCustomer').modal('show');
            $('#oetTQInsertBarcode').val('');
        }
        e.preventDefault();
    }

    //ค้นหาบาร์โค๊ด
	function JCNSearchBarcodePdt(ptTextScan){
        var tWhereCondition = "";

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

        var tPplCode = $('#ohdTQCustomerPPLCode').val();
        
        $.ajax({
            type    : "POST",
            url     : "BrowseDataPDTTableCallView",
            data    :  {
                'Qualitysearch'       : [],
                'ReturnType'          : "S",
                'ShowCountRecord'     : 10,
                'aPriceType'          : ['Price4Cst',tPplCode],
                'NextFunc'            : "",
                'SelectTier'          : ["PDT"],
                'SPL'                 : '',
                'BCH'                 : $('#ohdTQBchCode').val(),
                'MCH'                 : '',
                'SHP'                 : '',
                'tWhere'              : aWhereItem,
                'tTextScan'           : ptTextScan,
                'tTYPEPDT'            : '',
                'tSNPDT'              : ''
            },
            catch   : false,
            timeout : 0,
            success : function (tResult){
                JCNxCloseLoading();
                var oText = JSON.parse(tResult);
                if(oText == '800'){
                    $('#oetTQInsertBarcode').attr('readonly',false);
                    $('#odvTQModalPDTNotFound').modal('show');
                    $('#oetTQInsertBarcode').val('');
                }else{
                    // พบสินค้ามีหลายบาร์โค้ด
                    if(oText.length > 1){
                        $('#odvTQModalPDTMoreOne').modal('show');
                        $('#odvTQModalPDTMoreOne .xCNTablePDTMoreOne tbody').html('');

                        for(i=0; i<oText.length; i++){
                            var aNewReturn      = JSON.stringify(oText[i]);
                            var tResult         = "["+aNewReturn+"]";
                            var oEncodePackData = window.btoa(unescape(encodeURIComponent(tResult)));
                            var tHTML = "<tr class='xCNColumnPDTMoreOne"+i+" xCNColumnPDTMoreOne' data-information='"+oEncodePackData+"' style='cursor: pointer;'>";
                                tHTML += "<td>"+oText[i].pnPdtCode+"</td>";
                                tHTML += "<td>"+oText[i].packData.PDTName+"</td>";
                                tHTML += "<td>"+oText[i].packData.PUNName+"</td>";
                                tHTML += "<td>"+oText[i].ptBarCode+"</td>";
                                tHTML += "</tr>";
                            $('#odvTQModalPDTMoreOne .xCNTablePDTMoreOne tbody').append(tHTML);
                        }

                        //เลือกสินค้า
                        $('.xCNColumnPDTMoreOne').off();

                        //ดับเบิ้ลคลิก
                        $('.xCNColumnPDTMoreOne').on('dblclick',function(e){
                            $('#odvTQModalPDTMoreOne').modal('hide');
                            var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                            JSxTQEventRenderTemp(tJSON); //Client
                            JSxTQEventInsertToTemp(tJSON); //Server
                        });

                        //คลิกได้เลย
                        $('.xCNColumnPDTMoreOne').on('click',function(e){
                            //เลือกสินค้าแบบตัวเดียว
                            $('.xCNColumnPDTMoreOne').removeClass('xCNActivePDT');
                            $('.xCNColumnPDTMoreOne').children().attr('style', 'background-color:transparent !important; color:#232C3D !important;');
                            $(this).addClass('xCNActivePDT');
                            $(this).children().attr('style', 'background-color:#1866ae !important; color:#FFF !important;');
                        });

                    }else{
                        //มีตัวเดียว
                        var aNewReturn  = JSON.stringify(oText);
                        JSxTQEventRenderTemp(aNewReturn); //Client
                        JSxTQEventInsertToTemp(aNewReturn); //Server
                    }
                }
            },
            error: function (jqXHR,textStatus,errorThrown){

            }
        });
    }

    //หลังจากค้นหาบาร์โค๊ด กรณีพบมากกว่าหนึ่งตัว
    function JCNxConfirmPDTMoreOne($ptType){
        if($ptType == 1){
            $("#odvTQModalPDTMoreOne .xCNTablePDTMoreOne tbody .xCNActivePDT").each(function( index ) {
                var tJSON = decodeURIComponent(escape(window.atob($(this).attr('data-information'))));
                JSxTQEventRenderTemp(tJSON); //Client
                JSxTQEventInsertToTemp(tJSON); //Server
            });
        }else{
            $('#oetTQInsertBarcode').attr('readonly',false);
            $('#oetTQInsertBarcode').val('');
        }
    }

    //เลือกสินค้า
    $('#obtTQDocBrowsePdt').unbind().click(function(){
        var dTime               = new Date();
        var dTimelocalStorage   = dTime.getTime();

        if($('#ohdTQCustomerCode').val() != ""){

        }else{
            $('#odvTQModalPleseselectCustomer').modal('show');
            return;
        }

        //อนุญาต "ซื้อ" ที่หน่วย และ อนุญาต "ขาย" ที่บาร์โค๊ด
        var aWhereItem      = [];
        tPDTAlwSale         = ' AND (PPCZ.FTPdtStaAlwSale = 1 ';
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = " OR ISNULL(PPCZ.FTPdtStaAlwSale,null) = null ) ";
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = ' AND (PBAR.FTBarStaAlwSale = 1 ';
        aWhereItem.push(tPDTAlwSale);

        tPDTAlwSale         = " OR ISNULL(PBAR.FTBarStaAlwSale,null) = null ) ";
        aWhereItem.push(tPDTAlwSale);

        var tPplCode = $('#ohdTQCustomerPPLCode').val();

        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                'Qualitysearch'   : [],
                'PriceType'       : ['Price4Cst',tPplCode],
                'SelectTier'      : ['PDT'],
                'ShowCountRecord' : 10,
                'NextFunc'        : 'JSxAfterChoosePDT',
                'ReturnType'      : 'M',
                'SPL'             : ['',''],
                'BCH'             : [$('#ohdTQBchCode').val(), $('#ohdTQBchCode').val()],
                'SHP'             : ['',''],
                'Where'           : aWhereItem,
                'TimeLocalstorage': dTimelocalStorage,
                // 'tTYPEPDT'        : '1,2,3,4,5'
                'aAlwPdtType' : ['T1','T2','T3','T5','S2','S3','S4','S5']
            },
            cache: false,
            timeout: 0,
            success: function(tResult){
                $('#odvModalDOCPDT').modal({backdrop: 'static', keyboard: false})
                $('#odvModalDOCPDT').modal({ show: true });

                //remove localstorage
                localStorage.removeItem("LocalItemDataPDT");
                $('#odvModalsectionBodyPDT').html(tResult);
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    //หลังจากเลือกสินค้า
    function JSxAfterChoosePDT(ptPdtData){
        var aPackData = JSON.parse(ptPdtData);
        for(var i=0;i<aPackData.length;i++){
            var aNewPackData = JSON.stringify(aPackData[i]);
            var aNewPackData = "["+aNewPackData+"]";
            JSxTQEventRenderTemp(aNewPackData);      // Event Render : client
            JSxTQEventInsertToTemp(aNewPackData);    // Event Insert : server
        }
    }

    //พิมพ์
    function JSxQTPrintDoc(){
        var tGrandText  = $('#odvTQDataTextBath').text();
        var aInfor      = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tTQFTBchCode); ?>'},
            {"DocCode"      : '<?=@$tTQDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tTQFTBchCode;?>'},
        ];
        window.open("<?php echo base_url(); ?>formreport/Frm_SQL_SMBillSQ?infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand="+tGrandText, '_blank');
    }

    $('#oimPreBrowseCarRegNo').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPreCarCstOption = undefined;
            oPreCarCstOption        = oPreCarCst({
                'tReturnInputCode'  : 'oetPreCarRegCode',
                'tReturnInputName'  : 'oetPreCarRegName',
                'tNextFuncName'     : 'JSxWhenSeletedCstCar',
                'aArgReturn'        : ['FTCarCode','FTCarRegNo'],
                'tParamsCstCode'    : $('#ohdTQCustomerCode').val()
            });
            JCNxBrowseData('oPreCarCstOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //เลือกรถ
    var oPreCarCst  = function(poDataFnc){
        let tInputReturnCode    = poDataFnc.tReturnInputCode;
        let tInputReturnName    = poDataFnc.tReturnInputName;
        let tNextFuncName       = poDataFnc.tNextFuncName;
        let aArgReturn          = poDataFnc.aArgReturn;
        let tParamsCstCode      = poDataFnc.tParamsCstCode;

        var tWhereCst = '';
        if($('#ohdTQCustomerCode').val() != ''){
            var tWhereCst = "AND TSVMCar.FTCarOwner = '" + tParamsCstCode + "'";
        }

        let oOptionReturn       = {
            Title   : ['document/jobrequest1/jobrequest1', 'tJR1CarCst'],
            Table   : {Master:'TSVMCar', PK:'FTCarCode'},
            Join    : {
                Table   : ['TCNMCst_L'],
                On      : ["TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = "+nLangEdits ]
            },
            Where   : {
                Condition : [tWhereCst]
            },
            GrideView:{
                ColumnPathLang      : 'document/jobrequest1/jobrequest1',
                ColumnKeyLang       : ['tJR1CarCstCode', 'tJR1CarCstName', 'tJR1OwnerCstName'],
                ColumnsSize         : ['15%', '15%', '60%'],
                WidthModal          : 50,
                DataColumns         : ['TSVMCar.FTCarCode', 'TSVMCar.FTCarRegNo', 'TCNMCst_L.FTCstName'],
                DataColumnsFormat   : ['','',''],
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
    };

    // ฟังก์ชั่นทำงานหลังจากทำการเลือกข้อมูลทะเบียนรถ
    function JSxWhenSeletedCstCar(aReturn){
        // Check Data Open Button Browse Car Customer
        if(aReturn != '' || aReturn != 'NULL'){
            // Find Data Car
            $.ajax({
                type : "POST",
                url : "docPreRepairResultFindCar",
                data : {"poItem" : aReturn},
                cache   : false,
                timeout : 0,
                success : function (tResult) {
                    let aReturn = JSON.parse(tResult);
                    if(aReturn != ""){
                        let aDataCarCst = aReturn.aDataCarCst;
                        var nChkRedlabel = aDataCarCst.raItems.FTCarStaRedLabel
                        $("#oetPreCarRegName").val(aDataCarCst.raItems.FTCarRegNo);
                        $("#oetPrePvnName").val(aDataCarCst.raItems.FTCarRegPvnName);
                        $("#oetPreCarTypeName").val(aDataCarCst.raItems.FTCarTypeName);
                        $("#oetPreCarBrandName").val(aDataCarCst.raItems.FTCarBrandName);
                        $("#oetPreCarModelName").val(aDataCarCst.raItems.FTCarModelName);
                        $("#oetPreCarColorName").val(aDataCarCst.raItems.FTCarColorName);
                        $("#oetPreCarGearName").val(aDataCarCst.raItems.FTCarGearName);
                        $("#oetPreCarVIDRef").val(aDataCarCst.raItems.FTCarVIDRef);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{

        }
    }
// ========================================== End Browse ทะเบียนรถ ================================================


/** ================== Check Box Auto GenCode ===================== */
$('#ocbTQStaAutoGenCode').on('change', function (e) {
    if($('#ocbTQStaAutoGenCode').is(':checked')){
        $("#oetTQDocNo").val('');
        $("#oetTQDocNo").attr("readonly", true);
        $('#oetTQDocNo').closest(".form-group").css("cursor","not-allowed");
        $('#oetTQDocNo').css("pointer-events","none");
        $("#oetTQDocNo").attr("onfocus", "this.blur()");
        $('#ofmTQFormAdd').removeClass('has-error');
        $('#ofmTQFormAdd .form-group').closest('.form-group').removeClass("has-error");
        $('#ofmTQFormAdd em').remove();
    }else{
        $('#oetTQDocNo').closest(".form-group").css("cursor","");
        $('#oetTQDocNo').css("pointer-events","");
        $('#oetTQDocNo').removeAttr('readonly',false);
        $("#oetTQDocNo").removeAttr("onfocus");
    }
});
/** =============================================================== */
</script>
