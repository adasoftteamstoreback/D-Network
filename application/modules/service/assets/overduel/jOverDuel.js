var nStaOdlBrowseType = $('#oetOdlStaBrowse').val();
var tCallOdlBackOption = $('#oetOdlCallBackOption').val();
$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxOdlNavDefult();
    if (nStaOdlBrowseType != 1) {
        JSvCallPageOdlList();
    } else {
        JSvCallPageOdlAdd();
    }
});


//function : Function Clear Defult Button Odl
//Parameters : Document Ready
//Creator : 9/08/2021 Paksaran(golf)
//Return : Show Tab Menu
//Return Type : -
function JSxOdlNavDefult() {
    if (nStaOdlBrowseType != 1 || nStaOdlBrowseType == undefined) {
        $('.xCNOdlVBrowse').hide();
        $('.xCNOdlVMaster').show();
        $('.xCNChoose').hide();
        $('#oliOdlTitleAdd').hide();
        $('#oliOdlTitleEdit').hide();
        $('#odvBtnAddEdit').hide();
        $('#odvBtnOdlInfo').show();
    } else {
        $('#odvModalBody .xCNOdlVMaster').hide();
        $('#odvModalBody .xCNOdlVBrowse').show();
        $('#odvModalBody #odvOdlMainMenu').removeClass('main-menu');
        $('#odvModalBody #oliOdlNavBrowse').css('padding', '2px');
        $('#odvModalBody #odvOdlBtnGroup').css('padding', '0');
        $('#odvModalBody .xCNOdlBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNOdlBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

//function : Function Show Event Error
//Parameters : Error Ajax Function
//Creator : 9/08/2021 Paksaran(golf)
//Return : Modal Status Error
//Return Type : view
function JCNxResponseError(jqXHR, textStatus, errorThrown) {
    JCNxCloseLoading();
    var tHtmlError = $(jqXHR.responseText);
    var tMsgError = "<h3 style='font-Odl:20px;color:red'>";
    tMsgError += "<i class='fa fa-exclamation-triangle'></i>";
    tMsgError += " Error<hr></h3>";
    switch (jqXHR.status) {
        case 404:
            tMsgError += tHtmlError.find('p:nth-child(2)').text();
            break;
        case 500:
            tMsgError += tHtmlError.find('p:nth-child(3)').text();
            break;

        default:
            tMsgError += 'something had error. please contact admin';
            break;
    }
    $("body").append(tModal);
    $('#modal-customs').attr("style", 'width: 450px; margin: 1.75rem auto;top:20%;');
    $('#myModal').modal({ show: true });
    $('#odvModalBody').html(tMsgError);
}

//function : Call  Odl Page list
//Parameters : Document Redy And Event Button
//Creator : 9/08/2021 Paksaran(golf)
//Return : View
//Return Type : View
function JSvCallPageOdlList() {
    localStorage.tStaPageNow = 'JSvCallPageOdlList';
    $('#oetSearchOdl').val('');
    $('#ocmSearchOdiType').val('');
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "masOdlList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $('#odvContentPageOdl').html(tResult);
            JSvOdlDataTable();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//function: Call Odl Data List
//Parameters: Ajax Success Event
//Creator : 9/08/2021 Paksaran(golf)
//Return: View
//Return Type: View
function JSvOdlDataTable(pnPage) {
    var tSearchAll = $('#oetSearchOdl').val();
    var tSearchAllType = $('#ocmSearchProductType').val();
    var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
    $.ajax({
        type: "POST",
        url: "masOdlDataTable",
        data: {
            tSearchAll: tSearchAll,
            tSearchAllType: tSearchAllType,
            nPageCurrent: nPageCurrent,
        },
        cache: false,
        Timeout: 0,
        success: function(tResult) {
            if (tResult != "") {
                $('#ostDataOdl').html(tResult);
            }
            JSxOdlNavDefult();
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : Call Odl Page Add
//Parameters : Event Button Click
//Creator : 21/06/2021 Pakkahwat
//Return : View
//Return Type : View
function JSvCallPageOdlAdd() {
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('', '');
    $.ajax({
        type: "POST",
        url: "masOdlPageAdd",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (nStaOdlBrowseType == 1) {
                $('.xCNOdlVMaster').hide();
                $('.xCNOdlVBrowse').show();
                $('#odvModalBodyBrowse').html(tResult);
                $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
            } else {
                $('.xCNOdlVBrowse').hide();
                $('.xCNOdlVMaster').show();
                $('#oliOdlTitleEdit').hide();
                $('#oliOdlTitleAdd').show();
                $('#odvBtnOdlInfo').hide();
                $('#odvBtnAddEdit').show();
            }
            $('#odvContentPageOdl').html(tResult);
            $('#ocbOdlAutoGenCode').change(function() {
                $("#oetOdlCode").val("");
                $("#ohdCheckDuplicateOdlCode").val("1");
                if ($('#ocbOdlAutoGenCode').is(':checked')) {
                    $("#oetOdlCode").attr("readonly", true);
                    $("#oetOdlCode").attr("onfocus", "this.blur()");
                    $('#ofmAddOdl').removeClass('has-error');
                    $('#ofmAddOdl em').remove();
                } else {
                    $("#oetOdlCode").attr("readonly", false);
                    $("#oetOdlCode").removeAttr("onfocus");
                }
            });
            $("#oetOdlCode").blur(function() {
                if (!$('#ocbOdlAutoGenCode').is(':checked')) {
                    if ($("#ohdCheckOdlClearValidate").val() == 1) {
                        $('#ofmAddOdl').validate().destroy();
                        $("#ohdCheckOdlClearValidate").val("0");
                    }
                    if ($("#ohdCheckOdlClearValidate").val() == 0) {
                        $.ajax({
                            type: "POST",
                            url: "CheckInputGenCode",
                            data: {
                                tTableName: "TCNMOverDueLev",
                                tFieldName: "FTOdlCode",
                                tCode: $("#oetOdlCode").val()
                            },
                            cache: false,
                            timeout: 0,
                            success: function(tResult) {
                                var aResult = JSON.parse(tResult);
                                $("#ohdCheckDuplicateOdlCode").val(aResult["tCode"]);
                                JSxValidationFormOdl("", $("#ohdPdtGroupRoute").val());
                                // $('#ofmAddPdtOdl').submit();

                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                JCNxResponseError(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }
                }
            });
            JCNxLayoutControll();
            JCNxCloseLoading();

        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : center validate form
//Parameters : function submit name, route
//Creator : 9/08/2021 Paksaran(golf)
//Update : 21/06/2021
//Return : -
//Return Type : -
function JSxValidationFormOdl(pFnSubmitName, ptRoute) {
    $.validator.addMethod('dublicateCode', function(value, element) {
        if (ptRoute == "masOdlEventAdd") {
            if ($('#ocbOdlAutoGenCode').is(':checked')) {
                return true;
            } else {
                if ($("#ohdCheckDuplicateOdlCode").val() == 1) {
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            return true;
        }
    }, '');
    $('#ofmAddOdl').validate({
        rules: {
            oetOdlCode: {
                "required": {
                    // ตรวจสอบเงื่อนไข validate
                    depends: function(oElement) {
                        if (ptRoute == "masOdlEventAdd") {
                            if ($('#ocbOdlAutoGenCode').is(':checked')) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return false;
                        }
                    }
                },
                "dublicateCode": {}
            }
        },
        messages: {
            oetOdlCode: {
                "required": $('#oetOdlCode').attr('data-validate-required'),
                "dublicateCode": $('#oetOdlCode').attr('data-validate-dublicateCode')
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
            $(element).closest('.form-group').addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
        },
        submitHandler: function(form) {
        }
    });


}

//Functionality : function submit by submit button only
//Parameters : route
//Creator : 9/08/2021 Paksaran(golf)
//Update : 21/06/2021
//Return : -
//Return Type : -
function JSxSubmitEventByButton(ptRoute) {

    if ($("#ohdCheckOdlClearValidate").val() == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: ptRoute,
            data: $('#ofmAddOdl').serialize(),
            cache: false,
            timeout: 0,
            success: function(oResult) {

                if (nStaOdlBrowseType != 1) {
                    var aReturn = JSON.parse(oResult);
                    if (aReturn['nStaEvent'] == 1) {
                        switch (aReturn['nStaCallBack']) {
                            case '1':
                                JSvCallPageOdlEdit(aReturn['tCodeReturn']);
                                break;
                            case '2':
                                JSvCallPageOdlAdd();
                                break;
                            case '3':
                                JSvCallPageOdlList();
                                break;
                            default:
                                JSvCallPageOdlEdit(aReturn['tCodeReturn']);
                        }
                    } else {
                        alert(aReturn['tStaMessg']);
                    }
                } else {
                    JCNxCloseLoading();
                    JCNxBrowseData(tCallOdlBackOption);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

//Functionality : Call  Odl Page Edit
//Parameters : Event Button Click
//Creator : 9/08/2021 Paksaran(golf)
//Return : View
//Return Type : View
function JSvCallPageOdlEdit(ptOdlCode) {
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('JSvCallPageOdlEdit', ptOdlCode);
    $.ajax({
        type: "POST",
        url: "masOdlPageEdit",
        data: { tOdlCode: ptOdlCode},
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (tResult != '') {
                $('#oliOdlTitleAdd').hide();
                $('#oliOdlTitleEdit').show();
                $('#odvBtnOdlInfo').hide();
                $('#odvBtnAddEdit').show();
                $('#odvContentPageOdl').html(tResult);
                $('#oetOdlCode').addClass('xCNDisable');
                $('#oetOdlCode').attr('readonly', true);
                $('.xCNBtnGenCode').attr('disabled', true);
            }
            JCNxLayoutControll();
            JCNxCloseLoading();

        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}
//Functionality : Add Data Odl Add/Edit
//Parameters : from ofmAddPdtOdl
//Creator : 9/08/2021 Paksaran(golf)
//Return : View
//Return Type : View
function JSoAddEditOdl(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    var tocmType = $("#ocmOdlTpye").val();
    var toldmin = $("#oetOdlMin").val();
    var toldmax = $("#oetOdlMax").val();
    if(toldmin === ''){
        toldmin = 0;
    }
    if(toldmax === ''){
        toldmax = 0;
    }
    var noldmin = parseInt(toldmin);
    var noldmax = parseInt(toldmax);
    if(noldmax < noldmin){
        FSvCMNSetMsgErrorDialog('ถึงจำนวนวัน ต้องมากกว่า จำนวนวัน');
        return false;
    }else if(noldmin == '0' && noldmax == '0'){
        FSvCMNSetMsgErrorDialog('จำนวนวัน และ ถึงจำนวนวัน ต้องไม่เป็น 0');
        return false;
    }else if(noldmin == noldmax){
        FSvCMNSetMsgErrorDialog('จำนวนวัน และ ถึงจำนวนวัน ต้องไม่เท่ากัน');
        return false;
    }else if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddOdl').validate().destroy();
        $.validator.addMethod('dublicateCode', function(value, element) {
            if (ptRoute == "masOdlEventAdd") {
                if ($("#ohdCheckDuplicateOdlCode").val() == 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }, '');
        $('#ofmAddOdl').validate({
            rules: {
                oetOdlCode: {
                    "required": {
                        depends: function(oElement) {
                            if (ptRoute == "masOdlEventAdd") {
                                if ($('#ocbOdlAutoGenCode').is(':checked')) {
                                    return false;
                                } else {
                                    return true;
                                }
                            } else {
                                return true;
                            }
                        }
                    },
                    "dublicateCode": {}
                },

            },
            messages: {
                oetOdlCode: {
                    "required": $('#oetOdlCode').attr('data-validate-required'),
                    "dublicateCode": $('#oetOdlCode').attr('data-validate-dublicateCode')
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
                $(element).closest('.form-group').addClass("has-success").removeClass("has-error");
            },
            submitHandler: function(form) {
                JSnCheckDataOverDuelDupInDB(ptRoute);
                
            },
        });
    }
}

function JSnCheckDataOverDuelDupInDB(ptRoute){
    let aDataChk    = {
        'tOdlCode'      : $('#oetOdlCode').val(),
        'tOdlAgnCode'   : $('#oetOdlAgnCode').val(),
        'tOdlTpye'      : $('#ocmOdlTpye').val(),
        'tOdlMin'       : $('#oetOdlMin').val(),
        'tOdlMax'       : $('#oetOdlMax').val(),
    };
    $.ajax({
        type    : "POST",
        url     : "masOdlChkDupMinMax",
        data    : aDataChk,
        cache   : false,
        timeout: 0,
        success: function(nChkDup){
            if(nChkDup == 0){
                $.ajax({
                    cache: false,
                    timeout: 0,
                    type: "POST",
                    url: ptRoute,
                    data: $('#ofmAddOdl').serialize(),
                    success: function(tResult){
                        if (nStaOdlBrowseType != 1) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                if (aReturn['nStaCallBack'] == '1' || aReturn['nStaCallBack'] == null) {
                                    JSvCallPageOdlEdit(aReturn['tCodeReturn'])
                                } else if (aReturn['nStaCallBack'] == '2') {
                                    JSvCallPageOdlAdd();
                                } else if (aReturn['nStaCallBack'] == '3') {
                                    JSvCallPageOdlList();
                                }
                            } else {
                                // alert(aReturn['tStaMessg']);
                                FSvCMNSetMsgErrorDialog('มีรหัสนี้ในระบบแล้ว');
                            }
                        } else {
                            JCNxBrowseData(tCallOdlBackOption);
                            JSvOdlDataTable();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }else{
                var tMessageError   = 'พบข้อมูลระดับวันครบกำหนดชำระนี้แล้วในระบบ กรุณาตรวจสอบข้อมูลอีกครั้ง';
                FSvCMNSetMsgErrorDialog(tMessageError);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}







//Functionality : Generate Code Odl
//Parameters : Event Button Click
//Creator : 9/08/2021 Paksaran(golf)
//Return : Event Push Value In Input
//Return Type : -
function JStGenerateOdlCode() {
    $('#oetOdlCode').parent().removeClass('alert-validate');
    var tTableName = 'TCNMOverDueLev';
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "generateCode",
        data: { tTableName: tTableName },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            var tData = $.parseJSON(tResult);
            if (tData.tCode == '1') {
                $('#oetOdlCode').val(tData.rtOdlCode);
                $('#oetOdlCode').addClass('xCNDisable');
                $('#oetOdlCode').attr('readonly', true);
                $('.xCNBtnGenCode').attr('disabled', true); //เปลี่ยน Class ใหม่
                $('#oetOdlName').focus();
            } else {
                $('#oetOdlCode').val(tData.tCode);
            }
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : Event Single Delete
//Parameters : Event Icon Delete
//Creator : 9/08/2021 Paksaran(golf)
//Return : object Status Delete
//Return Type : object
function JSoOdlDel(pnPage, tIDCode, tYesOnNo) {
    var aData = $('#ohdConfirmIDDelete').val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];

    if (aDataSplitlength == '1') {

        $('#odvModalDelOdl').modal('show');
        $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode  + tYesOnNo);
        $('#osmConfirm').on('click', function(evt) {

            if (localStorage.StaDeleteArray != '1') {

                $.ajax({
                    type: "POST",
                    url: "masOdlEventDelete",
                    data: { 'tIDCode': tIDCode },
                    cache: false,
                    success: function(tResult) {
                        tResult = tResult.trim();
                        var aReturn = $.parseJSON(tResult);
                        if (aReturn['nStaEvent'] == '1') {
                            $('#odvModalDelOdl').modal('hide');
                            $('#ospConfirmDelete').empty();
                            localStorage.removeItem('LocalItemData');
                            $('#ospConfirmIDDelete').val('');
                            $('#ohdConfirmIDDelete').val('');
                            setTimeout(function() {
                                if (aReturn["nNumRowOdl"] != 0) {
                                    if (aReturn["nNumRowOdl"] > 10) {
                                        nNumPage = Math.ceil(aReturn["nNumRowOdl"] / 10);
                                        if (pnPage <= nNumPage) {
                                            JSvOdlDataTable(pnPage);
                                        } else {
                                            JSvOdlDataTable(nNumPage);
                                        }
                                    } else {
                                        JSvOdlDataTable(1);
                                    }
                                } else {
                                    JSvOdlDataTable(1);
                                }
                            }, 500);
                        } else {
                            JCNxOpenLoading();
                            alert(aReturn['tStaMessg']);
                        }
                        JSxOdlNavDefult();

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }


        });
    }
}
//Functionality: Event Multi Delete
//Parameters: Event Button Delete All
//Creator : 9/08/2021 Paksaran(golf)
//Return:  object Status Delete
//Return Type: object
function JSoOdlDelChoose(pnPage) {
    JCNxOpenLoading();
    var aData = $('#ohdConfirmIDDelete').val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];

    for ($i = 0; $i < aDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }

    if (aDataSplitlength > 1) {

        localStorage.StaDeleteArray = '1';

        $.ajax({
            type: "POST",
            url: "masOdlEventDelete",
            data: { 'tIDCode': aNewIdDelete },
            success: function(tResult) {
                tResult = tResult.trim();
                var aReturn = $.parseJSON(tResult);

                if (aReturn['nStaEvent'] == '1') {
                    $('#odvModalDelOdl').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    $('#ospConfirmIDDelete').val('');
                    $('#ohdConfirmIDDelete').val('');
                    setTimeout(function() {
                        if (aReturn["nNumRowOdl"] != 0) {
                            if (aReturn["nNumRowOdl"] > 10) {
                                nNumPage = Math.ceil(aReturn["nNumRowOdl"] / 10);
                                if (pnPage <= nNumPage) {
                                    JSvOdlDataTable(pnPage);
                                } else {
                                    JSvOdlDataTable(nNumPage);
                                }
                            } else {
                                JSvOdlDataTable(1);
                            }
                        } else {
                            JSvOdlDataTable(1);
                        }
                    }, 500);
                } else {
                    JCNxOpenLoading();
                    alert(aReturn['tStaMessg']);
                }
                JSxOdlNavDefult();


            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });


    } else {
        localStorage.StaDeleteArray = '0';

        return false;
    }
}

//Functionality : เปลี่ยนหน้า pagenation
//Parameters : Event Click Pagenation
//Creator : 9/08/2021 Paksaran(golf)
//Return : View
//Return Type : View
function JSvOdlClickPage(ptPage) {
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageOdl .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageOdl .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvOdlDataTable(nPageCurrent);
}

//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator : 9/08/2021 Paksaran(golf)
//Return: -
//Return Type: -
function JSxShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == '') {
        $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $('#odvMngTableList #oliBtnDeleteAll').removeClass('disabled');
        } else {
            $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
        }
    }
}

//Functionality: Insert Text In Modal Delete
//Parameters: LocalStorage Data
//Creator : 9/08/2021 Paksaran(golf)
//Return: -
//Return Type: -
function JSxTextinModal() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == '') {} else {
        var tTextCode = '';
        for ($i = 0; $i < aArrayConvert[0].length; $i++) {
            tTextCode += aArrayConvert[0][$i].nCode;
            tTextCode += ' , ';
        }
        $('#ospConfirmDelete').text($('#oetTextComfirmDeleteMulti').val());
        $('#ohdConfirmIDDelete').val(tTextCode);
    }
}

//Functionality: Function Chack Value LocalStorage
//Parameters: Event Select List Reason
//Creator : 9/08/2021 Paksaran(golf)
//Return: Duplicate/none
//Return Type: string
function findObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return 'Dupilcate';
        }
    }
    return 'None';
}
