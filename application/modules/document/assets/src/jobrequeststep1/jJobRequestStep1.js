$("document").ready(function() {
    JSxJR1NavDefult('page_list');
    JSxCheckPinMenuClose();

    // Event Click Button Add Page
    $('#obtJR1CallPageAdd').unbind().click(function() {
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSvJR1CallPageAdd();
        } else {
            JCNxShowMsgSessionExpired();
        }
    });

    //รองรับการเข้ามาแบบ Noti
    var nJOB1StaBrowseType = $("#oetJOB1StaBrowse").val();
    switch (nJOB1StaBrowseType) {
        case '2':
            var tAgnCode = $('#oetJOB1JumpAgnCode').val();
            var tBchCode = $('#oetJOB1JumpBchCode').val();
            var tDocNo = $('#oetJOB1JumpDocNo').val();
            JSvJR1CallPageEdit(tAgnCode, tBchCode, tDocNo, '');
            break;
        default:
            JSvJR1CallPageList();
    }

});

// ---------------------------------------------- controll layout button ------------------------------------------------------------
function JSxJR1NavDefult(ptType) {
    // controll layout button
    if (ptType == "page_list") {
        // controller title
        $('#oliJR1TitleViewData').hide();
        $('#oliJR1TitleAdd').hide();
        $('#oliJR1TitleEdit').hide();
        // controll Button
        $('#obtBtnBack').hide();
        $('#obtJR1PrintDoc').hide();
        $('#obtJR1CancelDoc').hide();
        $('#obtJR1ApproveDoc').hide();
        $('#odvJR1BtnGrpSave').hide();
        $('#obtJR1CallPageAdd').show();
    } else if (ptType == "page_add") {
        // controller title
        $('#oliJR1TitleViewData').hide();
        $('#oliJR1TitleAdd').show();
        $('#oliJR1TitleEdit').hide();
        // controll Button
        $('#obtBtnBack').show();
        $('#obtJR1PrintDoc').hide();
        $('#obtJR1CancelDoc').hide();
        $('#obtJR1ApproveDoc').hide();
        $('#odvJR1BtnGrpSave').show();
        $('#obtJR1CallPageAdd').hide();
    } else if (ptType == "showpage_edit") {
        // controller title
        $('#oliJR1TitleViewData').hide();
        $('#oliJR1TitleAdd').hide();
        $('#oliJR1TitleEdit').show();
        // controll Button
        $('#obtBtnBack').show();
        $('#obtJR1PrintDoc').show();
        $('#obtJR1CancelDoc').show();
        $('#obtJR1ApproveDoc').show();
        $('#odvJR1BtnGrpSave').show();
        $('#obtJR1CallPageAdd').hide();
    }
}

// ------------------------------------------------ Call page func ------------------------------------------------------------------
// Call Page List
function JSvJR1CallPageList() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        JCNxOpenLoading();
        $.ajax({
            type: "GET",
            url: "docJR1PageList",
            cache: false,
            timeout: 0,
            success: function(tResult) {
                JSxJR1NavDefult('page_list');
                $("#odvJR1PageDocument").html(tResult);
                JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
                JSvJR1CallPageDataTable()
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function: Call Page DataTable
function JSvJR1CallPageDataTable(pnPage) {
    var oAdvanceSearch = JSoJR1GetAdvanceSearchData();
    var nPageCurrent = pnPage;
    if (typeof(nPageCurrent) == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }
    $.ajax({
        type: "POST",
        url: "docJR1DataTable",
        data: {
            nPageCurrent: nPageCurrent,
            oAdvanceSearch: oAdvanceSearch
        },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aReturnData = JSON.parse(oResult);
            if (aReturnData['nStaEvent'] == '1') {
                $('#ostJR1DataTableDocument').html(aReturnData['tViewDataTable']);
            } else {
                var tMessageError = aReturnData['tStaMessg'];
                FSvCMNSetMsgErrorDialog(tMessageError);
            }
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Call Document Page Add
function JSvJR1CallPageAdd() {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docJR1PageAdd",
        data: {
            'pnType': 1, // เข้าแบบปรกติจากหน้า List
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxJR1NavDefult('page_add');
            $("#odvJR1PageDocument").html(tResult);
            $("#odvJR1ApvBy").hide();

            //โหลดสินค้าใน Product Temp
            JCNxCloseLoading();
            JSvJR1LoadPdtDataTableHtml();

        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Call Product Data Table
function JSvJR1LoadPdtDataTableHtml(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var tJR1StaApv = $("#ohdJR1StaApv").val();
        var tJR1StaDoc = $("#ohdJR1StaDoc").val();
        var tJR1DocNo = "";
        var pnNewPage = "";
        var nPageCurrent = "";
        if ($("#ohdJR1Route").val() != "docJR1EventAdd") {
            tJR1DocNo = $("#oetJR1DocNo").val();
        }
        if (pnPage == '' || pnPage == null) {
            pnNewPage = 1;
        } else {
            pnNewPage = pnPage;
        }
        nPageCurrent = pnNewPage;

        $.ajax({
            type: "POST",
            url: "docJR1TableDTTemp",
            data: {
                'ptJR1AgnCode'  : $('#ohdJR1ADCode').val(),
                'ptJR1BCHCode'  : $('#ohdJR1BchCode').val(),
                'ptJR1DocNo'    : tJR1DocNo,
                'ptJR1StaApv'   : tJR1StaApv,
                'ptJR1StaDoc'   : tJR1StaDoc,
            },
            cache: false,
            Timeout: 0,
            success: function(oResult) {
                var aReturnData = JSON.parse(oResult);
                if (aReturnData['nStaEvent'] == '1') {
                    $('#odvJR1DataPdtTableDTTemp').html('');
                    $('#odvJR1DataPdtTableDTTemp').html(aReturnData['tJR1PdtAdvTableHtml']);
                    var aJR1EndOfBill = aReturnData['aJR1EndOfBill'];
                    JSxJR1SetFooterEndOfBill(aJR1EndOfBill);
                    // JCNxCloseLoading();
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    // JCNxCloseLoading();
                }
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Page Edit
function JSvJR1CallPageEdit(ptAgnCode, ptBchCode, ptDocumentNumber, ptCarCode) {
    JSxCheckPinMenuClose();
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docJR1PageEdit",
        data: {
            'ptAgnCode': ptAgnCode,
            'ptBchCode': ptBchCode,
            'ptDocumentNumber': ptDocumentNumber,
            'ptCarCode': ptCarCode
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            JSxJR1NavDefult('showpage_edit');
            $("#odvJR1PageDocument").html(tResult);
            JSvJR1LoadPdtDataTableHtml();
            JCNxCloseLoading();

            //เช็คว่าเอกสารยกเลิก หรือเอกสารอนุมัติ
            JSxJR1ControlFormWhenCancelOrApprove();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Control ปุ่ม และอินพุตต่างๆ [เอกสารยกเลิก / เอกสารอนุมัติ]
function JSxJR1ControlFormWhenCancelOrApprove() {
    var tStatusDoc = $('#ohdJR1StaDoc').val();
    var tStatusApv = $('#ohdJR1StaApv').val();

    //control ฟอร์ม
    if (tStatusDoc == 3 || (tStatusDoc == 1 && tStatusApv == 1)) { //เอกสารยกเลิก
        //ปุ่มเลือก
        $('.xCNBtnBrowseAddOn').addClass('disabled');
        $('.xCNBtnBrowseAddOn').attr('disabled', true);

        //วันที่
        $('.xCNDatePicker').attr('disabled', true);

        //ปุ่มเวลา
        $('.xCNBtnDateTime').addClass('disabled');
        $('.xCNBtnDateTime').attr('disabled', true);

        //อินพุต
        $('.form-control').attr('readonly', true);

        //เพิ่มข้อมูลสินค้า
        $('.xCNHideWhenCancelOrApprove').hide();

        //พวก selectpicker
        $('.selectpicker').prop("disabled", true)

        //ปุ่มน้ำมัน
        $('.xCNClickMile').off();
    }

    //control ปุ่ม
    if (tStatusDoc == 3) { //เอกสารยกเลิก
        //ปุ่มยกเลิก
        $('#obtJR1CancelDoc').hide();

        //ปุ่มอนุมัติ
        $('#obtJR1ApproveDoc').hide();

        //ปุ่มบันทึก
        $('#odvJR1BtnGrpSave').hide();
    } else if (tStatusDoc == 1 && tStatusApv == 1) { //เอกสารอนุมัติแล้ว
        //ปุ่มยกเลิก
        if($('#ohdJR1UseInJOB2').val() == 1){ // ถูกอ้างอิงใบสั่งงานเเล้วยกเลิกไม่ได้
            $('#obtJR1CancelDoc').hide();
        }else{ //ยังไม่ถูกอ้างอิงยกเลิกได้
            $('#obtJR1CancelDoc').show();
        }

        //ปุ่มอนุมัติ
        $('#obtJR1ApproveDoc').hide();

        //ปุ่มบันทึก
        $('#odvJR1BtnGrpSave').show();

        //สามารถกรอกหมายเหตุได้
        $('#otaJR1Remark').attr('readonly', false);
    }

    if (tStatusDoc == 1 || tStatusDoc == 3) {
        $('.xWBtnDisabledOnSave').attr('disabled', true);
    }

    $("#oetJR1DocDate").attr('disabled', false);
}

//ยกเลิกเอกสาร
function JSxJR1DocumentCancel(pbIsConfirm) {
    var tDocNo = $("#oetJR1DocNo").val();
    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "docJR1CancelDocument",
            data: {
                'tBchCode': $('#ohdJR1BchCode').val(),
                'tDocNo': tDocNo,
                'tRefIntDoc': $('#oetJR1DocRefBookCode').val()
            },
            cache: false,
            timeout: 0,
            success: function(tResult) {
                $("#odvJR1PopupCancel").modal("hide");
                $('.modal-backdrop').remove();
                var aReturnData = JSON.parse(tResult);
                if (aReturnData['rtCode'] == '1') {
                    JSvJR1CallPageEdit('', '', tDocNo, $('#oetJR1CarRegCode').val());
                } else {
                    var tMessageError = aReturnData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog(tMessageError);
                    JCNxCloseLoading();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        $('#odvJR1PopupCancel').modal({ backdrop: 'static', keyboard: false });
        $("#odvJR1PopupCancel").modal("show");
    }
}