<script type="text/javascript">

    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit")?>;

    $(document).ready(function(){
      
    });


    $('#oetErrCode').blur(function(){
        JSxCheckErrCodeDupInDB();
    });

    //Functionality: Event Check Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 23/07/2021 Off
    //Return: -
    //ReturnType: -
    function JSxCheckErrCodeDupInDB(){
        $.ajax({
            type: "POST",
            url: "CheckInputGenCode",
            data: { 
                tTableName: "TLKMErrMsg",
                tFieldName: "FTErrCode",
                tCode: $("#oetErrCode").val()
            },
            async : false,
            cache: false,
            timeout: 0,
            success: function(tResult){
                var aResult = JSON.parse(tResult);
                $("#ohdCheckDuplicateErrCode").val(aResult["rtCode"]);
                JSxErrCodeSetValidEventBlur();
                $('#ofmAddConnectionSettingRespond').submit();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }


    //Functionality: Set Validate Event Blur
    //Parameters: Validate Event Blur
    //Creator: 23/07/2021 Off
    //Return: -
    //ReturnType: -
    function JSxErrCodeSetValidEventBlur(){
        $('#ofmAddConnectionSettingRespond').validate().destroy();

        $.validator.addMethod('dublicateCode', function(value, element) {
            if($("#ohdCheckDuplicateErrCode").val() == 1){
                return false;
            }else{
                return true;
            }
        },'');

        // From Summit Validate
        $('#ofmAddConnectionSettingRespond').validate({
            rules: {
                oetErrCode : {
                    "required" :{},
                    "dublicateCode" :{}
                }
            },
            messages: {
                oetErrCode : {
                    "required"      : $('#oetErrCode').attr('data-validate-required'),
                    "dublicateCode" : $('#oetErrCode').attr('data-validate-dublicateCode')
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


</script>