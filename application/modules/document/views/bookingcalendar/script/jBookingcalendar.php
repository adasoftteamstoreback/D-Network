<script>

    $("document").ready(function () {
        localStorage.removeItem('ItemDataForCheckAgain');
        JSxCheckPinMenuClose(); 
        JSxBKNavDefult();
        JSvBKCallPageList();
    });

    //control เมนู
    function JSxBKNavDefult() {
        $("#oliBKTitleAdd").hide();
        $("#oliBKTitleEdit").hide();
    }

    //โหลด List
    function JSvBKCallPageList(){
        $.ajax({
            type    : "POST",
            url     : "docBookingCalendarList",
            data    : {},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $("#obtSubmitInvExpExcel").addClass("xCNHide");
                $("#odvContentBK").html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //เปิดหน้าจอตารางงาน (มาจากหน้าตาราง)
    function JSxPopupBookingCalendar(nStartTime,nEndTime,aColumn,tDocCode,tType){
        
        $("#odvModalPopupBookingCalendar .modal-body").html('');

        if(tDocCode == ''){
            var tLangTitleBar = ' - เพิ่ม';
        }else{
            var tLangTitleBar = ' - ตรวจสอบ';
        }

        $('#odvModalPopupBookingCalendar').modal('show');
        $('#odvModalPopupBookingCalendar .xCNTextModalHeard').text('<?= language('document/bookingcalendar/bookingcalendar','tBKTitle') ?>' + tLangTitleBar);

        if(tDocCode == 'JUMPBOOKING' || tType == 'List'){
            var nStartTime = nStartTime;
            var nEndTime   = nEndTime;
        }else{
            var nStartTime = nStartTime.format('YYYY-MM-DD HH:mm:00');
            var nEndTime   = nEndTime.format('YYYY-MM-DD HH:mm:00');
        }
        
        $.ajax({
            type    : "POST",
            url     : "docBookingCalendarPageAdd",
            data    : {'nStartTime' : nStartTime , 'nEndTime' : nEndTime , 'aColumn' : aColumn , 'tDocCode' : tDocCode},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {

                $("#odvModalPopupBookingCalendar .modal-body").html(tResult);

                //load หน้าจอสินค้า
                JSxLoadTablePDTBookingCalendar();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //เปิดหน้าจอตารางงาน (มาจากการ Jump มา)
    function JSxPopupBookingCalendarList(nStartTime,nEndTime,aColumn,tDocCode,tType,poValue){
        JSxCheckPinMenuClose();

        $("#odvModalPopupBookingCalendar .modal-body").html('');

        if(tDocCode == ''){
            var tLangTitleBar = ' - เพิ่ม';
        }else{
            var tLangTitleBar = ' - ตรวจสอบ';
        }

        $('#odvModalPopupBookingCalendar').modal('show');
        $('#odvModalPopupBookingCalendar .xCNTextModalHeard').text('<?= language('document/bookingcalendar/bookingcalendar','tBKTitle') ?>' + tLangTitleBar);

        if(tDocCode == 'JUMPBOOKING' || tType == 'List'){
            var nStartTime = nStartTime;
            var nEndTime   = nEndTime;
        }else{
            var nStartTime = nStartTime.format('YYYY-MM-DD HH:mm:00');
            var nEndTime   = nEndTime.format('YYYY-MM-DD HH:mm:00');
        }
        
        $.ajax({
            type    : "POST",
            url     : "docBookingCalendarPageAdd",
            data    : {'nStartTime' : nStartTime , 'nEndTime' : nEndTime , 'aColumn' : aColumn , 'tDocCode' : tDocCode},
            cache   : false,
            timeout : 5000,
            success : function (tResult) {

                $("#odvModalPopupBookingCalendar .modal-body").html(tResult);

                //ปุ่มของลูกค้า
                $('#obtBKBrowseCustomer').attr('disabled',true)

                //ลูกค้า
                $('#oetBKCusCode').val(poValue.FTCstCode);
                $('#oetBKCusName').val(poValue.FTCstName);

                //รถ
                $('#oetBKCarCode').val(poValue.FTCarCode);
                $('#oetBKCarName').val(poValue.FTCarRegNo);

                //เบอร์
                $('#oetBKTelephone').val(poValue.FTCstTel);

                //อีเมล์
                $('#oetBKEmail').val(poValue.FTCstEmail);

                //โหลดข้อมูลรถ
                JSxLoadInformationCar(poValue.FTCarCode);

                //load หน้าจอสินค้า
                JSxLoadTablePDTBookingCalendar();

                $('.xCNOverlayBooking').delay(1000).fadeOut();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // ปุ่มเรียกใช้สำหรับฟังชั่น export excel
    function JSvBKExcel(pnPage) {
        var data =
        "&ocmSearchTypeCondition="+$("#ocmSearchTypeCondition").val()+
        "&oetDateConditionFrom="+$("#oetDateConditionFrom").val()+
        "&oetDateConditionTo="+$("#oetDateConditionTo").val()+
        "&oetFindCusCode="+$("#oetFindCusCode").val()+
        "&oetFindCusEmail="+$("#oetFindCusEmail").val()+
        "&oetFindCusTel="+$("#oetFindCusTel").val()+
        "&oetFindCusCarIDCode="+$("#oetFindCusCarIDCode").val();

        window.location.href = "docBookingCalendarExportExcel?"+data;
    }
    
    
</script>