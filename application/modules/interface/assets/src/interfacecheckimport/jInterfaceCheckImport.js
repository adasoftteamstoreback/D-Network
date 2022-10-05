function JSvInterfaceImportCallPage(){ //กลับหน้าหลัก
    $.ajax({
        type    : "GET",
        url     : "masChkImport/0/0",
        data    : {},
        cache   : false,
        timeout : 5000,
        success : function (tResult) {
            $('.odvMainContent').html(tResult);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}
