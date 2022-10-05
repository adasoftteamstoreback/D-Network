<script type="text/javascript">
    var tBaseURL = '<?php echo base_url(); ?>';
    var nLangEdits = '<?php echo $this->session->userdata("tLangEdit") ?>';

    $(document).ready(function(){
     
        JCNxCloseLoading();
   

        $('.selectpicker').selectpicker();

        FSxBCMCallStandDataTable();
    });


$('#obtBCMBtnFilter').click(function(){
    FSxBCMCallStandDataTable();
});
// Function: Confirm Filter DashBoard
// Parameters: Document Ready Or Parameter Event
// Creator: 06/02/2020 Wasin(Yoshi)
// Return: View Page Main
// ReturnType: View
function FSxBCMCallStandDataTable(nPageCurrent){
    JCNxOpenLoading();
    if(nPageCurrent=='' || nPageCurrent == undefined || nPageCurrent == 'NaN' ){
        nPageCurrent = 1;
    }
    $.ajax({
        type: "POST",
        url: "dasBCMCallPageStandDataTable",
        data: $('#ofmBCMSALStandFormFilter').serialize()+"&nPageCurrent="+nPageCurrent,
        cache: false,
        timeout: 0,
        success : function(paDataReturn){
           
            $('#odvPanelSaleData').html(paDataReturn);

            JCNxCloseLoading();
        },
        error : function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR,textStatus,errorThrown);
        }
    });
}




    $('#obtBCMBtnBackBatPage').click(function(){
        JSxBCMCallPageBatch();
    });



</script>