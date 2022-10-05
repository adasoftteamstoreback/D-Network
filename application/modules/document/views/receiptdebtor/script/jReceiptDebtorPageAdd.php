<script>
    $('document').ready(function(){
        JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
        var nStaRef = $('#ohdStaRef').val();
        if (nStaRef != '') {
            if (nStaRef == 0) {
                $("#ocmCreditNoteXphCshOrCrd.selectpicker").val("0").selectpicker("refresh");
            }else if(nStaRef == 1){
                $("#ocmCreditNoteXphCshOrCrd.selectpicker").val("1").selectpicker("refresh");
            }else{
                $("#ocmCreditNoteXphCshOrCrd.selectpicker").val("2").selectpicker("refresh");
            }
        }

        $("#olbUPFChsForInputTARTSpHDodvDOShowDataTable").hide();
    });

        //ค้นหาสินค้าใน temp
    function JSvRCBCSearchPdtHTML() {
        var value = $("#oetRCBFilterPdt").val().toLowerCase();
        $("#otbRCBPdtTableList tbody tr ").filter(function() {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

    function JSxRCBPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?php echo FCNaHGetLangEdit(); ?>'}, // Lang ID
            {"ComCode"      : '<?php echo FCNtGetCompanyCode(); ?>'}, // Company Code
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tBchCode); ?>'},
            {"DocCode"      : '<?php echo $tXshDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tBchCode;?>'},
        ];
        var tGrandText = $('#odvRCBDataTextBath').text();
        window.open("<?=base_url(); ?>formreport/Frm_SQL_SMBillReceipt?infor=" + JCNtEnCodeUrlParameter(aInfor) + "&Grand="+tGrandText, '_blank');
    } 
</script>