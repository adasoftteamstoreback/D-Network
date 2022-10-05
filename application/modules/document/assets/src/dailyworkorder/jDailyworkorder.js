$("document").ready(function() {
    var tReturnCode = $('#ohdRtCode').val();
    var tDocNo = $('#ohdDocNo').val()
    JSvDWOCallPageSearch();
    JSxCheckPinMenuClose();
})

//แท็บ Search
function JSvDWOCallPageSearch() {
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docDWOSearchList",
        data: {
            'pnType': 1, // เข้าแบบปรกติจากหน้า List
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $("#odvDWOPageDocument").html(tResult);
            $("#odvSatApvBy").hide(); 
            JSvDWOCallPageDataTableFilter();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//กดปุ่ม Filter
function JSvDWOCallPageDataTableFilter() {
    var dDate = $("#ohdDWODateSearch").val();
    var tBchCode = $("#ohdDWOBchCode").val();
    var nStatus = $("#ocmDWOStatusBay").val();
    var tDepart = $("#ohdDWODptCode").val();
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "docDWOPageMonitor",
        data: {
            'pnType': 1, // เข้าแบบปรกติจากหน้า List
            'tBchCode' : tBchCode,
            'dDate' : dDate,
            'nStatus' : nStatus,
            'tDepart' : tDepart
        },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            $("#odvDWOPageDatatable").html(tResult);
            $("#odvSatApvBy").hide(); 
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

