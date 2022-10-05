var nStaCldBrowseType = $('#oetQahStaBrowse').val();
var tCallCldBackOption = $('#oetQahCallBackOption').val();
$('ducument').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxCldNavDefult();
    if (nStaCldBrowseType != 1) {
        JSvCallPageQuestionList();
    } else {
        JSvCallPageQuestionAdd();
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
        $('#oliQahTitleAdd').hide();
        $('#oliQahTitleEdit').hide();
        $('#odvBtnQahAddEdit').hide();
        $('#odvBtnQahInfo').show();
    } else { // เข้ามาจาก Browse Modal
        $('#odvModalBody #odvQahMainMenu').removeClass('main-menu');
        $('#odvModalBody .xCNQahBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNQahBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

///function : Call Question Page list  
//Parameters : - 
//Creator:	19/05/2021 Off
//Update:   
//Return : View
//Return Type : View
function JSvCallPageQuestionList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        localStorage.tStaPageNow = 'JSvCallPageQuestionList';
        $('#oetSearchAll').val('');
        $.ajax({
            type: "POST",
            url: "questionList",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $('#odvContentPageQuestion').html(tResult);
                JSvQuestionDataTable(pnPage);
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

///function : Call Question Data List
//Parameters : Ajax Success Event 
//Creator:	19/05/2021 Off
//Update:   
//Return : View
//Return Type : View
function JSvQuestionDataTable(pnPage) {
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
            url: "questionDataTable",
            data: {
                tSearchAll: tSearchAll,
                nPageCurrent: nPageCurrent,
            },
            cache: false,
            Timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#ostDataQuestion').html(tResult);
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
function JSvCallPageQuestionAdd() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('', '');
        $.ajax({
            type: "POST",
            url: "questionPageAdd",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (nStaCldBrowseType == 1) {
                    $('#odvModalBodyBrowse').html(tResult);
                    $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                } else {
                    $('#oliQahTitleEdit').hide();
                    $('#oliQahTitleAdd').show();
                    $('#odvBtnQahInfo').hide();
                    $('#odvBtnQahAddEdit').show();
                }
                $('#odvContentPageQuestion').html(tResult);
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

//Functionality : Call Question Page Edit  
//Parameters : -
//Creator: 22/05/2021 Off
//Update: 
//Return : View
//Return Type : View
function JSvCallPageQuestionEdit(ptQahCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('JSvCallPageQuestionEdit', ptQahCode);
        $.ajax({
            type: "POST",
            url: "questionPageEdit",
            data: { tQahCode: ptQahCode },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#oliQahTitleAdd').hide();
                    $('#oliQahTitleEdit').show();
                    $('#odvBtnQahInfo').hide();
                    $('#odvBtnQahAddEdit').show();
                    $('#odvContentPageQuestion').html(tResult);
                    $('#oetQahCode').addClass('xCNDisable');
                    $('.xCNDisable').attr('readonly', true);
                    $('.xCNiConGen').attr('disabled', true);
                }
                $.ajax({
                    type: "POST",
                    url: "questiondetailDataTable",
                    data: {
                        tSearchAll: "",
                        nPageCurrent: 1,
                        nQahCode: ptQahCode
                    },
                    cache: false,
                    Timeout: 0,
                    success: function(tResult){
                        $('#odvQuestionDetailContentPage').html(tResult);
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


//Functionality : Call Preview  
//Parameters : -
//Creator: 15/12/2021 Off
//Update: 
//Return : View
//Return Type : View
function JSvCallPreviewQuestion(ptQahCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('JSvCallPageQuestionEdit', ptQahCode);
        $.ajax({
            type: "POST",
            url: "questionPagePreview",
            data: { tQahCode: ptQahCode },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                JCNxCloseLoading();
                $('#odvPreview').html(tResult);
                $('#odvModalPreviewQuestion').modal('show');
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
        $('#oliQahDetailTitleAdd').hide();
        $('#oliQahDetailTitleEdit').hide();
        $('#oliQahDetailTitletmp').hide();
        $('#oliCldUserTitleAddPageDivice').hide();
        $('#odvBtnQahDetailAddEdit').hide();
        $('#odvBtnQahDetailInfo').show();
    }
}

//Functionality : (event) Add/Edit Question
//Parameters : form
//Creator : 24/06/2021 Off
//Return : object Status Event And Event Call Back
//Return Type : object
function JSnAddEditQuestion(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddQuestion').validate().destroy();
        
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


        $('#ofmAddQuestion').validate({
            rules: {
                oetObjCode: {
                    "required": {
                        depends: function(oElement) {
                            if (ptRoute == "calendarEventAdd") {
                                if ($('#ocbQuestionAutoGenCode').is(':checked')) {
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
                oetQahName: { "required": {} },
                ocmObjGroup: { "required": {} },
            },
            messages: {
                oetObjCode: {
                    "required": $('#oetObjCode').attr('data-validate-required'),
                    "dublicateCode": $('#oetObjCode').attr('data-validate-dublicateCode')
                },
                oetObjName: {
                    "required": $('#oetObjName').attr('data-validate-required'),
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
                    data: $('#ofmAddQuestion').serialize(),
                    async: false,
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        if (nStaCldBrowseType != 1) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                if (aReturn['nStaCallBack'] == '1' || aReturn['nStaCallBack'] == null) {
                                    JSvCallPageQuestionEdit(aReturn['tCodeReturn']);
                                } else if (aReturn['nStaCallBack'] == '2') {
                                    JSvCallPageQuestionAdd();
                                } else if (aReturn['nStaCallBack'] == '3') {
                                    JSvCallPageQuestionList();
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
            nPageOld = $('.xWPageQuestionGrp .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew;
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageQuestionGrp .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew;
            break;
        default:
            nPageCurrent = ptPage;
    }
    JSvQuestionDataTable(nPageCurrent);
}

//Functionality: (event) Delete
//Parameters: Button Event [tIDCode รหัสเหตุผล]
//Creator: 19/05/2021 Off
//Update: 
//Return: Event Delete Calendar List
//Return Type: -

function JSnQuestionDel(pnCurrentPage, ptDelName, ptIDCode, ptYesOnNo) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var aData = $('#ohdConfirmIDDelete').val();
        var aTexts = aData.substring(0, aData.length - 2);
        var aDataSplit = aTexts.split(" , ");
        var aDataSplitlength = aDataSplit.length;
        var aNewIdDelete = [];
        if (aDataSplitlength == '1') {
            $('#odvModalDelQuestion').modal('show');
            // $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode);
            $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptIDCode + ' ( ' + ptDelName + ' ) ' + ptYesOnNo);
            $('#osmConfirm').off('click').on('click', function(evt) {
                if (localStorage.StaDeleteArray != '1') {
                    $.ajax({
                        type: "POST",
                        url: "questionEventDelete",
                        data: { 'tIDCode': ptIDCode },
                        cache: false,
                        success: function(tResult) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                $('#odvModalDelQuestion').modal('hide');
                                $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                                $('#ohdConfirmIDDelete').val('');
                                localStorage.removeItem('LocalItemData');
                                $('.modal-backdrop').remove();
                                setTimeout(function() {
                                    if (aReturn["nNumRowCldLoc"] != 0) {
                                        if (aReturn["nNumRowCldLoc"] > 10) {
                                            nNumPage = Math.ceil(aReturn["nNumRowCldLoc"] / 10);
                                            if (pnCurrentPage <= nNumPage) {
                                                JSvCallPageQuestionList(pnCurrentPage);
                                            } else {
                                                JSvCallPageQuestionList(nNumPage);
                                            }
                                        } else {
                                            JSvCallPageQuestionList(1);
                                        }
                                    } else {
                                        JSvCallPageQuestionList(1);
                                    }
                                    // JSvBntDataTable(pnPage);
                                    // JSvQuestionDataTable(tCurrentPage);
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
function JSnQuestionDelChoose(ptCurrentPage) {
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
                url: "questionEventDelete",
                data: { 'tIDCode': aNewIdDelete },
                success: function(tResult) {
                    var aReturn = JSON.parse(tResult);
                    if (aReturn['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvModalDelQuestion').modal('hide');
                            JSvQuestionDataTable(ptCurrentPage);
                            $('#ospConfirmDelete').empty();
                            $('#ohdConfirmIDDelete').val();
                            localStorage.removeItem('LocalItemData');
                            $('.modal-backdrop').remove();
                            // JSvBntDataTable(pnPage);
                            // JSvQuestionDataTable(tCurrentPage);
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
function JSxQuestionVisibledDelAllBtn(poElement, poEvent) { // Action start after change check box value.
    try {
        var nCheckedCount = $('#odvQAHList td input:checked').length;
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
// Creator: 24/06/2021 Off
// Return: object Status Delete
// ReturnType: boolean
function JSbQuestionIsCreatePage() {
    try {
        const tCldCode = $('#oetQahCode').data('is-created');
        var bStatus = false;
        if (tCldCode == "") { // No have data
            bStatus = true;
        }
        
        return bStatus;
    } catch (err) {
        console.log('JSbQuestionIsCreatePage Error: ', err);
    }
}

// Functionality: Function Check Is Create Page
// Parameters: Event Documet Redy
// Creator: 22/06/2021 Off
// Return: object Status Delete
// ReturnType: boolean
function JSbQuestionDetailIsCreatePage() {
    alert('this');
    try {
        const tCldCode = $('#oetQahCode').data('is-created');
        var bStatus = false;
        if (tCldCode == "") { // No have data
            bStatus = true;
        }
        
        return bStatus;
    } catch (err) {
        console.log('JSbQuestionDetailIsCreatePage Error: ', err);
    }
}

// Functionality: Function Check Is Update Page
// Parameters: Event Documet Redy
// Creator: 24/06/2021 Off
// Return: object Status Delete
// ReturnType: boolean
function JSbQuestionIsUpdatePage() {
    try {
        const tCldCode = $('#oetQahCode').data('is-created');
        var bStatus = false;
        if (!tCldCode == "") { // Have data
            bStatus = true;
        }
        return bStatus;
    } catch (err) {
        console.log('JSbQuestionIsUpdatePage Error: ', err);
    }
}

// Functionality : Show or Hide Component
// Parameters : ptComponent is element on document(id or class or...),pbVisible is visible
// Creator : 24/06/2021 Off
// Return : -
// Return Type : -
function JSxQuestionVisibleComponent(ptComponent, pbVisible, ptEffect) {
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
        console.log('JSxQuestionVisibleComponent Error: ', err);
    }
}

//function: Call UserCalendar Data List
//Parameters: Ajax Success Event 
//Creator:	01/06/2021 Off
//Return: View
//Return Type: View
function JSvQuestionDetailDataTable(pnPage, pnQahCode) {
    var tSearchAll = $('#oetSearchUserCalendar').val();
    var nPageCurrent = (pnPage === undefined || pnPage == '') ? '1' : pnPage;
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "questiondetailDataTable",
        data: {
            tSearchAll: tSearchAll,
            nPageCurrent: nPageCurrent,
            nQahCode: pnQahCode
        },
        cache: false,
        Timeout: 0,
        success: function(tResult) {
            if (tResult != "") {
                $('#odvQuestionDetailContentPage').html(tResult);
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
        $('#oliQahDetailTitleAdd').hide();
        $('#oliQahDetailTitleEdit').hide();
        $('#oliQahDetailTitletmp').hide();
        $('#oliCldUserTitleAddPageDivice').hide();
        $('#odvBtnQahDetailAddEdit').hide();
        $('#odvBtnQahDetailInfo').show();
    }
}

//Functionality : Call QuestionDetail Page Add  
//Parameters : Event Button Click
//Creator : 22/06/2021 Off
//Update : 
//Return : View
//Return Type : View
function JSvCallPageQuestionDetailAdd() {
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('', '');
    var tQahCode = $('#oetQahCode').val();
    var tQahType = $('#ohdCheckType').val();
    $.ajax({
        type: "POST",
        url: "questiondetailPageAdd",
        cache: false,
        data: {
            'tQahCode': tQahCode,
            'tQahType': tQahType
         },
        timeout: 0,
        success: function(tResult) {
            if (nStaCldBrowseType == 1) {

            } else {
                $('#oliQahDetailTitleEdit').hide();
                $('#oliQahDetailTitletmp').hide();

                $('#oliQahDetailTitleAdd').show();
                $('#oliCldUserTitleAddPageDivice').hide();
                $('#odvBtnQahDetailInfo').hide();
                $('#odvBtnQahDetailAddEdit').show();
            }
            $('#odvQuestionDetailContentPage').html(tResult);
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
function JSxSetStatusClickDetailSubmit() {
    $("#ohdCheckQuestionDetailClearValidate").val("1");
}

function JSoAddEditQuestionDetail(ptRoute) {
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
                                    JSvQuestionDetailDataTable(1, aReturn['tQahCode']);
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
function JSoQuestionDetailDel(pnPage, ptName, tIDCode, tYesOnNo, ptSeqCode) {
    var aData       = $('#ohdConfirmIDDelete').val();
    var aTexts      = aData.substring(0, aData.length - 2);
    var aDataSplit      = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;

    var aNewIdDelete = [];

    if (aDataSplitlength == '1') {

        $('#odvModalDelQuestionDetail').modal('show');
        $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode + ' ( ' + ptName + ' ) ' + tYesOnNo);
        $('#osmConfirm').off('click').on('click', function(evt) {

            if (localStorage.StaDeleteArray != '1') {
                $.ajax({
                    type: "POST",
                    url: "questiondetailEventDelete",
                    data: { 
                        'tIDCode'   : tIDCode, 
                        'ptSeqCode' : ptSeqCode
                    },
                    cache: false,
                    success: function(tResult) {
                        tResult = tResult.trim();
                        var tData = $.parseJSON(tResult);

                        if (tData['nStaEvent'] == '1') {
                            $('#odvModalDelQuestionDetail').modal('hide');
                            $('#ospConfirmDelete').empty();
                            localStorage.removeItem('LocalItemData');
                            $('#ohdConfirmIDDelete').val('');
                            setTimeout(function() {
                                if (tData["nNumRowCld"] != 0) {
                                    if (tData["nNumRowCld"] > 10) {
                                        nNumPage = Math.ceil(tData["nNumRowCld"] / 10);
                                        if (pnPage <= nNumPage) {
                                            JSvQuestionDetailDataTable(pnPage, $("#oetQahCode").val());
                                        } else {
                                            JSvQuestionDetailDataTable(nNumPage, $("#oetQahCode").val());
                                        }
                                    } else {
                                        JSvQuestionDetailDataTable(1, $("#oetQahCode").val());
                                    }
                                } else {
                                    JSvQuestionDetailDataTable(1, $("#oetQahCode").val());
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
//Creator: 24/06/2021 Off
//Return: - 
//Return Type: -
function JSxQuestionDetailShowButtonChoose() {
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
function JSxQuestionDetailPaseCodeDelInModal() {
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
//Creator: 23/06/2021 Off
//Update :
//Return:  object Status Delete
//Return Type: object
function JSoQuestionDetailDelChoose(pnPage) {
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
            url: "questiondetailEventDelete",
            data: { 
                'ptSeqCode' : aNewIdDelete, 
                'tIDCode'   : $("#oetQahCode").val()
            },
            success: function(tResult) {
                var aReturn = JSON.parse(tResult);
                if (aReturn['nStaEvent'] == '1') {
                    $('#odvModalDelQuestionDetail').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    $('#ohdConfirmIDDelete').val('');
                    $('#ospConfirmIDDelete').val('');
                    setTimeout(function() {
                        if (aReturn["nNumRowCld"] != 0) {
                            if (aReturn["nNumRowCld"] > 10) {
                                nNumPage = Math.ceil(aReturn["nNumRowCld"] / 10);
                                if (tCurrentPage <= nNumPage) {
                                    JSvQuestionDetailDataTable(tCurrentPage, $("#oetQahCode").val());
                                } else {
                                    JSvQuestionDetailDataTable(nNumPage, $("#oetQahCode").val());
                                }
                            } else {
                                JSvQuestionDetailDataTable(1, $("#oetQahCode").val());
                            }
                        } else {
                            JSvQuestionDetailDataTable(1, $("#oetQahCode").val());
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

//Functionality : Call QuestionDetail Page Edit  
//Parameters : Event Button Click 
//Creator : 23/06/2021 witsarut
//Update : 23/06/2021 pap
//Return : View
//Return Type : View
function JSvCallPageQuestionDetailEdit(ptSeqCode,ptType) {
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('JSvCallPageQuestionDetailEdit', ptSeqCode);
    var tQahCode = $('#oetQahCode').val();
    var tQahType = ptType;
    $.ajax({
        type: "POST",
        url: "questiondetailPageEdit",
        data: { 
            'tSeqCode'  : ptSeqCode, 
            'tQahType'  : tQahType, 
            'tQahCode'  : tQahCode
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (nStaCldBrowseType == 1) {

            } else {
                $('#oliQahDetailTitleEdit').show();
                $('#oliQahDetailTitletmp').hide();
            
                $('#oliQahDetailTitleAdd').hide();
                $('#oliCldUserTitleAddPageDivice').hide();
                $('#odvBtnQahDetailInfo').hide();
                $('#odvBtnQahDetailAddEdit').show();
            }

            oComport = $('#ocmComport').val();
                $("#ocmComport option[value='" + oComport + "']").attr('selected', true).trigger('change');

        
            $('#odvQuestionDetailContentPage').html(tResult);
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
//Creator : 24/06/2021 Off
//Return : View
//Return Type : View
function JSvQuestionDetailClickPage(ptPage) {
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageQuestionDetail .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageQuestionDetail .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvQuestionDetailDataTable(nPageCurrent, $("#oetQahCode").val());
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
        if(JCNnMessageCountRow('head') >= 10){return;}
        
        let nIndex = JCNnMessageGetMaxID('head');
        console.log('MaxID: ', JCNnMessageGetMaxID('head'));
        
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
        if(confirm('Delete ?')){
            $(poElement).parents('.xWSmgItemSelect').remove();
        }
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
        if(JCNnMessageCountRow(ptReceiptType) <= 0){return 0;}

        if(ptReceiptType == 'head'){
            let nMaxID = 0;
            let oHeadItems = $('#odvSmgSlipHeadContainer .xWSmgItemSelect');
            oHeadItems.each((pnIndex, poElement) => {
                let tElementID = $(poElement).attr('id');
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