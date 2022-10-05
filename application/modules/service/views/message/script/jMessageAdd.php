<script type="text/javascript">
    $('.selection-2').selectpicker();

    $('#ocmRcnGroup').change(function() {
        $('#ocmRcnGroup-error').hide();
    });

    if ($('#ohdCheckStatus').val() != 1) {
        $("#ocbMessageStatus").prop('checked', false);;
    }
    if (JSbCalendarIsCreatePage()) { // For create page

        // Set head of receipt default
        JSxMessageRowDefualt('head', 1);
        // Set end of receipt default

    } else { // for update page

        if (JCNnMessageCountRow('head') <= 0) {
            // Set head of receipt default
            JSxMessageRowDefualt('head', 1);
        }

    }
    JSaMessageGetSortData('head');
    // Remove sort data
    JSxMessageRemoveSortData('all');

    $(document).ready(function() {
        if (JSbCalendarIsCreatePage()) {
            $("#oetMsgCode").attr("disabled", true);
            $('#ocbMessageAutoGenCode').change(function() {
                if ($('#ocbMessageAutoGenCode').is(':checked')) {
                    $('#oetMsgCode').val('');
                    $("#oetMsgCode").attr("disabled", true);
                    $('#odvMessageCodeForm').removeClass('has-error');
                    $('#odvMessageCodeForm em').remove();
                } else {
                    $("#oetMsgCode").attr("disabled", false);
                }
            });
            JSxCalendarVisibleComponent('#odvMessageAutoGenCode', true);
        }

        if (JSbCalendarIsUpdatePage()) {
            // Sale Person Code
            $("#oetMsgCode").attr("readonly", true);
            $('#odvMessageAutoGenCode input').attr('disabled', true);
            JSxCalendarVisibleComponent('#odvMessageAutoGenCode', false);
        }

        $('#oetMsgCode').blur(function() {
            JSxCheckMSGCodeDupInDB();
        });


        // Event Tab
        $('#odvMsgPanelBody .xCNCLDTab').unbind().click(function() {
            let tPosRoute = '<?php echo @$tRoute; ?>';
            if (tPosRoute == 'calendarEventAdd') {
                return;
            } else {
                let tTypeTab = $(this).data('typetab');
                if (typeof(tTypeTab) !== undefined && tTypeTab == 'main') {
                    JCNxOpenLoading();
                    setTimeout(function() {
                        $('#odvPosMainMenu #odvBtnAddEdit').show();
                        JCNxCloseLoading();
                        return;
                    }, 500);
                } else if (typeof(tTypeTab) !== undefined && tTypeTab == 'sub') {
                    $('#odvPosMainMenu #odvBtnAddEdit').hide();
                    let tTabTitle = $(this).data('tabtitle');
                    switch (tTabTitle) {
                        case 'posinfouser':
                            JCNxOpenLoading();
                            setTimeout(function() {
                                JCNxCloseLoading();
                                return;
                            }, 500);
                            break;
                    }
                }
            }
        });

        $('#obtMsgStartDate').click(function(event) {
            $('#oetMsgStart').datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true,
                enableOnReadonly: false,
                startDate: '1900-01-01',
                disableTouchKeyboard: true,
                autoclose: true,
            });
            $('#oetMsgStart').datepicker('show');
            event.preventDefault();
        });

        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            enableOnReadonly: false,
            startDate: $('#oetMsgStart').val(),
            minDate: $('#oetMsgStart').val(),
            disableTouchKeyboard: true,
            autoclose: true
        });

        $('#obtMsgStartDate').click(function(event) {
            $('#oetMsgStart').datepicker('show');
            event.preventDefault();
        });

        $('#obtMsgFinishDate').click(function(event) {
            $('#oetMsgFinish').datepicker('show');
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

    //Functionality: Check Type of Message Detail
    //Parameters: Type of Message Detail
    //Creator: 07/06/2021 Off
    //Return: -
    //ReturnType: -
    function JSxChangeType(poElement = null) {
        let tMsgTypeId = $(poElement).val();
        let tBaseUrl    = $('#ohdBaseurl').val();
        let tMsgId     = poElement.attr('getattr');
        if(poElement.value == 4){
            var oOnclick = 'JSxImageUplodeResizeNEW(this,"","PDTDEMO'+tMsgId+'","1")';
            var tHTML = "<div class='input-group xWMsgImageButton"+tMsgId+"''>";
            tHTML += "<input readonly required type='text' name='oetImgInputPDTDEMO"+tMsgId+"' id='oetImgInputPDTDEMO"+tMsgId+"'>";
            tHTML += "<input style='display:none; visibility:none' type='file' id='oetInputUplodePDTDEMO"+tMsgId+"'  onchange='"+oOnclick+"' accept='image/png, image/jpeg'>"
            tHTML += "<span class='input-group-btn'>"
            tHTML += "<button id='obtMsgFinishDate' style='height: 34px;color: #FFFFFF !important;background-color: #aba9a9 !important;border-color: #aba9a9 !important;' type='button' class='btn xCNBtnDateTime'>"
            tHTML += "<label style='cursor: pointer;' for='oetInputUplodePDTDEMO"+tMsgId+"'>"
            tHTML += "<i class='fa fa-picture-o xCNImgButton'></i> เลือกรูป"
            tHTML += "</label>"
            tHTML += "</button></span></div>"

            $('.XWMsgType'+tMsgId).append(tHTML);
            $('#oetMsgValue'+tMsgId).remove();
        }else if(poElement.value == 6){
            $('#oetMsgValue'+tMsgId).remove();
            var tHTML = "<input type='text' readonly class='form-control xWSmgDyForm' maxlength='100' required id='oetMsgValue"+tMsgId+"' name = 'oetMsgValue["+tMsgId+"]' value='System Date'>";
            $('.XWMsgType'+tMsgId).append(tHTML);
            $('#oetInputUplodePDTDEMO'+tMsgId).remove();
            $('#oetImgInputPDTDEMO'+tMsgId).remove();
            $('.xWMsgImageButton'+tMsgId).remove();
        }else if(poElement.value == 7){
            $('#oetMsgValue'+tMsgId).remove();
            var tHTML = "<input type='text' readonly class='form-control xWSmgDyForm' maxlength='100' required id='oetMsgValue"+tMsgId+"' name = 'oetMsgValue["+tMsgId+"]' value='Line Feed'>";
            $('.XWMsgType'+tMsgId).append(tHTML);
            $('#oetInputUplodePDTDEMO'+tMsgId).remove();
            $('#oetImgInputPDTDEMO'+tMsgId).remove();
            $('.xWMsgImageButton'+tMsgId).remove();
        }else{
            $('#oetMsgValue'+tMsgId).remove();
            var tHTML = "<input type='text' class='form-control xWSmgDyForm' maxlength='100' required id='oetMsgValue"+tMsgId+"' name = 'oetMsgValue["+tMsgId+"]' value=''>";
            $('.XWMsgType'+tMsgId).append(tHTML);
            $('#oetInputUplodePDTDEMO'+tMsgId).remove();
            $('#oetImgInputPDTDEMO'+tMsgId).remove();
            $('.xWMsgImageButton'+tMsgId).remove();
        }
    }

    /** <i class="fa fa-picture-o xCNImgButton"></i>
 * Functionality : Show or Hide Media Input
 * Parameters : pbVisibled is true=show, false=hide
 * Creator : 10/09/2018 piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxVisibledImageType(pbVisibled) {
    try {
        if (pbVisibled) {
            $('#odvImageTypeContainer').show();
        } else {
            $('#odvImageTypeContainer').hide();
        }
    } catch (err) {
        console.log('JSxVisibledMediaType Error: ', err);
    }
}

    //Functionality: Event Check Calendar Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 20/05/2021 Off
    //Return: -
    //ReturnType: -
    function JSxCheckMSGCodeDupInDB() {
        if (!$('#ocbMessageAutoGenCode').is(':checked')) {
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: {
                    tTableName: "TCNMMsgHD",
                    tFieldName: "FTMshCode",
                    tCode: $("#oetMsgCode").val()
                },
                async: false,
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateMsgCode").val(aResult["rtCode"]);
                    JSxCalendarSetValidEventBlur();
                    $('#ofmAddMessage').submit();
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
    function JSxCalendarSetValidEventBlur() {
        $('#ofmAddMessage').validate().destroy();

        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if ($("#ohdCheckDuplicateMsgCode").val() == 1) {
                return false;
            } else {
                return true;
            }
        }, '');

        // From Summit Validate
        $('#ofmAddMessage').validate({
            rules: {
                oetMsgCode: {
                    "required": {
                        // ตรวจสอบเงื่อนไข validate
                        depends: function(oElement) {
                            if ($('#ocbMessageAutoGenCode').is(':checked')) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    },
                    "dublicateCode": {}
                },
                oetMsgName: {
                    "required": {}
                }
            },
            messages: {
                oetMsgCode: {
                    "required": $('#oetMsgCode').attr('data-validate-required'),
                    "dublicateCode": $('#oetMsgCode').attr('data-validate-dublicateCode')
                },
                oetMsgName: {
                    "required": $('#oetMsgName').attr('data-validate-required'),
                }
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                error.addClass("help-block");
                if (element.prop("type") === "checkbox") {
                    error.appendTo(element.parent("label"));
                } else {
                    var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                    if (tCheck == 0) {
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error").removeClass("has-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                var nStaCheckValid = $(element).parents('.form-group').find('.help-block').length
                if (nStaCheckValid != 0) {
                    $(element).closest('.form-group').addClass("has-success").removeClass("has-error");
                }
            },
            submitHandler: function(form) {}
        });
    }

    //BrowseAgn 
    $('#oimBrowseAgn').click(function(e) {
        e.preventDefault();
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JSxCheckPinMenuClose();
            window.oPdtBrowseAgencyOption = oBrowseAgn({
                'tReturnInputCode': 'oetMsgAgnCode',
                'tReturnInputName': 'oetMsgAgnName',
            });
            JCNxBrowseData('oPdtBrowseAgencyOption');
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;

    //Option Agn
    var oBrowseAgn = function(poReturnInput) {
        var tInputReturnCode = poReturnInput.tReturnInputCode;
        var tInputReturnName = poReturnInput.tReturnInputName;


        var oOptionReturn = {
            Title: ['ticket/agency/agency', 'tAggTitle'],
            Table: {
                Master: 'TCNMAgency',
                PK: 'FTAgnCode'
            },
            Join: {
                Table: ['TCNMAgency_L'],
                On: ['TCNMAgency_L.FTAgnCode = TCNMAgency.FTAgnCode AND TCNMAgency_L.FNLngID = ' + nLangEdits]
            },
            GrideView: {
                ColumnPathLang: 'ticket/agency/agency',
                ColumnKeyLang: ['tAggCode', 'tAggName'],
                ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMAgency.FTAgnCode', 'TCNMAgency_L.FTAgnName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMAgency.FDCreateOn DESC'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: [tInputReturnCode, "TCNMAgency.FTAgnCode"],
                Text: [tInputReturnName, "TCNMAgency_L.FTAgnName"],
            },
            RouteAddNew: 'agency',
            BrowseLev: 1,
        }
        return oOptionReturn;
    }


    var tStaUsrLevel = '<?php echo $this->session->userdata("tSesUsrLevel"); ?>';

    if (tStaUsrLevel == 'BCH' || tStaUsrLevel == 'SHP') {
        $('#oimBrowseAgn').attr("disabled", true);
    }
</script>