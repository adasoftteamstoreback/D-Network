<script type="text/javascript">
    $('.selection-2').selectpicker();

    $('#ocmRcnGroup').change(function() {
        $('#ocmRcnGroup-error').hide();
    });
    $(document).ready(function () {
        if(JSbQasGroupIsCreatePage()){
            $("#oetQGPCode").attr("disabled", true);
            $('#ocbQasGroupAutoGenCode').change(function(){
                if($('#ocbQasGroupAutoGenCode').is(':checked')) {
                    $('#oetQGPCode').val('');
                    $("#oetQGPCode").attr("disabled", true);
                    $('#odvQasGroupCodeForm').removeClass('has-error');
                    $('#odvQasGroupCodeForm em').remove();
                }else{
                    $("#oetQGPCode").attr("disabled", false);
                }
            });
            JSxQasGroupVisibleComponent('#odvQasGroupAutoGenCode', true);
        }

        if(JSbQasGroupIsUpdatePage()){
            // Sale Person Code
            $("#oetQGPCode").attr("readonly", true);
            $('#odvQasGroupAutoGenCode input').attr('disabled', true);
            JSxQasGroupVisibleComponent('#odvQasGroupAutoGenCode', false);    
        }

        $('#oetQGPCode').blur(function(){
            JSxCheckQasGroupCodeDupInDB();
        });

    });

    //Functionality: Event Check QasGroup Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 20/05/2021 Off
    //Return: -
    //ReturnType: -
    function JSxCheckQasGroupCodeDupInDB(){
        if(!$('#ocbQasGroupAutoGenCode').is(':checked')){
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName: "TCNMQasGrp",
                    tFieldName: "FTQgpCode",
                    tCode: $("#oetQGPCode").val()
                },
                async : false,
                cache: false,
                timeout: 0,
                success: function(tResult){
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateQGPCode").val(aResult["rtCode"]);
                    JSxQasGroupSetValidEventBlur();
                    $('#ofmAddQasGroup').submit();
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
    function JSxQasGroupSetValidEventBlur(){
        $('#ofmAddQasGroup').validate().destroy();

        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if($("#ohdCheckDuplicateQGPCode").val() == 1){
                return false;
            }else{
                return true;
            }
        },'');

        // From Summit Validate
        $('#ofmAddQasGroup').validate({
            rules: {
                oetQGPCode : {
                    "required" :{
                        // ตรวจสอบเงื่อนไข validate
                        depends: function(oElement) {
                            if($('#ocbQasGroupAutoGenCode').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }
                        }
                    },
                    "dublicateCode" :{}
                },
                oetQGPName:     {"required" :{}}
            },
            messages: {
                oetQGPCode : {
                    "required"      : $('#oetQGPCode').attr('data-validate-required'),
                    "dublicateCode" : $('#oetQGPCode').attr('data-validate-dublicateCode')
                },
                oetQGPName : {
                    "required"      : $('#oetQGPName').attr('data-validate-required'),
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
                'tReturnInputCode'  : 'oetQGPAgnCode',
                'tReturnInputName'  : 'oetQGPAgnName',
            });
            JCNxBrowseData('oPdtBrowseAgencyOption');
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


    var tStaUsrLevel    = '<?php  echo $this->session->userdata("tSesUsrLevel"); ?>';

    if(tStaUsrLevel == 'BCH' || tStaUsrLevel == 'SHP'){
        $('#oimBrowseAgn').attr("disabled", true);
    }



</script>