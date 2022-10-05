<script type="text/javascript">
 $(document).ready(function(){
    $('.selectpicker').selectpicker();
    if(JSbCustomerIsCreatePage()){
        //Customer Code
        $("#oetCstCode").attr("disabled", true);
        $('#ocbCustomerAutoGenCode').change(function(){
            if($('#ocbCustomerAutoGenCode').is(':checked')) {
                $('#oetCstCode').val('');
                $("#oetCstCode").attr("disabled", true);
                $('#odvCstCodeForm').removeClass('has-error');
                $('#odvCstCodeForm em').remove();
            }else{
                $("#oetCstCode").attr("disabled", false);
            }
        });
        JSxCustomerVisibleComponent('#ocbCustomerAutoGenCode', true);
    }
    
    if(JSbCustomerIsUpdatePage()){
        // Customer Code
        $("#oetCstCode").attr("readonly", true);
        $('#odvCstAutoGenCode input').attr('disabled', true);
            JSxCustomerVisibleComponent('#odvCstAutoGenCode', false);    
        }
    });
</script>
