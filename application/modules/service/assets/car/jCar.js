var nStaCldBrowseType   = $('#oetCarStaBrowse').val();
var tCallCldBackOption  = $('#oetCarCallBackOption').val();
$('ducument').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxCldNavDefult();
    if (nStaCldBrowseType != 1) {
        JSvCallPageCarList();
    } else {
        JSvCallPageCarAdd();
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
        $('#oliCarTitleAdd').hide();
        $('#oliCarTitleEdit').hide();
        $('#odvBtnCarAddEdit').hide();
        $('#odvBtnCarInfo').show();
    } else { // เข้ามาจาก Browse Modal
        $('#odvModalBody #odvCarMainMenu').removeClass('main-menu');
        $('#odvModalBody .xCNCarBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNCarBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

///function : Call Car Page list
//Parameters : -
//Creator:	09/06/2021 Off
//Update:
//Return : View
//Return Type : View
function JSvCallPageCarList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        localStorage.tStaPageNow = 'JSvCallPageCarList';
        $('#oetSearchAll').val('');
        $.ajax({
            type: "POST",
            url: "carList",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $('#odvContentPageCar').html(tResult);
                JSvCarDataTable(pnPage);
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

///function : Call Car Data List
//Parameters : Ajax Success Event
//Creator:	19/05/2021 Off
//Update:
//Return : View
//Return Type : View
function JSvCarDataTable(pnPage) {
    let tSearchAll      = $('#oetSearchAll').val();
    let tSearchType1    = $('#oetCarOptionID1').val();
    let tSearchType2    = $('#oetCarOptionID2').val();
    let tSearchType3    = $('#oetCarOptionID3').val();
    let tSearchType4    = $('#oetCarOptionID4').val();
    let tSearchType5    = $('#oetCarOptionID5').val();
    let tSearchType6    = $('#oetCarOptionID6').val();
    let tSearchType7    = $('#oetCarOptionID7').val();
    let tSearchType8    = $('#oetCarOptionID8').val();
    let nPageCurrent    = pnPage;
    if (nPageCurrent == undefined || nPageCurrent == '') {
        nPageCurrent    = '1';
    }
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "carDataTable",
        data: {
            tSearchAll      : tSearchAll,
            tSearchType1    : tSearchType1,
            tSearchType2    : tSearchType2,
            tSearchType3    : tSearchType3,
            tSearchType4    : tSearchType4,
            tSearchType5    : tSearchType5,
            tSearchType6    : tSearchType6,
            tSearchType7    : tSearchType7,
            tSearchType8    : tSearchType8,
            nPageCurrent    : nPageCurrent,
        },
        cache: false,
        Timeout: 0,
        success: function(tResult) {
            if (tResult != "") {
                $('#ostDataCar').html(tResult);
                $(".xCNDisabled").removeAttr("onclick");
            }
            JSxCldNavDefult();
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Functionality : Call Car Page Add
//Parameters : -
//Creator : 10/06/2021 Off
//Update:
//Return : View
//Return Type : View
function JSvCallPageCarAdd() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('', '');
        $.ajax({
            type: "POST",
            url: "carPageAdd",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (nStaCldBrowseType == 1) {
                    $('#odvModalBodyBrowse').html(tResult);
                    $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                } else {
                    $('#oliCarTitleEdit').hide();
                    $('#oliCarTitleAdd').show();
                    $('#odvBtnCarInfo').hide();
                    $('#odvBtnCarAddEdit').show();
                }
                $('#odvContentPageCar').html(tResult);
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
function JSvCallPageCarEdit(ptCarCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('JSvCallPageCarEdit', ptCarCode);
        $.ajax({
            type: "POST",
            url: "carPageEdit",
            data: { tCarCode: ptCarCode },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#oliCarTitleAdd').hide();
                    $('#oliCarTitleEdit').show();
                    $('#odvBtnCarInfo').hide();
                    $('#odvBtnCarAddEdit').show();
                    $('#odvContentPageCar').html(tResult);
                    $('#oetCarCode').addClass('xCNDisable');
                    $('.xCNDisable').attr('readonly', true);
                    $('.xCNiConGen').attr('disabled', true);
                    var tCarRegNo = $("#oetCarNoreq").val();
                }
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

//function : Function Clear Defult Button UserCalendar
//Parameters : Document Ready
//Creator : 01/06/2021 Off
//Return : Show Tab Menu
//Return Type : -
function JSxCarHistoryNavDefult(){
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

//Functionality : (event) Add/Edit Car
//Parameters : form
//Creator : 10/06/2021 Off
//Return : object Status Event And Event Call Back
//Return Type : object
function JSnAddEditCar(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {

        var nCheckSeq = $('#ohdSeqNoInBCH').val();
        if(nCheckSeq == 1){
            if($('#oetCarRefBCHCode').val() == '' || $('#oetCarRefBCHCode').val() == null){
                FSvCMNSetMsgWarningDialog('กรุณาระบุรหัสอ้างอิงสาขา');
                return;
            }
        }

        $('#ofmAddCalendar').validate().destroy();

        $.validator.addMethod('dublicateCode', function(value, element) {
            if (ptRoute == "carEventAdd") {
                if ($("#ohdCheckDuplicateObjCode").val() == 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }, '');

        $('#ofmAddCalendar').validate({
            rules: {
                oetCarCode: {
                    "required": {
                        depends: function(oElement) {
                            if (ptRoute == "carEventAdd") {
                                if ($('#ocbCalendarAutoGenCode').is(':checked')) {
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
                oetCarCode: { "required": {} },
                oetCarNoreq: { "required": {} },
                ocmObjGroup: { "required": {} },
                //oetCarEnginereq: { "required": {} },
                //oetCarPowerreq: { "required": {} },
                // oetCarStart: { "required": {} },
                // oetCarFinish: { "required": {} },
            },
            messages: {
                oetCarCode: {
                    "required": $('#oetCarCode').attr('data-validate-required'),
                    "dublicateCode": $('#oetCarCode').attr('data-validate-dublicateCode')
                },
                oetCarNoreq: {
                    "required": $('#oetCarNoreq').attr('data-validate-required'),
                },
                // oetCarEnginereq: {
                //     //"required": $('#oetCarEnginereq').attr('data-validate-required'),
                // },
                // oetCarPowerreq: {
                //     //"required": $('#oetCarPowerreq').attr('data-validate-required'),
                // },
                // oetCarStart: {
                //     "required": $('#oetCarStart').attr('data-validate-required'),
                // },
                // oetCarFinish: {
                //     "required": $('#oetCarFinish').attr('data-validate-required'),
                // }
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
                    data: $('#ofmAddCalendar').serialize(),
                    async: false,
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                      console.log(tResult);
                        if (nStaCldBrowseType != 1) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                if (aReturn['nStaCallBack'] == '1' || aReturn['nStaCallBack'] == null) {
                                    JSvCallPageCarEdit(aReturn['tCodeReturn']);
                                } else if (aReturn['nStaCallBack'] == '2') {
                                    JSvCallPageCarAdd();
                                } else if (aReturn['nStaCallBack'] == '3') {
                                    JSvCallPageCarList();
                                }
                            } else {
                                alert(aReturn['tStaMessg']);
                            }
                        } else {
                            JCNxCloseLoading();
                            JCNxBrowseData(tCallCldBackOption);
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
    JSvCarDataTable(nPageCurrent);
}

//Functionality: (event) Delete
//Parameters: Button Event [tIDCode รหัสเหตุผล]
//Creator: 19/05/2021 Off
//Update:
//Return: Event Delete Calendar List
//Return Type: -

function JSnCarDel(pnCurrentPage, ptDelName, ptIDCode, ptYesOnNo) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var aData = $('#ohdConfirmIDDelete').val();
        var aTexts = aData.substring(0, aData.length - 2);
        var aDataSplit = aTexts.split(" , ");
        var aDataSplitlength = aDataSplit.length;
        var aNewIdDelete = [];
        if (aDataSplitlength == '1') {
            $('#odvModalDelCar').modal('show');
            // $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode);
            $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptIDCode + ' ( ' + ptDelName + ' ) ' + ptYesOnNo);
            $('#osmConfirm').off('click').on('click', function(evt) {
                if (localStorage.StaDeleteArray != '1') {
                    $.ajax({
                        type: "POST",
                        url: "carEventDelete",
                        data: { 'tIDCode': ptIDCode },
                        cache: false,
                        success: function(tResult) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                $('#odvModalDelCar').modal('hide');
                                $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                                $('#ohdConfirmIDDelete').val('');
                                localStorage.removeItem('LocalItemData');
                                $('.modal-backdrop').remove();
                                setTimeout(function() {
                                    if (aReturn["nNumRowCldLoc"] != 0) {
                                        if (aReturn["nNumRowCldLoc"] > 10) {
                                            nNumPage = Math.ceil(aReturn["nNumRowCldLoc"] / 10);
                                            if (pnCurrentPage <= nNumPage) {
                                                JSvCallPageCarList(pnCurrentPage);
                                            } else {
                                                JSvCallPageCarList(nNumPage);
                                            }
                                        } else {
                                            JSvCallPageCarList(1);
                                        }
                                    } else {
                                        JSvCallPageCarList(1);
                                    }
                                    // JSvBntDataTable(pnPage);
                                    // JSvCarDataTable(tCurrentPage);
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
function JSnCarDelChoose(ptCurrentPage) {
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
                url: "carEventDelete",
                data: { 'tIDCode': aNewIdDelete },
                success: function(tResult) {
                    var aReturn = JSON.parse(tResult);
                    if (aReturn['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvModalDelCar').modal('hide');
                            JSvCarDataTable(ptCurrentPage);
                            $('#ospConfirmDelete').empty();
                            $('#ohdConfirmIDDelete').val();
                            localStorage.removeItem('LocalItemData');
                            $('.modal-backdrop').remove();
                            // JSvBntDataTable(pnPage);
                            // JSvCarDataTable(tCurrentPage);
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
        var nCheckedCount = $('#odvCARList td input:checked').length;
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
        const tCldCode = $('#oetCarCode').data('is-created');
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
        const tCldCode = $('#oetCarCode').data('is-created');
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
function JSvCarHistoryDataTable(pnPage, pnPosCode) {
    var tSearchAll = $('#oetSearchCarHistory').val();
    var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
    var tCarRegNo = $("#oetCarNoreq").val();
    var oAdvanceSearchData  = {
        tStatus          : $("#ocmCarHistoryAdvSearchStaDoc").val(),
        dDocdate         : $("#oetCarHistoryAdvSearcDocDate").val(),
        dJointdate       : $("#oetCarHistoryAdvSearcJointDate").val(),
        tBchFrom         : $("#oetCarHistoryAdvSearchBchCodeFrom").val(),
        tBchTo           : $("#oetCarHistoryAdvSearchBchCodeTo").val()
    }
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "carHistoryDataTable",
        data: {
            tSearchAll: tSearchAll,
            nPageCurrent: nPageCurrent,
            nPosCode: pnPosCode,
            tCarRegNo: tCarRegNo,
            aAdvanceSearchData : oAdvanceSearchData
        },
        cache: false,
        Timeout: 0,
        success: function(tResult) {
            if (tResult != "") {
                $('#odvCarHistoryContentPage').html(tResult);
                $('#odvBtnCarUserSearch').show();
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

//function: Call UserCalendar Data List
//Parameters: Ajax Success Event
//Creator:	01/06/2021 Off
//Return: View
//Return Type: View
function JSvCarOrderHistoryDataTable(pnPage, pnPosCode) {
    var tSearchAll = $('#oetSearchOrderCarHistory').val();
    var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
    var tCarRegNo = $("#oetCarNoreq").val();
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "carOrderHistoryDataTable",
        data: {
            tSearchAll: tSearchAll,
            nPageCurrent: nPageCurrent,
            nPosCode: pnPosCode,
            tCarRegNo: tCarRegNo
        },
        cache: false,
        Timeout: 0,
        success: function(tResult) {
            if (tResult != "") {
                $('#odvAllCarHistoryContentPage').html(tResult);
                $('#odvBtnCarUserSearch').show();
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
    var tCldCode = $('#oetCarCode').val();
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
            $('#odvCarHistoryContentPage').html(tResult);
            $('#odvBtnCarUserSearch').hide();
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
    var tCldCode = $('#oetCarCode').val();
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


            $('#odvCarHistoryContentPage').html(tResult);
            $('#odvConnType').show();
            $('#odvBtnCarUserSearch').hide();
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
function JSvCarHistoryClickPage(ptPage) {
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageCarHistory .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageCarHistory .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvCarHistoryDataTable(nPageCurrent, $("#oetCarCode").val());
}

//Functionality : เปลี่ยนหน้า pagenation
//Parameters : Event Click Pagenation
//Creator : 02/09/2021 Off
//Return : View
//Return Type : View
function JSvOrderHistoryClickPage(ptPage) {
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageOrderHistory .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageOrderHistory .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvCarOrderHistoryDataTable(nPageCurrent, $("#oetCarCode").val());
}


function JSvCarCallOrderDetail(ptDocNo,ptBchCode,ptAgnCode,ptTypeJump = ''){
    var tRoute = 'docJOB/0/0';
    $.ajax({
        type    : "GET",
        url     : tRoute,
        data    : {'ptTypeJump' : ptTypeJump },
        cache   : false,
        timeout : 5000,
        success: function(tResult) {
            $(window).scrollTop(0);
            $('.odvMainContent').html(tResult);
            localStorage.tCheckBackStage = 'PageCarDetail';

            setTimeout(function(){
                JSvJOBCallPageEdit(ptAgnCode,ptBchCode,ptDocNo);
            }, 500);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}
