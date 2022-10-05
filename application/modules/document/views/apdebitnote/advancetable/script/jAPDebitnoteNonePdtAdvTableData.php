<script>
    $(document).ready(function () {
        // เปิด/ปิด ฟอร์ม หรับใบลดหนี้ไม่มีสินค้า
        $('#oetAPDSplCode').on('change', function(){
            if($(this).val() != ''){
                JSxCMNVisibleComponent('#otrAPDNonePdtMessageForm', false);
                JSxCMNVisibleComponent('#otrAPDNonePdtActiveForm', true);
            }else{
                JSxCMNVisibleComponent('#otrAPDNonePdtMessageForm', true);
                JSxCMNVisibleComponent('#otrAPDNonePdtActiveForm', false);
            }
        });
        
        if(JCNbAPDIsUpdatePage()){
            JSxCMNVisibleComponent('#otrAPDNonePdtActiveForm', true);
            JSxCMNVisibleComponent('#otrAPDNonePdtMessageForm', false);
            JSoAPDCalEndOfBillNonePdt();
        }
        
        
    });
    
    /**
    * Functionality : Add or Update
    * Parameters : route
    * Creator : 25/06/2019 Piya
    * Update : -
    * Return : -
    * Return Type : -
    */
    function JSxAPDAddUpdateDisChg() {
        FSvPDTAddPdtIntoTableDT();
    }
    
</script>










