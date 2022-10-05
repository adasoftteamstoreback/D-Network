<script>
    $(document).ready(function () {
        // เปิด/ปิด ฟอร์ม หรับใบลดหนี้ไม่มีสินค้า
        $('#oetPNSplCode').on('change', function(){
            if($(this).val() != ''){
                JSxCMNVisibleComponent('#otrPNNonePdtMessageForm', false);
                JSxCMNVisibleComponent('#otrPNNonePdtActiveForm', true);
            }else{
                JSxCMNVisibleComponent('#otrPNNonePdtMessageForm', true);
                JSxCMNVisibleComponent('#otrPNNonePdtActiveForm', false);
            }
        });
        
        if(JCNbPNIsUpdatePage()){
            JSxCMNVisibleComponent('#otrPNNonePdtActiveForm', true);
            JSxCMNVisibleComponent('#otrPNNonePdtMessageForm', false);
            JSoPNCalEndOfBillNonePdt();
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
    function JSxPNAddUpdateDisChg() {
        FSvPDTAddPdtIntoTableDT();
    }
</script>










