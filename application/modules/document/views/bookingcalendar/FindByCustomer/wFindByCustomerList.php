
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?=language('document/bookingcalendar/bookingcalendar','tTypeCondition');?></label>
                    <select class="selectpicker form-control" id="ocmSearchTypeCondition" name="ocmSearchTypeCondition" maxlength="1" >
                        <option value="2"><?=language('document/bookingcalendar/bookingcalendar','tTypeCondition2')?></option>
                        <option value="3"><?=language('document/bookingcalendar/bookingcalendar','tTypeCondition3')?></option>
                        <option value="4"><?=language('document/bookingcalendar/bookingcalendar','tTypeCondition4')?></option>
                        <option value="1"><?=language('document/bookingcalendar/bookingcalendar','tTypeCondition1')?></option>
                        <option value="5"><?=language('document/bookingcalendar/bookingcalendar','tTypeCondition5')?></option>
                        <option value="6"><?=language('document/bookingcalendar/bookingcalendar','tTypeCondition6')?></option>
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group" style="width: 100%; display: inline-block;">
                    <label class="xCNLabelFrm" style="width: 100%;">&nbsp;</label>
                    <button class="btn xCNBTNPrimery" style="width:40%" onclick="JSvBKCallFindByCutomerDataTable(1)"><?php echo language('common/main/main', 'tSearch'); ?></button>
                    <button class="btn xCNBTNDefult xCNBTNDefult1Btn" style="width:50%; margin-left: 5px;" onclick="JSvBKClearValueSearchInTab()"><?php echo language('common/main/main', 'ล้างข้อมูลค้นหา'); ?></button>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 xCNCondition1And2">
                <div class="form-group">
                    <label class="xCNLabelFrm xCNDateConditionForm"><?php echo language('document/purchasebranch/purchasebranch','วันที่ต้องเข้าใช้บริการครั้งถัดไป'); ?></label>
                    <div class="input-group">
                        <input
                            class="form-control xCNDatePickerListFrom"
                            type="text"
                            id="oetDateConditionFrom"
                            name="oetDateConditionFrom"
                            autocomplete="off"
                            value="<?=date('Y-m-d');?>"
                        >
                        <span class="input-group-btn" >
                            <button id="obtDateCalendarConditionFrom" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 xCNCondition1And2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('document/purchasebranch/purchasebranch','ถึงวันที่'); ?></label>
                    <div class="input-group">
                        <input
                            class="form-control xCNDatePickerListTo"
                            type="text"
                            id="oetDateConditionTo"
                            name="oetDateConditionTo"
                            autocomplete="off"
                            value="<?=date('Y-m-d');?>"
                        >
                        <span class="input-group-btn" >
                            <button id="obtDateCalendarConditionTo" type="button" class="btn xCNBtnDateTime"><img class="xCNIconCalendar"></button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('document/bookingcalendar/bookingcalendar', 'tBKCustomer') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetFindCusCode" name="oetFindCusCode" value="" >
                        <input type="text" class="form-control" id="oetFindCusName" name="oetFindCusName" readonly  placeholder="<?=language('document/bookingcalendar/bookingcalendar','tBKCustomer');?>">
                        <span class="input-group-btn">
                            <button id="obtFindBrowseCustomer" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?=language('document/bookingcalendar/bookingcalendar','tBKEmail');?></label>
                    <input type="text" class="form-control" maxlength="100" id="oetFindCusEmail" name="oetFindCusEmail" value="" placeholder="<?=language('document/bookingcalendar/bookingcalendar','tBKEmail');?>" 
                    autocomplete="off">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?=language('document/bookingcalendar/bookingcalendar','tBKTelephone');?></label>
                    <input type="text" class="form-control" maxlength="15" id="oetFindCusTel" name="oetFindCusTel" value="" placeholder="<?=language('document/bookingcalendar/bookingcalendar','tBKTelephone');?>" 
                    autocomplete="off">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('document/bookingcalendar/bookingcalendar', 'tBKTitleCarID') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetFindCusCarIDCode" name="oetFindCusCarIDCode" >
                        <input type="text" class="form-control" id="oetFindCusCarIDName" name="oetFindCusCarIDName" readonly placeholder="<?=language('document/bookingcalendar/bookingcalendar','tBKTitleCarID');?>">
                        <span class="input-group-btn">
                            <button id="obtFindBrowseCarID" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="<?= base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div id="odvFindByCustomer"></div>
    </div>
</div>

<script>

    var nLangEdits  = '<?=$this->session->userdata("tLangEdit")?>';

    //วันที่
    $(".xCNDatePickerListFrom").datepicker({
        format          		: 'yyyy-mm-dd',
        todayHighlight  		: true,
        enableOnReadonly		: false,
        disableTouchKeyboard 	: true,
        autoclose       		: true,
        orientation     		: 'bottom' 
    });
    //วันที่
    $(".xCNDatePickerListTo").datepicker({
        format                  : "yyyy-mm-dd",
        todayHighlight  		: true,
        enableOnReadonly        : false,
        startDate               : $('.xCNDatePickerListFrom').val(),
        disableTouchKeyboard    : true,
        autoclose               : true,
        orientation     		: 'bottom' 
    });

    $("document").ready(function () {

        //ทุกครั้งทีเ่ปลี่ยนวันที่เริ่มต้น
        $('.xCNDatePickerListFrom').on('change',function(){

            if($('.xCNDatePickerListTo').val() < $(".xCNDatePickerListFrom").val()){
                var tStartDate = $(".xCNDatePickerListFrom").val();
                $('.xCNDatePickerListTo').val(tStartDate);
            }
            $(".xCNDatePickerListTo").datepicker("destroy");
            $(".xCNDatePickerListTo").datepicker({
                format                  : "yyyy-mm-dd",
                enableOnReadonly        : false,
                startDate               : $('.xCNDatePickerListFrom').val(),
                disableTouchKeyboard    : true,
                autoclose               : true,
                orientation     		: 'bottom' 
            });
            $(".xCNDatePickerListTo").datepicker("refresh");
        });

        $('.selectpicker').selectpicker();	

        //วันที่
        var date    = new Date();
        var d       = ("0" + (date.getDate())).slice(-2);
        var m       = ("0" + (date.getMonth() + 1)).slice(-2);
        var y       = date.getFullYear();

        //วันที่ปัจจุบัน + 7
        var dDateNextSeven = new Date();
            dDateNextSeven.setDate(dDateNextSeven.getDate() + 7);
        var dDateNextSeven   = dDateNextSeven.getFullYear()+'-'+("0" + (dDateNextSeven.getMonth() + 1)).slice(-2)+'-'+("0" + (dDateNextSeven.getDate())).slice(-2); 
        
        //วันที่ปัจจุบัน + 3
        var dDateNextThree = new Date();
            dDateNextThree.setDate(dDateNextThree.getDate() + 3);
        var dDateNextThree   = dDateNextThree.getFullYear()+'-'+("0" + (dDateNextThree.getMonth() + 1)).slice(-2)+'-'+("0" + (dDateNextThree.getDate())).slice(-2); 
        
        //วันที่ปัจจุบัน - 1
        var dDateB4Current = new Date();
            dDateB4Current.setDate(dDateB4Current.getDate() - 1);
        var dDateB4Current   = dDateB4Current.getFullYear()+'-'+("0" + (dDateB4Current.getMonth() + 1)).slice(-2)+'-'+("0" + (dDateB4Current.getDate())).slice(-2); 
        
        //วันที่ปัจจุบัน - 7
        var dDateDeleteSeven = new Date();
            dDateDeleteSeven.setDate(dDateDeleteSeven.getDate() - 7);
        var dDateDeleteSeven   = dDateDeleteSeven.getFullYear()+'-'+("0" + (dDateDeleteSeven.getMonth() + 1)).slice(-2)+'-'+("0" + (dDateDeleteSeven.getDate())).slice(-2); 
      
        var dDateCurrent        = y+'-'+m+'-'+d;
        var dDateNextSeven      = dDateNextSeven; 
        var dDateNextThree      = dDateNextThree; 
        var dDateB4Current      = dDateB4Current; 
        var dDateDeleteSeven    = dDateDeleteSeven; 
        $('#oetDateConditionFrom').val(dDateCurrent);
        $('#oetDateConditionTo').val(dDateNextSeven);

        //เปลี่ยนประเภทการค้นหา
        $('#ocmSearchTypeCondition').on('change', function() {

            //ล้างค่า
            $('#oetFindCusCode').val('');
            $('#oetFindCusName').val('');

            $('#oetFindCusCarIDCode').val('');
            $('#oetFindCusCarIDName').val('');

            $('.xCNCondition1And2').show();
            switch ($(this).val()) {
                case '1':   //ค้นหาเพื่อทำแบบสอบถามหลังบริการ
                    $('.xCNDateConditionForm').text('วันที่');                    
                    $('#oetDateConditionFrom').val(dDateDeleteSeven);
                    $('#oetDateConditionTo').val(dDateB4Current);
                    break;
                case '2':   //ค้นหาลูกค้าเพื่อนัดหมายเข้ารับบริการ
                    $('.xCNDateConditionForm').text('วันที่ต้องเข้าใช้บริการครั้งถัดไป');     
                    $('#oetDateConditionFrom').val(dDateCurrent);
                    $('#oetDateConditionTo').val(dDateNextSeven);
                    break;
                case '3':   //ค้นหาลูกค้าเพื่อยืนยันนัดหมาย
                    $('.xCNDateConditionForm').text('วันที่');     
                    $('#oetDateConditionFrom').val(dDateCurrent);
                    $('#oetDateConditionTo').val(dDateNextThree);
                    break;
                case '4':   //ค้นหาลูกค้าที่ไม่มาตามนัด/ยังไม่ถึงกำหนด
                    $('.xCNDateConditionForm').text('วันที่');     
                    $('#oetDateConditionFrom').val(dDateB4Current);
                    $('#oetDateConditionTo').val(dDateB4Current);
                    break;
                case '5':   //ตรวจสอบสินค้ารอซื้อเพื่อการนัดหมาย
                    $('.xCNDateConditionForm').text('วันที่');     
                    $('#oetDateConditionFrom').val(dDateCurrent);
                    $('#oetDateConditionTo').val(dDateNextSeven);
                    break;
                case '6':   //ค้นหาเอกสารแจ้งเตือนก่อนถึงวันนัด
                    $('.xCNDateConditionForm').text('วันที่');     
                    $('#oetDateConditionFrom').val(dDateCurrent);
                    $('#oetDateConditionTo').val(dDateCurrent);
                    break;
            }

            //โหลดข้อมูล 
            JSvBKCallFindByCutomerDataTable(1);
        });

        JSvBKCallFindByCutomerDataTable(1);
    });

    //วันที่ค้นหา
    $('#obtDateCalendarConditionFrom').unbind().click(function(){
        $('#oetDateConditionFrom').datepicker('show');
    });

    //วันที่ค้นหาจาก
    $('#obtDateCalendarConditionTo').unbind().click(function(){
        $('#oetDateConditionTo').datepicker('show');
    });

    //หน้าจอค้นหา
    function JSvBKCallFindByCutomerDataTable(pnPage){
        var nPageCurrent = pnPage;
        if (nPageCurrent == undefined || nPageCurrent == "") {
            nPageCurrent = "1";
        }
        var tTypeCondition      = $('#ocmSearchTypeCondition option:selected').val();
        var tFindCus            = $('#oetFindCusCode').val();
        var tFindCusEmail       = $('#oetFindCusEmail').val();
        var tFindCusTel         = $('#oetFindCusTel').val();
        var tFindCusCarID       = $('#oetFindCusCarIDCode').val();

        $.ajax({
            type    : "POST",
            url     : "docBookingCalendarCusDatatable",
            data    : {
                'nPage'                 : nPageCurrent,
                'tTypeCondition'        : tTypeCondition,
                'tFindDateFrom'         : $('#oetDateConditionFrom').val(),
                'tFindDateTo'           : $('#oetDateConditionTo').val(),
                'tFindCus'              : tFindCus,
                'tFindCusEmail'         : tFindCusEmail,
                'tFindCusTel'           : tFindCusTel,
                'tFindCusCarID'         : tFindCusCarID
            },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                $("#odvFindByCustomer").html(tResult);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //ล้างข้อมูล
    function JSvBKClearValueSearchInTab(){
        $('#oetFindCusCode , #oetFindCusName').val('');
        $('#oetFindCusEmail').val('');
        $('#oetFindCusTel').val('');
        $('#oetFindCusCarIDCode , #oetFindCusCarIDName').val('');

        JSvBKCallFindByCutomerDataTable(1);
    }

    //เปลี่ยนหน้า Pagenation หน้า Table List Document
    function JSvFindCstClickPage(ptPage) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var nPageCurrent = "";
            switch (ptPage) {
                case "next": //กดปุ่ม Next
                    nPageOld = $(".xWPageFindCst .active").text(); // Get เลขก่อนหน้า
                    nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                    nPageCurrent = nPageNew;
                    break;
                case "previous": //กดปุ่ม Previous
                    nPageOld = $(".xWPageFindCst .active").text(); // Get เลขก่อนหน้า
                    nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                    nPageCurrent = nPageNew;
                    break;
                default:
                    nPageCurrent = ptPage;
            }
            JSvBKCallFindByCutomerDataTable(nPageCurrent);
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    //กดจองจากหน้าลูกค้า
    function JSxBackStepToBooking(poValue){
        var oDate           = new Date();
        var nDay            = ("0" + (oDate.getDate())).slice(-2);
        var nMonth          = ("0" + (oDate.getMonth() + 1)).slice(-2);
        var nYear           = oDate.getFullYear();
        var tTimeStart      = oDate.toTimeString().substr(0, 9);
                              oDate.setSeconds(1800);
        var tTimeEnd        = oDate.toTimeString().substr(0, 9);

        var tTextDateStart  = nYear+'-'+nMonth+'-'+nDay+' '+tTimeStart;
        var tTextDateEnd    = nYear+'-'+nMonth+'-'+nDay+' '+tTimeEnd;
        var tDateStart      = tTextDateStart;
        var tDateEnd        = tTextDateEnd;

        var tFKBayService   = {
                                'id' 		: '' , 
                                'name' 		: '' , 
                                'adcode' 	: $('#ohdBKFindADCode').val() , 
                                'bchcode'   : $('#ohdBKFindBchCode').val() };
        var tDocCode        = 'JUMPBOOKING';
        JSxPopupBookingCalendarList(tDateStart,tDateEnd,tFKBayService,tDocCode,'List',poValue);
    
    }

    //โหลดข้อมูลรถ
    function JSxLoadInformationCar(pnCar){
        $.ajax({
            type    : "POST",
            url     : "docBookingCalendarGetInforCar",
            data    : {
                'pnCar' : pnCar
            },
            cache   : false,
            timeout : 5000,
            success : function (tResult) {
                var oResult = JSON.parse(tResult);

                var rtCarEngineSizeName = '-';
                if(oResult[0].rtCarEngineSizeName != null){ var rtCarEngineSizeName = oResult[0].rtCarEngineSizeName; }

                var rtCarRegNo = '-';
                if(oResult[0].rtCarRegNo != null){ var rtCarRegNo = oResult[0].rtCarRegNo; }

                var rtCarEngineNo = '-';
                if(oResult[0].rtCarEngineNo != null){ var rtCarEngineNo = oResult[0].rtCarEngineNo; }

                var rtCarPowerNumber = '-';
                if(oResult[0].rtCarPowerNumber != null){ var rtCarPowerNumber = oResult[0].rtCarPowerNumber; }

                var rtCarPowerTypeName = '-';
                if(oResult[0].rtCarPowerTypeName != null){ var rtCarPowerTypeName = oResult[0].rtCarPowerTypeName; }

                var rtCarGearName = '-';
                if(oResult[0].rtCarGearName != null){ var rtCarGearName = oResult[0].rtCarGearName; }

                var rtCarCategoryName = '-';
                if(oResult[0].rtCarCategoryName != null){ var rtCarCategoryName = oResult[0].rtCarCategoryName;}

                var rtCarColorName = '-';
                if(oResult[0].rtCarColorName != null){ var rtCarColorName = oResult[0].rtCarColorName; }

                var rtCarModelName = '-';
                if(oResult[0].rtCarModelName != null){ var rtCarModelName = oResult[0].rtCarModelName; }

                var rtCarBrandName = '-';
                if(oResult[0].rtCarBrandName != null){ var rtCarBrandName = oResult[0].rtCarBrandName; }

                var rtCarTypeName = '-';
                if(oResult[0].rtCarTypeName != null){ 
                    var rtCarTypeName = oResult[0].rtCarTypeName; 
                    if(rtCarTypeName.length > 20){
                        var rtCarTypeName = rtCarTypeName.substring(0, 20) + '...'
                    }
                }

                var rtCarDate = '-';
                if(oResult[0].rtCarDate != null){ var rtCarDate = oResult[0].rtCarDate; }

                var rtCarDateOutCar = '-';
                if(oResult[0].rtCarDateOutCar != null){ var rtCarDateOutCar = oResult[0].rtCarDateOutCar; }

                $('.xCNTextRegNumber').text(': '+ rtCarRegNo);
                $('.xCNTextEngineno').text(': ' +rtCarEngineNo);
                $('.xCNTextPowerno').text(': '  +rtCarPowerNumber);
                $('.xCNTextOption8').text(': '  +rtCarCategoryName);
                $('.xCNTextOption7').text(': '  + rtCarEngineSizeName);
                $('.xCNTextOption6').text(': '  +rtCarPowerTypeName);
                $('.xCNTextOption5').text(': '  +rtCarGearName);
                $('.xCNTextOption4').text(': '  +rtCarColorName);
                $('.xCNTextOption3').text(': '  +rtCarModelName);
                $('.xCNTextOption2').text(': '  +rtCarBrandName);
                $('.xCNTextOption1').text(': '  +rtCarTypeName);
                $('.xCNTextStartDate').text(': '+rtCarDate);
                $('.xCNTextEndDate').text(': '  +rtCarDateOutCar);

                //รูปภาพ
                var tImageCar   = oResult[0].FTImgObj;
                
                $('.xCNImageCar').show();
                if(tImageCar == '' || tImageCar == 'NULL'){
                    $('.xCNImageCar').attr('src','<?=base_url().'/application/modules/common/assets/images/logo/fitauto.jpg'?>');
                    $('.xCNImageCar').css('opacity','0.2');
                }else{
                    if(tImageCar.substring(0, 1) == '#'){ //ถ้าเป็นสี
                        $('.xCNImageCar').hide();
                        $('.xCNImageCarInCalendar').append('<div class="text-center xCNImageCarTypeColor"><span style="margin-top: 8px;height:170px;width:400px;background-color:'+tImageCar+';display:inline-block;line-height:2.3;"></span></div>');
                    }else{ //ถ้าเป็นรูปภาพ
                        $('.xCNImageCar').attr('src',tImageCar);
                        $('.xCNImageCar').css('opacity','1');
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    ////////////////////////////// เลือกลูกค้า //////////////////////////////

    //ภาษา
    var nLngID = '<?=$this->session->userdata("tLangEdit")?>';

    //เลือกลูกค้า
    $('#obtFindBrowseCustomer').unbind().click(function(){
        nKeepBrowseMain = 1;
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oBrowseCstOption   = undefined;
            oBrowseCstOption          = oFindCstOptionByTab({
                'tReturnInputCode'  : 'oetFindCusCode',
                'tReturnInputName'  : 'oetFindCusName'
            });
            setTimeout(function(){ 
                JCNxBrowseData('oBrowseCstOption');
            }, 500);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oFindCstOptionByTab      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var oOptionReturn       = {
            Title                   : ['customer/customer/customer', 'tCSTTitle'],
            Table                   : {Master:'TCNMCst', PK:'FTCstCode'},
            Join                    : {
                Table               : ['TCNMCst_L'],
                On                  : ['TCNMCst_L.FTCstCode = TCNMCst.FTCstCode AND TCNMCst_L.FNLngID = '+nLngID]
            },
            Where:{
                Condition           : ["AND TCNMCst.FTCstStaActive = '1' "]
            },
            GrideView:{
                ColumnPathLang      : 'customer/customer/customer',
                ColumnKeyLang       : ['tCSTCode', 'tCSTName'],
                ColumnsSize         : ['15%', '75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMCst.FTCstCode', 'TCNMCst_L.FTCstName','TCNMCst.FTCstCardID','TCNMCst.FTCstTel'],
                DataColumnsFormat   : ['',''],
                DisabledColumns     : [2, 3, 4, 5],
                Perpage             : 10,
                OrderBy             : ['TCNMCst_L.FTCstName ASC']
            },
            CallBack:{
                ReturnType          : 'S',
                Value               : [tInputReturnCode,"TCNMCst.FTCstCode"],
                Text                : [tInputReturnName,"TCNMCst_L.FTCstName"]
            }
        };
        return oOptionReturn;
    }

    ////////////////////////////// เลือกทะเบียนรถ //////////////////////////////
    
    //เลือกรถ
    $('#obtFindBrowseCarID').unbind().click(function(){
        nKeepBrowseMain = 1;
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oBrowseCarOption   = undefined;
            oBrowseCarOption          = oFindCarIDOption({
                'tReturnInputCode'  : 'oetFindCusCarIDCode',
                'tReturnInputName'  : 'oetFindCusCarIDName'
            });
            setTimeout(function(){ 
                JCNxBrowseData('oBrowseCarOption');
            }, 500);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    var oFindCarIDOption      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tValueCusCode       = '';

        if($('#oetFindCusCode').val() == '' || $('#oetFindCusCode').val() == null){
            tValueCusCode = "";
        }else{
            tValueCusCode = "AND TSVMCar.FTCarOwner = '" + $('#oetFindCusCode').val() + "'";
        }

        var oOptionReturn       = {
            Title                   : ['document/bookingcalendar/bookingcalendar', 'tBKTitleCar'],
            Table                   : {Master:'TSVMCar', PK:'FTCarCode'},
            Join    : {
                Table   : ['TCNMCst_L'],
                On      : ["TCNMCst_L.FTCstCode = TSVMCar.FTCarOwner AND TCNMCst_L.FNLngID = "+nLangEdits ]
            },
            Where                   : {
                Condition           : [tValueCusCode]
            },
            GrideView:{
                ColumnPathLang      : 'document/bookingcalendar/bookingcalendar',
                ColumnKeyLang       : ['tBKTitleCarCode', 'tBKTitleCarID', 'tBKOwnerCstName'],
                ColumnsSize         : ['15%', '15%', '60%'],
                WidthModal          : 50,
                DataColumns         : ['TSVMCar.FTCarCode', 'TSVMCar.FTCarRegNo', 'TCNMCst_L.FTCstName'],
                DataColumnsFormat   : ['','',''],
                Perpage             : 10,
                OrderBy             : ['TSVMCar.FTCarCode ASC']
            },
            CallBack:{
                ReturnType          : 'S',
                Value               : [tInputReturnCode,"TSVMCar.FTCarCode"],
                Text                : [tInputReturnName,"TSVMCar.FTCarRegNo"]
            }
        };
        return oOptionReturn;
    }

</script>