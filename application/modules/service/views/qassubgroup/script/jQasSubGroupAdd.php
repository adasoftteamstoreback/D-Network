<script type="text/javascript">
    $('.selection-2').selectpicker();

    $('#ocmRcnGroup').change(function() {
        $('#ocmRcnGroup-error').hide();
    });
    $(document).ready(function () {
        if(JSbQasSubGroupIsCreatePage()){
            $("#oetQSGCode").attr("disabled", true);
            $('#ocbQasSubGroupAutoGenCode').change(function(){
                if($('#ocbQasSubGroupAutoGenCode').is(':checked')) {
                    $('#oetQSGCode').val('');
                    $("#oetQSGCode").attr("disabled", true);
                    $('#odvQasSubGroupCodeForm').removeClass('has-error');
                    $('#odvQasSubGroupCodeForm em').remove();
                }else{
                    $("#oetQSGCode").attr("disabled", false);
                }
            });
            JSxQasSubGroupVisibleComponent('#odvQasSubGroupAutoGenCode', true);
        }

        if(JSbQasSubGroupIsUpdatePage()){
            // Sale Person Code
            $("#oetQSGCode").attr("readonly", true);
            $('#odvQasSubGroupAutoGenCode input').attr('disabled', true);
            JSxQasSubGroupVisibleComponent('#odvQasSubGroupAutoGenCode', false);    
        }

        $('#oetQSGCode').blur(function(){
            JSxCheckQasSubGroupCodeDupInDB();
        });

    });

    //Functionality: Event Check QasSubGroup Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 20/05/2021 Off
    //Return: -
    //ReturnType: -
    function JSxCheckQasSubGroupCodeDupInDB(){
        if(!$('#ocbQasSubGroupAutoGenCode').is(':checked')){
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName: "TCNMQasSubGrp",
                    tFieldName: "FTQsgCode",
                    tCode: $("#oetQSGCode").val()
                },
                async : false,
                cache: false,
                timeout: 0,
                success: function(tResult){
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateQSGCode").val(aResult["rtCode"]);
                    JSxQasSubGroupSetValidEventBlur();
                    $('#ofmAddQasSubGroup').submit();
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
    function JSxQasSubGroupSetValidEventBlur(){
        $('#ofmAddQasSubGroup').validate().destroy();

        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if($("#ohdCheckDuplicateQSGCode").val() == 1){
                return false;
            }else{
                return true;
            }
        },'');

        // From Summit Validate
        $('#ofmAddQasSubGroup').validate({
            rules: {
                oetQSGCode : {
                    "required" :{
                        // ตรวจสอบเงื่อนไข validate
                        depends: function(oElement) {
                            if($('#ocbQasSubGroupAutoGenCode').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }
                        }
                    },
                    "dublicateCode" :{}
                },
                oetQSGName:     {"required" :{}}
            },
            messages: {
                oetQSGCode : {
                    "required"      : $('#oetQSGCode').attr('data-validate-required'),
                    "dublicateCode" : $('#oetQSGCode').attr('data-validate-dublicateCode')
                },
                oetQSGName : {
                    "required"      : $('#oetQSGName').attr('data-validate-required'),
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
                'tReturnInputCode'  : 'oetQSGAgnCode',
                'tReturnInputName'  : 'oetQSGAgnName',
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