<script>
  $(document).ready(function(){
        localStorage.removeItem("LocalItemData");
        JSxCheckPinMenuClose();
        JSxBCMCallPageBatch();

        $('#obtBCMBtnBackBatPage').hide();
  });

// Function: Call Main Page DashBoard
// Parameters: Document Ready Or Parameter Event
// Creator: 14/01/2020 Wasin(Yoshi)
// Return: View Page Main
// ReturnType: View
function JSxBCMCallPageBatch(){
    $.ajax({
        type: "POST",
        url: "dasBCMCallPageBatch",
        cache: false,
        timeout: 0,
        success: function (tResult){
            $("#odvBCMSALContentPage").html(tResult);
            $('#obtBCMBtnBackBatPage').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

$('#oliBCMSALTitle').click(function(){
    JSxBCMCallPageBatch();
});


$('#obtBCMExport').click(function(){
    JSxBCMExport();
});

// Function: Call Main Page DashBoard
// Parameters: Document Ready Or Parameter Event
// Creator: 14/01/2020 Wasin(Yoshi)
// Return: View Page Main
// ReturnType: View
function JSxBCMExport(){
    var tBatID  = $('#oetBatID').val();
    var tTabSht = $('#ohdTabSht').val(); 
    if(tBatID!='' && tBatID!=undefined){
        window.open('dasBCMExportStand?tBatchID='+tBatID+'&tTabSht='+tTabSht , '_blank');
    }else{
        window.open('dasBCMExportBatch' , '_blank');
    }
}

</script>