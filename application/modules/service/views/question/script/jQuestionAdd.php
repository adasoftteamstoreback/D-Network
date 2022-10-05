<script type="text/javascript">
    $('.selection-2').selectpicker();

    $('#ocmRcnGroup').change(function() {
        $('#ocmRcnGroup-error').hide();
    });


    
    $('#oahDetailTab').click(function(event) {
        $('#odvBtnQahAddEdit').hide();
        });

        $('#oahQuestionTab').click(function(event) {
        $('#odvBtnQahAddEdit').show();
        });

    $(document).ready(function () {
        if(JSbQuestionIsCreatePage()){
            $("#oetQahCode").attr("disabled", true);
            $('#ocbQuestionAutoGenCode').change(function(){
                if($('#ocbQuestionAutoGenCode').is(':checked')) {
                    $('#oetQahCode').val('');
                    $("#oetQahCode").attr("disabled", true);
                    $('#odvQuestionCodeForm').removeClass('has-error');
                    $('#odvQuestionCodeForm em').remove();
                }else{
                    $("#oetQahCode").attr("disabled", false);
                }
            });
            JSxQuestionVisibleComponent('#odvQuestionAutoGenCode', true);
        }

        if(JSbQuestionIsUpdatePage()){
            // Sale Person Code
            $("#oetQahCode").attr("readonly", true);
            $('#odvQuestionAutoGenCode input').attr('disabled', true);
            JSxQuestionVisibleComponent('#odvQuestionAutoGenCode', false);    
        }

        $('#oetQahCode').blur(function(){
            JSxCheckQuestionCodeDupInDB();
        });

        $( ".xWCheckSelect" ).each(function( index ) {
            var nchecktype = $('#ohdCheckType').val();
            if(nchecktype != '' && $(this).val() == nchecktype){
                $(this).attr("selected","selected");
            };
        });

        // Event Tab
        $('#odvQahPanelBody .xCNQAHTab').unbind().click(function(){
            let tPosRoute       = '<?php echo @$tRoute;?>';
            if(tPosRoute == 'questionEventAdd'){
                return;
            }else{
                let tTypeTab    = $(this).data('typetab');
                if(typeof(tTypeTab) !== undefined && tTypeTab == 'main'){
                    JCNxOpenLoading();
                    setTimeout(function(){
                        $('#odvPosMainMenu #odvBtnAddEdit').show();
                        JCNxCloseLoading();
                        return;
                    },500);
                }else if(typeof(tTypeTab) !== undefined && tTypeTab == 'sub'){
                    $('#odvPosMainMenu #odvBtnAddEdit').hide();
                    let tTabTitle   = $(this).data('tabtitle');
                    switch(tTabTitle){
                        case 'questiondetail':
                            JCNxOpenLoading();
                            setTimeout(function(){
                                JCNxCloseLoading();
                                return;
                            },500);
                        break;
                    }
                }   
            }
        });
        $('#obtQahDate').click(function(event) {
            $('#oetQahStart').datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true,
                enableOnReadonly: false,
                startDate: '1900-01-01',
                disableTouchKeyboard: true,
                autoclose: true,
            });
            $('#oetQahStart').datepicker('show');
            event.preventDefault();
        });

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            enableOnReadonly: false,
            startDate: $('#oetQahStart').val(),
            minDate: $('#oetQahStart').val(),
            disableTouchKeyboard: true,
            autoclose: true
        });

        $('#obtQahStartDate').click(function(event) {
            $('#oetQahStart').datepicker('show');
            event.preventDefault();
        });

        $('#obtQahFinishDate').click(function(event) {
            $('#oetQahFinish').datepicker('show');
            event.preventDefault();
        });

        $('#odvSmgSlipHeadContainer').sortable({
            items: '.xWSmgItemSelect',
            opacity: 0.7,
            axis: 'y',
            handle: '.xWSmgMoveIcon',
            update: function(event, ui) {
                var aToArray = $(this).sortable('toArray');
                var aSerialize = $(this).sortable('serialize', {
                    key: ".sort"
                });
            }
        });
    });

    //Functionality: Event Check Question Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 24/06/2021 Off
    //Return: -
    //ReturnType: -
    function JSxCheckQuestionCodeDupInDB(){
        if(!$('#ocbQuestionAutoGenCode').is(':checked')){
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName: "TCNTQaHD",
                    tFieldName: "FTQahDocNo",
                    tCode: $("#oetQahCode").val()
                },
                async : false,
                cache: false,
                timeout: 0,
                success: function(tResult){
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateQahCode").val(aResult["rtCode"]);
                    JSxQuestionSetValidEventBlur();
                    $('#ofmAddQuestion').submit();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //Functionality: Set Validate Event Blur
    //Parameters: Validate Event Blur
    //Creator: 24/06/2021 Off
    //Return: -
    //ReturnType: -
    function JSxQuestionSetValidEventBlur(){
        $('#ofmAddQuestion').validate().destroy();

        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if($("#ohdCheckDuplicateQahCode").val() == 1){
                return false;
            }else{
                return true;
            }
        },'');

        // From Summit Validate
        $('#ofmAddQuestion').validate({
            rules: {
                oetQahCode : {
                    "required" :{
                        // ตรวจสอบเงื่อนไข validate
                        depends: function(oElement) {
                            if($('#ocbQuestionAutoGenCode').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }
                        }
                    },
                    "dublicateCode" :{}
                },
                oetCldName:     {"required" :{}}
            },
            messages: {
                oetQahCode : {
                    "required"      : $('#oetQahCode').attr('data-validate-required'),
                    "dublicateCode" : $('#oetQahCode').attr('data-validate-dublicateCode')
                },
                oetCldName : {
                    "required"      : $('#oetCldName').attr('data-validate-required'),
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

  //BrowseQasGroup 
  $('#oimBrowseQasGroup').click(function(e){
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPdtBrowseQasGroupOption = oBrowseQasGroup({
                'tReturnInputCode'  : 'oetQasGroupCode',
                'tReturnInputName'  : 'oetQasGroupName',
            });
            JCNxBrowseData('oPdtBrowseQasGroupOption');
        }else{
            JCNxShowMsgSessionExpired(); 
        }
    });

    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit")?>;
    var tSesAgnCode = $('#ohdCheckAgn').val();
    var tSQLWhere = "";
    if(tSesAgnCode != ''){
         var tSQLWhere = " AND TCNMQasGrp.FTAgnCode = " + tSesAgnCode;
        };

    //Option QasGroup
    var oBrowseQasGroup = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;


        var oOptionReturn   = {
            Title : ['service/question/question', 'tQAHQasGroup'],
            Table:{Master:'TCNMQasGrp', PK:'FTQgpCode'},
            Join :{
            Table: ['TCNMQasGrp_L'],
                On: ['TCNMQasGrp_L.FTQgpCode = TCNMQasGrp.FTQgpCode AND TCNMQasGrp_L.FNLngID = '+nLangEdits]
            },
            Where: {
            Condition: [tSQLWhere]
            },
            GrideView:{
                ColumnPathLang	: 'service/question/question',
                ColumnKeyLang	: ['tQAHQasGroupCode', 'tQAHQasGroupName'],
                ColumnsSize     : ['15%', '85%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMQasGrp.FTQgpCode', 'TCNMQasGrp_L.FTQgpName'],
                DataColumnsFormat : ['', ''],
                Perpage			: 10,
                OrderBy			: ['TCNMQasGrp.FDCreateOn DESC'],
            },
            CallBack:{
                ReturnType  : 'S',
                Value		: [tInputReturnCode,"TCNMQasGrp.FTQgpCode"],
                Text		: [tInputReturnName,"TCNMQasGrp_L.FTQgpName"],
            },
            RouteAddNew : 'QasGroup',
            BrowseLev : 1,
        }
        return oOptionReturn;
    }

    //BrowseQasSub
  $('#oimBrowseQasSub').click(function(e){
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oPdtBrowseQasSubOption = oBrowseAvg({
                'tReturnInputCode'  : 'oetQasSubCode',
                'tReturnInputName'  : 'oetQasSubName',
            });
            JCNxBrowseData('oPdtBrowseQasSubOption');
        }else{
            JCNxShowMsgSessionExpired(); 
        }
    });

    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit")?>;
    var tSesAgnCode = $('#ohdCheckAgn').val();
    var tSQLWhere = "";
    if(tSesAgnCode != ''){
         var tSQLWhere = " AND TCNMQasGrp.FTAgnCode = " + tSesAgnCode;
    };

    //Option QasSub
    var oBrowseAvg = function(poReturnInput){
        var tInputReturnCode    = poReturnInput.tReturnInputCode;
        var tInputReturnName    = poReturnInput.tReturnInputName;

        var oOptionReturn   = {
            Title : ['service/question/question', 'tQAHQasSubGroup'],
            Table: {
                Master: 'TCNMQasSubGrp',
                PK: 'FTQsgCode'
            },
            Join: {
                Table: ['TCNMQasSubGrp_L'],
                On: ['TCNMQasSubGrp_L.FTQsgCode = TCNMQasSubGrp.FTQsgCode AND TCNMQasSubGrp_L.FNLngID = ' + nLangEdits, ]
            },
            GrideView: {
            ColumnPathLang: 'service/question/question',
            ColumnKeyLang: ['tQAHQasSubGroupCode', 'tQAHQasSubGroupName'],
            ColumnsSize: ['30%', '40%', '30%'],
            DataColumns: ['TCNMQasSubGrp.FTQsgCode', 'TCNMQasSubGrp_L.FTQsgName'],
            DataColumnsFormat: ['', '', ''],
            WidthModal: 50,
            Perpage: 10,
            OrderBy: ['TCNMQasSubGrp.FDCreateOn DESC'],
        },
            CallBack:{
                ReturnType  : 'S',
                Value		: [tInputReturnCode,"TCNMQasSubGrp.FTQsgCode"],
                Text		: [tInputReturnName,"TCNMQasSubGrp_L.FTQsgName"],
            },
            RouteAddNew : 'QasSub',
            BrowseLev : 1,
        }
        return oOptionReturn;
    }


    var tStaUsrLevel    = '<?php  echo $this->session->userdata("tSesUsrLevel"); ?>';

    if(tStaUsrLevel == 'BCH' || tStaUsrLevel == 'SHP'){
        $('#oimBrowseAgn').attr("disabled", true);
    }



</script>