var nStaMsgBrowseType = $('#oetMsgStaBrowse').val();
var tCallMsgBackOption = $('#oetMsgCallBackOption').val();
$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxMsgNavDefult();
    if (nStaMsgBrowseType != 1) {
        JSvCallPageMessageList();
    } else {
        JSvCallPageMessageAdd();
    }
});


///function : Function Clear Defult Button Message
//Parameters : -
//Creator : 08/06/2021 Off
//Update:   
//Return : -
//Return Type : -
function JSxMsgNavDefult() {
    // Menu Bar เข้ามาจาก หน้า Master หรือ Browse
    if (nStaMsgBrowseType != 1) { // เข้ามาจาก  Master
        $('.obtChoose').hide();
        $('#oliMsgTitleAdd').hide();
        $('#oliMsgTitleEdit').hide();
        $('#odvBtnMsgAddEdit').hide();
        $('#odvBtnMsgInfo').show();
    } else { // เข้ามาจาก Browse Modal
        $('#odvModalBody #odvMsgMainMenu').removeClass('main-menu');
        $('#odvModalBody .xCNMsgBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNMsgBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

///function : Call Message Page list  
//Parameters : - 
//Creator:	08/06/2021 Off
//Update:   
//Return : View
//Return Type : View
function JSvCallPageMessageList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        localStorage.tStaPageNow = 'JSvCallPageMessageList';
        $('#oetSearchAll').val('');
        $.ajax({
            type: "POST",
            url: "messageList",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $('#odvContentPageMessage').html(tResult);
                JSvMessageDataTable(pnPage);
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

///function : Call Message Data List
//Parameters : Ajax Success Event 
//Creator:	04/06/2021 Off
//Update:   
//Return : View
//Return Type : View
function JSvMessageDataTable(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var tSearchAll = $('#oetSearchAll').val();
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == '') {
            nPageCurrent = '1';
        }
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "messageDataTable",
            data: {
                tSearchAll: tSearchAll,
                nPageCurrent: nPageCurrent,
            },
            cache: false,
            Timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#ostDataMessage').html(tResult);
                    $(".xCNDocDisabled").removeAttr("onclick");
                }
                JSxMsgNavDefult();
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : Call Message Page Add  
//Parameters : -
//Creator : 19/05/2021 Off
//Update: 
//Return : View
//Return Type : View
function JSvCallPageMessageAdd() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('', '');
        $.ajax({
            type: "POST",
            url: "messagePageAdd",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (nStaMsgBrowseType == 1) {
                    $('#odvModalBodyBrowse').html(tResult);
                    $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                } else {
                    $('#oliMsgTitleEdit').hide();
                    $('#oliMsgTitleAdd').show();
                    $('#odvBtnMsgInfo').hide();
                    $('#odvBtnMsgAddEdit').show();
                }
                $('#odvContentPageMessage').html(tResult);
                JCNxLayoutControll();
                JCNxCloseLoading();
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : Call Calendar Page Edit  
//Parameters : -
//Creator: 19/05/2021 Off
//Update: 
//Return : View
//Return Type : View
function JSvCallPageMessageEdit(ptMshCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('JSvCallPageMessageEdit', ptMshCode);
        $.ajax({
            type: "POST",
            url: "messagePageEdit",
            data: { tMshCode: ptMshCode },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#oliMsgTitleAdd').hide();
                    $('#oliMsgTitleEdit').show();
                    $('#odvBtnMsgInfo').hide();
                    $('#odvBtnMsgAddEdit').show();
                    $('#odvContentPageMessage').html(tResult);
                    $('#oetMsgCode').addClass('xCNDisable');
                    $('.xCNDisable').attr('readonly', true);
                    $('.xCNiConGen').attr('disabled', true);
                }
                $.ajax({
                    type: "POST",
                    url: "calendaruserDataTable",
                    data: {
                        tSearchAll: "",
                        nPageCurrent: 1,
                        nPosCode: ptMshCode
                    },
                    cache: false,
                    Timeout: 0,
                    success: function(tResult){
                        JCNxLayoutControll();
                        JCNxCloseLoading();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
                // JCNxLayoutControll();
                // JCNxCloseLoading();
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//function : Function Clear Defult Button UserCalendar
//Parameters : Document Ready
//Creator : 01/06/2021 Off
//Return : Show Tab Menu
//Return Type : -
function JSxCldUserCalendarNavDefult(){
    if(nStaMsgBrowseType != 1 || nStaMsgBrowseType == undefined){
        $('.xCNChoose').hide();
        $('#oliCldUserTitleAdd').hide();
        $('#oliCldUserTitleEdit').hide();
        $('#oliCldUserTitleAddPageDivice').hide();
        $('#odvBtnMsgUserAddEdit').hide();
        $('#odvBtnMsgUserInfo').show();
    }
}

//Functionality : (event) Add/Edit Message
//Parameters : form
//Creator : 19/05/2021 Off
//Return : object Status Event And Event Call Back
//Return Type : object
function JSnAddEditMessage(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddMessage').validate().destroy();
        
        $.validator.addMethod('dublicateCode', function(value, element) {
            if (ptRoute == "calendarEventAdd") {
                if ($("#ohdCheckDuplicateObjCode").val() == 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }, '');


        $('#ofmAddMessage').validate({
            rules: {
                oetMsgCode: {
                    "required": {
                        depends: function(oElement) {
                            if (ptRoute == "calendarEventAdd") {
                                if ($('#ocbMessageAutoGenCode').is(':checked')) {
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
                oetMsgCode: { "required": {} },
                oetMsgName: { "required": {} },
                oetMsgValue1: { "required": {} },
            },
            messages: {
                oetMsgCode: {
                    "required": $('#oetMsgCode').attr('data-validate-required'),
                    "dublicateCode": $('#oetMsgCode').attr('data-validate-dublicateCode')
                },
                oetMsgName: {
                    "required": $('#oetMsgName').attr('data-validate-required'),
                },
                oetMsgValue1: {
                    "required": $('#oetMsgValue1').attr('data-validate-required'),
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
            submitHandler: function(form) {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: ptRoute,
                    data: $('#ofmAddMessage').serialize(),
                    async: false,
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        if (nStaMsgBrowseType != 1) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                if (aReturn['nStaCallBack'] == '1' || aReturn['nStaCallBack'] == null) {
                                    JSvCallPageMessageEdit(aReturn['tCodeReturn']);
                                } else if (aReturn['nStaCallBack'] == '2') {
                                    JSvCallPageMessageAdd();
                                } else if (aReturn['nStaCallBack'] == '3') {
                                    JSvCallPageMessageList();
                                }
                            } else {
                                alert(aReturn['tStaMessg']);
                            }
                        } else {
                            JCNxCloseLoading();
                            JCNxBrowseData(tCallMsgBackOption);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality: เปลี่ยนหน้า pagenation
//Parameters: -
//Creator: 25/05/2021 Off
//Update: 
//Return: View
//Return Type: View
function JSvClickPage(ptPage) {
    var nPageCurrent = '';
    var nPageNew;
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageCalendarGrp .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew;
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageCalendarGrp .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew;
            break;
        default:
            nPageCurrent = ptPage;
    }
    JSvMessageDataTable(nPageCurrent);
}

//Functionality: (event) Delete
//Parameters: Button Event [tIDCode รหัสเหตุผล]
//Creator: 19/05/2021 Off
//Update: 
//Return: Event Delete Calendar List
//Return Type: -

function JSnMessageDel(pnCurrentPage, ptDelName, ptIDCode, ptYesOnNo) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var aData = $('#ohdConfirmIDDelete').val();
        var aTexts = aData.substring(0, aData.length - 2);
        var aDataSplit = aTexts.split(" , ");
        var aDataSplitlength = aDataSplit.length;
        var aNewIdDelete = [];
        if (aDataSplitlength == '1') {
            $('#odvModalDelMessage').modal('show');
            // $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode);
            $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptIDCode + ' ( ' + ptDelName + ' ) ' + ptYesOnNo);
            $('#osmConfirm').off('click').on('click', function(evt) {
                if (localStorage.StaDeleteArray != '1') {
                    $.ajax({
                        type: "POST",
                        url: "messageEventDelete",
                        data: { 'tIDCode': ptIDCode },
                        cache: false,
                        success: function(tResult) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                $('#odvModalDelMessage').modal('hide');
                                $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                                $('#ohdConfirmIDDelete').val('');
                                localStorage.removeItem('LocalItemData');
                                $('.modal-backdrop').remove();
                                setTimeout(function() {
                                    if (aReturn["nNumRowCldLoc"] != 0) {
                                        if (aReturn["nNumRowCldLoc"] > 10) {
                                            nNumPage = Math.ceil(aReturn["nNumRowCldLoc"] / 10);
                                            if (pnCurrentPage <= nNumPage) {
                                                JSvCallPageMessageList(pnCurrentPage);
                                            } else {
                                                JSvCallPageMessageList(nNumPage);
                                            }
                                        } else {
                                            JSvCallPageMessageList(1);
                                        }
                                    } else {
                                        JSvCallPageMessageList(1);
                                    }
                                    // JSvBntDataTable(pnPage);
                                    // JSvMessageDataTable(tCurrentPage);
                                }, 500);
                            } else {
                                FSvCMNSetMsgWarningDialog(aReturn['tStaMessg']);
                                //alert(aReturn['tStaMessg']);
                            }
                            JSxMsgNavDefult();
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            });
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : (event) Delete All
//Parameters : Button Event 
//Creator : 08/06/2021 Off
//Update: 
//Return : Event Delete All Select List
//Return Type : -
function JSnMessageDelChoose(ptCurrentPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
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
                url: "messageEventDelete",
                data: { 'tIDCode': aNewIdDelete },
                success: function(tResult) {
                    var aReturn = JSON.parse(tResult);
                    if (aReturn['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvModalDelMessage').modal('hide');
                            JSvMessageDataTable(ptCurrentPage);
                            $('#ospConfirmDelete').empty();
                            $('#ohdConfirmIDDelete').val();
                            localStorage.removeItem('LocalItemData');
                            $('.modal-backdrop').remove();
                            // JSvBntDataTable(pnPage);
                            // JSvMessageDataTable(tCurrentPage);
                        }, 1000);
                    } else {
                        alert(aReturn['tStaMessg']);
                    }
                    JSxMsgNavDefult();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        } else {
            localStorage.StaDeleteArray = '0';
            return false;
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality: Function Show Button Delete All
//Parameters:   Event Parameter
//Creator:  25/05/2021 Off
//Return: Event Button Delete All
//Return Type: -
function JSxShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == '') {
        $('.obtChoose').hide();
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $('.obtChoose').fadeIn(300);
        } else {
            $('.obtChoose').fadeOut(300);
        }
    }
}

//Functionality: Function Insert Text Delete
//Parameters: Event Parameter
//Creator: 25/05/2021 Off
//Return: Event Insert Text
//Return Type: -
function JSxTextinModal() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];

    if (aArrayConvert[0] == null || aArrayConvert[0] == '') {} else {
        var tText = '';
        var tTextCode = '';
        for ($i = 0; $i < aArrayConvert[0].length; $i++) {
            tText += aArrayConvert[0][$i].tName + '(' + aArrayConvert[0][$i].nCode + ') ';
            tText += ' , ';

            tTextCode += aArrayConvert[0][$i].nCode;
            tTextCode += ' , ';
        }
        var tTexts = tText.substring(0, tText.length - 2);
        $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val() + tTexts);
        $('#ohdConfirmIDDelete').val(tTextCode);
    }
}

//Functionality: Function Chack Dupilcate Data
//Parameters: Event Select List Calendar
//Creator: 25/05/2021 Off
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

//Choose Checkbox
function JSxCalendarVisibledDelAllBtn(poElement, poEvent) { // Action start after change check box value.
    try {
        var nCheckedCount = $('#odvCLDList td input:checked').length;
        if (nCheckedCount > 1) {
            $('#oliBtnDeleteAll').removeClass("disabled");
        } else {
            $('#oliBtnDeleteAll').addClass("disabled");
        }
        if (nCheckedCount > 1) {
            $('.xCNIconDel').addClass('xCNDisabled');
        } else {
            $('.xCNIconDel').removeClass('xCNDisabled');
        }
    } catch (err) {
        //console.log('JSxDepartmentVisibledDelAllBtn Error: ', err);
    }
}


//Functionality: Insert Text In Modal Delete
//Parameters: LocalStorage Data
//Creator: 25/05/2021 Off
//Return: -
//Return Type: -
function JSxPaseCodeDelInModal() {
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


// Functionality: Function Check Is Create Page
// Parameters: Event Documet Redy
// Creator: 25/05/2021 Off
// Return: object Status Delete
// ReturnType: boolean
function JSbCalendarIsCreatePage() {
    try {
        const tCldCode = $('#oetMsgCode').data('is-created');
        var bStatus = false;
        if (tCldCode == "") { // No have data
            bStatus = true;
        }
        
        return bStatus;
    } catch (err) {
        console.log('JSbCalendarIsCreatePage Error: ', err);
    }
}

// Functionality: Function Check Is Update Page
// Parameters: Event Documet Redy
// Creator: 25/05/2021 Off
// Return: object Status Delete
// ReturnType: boolean
function JSbCalendarIsUpdatePage() {
    try {
        const tCldCode = $('#oetMsgCode').data('is-created');
        var bStatus = false;
        if (!tCldCode == "") { // Have data
            bStatus = true;
        }
        return bStatus;
    } catch (err) {
        console.log('JSbCalendarIsUpdatePage Error: ', err);
    }
}

// Functionality : Show or Hide Component
// Parameters : ptComponent is element on document(id or class or...),pbVisible is visible
// Creator : 25/05/2021 Off
// Return : -
// Return Type : -
function JSxCalendarVisibleComponent(ptComponent, pbVisible, ptEffect) {
    try {
        if (pbVisible == false) {
            $(ptComponent).addClass('hidden');
        }
        if (pbVisible == true) {
            // $(ptComponent).removeClass('hidden');
            $(ptComponent).removeClass('hidden fadeIn animated').addClass('fadeIn animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                $(this).removeClass('hidden fadeIn animated');
            });
        }
    } catch (err) {
        console.log('JSxCalendarVisibleComponent Error: ', err);
    }
}



//function : Function Clear Defult Button UserCalendar
//Parameters : Document Ready
//Creator : 31/05/2021 Off
//Return : Show Tab Menu
//Return Type : -
function JSxUsrNavDefult() {
    if (nStaMsgBrowseType != 1 || nStaMsgBrowseType == undefined) {
        $('.xCNChoose').hide();
        $('#oliCldUserTitleAdd').hide();
        $('#oliCldUserTitleEdit').hide();
        $('#oliCldUserTitleAddPageDivice').hide();
        $('#odvBtnMsgUserAddEdit').hide();
        $('#odvBtnMsgUserInfo').show();
    }
}

//Functionality : Call UserCalendar Page Add  
//Parameters : Event Button Click
//Creator : 28/05/2021 Off
//Update : 
//Return : View
//Return Type : View
function JSvCallPageUserCalendarAdd() {
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('', '');
    var tCldCode = $('#oetMsgCode').val();
    $.ajax({
        type: "POST",
        url: "calendaruserPageAdd",
        cache: false,
        data: {
            'tCldCode': tCldCode 
         },
        timeout: 0,
        success: function(tResult) {
            if (nStaMsgBrowseType == 1) {

            } else {
                $('#oliCldUserTitleEdit').hide();

                $('#oliCldUserTitleAdd').show();
                $('#oliCldUserTitleAddPageDivice').hide();
                $('#odvBtnMsgUserInfo').hide();
                $('#odvBtnMsgUserAddEdit').show();
            }
            $('#odvUserCalendarContentPage').html(tResult);
            $('#odvBtnMsgUserSearch').hide();
            $('#odvMngTableList').hide();

            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : set click status submit form from save button
//Parameters : -
//Creator : 26/03/2019 pap
//Return : -
//Return Type : -
function JSxSetStatusClickCldUserSubmit() {
    $("#ohdCheckCldUserClearValidate").val("1");
}

function JSoAddEditUserCalendar(ptRoute) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            $('#ofmAddUserCalendar').validate().destroy();
            $('#ofmAddUserCalendar').validate({
                rules: {
                    oetAddNameUserCalendar: {
                        "required": {}
                    }
                },
                messages: {
                    oetAddNameUserCalendar: {
                        "required": $('#oetAddNameUserCalendar').attr('data-validate-required')
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
                        type: "POST",
                        url: ptRoute,
                        data: $('#ofmAddUserCalendar').serialize(),
                        cache: false,
                        timeout: 0,
                        success: function(tResult) {
                            console.log(tResult);
                            if (nStaMsgBrowseType != 1) {
                                var aReturn = JSON.parse(tResult);
                                if (aReturn['nStaEvent'] == 1) {
                                    JSvCalendarUserDataTable(1, aReturn['tCldCode']);
                                } else {
                                    alert(aReturn['tStaMessg']);
                                }
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


//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator: 31/05/2021 Off
//Return: - 
//Return Type: -
function JSxUserCalendarShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == '') {
        $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $('#odvMngTableList #oliBtnDeleteAll').removeClass('disabled');
            $('.xCNIconDel').addClass('xCNDisabled');
        } else {
            $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
            $('.xCNIconDel').removeClass('xCNDisabled');
        }
    }
}

//Functionality: Insert Text In Modal Delete
//Parameters: LocalStorage Data
//Creator: 31/05/2021 Off
//Return: -
//Return Type: -
function JSxUserCalendarPaseCodeDelInModal() {
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

//Functionality : Call UserCalendar Page Edit  
//Parameters : Event Button Click 
//Creator : 07/11/2018 witsarut
//Update : 03/04/2019 pap
//Return : View
//Return Type : View
function JSvCallPageUserCalendarEdit(ptUsrCode) {
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('JSvCallPageUserCalendarEdit', ptUsrCode);
    var tCldCode = $('#oetMsgCode').val();
    $.ajax({
        type: "POST",
        url: "calendaruserPageEdit",
        data: { 
            'tUsrCode'  : ptUsrCode, 
            'tCldCode'  : tCldCode
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (nStaMsgBrowseType == 1) {

            } else {
                $('#oliCldUserTitleEdit').show();
            
                $('#oliCldUserTitleAdd').hide();
                $('#oliCldUserTitleAddPageDivice').hide();
                $('#odvBtnMsgUserInfo').hide();
                $('#odvBtnMsgUserAddEdit').show();
            }

            oComport = $('#ocmComport').val();
                $("#ocmComport option[value='" + oComport + "']").attr('selected', true).trigger('change');

        
            $('#odvUserCalendarContentPage').html(tResult);
            $('#odvConnType').show();
            $('#odvBtnMsgUserSearch').hide();
            $('#odvMngTableList').hide();
            $('.xCNBtnGenCode').attr('disabled', true);
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });

}

//Functionality : เปลี่ยนหน้า pagenation
//Parameters : Event Click Pagenation
//Creator : 1/06/2021 Off
//Return : View
//Return Type : View
function JSvUserCalendarClickPage(ptPage) {
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageUserCalendar .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageUserCalendar .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvCalendarUserDataTable(nPageCurrent, $("#oetMsgCode").val());
}

/**
 * Functionality : Add head of receipt row
 * Parameters : -
 * Creator : 07/06/2021 Off
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
 function JSxMessageAddRow(){
    try{
        
        let nIndex = JCNnMessageGetMaxID('head');
        
        // Get template in wSlipMessageAdd.php
        var template = $.validator.format($.trim($('#oscSlipHeadRowTemplate').html()));
        // Add template
        $(template(++nIndex)).appendTo("#odvSmgSlipHeadContainer");
    }catch(err){
        console.log('JSxMessageAddRow Error: ', err);
    }
}

/**
 * Functionality : Count row in head of receipt or end of receipt
 * Parameters : ptReceiptPosition is position for limit(head or end)
 * Creator : 06/09/2018 piya
 * Last Modified : -
 * Return : Row count
 * Return Type : number
 */
 function JCNnMessageCountRow(ptReceiptPosition){
    try{
        if(ptReceiptPosition == 'head'){
            let nHeadRow = $('#odvSmgSlipHeadContainer .xWSmgItemSelect').length;
            return nHeadRow;
        }
    }catch(err){
        console.log('JCNnMessageCountRow Error: ', err);
    }
}

/**
 * Functionality : Prepare sort number after move row
 * Parameters : ptReceiptType is type for sorting(head, end), 
 * pbUseStringFormat is use string format? (set true return string format, set false return object format)
 * Creator : 07/09/2018 piya
 * Last Modified : -
 * Return : Head of receipt or End of receipt value
 * Return Type : object
 */
 function JSoSlipMessageSortabled(ptReceiptType, pbUseStringFormat){
    try{
        if(ptReceiptType == 'head'){
            let aSortData = JSaMessageGetSortData('head');
            let aSortabled = {};
            $.each(aSortData, (pnIndex, pnValue) => {
                let tValue = $('#odvSmgSlipHeadContainer .xWSmgItemSelect[id=' + pnValue + ']').find('.xWSmgDyForm').val();
                aSortabled[pnIndex] = tValue;
            });
            // console.log('Sortabled: ', aSortabled);
            if(pbUseStringFormat){
                return JSON.stringify(aSortabled);
            }else{
                return aSortabled;
            }
        }
    }catch(err){
        console.log('JSoSlipMessageSortabled Error: ', err);
    }
}

/**
 * Functionality : Open Model Delete
 * Parameters :
 * Creator : 13/07/2021 Off
 * Last Modified : -
 * Return : Row count
 * Return Type : number
 */
 function JSxMessageEventDeleteDetail(poElement = null, poEvent = null) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#odvModalDeleteMessageDetail #ospTextConfirmDelMessageSet').html("ยืนยันการลบข้อมูล");
        $('#odvModalDeleteMessageDetail').modal('show');
        $('#odvModalDeleteMessageDetail #osmConfirmDelMessage').unbind().click(function() {
            JSxMessageDeleteRowHead(poElement, poEvent)
            $('.modal-backdrop').remove();
            $('#odvModalDeleteMessageDetail').modal('hide');
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : Delete head recive or end recive row
 * Parameters : poElement is Itself element, poEvent is Itself event
 * Creator : 07/06/2021 Off
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
 function JSxMessageDeleteRowHead(poElement = null, poEvent = null){
    try{
        if((JCNnMessageCountRow('head') == 1)){return;}
        $(poElement).parents('.xWSmgItemSelect').remove();
    }catch(err){
        console.log('JSxSlipMessageDeleteRow Error: ', err);
    }
}

/**
 * Functionality : {description}
 * Parameters : ptReceiptType is type for Head of receipt("head") End of receipt("end"),
 * Creator : 07/09/2018 piya
 * Last Modified : -
 * Return : Max id number
 * Return Type : number
 */
 function JCNnMessageGetMaxID(ptReceiptType){
    try{
        // if(JCNnMessageCountRow(ptReceiptType) <= 0){return 0;}
        if(ptReceiptType == 'head'){
            let nMaxID = 0;
            let oHeadItems = $('#odvSmgSlipHeadContainer .xWSmgItemSelect');
            oHeadItems.each((pnIndex, poElement) => {
                let tElementID = parseInt($(poElement).attr('id'));
                if(nMaxID < tElementID){
                    nMaxID = tElementID;
                }
            });
            return nMaxID;
        }
    }catch(err){
        console.log('JCNnMessageGetMaxID Error: ', err);
    }
}

/**
 * Functionality : Display head of receipt and end of receipt row
 * Parameters : ptReceiptType is type for Head of receipt("head") End of receipt("end"), 
 * pnRows is number for row item
 * Creator : 07/09/2018 piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
 function JSxMessageRowDefualt(ptReceiptType, pnRows){
    try{
        // Validate pnRows
        if(pnRows <= 0){return;}// Invalid exit function
        
        if(ptReceiptType == "head"){
            tReceiptType = "Head";
        }
        
        // Get template in wSlipMessageAdd.php
        var template = $.validator.format($.trim($("#oscSlip" + tReceiptType + "RowTemplate").html()));
        
        // Add template by pnRows
        for(let loop=1; loop<=pnRows; loop++){
            $(template(loop)).appendTo("#odvSmgSlip" + tReceiptType + "Container");
        }
    }catch(err){
        console.log('JSxMessageRowDefualt Error: ', err);
    }
}

/**
 * Functionality : Get data sort from sortable plugin
 * Parameters : ptReceiptType is type for get sort data(head, end)
 * Creator : 07/08/2018 piya
 * Last Modified : -
 * Return : Sort data
 * Return Type : array
 */
 function JSaMessageGetSortData(ptReceiptType){
    try{
        if(ptReceiptType == 'head'){
            if(!(localStorage.getItem('headReceiptSort') == null)){
                return JSON.parse(localStorage.getItem('headReceiptSort'));
            }
        }
        if(ptReceiptType == 'end'){
            if(!(localStorage.getItem('endReceiptSort') == null)){
                return JSON.parse(localStorage.getItem('endReceiptSort'));
            }
        }
    }catch(err){
        console.log('JSaMessageGetSortData Error: ', err);
    }
}

/**
 * Functionality : Remove data sort from sortable plugin
 * Parameters : ptReceiptType is type for remove sort data(head, end, all)
 * Creator : 07/08/2018 piya
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
 function JSxMessageRemoveSortData(ptReceiptType){
    try{
        if(ptReceiptType == 'head'){
            localStorage.removeItem('headReceiptSort');
        }
        if(ptReceiptType == 'end'){
            localStorage.removeItem('endReceiptSort');
        }
        if(ptReceiptType == 'all'){
            localStorage.removeItem('headReceiptSort');
            localStorage.removeItem('endReceiptSort');
        }
    }catch(err){
        console.log('JSxMessageRemoveSortData Error: ', err);
    }
}