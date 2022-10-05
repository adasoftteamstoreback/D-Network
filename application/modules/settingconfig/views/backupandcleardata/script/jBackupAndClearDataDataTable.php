<script type="text/javascript">
    localStorage.removeItem("LocalItemData");
    $(document).ready(function(){
        $('.ocbListItem').unbind().click(function(){
            var nCode   = $(this).parent().parent().parent().data('code');  //code
            var tName   = $(this).parent().parent().parent().data('name');  //code
            $(this).prop('checked', true); 
            $('#odvBtnAddEdit').show();
            $('#odvBtnSelectBAC').show();
            var LocalItemData   = localStorage.getItem("LocalItemData");
            var obj = [];
            if(LocalItemData){
                obj = JSON.parse(LocalItemData);
            }else{}
            var aArrayConvert   = [JSON.parse(localStorage.getItem("LocalItemData"))];
            if(aArrayConvert == '' || aArrayConvert == null){
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxBACTextinModal();
            }else{
                var aReturnRepeat = JStBACFindObjectByKey(aArrayConvert[0],'nCode',nCode);
                if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                    obj.push({"nCode": nCode, "tName": tName });
                    localStorage.setItem("LocalItemData",JSON.stringify(obj));
                    JSxBACTextinModal();
                }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
                    localStorage.removeItem("LocalItemData");
                    $(this).prop('checked', false);
                    $('#odvBtnAddEdit').hide();
                    $('#odvBtnSelectBAC').hide();
                    var nLength = aArrayConvert[0].length;
                    for($i=0; $i<nLength; $i++){
                        if(aArrayConvert[0][$i].nCode == nCode){
                            delete aArrayConvert[0][$i];
                        }
                    }
                    var aNewarraydata = [];
                    for($i=0; $i<nLength; $i++){
                        if(aArrayConvert[0][$i] != undefined){
                            aNewarraydata.push(aArrayConvert[0][$i]);
                        }
                    }
                    localStorage.setItem("LocalItemData",JSON.stringify(aNewarraydata));
                    JSxBACTextinModal();
                }
            }
            JSxShowButtonChoose();
        });
        // Event Click Confrim Delete Multiple
        $('#odvBACModalDelDocMultiple #osmConfirmDelMultiple').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSoBACDelDocMultiple();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    });


    // Insert Text In Modal Delete
    function JSxBACTextinModal(){
        var aArrayConvert   = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") { } else {
            var tTextCode   = "";
            for ($i = 0; $i < aArrayConvert[0].length; $i++) {
                tTextCode += aArrayConvert[0][$i].nCode;
                tTextCode += " , ";
            }
            //Disabled ปุ่ม Delete
            if (aArrayConvert[0].length > 1) {
                $(".xCNIconDel").addClass("xCNDisabled");
            } else {
                $(".xCNIconDel").removeClass("xCNDisabled");
            }
            $("#odvBACModalDelDocMultiple #ospTextConfirmDelMultiple").text($('#oetTextComfirmDeleteMulti').val());
            $("#odvBACModalDelDocMultiple #ohdConfirmIDDelMultiple").val(tTextCode);
        }
    }

    // เช็คค่าใน array [หลายรายการ]
    function JStBACFindObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return "Dupilcate";
            }
        }
        return "None";
    }

    // เปิดปุ่มให้ลบได้ กรณีลบหลายรายการ
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

    // Click Next Page Controll Pagination
    function JSvBACClickPageList(ptPage){
        var nPageCurrent    = '';
        switch (ptPage) {
            case 'next': //กดปุ่ม Next
                $('.xWBtnNext').addClass('disabled');
                nPageOld    = $('.xWBACPageDataTable .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew
                break;
            case 'previous': //กดปุ่ม Previous
                nPageOld    = $('.xWBACPageDataTable .active').text(); // Get เลขก่อนหน้า
                nPageNew    = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew
                break;
            default:
                nPageCurrent = ptPage
        }
        JSvBACCallPageDataTable(nPageCurrent);
    }
    
    // Delete Document Multiple
    function JSoBACDelDocMultiple(){
        var aDataDelMultiple    = $('#odvBACModalDelDocMultiple #ohdConfirmIDDelMultiple').val();
        var aTextsDelMultiple   = aDataDelMultiple.substring(0, aDataDelMultiple.length - 2);
        var aDataSplit          = aTextsDelMultiple.split(" , ");
        var nDataSplitlength    = aDataSplit.length;
        var aNewIdDelete        = [];
        for ($i = 0; $i < nDataSplitlength; $i++) {
            aNewIdDelete.push(aDataSplit[$i].trim());
        }
        if (nDataSplitlength > 1) {
            JCNxOpenLoading();

            $.ajax({
                type    : "POST",
                url     : "docBACEventDelete",
                data    : {'tDataDocNo' : aNewIdDelete},
                cache   : false,
                timeout : 0,
                success : function (oResult) {
                    setTimeout(function () {
                        $('#odvBACModalDelDocMultiple').modal('hide');
                        $('#odvBACModalDelDocMultiple #ospTextConfirmDelMultiple').empty();
                        $('#odvBACModalDelDocMultiple #ohdConfirmIDDelMultiple').val('');
                        $('.modal-backdrop').remove();
                        localStorage.removeItem('LocalItemData');
                        JSvBACCallPageDataTable();
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    // Delete Document Single
    function JSoBACDelDocSingle(ptCurrentPage, ptBACDocNo, ptBACAgnCode, ptBACBchCode, ptBACDocRefCode) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            if (typeof(ptBACDocNo) != undefined && ptBACDocNo != "") {
                var tTextConfrimDelSingle = $('#oetTextComfirmDeleteSingle').val() + "&nbsp" + ptBACDocNo + "&nbsp" + $('#oetTextComfirmDeleteYesOrNot').val();
                $('#odvBACModalDelDocSingle #ospTextConfirmDelSingle').html(tTextConfrimDelSingle);
                $('#odvBACModalDelDocSingle').modal('show');
                $('#odvBACModalDelDocSingle #osmConfirmDelSingle').unbind().click(function() {
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: "docBACEventDelete",
                        data: {
                            'tDataDocNo': ptBACDocNo,
                            'tBACAgnCode' : ptBACAgnCode,
                            'tBACBchCode' : ptBACBchCode,
                            'tBACDocRefCode' : ptBACDocRefCode
                        },
                        cache: false,
                        timeout: 0,
                        success: function(oResult) {
                            var aReturnData = JSON.parse(oResult);
                            if (aReturnData['nStaEvent'] == '1') {
                                $('#odvBACModalDelDocSingle').modal('hide');
                                $('#odvBACModalDelDocSingle #ospTextConfirmDelSingle').html($('#oetTextComfirmDeleteSingle').val());
                                $('.modal-backdrop').remove();
                                setTimeout(function() {
                                JSvBACCallPageDataTable(ptCurrentPage);
                                }, 500);
                            } else {
                                JCNxCloseLoading();
                                FSvCMNSetMsgErrorDialog(aReturnData['tStaMessg']);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                });
            } else {
                FSvCMNSetMsgErrorDialog('Error Not Found Document Number !!');
            }
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Call BackupAndClear Page Edit
     * Parameters : {params}
     * Creator : 06/08/2022 Off
     * Last Modified : -
     * Return : view
     * Return Type : view
     */
    function JSvCallPageBackupAndClearEdit(ptPrgKey,ptDocType) {

    // JCNxOpenLoading();
    // JStCMMGetPanalLangSystemHTML('JSvCallPageAdMessageEdit', ptAdvCode);
    $.ajax({
        type: "POST",
        url: "BACEditPage",
        data: { 
            tPrgKey: ptPrgKey,
            tDocType: ptDocType
         },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (tResult != '') {
                $('#oliAdvTitleEdit').show();
                $('#odvBtnAdvInfo').hide();
                $('#odvBtnAddEdit').show();
                $('#ostBACDataTableDocument').html(tResult);
                $('#oetAdvCode').addClass('xCNDisable');
                $('.xCNDisable').attr('readonly', true);
                $('#odvSearch').hide();
            }
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });

    }

</script>