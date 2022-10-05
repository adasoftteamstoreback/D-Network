<style>
    .xCNBookingWaitConfirm{
        background  : #ca9d1b;
        border      : 1px solid #c79e25;
        border-radius: 4px;
    }

    .xCNBookingWaitConfirm_text{
        color       : #ca9d1b !important;
        font-weight : bold !important;
    }

    .xCNBookingCancel{
        background  : #e74c3c;
        border      : 1px solid #c79e25;
        border-radius: 4px;
    }

    .xCNBookingCancel_text{
        color       : #e74c3c !important;
        font-weight : bold !important;
    }

    .xCNBookingConfirm{
        background  : #1b9f7f;
        border      : 1px solid #27967b;
        border-radius: 4px;
    }

    .xCNBookingConfirm_text{
        color       : #1b9f7f !important;
        font-weight : bold !important;
    }

    .xCNBookingNotCheck{
        background  : #1b9f7f;
        border      : 1px solid #27967b;
        border-radius: 4px;
    }

    .xCNBookingNotCheck_text{
        color       : #1b9f7f !important;
        font-weight : bold !important;
    }

    #odvHiddenBlockEmptyData{
        margin-top  : 20px;
        position    : absolute;
        width       : 100%;
        height      : 100%;
        z-index     : 5;
        display     : none;
    }
</style>

<div id="odvCalendarBooking"></div>

<script>
    $(document).ready(function() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
		
        //ข้อมูลช่องให้บริการ
        if('<?=$aBayService['rtCode']?>' != 800){
            var oObjectBay      = '<?=json_encode($aBayService['raItems'])?>';
            var oObjectBayJS    = JSON.parse(oObjectBay);
            var aItemsBay          = [];
            for(i=0; i<oObjectBayJS.length; i++){
                var tItems = {
                    'id'        : oObjectBayJS[i]['FTSpsCode'] , 
                    'name'      : oObjectBayJS[i]['FTSpsName'] ,
                    'bchcode'   : oObjectBayJS[i]['FTBchCode'] ,
                    'adcode'    : oObjectBayJS[i]['FTAgnCode'] 
                };
                aItemsBay.push(tItems);
            }
            $('#odvHiddenBlockEmptyData').hide();
        }else{
            var aItemsBay          = [];
            var tItems = {'id' : '0' , 'name' : '<?= language('document/bookingcalendar/bookingcalendar','tBKBayIsNull') ?>' };
            aItemsBay.push(tItems);
            $('#odvHiddenBlockEmptyData').show();
        }

        //ข้อมูลตารางนัดหมาย
        if('<?=$aCalendarService['rtCode']?>' != 800){
            var oObjectBook       = '<?=json_encode($aCalendarService['raItems'])?>';
            var oObjectBooking    = JSON.parse(oObjectBook);
            var aItemsBooking     = [];
            for(i=0; i<oObjectBooking.length; i++){
                var tNameTitleShow = (oObjectBooking[i]['FTCstName'] == '') ? 'ชื่อลูกค้า : ไม่ได้ระบุ ' : 'ชื่อลูกค้า : ' + oObjectBooking[i]['FTCstName'];
                    tNameTitleShow += '\n';
                    tNameTitleShow += (oObjectBooking[i]['FTXshTel'] == '') ? 'เบอร์โทรศัพท์ : ไม่ได้ระบุ ' : 'เบอร์โทรศัพท์ : ' + oObjectBooking[i]['FTXshTel'];
                
                if(oObjectBooking[i]['FTXshStaDoc'] == 3){ //เอกสารยกเลิก
                    var tStatusClassName = 'xCNBookingCancel';
                }else{
                    if(oObjectBooking[i]['FTXshStaPrcDoc'] == ''){ //นัดหมาย (เหลืองสอง)
                        var tStatusClassName = 'xCNBookingWaitConfirm';
                    }else if(oObjectBooking[i]['FTXshStaPrcDoc'] == '1'){ //นัดหมายแล้วรอยืนยัน (เหลือง)
                        var tStatusClassName = 'xCNBookingWaitConfirm';
                    }else if(oObjectBooking[i]['FTXshStaPrcDoc'] == '2'){ //นัดหมายเเละยืนยันเเล้ว (เขียว)
                        var tStatusClassName = 'xCNBookingConfirm';
                    }
                }

				var tItems = {
					title           : '( หมายเลขการจอง : ' + oObjectBooking[i]['FTXshDocNo'] + ' ) ' + tNameTitleShow, 
					start           : new Date(oObjectBooking[i]['FDXshTimeStart']),
					end             : new Date(oObjectBooking[i]['FDXshTimeStop']),
					allDay          : false,
					resources       : [oObjectBooking[i]['FTSpsCode']],
					PKBookingCode   : oObjectBooking[i]['FTXshDocNo'],
					FKBayService    : {
										'id' 		: oObjectBooking[i]['FTSpsCode'] , 
										'name' 		: oObjectBooking[i]['FTSpsName'] , 
										'adcode' 	: oObjectBooking[i]['FTAgnCode'] , 
										'bchcode'   : oObjectBooking[i]['FTBchCode']  },
					className       : [tStatusClassName]
				};

				aItemsBooking.push(tItems);
            }
        }

        if($('#oetDateCalendar').val() == '' || $('#oetDateCalendar').val() == null){
            aDataSearch = date;
        }else{
            aDataSearch = $('#oetDateCalendar').val();
        }

        $('#odvCalendarBooking').fullCalendar({
            defaultDate : aDataSearch,
            monthNames  : [ '<?=language('report/report/report','tRptMonth1')?>',
                                    '<?=language('report/report/report','tRptMonth2')?>',
                                    '<?=language('report/report/report','tRptMonth3')?>',
                                    '<?=language('report/report/report','tRptMonth4')?>',
                                    '<?=language('report/report/report','tRptMonth5')?>',
                                    '<?=language('report/report/report','tRptMonth6')?>',
                                    '<?=language('report/report/report','tRptMonth7')?>',
                                    '<?=language('report/report/report','tRptMonth8')?>',
                                    '<?=language('report/report/report','tRptMonth9')?>',
                                    '<?=language('report/report/report','tRptMonth10')?>',
                                    '<?=language('report/report/report','tRptMonth11')?>',
                                    '<?=language('report/report/report','tRptMonth12')?>' ],
            header: {
                left    : 'prev,next today',
                center  : 'title',
                right   : ''
            },
            buttonText: {
                today   : '<?=language('report/report/report','tRptCurrentDay')?>'
            },
            height          :"1400",
            contentHeight   :"1400",
            defaultView     : 'resourceDay',
            allDaySlot      : false,
            resources       : [
                aItemsBay
            ],
            axisFormat  : 'HH:mm',
            timeFormat  : 'H:mm',
            events      : aItemsBooking,
            selectable    : function(){ false; },
            selectHelper  : true,
            select: function(start, end, ev) {
                JSxPopupBookingCalendar(start,end,ev.data,'','Booking');
            },
            eventClick: function(event) {
                var tDateStart      = event.start;
                var tDateEnd        = event.end;
                var tFKBayService   = event.FKBayService;
                var tDocCode        = event.PKBookingCode;
                JSxPopupBookingCalendar(tDateStart,tDateEnd,tFKBayService,tDocCode,'Booking');
            },
            eventRender: function(event, element) { 

            }
        });
    });
</script>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>