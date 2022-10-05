<script type="text/javascript">
    $('.selection-2').selectpicker();

    $('#ocmRcnGroup').change(function() {
        $('#ocmRcnGroup-error').hide();
    });

    if($('#ohdCheckStatus').val() == 2){
        $("#ocbCarInfoStatus").prop('checked', false);;
    }

    $(document).ready(function () {
        if(JSbCarInfoIsCreatePage()){
            $("#oetCAICode").attr("disabled", true);
            $('#odvCarInfoAutoGenCode').change(function(){
                if($('#odvCarInfoAutoGenCode').is(':checked')) {
                    $('#oetCAICode').val('');
                    $("#oetCAICode").attr("disabled", true);
                    $('#odvCarInfoCodeForm').removeClass('has-error');
                    $('#odvCarInfoCodeForm em').remove();
                }else{
                    $("#oetCAICode").attr("disabled", false);
                }
            });
            JSxCarInfoVisibleComponent('#odvCarInfoAutoGenCode', true);
        }

        if(JSbCarInfoIsUpdatePage()){
            // Sale Person Code
            $("#oetCAICode").attr("readonly", true);
            $('#odvCarInfoAutoGenCode input').attr('disabled', true);
            JSxCarInfoVisibleComponent('#odvCarInfoAutoGenCode', false);    
        }

        $('#oetCAICode').blur(function(){
            JSxCheckCarInfoCodeDupInDB();
        });

    });

    //Functionality: Event Check CarInfo Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 02/06/2021 Off
    //Return: -
    //ReturnType: -
    function JSxCheckCarInfoCodeDupInDB(){
        if(!$('#odvCarInfoAutoGenCode').is(':checked')){
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName: "TSVMCarInfo",
                    tFieldName: "FTCaiCode",
                    tCode: $("#oetCAICode").val()
                },
                async : false,
                cache: false,
                timeout: 0,
                success: function(tResult){
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateCaiCode").val(aResult["rtCode"]);
                    JSxCarInfoSetValidEventBlur();
                    $('#ofmAddCarInfo').submit();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //Functionality: Set Validate Event Blur
    //Parameters: Validate Event Blur
    //Creator: 20/05/2021 Off
    //Return: -
    //ReturnType: -
    function JSxCarInfoSetValidEventBlur(){
        $('#ofmAddCarInfo').validate().destroy();

        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if($("#ohdCheckDuplicateCaiCode").val() == 1){
                return false;
            }else{
                return true;
            }
        },'');

        // From Summit Validate
        $('#ofmAddCarInfo').validate({
            rules: {
                oetCAICode : {
                    "required" :{
                        // ตรวจสอบเงื่อนไข validate
                        depends: function(oElement) {
                            if($('#odvCarInfoAutoGenCode').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }
                        }
                    },
                    "dublicateCode" :{}
                },
                oetCAIName:     {"required" :{}}
            },
            messages: {
                oetCAICode : {
                    "required"      : $('#oetCAICode').attr('data-validate-required'),
                    "dublicateCode" : $('#oetCAICode').attr('data-validate-dublicateCode')
                },
                oetCAIName : {
                    "required"      : $('#oetCAIName').attr('data-validate-required'),
                }
            },
            errorElement: "em",
            errorPlacement: function (error, element ) {
                error.addClass( "help-block" );
                if ( element.prop( "type" ) === "checkbox" ) {
                    error.appendTo( element.parent( "label" ) );
                } else {
                    var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                    if(tCheck == 0){
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function ( element, errorClass, validClass ) {
                $( element ).closest('.form-group').addClass( "has-error" ).removeClass( "has-success" );
            },
            unhighlight: function(element, errorClass, validClass) {
                var nStaCheckValid  = $(element).parents('.form-group').find('.help-block').length
                if(nStaCheckValid != 0){
                    $(element).closest('.form-group').addClass( "has-success" ).removeClass( "has-error" );
                }
            },
            submitHandler: function(form){}
        });
    }

  //BrowseAgn 
  $('#oimBrowseAgn').click(function(e){
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPdtBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode'  : 'oetCaiAgnCode',
                'tReturnInputName'  : 'oetCaiAgnName',
            });
            JCNxBrowseData('oPdtBrowseAgencyOption');
        }else{
            JCNxShowMsgSessionExpired(); 
        }
    });

    //BrowseBrand
  $('#oimBrowseBrand').click(function(e){
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPdtBrowseBrandOption = oBrowseBrand({
                'tReturnInputCode'  : 'oetCaiBrandCode',
                'tReturnInputName'  : 'oetCaiBrandName',
            });
            JCNxBrowseData('oPdtBrowseBrandOption');
        }else{
            JCNxShowMsgSessionExpired(); 
        }
    });

    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit")?>;

    //Option Agn
    var oBrowseAgn = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;


        var oOptionReturn   = {
            Title : ['ticket/agency/agency', 'tAggTitle'],
            Table:{Master:'TCNMAgency', PK:'FTAgnCode'},
            Join :{
            Table: ['TCNMAgency_L'],
                On: ['TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = '+nLangEdits]
            },
            GrideView:{
                ColumnPathLang	: 'ticket/agency/agency',
                ColumnKeyLang	: ['tAggCode', 'tAggName'],
                ColumnsSize     : ['15%', '85%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat : ['', ''],
                Perpage			: 10,
                OrderBy			: ['TCNMAgency.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType  : 'S',
                Value		: [tInputReturnCode,"TCNMAgency.FTAgnCode"],
                Text		: [tInputReturnName,"TCNMAgency_L.FTAgnName"],
            },
            RouteAddNew : 'agency',
            BrowseLev : 1,
        }
        return oOptionReturn;
    }

    //Option Agn
    var oBrowseBrand = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;
        var tSQLWhere = "AND TSVMCarInfo.FTCaiType = '2' ";


        var oOptionReturn   = {
            Title : ['product/carinfo/carinfo', 'tCAITitle2'],
            Table:{Master:'TSVMCarInfo', PK:'FTCaiCode'},
            Join :{
            Table: ['TSVMCarInfo_L'],
                On: ['TSVMCarInfo_L.FTCaiCode = TSVMCarInfo.FTCaiCode AND TSVMCarInfo_L.FNLngID = '+nLangEdits]
            },
            Where: {
                Condition: [tSQLWhere]
            },
            GrideView:{
                ColumnPathLang	: 'product/carinfo/carinfo',
                ColumnKeyLang	: ['tCAICode2', 'tCAIName2'],
                ColumnsSize     : ['15%', '85%'],
                WidthModal      : 50,
                DataColumns		: ['TSVMCarInfo.FTCaiCode', 'TSVMCarInfo_L.FTCaiName'],
                DataColumnsFormat : ['', ''],
                Perpage			: 10,
                OrderBy			: ['TSVMCarInfo.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType  : 'S',
                Value		: [tInputReturnCode,"TSVMCarInfo.FTCaiCode"],
                Text		: [tInputReturnName,"TSVMCarInfo_L.FTCaiName"],
            },
            RouteAddNew : 'agency',
            BrowseLev : 1,
        }
        return oOptionReturn;
    }


    var tStaUsrLevel    = '<?php  echo $this->session->userdata("tSesUsrLevel"); ?>';

    if(tStaUsrLevel == 'BCH' || tStaUsrLevel == 'SHP'){
        $('#oimBrowseAgn').attr("disabled", true);
    }



</script>