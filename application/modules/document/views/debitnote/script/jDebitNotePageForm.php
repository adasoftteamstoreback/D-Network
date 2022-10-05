<script type="text/javascript">
    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit");?>';
    var dCurrentDate    = new Date();
    var tAmOrPm         = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
    var tCurrentTime    = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes() + " " + tAmOrPm;
    var oDate           = new Date();
    oDate.setSeconds(1800);
    var tTimeEnd        = oDate.toTimeString().substr(0, 9);
    var tUsrLevel       = '<?=$this->session->userdata('tSesUsrLevel')?>';
    // วันที่ประเมิน
    $('.xCNDatePicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        disableTouchKeyboard : true,
        autoclose: true
    });

    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });

    $('.selectpicker').selectpicker();


    // Event Click Submit From Document
    $('#obtDBNSubmitFromDoc').unbind().click(function(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== "undefined" && nStaSession == 1) {
            JSxDBNSetStatusClickSubmit(1);
            $('#obtSubmitDBN').click();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Set Status Click Submit
    function JSxDBNSetStatusClickSubmit(pnStatus){
        $("#ohdDBNCheckSubmitByButton").val(pnStatus);
    }

    // Validate and insert
    function JSoAddEditDBN(ptRoute){
        if($("#ohdDBNCheckSubmitByButton").val() == 1) {
            JSxDBNSubmitEventByButton(ptRoute);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }
    
    // Function: Validate Success And Send Ajax Add / Update Document
    function JSxDBNSubmitEventByButton(ptRoute){
        JCNxOpenLoading();
        $.ajax({
            type : "POST",
            url  : ptRoute,
            data : $('#ofmDBNAddForm').serialize(),
            success: function(oResult){
                let aReturn = JSON.parse(oResult);
                if(aReturn['nStaEvent'] == 1){
                    switch(aReturn['nStaCallBack']) {
                        case '1':
                            JSvDBNCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo'],tCstCode);
                            break;
                        case '2':
                            JSvDBNCallPageEdit(aReturn['tAgnCode'],aReturn['tBchCode'],aReturn['tDocNo'],tCstCode);
                            break;
                        case '3':
                            JSvDBNCallPageList()
                            break;
                        default:
                            JSvDBNCallPageList()
                            break;
                    }
                }else{
                    FCMNSetMsgErrorDialog(aReturn['tStaMessg']);
                    JCNxCloseLoading();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Function Search Pdt HTML
    function JSvDBNDOCSearchPdtHTML(){
        var value = $("#oetDBNSearchPDT").val().toLowerCase();
        $("#otbDBNPdtTable tbody tr ").filter(function () {
            tText = $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }

     //พิมพ์เอกสาร
     function JSxDBPrintDoc(){
        var aInfor = [
            {"Lang"         : '<?=FCNaHGetLangEdit(); ?>'},
            {"ComCode"      : '<?=FCNtGetCompanyCode(); ?>'},
            {"BranchCode"   : '<?=FCNtGetAddressBranch(@$tDBNBchCode); ?>'},
            {"DocCode"      : '<?=@$tDBNDocNo; ?>'}, // เลขที่เอกสาร
            {"DocBchCode"   : '<?=@$tDBNBchCode;?>'}
        ];
        window.open("<?=base_url(); ?>formreport/Frm_PSInvoiceSale_DN?infor=" + JCNtEnCodeUrlParameter(aInfor), '_blank');
    }

</script>