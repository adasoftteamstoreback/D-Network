var nStaNewBrowseType   = $('#oetNewStaBrowse').val();
var tCallNewBackOption  = $('#oetNewCallBackOption').val();

$('document').ready(function(){
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxNewNavDefult();
    if(nStaNewBrowseType != 1){
        JSvCallPageNewList();
    }else{
        JSvCallPageNewAdd();
    }
});

//function : Function Clear Defult Button News
//Parameters : Document Ready
//Creator : 16/06/2021 Supawat
//Return : Show Tab Menu
//Return Type : -
function JSxNewNavDefult(){
    if(nStaNewBrowseType != 1 || nStaNewBrowseType == undefined){
        $('.xCNNewVBrowse').hide();
        $('.xCNNewVMaster').show();
        $('.xCNChoose').hide();
        $('#oliNewTitleAdd').hide();
        $('#oliNewTitleEdit').hide();
        $('#odvBtnAddEdit').hide();
        $('#odvBtnNewInfo').show();
    }else{
        $('#odvModalBody .xCNNewVMaster').hide();
        $('#odvModalBody .xCNNewVBrowse').show();
        $('#odvModalBody #odvNewMainMenu').removeClass('main-menu');
        $('#odvModalBody #oliNewNavBrowse').css('padding', '2px');
        $('#odvModalBody #odvNewBtnGroup').css('padding', '0');
        $('#odvModalBody .xCNNewBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNNewBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

//function : Call Product Brand Page News  
//Parameters : Document Redy And Event Button
//Creator :	16/06/2021 Supawat
//Return : View
//Return Type : View
function JSvCallPageNewList(){
    $('#oetSearchNew').val('');
    JCNxOpenLoading();    
    $.ajax({
        type    : "POST",
        url     : "newslist",
        cache   : false,
        timeout : 0,
        success : function(tResult){
            $('#odvContentPageNews').html(tResult);
            JSvNewDataTable();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//function: Call News Data List
//Parameters: Ajax Success Event 
//Creator:	16/06/2021 Supawat
//Return: View
//Return Type: View
function JSvNewDataTable(pnPage){
    var tSearchAll      = $('#oetSearchNew').val();
    var nPageCurrent    = (pnPage === undefined || pnPage == '')? '1' : pnPage;
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "newsDataTable",
        data: {
            tSearchAll: tSearchAll,
            nPageCurrent: nPageCurrent,
        },
        cache: false,
        Timeout: 0,
        success: function(tResult){
            if (tResult != "") {
                $('#ostDataNew').html(tResult);
            }
            JSxNewNavDefult();
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : Call News Page Add  
//Parameters : Event Button Click
//Creator : 16/06/2021 Supawat
//Return : View
//Return Type : View
function JSvCallPageNewAdd(){
    JCNxOpenLoading();
    $.ajax({
        type    : "POST",
        url     : "newsPageAdd",
        cache   : false,
        timeout : 0,
        success: function(tResult){
            if (nStaNewBrowseType == 1) {
                $('#odvModalBodyBrowse').html(tResult);
                $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
                $('.xCNNewVMaster').hide();
                $('.xCNNewVBrowse').show();
            }else{
                $('.xCNNewVBrowse').hide();
                $('.xCNNewVMaster').show();
                $('#oliNewTitleEdit').hide();
                $('#oliNewTitleAdd').show();
                $('#odvBtnNewInfo').hide();
                $('#odvBtnAddEdit').show();
            }
            $('#odvContentPageNews').html(tResult);
       
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : function submit by submit button only
//Parameters : route
//Creator : 16/06/2021 Supawat
//Update : -
//Return : -
//Return Type : -
function JSxSetStatusClickNewSubmit(ptRoute){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        $('#ofmAddNew').validate().destroy();
        $('#ofmAddNew').validate({
            rules: {
                oetNewCode:  {"required" :{}},
                oetNewName:  {"required" :{}},
            },
            messages: {
                oetNewCode : {
                    "required"      : $('#oetNewCode').attr('data-validate-required'),
                },
                oetNewName : {
                    "required"      : $('#oetNewName').attr('data-validate-required'),
                },
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
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass( "has-error" ).removeClass( "has-success" );
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass( "has-success" ).removeClass( "has-error" );
            },
            submitHandler: function(form){
                $.ajax({
                    type    : "POST",
                    url     : ptRoute,
                    data    : $('#ofmAddNew').serialize(),
                    cache   : false,
                    timeout : 0,
                    success : function(tResult){
                        if(nStaNewBrowseType != 1){
                            var aReturn = JSON.parse(tResult);
                            if(aReturn['nStaEvent'] == 1){
                                var nNewCodeCallBack    = aReturn['tCodeReturn'];
                                var oNewCallDataTableFile = {
                                    ptElementID : 'odvNewShowDataTable',
                                    ptBchCode   : $('#ohdBchCode').val(),
                                    ptDocNo     : nNewCodeCallBack,
                                    ptDocKey    :'TCNMNews',
                                }
    
                                JCNxUPFInsertDataFile(oNewCallDataTableFile);


                                switch(aReturn['nStaCallBack']){
                                    case '1' :
                                        JSvCallPageNewsEdit(aReturn['tCodeReturn']);
                                    break;
                                    case '2' :
                                        JSvCallPageNewAdd();
                                    break;
                                    case '3' :
                                        JSvCallPageNewList();
                                    break;
                                    case '4' :
                                        /* บันทึกและส่งข่าวสาร */

                                        var aNewIdDelete = [];
                                        aNewIdDelete.push(aReturn['tCodeReturn']);

                                        $.ajax({
                                            type: "POST",
                                            url: "newsEventSendNoti",
                                            data:{
                                                'aNewCode' : aNewIdDelete,
                                                'nStaChkAll' : 2
                                            },
                                            success: function (tResult){
                                                tResult = tResult.trim();
                                                var aReturn = JSON.parse(tResult);
                                                if (aReturn['nStaEvent'] == '1'){
                                                    JSvCallPageNewList();
                                                    FSvCMNSetMsgSucessDialog(aReturn['tStaMessg']);
                                                }else{
                                                    JCNxCloseLoading();
                                                    FSvCMNSetMsgWarningDialog(aReturn['tStaMessg']);    
                                                }
                                                JSxNewNavDefult();
                                            },
                                             error: function(jqXHR, textStatus, errorThrown) {
                                                JCNxResponseError(jqXHR, textStatus, errorThrown);
                                            }
                                        });
                                        
                                    break;
                                    default:
                                        JSvCallPageNewsEdit(aReturn['tCodeReturn']);
                                }
                                JCNxImgWarningMessage(aReturn['aImgReturn']);
                            }else{
                                JCNxCloseLoading();
                                FSvCMNSetMsgWarningDialog(aReturn['tStaMessg']);
                            }
                        }else{
                            JCNxCloseLoading();
                            JCNxBrowseData(tCallNewBackOption);  
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        });
    }else{
        JCNxShowMsgSessionExpired();  
    }
}


//Functionality: Event Multi Delete
//Parameters: Event Button Delete All
//Creator: 16/06/2021 Supawat
//Return:  object Status Delete
//Return Type: object
function JSoNewsDelChoose(pnPage){
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
            url: "newsEventDelete",
            data:{
                'tIDCode' : aNewIdDelete
            },
            success: function (tResult){
                tResult = tResult.trim();
                var aReturn = JSON.parse(tResult);
                if (aReturn['nStaEvent'] == '1'){
                    $('#odvModalDelNew').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    $('#ospConfirmIDDelete').val('');
                    $('#ohdConfirmIDDelete').val('');
                    setTimeout(function() {
                        if(aReturn["nNumRowNew"]!=0){
                            if(aReturn["nNumRowNew"]>10){
                                nNumPage = Math.ceil(aReturn["nNumRowNew"]/10);
                                if(pnPage<=nNumPage){
                                    JSvNewDataTable(pnPage);
                                }else{
                                    JSvNewDataTable(nNumPage);
                                }
                            }else{
                                JSvNewDataTable(1);
                            }
                        }else{
                            JSvNewDataTable(1);
                        }
                    }, 500);
                }else{
                    JCNxOpenLoading();
                    alert(aReturn['tStaMessg']);    
                }
                JSxNewNavDefult();
            },
             error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }else{
        localStorage.StaDeleteArray = '0';
        return false;
    }
}


//Functionality: Event Multi Delete
//Parameters: Event Button Delete All
//Creator: 16/06/2021 Supawat
//Return:  object Status Delete
//Return Type: object
function JSoNewsSendNotiChoose(){
    JCNxOpenLoading();
    var aData = $('#ohdConfirmIDDelete').val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];
 
    if($('#ocmNewCheckDeleteAll').is(':checked')==true){
        var nStaChkAll = 1;
    }else{
        var nStaChkAll = 2;
    }
 
    for ($i = 0; $i < aDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }



        $.ajax({
            type: "POST",
            url: "newsEventSendNoti",
            data:{
                'aNewCode' : aNewIdDelete,
                'nStaChkAll' : nStaChkAll
            },
            success: function (tResult){
                tResult = tResult.trim();
                var aReturn = JSON.parse(tResult);
                if (aReturn['nStaEvent'] == '1'){
                    $('#odvNewModalConfirm').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    $('#ospConfirmIDDelete').val('');
                    $('#ohdConfirmIDDelete').val('');
                    FSvCMNSetMsgSucessDialog(aReturn['tStaMessg']);
                }else{
                    JCNxCloseLoading();
                    FSvCMNSetMsgWarningDialog(aReturn['tStaMessg']);    
                }
                JSxNewNavDefult();
            },
             error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
  
}



//Functionality : เปลี่ยนหน้า pagenation
//Parameters : Event Click Pagenation
//Creator : 16/06/2021 Supawat
//Return : View
//Return Type : View
function JSvNewsClickPage(ptPage){
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageNews .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageNews .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvNewDataTable(nPageCurrent);
}

//Functionality : Event Single Delete
//Parameters : Event Icon Delete
//Creator : 16/06/2021 Supawat
//Return : object Status Delete
//Return Type : object
function JSoNewsDel(pnPage, tIDCode, ptName, tYesOnNo){
    var aData = $('#ohdConfirmIDDelete').val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;

    if (aDataSplitlength == '1') {
        $('#odvModalDelNew').modal('show');
        $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode + ' ( ' + ptName + ' ) '+ tYesOnNo );
        $('#osmConfirm').on('click', function(evt) {
            if (localStorage.StaDeleteArray != '1') {
                $.ajax({
                    type: "POST",
                    url : "newsEventDelete",
                    data: { 'tIDCode': tIDCode},
                    cache: false,
                    success: function(tResult){
                        tResult = tResult.trim();
                        var aReturn = $.parseJSON(tResult);
                        if (aReturn['nStaEvent'] == '1'){
                            $('#odvModalDelNew').modal('hide');
                            $('#ospConfirmDelete').empty();
                            localStorage.removeItem('LocalItemData');
                            $('#ospConfirmIDDelete').val('');
                            $('#ohdConfirmIDDelete').val('');
                            setTimeout(function() {
                                if(aReturn["nNumRowNew"]!=0){
                                    if(aReturn["nNumRowNew"]>10){
                                        nNumPage = Math.ceil(aReturn["nNumRowNew"]/10);
                                        if(pnPage<=nNumPage){
                                            JSvNewDataTable(pnPage);
                                        }else{
                                            JSvNewDataTable(nNumPage);
                                        }
                                    }else{
                                        JSvNewDataTable(1);
                                    }
                                }else{
                                    JSvNewDataTable(1);
                                }
                            }, 500);

                        }else{
                            JCNxOpenLoading();
                            alert(aReturn['tStaMessg']);   
                        }
                        JSxNewNavDefult();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        });
    }
}

//Functionality : Call News Page Edit  
//Parameters : Event Button Click 
//Creator : 16/06/2021 Supawat
//Return : View
//Return Type : View
function JSvCallPageNewsEdit(ptNewCode){
    JCNxOpenLoading();
    $.ajax({
        type : "POST",
        url  : "newsPageEdit",
        data : {
            tNewCode : ptNewCode
        },
        cache: false,
        timeout: 0,
        success: function (tResult){
            if(tResult != ''){
                $('#oliNewTitleAdd').hide();
                $('#oliNewTitleEdit').show();
                $('#odvBtnNewInfo').hide();
                $('#odvBtnAddEdit').show();
                $('#odvContentPageNews').html(tResult);
                // $('#oetNewCode').addClass('xCNDisable');
                // $('#oetNewCode').attr('readonly', true);
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

//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator: 16/06/2021 Supawat
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
//Creator: 16/06/2021 Supawat
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
//Creator: 16/06/2021 Supawat
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
