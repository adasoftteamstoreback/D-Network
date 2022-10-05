<script type="text/javascript">
    $(document).ready(function(){

        $('.selectpicker').selectpicker('refresh');
        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        
        // var dCurrentDate    = new Date();
        // if($('#oetMNTDocDate').val() == ''){
        //         $('#oetMNTDocDate').datepicker("setDate",dCurrentDate); 
        //     }
            
            $('#obtMNTDocDateFrom').unbind().click(function(){
                $('#oetMNTDocDateFrom').datepicker('show');
            });
            $('#obtMNTDocDateTo').unbind().click(function(){
                $('#oetMNTDocDateTo').datepicker('show');
            });

            
            JSxMNTGetPageDataTable();
    });

    $('#obtMainAdjustProductFilter').click(function(e) {

            JSxMNTGetPageDataTable();
    });
    var tBaseURL = '<?php echo base_url(); ?>';
    var nLangEdits = '<?php echo $this->session->userdata("tLangEdit") ?>';
    function JSxMNTClearConditionAll(){
        $('#oetMNTAgnCode').val('');
        $('#oetMNTAgnName').val('');
        $('#oetMNTDocDate').val('');
        $('#ocmMNTDocType').val('');
        
        JSxMNTGetPageDataTable();

    }

  









</script>