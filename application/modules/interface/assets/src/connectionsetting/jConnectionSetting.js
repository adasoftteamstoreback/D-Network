var nStaInterfaceImportBrowseType = $('#oetInterfaceImportStaBrowse').val();
var tCallInterfaceImportBackOption = $('#oetInterfaceImportCallBackOption').val();


$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/

    JSvConnectionSettingList(1);

    // create By Witsarut 15/05/2020
    // Default หน้าแรก
    JSxCallGetConGeneral();

});

//function : Call ConnsetGen Page list  
//Parameters : Document Redy And Event Button
//Creator :	15/05/2020 Witsarut (Bell)
//Return : View
//Return Type : View
function JSvConnectionSettingList(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var tSearchAllNotSet = $('#oetSearchAllNotSet').val();
        var tSearchAllSetUp = $('#oetSearchAllSetUp').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "connectionsettingDataTable",
            data: {
                nPageCurrent: pnPage,
                tSearchAllNotSet: tSearchAllNotSet,
                tSearchAllSetUp: tSearchAllSetUp
            },
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvWahouse').html(tResult);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//function : Call UserShop Page list
//Parameters : Document Redy And Event Button
//Creator :	19/07/2021 Off
//Return : View
//Return Type : View
function JSvConnectionSettingListUsrShop(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var tSearchAllCstShp = $('#oetSearchAllCstShp').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "connectionsettingUsrShopDataTable",
            data: {
                nPageCurrent: pnPage,
                tSearchAllCstShp: tSearchAllCstShp
            },
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvUsrShop').html(tResult);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//function : Call UserShop Page list
//Parameters : Document Redy And Event Button
//Creator :	19/07/2021 Off
//Return : View
//Return Type : View
function JSvConnectionSettingListCarInter(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var nPageCurrent = pnPage;
        var tSearchAllCarInter = $('#oetSearchAllCarInter').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "connectionsettingAgcCarDataTable",
            data: {
                nPageCurrent: nPageCurrent,
                tSearchAllCarInter: tSearchAllCarInter
            },
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvAgcCar').html(tResult);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//function : Call UserShop Page list
//Parameters : Document Redy And Event Button
//Creator :	19/07/2021 Off
//Return : View
//Return Type : View
function JSvConnectionSettingListMapping(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var tSearchMapping = $('#oetSearchMapping').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "connectionsettingMappingDataTable",
            data: {
                nPageCurrent: pnPage,
                tSearchMapping: tSearchMapping
            },
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvMapping').html(tResult);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//function : Call UserShop Page list
//Parameters : Document Redy And Event Button
//Creator :	19/07/2021 Off
//Return : View
//Return Type : View
function JSvConnectionSettingListUMS(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var tSearchAllNotSet = $('#oetSearchAllNotSet').val();
        var tSearchAllSetUp = $('#oetSearchAllSetUp').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "connectionsettingUMSDataTable",
            data: {
                nPageCurrent: pnPage,
                tSearchAllNotSet: tSearchAllNotSet,
                tSearchAllSetUp: tSearchAllSetUp
            },
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvUMS').html(tResult);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//function : Call UserShop Page list
//Parameters : Document Redy And Event Button
//Creator :	19/07/2021 Off
//Return : View
//Return Type : View
function JSvConnectionSettingListMSShop(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var tSearchMSShop = $('#oetSearchMSShop').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "connectionsettingMSShopDataTable",
            data: {
                nPageCurrent: pnPage,
                tSearchMSShop: tSearchMSShop
            },
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvTabMSShop').html(tResult);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//function : Call UserShop Page list
//Parameters : Document Redy And Event Button
//Creator :	19/07/2021 Off
//Return : View
//Return Type : View
function JSvConnectionSettingListRespond(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var tSearchAllRespond = $('#oetSearchAllRespond').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "connectionsettingErrMsgDataTable",
            data: {
                nPageCurrent: pnPage,
                tSearchAllRespond: tSearchAllRespond
            },
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvTabRespond').html(tResult);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//function : Call UMS Page list
//Parameters : Document Redy And Event Button
//Creator :	19/07/2021 Off
//Return : View
//Return Type : View
function JSvConnectionSettingListUMS(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        var tSearchAllNotSet = $('#oetSearchAllNotSet').val();
        var tSearchAllSetUp = $('#oetSearchAllSetUp').val();
        // JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: "connectionsettingUMSDataTable",
            data: {
                nPageCurrent: pnPage,
                tSearchAllNotSet: tSearchAllNotSet,
                tSearchAllSetUp: tSearchAllSetUp
            },
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                $('#odvUMS').html(tResult);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : Call connectionsetting Page list
// Parameters : -
// Creator :	14/05/2020 saharat(Golf)
// Last Update:	
// Return : View
// Return Type : View
function JSxCallGetContent() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "GET",
            url: "connectionsettingCallPageList",
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                JSvConnectionSettingList(1);
                // $('#odvWahouse').html(tResult);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

// Function : Call connectionsetting Page list
// Parameters : -
// Creator :	14/05/2020 saharat(Golf)
// Last Update:	
// Return : View
// Return Type : View
function JSxCallGetContentUMS() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "GET",
            url: "connectionsettingCallPageListUMS",
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                JSvConnectionSettingListUMS(1);
                // $('#odvWahouse').html(tResult);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

// Function : Call connectionsetting Page list
// Parameters : -
// Creator :	14/05/2020 saharat(Golf)
// Last Update:	
// Return : View
// Return Type : View
function JSxCallGetContentUserShop() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "GET",
            url: "connectionsettingCallPageListUrsShop",
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                JSvConnectionSettingListUsrShop(1);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

// Function : Call connectionsetting Page list
// Parameters : -
// Creator :	14/05/2020 saharat(Golf)
// Last Update:	
// Return : View
// Return Type : View
function JSxCallGetContentCarInter() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "GET",
            url: "connectionsettingCallPageListAgcCar",
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                JSvConnectionSettingListCarInter(1);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

// Function : Call connectionsetting Page list
// Parameters : -
// Creator :	14/05/2020 saharat(Golf)
// Last Update:	
// Return : View
// Return Type : View
function JSxCallGetContentMapping() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "GET",
            url: "connectionsettingCallPageListMapping",
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                JSvConnectionSettingListMapping(1);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

// Function : Call connectionsetting Page list
// Parameters : -
// Creator :	14/05/2020 saharat(Golf)
// Last Update:	
// Return : View
// Return Type : View
function JSxCallGetContentMSSHOP() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "GET",
            url: "connectionsettingCallPageListMSShop",
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                JSvConnectionSettingListMSShop(1);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

// Function : Call connectionsetting Page list
// Parameters : -
// Creator :	14/05/2020 saharat(Golf)
// Last Update:	
// Return : View
// Return Type : View
function JSxCallGetContentRespond() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "GET",
            url: "connectionsettingCallPageListErrMsg",
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                JSvConnectionSettingListRespond(1);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

// Function : Call connectionsetting Page list
// Parameters : -
// Creator :	14/05/2020 Witsarut(Bell)
// Last Update:	
// Return : View
// Return Type : View
function JSxCallGetConGeneral() {
    JCNxOpenLoading();
    var nStaSession = JCNxFuncChkSessionExpired();
    // If has Session 
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $.ajax({
            type: "POST",
            url: "connectSetGenaral",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $('#odvGeneralInformation').html(tResult);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }

}

// Function : Call connectionsetting Page list
// Parameters : -
// Creator :	14/05/2020 saharat(Golf)
// Last Update:	
// Return : View
// Return Type : View
function JSxCallGetContentUMS() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "GET",
            url: "connectionsettingCallPageListUMS",
            cache: false,
            timeout: 0,
            async: false,
            success: function(tResult) {
                JSvConnectionSettingListUMS(1);
                JSvConnectionSettingListMSShop(1);
                JSxControlScroll();
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

// Function : Call connectionsetting Page list
// Parameters : -
// Creator :	14/05/2020 saharat(Golf)
// Last Update:	
// Return : View
// Return Type : View
function JSvCallPageAddWahouse() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "connectionsettingCallPageAddWahouse",
        cache: false,
        success: function(tResult) {
            $('#odvWahouse').html(tResult);
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : Call Addpage UserShop
// Parameters : -
// Creator :	19/07/2021 Off
// Last Update:	
// Return : View
// Return Type : View
function JSvCallPageAddUserShop() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "connectionsettingCallPageAddUsrShop",
        cache: false,
        success: function(tResult) {
            $('#odvUsrShop').html(tResult);
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : Call Addpage UserShop
// Parameters : -
// Creator :	19/07/2021 Off
// Last Update:	
// Return : View
// Return Type : View
function JSvCallPageAddRespond() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "connectionsettingCallPageAddRespond",
        cache: false,
        success: function(tResult) {
            $('#odvTabRespond').html(tResult);
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : Call Addpage UserShop
// Parameters : -
// Creator :	19/07/2021 Off
// Last Update:	
// Return : View
// Return Type : View
function JSvCallPageAddMSShop() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "connectionsettingCallPageAddMSShop",
        cache: false,
        success: function(tResult) {
            $('#odvTabMSShop').html(tResult);
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : Call Addpage CarInter
// Parameters : -
// Creator :	19/07/2021 Off
// Last Update:	
// Return : View
// Return Type : View
function JSvCallPageAddCarInter() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "connectionsettingCallPageAddCarInter",
        cache: false,
        success: function(tResult) {
            $('#odvAgcCar').html(tResult);
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : Call Addpage Mapping
// Parameters : -
// Creator :	19/07/2021 Off
// Last Update:	
// Return : View
// Return Type : View
function JSvCallPageAddMapping() {
    JCNxOpenLoading();
    $.ajax({
        type: "GET",
        url: "connectionsettingCallPageAddMapping",
        cache: false,
        success: function(tResult) {
            $('#odvMapping').html(tResult);
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : insert data ConnectionSetting
// Parameters : form ofmAddConnectionSetting
// Creator :	15/05/2020 saharat(Golf)
// Last Update:	
// Return : View
// Return Type : View
function JSnAddEditConnectionSetting(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    var tBchCode = $('#oetCssBchCode').val();

    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddConnectionSetting').validate().destroy();
        $('#ofmAddConnectionSetting').validate({
            rules: {

                oetCssAgnName: { "required": {} },
                oetCssBchName: { "required": {} },
                oetCssWahName: { "required": {} },
                oetCssWahRefNo: { "required": {} },

            },
            messages: {
                oetCssAgnName: {
                    "required": $('#oetCssAgnName').attr('data-validate-required'),
                    "dublicateCode": $('#oetCssAgnName').attr('data-validate-dublicateCode'),
                },
                oetCssBchName: {
                    "required": $('#oetCssBchName').attr('data-validate-required'),
                    "dublicateCode": $('#oetCssBchName').attr('data-validate-dublicateCode'),
                },
                oetCssWahName: {
                    "required": $('#oetCssWahName').attr('data-validate-required'),
                    "dublicateCode": $('#oetCssWahName').attr('data-validate-dublicateCode'),
                },
                oetCssWahRefNo: {
                    "required": $('#oetCssWahRefNo').attr('data-validate-required'),
                    "dublicateCode": $('#oetCssWahRefNo').attr('data-validate-dublicateCode'),
                },
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
                    data: $('#ofmAddConnectionSetting').serialize(),
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        JSxCallGetContent();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    }
}


// Function : insert data UserShop
// Parameters : form ofmAddConnectionSettingMapping
// Creator :	21/07/2021 Off
// Last Update:	
// Return : View
// Return Type : View
function JSnAddEditConnectionSettingUserShop(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    var oetUsrShopBchCode = $('#oetUsrShopBchCode').val();

    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddConnectionSetting').validate().destroy();
        $('#ofmAddConnectionSetting').validate({
            rules: {
                oetBchName: { "required": {} },
            },
            messages: {
                oetBchName: {
                    "required": $('#oetBchName').attr('data-validate-required'),
                    "dublicateCode": $('#oetBchName').attr('data-validate-dublicateCode'),
                },
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
                    data: $('#ofmAddConnectionSetting').serialize(),
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        JSxCallGetContentUserShop();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    }
}

// Function : insert data MSShop
// Parameters : form ofmAddConnectionSettingMapping
// Creator :	22/07/2021 Off
// Last Update:	
// Return : View
// Return Type : View
function JSnAddEditConnectionSettingMSShop(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if($('#oetMSShopPassword').val() != ''){
        var tPws = JCNtAES128EncryptData($('#oetMSShopPassword').val(),tKey,tIV);
        var tOpws = $('#oetMSShopPasswordEncode').val();
        var tNpws = $('#oetMSShopPassword').val();

        if(tOpws != tNpws){
            $('#oetMSShopPasswordEncode').val(tPws);
        }
    }else{
        $('#oetMSShopPasswordEncode').val('');
    }
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddConnectionSettingMSShop').validate().destroy();
        $('#ofmAddConnectionSettingMSShop').validate({
            rules: {
                oetBchName: { "required": {} },
                oetMSShopMid: { "required": {} },
                oetMSShopTid: { "required": {} },
                oetMSShopPosName: { "required": {} },
                oetMSShopUser: { "required": {} },
                oetMSShopPassword: { "required": {} },
                oetMSShopApiToken: { "required": {} },
            },
            messages: {
                oetBchName: {
                    "required": $('#oetBchName').attr('data-validate-required'),
                    "dublicateCode": $('#oetBchName').attr('data-validate-dublicateCode'),
                },
                oetMSShopMid: {
                    "required": $('#oetMSShopMid').attr('data-validate-required'),
                },
                oetMSShopTid: {
                    "required": $('#oetMSShopTid').attr('data-validate-required'),
                },
                oetMSShopPosName: {
                    "required": $('#oetMSShopPosName').attr('data-validate-required'),
                },
                oetMSShopUser: {
                    "required": $('#oetMSShopUser').attr('data-validate-required'),
                },
                oetMSShopPassword: {
                    "required": $('#oetMSShopPassword').attr('data-validate-required'),
                },
                oetMSShopApiToken: {
                    "required": $('#oetMSShopApiToken').attr('data-validate-required'),
                },
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
                    data: $('#ofmAddConnectionSettingMSShop').serialize(),
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        JSxCallGetContentMSSHOP();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    }
}

// Function : insert data MSShop
// Parameters : form ofmAddConnectionSettingMapping
// Creator :	22/07/2021 Off
// Last Update:	
// Return : View
// Return Type : View
function JSnAddEditConnectionSettingRespond(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();

    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddConnectionSettingRespond').validate().destroy();
        $('#ofmAddConnectionSettingRespond').validate({
            rules: {
                oetErrCode: { "required": {} },
                otaErrDetail: { "required": {} },
            },
            messages: {
                oetErrCode: {
                    "required": $('#oetErrCode').attr('data-validate-required'),
                    "dublicateCode": $('#oetErrCode').attr('data-validate-dublicateCode'),
                },
                otaErrDetail: {
                    "required": $('#otaErrDetail').attr('data-validate-required'),
                },
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
                    data: $('#ofmAddConnectionSettingRespond').serialize(),
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        JSxCallGetContentRespond();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    }
}

// Function : insert data CarInter
// Parameters : form ofmAddConnectionSettingMapping
// Creator :	22/07/2021 Off
// Last Update:	
// Return : View
// Return Type : View
function JSnAddEditConnectionSettingCarInter(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();

    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddConnectionSettingCarInter').validate().destroy();
        $('#ofmAddConnectionSettingCarInter').validate({
            rules: {
                oetCssCarName: { "required": {} },
            },
            messages: {
                oetCssCarName: {
                    "required": $('#oetCssCarName').attr('data-validate-required'),
                    "dublicateCode": $('#oetCssCarName').attr('data-validate-dublicateCode'),
                },
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
                    data: $('#ofmAddConnectionSettingCarInter').serialize(),
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        JSxCallGetContentCarInter();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    }
}


// Function : insert data Mapping
// Parameters : form ofmAddConnectionSettingMapping
// Creator :	21/07/2021 Off
// Last Update:	
// Return : View
// Return Type : View
function JSnAddEditConnectionSettingMapping(ptRoute) {
    var nStaSession = JCNxFuncChkSessionExpired();
    var tBchCode = $('#oetCssBchCode').val();

    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddConnectionSettingMapping').validate().destroy();
        $('#ofmAddConnectionSettingMapping').validate({
            rules: {
                oetMPCode: { "required": {} },
            },
            messages: {
                oetMPCode: {
                    "required": $('#oetMPCode').attr('data-validate-required'),
                    "dublicateCode": $('#oetMPCode').attr('data-validate-dublicateCode'),
                },
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
                    data: $('#ofmAddConnectionSettingMapping').serialize(),
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        JSxCallGetContentMapping();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        });
    }
}

//Functionality : Call ConnectionSetting PageEdit
//Parameters : function Parameters
//Creator : 15/05/2020 Saharat(Golf)
//Return : View
//Return Type : View
function JSvCallPageEditConnectionSetting(ptMerCode, ptBchCode, ptShpCode, ptWahCode) {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "connectionsettingCallPageEdit",
        data: {
            tMerCode: ptMerCode,
            tBchCode: ptBchCode,
            tShpCode: ptShpCode,
            tWahCode: ptWahCode,
        },
        success: function(tResult) {
            $('#odvWahouse').html(tResult);
            JCNxCloseLoading();
        },
        error: function(data) {
            console.log(data);
        }
    });
}


//Functionality : Call ConnectionSetting PageEdit
//Parameters : function Parameters
//Creator : 15/05/2020 Saharat(Golf)
//Return : View
//Return Type : View
function JSvCallPageEditConnectionSettingErrMsg(ptErrCode) {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "connectionsettingCallPageEditErrMsg",
        data: {
            tErrCode: ptErrCode,
        },
        success: function(tResult) {
            $('#odvTabRespond').html(tResult);
            JCNxCloseLoading();
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Functionality : Call ConnectionSetting PageEdit
//Parameters : function Parameters
//Creator : 15/05/2020 Saharat(Golf)
//Return : View
//Return Type : View
function JSvCallPageEditConnectionSettingMSShop(ptBchCode, ptPosCode) {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "connectionsettingCallPageEditMSShop",
        data: {
            tBchCode: ptBchCode,
            tPosCode: ptPosCode,
        },
        success: function(tResult) {
            $('#odvTabMSShop').html(tResult);
            JCNxCloseLoading();
        },
        error: function(data) {
            console.log(data);
        }
    });
}


//Functionality : Call ConnectionSetting PageEdit
//Parameters : function Parameters
//Creator : 15/05/2020 Saharat(Golf)
//Return : View
//Return Type : View
function JSnCallTimeOut() {
    nTimeout = $("#oetMSShopTime").val();
    tMid = $("#oetMSShopMid").val();
    tTid = $("#oetMSShopTid").val();
    tUserName = $("#oetMSShopUser").val();
    tPassWord = JCNtAES128DecryptData($('#oetMSShopPassword').val(),tKey,tIV);
    tURL = $("#oetMSShopApiToken").val();
    $.ajax({
        type: "POST",
        url: "connectionsettingMSShopTestHost",
        data: {
            nTimeout: nTimeout,
            tMid: tMid,
            tTid: tTid,
            tUserName: tUserName,
            tPassWord: tPassWord,
            tURL: tURL,
        },
        success: function(tResult) {
            var aReturn = JSON.parse(tResult);
            $('#odvModalMSShopRespondCode').modal('show');
            if(aReturn['SystemData']['ReturnCode'] == '00'){
                $('#odvModalMSShopRespondCode #ospConfirm').html('เชื่อมต่อสำเร็จ');
            }else{
                $('#odvModalMSShopRespondCode #ospConfirm').html('Return Code' + " " + aReturn['SystemData']['ReturnCode'] + ' : ' + aReturn['SystemData']['Description']);
            }
            JCNxCloseLoading();
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Functionality : Call ConnectionSetting PageEdit
//Parameters : function Parameters
//Creator : 15/05/2020 Saharat(Golf)
//Return : View
//Return Type : View
function JSvCallPageEditConnectionSettingMapping(ptMappingCode, ptMappingSeqNo) {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "connectionsettingCallPageEditMapping",
        data: {
            tMappingCode: ptMappingCode,
            tMappingSeqNo: ptMappingSeqNo
        },
        success: function(tResult) {
            $('#odvMapping').html(tResult);
            JCNxCloseLoading();
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Functionality : Call ConnectionSetting PageEdit
//Parameters : function Parameters
//Creator : 15/05/2020 Saharat(Golf)
//Return : View
//Return Type : View
function JSvCallPageEditConnectionSettingCstShp(ptBchCode) {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "connectionsettingCallPageEditUserShop",
        data: {
            tBchCode: ptBchCode,
        },
        success: function(tResult) {
            $('#odvUsrShop').html(tResult);
            JCNxCloseLoading();
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//Functionality : Call ConnectionSetting PageEdit
//Parameters : function Parameters
//Creator : 15/05/2020 Saharat(Golf)
//Return : View
//Return Type : View
function JSvCallPageEditConnectionSettingCarInter(ptCarReq) {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "connectionsettingCallPageEditCarInter",
        data: {
            tCarReq: ptCarReq,
        },
        success: function(tResult) {
            $('#odvAgcCar').html(tResult);
            JCNxCloseLoading();
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//ควบคุมตารางให้มีสกอร์ หรือไม่มีสกอร์
function JSxControlScroll() {
    var nWindowHeight = ($(window).height() - 50) / 2;

    //สำหรับตารางที่เป็นเช็คบ๊อก
    var nLenCheckbox = $('#otbTableForCheckbox tbody tr').length;
    if (nLenCheckbox > 6) {
        $('.xCNTableHeightCheckbox').css('height', nWindowHeight);
    } else {
        $('.xCNTableHeightCheckbox').css('height', 'auto');
    }

    //สำหรับตารางอื่นๆ
    var nLenInput = $('#otbTableForInput tbody tr').length;
    if (nLenCheckbox < 6) {
        var nWindowHeightInput = ($(window).height() - 125) / 2;
    } else {
        var nWindowHeightInput = nWindowHeight;
    }

    if (nLenInput > 6) {
        $('.xCNTableHeightInput').css('height', nWindowHeightInput);
    } else {
        $('.xCNTableHeightInput').css('height', 'auto');
    }
}

// Function delete sigle
// Create witsarut 21/05/2020
function JSxConSetDelete(ptAgnCode, ptBchCode, ptShpCode, ptWahCode, tYesOnNo) {
    $('#odvModalDeleteSingle').modal('show');
    $('#odvModalDeleteSingle #ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptAgnCode + ' ' + tYesOnNo);
    $('#odvModalDeleteSingle #osmConfirmDelete').on('click', function(evt) {
        $.ajax({
            type: "POST",
            url: "connectionsettingEventDelete",
            data: {
                tAgnCode: ptAgnCode,
                tBchCode: ptBchCode,
                tShpCode: ptShpCode,
                tWahCode: ptWahCode,
            },
            cache: false,
            success: function(tResult) {
                $('#odvModalDeleteSingle').modal('hide');
                setTimeout(function() {
                    JSxCallGetContent();
                }, 500);
            },

        });
    });
}

// Function delete sigle
// Create witsarut 21/05/2020
function JSxConSetDeleteMSShop(ptBchCode, ptPosCode, tYesOnNo) {
    $('#odvModalDeleteSingleMSShop').modal('show');
    $('#odvModalDeleteSingleMSShop #ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptBchCode + ' ' + tYesOnNo);
    $('#odvModalDeleteSingleMSShop #osmConfirmDelete').on('click', function(evt) {
        $.ajax({
            type: "POST",
            url: "connectionsettingEventDeleteMSShop",
            data: {
                tPosCode: ptPosCode,
                tBchCode: ptBchCode,
            },
            cache: false,
            success: function(tResult) {
                $('#odvModalDeleteSingleMSShop').modal('hide');
                setTimeout(function() {
                    JSxCallGetContentMSSHOP();
                }, 500);
            },
        });
    });
}


// Function delete sigle
// Create witsarut 22/07/2021 Off
function JSxConSetDeleteCstShp(ptBchCode, tYesOnNo) {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    var nNumCheck = 0;
    if (aArrayConvert != '') {
        var nNumOfArr = aArrayConvert[0].length;
        nNumCheck = nNumOfArr;
    }
    if (nNumCheck > 1) {
        return false;
    }
    $('#odvModalDeleteSingleCstShp').modal('show');
    $('#odvModalDeleteSingleCstShp #ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptBchCode + ' ' + tYesOnNo);
    $('#odvModalDeleteSingleCstShp #osmConfirmDelete').off('click').on('click', function(evt) {
        $.ajax({
            type: "POST",
            url: "connectionsettingEventDeleteCstShp",
            data: {
                tBchCode: ptBchCode,
            },
            cache: false,
            success: function(tResult) {
                $('#odvModalDeleteSingleCstShp').modal('hide');
                setTimeout(function() {
                    JSxCallGetContentUserShop();
                }, 500);
            },

        });
    });
}

// Function delete sigle
// Create witsarut 22/07/2021 Off
function JSxConSetDeleteCarInter(ptCarReq, tYesOnNo) {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    var nNumCheck = 0;
    if (aArrayConvert != '') {
        var nNumOfArr = aArrayConvert[0].length;
        nNumCheck = nNumOfArr;
    }
    if (nNumCheck > 1) {
        return false;
    }
    $('#odvModalDeleteSingleCarInter').modal('show');
    $('#odvModalDeleteSingleCarInter #ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptCarReq + ' ' + tYesOnNo);
    $('#odvModalDeleteSingleCarInter #osmConfirmDelete').off('click').on('click', function(evt) {
        $.ajax({
            type: "POST",
            url: "connectionsettingEventDeleteCarInter",
            data: {
                tCarReq: ptCarReq,
            },
            cache: false,
            success: function(tResult) {
                $('#odvModalDeleteSingleCarInter').modal('hide');
                setTimeout(function() {
                    JSxCallGetContentCarInter();
                }, 500);
            },

        });
    });
}

// Function delete sigle
// Create witsarut 22/07/2021 Off
function JSxConSetDeleteErrMsg(ptErrReq, tYesOnNo) {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    var nNumCheck = 0;
    if (aArrayConvert != '') {
        var nNumOfArr = aArrayConvert[0].length;
        nNumCheck = nNumOfArr;
    }
    if (nNumCheck > 1) {
        return false;
    }
    $('#odvModalDeleteSingleErrMgs').modal('show');
    $('#odvModalDeleteSingleErrMgs #ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptErrReq + ' ' + tYesOnNo);
    $('#odvModalDeleteSingleErrMgs #osmConfirmDelete').off('click').on('click', function(evt) {
        $.ajax({
            type: "POST",
            url: "connectionsettingEventDeleteRespond",
            data: {
                tErrReq: ptErrReq,
            },
            cache: false,
            success: function(tResult) {
                $('#odvModalDeleteSingleErrMgs').modal('hide');
                setTimeout(function() {
                    JSxCallGetContentRespond();
                }, 500);
            },
        });
    });
}


//Functionality : (event) Delete All
//Parameters :
//Creator : 11/06/2019 Witsarut (Bell)
//Return : 
//Return Type :
function JSxDeleteMutirecord() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        var aDataAgnCode = [];
        var aDataMerCode = [];
        var aDataWahCode = [];
        var ocbListItem = $(".ocbListItem");
        for (var nI = 0; nI < ocbListItem.length; nI++) {
            if ($($(".ocbListItem").eq(nI)).prop('checked')) {
                aDataAgnCode.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmAgnCodeDelete"));
                aDataBchCode.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmBchDelete"));
                aDataShpCode.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmShpDelete"));
                aDataWahCode.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmWahDelete"));
            }
        }

        $.ajax({
            type: "POST",
            url: "connectionsettingEventDeleteMultiple",
            data: {
                'paDataAgnCode': aDataAgnCode,
                'paDataBchCode': aDataBchCode,
                'paDataShpCode': aDataShpCode,
                'paDataWahCode': aDataWahCode,
            },
            cache: false,
            success: function(tResult) {
                tResult = tResult.trim();
                var aReturn = $.parseJSON(tResult);
                if (aReturn['nStaEvent'] == '1') {
                    $('#odvModalDeleteMutirecord').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    setTimeout(function() {
                        JSxCallGetContent();
                    }, 500);
                } else {
                    alert(aReturn['tStaMessg']);
                }
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : (event) Delete All
//Parameters :
//Creator : 11/06/2019 Witsarut (Bell)
//Return : 
//Return Type :
function JSxDeleteMutirecordMSShop() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        var aDataBchCode = [];
        var aDataPosCode = [];
        var ocbListItem = $(".ocbListItem");
        for (var nI = 0; nI < ocbListItem.length; nI++) {
            if ($($(".ocbListItem").eq(nI)).prop('checked')) {
                aDataBchCode.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmBchDelete"));
                aDataPosCode.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmPosDelete"));
            }
        }
        $.ajax({
            type: "POST",
            url: "connectionsettingEventDeleteMultipleMSShop",
            data: {
                'paDataBchCode': aDataBchCode,
                'paDataPosCode': aDataPosCode,
            },
            cache: false,
            success: function(tResult) {
                tResult = tResult.trim();
                var aReturn = $.parseJSON(tResult);
                if (aReturn['nStaEvent'] == '1') {
                    $('#odvModalDeleteMutirecord').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    setTimeout(function() {
                        JSxCallGetContentMSSHOP();
                    }, 500);
                } else {
                    alert(aReturn['tStaMessg']);
                }
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : (event) Delete All CstShp
//Parameters :
//Creator : 22/07/2021 Off
//Return : 
//Return Type :
function JSxDeleteMutirecordCstShp() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        var aDataBchCode = [];
        var ocbListItem = $(".ocbListItem");
        for (var nI = 0; nI < ocbListItem.length; nI++) {
            if ($($(".ocbListItem").eq(nI)).prop('checked')) {
                aDataBchCode.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmBchDelete"));
            }
        }
        $.ajax({
            type: "POST",
            url: "connectionsettingEventDeleteMultipleCstShp",
            data: {
                'paDataBchCode': aDataBchCode,
            },
            cache: false,
            success: function(tResult) {
                tResult = tResult.trim();
                var aReturn = $.parseJSON(tResult);
                if (aReturn['nStaEvent'] == '1') {
                    $('#odvModalDeleteMutirecordCstShp').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    setTimeout(function() {
                        JSxCallGetContentUserShop();
                    }, 500);
                } else {
                    alert(aReturn['tStaMessg']);
                }
                // JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : (event) Delete All CarInter
//Parameters :
//Creator : 22/07/2021 Off
//Return : 
//Return Type :
function JSxDeleteMutirecordCarInter() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        var aDataCarReq = [];
        var ocbListItem = $(".ocbListItem");
        for (var nI = 0; nI < ocbListItem.length; nI++) {
            if ($($(".ocbListItem").eq(nI)).prop('checked')) {
                aDataCarReq.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmCarDelete"));
            }
        }
        $.ajax({
            type: "POST",
            url: "connectionsettingEventDeleteMultipleCarInter",
            data: {
                'paDataCarReq': aDataCarReq,
            },
            cache: false,
            success: function(tResult) {
                tResult = tResult.trim();
                var aReturn = $.parseJSON(tResult);
                if (aReturn['nStaEvent'] == '1') {
                    $('#odvModalDeleteMutirecordCarInter').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    setTimeout(function() {
                        JSxCallGetContentCarInter();
                    }, 500);
                } else {
                    alert(aReturn['tStaMessg']);
                }
                // JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : (event) Delete All CarInter
//Parameters :
//Creator : 22/07/2021 Off
//Return : 
//Return Type :
function JSxDeleteMutirecordRespond() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        var aDataErrCode = [];
        var ocbListItem = $(".ocbListItem");
        for (var nI = 0; nI < ocbListItem.length; nI++) {
            if ($($(".ocbListItem").eq(nI)).prop('checked')) {
                aDataErrCode.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmErrCodeDelete"));
            }
        }
        $.ajax({
            type: "POST",
            url: "connectionsettingEventDeleteMultipleErrMsg",
            data: {
                'paDataErrCode': aDataErrCode,
            },
            cache: false,
            success: function(tResult) {
                tResult = tResult.trim();
                var aReturn = $.parseJSON(tResult);
                if (aReturn['nStaEvent'] == '1') {
                    $('#odvModalDeleteMutirecordCarInter').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    setTimeout(function() {
                        JSxCallGetContentRespond();
                    }, 500);
                } else {
                    alert(aReturn['tStaMessg']);
                }
                // JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}


//Functionality: Insert Text In Modal Delete
//Parameters: LocalStorage Data
//Creator: 05/07/2019 witsarut (Bell)
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


//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator: 05/07/2019 witsarut (Bell)
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
            $('.xCNIconDel').addClass('xCNDisabled');
        } else {
            $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
            $('.xCNIconDel').removeClass('xCNDisabled');
        }
    }
}

//Functionality: Function Chack Value LocalStorage
//Parameters: Event Select List Branch
//Creator: 05/07/2019 witsarut (Bell)
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