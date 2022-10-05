var nStaLotBrowseType = $('#oetLotStaBrowse').val();
var tCallLotBackOption = $('#oetLotCallBackOption').val();
$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxLotNavDefult();
    if (nStaLotBrowseType != 1) {
        JSvCallPagePdtLotList();
    } else {
        JSvCallPagePdtLotAdd();
    }
});

//function : Function Clear Defult Button Product Lot
//Parameters : Document Ready
//Creator : 21/06/2021 Pakkahwat
//Return : Show Tab Menu
//Return Type : -
function JSxLotNavDefult() {
    if (nStaLotBrowseType != 1 || nStaLotBrowseType == undefined) {
        $('.xCNLotVBrowse').hide();
        $('.xCNLotVMaster').show();
        $('.xCNChoose').hide();
        $('#oliLotTitleAdd').hide();
        $('#oliLotTitleEdit').hide();
        $('#odvBtnAddEdit').hide();
        $('#odvBtnLotInfo').show();
    } else {
        $('#odvModalBody .xCNLotVMaster').hide();
        $('#odvModalBody .xCNLotVBrowse').show();
        $('#odvModalBody #odvLotMainMenu').removeClass('main-menu');
        $('#odvModalBody #oliLotNavBrowse').css('padding', '2px');
        $('#odvModalBody #odvLotBtnGroup').css('padding', '0');
        $('#odvModalBody .xCNLotBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNLotBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

//function : Function Show Event Error
//Parameters : Error Ajax Function 
//Creator : 17/10/2018 witsarut
//Return : Modal Status Error
//Return Type : view
function JCNxResponseError(jqXHR, textStatus, errorThrown) {
    JCNxCloseLoading();
    var tHtmlError = $(jqXHR.responseText);
    var tMsgError = "<h3 style='font-Lot:20px;color:red'>";
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

//function : Call Product Lot Page list  
//Parameters : Document Redy And Event Button
//Creator :	21/06/2021 Pakkahwat
//Return : View
//Return Type : View
function JSvCallPagePdtLotList() {
    localStorage.tStaPageNow = 'JSvCallPagePdtLotList';
    $('#oetSearchPdtLot').val('');
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "maslotList",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $('#odvContentPagePdtLot').html(tResult);
            JSvPdtLotDataTable();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//function: Call Product Lot Data List
//Parameters: Ajax Success Event 
//Creator:	21/06/2021 Pakkahwat
//Return: View
//Return Type: View
function JSvPdtLotDataTable(pnPage) {
    var tSearchAll = $('#oetSearchPdtLot').val();
    var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
    $.ajax({
        type: "POST",
        url: "maslotDataTable",
        data: {
            tSearchAll: tSearchAll,
            nPageCurrent: nPageCurrent,
        },
        cache: false,
        Timeout: 0,
        success: function(tResult) {
            if (tResult != "") {
                $('#ostDataPdtLot').html(tResult);
            }
            JSxLotNavDefult();
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : Call Product Lot Page Add  
//Parameters : Event Button Click
//Creator : 21/06/2021 Pakkahwat
//Return : View
//Return Type : View
function JSvCallPagePdtLotAdd() {
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('', '');
    $.ajax({
        type: "POST",
        url: "maslotPageAdd",
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (nStaLotBrowseType == 1) {
                $('.xCNLotVMaster').hide();
                $('.xCNLotVBrowse').show();
                $('#odvModalBodyBrowse').html(tResult);
                $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
            } else {
                $('.xCNLotVBrowse').hide();
                $('.xCNLotVMaster').show();
                $('#oliLotTitleEdit').hide();
                $('#oliLotTitleAdd').show();
                $('#odvBtnLotInfo').hide();
                $('#odvBtnAddEdit').show();
            }
            $('#odvContentPagePdtLot').html(tResult);
            $('#ocbLotAutoGenCode').change(function() {
                $("#oetLotCode").val("");
                $("#ohdCheckDuplicateLotCode").val("1");
                if ($('#ocbLotAutoGenCode').is(':checked')) {
                    $("#oetLotCode").attr("readonly", true);
                    $("#oetLotCode").attr("onfocus", "this.blur()");
                    $('#ofmAddPdtLot').removeClass('has-error');
                    $('#ofmAddPdtLot em').remove();
                } else {
                    $("#oetLotCode").attr("readonly", false);
                    $("#oetLotCode").removeAttr("onfocus");
                }
            });
            $("#oetLotCode").blur(function() {
                if (!$('#ocbLotAutoGenCode').is(':checked')) {
                    if ($("#ohdCheckLotClearValidate").val() == 1) {
                        $('#ofmAddPdtLot').validate().destroy();
                        $("#ohdCheckLotClearValidate").val("0");
                    }
                    if ($("#ohdCheckLotClearValidate").val() == 0) {
                        $.ajax({
                            type: "POST",
                            url: "CheckInputGenCode",
                            data: {
                                tTableName: "TCNMLot",
                                tFieldName: "FTLotNo",
                                tCode: $("#oetLotCode").val()
                            },
                            cache: false,
                            timeout: 0,
                            success: function(tResult) {
                                var aResult = JSON.parse(tResult);
                                $("#ohdCheckDuplicateLotCode").val(aResult["tCode"]);
                                JSxValidationFormLot("", $("#ohdPdtGroupRoute").val());
                                // $('#ofmAddPdtLot').submit();

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
//Creator : 21/06/2021 Pakkahwat
//Update : 21/06/2021
//Return : -
//Return Type : -
function JSxValidationFormLot(pFnSubmitName, ptRoute) {
    $.validator.addMethod('dublicateCode', function(value, element) {
        if (ptRoute == "maslotEventAdd") {
            if ($('#ocbLotAutoGenCode').is(':checked')) {
                return true;
            } else {
                if ($("#ohdCheckDuplicateLotCode").val() == 1) {
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            return true;
        }
    }, '');
    $('#ofmAddPdtLot').validate({
        rules: {
            oetLotCode: {
                "required": {
                    // ตรวจสอบเงื่อนไข validate
                    depends: function(oElement) {
                        if (ptRoute == "maslotEventAdd") {
                            if ($('#ocbLotAutoGenCode').is(':checked')) {
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
            oetLotCode: {
                "required": $('#oetLotCode').attr('data-validate-required'),
                "dublicateCode": $('#oetLotCode').attr('data-validate-dublicateCode')
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
        submitHandler: function(form) {}
    });


}

//Functionality : function submit by submit button only
//Parameters : route
//Creator : 21/06/2021 Pakkahwat
//Update : 21/06/2021
//Return : -
//Return Type : -
function JSxSubmitEventByButton(ptRoute) {

    if ($("#ohdCheckLotClearValidate").val() == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: ptRoute,
            data: $('#ofmAddPdtLot').serialize(),
            cache: false,
            timeout: 0,
            success: function(oResult) {
                if (nStaLotBrowseType != 1) {
                    var aReturn = JSON.parse(oResult);
                    if (aReturn['nStaEvent'] == 1) {
                        switch (aReturn['nStaCallBack']) {
                            case '1':
                                JSvCallPagePdtLotEdit(aReturn['tCodeReturn']);
                                break;
                            case '2':
                                JSvCallPagePdtLotAdd();
                                break;
                            case '3':
                                JSvCallPagePdtLotList();
                                break;
                            default:
                                JSvCallPagePdtLotEdit(aReturn['tCodeReturn']);
                        }
                    } else {
                        alert(aReturn['tStaMessg']);
                    }
                } else {
                    JCNxCloseLoading();
                    JCNxBrowseData(tCallLotBackOption);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

//Functionality : Call Product Lot Page Edit  
//Parameters : Event Button Click 
//Creator : 21/06/2021 Pakkahwat
//Return : View
//Return Type : View
function JSvCallPagePdtLotEdit(ptLotNo) {
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('JSvCallPagePdtLotEdit', ptLotNo);
    $.ajax({
        type: "POST",
        url: "maslotPageEdit",
        data: { tLotNo: ptLotNo },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (tResult != '') {
                $('#oliLotTitleAdd').hide();
                $('#oliLotTitleEdit').show();
                $('#odvBtnLotInfo').hide();
                $('#odvBtnAddEdit').show();
                $('#odvContentPagePdtLot').html(tResult);
                $('#oetLotCode').addClass('xCNDisable');
                $('#oetLotCode').attr('readonly', true);
                $('.xCNBtnGenCode').attr('disabled', true);
                $('#odvBAMInfoTab').hide();
            }

            $.ajax({
                type: "POST",
                url: "maslotBAMDataTable",
                data: {
                    tSearchAll: $('#oetSearchBAMDevice').val(),
                    nPageCurrent: 1,
                    tDotNo: ptLotNo
                },
                cache: false,
                Timeout: 0,
                success: function(tResult){
                    $('#odvBAMContentPage').html(tResult);
                    $('#obtSearchBAMDevice').click(function(){
                        var tLotCode = $('#oetLotCode').val();
                        JCNxOpenLoading();
                        JSvDotBAMDataTable(1,tLotCode);
                    });
                    JSxDotBAMNavDefult();
                    JCNxLayoutControll();
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}


//Functionality : Add Data Product Lot Add/Edit  
//Parameters : from ofmAddPdtLot
//Creator : 21/06/2021 Pakkahwat
//Return : View
//Return Type : View
function JSoAddEditPdtLot(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddPdtLot').validate().destroy();
        $.validator.addMethod('dublicateCode', function(value, element) {
            if (ptRoute == "maslotEventAdd") {
                if ($("#ohdCheckDuplicateLotCode").val() == 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }, '');
        $('#ofmAddPdtLot').validate({
            rules: {
                oetLotCode: {
                    "required": {
                        depends: function(oElement) {
                            if (ptRoute == "maslotEventAdd") {
                                if ($('#ocbLotAutoGenCode').is(':checked')) {
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
                oetLotBatchNo: { "required": {} }
            },
            messages: {
                oetLotCode: {
                    "required": $('#oetLotCode').attr('data-validate-required'),
                    "dublicateCode": $('#oetLotCode').attr('data-validate-dublicateCode')
                },
                oetLotBatchNo: {
                    "required": $('#oetLotBatchNo').attr('data-validate-required'),
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
                $.ajax({
                    cache: false,
                    timeout: 0,
                    type: "POST",
                    url: ptRoute,
                    data: $('#ofmAddPdtLot').serialize(),
                    success: function(tResult) {
                        if (nStaLotBrowseType != 1) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                if (aReturn['nStaCallBack'] == '1' || aReturn['nStaCallBack'] == null) {
                                    JSvCallPagePdtLotEdit(aReturn['tCodeReturn'])
                                } else if (aReturn['nStaCallBack'] == '2') {
                                    JSvCallPagePdtLotAdd();
                                } else if (aReturn['nStaCallBack'] == '3') {
                                    JSvCallPagePdtLotList();
                                }
                            } else {
                                alert(aReturn['tStaMessg']);
                            }
                        } else {
                            JCNxBrowseData(tCallLotBackOption);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    }
}


//Functionality : Generate Code Product Lot
//Parameters : Event Button Click
//Creator : 21/06/2021 Pakkahwat
//Return : Event Push Value In Input
//Return Type : -
function JStGeneratePdtLotCode() {
    $('#oetLotCode').parent().removeClass('alert-validate');
    var tTableName = 'TCNMLot';
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
                $('#oetLotCode').val(tData.rtLotCode);
                $('#oetLotCode').addClass('xCNDisable');
                $('#oetLotCode').attr('readonly', true);
                $('.xCNBtnGenCode').attr('disabled', true); //เปลี่ยน Class ใหม่
                $('#oetLotName').focus();
            } else {
                $('#oetLotCode').val(tData.tCode);
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
//Creator : 21/06/2021 Pakkahwat
//Return : object Status Delete
//Return Type : object
function JSoPdtLotDel(pnPage, tIDCode, tYesOnNo) {
    var aData = $('#ohdConfirmIDDelete').val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];

    if (aDataSplitlength == '1') {

        $('#odvModalDelPdtLot').modal('show');
        $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode + tYesOnNo);
        $('#osmConfirm').on('click', function(evt) {

            if (localStorage.StaDeleteArray != '1') {

                $.ajax({
                    type: "POST",
                    url: "maslotEventDelete",
                    data: { 'tIDCode': tIDCode },
                    cache: false,
                    success: function(tResult) {
                        tResult = tResult.trim();
                        var aReturn = $.parseJSON(tResult);

                        if (aReturn['nStaEvent'] == '1') {
                            $('#odvModalDelPdtLot').modal('hide');
                            $('#ospConfirmDelete').empty();
                            localStorage.removeItem('LocalItemData');
                            $('#ospConfirmIDDelete').val('');
                            $('#ohdConfirmIDDelete').val('');
                            setTimeout(function() {
                                if (aReturn["nNumRowLot"] != 0) {
                                    if (aReturn["nNumRowLot"] > 10) {
                                        nNumPage = Math.ceil(aReturn["nNumRowLot"] / 10);
                                        if (pnPage <= nNumPage) {
                                            JSvPdtLotDataTable(pnPage);
                                        } else {
                                            JSvPdtLotDataTable(nNumPage);
                                        }
                                    } else {
                                        JSvPdtLotDataTable(1);
                                    }
                                } else {
                                    JSvPdtLotDataTable(1);
                                }
                            }, 500);
                        } else {
                            JCNxOpenLoading();
                            alert(aReturn['tStaMessg']);
                        }
                        JSxLotNavDefult();

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
//Creator: 21/06/2021 Pakkahwat
//Return:  object Status Delete
//Return Type: object
function JSoPdtLotDelChoose(pnPage) {
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
            url: "maslotEventDelete",
            data: { 'tIDCode': aNewIdDelete },
            success: function(tResult) {
                tResult = tResult.trim();
                var aReturn = $.parseJSON(tResult);

                if (aReturn['nStaEvent'] == '1') {
                    $('#odvModalDelPdtLot').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    $('#ospConfirmIDDelete').val('');
                    $('#ohdConfirmIDDelete').val('');
                    setTimeout(function() {
                        if (aReturn["nNumRowLot"] != 0) {
                            if (aReturn["nNumRowLot"] > 10) {
                                nNumPage = Math.ceil(aReturn["nNumRowLot"] / 10);
                                if (pnPage <= nNumPage) {
                                    JSvPdtLotDataTable(pnPage);
                                } else {
                                    JSvPdtLotDataTable(nNumPage);
                                }
                            } else {
                                JSvPdtLotDataTable(1);
                            }
                        } else {
                            JSvPdtLotDataTable(1);
                        }
                    }, 500);
                } else {
                    JCNxOpenLoading();
                    alert(aReturn['tStaMessg']);
                }
                JSxLotNavDefult();


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
//Creator : 21/06/2021 Pakkahwat
//Return : View
//Return Type : View
function JSvPdtLotClickPage(ptPage) {
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPagePdtLot .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPagePdtLot .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvPdtLotDataTable(nPageCurrent);
}

//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator: 21/06/2021 Pakkahwat
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
//Creator: 21/06/2021 Pakkahwat
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
//Creator: 21/06/2021 Pakkahwat
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

function JSxDotBAMNavDefult(){
    if(nStaLotBrowseType != 1 || nStaLotBrowseType == undefined){
        $('#oliDotBAMTitleAdd').hide();
        $('#oliDotBAMTitleEdit').hide();
        $('#odvBtnDotBAMAddEdit').hide();
        $('#odvBtnDotBAMInfo').show();
        $('#odvBtnDotBAMSearch').show();
        $('#odvDotBAMTableList').show();
    }
}
