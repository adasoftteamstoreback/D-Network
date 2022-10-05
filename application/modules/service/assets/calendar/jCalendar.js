var nStaCldBrowseType = $('#oetCldStaBrowse').val();
var tCallCldBackOption = $('#oetCldCallBackOption').val();
$('ducument').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxCldNavDefult();
    if (nStaCldBrowseType != 1) {
        JSvCallPageCalendarList();
    } else {
        JSvCallPageCalendarAdd();
    }
});


///function : Function Clear Defult Button Calendar
//Parameters : -
//Creator : 19/05/2021 Off
//Update:   
//Return : -
//Return Type : -
function JSxCldNavDefult() {
    // Menu Bar เข้ามาจาก หน้า Master หรือ Browse
    if (nStaCldBrowseType != 1) { // เข้ามาจาก  Master
        $('.obtChoose').hide();
        $('#oliCldTitleAdd').hide();
        $('#oliCldTitleEdit').hide();
        $('#odvBtnCldAddEdit').hide();
        $('#odvBtnCldInfo').show();
    } else { // เข้ามาจาก Browse Modal
        $('#odvModalBody #odvCldMainMenu').removeClass('main-menu');
        $('#odvModalBody .xCNCldBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNCldBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

///function : Call Calendar Page list  
//Parameters : - 
//Creator:	19/05/2021 Off
//Update:   
//Return : View
//Return Type : View
function JSvCallPageCalendarList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        localStorage.tStaPageNow = 'JSvCallPageCalendarList';
        $('#oetSearchAll').val('');
        $.ajax({
            type: "POST",
            url: "calendarList",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $('#odvContentPageCalendar').html(tResult);
                JSvCalendarDataTable(pnPage);
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

///function : Call Calendar Data List
//Parameters : Ajax Success Event 
//Creator:	19/05/2021 Off
//Update:   
//Return : View
//Return Type : View
function JSvCalendarDataTable(pnPage) {
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
            url: "calendarDataTable",
            data: {
                tSearchAll: tSearchAll,
                nPageCurrent: nPageCurrent,
            },
            cache: false,
            Timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#ostDataCalendar').html(tResult);
                    $(".xCNDocDisabled").removeAttr("onclick");
                }
                JSxCldNavDefult();
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

//Functionality : Call Calendar Page Add  
//Parameters : -
//Creator : 19/05/2021 Off
//Update: 
//Return : View
//Return Type : View
function JSvCallPageCalendarAdd() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('', '');
        $.ajax({
            type: "POST",
            url: "calendarPageAdd",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (nStaCldBrowseType == 1) {
                    $('#odvModalBodyBrowse').html(tResult);
                    $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                } else {
                    $('#oliCldTitleEdit').hide();
                    $('#oliCldTitleAdd').show();
                    $('#odvBtnCldInfo').hide();
                    $('#odvBtnCldAddEdit').show();
                }
                $('#odvContentPageCalendar').html(tResult);
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
function JSvCallPageCalendarEdit(ptCldCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('JSvCallPageCalendarEdit', ptCldCode);
        $.ajax({
            type: "POST",
            url: "calendarPageEdit",
            data: { tCldCode: ptCldCode },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#oliCldTitleAdd').hide();
                    $('#oliCldTitleEdit').show();
                    $('#odvBtnCldInfo').hide();
                    $('#odvBtnCldAddEdit').show();
                    $('#odvContentPageCalendar').html(tResult);
                    $('#oetCldCode').addClass('xCNDisable');
                    $('.xCNDisable').attr('readonly', true);
                    $('.xCNiConGen').attr('disabled', true);
                }
                $.ajax({
                    type: "POST",
                    url: "calendaruserDataTable",
                    data: {
                        tSearchAll: "",
                        nPageCurrent: 1,
                        nPosCode: ptCldCode
                    },
                    cache: false,
                    Timeout: 0,
                    success: function(tResult){
                        $('#odvUserCalendarContentPage').html(tResult);
                        $('#obtSearchUserCalendar').click(function(){
                            var tCldCode = $('#oetCldCode').val();
                            JCNxOpenLoading();
                            JSvCalendarUserDataTable(1,tCldCode);
                        });
                        $('#oetSearchUserCalendar').keypress(function(event){
                            if(event.keyCode == 13){
                                var tCldCode = $('#oetCldCode').val();
                                JCNxOpenLoading();
                                JSvCalendarUserDataTable(1,tCldCode);
                            }
                        });
                        JSxCldUserCalendarNavDefult();
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
    if(nStaCldBrowseType != 1 || nStaCldBrowseType == undefined){
        $('.xCNChoose').hide();
        $('#oliCldUserTitleAdd').hide();
        $('#oliCldUserTitleEdit').hide();
        $('#oliCldUserTitletmp').hide();
        $('#oliCldUserTitleAddPageDivice').hide();
        $('#odvBtnCldUserAddEdit').hide();
        $('#odvBtnCldUserInfo').show();
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
    JSvCalendarDataTable(nPageCurrent);
}

//Functionality: (event) Delete
//Parameters: Button Event [tIDCode รหัสเหตุผล]
//Creator: 19/05/2021 Off
//Update: 
//Return: Event Delete Calendar List
//Return Type: -

function JSnCalendarDel(pnCurrentPage, ptDelName, ptIDCode, ptYesOnNo) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var aData = $('#ohdConfirmIDDelete').val();
        var aTexts = aData.substring(0, aData.length - 2);
        var aDataSplit = aTexts.split(" , ");
        var aDataSplitlength = aDataSplit.length;
        var aNewIdDelete = [];
        if (aDataSplitlength == '1') {
            $('#odvModalDelCalendar').modal('show');
            // $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode);
            $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptIDCode + ' ( ' + ptDelName + ' ) ' + ptYesOnNo);
            $('#osmConfirm').off('click').on('click', function(evt) {
                if (localStorage.StaDeleteArray != '1') {
                    $.ajax({
                        type: "POST",
                        url: "calendarEventDelete",
                        data: { 'tIDCode': ptIDCode },
                        cache: false,
                        success: function(tResult) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                $('#odvModalDelCalendar').modal('hide');
                                $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                                $('#ohdConfirmIDDelete').val('');
                                localStorage.removeItem('LocalItemData');
                                $('.modal-backdrop').remove();
                                setTimeout(function() {
                                    if (aReturn["nNumRowCldLoc"] != 0) {
                                        if (aReturn["nNumRowCldLoc"] > 10) {
                                            nNumPage = Math.ceil(aReturn["nNumRowCldLoc"] / 10);
                                            if (pnCurrentPage <= nNumPage) {
                                                JSvCallPageCalendarList(pnCurrentPage);
                                            } else {
                                                JSvCallPageCalendarList(nNumPage);
                                            }
                                        } else {
                                            JSvCallPageCalendarList(1);
                                        }
                                    } else {
                                        JSvCallPageCalendarList(1);
                                    }
                                    // JSvBntDataTable(pnPage);
                                    // JSvCalendarDataTable(tCurrentPage);
                                }, 500);
                            } else {
                                FSvCMNSetMsgWarningDialog(aReturn['tStaMessg']);
                                //alert(aReturn['tStaMessg']);
                            }
                            JSxCldNavDefult();
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
//Creator : 19/05/2021 Off
//Update: 
//Return : Event Delete All Select List
//Return Type : -
function JSnCalendarDelChoose(ptCurrentPage) {
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
                url: "calendarEventDelete",
                data: { 'tIDCode': aNewIdDelete },
                success: function(tResult) {
                    var aReturn = JSON.parse(tResult);
                    if (aReturn['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvModalDelCalendar').modal('hide');
                            JSvCalendarDataTable(ptCurrentPage);
                            $('#ospConfirmDelete').empty();
                            $('#ohdConfirmIDDelete').val();
                            localStorage.removeItem('LocalItemData');
                            $('.modal-backdrop').remove();
                            // JSvBntDataTable(pnPage);
                            // JSvCalendarDataTable(tCurrentPage);
                        }, 1000);
                    } else {
                        alert(aReturn['tStaMessg']);
                    }
                    JSxCldNavDefult();
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
        const tCldCode = $('#oetCldCode').data('is-created');
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
        const tCldCode = $('#oetCldCode').data('is-created');
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

//function: Call UserCalendar Data List
//Parameters: Ajax Success Event 
//Creator:	01/06/2021 Off
//Return: View
//Return Type: View
function JSvCalendarUserDataTable(pnPage, pnPosCode) {
    var tSearchAll = $('#oetSearchUserCalendar').val();
    var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "calendaruserDataTable",
        data: {
            tSearchAll: tSearchAll,
            nPageCurrent: nPageCurrent,
            nPosCode: pnPosCode
        },
        cache: false,
        Timeout: 0,
        success: function(tResult) {
            if (tResult != "") {
                $('#odvUserCalendarContentPage').html(tResult);
                $('#odvBtnCldUserSearch').show();
                $('#odvMngTableList').show();
                $('#odvMngMargin').show();

            }
            JSxUsrNavDefult();
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//function : Function Clear Defult Button UserCalendar
//Parameters : Document Ready
//Creator : 31/05/2021 Off
//Return : Show Tab Menu
//Return Type : -
function JSxUsrNavDefult() {
    if (nStaCldBrowseType != 1 || nStaCldBrowseType == undefined) {
        $('.xCNChoose').hide();
        $('#oliCldUserTitleAdd').hide();
        $('#oliCldUserTitleEdit').hide();
        $('#oliCldUserTitletmp').hide();
        $('#oliCldUserTitleAddPageDivice').hide();
        $('#odvBtnCldUserAddEdit').hide();
        $('#odvBtnCldUserInfo').show();
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
    var tCldCode = $('#oetCldCode').val();
    $.ajax({
        type: "POST",
        url: "calendaruserPageAdd",
        cache: false,
        data: {
            'tCldCode': tCldCode 
        },
        timeout: 0,
        success: function(tResult) {
            if (nStaCldBrowseType == 1) {

            } else {
                $('#oliCldUserTitleEdit').hide();
                $('#oliCldUserTitletmp').hide();

                $('#oliCldUserTitleAdd').show();
                $('#oliCldUserTitleAddPageDivice').hide();
                $('#odvBtnCldUserInfo').hide();
                $('#odvBtnCldUserAddEdit').show();
            }
            $('#odvUserCalendarContentPage').html(tResult);
            $('#odvBtnCldUserSearch').hide();
            $('#odvMngTableList').hide();
            $('#odvMngMargin').hide();

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
                            if (nStaCldBrowseType != 1) {
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

//Functionality : Event Single Delete UserCalendar
//Parameters : Event Icon Delete UserCalendar
//Creator : 31/05/2021 Off
//Update : 
//Return : object Status Delete
//Return Type : object
function JSoUserCalendarDel(pnPage, ptName, tIDCode, tYesOnNo, ptObjCode) {
    var aData       = $('#ohdConfirmIDDelete').val();
    var aTexts      = aData.substring(0, aData.length - 2);
    var aDataSplit      = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;

    var aNewIdDelete = [];

    if (aDataSplitlength == '1') {

        $('#odvModalDelUserCalendar').modal('show');
        $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode + ' ( ' + ptName + ' ) ' + tYesOnNo);
        $('#osmConfirm').off('click').on('click', function(evt) {

            if (localStorage.StaDeleteArray != '1') {
                $.ajax({
                    type: "POST",
                    url: "calendaruserEventDelete",
                    data: { 
                        'tIDCode'   : tIDCode, 
                        'ptObjCode' : ptObjCode
                    },
                    cache: false,
                    success: function(tResult) {
                        tResult = tResult.trim();
                        var tData = $.parseJSON(tResult);

                        if (tData['nStaEvent'] == '1') {
                            $('#odvModalDelUserCalendar').modal('hide');
                            $('#ospConfirmDelete').empty();
                            localStorage.removeItem('LocalItemData');
                            $('#ohdConfirmIDDelete').val('');
                            setTimeout(function() {
                                if (tData["nNumRowCld"] != 0) {
                                    if (tData["nNumRowCld"] > 10) {
                                        nNumPage = Math.ceil(tData["nNumRowCld"] / 10);
                                        if (pnPage <= nNumPage) {
                                            JSvCalendarUserDataTable(pnPage, $("#oetCldCode").val());
                                        } else {
                                            JSvCalendarUserDataTable(nNumPage, $("#oetCldCode").val());
                                        }
                                    } else {
                                        JSvCalendarUserDataTable(1, $("#oetCldCode").val());
                                    }
                                } else {
                                    JSvCalendarUserDataTable(1, $("#oetCldCode").val());
                                }
                            }, 500);
                        } else {
                            JCNxOpenLoading();
                            alert(tData['tStaMessg']);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }


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

//Functionality: Event Multi Delete
//Parameters: Event Button Delete All
//Creator: 31/05/2021 Off
//Update :
//Return:  object Status Delete
//Return Type: object
function JSoUserCalendarDelChoose(pnPage) {
    JCNxOpenLoading();
    var tCurrentPage = $("#nCurrentPageTB").val();
    var aData       = $('#ohdConfirmIDDelete').val();
    var aDataBch    = $('#ohdConfirmBchDelete').val();

    var aTexts      = aData.substring(0, aData.length - 2);
    var aTextsBch   = aDataBch.substring(0, aDataBch.length - 2);

    var aDataSplit      = aTexts.split(" , ");
    var aDataBchSplit   = aTextsBch.split(" , ");

    var aDataSplitlength = aDataSplit.length;

    var aNewIdDelete = [];

    for ($i = 0; $i < aDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }

    if (aDataSplitlength > 1) {

        localStorage.StaDeleteArray = '1';

        $.ajax({
            type: "POST",
            url: "calendaruserEventDelete",
            data: { 
                'tIDCode'    : aNewIdDelete, 
                'ptObjCode'   : $("#oetCldCode").val()
            },
            success: function(tResult) {
                var aReturn = JSON.parse(tResult);
                if (aReturn['nStaEvent'] == '1') {
                    $('#odvModalDelUserCalendar').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    $('#ohdConfirmIDDelete').val('');
                    $('#ospConfirmIDDelete').val('');
                    setTimeout(function() {
                        if (aReturn["nNumRowCld"] != 0) {
                            if (aReturn["nNumRowCld"] > 10) {
                                nNumPage = Math.ceil(aReturn["nNumRowCld"] / 10);
                                if (tCurrentPage <= nNumPage) {
                                    JSvCalendarUserDataTable(tCurrentPage, $("#oetCldCode").val());
                                } else {
                                    JSvCalendarUserDataTable(nNumPage, $("#oetCldCode").val());
                                }
                            } else {
                                JSvCalendarUserDataTable(1, $("#oetCldCode").val());
                            }
                        } else {
                            JSvCalendarUserDataTable(1, $("#oetCldCode").val());
                        }
                    }, 500);
                } else {
                    JCNxOpenLoading();
                    alert(aReturn['tStaMessg']);
                }
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

//Functionality : Call UserCalendar Page Edit  
//Parameters : Event Button Click 
//Creator : 07/11/2018 witsarut
//Update : 03/04/2019 pap
//Return : View
//Return Type : View
function JSvCallPageUserCalendarEdit(ptUsrCode) {
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('JSvCallPageUserCalendarEdit', ptUsrCode);
    var tCldCode = $('#oetCldCode').val();
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
            if (nStaCldBrowseType == 1) {

            } else {
                $('#oliCldUserTitleEdit').show();
                $('#oliCldUserTitletmp').hide();
            
                $('#oliCldUserTitleAdd').hide();
                $('#oliCldUserTitleAddPageDivice').hide();
                $('#odvBtnCldUserInfo').hide();
                $('#odvBtnCldUserAddEdit').show();
            }

            oComport = $('#ocmComport').val();
                $("#ocmComport option[value='" + oComport + "']").attr('selected', true).trigger('change');

        
            $('#odvUserCalendarContentPage').html(tResult);
            $('#odvConnType').show();
            $('#odvBtnCldUserSearch').hide();
            $('#odvMngTableList').hide();
            $('#odvMngMargin').hide();
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
    JSvCalendarUserDataTable(nPageCurrent, $("#oetCldCode").val());
}