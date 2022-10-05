var nStaQGPBrowseType = $('#oetCldStaBrowse').val();
var tCallQGPBackOption = $('#oetCldCallBackOption').val();
$('ducument').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxQNavDefult();
    if (nStaQGPBrowseType != 1) {
        JSvCallPageQasGroupList();
    } else {
        JSvCallPageQasGroupAdd();
    }
});


///function : Function Clear Defult Button QasSubGroup
//Parameters : -
//Creator : 25/05/2021 Off
//Update:   
//Return : -
//Return Type : -
function JSxQNavDefult() {
    // Menu Bar เข้ามาจาก หน้า Master หรือ Browse
    if (nStaQGPBrowseType != 1) { // เข้ามาจาก  Master
        $('.obtChoose').hide();
        $('#oliQGPTitleAdd').hide();
        $('#oliQGPTitleEdit').hide();
        $('#odvBtnQGPAddEdit').hide();
        $('#odvBtnQGPInfo').show();
    } else { // เข้ามาจาก Browse Modal
        $('#odvModalBody #odvQGPMainMenu').removeClass('main-menu');
        $('#odvModalBody .xCNQGPBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNQGPBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

///function : Call QasSubGroup Page list  
//Parameters : - 
//Creator : 25/05/2021 Off
//Update:   
//Return : View
//Return Type : View
function JSvCallPageQasGroupList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        localStorage.tStaPageNow = 'JSvCallPageQasGroupList';
        $('#oetSearchAll').val('');
        $.ajax({
            type: "POST",
            url: "qasgroupList",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $('#odvContentPageQasGroup').html(tResult);
                JSvQgpGroupDataTable(pnPage);
            },
            error: function(data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

///function : Call QasSubGroup Data List
//Parameters : Ajax Success Event 
//Creator : 25/05/2021 Off
//Update:   
//Return : View
//Return Type : View
function JSvQgpGroupDataTable(pnPage) {
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
            url: "qasgroupDataTable",
            data: {
                tSearchAll: tSearchAll,
                nPageCurrent: nPageCurrent,
            },
            cache: false,
            Timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#ostDataQasGroup').html(tResult);
                    $(".xCNDocDisabled").removeAttr("onclick");
                }
                JSxQNavDefult();
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

//Functionality : Call QasSubGroup Page Add  
//Parameters : -
//Creator : 25/05/2021 Off
//Update: 
//Return : View
//Return Type : View
function JSvCallPageQasGroupAdd() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('', '');
        $.ajax({
            type: "POST",
            url: "qasgroupPageAdd",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (nStaQGPBrowseType == 1) {
                    $('#odvModalBodyBrowse').html(tResult);
                    $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                } else {
                    $('#oliQGPTitleEdit').hide();
                    $('#oliQGPTitleAdd').show();
                    $('#odvBtnQGPInfo').hide();
                    $('#odvBtnQGPAddEdit').show();
                }
                $('#odvContentPageQasGroup').html(tResult);
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

//Functionality : Call QasSubGroup Page Edit  
//Parameters : -
//Creator : 25/05/2021 Off
//Update: 
//Return : View
//Return Type : View
function JSvCallPageQasGroupEdit(ptQGPCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        JStCMMGetPanalLangSystemHTML('JSvCallPageQasGroupEdit', ptQGPCode);
        $.ajax({
            type: "POST",
            url: "qasgroupPageEdit",
            data: { tQGPCode: ptQGPCode },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                if (tResult != "") {
                    $('#oliQGPTitleAdd').hide();
                    $('#oliQGPTitleEdit').show();
                    $('#odvBtnQGPInfo').hide();
                    $('#odvBtnQGPAddEdit').show();
                    $('#odvContentPageQasGroup').html(tResult);
                    $('#oetQGPCode').addClass('xCNDisable');
                    $('.xCNDisable').attr('readonly', true);
                    $('.xCNiConGen').attr('disabled', true);
                }
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

//Functionality : (event) Add/Edit QasSubGroup
//Parameters : form
//Creator : 25/05/2021 Off
//Return : object Status Event And Event Call Back
//Return Type : object
function JSnAddEditQasGroup(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddQasGroup').validate().destroy();
        
        $.validator.addMethod('dublicateCode', function(value, element) {
            if (ptRoute == "qassubgroupEventAdd") {
                if ($("#ohdCheckDuplicateObjCode").val() == 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }, '');


        $('#ofmAddQasGroup').validate({
            rules: {
                oetObjCode: {
                    "required": {
                        depends: function(oElement) {
                            if (ptRoute == "qasgroupEventAdd") {
                                if ($('#ocbQasGroupAutoGenCode').is(':checked')) {
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
                oetQGPName: { "required": {} },
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
                    data: $('#ofmAddQasGroup').serialize(),
                    async: false,
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        if (nStaQGPBrowseType != 1) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                if (aReturn['nStaCallBack'] == '1' || aReturn['nStaCallBack'] == null) {
                                    JSvCallPageQasGroupEdit(aReturn['tCodeReturn']);
                                } else if (aReturn['nStaCallBack'] == '2') {
                                    JSvCallPageQasGroupAdd();
                                } else if (aReturn['nStaCallBack'] == '3') {
                                    JSvCallPageQasGroupList();
                                }
                            } else {
                                alert(aReturn['tStaMessg']);
                            }
                        } else {
                            JCNxCloseLoading();
                            JCNxBrowseData(tCallQGPBackOption);
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
//Creator : 25/05/2021 Off
//Update: 
//Return: View
//Return Type: View
function JSvClickPage(ptPage) {
    var nPageCurrent = '';
    var nPageNew;
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageQasSubGroupGrp .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew;
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageQasSubGroupGrp .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew;
            break;
        default:
            nPageCurrent = ptPage;
    }
    JSvQgpGroupDataTable(nPageCurrent);
}

//Functionality: (event) Delete
//Parameters: Button Event [tIDCode รหัสเหตุผล]
//Creator : 25/05/2021 Off
//Update: 
//Return: Event Delete QasSubGroup List
//Return Type: -

function JSnQasGroupDel(pnCurrentPage, ptDelName, ptIDCode, ptYesOnNo) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var aData = $('#ohdConfirmIDDelete').val();
        var aTexts = aData.substring(0, aData.length - 2);
        var aDataSplit = aTexts.split(" , ");
        var aDataSplitlength = aDataSplit.length;
        var aNewIdDelete = [];
        if (aDataSplitlength == '1') {
            $('#odvModalDelQasGroup').modal('show');
            // $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode);
            $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptIDCode + ' ( ' + ptDelName + ' ) ' + ptYesOnNo);
            $('#osmConfirm').off('click').on('click', function(evt) {
                if (localStorage.StaDeleteArray != '1') {
                    $.ajax({
                        type: "POST",
                        url: "qasgroupEventDelete",
                        data: { 'tIDCode': ptIDCode },
                        cache: false,
                        success: function(tResult) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                $('#odvModalDelQasGroup').modal('hide');
                                $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                                $('#ohdConfirmIDDelete').val('');
                                localStorage.removeItem('LocalItemData');
                                $('.modal-backdrop').remove();
                                setTimeout(function() {
                                    if (aReturn["nNumRowCldLoc"] != 0) {
                                        if (aReturn["nNumRowCldLoc"] > 10) {
                                            nNumPage = Math.ceil(aReturn["nNumRowCldLoc"] / 10);
                                            if (pnCurrentPage <= nNumPage) {
                                                JSvCallPageQasGroupList(pnCurrentPage);
                                            } else {
                                                JSvCallPageQasGroupList(nNumPage);
                                            }
                                        } else {
                                            JSvCallPageQasGroupList(1);
                                        }
                                    } else {
                                        JSvCallPageQasGroupList(1);
                                    }
                                    // JSvBntDataTable(pnPage);
                                    // JSvQgpGroupDataTable(tCurrentPage);
                                }, 500);
                            } else {
                                FSvCMNSetMsgWarningDialog(aReturn['tStaMessg']);
                                //alert(aReturn['tStaMessg']);
                            }
                            JSxQNavDefult();
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
//Creator : 25/05/2021 Off
//Update: 
//Return : Event Delete All Select List
//Return Type : -
function JSnQasGroupDelChoose(ptCurrentPage) {
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
                url: "qasgroupEventDelete",
                data: { 'tIDCode': aNewIdDelete },
                success: function(tResult) {
                    var aReturn = JSON.parse(tResult);
                    if (aReturn['nStaEvent'] == '1') {
                        setTimeout(function() {
                            $('#odvModalDelQasGroup').modal('hide');
                            JSvQgpGroupDataTable(ptCurrentPage);
                            $('#ospConfirmDelete').empty();
                            $('#ohdConfirmIDDelete').val();
                            localStorage.removeItem('LocalItemData');
                            $('.modal-backdrop').remove();
                            // JSvBntDataTable(pnPage);
                            // JSvQgpGroupDataTable(tCurrentPage);
                        }, 1000);
                    } else {
                        alert(aReturn['tStaMessg']);
                    }
                    JSxQNavDefult();
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
//Creator : 25/05/2021 Off
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
//Creator : 25/05/2021 Off
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
//Parameters: Event Select List QasSubGroup
//Creator : 25/05/2021 Off
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
function JSxQasSubGroupVisibledDelAllBtn(poElement, poEvent) { // Action start after change check box value.
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
//Creator : 25/05/2021 Off
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
//Creator : 25/05/2021 Off
// Return: object Status Delete
// ReturnType: boolean
function JSbQasGroupIsCreatePage() {
    try {
        const tQGPCode = $('#oetQGPCode').data('is-created');
        var bStatus = false;
        if (tQGPCode == "") { // No have data
            bStatus = true;
        }
        
        return bStatus;
    } catch (err) {
        console.log('JSbQasGroupIsCreatePage Error: ', err);
    }
}

// Functionality: Function Check Is Update Page
// Parameters: Event Documet Redy
//Creator : 25/05/2021 Off
// Return: object Status Delete
// ReturnType: boolean
function JSbQasGroupIsUpdatePage() {
    try {
        const tQGPCode = $('#oetQGPCode').data('is-created');
        var bStatus = false;
        if (!tQGPCode == "") { // Have data
            bStatus = true;
        }
        return bStatus;
    } catch (err) {
        console.log('JSbQasGroupIsUpdatePage Error: ', err);
    }
}

// Functionality : Show or Hide Component
// Parameters : ptComponent is element on document(id or class or...),pbVisible is visible
//Creator : 25/05/2021 Off
// Return : -
// Return Type : -
function JSxQasGroupVisibleComponent(ptComponent, pbVisible, ptEffect) {
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
        console.log('JSxQasGroupVisibleComponent Error: ', err);
    }
}